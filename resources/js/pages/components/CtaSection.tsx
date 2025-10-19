import { Button } from '@/components/ui/button';

export default function CtaSection() {
    return (
        <section className="w-full bg-green-700 py-16 text-white">
            <div className="container mx-auto flex flex-col items-center gap-6 px-4 text-center md:px-6">
                <h2 className="text-3xl font-bold tracking-tighter">Siap untuk Hidup Lebih Sehat?</h2>
                <p className="max-w-[600px] text-green-100">
                    Daftar sekarang dan dapatkan diskon 10% untuk pembelian pertama Anda. Jadilah yang pertama tahu tentang panen terbaru dan
                    penawaran spesial.
                </p>
                <Button size="lg" variant="secondary" className="bg-white text-green-700 hover:bg-green-50">
                    Daftar Sekarang
                </Button>
            </div>
        </section>
    );
}
