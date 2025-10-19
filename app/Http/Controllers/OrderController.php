<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi request dari React
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // 2. Konfigurasi Midtrans
        // Panggil ini di setiap transaksi atau di AppServiceProvider
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            // 3. (Best Practice) Ambil data dari DB, JANGAN percaya frontend
            $product = Product::findOrFail($request->product_id);
            $user = Auth::user();

            // 3.1. Validasi stock tersedia
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'error' => 'Stock tidak mencukupi. Stock tersedia: ' . $product->stock
                ], 400);
            }

            // 4. Hitung Total Harga di Backend
            // ⚠️ PENTING: Midtrans IDR tidak boleh ada desimal!
            $productPrice = (int) round($product->price); // Cast ke integer
            $totalAmount = $productPrice * $request->quantity;


            // 5. Simpan order ke DB Anda dengan status 'pending'
            $order = Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $totalAmount,
                'status' => 'pending',
                // 'snap_token' akan diisi di bawah
            ]);

            // 6. Siapkan data untuk Midtrans
            // ✅ Semua harga HARUS integer untuk IDR
            $params = [
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => $totalAmount, // ✅ gross_amount, bukan total_price
                ],
                'item_details' => [
                    [
                        'id' => $product->id,
                        'price' => $productPrice, // ✅ Integer
                        'quantity' => $request->quantity,
                        'name' => $product->name,
                    ]
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ];

            // 7. Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($params);

            // 8. Simpan snap_token ke order Anda
            $order->snap_token = $snapToken;
            $order->save();

            // 9. Kembalikan token ke React
            return response()->json(['snap_token' => $snapToken], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 📋 Get all orders for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with('product')
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * 🔍 Get single order detail
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = $request->user()
            ->orders()
            ->with('product')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    /**
     * 🔄 Update order status
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $order = $request->user()
            ->orders()
            ->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled,shipped,completed',
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'order' => $order,
        ]);
    }

    /**
     * 🗑️ Cancel order
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $order = $request->user()
            ->orders()
            ->findOrFail($id);

        // Only allow cancellation if order is still pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel order that is not pending',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
        ]);
    }

    /**
     * 💳 Generate Midtrans Snap Token
     * 
     * TODO: Implement actual Midtrans integration
     */
    private function generateMidtransToken(Order $order): string
    {
        // Placeholder - integrate with Midtrans SDK
        return 'mock_snap_token_' . $order->id;
    }
}
