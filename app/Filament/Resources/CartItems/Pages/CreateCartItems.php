<?php

namespace App\Filament\Resources\CartItems\Pages;

use App\Filament\Resources\CartItems\CartItemsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCartItems extends CreateRecord
{
    protected static string $resource = CartItemsResource::class;
}
