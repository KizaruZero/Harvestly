<?php

namespace App\Filament\Resources\DiscountRedemptions\Pages;

use App\Filament\Resources\DiscountRedemptions\DiscountRedemptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiscountRedemption extends EditRecord
{
    protected static string $resource = DiscountRedemptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
