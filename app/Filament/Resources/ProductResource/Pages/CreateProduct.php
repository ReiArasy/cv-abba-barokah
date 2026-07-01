<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\Action; // <-- Ditambahkan untuk mengubah label tombol
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * 1. Mengubah Judul Halaman "Create Produk" -> "Tambah Produk"
     */
    public function getTitle(): string 
    {
        return 'Tambah Produk';
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
     * Menampilkan notifikasi sukses kustom berbahasa Indonesia
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Produk berhasil ditambahkan')
            ->success(); // ->send() dihapus agar tidak muncul dua kali
    }

    /**
     * Mengatur rute pengalihan (redirect) yang benar setelah data berhasil disimpan
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}