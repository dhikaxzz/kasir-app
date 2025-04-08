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

    protected static ?string $navigationLabel = 'Detail Transaksi'; // Nama di sidebar

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2; // Urutan menu

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('transaksi_id')
                    ->relationship('transaksi', 'kode_transaksi')
                    ->searchable()
                    ->required(),

                Select::make('barang_id')
                    ->relationship('barang', 'nama_barang')
                    ->searchable()
                    ->required(),

                TextInput::make('jumlah')->numeric()->required(),

                TextInput::make('harga_satuan')->numeric()->required(),

                TextInput::make('subtotal')->numeric()->disabled(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaksi.kode_transaksi')->sortable(),
                TextColumn::make('barang.nama_barang')->sortable(),
                TextColumn::make('jumlah')->sortable(),
                TextColumn::make('harga_satuan')->money('IDR')->sortable(),
                TextColumn::make('subtotal')->money('IDR')->sortable(),
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

    public static function shouldRegisterNavigation(): bool
    {
        return false;
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
