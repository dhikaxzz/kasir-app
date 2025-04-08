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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BarangResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangResource\RelationManagers;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Kelola Barang';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
{
    return $form->schema([
        Section::make('Informasi Barang')
            ->description('Masukkan data utama barang secara lengkap.')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('kode_barang')
                        ->label('Kode Barang')
                        ->prefixIcon('heroicon-o-tag')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->placeholder('Contoh: BRG-001'),

                    TextInput::make('nama_barang')
                        ->label('Nama Barang')
                        ->prefixIcon('heroicon-o-archive-box')
                        ->required()
                        ->placeholder('Nama lengkap barang'),

                    TextInput::make('merek')
                        ->label('Merek')
                        ->prefixIcon('heroicon-o-cube')
                        ->placeholder('Contoh: Zinc Shampoo'),

                    TextInput::make('varian')
                        ->label('Varian')
                        ->prefixIcon('heroicon-o-bars-3-bottom-left')
                        ->placeholder('Contoh: Active Fresh, Cool Booster'),

                    Select::make('kategori_id')
                        ->label('Kategori')
                        ->prefixIcon('heroicon-o-folder')
                        ->relationship('kategori', 'nama')
                        ->placeholder('Pilih Kategori dari Manajemen Kategori')
                        ->preload()
                        ->searchable()
                        ->required(),

                    Select::make('satuan')
                        ->label('Satuan')
                        ->prefixIcon('heroicon-o-scale')
                        ->placeholder('Pilih Satuan Barang')
                        ->options([
                            'pcs' => 'PCS',
                            'kg' => 'Kg',
                            'liter' => 'Liter',
                        ])
                        ->required(),
                ]),
            ])
            ->columns(1)
            ->collapsible(),

        Section::make('Harga & Stok')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('harga_jual')
                        ->label('Harga Jual')
                        ->prefixIcon('heroicon-o-currency-dollar')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),

                    TextInput::make('stok')
                        ->label('Stok')
                        ->prefixIcon('heroicon-o-square-3-stack-3d')
                        ->numeric()
                        ->required(),
                ]),
            ])
            ->columns(1)
            ->collapsible(),

        Section::make('Lokasi & Expired')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('lokasi_rak')
                        ->label('Lokasi Rak')
                        ->prefixIcon('heroicon-o-map')
                        ->placeholder('Contoh: Rak A1, B2'),

                    DatePicker::make('expired_date')
                        ->label('Tanggal Kedaluwarsa')
                        ->prefixIcon('heroicon-o-calendar')
                        ->nullable()
                        ->displayFormat('d M Y'),
                ]),
            ])
            ->columns(1)
            ->collapsible(),
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
                TextColumn::make('kategori.nama') // ✅ Menampilkan kategori barang
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
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
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama'),
            ])
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
