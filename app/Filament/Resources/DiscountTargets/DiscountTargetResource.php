<?php

namespace App\Filament\Resources\DiscountTargets;

use App\Filament\Resources\DiscountTargets\Pages\CreateDiscountTarget;
use App\Filament\Resources\DiscountTargets\Pages\EditDiscountTarget;
use App\Filament\Resources\DiscountTargets\Pages\ListDiscountTargets;
use App\Filament\Resources\DiscountTargets\Schemas\DiscountTargetForm;
use App\Filament\Resources\DiscountTargets\Tables\DiscountTargetsTable;
use App\Models\DiscountTarget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscountTargetResource extends Resource
{
    protected static ?string $model = DiscountTarget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ReceiptPercent;

    public static function getNavigationGroup(): ?string
    {
        return 'Discounts Management';
    }

    public static function getNavigationLabel(): string
    {
        return 'Discount Targets';
    }
    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return DiscountTargetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountTargetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscountTargets::route('/'),
            'create' => CreateDiscountTarget::route('/create'),
            'edit' => EditDiscountTarget::route('/{record}/edit'),
        ];
    }
}
