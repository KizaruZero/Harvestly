<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'weight_kg' => $this->weight_kg,
            'sku' => $this->sku,
            'stock' => $this->inventories ? $this->inventories->stock : 0,
            'reserved_stock' => $this->inventories ? $this->inventories->reserved_stock : 0,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image_url' => $this->primaryImage ? url($this->primaryImage->image_url) : null,
        ];
    }
}
