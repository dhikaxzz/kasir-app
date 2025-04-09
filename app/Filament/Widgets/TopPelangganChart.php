<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaksi;

class TopPelangganChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Pelanggan dengan Pembelian Terbanyak';

    protected function getData(): array
    {
        // Ambil data top pelanggan
        $data = Transaksi::selectRaw('pelanggan_id, COUNT(*) as jumlah_transaksi')
            ->groupBy('pelanggan_id')
            ->orderByDesc('jumlah_transaksi')
            ->with('pelanggan')
            ->take(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $data->pluck('jumlah_transaksi'),
                    'backgroundColor' => [
                        '#16a34a', // Green
                        '#3b82f6', // Blue
                        '#f97316', // Orange
                        '#e11d48', // Rose
                        '#8b5cf6', // Violet
                    ],
                    'borderRadius' => 10,
                ],
            ],
            'labels' => $data->map(fn ($item) => $item->pelanggan?->nama ?? 'Tidak diketahui'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getHeight(): ?int
    {
        return 360;
    }
}
