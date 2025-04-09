<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Columns;
use App\Models\DetailTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationLabel = 'Transaksi'; // Nama di sidebar
    
    protected static ?int $navigationSort = 1; // Urutan menu

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('kode_transaksi')
                ->default(fn () => 'TRX-' . now()->timestamp)
                ->disabled()
                ->prefixIcon('heroicon-m-identification'),
        
            Forms\Components\DateTimePicker::make('tanggal')
                ->default(now())
                ->disabled()
                ->prefixIcon('heroicon-m-calendar-days'),
        
            Forms\Components\Repeater::make('detailTransaksi')
                ->relationship('detailTransaksi')
                ->schema([
                    Forms\Components\Select::make('barang_id')
                        ->relationship('barang', 'nama_barang')
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->options(
                            \App\Models\Barang::where('stok', '>', 0)->pluck('nama_barang', 'id')
                        )
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('harga_satuan', \App\Models\Barang::find($state)?->harga_jual ?? 0)
                        ),
        
                    Forms\Components\TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $barangId = $get('barang_id');
                            $barang = \App\Models\Barang::find($barangId);
                    
                            if ($barang && $state > $barang->stok) {
                                Notification::make()
                                    ->title('Stok tidak mencukupi')
                                    ->body("Stok tersedia hanya {$barang->stok}")
                                    ->danger()
                                    ->send();
                    
                                // Reset jumlah jadi stok maksimal
                                $set('jumlah', $barang->stok);
                            }
                    
                            $set('subtotal', ($get('harga_satuan') ?? 0) * ($state ?? 1));
                        }),
        
                    Forms\Components\TextInput::make('harga_satuan')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                                
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ])
                ->columns(4)
                ->dehydrated()
                ->live()
                ->afterStateUpdated(fn (callable $set, callable $get) =>
                    $set('total_harga', collect($get('detailTransaksi'))->sum('subtotal'))
                ),
        
            Forms\Components\TextInput::make('total_harga')
                ->numeric()
                ->disabled()
                ->prefixIcon('heroicon-m-banknotes'),
        
            Forms\Components\Select::make('pelanggan_id')
                ->label('Pelanggan')
                ->relationship('pelanggan', 'nama')
                ->searchable()
                ->preload()
                ->required()
                ->prefixIcon('heroicon-m-user'),
        
            Forms\Components\Select::make('metode_pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'debit' => 'Debit',
                    'qris' => 'QRIS'
                ])
                ->required()
                ->prefixIcon('heroicon-m-credit-card'),
        
            Forms\Components\TextInput::make('total_bayar')
                ->numeric()
                ->required()
                ->reactive()
                ->minValue(fn (callable $get) => $get('total_harga'))
                ->debounce(500)
                ->prefixIcon('heroicon-m-wallet')
                ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                    $set('kembalian', max(0, ($state ?? 0) - ($get('total_harga') ?? 0)))
                ),
        
            Forms\Components\TextInput::make('kembalian')
                ->numeric()
                ->disabled()
                ->required()
                ->default(0)
                ->prefixIcon('heroicon-m-arrow-uturn-left'),
        ]);
        

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->searchable(),
                TextColumn::make('pelanggan.nama')->label('Pelanggan')->searchable()->sortable(),
                TextColumn::make('tanggal')->dateTime()->sortable(),
                TextColumn::make('total_harga')->money('IDR')->sortable(),
                TextColumn::make('total_bayar')->money('IDR')->sortable(),
                TextColumn::make('kembalian')->money('IDR')->sortable(),
                TextColumn::make('metode_pembayaran')->sortable(),
            ])
            ->actions([
                // ViewAction::make()->url(fn($record) => TransaksiResource::getUrl('view', ['record' => $record->id])),
                Action::make('cetak')
                    ->label('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->action(fn ($record) => self::generatePDF($record)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function generatePDF($record)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang', 'pelanggan')->findOrFail($record->id);

        // Generate QR Code format SVG (tidak butuh imagick)
        $qrCode = base64_encode(
            QrCode::format('svg') // pakai svg, bukan png
                ->size(150)
                ->generate($transaksi->kode_transaksi)
        );

        // Render Blade ke HTML
        $html = View::make('struk', compact('transaksi', 'qrCode'))->render();

        // Generate PDF
        $pdf = Pdf::loadHTML($html);

        // Stream PDF
        return response()->streamDownload(
            fn () => print($pdf->output()),
            "Struk_{$transaksi->kode_transaksi}.pdf"
        );
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
