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

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationLabel = 'Transaksi'; // Nama di sidebar
    
    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1; // Urutan menu

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('kode_transaksi')
                ->unique(ignoreRecord: true)
                ->required(),

            DateTimePicker::make('tanggal')
                ->default(now())
                ->label('Tanggal Transaksi')
                ->disabled(),

            Select::make('pelanggan_id')
                ->relationship('pelanggan', 'nama')
                ->searchable()
                ->required(),

            Repeater::make('detailTransaksi')
                ->relationship('detailTransaksi')
                ->schema([
                    Select::make('barang_id')
                        ->relationship('barang', 'nama_barang')
                        ->searchable()
                        ->required(),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('subtotal', $get('harga_satuan') * $state)
                        )
                        ->required(),

                    TextInput::make('harga_satuan')
                        ->numeric()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('subtotal', $state * $get('jumlah'))
                        )
                        ->required(),

                    TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->required(),
                ]),

            TextInput::make('total_harga')
                ->numeric()
                ->disabled()
                ->default(0),

            TextInput::make('total_bayar')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                    $set('kembalian', $state - $get('total_harga'))
                )
                ->required(),

            TextInput::make('kembalian')
                ->numeric()
                ->disabled()
                ->default(0),

            Select::make('metode_pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'debit' => 'Debit',
                    'qris' => 'QRIS',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->searchable(),
                TextColumn::make('tanggal')->dateTime(),
                TextColumn::make('pelanggan.nama')->label('Pelanggan'),
                TextColumn::make('total_harga')->money('IDR'),
                TextColumn::make('total_bayar')->money('IDR'),
                TextColumn::make('kembalian')->money('IDR'),
                TextColumn::make('metode_pembayaran'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
