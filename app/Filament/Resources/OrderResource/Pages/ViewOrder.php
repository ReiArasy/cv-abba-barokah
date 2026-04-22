<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.pages.view-order';

    public function approve(): void
    {
        if ($this->record->payment_status !== 'paid') {
            Notification::make()
                ->title('Order belum dibayar!')
                ->danger()
                ->send();

            return;
        }

        $this->record->update([
            'status' => 'processing',
        ]);

        Notification::make()
            ->title('Selamat, Order berhasil di-approve!')
            ->success()
            ->send();

        $this->redirect(OrderResource::getUrl());
    }

    public function reject(): void
    {
        $this->record->update([
            'status' => 'cancelled',
        ]);

        Notification::make()
            ->title('Order berhasil ditolak.')
            ->danger()
            ->send();

         $this->redirect(OrderResource::getUrl());
    }

    public function ship(): void
    {
        if ($this->record->status !== 'processing') {
            Notification::make()
                ->title('Order belum diproses!')
                ->danger()
                ->send();

            return;
        }

        $this->record->update([
            'status' => 'shipped',
        ]);

        Notification::make()
            ->title('Order berhasil dikirim!')
            ->success()
            ->send();

        $this->redirect(OrderResource::getUrl());
    }
}
