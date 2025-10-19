import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Leaf, Star, Truck } from 'lucide-react';

export default function FeaturesSection() {
    const features = [
        {
            icon: <Leaf className="h-10 w-10 text-green-600" />,
            title: '100% Organik',
            description: 'Bebas pestisida dan bahan kimia berbahaya. Sehat untuk Anda dan keluarga.',
        },
        {
            icon: <Truck className="h-10 w-10 text-green-600" />,
            title: 'Pengiriman Cepat',
            description: 'Pesan hari ini sebelum jam 12 siang, kami antar di hari yang sama.',
        },
        {
            icon: <Star className="h-10 w-10 text-green-600" />,
            title: 'Kualitas Premium',
            description: 'Kami hanya memilih hasil panen terbaik dari petani lokal tepercaya.',
        },
    ];

    return (
        <section id="features" className="bg-muted py-16 md:py-24">
            <div className="container mx-auto">
                <h2 className="mb-12 text-center text-3xl font-bold text-gray-900">Kenapa Memilih FreshCart?</h2>
                <div className="grid gap-8 md:grid-cols-3">
                    {features.map((feature) => (
                        <Card key={feature.title} className="text-center shadow-lg">
                            <CardHeader className="items-center">
                                <div className="mx-auto rounded-full bg-green-100 p-4">{feature.icon}</div>
                                <CardTitle className="mt-4">{feature.title}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-muted-foreground">{feature.description}</p>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </section>
    );
}
