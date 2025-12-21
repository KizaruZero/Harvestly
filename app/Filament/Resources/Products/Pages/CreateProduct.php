<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract images from form data
        $images = $data['product_images'] ?? [];
        // Remove images from data so it doesn't try to save to products table
        unset($data['product_images']);

        // Store images temporarily to use in afterCreate
        $this->productImages = $images;

        return $data;
    }

    protected array $productImages = [];

    protected function afterCreate(): void
    {
        // Save each image to product_images table
        if (!empty($this->productImages)) {
            foreach ($this->productImages as $index => $imageUrl) {
                $this->record->productImages()->create([
                    'image_url' => $imageUrl,
                    'is_primary' => $index === 0, // First image is primary
                    'is_active' => true,
                    'image_order' => $index,
                ]);
            }
        }
    }
}
