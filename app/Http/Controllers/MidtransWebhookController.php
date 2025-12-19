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
        $orderId = $notif->order_id;
        $statusCode = $notif->status_code;
        $grossAmount = $notif->gross_amount;
        $serverKey = Config::$serverKey;

        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($notif->signature_key !== $mySignatureKey) {
            return response()->json(['error' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $notif->transaction_status;
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        if ($order->status === 'paid') {
            return response()->json(['message' => 'Order already paid'], 200);
        }

        switch ($transactionStatus) {
            case 'settlement':
                $order->status = 'paid';
                $order->metode_pembayaran = $notif->payment_type;
                $order->save();

                $product = Product::find($order->product_id);
                if ($product != null) {
                    $product->stock -= $order->quantity;
                    $product->save();
                }
                break;

            case 'pending':
                $order->status = 'pending';
                $order->metode_pembayaran = $notif->payment_type;
                $order->save();
                break;

            case 'expire':
                $order->status = 'expired';
                $order->metode_pembayaran = $notif->payment_type;
                $order->save();
                break;

            case 'cancel':
            case 'deny':
                $order->status = 'cancelled';
                $order->save();
                break;
        }
        return response()->json(['message' => 'Notification processed successfully'], 200);
    }
}
