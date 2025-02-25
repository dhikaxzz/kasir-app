<?php

namespace App\Filament\Resources\TransaksiResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class DetailTransaksiRelationManager extends RelationManager
{
    protected static string $relationship = 'detailTransaksi';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.nama')->label('Nama Barang')->sortable(),
                TextColumn::make('harga_satuan')->label('Harga')->money('IDR')->sortable(),
            ]);
    }
}
