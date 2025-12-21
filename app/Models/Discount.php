<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'minimum_order_amount',
        'max_usage',
        'max_usage_per_user',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'max_usage' => 'integer',
        'max_usage_per_user' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function discountTarget(): HasOne
    {
        return $this->hasOne(DiscountTarget::class);
    }

    public function discountRedemptions(): HasMany
    {
        return $this->hasMany(DiscountRedemption::class);
    }
}
