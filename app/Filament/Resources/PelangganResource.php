<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use App\Models\Pembeli;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Colors\Color;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Kelola Pelanggan'; // Nama di sidebar

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Pelanggan')
                ->description('Masukkan data pelanggan.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nama')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->placeholder('Masukkan nama pelanggan')
                            ->prefixIcon('heroicon-m-user'),
        
                        TextInput::make('no_telpon')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->placeholder('Masukkan nomor telepon')
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-phone'),
                    ]),
        
                    Grid::make(1)->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->placeholder('Masukkan email pelanggan')
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-envelope'),
        
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->placeholder('Masukkan alamat pelanggan')
                            ->prefixIcon('heroicon-m-map'),
        
                        Select::make('member')
                            ->options([true => 'Ya', false => 'Tidak'])
                            ->required()
                            ->prefixIcon('heroicon-m-check-circle'),
                    ]),

                    Grid::make(2)->schema([
                        Select::make('loyalty_level')
                            ->label('Level Membership')
                            ->options([
                                'regular' => 'Regular',
                                'silver' => 'Silver',
                                'gold' => 'Gold',
                                'platinum' => 'Platinum',
                            ])
                            ->default('regular')
                            ->required()
                            ->prefixIcon('heroicon-m-star'),
                    
                        TextInput::make('total_transaksi')
                            ->label('Total Transaksi')
                            ->numeric()
                            ->default(0)
                            ->disabled() // biar gak diubah manual
                            ->dehydrated(false), // biar gak ikut form submit
                    ]),
                    
                ]),
        ]);        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable()->label('Nama Pelanggan')->sortable(),
                TextColumn::make('no_telpon')->label('No. Telepon')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
                TextColumn::make('alamat')->searchable()->label('Alamat')->limit(25)->sortable(),
                TextColumn::make('loyalty_level')
                    ->label('Membership')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'platinum' => Color::Gray,
                        'gold' => Color::Amber,
                        'silver' => Color::Sky,
                        'regular' => Color::Slate,
                    }),
                TextColumn::make('total_transaksi')
                    ->label('Total Transaksi')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
