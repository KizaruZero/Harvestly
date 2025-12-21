<?php

namespace App\Filament\Resources\ProductImages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
class ProductImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image_url')
                    ->disk('public')
                    ->visibility('public'),
                IconColumn::make('is_primary')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('image_order')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('product_id')
                    ->relationship('product', 'name')
                    ->preload()
                    ->label('Product'),
                SelectFilter::make('product.categories.name')
                    ->relationship('product.categories', 'name')
                    ->preload()
                    ->label('Category'),
                SelectFilter::make('is_primary')
                    ->label('Is Primary')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Is Active')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
