import CtaSection from './components/CtaSection';
import FeaturesSection from './components/FeatureSection';
import FooterSection from './components/FooterSection';
import HeroSection from './components/HeroSection';
import Navbar from './components/Navbar';
import ProductSection from './components/ProductSection';
// --- Komponen Utama Landing Page ---

export default function HomeView() {
    return (
        <div className="flex min-h-screen flex-col font-sans">
            <Navbar />
            <main className="flex-grow">
                <HeroSection />
                <FeaturesSection />
                <ProductSection />
                <CtaSection />
            </main>
            <FooterSection />
        </div>
    );
}
