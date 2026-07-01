<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Produk', Product::count())
                ->description('Total Produk')
                ->icon('heroicon-o-cube'),

            Stat::make('Customer', User::count())
                ->description('Total Customer')
                ->icon('heroicon-o-users'),

            Stat::make('Transaksi', Order::count())
                ->description('Total Transaksi')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
