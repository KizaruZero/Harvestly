<?php

namespace App\Filament\Resources\DiscountTargets\Pages;

use App\Filament\Resources\DiscountTargets\DiscountTargetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiscountTarget extends EditRecord
{
    protected static string $resource = DiscountTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
