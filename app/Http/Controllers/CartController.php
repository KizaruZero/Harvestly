<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $totalPrice = $this->calculateTotalPrice($cart);

        return response()->json([
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_items' => count($cart)
        ], 200);
    }

    /**
     * Calculate total price of cart items
     */
    private function calculateTotalPrice($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    //
    public function store(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($id);
        $quantity = $request->quantity;

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // Cek stok tersedia
        if ($quantity > $product->quantity) {
            return response()->json([
                'message' => 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $product->quantity
            ], 400);
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $newTotalQuantity = $cart[$id]['quantity'] + $quantity;

            // Validasi total quantity tidak melebihi stok
            if ($newTotalQuantity > $product->quantity) {
                return response()->json([
                    'message' => 'Total jumlah melebihi stok yang tersedia. Stok tersedia: ' . $product->quantity . ', Di keranjang: ' . $cart[$id]['quantity']
                ], 400);
            }

            $cart[$id]['quantity'] = $newTotalQuantity;
        } else {
            $cart[$id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image_product
            ];
        }
        session()->put('cart', $cart);
        $totalPrice = $this->calculateTotalPrice($cart);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_items' => count($cart)
        ], 200);
    }
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'message' => 'Produk tidak ditemukan di keranjang'
            ], 404);
        }

        unset($cart[$id]);
        session()->put('cart', $cart);
        $totalPrice = $this->calculateTotalPrice($cart);

        return response()->json([
            'message' => 'Produk berhasil dihapus dari keranjang',
            'cart' => $cart,
            'total_price' => $totalPrice,
            'total_items' => count($cart)
        ], 200);
    }

    public function updateCart(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'message' => 'Produk tidak ditemukan di keranjang'
            ], 404);
        }

        $newQuantity = $request->quantity;

        // Validasi stock produk
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        if ($newQuantity > $product->quantity) {
            return response()->json([
                'message' => 'Jumlah melebihi stok yang tersedia. Stok tersedia: ' . $product->quantity
            ], 400);
        }

        // Update quantity
        $cart[$id]['quantity'] = $newQuantity;

        // Update harga total jika ada perubahan harga
        $cart[$id]['price'] = $product->price;

        session()->put('cart', $cart);
        $totalPrice = $this->calculateTotalPrice($cart);

        return response()->json([
            'message' => 'Keranjang berhasil diperbarui',
            'cart' => $cart,
            'updated_item' => $cart[$id],
            'total_price' => $totalPrice,
            'total_items' => count($cart)
        ], 200);
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        session()->forget('cart');

        return response()->json([
            'message' => 'Keranjang berhasil dikosongkan',
            'cart' => [],
            'total_price' => 0,
            'total_items' => 0
        ], 200);
    }

    /**
     * Get cart summary (total items and total price)
     */
    public function summary()
    {
        $cart = session()->get('cart', []);
        $totalPrice = $this->calculateTotalPrice($cart);
        $totalItems = 0;

        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }

        return response()->json([
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'unique_products' => count($cart)
        ], 200);
    }


}
