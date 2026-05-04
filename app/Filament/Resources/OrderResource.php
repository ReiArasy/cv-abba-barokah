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
    protected static ?string $modelLabel = 'Order';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'cancelled' => 'Cancelled',
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
                ->label('Customer')
                ->searchable(),

            Tables\Columns\TextColumn::make('total_price')
                ->label('Total')
                ->money('IDR', true),

            Tables\Columns\TextColumn::make('payment_status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'paid' => 'success',
                    'unpaid' => 'warning',
                    'failed' => 'danger',
                }),

            Tables\Columns\TextColumn::make('status')
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
                Tables\Actions\ViewAction::make(),
            ]);
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
