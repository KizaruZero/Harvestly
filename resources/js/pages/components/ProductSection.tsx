import ProductImage from '@/assets/reze.jpeg';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from '@/components/ui/carousel';
import { Product } from '@/types';
import axios from 'axios';
import { Minus, Plus, ShoppingCart } from 'lucide-react';
import { useEffect, useState } from 'react';
import CheckoutButton from './CheckoutButton';
import Modal from './Modal';

export default function ProductSection() {
    const [products, setProducts] = useState<Product[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState<Product | null>(
        null,
    );
    const [isAlertOpen, setIsAlertOpen] = useState(false);
    const [quantity, setQuantity] = useState(1);

    const handleBuyClick = (product: Product) => {
        setIsModalOpen(true);
        setSelectedProduct(product);
        setQuantity(1);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedProduct(null);
    };

    const handleBuyConfirm = () => {
        setIsAlertOpen(true);
    };
    const handleCloseAlert = () => {
        setIsAlertOpen(false);
        setQuantity(1);
    };

    const handleAddQuantity = () => {
        if (!selectedProduct) return;
        setQuantity(quantity + 1);
        if (quantity >= selectedProduct.stock) {
            setQuantity(selectedProduct.stock);
        }
    };
    const handleSubtractQuantity = () => {
        if (!selectedProduct) return;
        setQuantity(quantity - 1);
        if (quantity <= 1) {
            setQuantity(1);
        }
    };

    useEffect(() => {
        const FetchProducts = async () => {
            try {
                const response = await axios.get('/api/products');
                setProducts(response.data.products);
            } catch (e: any) {
                setError(e.message);
            } finally {
                setIsLoading(false);
            }
        };
        FetchProducts();
    }, []);

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;
    if (products.length === 0) return <div>No products found</div>;

    return (
        <section id="products" className="py-16 md:py-24">
            <div className="container mx-auto">
                <h2 className="mb-12 text-center text-3xl font-bold text-gray-900">
                    Produk Pilihan Minggu Ini
                </h2>
                <Carousel
                    opts={{
                        align: 'start',
                        loop: true,
                    }}
                    className="mx-auto w-full max-w-2xl md:max-w-5xl lg:max-w-7xl"
                >
                    <CarouselContent>
                        {products.map((products) => (
                            <CarouselItem
                                key={products.name}
                                className="md:basis-1/3 lg:basis-1/4"
                            >
                                <div className="p-1">
                                    <Card className="overflow-hidden transition-all duration-300 ease-in-out hover:shadow-xl">
                                        <CardContent className="p-0">
                                            <img
                                                src={ProductImage}
                                                alt={products.name}
                                                className="h-72 w-full object-cover"
                                            />
                                        </CardContent>
                                        <CardHeader>
                                            <CardTitle className="text-2xl uppercase">
                                                {products.name}
                                            </CardTitle>
                                        </CardHeader>
                                        <CardContent>
                                            <p className="text-sm font-semibold text-black">
                                                STOCK : {products.stock}
                                            </p>
                                            <p className="text-lg font-semibold text-green-600">
                                                IDR{' '}
                                                {products.price.toLocaleString(
                                                    'id-ID',
                                                    {
                                                        style: 'currency',
                                                        currency: 'IDR',
                                                    },
                                                )}
                                            </p>
                                        </CardContent>
                                        <CardFooter>
                                            <Button
                                                className="w-full bg-green-600 hover:bg-green-700"
                                                onClick={() =>
                                                    handleBuyClick(products)
                                                }
                                            >
                                                <ShoppingCart className="mr-2 h-4 w-4" />
                                                Beli
                                            </Button>
                                            <Button
                                                className="mx-4 w-full bg-green-600 hover:bg-green-700"
                                                onClick={() =>
                                                    handleBuyClick(products)
                                                }
                                            >
                                                <ShoppingCart className="mr-2 h-4 w-4" />
                                                Beli
                                            </Button>
                                        </CardFooter>
                                    </Card>
                                </div>
                            </CarouselItem>
                        ))}
                    </CarouselContent>

                    <CarouselPrevious className="-left-12 hidden sm:flex" />
                    <CarouselNext className="-right-12 hidden sm:flex" />
                </Carousel>
                <Modal isOpen={isModalOpen} onClose={handleCloseModal}>
                    <Card className="h-full overflow-hidden transition-all duration-300 ease-in-out hover:shadow-xl">
                        <CardContent className="p-0">
                            <img
                                src={ProductImage}
                                alt={selectedProduct?.name}
                                className="h-72 w-full object-cover"
                            />
                        </CardContent>
                        <CardHeader>
                            <CardTitle className="text-3xl uppercase">
                                {selectedProduct?.name}
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm font-semibold text-black">
                                STOCK : {selectedProduct?.stock}
                            </p>
                            <p className="text-lg font-semibold text-green-600">
                                IDR{' '}
                                {selectedProduct?.price.toLocaleString(
                                    'id-ID',
                                    {
                                        style: 'currency',
                                        currency: 'IDR',
                                    },
                                )}
                            </p>
                        </CardContent>
                        {/* button quantity */}
                        <div className="flex items-center justify-between">
                            <Button
                                variant="outline"
                                size="icon"
                                onClick={handleSubtractQuantity}
                            >
                                <Minus className="h-4 w-4" />
                            </Button>
                            <span className="text-lg font-semibold text-black">
                                {quantity}
                            </span>
                            <Button
                                variant="outline"
                                size="icon"
                                onClick={handleAddQuantity}
                            >
                                <Plus className="h-4 w-4" />
                            </Button>
                        </div>
                        <CardFooter>
                            <CheckoutButton
                                productId={selectedProduct?.id ?? 0}
                                quantity={quantity}
                            />
                        </CardFooter>
                    </Card>
                </Modal>
            </div>
        </section>
    );
}
