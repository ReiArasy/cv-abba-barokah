<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Produk berhasil diperbarui!')
            ->success()
            ->send();

            $this->redirect(ProductResource::getUrl());
    }
}
