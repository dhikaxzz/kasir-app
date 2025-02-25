<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailTransaksiResource\Pages;
use App\Filament\Resources\DetailTransaksiResource\RelationManagers;
use App\Models\DetailTransaksi;
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

class DetailTransaksiResource extends Resource
{
    protected static ?string $model = DetailTransaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('transaksi_id')
                    ->relationship('transaksi', 'kode_transaksi')
                    ->required()
                    ->label('Kode Transaksi'),
                Select::make('barang_id')
                    ->relationship('barang', 'nama_barang')
                    ->required()
                    ->label('Nama Barang'),
                TextInput::make('harga_satuan')
                    ->numeric()
                    ->required()
                    ->label('Harga Satuan'),
                TextInput::make('subtotal')
                    ->numeric()
                    ->required()
                    ->label('Subtotal'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaksi.kode_transaksi')->label('Kode Transaksi')->searchable(),
                TextColumn::make('barang.nama_barang')->label('Nama Barang')->searchable(),
                TextColumn::make('harga_satuan')->money('IDR')->label('Harga Satuan'),
                TextColumn::make('subtotal')->money('IDR')->label('Subtotal'),
            ])
            ->filters([])
            
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
            'index' => Pages\ListDetailTransaksis::route('/'),
            'create' => Pages\CreateDetailTransaksi::route('/create'),
            'edit' => Pages\EditDetailTransaksi::route('/{record}/edit'),
        ];
    }
}
