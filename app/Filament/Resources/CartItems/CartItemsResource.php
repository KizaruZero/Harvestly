<?php

namespace App\Filament\Resources\CartItems;

use App\Filament\Resources\CartItems\Pages\CreateCartItems;
use App\Filament\Resources\CartItems\Pages\EditCartItems;
use App\Filament\Resources\CartItems\Pages\ListCartItems;
use App\Filament\Resources\CartItems\Schemas\CartItemsForm;
use App\Filament\Resources\CartItems\Tables\CartItemsTable;
use App\Models\CartItems;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CartItemsResource extends Resource
{
    protected static ?string $model = CartItems::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CartItemsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CartItemsTable::configure($table);
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
            'index' => ListCartItems::route('/'),
            'create' => CreateCartItems::route('/create'),
            'edit' => EditCartItems::route('/{record}/edit'),
        ];
    }
}
