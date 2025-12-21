<?php

namespace App\Filament\Resources\DiscountRedemptions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DiscountRedemptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('discount_id')
                    ->relationship('discount', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'success' => 'Success', 'cancelled' => 'Cancelled'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
