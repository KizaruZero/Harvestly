import FooterSection from '@/pages/components/FooterSection';
import Navbar from '@/pages/components/Navbar';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children }: AppLayoutProps) {
    return (
        <>
            <Navbar />
            {children}
            <FooterSection />
        </>
    );
}
