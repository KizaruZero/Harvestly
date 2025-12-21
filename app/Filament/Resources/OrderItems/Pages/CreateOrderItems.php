<?php

namespace App\Filament\Resources\OrderItems\Pages;

use App\Filament\Resources\OrderItems\OrderItemsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderItems extends CreateRecord
{
    protected static string $resource = OrderItemsResource::class;
}
