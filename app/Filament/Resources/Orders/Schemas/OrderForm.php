<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('metode_pembayaran'),
                TextInput::make('subtotal_price')
                    ->required()
                    ->numeric(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'paid' => 'Paid',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            'packed' => 'Packed',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
        ])
                    ->default('pending')
                    ->required(),
                Select::make('address_id')
                    ->relationship('address', 'id')
                    ->required(),
                Select::make('discount_id')
                    ->relationship('discount', 'id')
                    ->required(),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('promo_code_snapshot'),
                TextInput::make('shipping_method'),
                TextInput::make('shipping_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('notes'),
            ]);
    }
}
