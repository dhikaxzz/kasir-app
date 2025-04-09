<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TopPelangganChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Pelanggan dengan Pembelian Terbanyak';

    protected function getData(): array
    {
        // Ambil data top pelanggan
        $data = Transaksi::selectRaw('pelanggan_id, COUNT(*) as jumlah_transaksi')
            ->groupBy('pelanggan_id')
            ->orderByDesc('jumlah_transaksi')
            ->with('pelanggan') // agar bisa ambil nama
            ->take(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $data->pluck('jumlah_transaksi'),
                ],
            ],
            'labels' => $data->map(fn ($item) => $item->pelanggan?->nama ?? 'Tidak diketahui'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
