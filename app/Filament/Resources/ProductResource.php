<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mengelola Produk';
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';
    protected static ?string $title = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->placeholder('Pilih Kategori...')
                    ->rules(['required']),

                TextInput::make('name')
                    ->label('Nama Produk')
                    ->maxLength(255)
                    ->rules(['required']),
                    
                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->minValue(0)
                    ->rules(['required', 'numeric']),

                TextInput::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->minValue(0)
                    ->rules(['required', 'numeric', 'integer']),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->nullable()
                    ->rules(['required']),

                FileUpload::make('image')
                    ->label('Gambar Produk')

                    // MULTIPLE IMAGE
                    ->multiple()

                    // MAX 3 FILE
                    ->maxFiles(3)

                    // WAJIB - harus pakai ->required() agar Filament tidak menambahkan 'nullable'
                    ->required()
                    ->rules(['required'])

                    // IMAGE ONLY
                    ->image()

                    // VALIDASI MIME
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ])

                    // MAX 2MB
                    ->maxSize(2048)

                    // STORAGE
                    ->disk('public')
                    ->visibility('public')
                    ->directory('products')

                    // PREVIEW
                    ->imagePreviewHeight('150')

                    // PANEL
                    ->panelLayout('grid')

                    // VALIDASI TEXT
                    ->helperText('Upload maksimal 3 gambar. Format: JPG, JPEG, PNG. Maksimal 2MB.'),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->inline(false),
            ]);
    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->label('Gambar')
                    ->getStateUsing(fn ($record) => $record->image[0] ?? null)

                    ->disk('public')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),

                TextColumn::make('stock')
                    ->label('Stok'),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
            ])

            ->filters([

                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),

                TernaryFilter::make('is_active')
                    ->label('Status Produk')
                    ->placeholder('Semua Produk')
                    ->trueLabel('Barang Aktif')
                    ->falseLabel('Barang Tidak Aktif')
                    ->boolean(),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah'), // Mengubah teks tombol aksi 'Edit' menjadi 'Ubah'
            ])

            ->bulkActions([])
            
            // Mengubah teks "No products" dan placeholder "Search"
            ->emptyStateHeading('Tidak Ada Produk')
            ->emptyStateDescription('Belum ada data produk yang ditambahkan.')
            ->searchPlaceholder('Cari Produk...');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}