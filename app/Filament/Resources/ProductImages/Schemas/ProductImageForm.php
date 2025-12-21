<?php

namespace App\Filament\Resources\ProductImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                FileUpload::make('image_url')
                    ->image()
                    ->required(),
                Toggle::make('is_primary')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                FileUpload::make('image_order')
                    ->image()
                    ->default(0),
            ]);
    }
}
