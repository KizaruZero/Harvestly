<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        $this->record->load('productImages');

        $existingImages = $this->record->productImages
            ->sortBy('image_order')
            ->pluck('image_url')
            ->filter(fn($url) => !empty($url))
            ->map(function ($url) {
                // Remove any leading slashes or storage paths
                // FileUpload expects path relative to storage root (e.g., "products/image.jpg")
                $url = ltrim($url, '/');
                // Remove storage/ prefix if present
                $url = preg_replace('#^storage/#', '', $url);
                // Remove public/ prefix if present
                $url = preg_replace('#^public/#', '', $url);
                return $url;
            })
            ->filter()
            ->values()
            ->toArray();

        // FileUpload expects array format, even if empty
        $data['product_images'] = $existingImages;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract images from form data
        $images = $data['product_images'] ?? [];
        // Remove images from data so it doesn't try to save to products table
        unset($data['product_images']);

        // Store images temporarily to use in afterSave
        $this->productImages = $images;

        return $data;
    }

    protected array $productImages = [];

    protected function afterSave(): void
    {
        // Delete existing images
        $this->record->productImages()->delete();

        // Save new images to product_images table
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
