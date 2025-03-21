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
            TextInput::make('kode_transaksi')->disabled(),

            Select::make('pelanggan_id')
                ->relationship('pelanggan', 'nama')
                ->searchable()
                ->required(),

            DateTimePicker::make('tanggal')->default(now()),

            Repeater::make('detailTransaksi')
                ->relationship('detailTransaksi')
                ->schema([
                    Select::make('barang_id')
                        ->relationship('barang', 'nama')
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => 
                            $set('harga_satuan', \App\Models\Barang::find($state)?->harga ?? 0)
                        ),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('subtotal', ($get('harga_satuan') ?? 0) * ($state ?? 0))
                        ),

                    TextInput::make('harga_satuan')->numeric()->disabled(),

                    TextInput::make('subtotal')->numeric()->disabled(),
                ])
                ->columns(4),

            TextInput::make('total_harga')->disabled(),

            Select::make('metode_pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'debit' => 'Debit',
                    'qris' => 'QRIS'
                ])
                ->required(),

            TextInput::make('total_bayar')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                    $set('kembalian', ($state ?? 0) - ($get('total_harga') ?? 0))
                ),

            TextInput::make('kembalian')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->searchable()->sortable(),
                TextColumn::make('tanggal')->dateTime()->sortable(),
                TextColumn::make('total_harga')->money('IDR')->sortable(),
                TextColumn::make('total_bayar')->money('IDR')->sortable(),
                TextColumn::make('metode_pembayaran')->sortable(),
            ])
            ->actions([
                ViewAction::make()->url(fn($record) => TransaksiResource::getUrl('view', ['record' => $record->id])),
                DeleteAction::make(),
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
