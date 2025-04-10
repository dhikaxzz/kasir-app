<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TransaksiResource;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Support\Carbon;

class ListTransaksis extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF Laporan Keseluruhan')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->action(function () {
                    $transaksis = \App\Models\Transaksi::with('pelanggan')->latest()->get();

                    // Data untuk Overview
                    $overview = [
                        'total_transaksi' => \App\Models\Transaksi::count(),
                        'total_pendapatan' => \App\Models\Transaksi::sum('total_harga'),
                        'barang_tersedia' => \App\Models\Barang::where('stok', '>', 0)->count(),
                        'total_member' => \App\Models\Pelanggan::count(),
                        'stok_hampir_habis' => \App\Models\Barang::where('stok', '<', 5)->count(),
                        'rata_rata_harian' => function () {
                            $total = \App\Models\Transaksi::count();
                            $first = \App\Models\Transaksi::min('tanggal');
                            $last = \App\Models\Transaksi::max('tanggal');
                            if (!$first || !$last) return 0;
                            $days = \Carbon\Carbon::parse($first)->diffInDays(\Carbon\Carbon::parse($last)) + 1;
                            return $days > 0 ? round($total / $days, 2) : $total;
                        },
                    ];

                    // Top 5 barang
                    $topBarang = DB::table('detail_transaksis')
                        ->select('barang_id', DB::raw('SUM(jumlah) as total_dibeli'))
                        ->groupBy('barang_id')
                        ->orderByDesc('total_dibeli')
                        ->take(5)
                        ->get()
                        ->map(function ($item) {
                            $barang = \App\Models\Barang::find($item->barang_id);
                            return [
                                'nama' => $barang?->nama_barang ?? 'Tidak diketahui',
                                'jumlah' => $item->total_dibeli
                            ];
                        });

                    // Top 5 pelanggan
                    $topPelanggan = \App\Models\Transaksi::selectRaw('pelanggan_id, COUNT(*) as jumlah_transaksi')
                        ->groupBy('pelanggan_id')
                        ->orderByDesc('jumlah_transaksi')
                        ->with('pelanggan')
                        ->take(5)
                        ->get()
                        ->map(fn($item) => [
                            'nama' => $item->pelanggan?->nama ?? 'Tidak diketahui',
                            'jumlah' => $item->jumlah_transaksi
                        ]);

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.transaksi', [
                        'transaksis' => $transaksis,
                        'overview' => $overview,
                        'topBarang' => $topBarang,
                        'topPelanggan' => $topPelanggan,
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'laporan-transaksi.pdf');
                }),
            Actions\CreateAction::make(),

        ];
    }

    
}
