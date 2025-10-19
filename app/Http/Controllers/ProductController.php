<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        return response()->json(
            ['products' => $products],
            200
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(
                ['message' => 'Unauthorized'],
                401
            );
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:150',
            'image_product' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer',
            'price' => 'required|numeric|min:0',
        ]);

        $image_product = $request->file('image_product');
        $image_product_name = time() . '.' . $image_product->getClientOriginalExtension();
        $image_product->move(public_path('images'), $image_product_name);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_product' => $image_product_name,
            'stock' => $request->stock,
            'price' => $request->price,
        ]);

        return response()->json(
            ['message' => 'Product created successfully', 'product' => $product],
            201
        );
    }

}
