<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image_product', 'stock', 'price'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
