<?php

namespace App\Filament\Resources\DiscountRedemptions;

use App\Filament\Resources\DiscountRedemptions\Pages\CreateDiscountRedemption;
use App\Filament\Resources\DiscountRedemptions\Pages\EditDiscountRedemption;
use App\Filament\Resources\DiscountRedemptions\Pages\ListDiscountRedemptions;
use App\Filament\Resources\DiscountRedemptions\Schemas\DiscountRedemptionForm;
use App\Filament\Resources\DiscountRedemptions\Tables\DiscountRedemptionsTable;
use App\Models\DiscountRedemption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscountRedemptionResource extends Resource
{
    protected static ?string $model = DiscountRedemption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static function getNavigationGroup(): ?string
    {
        return 'Discounts Management';
    }

    public static function getNavigationLabel(): string
    {
        return 'Discount Redemptions';
    }

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return DiscountRedemptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountRedemptionsTable::configure($table);
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
            'index' => ListDiscountRedemptions::route('/'),
            'create' => CreateDiscountRedemption::route('/create'),
            'edit' => EditDiscountRedemption::route('/{record}/edit'),
        ];
    }
}
