import { Button } from '@/components/ui/button';
import axios from 'axios';
import { ShoppingCart } from 'lucide-react';
import { useState } from 'react';

/**
 * 🔐 AUTHENTICATION NOTES:
 * - Tidak perlu Authorization header Bearer token
 * - Sanctum menggunakan cookie-based authentication (stateful)
 * - Axios sudah dikonfigurasi di app.tsx untuk mengirim cookies & CSRF token secara otomatis
 * - Laravel otomatis verify user dari session cookie
 */

const CheckoutButton = ({
    productId,
    quantity,
}: {
    productId: number;
    quantity: number;
}) => {
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const handleCheckout = async () => {
        setIsLoading(true);
        setError(null);

        try {
            // 1. Panggil API Backend (auth sudah otomatis dari cookies)
            const response = await axios.post('/api/orders', {
                product_id: productId,
                quantity: quantity,
            });
            // ⬆️ Headers tidak perlu didefinisikan lagi, sudah di-set globally di app.tsx

            // 2. Dapatkan snap_token dari respons
            const snapToken = response.data.snap_token;

            // 3. Validasi Midtrans Snap tersedia
            if (!window.snap) {
                setError(
                    'Midtrans Snap belum tersedia. Silakan refresh halaman.',
                );
                return;
            }

            // 4. Panggil window.snap.pay
            if (snapToken) {
                window.snap.pay(snapToken, {
                    // 5. (Best Practice) Callback untuk User Experience
                    // PENTING: JANGAN update status "paid" di sini.
                    // Ini hanya untuk memberi tahu user & mengarahkan halaman.

                    onSuccess: (result) => {
                        /* User akan diarahkan ke halaman ini setelah sukses */
                        console.log('Payment Success:', result);
                        window.location.href = '/order/success'; // Arahkan ke halaman "Terima Kasih"
                    },
                    onPending: (result) => {
                        /* User akan diarahkan ke halaman ini jika pending */
                        console.log('Payment Pending:', result);
                        window.location.href = '/order/pending'; // Arahkan ke halaman "Menunggu Pembayaran"
                    },
                    onError: (result) => {
                        /* Handle error */
                        console.error('Payment Error:', result);
                        setError('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: () => {
                        /* Handle jika pop-up ditutup sebelum bayar */
                        console.log(
                            'Customer closed the popup without finishing the payment',
                        );
                    },
                });
            }
        } catch (err: any) {
            setError(
                err.response?.data?.message ||
                    err.message ||
                    'Gagal membuat pesanan',
            );
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="w-full">
            <Button
                className="w-full bg-green-600 hover:bg-green-700"
                onClick={handleCheckout}
                disabled={isLoading}
            >
                <ShoppingCart className="mr-2 h-4 w-4" />
                {isLoading ? 'Memproses...' : 'Bayar Sekarang'}
            </Button>
            {error && (
                <p className="mt-2 text-center text-sm text-red-600">{error}</p>
            )}
        </div>
    );
};

export default CheckoutButton;
