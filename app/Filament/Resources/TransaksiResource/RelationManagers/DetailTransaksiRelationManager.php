<?php

namespace App\Filament\Resources\TransaksiResource\RelationManagers;

use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailTransaksiRelationManager extends RelationManager
{
    protected static string $relationship = 'detailTransaksi';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('barang_id')
                ->label('Pilih Barang')
                ->relationship('barang', 'nama_barang') // Menampilkan nama barang, bukan ID
                ->searchable()
                ->preload() // Menampilkan daftar barang langsung
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('harga_satuan', Barang::find($state)?->harga_jual ?? 0)
                ),

            TextInput::make('jumlah')
                ->numeric()
                ->default(1)
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                    $set('subtotal', ($get('harga_satuan') ?? 0) * ($state ?? 1))
                ),

            TextInput::make('harga_satuan')->numeric()->disabled(),
            TextInput::make('subtotal')->numeric()->disabled(),
        ]);

    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.nama_barang')->sortable(),
                TextColumn::make('jumlah')->sortable(),
                TextColumn::make('harga_satuan')->money('IDR')->sortable(),
                TextColumn::make('subtotal')->money('IDR')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
