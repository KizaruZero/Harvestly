<?php

namespace App\Filament\Resources\DiscountRedemptions\Pages;

use App\Filament\Resources\DiscountRedemptions\DiscountRedemptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiscountRedemptions extends ListRecords
{
    protected static string $resource = DiscountRedemptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
