<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationLabel = 'Transaksi'; // Nama di sidebar
    
    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1; // Urutan menu

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('kode_transaksi')->unique(ignoreRecord: true)->required(),
            
            DateTimePicker::make('tanggal')
                ->default(now())
                ->label('Tanggal Transaksi')
                ->disabled(),

            TextInput::make('total_harga')->numeric()->required(),
            TextInput::make('total_bayar')->numeric()->required(),

            Select::make('metode_pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'debit' => 'Debit',
                    'qris' => 'QRIS',
                ])
                ->required(),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->searchable(),
                TextColumn::make('tanggal')->dateTime(),
                TextColumn::make('total_harga')->money('IDR'),
                TextColumn::make('metode_pembayaran'),
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
