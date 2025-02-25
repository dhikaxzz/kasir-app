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

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Kelola Pelanggan'; // Nama di sidebar

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->placeholder('Masukkan nama pelanggan'),

                        TextInput::make('no_telpon')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->placeholder('Masukkan nomor telepon')
                            ->unique(ignoreRecord: true),
                    ]),

                    Grid::make(1)->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->placeholder('Masukkan email pelanggan')
                            ->unique(ignoreRecord: true),
                        
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->placeholder('Masukkan alamat pelanggan'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable()->label('Nama Pelanggan'),
                TextColumn::make('no_telpon')->label('No. Telepon')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('alamat')->searchable()->label('Alamat')->limit(50),
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
