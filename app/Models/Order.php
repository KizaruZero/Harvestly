<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'metode_pembayaran',
        'quantity',
        'total_price',
        'status',
        'snap_token'
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
