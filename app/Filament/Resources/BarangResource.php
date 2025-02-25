<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode_barang')
                ->unique(ignoreRecord: true)
                ->required()
                ->label('Kode Barang')
                ->placeholder('Masukkan kode barang unik'),
            
            TextInput::make('nama_barang')
                ->required()
                ->label('Nama Barang')
                ->placeholder('Masukkan nama barang'),

            Select::make('kategori')
                ->label('Kategori')
                ->options([
                    'Elektronik' => 'Elektronik',
                    'Makanan' => 'Makanan',
                    'Minuman' => 'Minuman',
                    'Produk' => 'Pakaian',
                    'Produk Rumah Tangga' => 'Produk Rumah Tangga',
                    'Aksesoris' => 'Aksesoris',
                    'Perlengkapan Sekolah' => 'Perlengkapan Sekolah',
                ])
                ->required(),

            TextInput::make('merek')
                ->label('Merek')
                ->placeholder('Masukkan merek barang'),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->placeholder('Deskripsi barang'),

            Select::make('satuan')
                ->label('Satuan')
                ->options([
                    'pcs' => 'PCS',
                    'kg' => 'KG',
                    'liter' => 'LITER',
                ])
                ->required(),

            TextInput::make('harga_jual')
                ->label('Harga Jual')
                ->prefix('Rp')
                ->numeric()
                ->required(),

            TextInput::make('lokasi_barang')
                ->label('Lokasi Barang')
                ->placeholder('Rak, gudang, dll.'),

            DatePicker::make('tanggal_kadaluarsa')
                ->label('Tanggal Kadaluarsa')
                ->required(),

            Select::make('status')
                ->label('Status Barang')
                ->options([
                    'aktif' => 'Aktif',
                    'nonaktif' => 'Nonaktif',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->searchable(),
                TextColumn::make('nama_barang')->searchable(),
                TextColumn::make('kategori')->searchable(),
                TextColumn::make('merek')->searchable(),
                TextColumn::make('satuan'),
                TextColumn::make('harga_jual')->money('IDR'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state === 'aktif' ? 'success' : 'danger')
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
