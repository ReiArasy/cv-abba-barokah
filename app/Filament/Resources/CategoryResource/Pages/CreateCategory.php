<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions\Action; // <-- Tambahkan ini untuk mengubah label tombol
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    
    /**
     * 1. Mengubah Judul Halaman "Create Kategori Produk" -> "Tambah Kategori Produk"
     */
    public function getTitle(): string 
    {
        return 'Tambah Kategori Produk';
    }

    /**
     * 2. Mengubah Tombol Utama "Create" -> "Simpan"
     */
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan');
    }

    /**
     * 3. Mengubah Tombol "Create & create another" -> "Simpan & Tambah Lagi"
     */
    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Tambah Lagi');
    }

    /**
     * 4. Mengubah Tombol "Cancel" -> "Batal"
     */
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }

    /**
     * Notifikasi sukses ketika data berhasil ditambahkan
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Kategori berhasil ditambahkan')
            ->success(); // hapus ->send() di sini agar tidak muncul double notifikasi
    }

    /**
     * Cara redirect yang benar di Filament setelah klik tombol Simpan
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}