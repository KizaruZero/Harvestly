import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { ShoppingCart, Trash2 } from 'lucide-react';
import { useState } from 'react';

// Asumsi tipe Product dan CartItem dari types.ts

// --- MOCK DATA (Ganti dengan data dari state management Anda) ---
interface CartItem {
    id: number;
    productId: number;
    name: string;
    price: number;
    quantity: number;
    image: string;
}

const mockCartItems: CartItem[] = [
    {
        id: 1,
        productId: 101,
        name: 'Brokoli Organik',
        price: 15000,
        quantity: 2,
        image: 'https://images.unsplash.com/photo-1587354390161-b5b03c80dfa2?w=500&auto=format&fit=crop&q=60',
    },
    {
        id: 2,
        productId: 102,
        name: 'Stroberi Segar',
        price: 25000,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1591289006989-2319c59f0f91?w=500&auto=format&fit=crop&q=60',
    },
    {
        id: 3,
        productId: 103,
        name: 'Tomat Ceri',
        price: 12000,
        quantity: 3,
        image: 'https://images.unsplash.com/photo-1591373507147-2b21c2c31e67?w=500&auto=format&fit=crop&q=60',
    },
];

// --- Komponen CheckoutButton (dari tutorial sebelumnya) ---
// Ini akan dipanggil oleh CartPage
import CheckoutButton from './components/CheckoutButton'; // Adjust path

export default function CartPage() {
    const [cartItems, setCartItems] = useState<CartItem[]>(mockCartItems);

    // Menghitung total belanja
    const calculateTotal = () => {
        return cartItems.reduce(
            (sum, item) => sum + item.price * item.quantity,
            0,
        );
    };

    const handleUpdateQuantity = (id: number, newQuantity: number) => {
        if (newQuantity < 1) return; // Kuantitas tidak boleh kurang dari 1
        setCartItems((prevItems) =>
            prevItems.map((item) =>
                item.id === id ? { ...item, quantity: newQuantity } : item,
            ),
        );
        // TODO: Di aplikasi nyata, Anda mungkin ingin update state keranjang global atau backend di sini
    };

    const handleRemoveItem = (id: number) => {
        setCartItems((prevItems) => prevItems.filter((item) => item.id !== id));
        // TODO: Di aplikasi nyata, Anda mungkin ingin update state keranjang global atau backend di sini
    };

    return (
        <AppLayout>
            <div className="container mx-auto min-h-screen bg-white p-4 md:p-8">
                <h1 className="mb-8 text-center text-4xl font-bold text-gray-900">
                    Keranjang Belanja Anda
                </h1>

                {cartItems.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-gray-600">
                        <ShoppingCart className="mb-6 h-24 w-24 text-green-500" />
                        <p className="mb-4 text-xl">Keranjang Anda kosong.</p>
                        <Button className="bg-green-600 hover:bg-green-700">
                            Mulai Belanja Sekarang
                        </Button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        {/* Bagian Kiri: Daftar Item Keranjang */}
                        <div className="space-y-6 lg:col-span-2">
                            {cartItems.map((item) => (
                                <div
                                    key={item.id}
                                    className="flex items-center space-x-4 rounded-lg border bg-white p-4 shadow-sm"
                                >
                                    <img
                                        src={item.image}
                                        alt={item.name}
                                        className="h-24 w-24 rounded-md object-cover"
                                    />
                                    <div className="flex-grow">
                                        <h3 className="text-lg font-semibold text-gray-800">
                                            {item.name}
                                        </h3>
                                        <p className="text-gray-600">
                                            Rp{' '}
                                            {item.price.toLocaleString('id-ID')}{' '}
                                            / item
                                        </p>
                                        <div className="mt-2 flex items-center space-x-2">
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                onClick={() =>
                                                    handleUpdateQuantity(
                                                        item.id,
                                                        item.quantity - 1,
                                                    )
                                                }
                                            >
                                                -
                                            </Button>
                                            <Input
                                                type="number"
                                                min="1"
                                                value={item.quantity}
                                                onChange={(e) =>
                                                    handleUpdateQuantity(
                                                        item.id,
                                                        parseInt(
                                                            e.target.value,
                                                        ),
                                                    )
                                                }
                                                className="w-16 text-center"
                                            />
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                onClick={() =>
                                                    handleUpdateQuantity(
                                                        item.id,
                                                        item.quantity + 1,
                                                    )
                                                }
                                            >
                                                +
                                            </Button>
                                        </div>
                                    </div>
                                    <div className="flex flex-col items-end">
                                        <p className="text-lg font-bold text-green-700">
                                            Rp{' '}
                                            {(
                                                item.price * item.quantity
                                            ).toLocaleString('id-ID')}
                                        </p>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="mt-2 text-red-500 hover:text-red-700"
                                            onClick={() =>
                                                handleRemoveItem(item.id)
                                            }
                                        >
                                            <Trash2 className="h-5 w-5" />
                                        </Button>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Bagian Kanan: Ringkasan Belanja */}
                        <div className="rounded-lg border bg-green-50 p-6 shadow-lg lg:col-span-1">
                            <h2 className="mb-6 text-2xl font-bold text-gray-900">
                                Ringkasan Belanja
                            </h2>
                            <div className="mb-4 flex items-center justify-between text-lg">
                                <p className="text-gray-700">Jumlah Item:</p>
                                <p className="font-semibold text-gray-800">
                                    {cartItems.length}
                                </p>
                            </div>
                            <Separator className="my-4 bg-green-200" />
                            <div className="mb-6 flex items-center justify-between text-2xl font-bold">
                                <p className="text-gray-900">Total Harga:</p>
                                <p className="text-green-700">
                                    Rp{' '}
                                    {calculateTotal().toLocaleString('id-ID')}
                                </p>
                            </div>

                            {/* Tombol Checkout Midtrans */}
                            <div className="w-full">
                                <CheckoutButton
                                    productId={cartItems[0].productId}
                                    quantity={cartItems[0].quantity}
                                />
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
