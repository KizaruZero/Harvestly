<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                // TextColumn::make('slug')
                //     ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                // TextColumn::make('weight_kg')
                //     ->numeric()
                //     ->sortable(),
                ImageColumn::make('primary_image')
                    ->label('Image')
                    ->disk('public')
                    ->visibility('public')
                    ->getStateUsing(function (Product $record): ?string {
                        $record->loadMissing('productImages');
                        $primaryImage = $record->productImages
                            ->where('is_primary', true)
                            ->where('is_active', true)
                            ->first();
                        if (!$primaryImage) {
                            $primaryImage = $record->productImages
                                ->where('is_active', true)
                                ->sortBy('image_order')
                                ->first();
                        }
                        return $primaryImage?->image_url;
                    })
                    ->defaultImageUrl(null),
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->default(true)
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->default(false)
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->preload()
                    ->searchable()
                    ->multiple(),
                TernaryFilter::make('is_featured')
                    ->label('Is Featured')
                    ->placeholder('All products')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
                Filter::make('price')
                    ->Schema([
                        TextInput::make('min_price')
                            ->label('Min Price')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->live(onBlur: true),
                        TextInput::make('max_price')
                            ->label('Max Price')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->minValue($data['min_price'] ?? 0),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['min_price'], function (Builder $query, $value) {
                            return $query->where('price', '>=', $value);
                        })->when($data['max_price'], function (Builder $query, $value) {
                            return $query->where('price', '<=', $value);
                        });
                    })
            ])
            ->recordActions([
                // Action::make('set_categories')
                //     ->label('Set Categories')
                //     ->icon('heroicon-o-tag')
                //     ->color('info')
                //     ->form([
                //         Select::make('categories')
                //             ->label('Categories')
                //             ->relationship('categories', 'name')
                //             ->preload()
                //             ->searchable()
                //             ->multiple()
                //             ->required()
                //             ->helperText('Set categories for the product')
                //             ->getOptionLabelFromRecordUsing(fn($record) => $record->name),
                //     ]),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
