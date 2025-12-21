<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Support\Str;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use Filament\Forms\Components\FileUpload;
class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->readOnly()
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('weight_kg')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('product_images')
                    ->directory('products')
                    ->disk('public')
                    ->visibility('public')
                    ->maxFiles(5)
                    ->multiple()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'])
                    ->label('Gambar Produk')
                    ->required()
                    ->helperText('Gambar produk adalah gambar yang akan ditampilkan di halaman produk')
                    ->image()
                    ->previewable()
                    ->downloadable(),
                Fieldset::make('inventories')
                    ->label('Stock & Reserved Stock')
                    ->relationship('inventories')
                    ->schema([
                        TextInput::make('stock')
                            ->label('Stock Quantity')
                            ->numeric()
                            ->required()
                            ->default(0),

                        TextInput::make('reserved_stock')
                            ->label('Reserved Stock (Optional)')
                            ->numeric()
                            ->default(0)
                            ->helperText('Reserved stock adalah stock yang telah direserved untuk pemesanan'),
                    ]),
                TextInput::make('sku')
                    ->label('SKU')
                    ->helperText('SKU adalah kode unik untuk produk'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('IDR '),
                Select::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->preload()
                    ->required()
                    ->multiple()
                    ->helperText('Produk anda dapat memiliki beberapa kategori')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->readOnly()
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('description')
                            ->required(),
                        FileUpload::make('image_category')
                            ->image(),
                        Toggle::make('is_active')
                            ->required(),
                        Toggle::make('is_featured')
                            ->required(),
                        Toggle::make('is_best_seller')
                            ->required(),
                        Toggle::make('is_top_rated')
                            ->required(),
                    ]),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}
