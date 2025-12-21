<?php

namespace App\Filament\Resources\Discounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use App\Filament\Resources\DiscountTargets\Schemas\DiscountTargetForm;
use Illuminate\Support\Str;
use App\Models\Discount;



class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Discount Code')
                    ->required()
                    ->maxLength(12)
                    ->unique(ignoreRecord: true)
                    ->default(function () {
                        // Generate random code 12 karakter (huruf besar + angka)
                        do {
                            $code = strtoupper(Str::random(12));
                        } while (Discount::where('code', $code)->exists());

                        return $code;
                    })
                    ->helperText('Kode discount akan di-generate otomatis (maksimal 12 karakter). Anda bisa mengubahnya jika diperlukan.')
                    ->suffixAction(
                        \Filament\Actions\Action::make('regenerate')
                            ->icon('heroicon-o-arrow-path')
                            ->action(function (Set $set) {
                                do {
                                    $code = strtoupper(Str::random(12));
                                } while (Discount::where('code', $code)->exists());

                                $set('code', $code);
                            })
                            ->tooltip('Generate kode baru')
                    ),

                Select::make('discount_type')
                    ->options(['percentage' => 'Percentage', 'fixed' => 'Fixed'])
                    ->required()
                    ->live(),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(function ($get) {
                        return $get('discount_type') === 'percentage' ? 100 : null;
                    })
                    ->prefix(function ($get) {
                        return $get('discount_type') === 'percentage' ? null : 'IDR ';
                    })
                    ->suffix(function ($get) {
                        return $get('discount_type') === 'percentage' ? '%' : null;
                    }),
                TextInput::make('minimum_order_amount')
                    ->required()
                    ->prefix('IDR ')
                    ->numeric(),
                TextInput::make('max_usage')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_usage_per_user')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Section::make('Discount Targets')
                    ->description('Target yang akan diterapkan pada discount')
                    ->schema([
                        Fieldset::make('discount_targets')
                            ->label('Discount Targets')
                            ->relationship('discountTarget')
                            ->schema([
                                Select::make('target_type')
                                    ->options(['product' => 'Product', 'category' => 'Category', 'user' => 'User'])
                                    ->required()
                                    ->live(),
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->visible(fn($get) => $get('target_type') === 'product')
                                    ->required(fn($get) => $get('target_type') === 'product')
                                    ->default(null)
                                    ->dehydrateStateUsing(fn($get, $state) => $get('target_type') === 'product' ? $state : null),
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->visible(fn($get) => $get('target_type') === 'category')
                                    ->required(fn($get) => $get('target_type') === 'category')
                                    ->default(null)
                                    ->dehydrateStateUsing(fn($get, $state) => $get('target_type') === 'category' ? $state : null),
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->visible(fn($get) => $get('target_type') === 'user')
                                    ->required(fn($get) => $get('target_type') === 'user')
                                    ->default(null)
                                    ->dehydrateStateUsing(fn($get, $state) => $get('target_type') === 'user' ? $state : null),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->required(),
                            ]),
                    ]),
            ]);

    }
}
