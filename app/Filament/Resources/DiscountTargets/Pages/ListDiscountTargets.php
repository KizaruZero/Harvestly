<?php

namespace App\Filament\Resources\DiscountTargets\Pages;

use App\Filament\Resources\DiscountTargets\DiscountTargetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiscountTargets extends ListRecords
{
    protected static string $resource = DiscountTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
