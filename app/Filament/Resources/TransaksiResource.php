<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use App\Models\Barang;


class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationLabel = 'Transaksi'; // Nama di sidebar
    
    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1; // Urutan menu

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('kode_transaksi')
                ->default(fn () => 'TRX-' . now()->timestamp)
                ->disabled(),

            Forms\Components\DateTimePicker::make('tanggal')
                ->default(now())
                ->disabled(),

                Forms\Components\Repeater::make('detailTransaksi')
                    ->relationship('detailTransaksi')
                    ->schema([
                        Forms\Components\Select::make('barang_id')
                            ->relationship('barang', 'nama_barang')
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) =>
                                $set('harga_satuan', Barang::find($state)?->harga_jual ?? 0)
                            ),
                
                        Forms\Components\TextInput::make('jumlah')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('subtotal', ($get('harga_satuan') ?? 0) * ($state ?? 1))
                            ),
                
                        Forms\Components\TextInput::make('harga_satuan')->numeric()->disabled()->dehydrated(),
                
                        Forms\Components\TextInput::make('subtotal')->numeric()->disabled()->dehydrated(),
                    ])
                    ->columns(4)
                    ->dehydrated()
                    ->live() // UPDATE otomatis tanpa harus klik "Add Detail"
                    ->afterStateUpdated(fn (callable $set, callable $get) =>
                        $set('total_harga', collect($get('detailTransaksi'))->sum('subtotal'))
                    ),

            Forms\Components\TextInput::make('total_harga')
                ->numeric()
                ->disabled(),

            Forms\Components\Select::make('metode_pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'debit' => 'Debit',
                    'qris' => 'QRIS'
                ])
                ->required(),

            Forms\Components\TextInput::make('total_bayar')
                ->numeric()
                ->required()
                ->reactive()
                ->minValue(fn (callable $get) => $get('total_harga')) // Bayar harus cukup
                ->debounce(500) // Cegah perhitungan terlalu sering
                ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                    $set('kembalian', max(0, ($state ?? 0) - ($get('total_harga') ?? 0)))
                ),

            Forms\Components\TextInput::make('kembalian')
                ->numeric()
                ->disabled()
                ->required()
                ->default(0),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->searchable(),
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
        $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($record->id);

        // Render Blade ke HTML
        $html = View::make('struk', compact('transaksi'))->render();

        // Generate PDF
        $pdf = Pdf::loadHTML($html);

        // Download atau Stream PDF
        return response()->streamDownload(fn () => print($pdf->output()), "Struk_{$transaksi->kode_transaksi}.pdf");
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
