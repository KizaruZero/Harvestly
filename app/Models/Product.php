<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'weight_kg',
        'sku',
        'price',
        //  'product_category_id',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight_kg' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(Category::class, 'product_category_id');
    // }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function discountTargets(): HasMany
    {
        return $this->hasMany(DiscountTarget::class);
    }

    public function inventories(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($q) use ($term) {
            $q->where('name', 'like', $term)
                ->orWhere('description', 'like', $term)
                ->where('is_active', true);
        });
    }

    public function scopeCategory($query, $category)
    {
        $query->whereHas('categories', function ($q) use ($category) {
            $q->where('slug', $category);
        });
    }

    public function scopePrices($query, $prices)
    {
        // Spatie Query Builder biasanya mengirim array jika format filter[price]=10,50
        // Atau string "10,50" tergantung config. Kita handle keduanya.
        if (is_string($prices)) {
            $prices = explode(',', $prices);
        }
        $min = $prices[0] ?? null;
        $max = $prices[1] ?? null;

        if ($min !== null && $min !== '') {
            $query->where('price', '>=', $min);
        }

        if ($max !== null && $max !== '') {
            $query->where('price', '<=', $max);
        }
    }

}
