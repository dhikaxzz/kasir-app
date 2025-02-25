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
        return $form
        ->schema([
            TextInput::make('kode_barang')->unique()->required(),
            TextInput::make('nama_barang')->required(),
            TextInput::make('kategori'),
            TextInput::make('merek'),
            Textarea::make('deskripsi'),
            Select::make('satuan')
                ->options([
                    'pcs' => 'PCS',
                    'kg' => 'KG',
                    'liter' => 'LITER',
                ])
                ->required(),
            TextInput::make('harga_jual')->numeric()->required(),
            TextInput::make('lokasi_barang'),
            DatePicker::make('tanggal_kadaluarsa')
                ->label('Tanggal Kadaluarsa')
                ->required(),
            Select::make('status')
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
                TextColumn::make('kategori'),
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
