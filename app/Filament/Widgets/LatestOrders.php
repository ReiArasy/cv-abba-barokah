<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;

class LatestOrders extends Widget
{
    protected static string $view = 'filament.widgets.latest-orders';

    protected function getViewData(): array
    {
        return [
            'orders' => Order::latest()->take(5)->get(),
        ];
    }
}
