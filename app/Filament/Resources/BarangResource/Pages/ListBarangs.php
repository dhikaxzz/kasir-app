<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangs extends ListRecords
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('scanQr')
            ->label('Scan QR')
            ->icon('heroicon-o-camera')
            ->action(fn () => null)
            ->modalHeading('Scan QR Kode Barang')
            ->modalSubmitAction(false)
            ->modalContent(view('scan-barang')),
            Actions\CreateAction::make(),
        ];
    }
}
