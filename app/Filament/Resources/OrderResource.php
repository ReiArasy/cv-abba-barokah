<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $navigationLabel = 'Manage Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    // Menambahkan plural label agar teks "Pesanans" di title dan breadcrumb berubah menjadi "Pesanan"
    protected static ?string $pluralModelLabel = 'Pesanan'; 
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pesanan') // Mengubah 'Order Details' -> 'Detail Pesanan'
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Tertunda',     // Mengubah 'Pending' -> 'Tertunda'
                                'paid' => 'Dibayar',         // Mengubah 'Paid' -> 'Dibayar'
                                'processing' => 'Diproses',  // Mengubah 'Processing' -> 'Diproses'
                                'shipped' => 'Dikirim',      // Mengubah 'Shipped' -> 'Dikirim'
                                'cancelled' => 'Dibatalkan', // Mengubah 'Cancelled' -> 'Dibatalkan'
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
            Tables\Columns\TextColumn::make('code')
                ->label('Kode Pesanan')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('user.name')
                ->label('Pelanggan') // Mengubah 'Customer' -> 'Pelanggan'
                ->searchable(),

            Tables\Columns\TextColumn::make('total_price')
                ->label('Total')
                ->money('IDR', true),

            Tables\Columns\TextColumn::make('payment_status')
                ->label('Status Pembayaran') // Menambahkan label Bahasa Indonesia
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'paid' => 'success',
                    'unpaid' => 'warning',
                    'failed' => 'danger',
                }),

            Tables\Columns\TextColumn::make('status')
                ->label('Status Pesanan') // Menambahkan label Bahasa Indonesia
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'pending' => 'warning',
                    'processing' => 'primary',
                    'shipped' => 'success',
                    'cancelled' => 'danger',
                }),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('d M Y'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'), // Mengubah tombol 'View' -> 'Lihat'
            ])
            // Menambahkan pengaturan bahasa khusus jika data tabel kosong & placeholder pencarian
            ->emptyStateHeading('Tidak Ada Pesanan')
            ->emptyStateDescription('Belum ada data transaksi pesanan yang masuk.')
            ->searchPlaceholder('Cari Pesanan...');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'items.product', 'payment']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}