<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('reserved_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
