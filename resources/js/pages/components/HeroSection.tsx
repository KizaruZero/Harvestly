import { Button } from '@/components/ui/button';
import { ShoppingCart } from 'lucide-react';

export default function HeroSection() {
    return (
        <section className="mx-auto w-full bg-gradient-to-b from-green-50 to-white py-20 md:py-32 lg:py-40">
            <div className="container mx-auto grid items-center gap-8 px-4 md:px-6 lg:grid-cols-2 lg:gap-16">
                <div className="space-y-6">
                    <h1 className="text-4xl font-bold tracking-tighter text-gray-900 md:text-5xl lg:text-6xl">
                        Segar Langsung dari Kebun
                        <span className="block text-green-600">ke Meja Anda</span>
                    </h1>
                    <p className="max-w-[600px] text-lg text-muted-foreground">
                        Nikmati buah dan sayuran organik terbaik di kota. Dipanen setiap hari dan diantar langsung ke depan pintu rumah Anda dengan
                        cepat.
                    </p>
                    <div className="flex flex-col gap-4 sm:flex-row">
                        <Button size="lg" className="bg-green-600 hover:bg-green-700">
                            Lihat Semua Produk
                            <ShoppingCart className="ml-2 h-5 w-5" />
                        </Button>
                        <Button size="lg" variant="outline">
                            Langganan Box
                        </Button>
                    </div>
                </div>
                <div className="flex justify-center">
                    <img
                        src="https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80"
                        alt="Keranjang Buah dan Sayur"
                        className="w-full max-w-md rounded-xl object-cover shadow-2xl"
                        style={{ aspectRatio: '1/1' }}
                    />
                </div>
            </div>
        </section>
    );
}
