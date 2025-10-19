import ThemeToggle from '@/components/theme-toggle';
import { Button } from '@/components/ui/button';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { Leaf, Menu, ShoppingCart } from 'lucide-react';

export default function Navbar() {
    const { auth } = usePage<SharedData>().props;

    return (
        <header className="sticky top-0 z-50 mx-auto w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div className="container mx-auto flex h-24 items-center justify-between">
                <a href="/" className="flex items-center gap-2">
                    <Leaf className="h-6 w-6 text-green-600" />
                    <span className="text-lg font-bold text-green-700">
                        FreshCart
                    </span>
                </a>
                <nav className="hidden items-center gap-6 text-sm md:flex">
                    <a
                        href="#features"
                        className="font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Kenapa Kami
                    </a>
                    <a
                        href="#products"
                        className="font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Produk
                    </a>
                    <a
                        href="#"
                        className="font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        Tentang
                    </a>
                </nav>

                <div className="flex items-center gap-4">
                    <ThemeToggle />
                    <Button variant="ghost" size="icon">
                        <ShoppingCart className="h-5 w-5" />
                        <span className="sr-only">Keranjang</span>
                    </Button>
                    <Button className="hidden bg-green-600 hover:bg-green-700 sm:flex">
                        Mulai Belanja
                    </Button>
                    <Button variant="outline" size="icon" className="md:hidden">
                        <Menu className="h-5 w-5" />
                        <span className="sr-only">Toggle menu</span>
                    </Button>
                </div>
            </div>
        </header>
    );
}
