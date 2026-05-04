<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.pages.view-order';

}
