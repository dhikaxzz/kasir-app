<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class TopBarangChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Barang Paling Banyak Dibeli';

    protected function getData(): array
    {
        $data = DB::table('detail_transaksis')
            ->select('barang_id', DB::raw('SUM(jumlah) as total_dibeli'))
            ->groupBy('barang_id')
            ->orderByDesc('total_dibeli')
            ->take(5)
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $barang = Barang::find($item->barang_id);
            $nama = $barang?->nama_barang ?? 'Tidak Diketahui';
            $labels[] = strlen($nama) > 20 ? substr($nama, 0, 20) . '...' : $nama;
            $values[] = $item->total_dibeli;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dibeli',
                    'data' => $values,
                    'backgroundColor' => [
                        '#22c55e', '#3b82f6', '#facc15', '#f97316', '#e11d48',
                    ],
                    'borderRadius' => 8,
                    'tension' => 0.4, // biar line-nya halus
                    'fill' => true,   // biar ada shading di bawah line
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'ticks' => [
                        'maxRotation' => 0, // biar label nggak miring
                        'minRotation' => 0,
                    ],
                ],
            ],
        ];
    }
}
