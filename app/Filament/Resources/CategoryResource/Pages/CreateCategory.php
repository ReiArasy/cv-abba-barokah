<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Kategori berhasil ditambahkan')
            ->success()
            ->send();

            $this->redirect(CategoryResource::getUrl());
    }

}


