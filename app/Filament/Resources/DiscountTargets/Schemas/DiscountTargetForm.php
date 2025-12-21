<?php

namespace App\Filament\Resources\DiscountTargets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiscountTargetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('discount_id')
                    ->relationship('discount', 'code')
                    ->required()
                    ->helperText('Discount yang akan diterapkan pada target'),
                Select::make('target_type')
                    ->options(['product' => 'Product', 'category' => 'Category', 'user' => 'User'])
                    ->required()
                    ->helperText('Target yang akan diterapkan pada discount')
                    ->live()
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->visible(fn($get) => $get('target_type') === 'product')
                    ->required(fn($get) => $get('target_type') === 'product')
                    ->helperText('Product yang akan diterapkan pada target'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->visible(fn($get) => $get('target_type') === 'category')
                    ->required(fn($get) => $get('target_type') === 'category')
                    ->helperText('Category yang akan diterapkan pada target'),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(fn($get) => $get('target_type') === 'user')
                    ->visible(fn($get) => $get('target_type') === 'user')
                    ->helperText('User yang akan diterapkan pada target'),
                Toggle::make('is_active')
                    ->required()
                    ->helperText('Status aktif/tidak aktif target'),
            ]);
    }
}
