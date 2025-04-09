<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;

class OverviewStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Transaksi', number_format(Transaksi::count()))
                ->description('Jumlah transaksi yang tercatat')
                ->icon('heroicon-o-receipt-refund')
                ->color('success'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format(Transaksi::sum('total_harga')))
                ->description('Akumulasi seluruh penjualan')
                ->icon('heroicon-o-currency-dollar')
                ->color('green'),

            Stat::make('Barang Tersedia', number_format(Barang::where('stok', '>', 0)->count()))
                ->description('Barang yang masih bisa dijual')
                ->icon('heroicon-o-cube')
                ->color('info'),

            Stat::make('Total Pelanggan', number_format(Pelanggan::count()))
                ->description('Pelanggan yang terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Stok Hampir Habis', number_format(Barang::where('stok', '<', 5)->count()))
                ->description('Barang dengan stok < 5 unit')
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
