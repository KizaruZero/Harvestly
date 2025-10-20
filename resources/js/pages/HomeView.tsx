import AppLayout from '@/layouts/app-layout';
import CtaSection from './components/CtaSection';
import FeaturesSection from './components/FeatureSection';
import HeroSection from './components/HeroSection';
import ProductSection from './components/ProductSection';
// --- Komponen Utama Landing Page ---

export default function HomeView() {
    return (
        <AppLayout>
            <main className="flex-grow">
                <HeroSection />
                <FeaturesSection />
                <ProductSection />
                <CtaSection />
            </main>
        </AppLayout>
    );
}
