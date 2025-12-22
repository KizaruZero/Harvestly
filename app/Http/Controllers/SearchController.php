<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

use App\Http\Resources\ProductResource;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        // Pasang 'where is_active' DI SINI, sebelum filter apapun.
        // Ini memastikan semua hasil pencarian HANYA produk aktif.
        $products = QueryBuilder::for(Product::where('is_active', true)->with(['primaryImage', 'inventories']))
            ->allowedFilters([
                // 'search' memanggil scopeSearch di model
                AllowedFilter::scope('search'),
                // 'category' memanggil scopeCategory di model
                AllowedFilter::scope('category'),
                // 'price' di URL mapping ke scopePrices di Model
                AllowedFilter::scope('price', 'prices'),
            ])
            ->paginate(20);

        return ProductResource::collection($products);
    }
}
