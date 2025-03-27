<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BarangResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangResource\RelationManagers;


class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Manajemen Barang'; // Nama di sidebar

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
                TextInput::make('kode_barang')
                    ->label('Kode Barang')
                    ->unique()
                    ->required(),
                TextInput::make('nama_barang')->required(),
                TextInput::make('merek'),
                TextInput::make('varian'),
                TextInput::make('harga_jual')->required()->numeric(),
                Select::make('satuan')
                    ->options(['pcs' => 'PCS', 'kg' => 'Kg', 'liter' => 'Liter'])
                    ->required(),
                TextInput::make('stok')->required()->numeric(),
                TextInput::make('lokasi_rak'),
                DatePicker::make('expired_date')->nullable(),
        ]);

        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->searchable(),
                TextColumn::make('nama_barang')->searchable(),
                TextColumn::make('merek')->sortable()->searchable(),
                TextColumn::make('varian')->sortable()->searchable(),
                TextColumn::make('lokasi_rak')->sortable()->searchable(),
                TextColumn::make('harga_jual')->money('IDR'),
                TextColumn::make('satuan')->sortable()->colors([
                    'primary' => 'pcs',
                    'success' => 'kg',
                    'warning' => 'liter',
                ]),
                TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('expired_date')->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
