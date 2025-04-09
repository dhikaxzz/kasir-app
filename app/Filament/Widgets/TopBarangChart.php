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
        // Ambil data top barang berdasarkan total jumlah pembelian
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
            $labels[] = $barang?->nama_barang ?? 'Tidak Diketahui';
            $values[] = $item->total_dibeli;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dibeli',
                    'data' => $values,
                    'backgroundColor' => [
                        '#22c55e', // green
                        '#3b82f6', // blue
                        '#facc15', // yellow
                        '#f97316', // orange
                        '#e11d48', // rose
                    ],
                    'borderRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
