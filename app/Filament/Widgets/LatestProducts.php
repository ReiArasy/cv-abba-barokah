<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Product;

class LatestProducts extends Widget
{
    protected static string $view = 'filament.widgets.latest-products';

    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'products' => Product::latest()->take(5)->get(),
        ];
        
    }
}
