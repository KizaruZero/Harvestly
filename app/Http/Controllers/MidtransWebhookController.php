<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
use Midtrans\Notification;
use App\Models\Order;
use App\Models\Product;


class MidtransWebhookController extends Controller
{
    //
    public function handle(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification payload'], 400);
        }
        // 3. (BEST PRACTICE) Validasi Signature Key
        // Ini adalah langkah keamanan terpenting
        $orderId = $notif->order_id;
        $statusCode = $notif->status_code;
        $grossAmount = $notif->gross_amount;
        $serverKey = Config::$serverKey;

        // Buat hash signature key
        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Bandingkan signature key
        if ($notif->signature_key !== $mySignatureKey) {
            // Signature tidak valid, kirim 403 Forbidden
            return response()->json(['error' => 'Invalid signature key'], 403);
        }

        // 4. Dapatkan data transaksi
        $transactionStatus = $notif->transaction_status;
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // 5. (BEST PRACTICE) Idempotency Check
        // Cek apakah status order sudah 'paid'. Jika ya, jangan proses lagi.
        if ($order->status === 'paid') {
            return response()->json(['message' => 'Order already paid'], 200);
        }

        // 6. Update Status Order Berdasarkan Notifikasi
        switch ($transactionStatus) {
            case 'settlement':
                // Pembayaran berhasil
                $order->status = 'paid';
                $order->metode_pembayaran = $notif->payment_type;
                $order->save();

                // --- (Logika Bisnis Anda) ---
                // TODO: Kirim email konfirmasi ke user
                // Kurangi stok produk
                $product = Product::find($order->product_id);
                if ($product != null) {
                    $product->stock -= $order->quantity;
                    $product->save(); // ✅ Simpan perubahan stock
                }
                // ------------------------------
                break;

            case 'pending':
                // Pembayaran masih menunggu
                $order->status = 'pending';
                $order->metode_pembayaran = $notif->payment_type;

                $order->save();
                break;

            case 'expire':
                // Pembayaran kadaluarsa
                $order->status = 'expired';
                $order->metode_pembayaran = $notif->payment_type;
                $order->save();
                break;

            case 'cancel':
            case 'deny':
                // Pembayaran dibatalkan atau ditolak
                $order->status = 'failed';
                $order->save();
                break;
        }

        // 7. Kirim 200 OK ke Midtrans
        // Ini memberitahu Midtrans bahwa notifikasi sudah diterima
        // Jika tidak, Midtrans akan mengirim ulang.
        return response()->json(['message' => 'Notification processed successfully'], 200);
    }
}
