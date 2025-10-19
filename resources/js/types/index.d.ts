import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Product {
    id: number;
    name: string;
    description: string;
    image_product: string;
    stock: number;
    price: number;
    created_at: DateTime;
    updated_at: DateTime;
    [key: string]: unknown;
}

// ============================================================
// 💳 Midtrans Snap TypeScript Declarations
// ============================================================

export interface MidtransSnapOptions {
    onSuccess?: (result: MidtransTransactionResult) => void;
    onPending?: (result: MidtransTransactionResult) => void;
    onError?: (result: MidtransTransactionResult) => void;
    onClose?: () => void;
}

export interface MidtransTransactionResult {
    status_code: string;
    status_message: string;
    transaction_id: string;
    order_id: string;
    gross_amount: string;
    payment_type: string;
    transaction_time: string;
    transaction_status: string;
    fraud_status?: string;
    [key: string]: unknown;
}

// Extend Window interface untuk Midtrans Snap
declare global {
    interface Window {
        snap?: {
            pay: (snapToken: string, options?: MidtransSnapOptions) => void;
            embed: (
                snapToken: string,
                options: {
                    embedId: string;
                    onSuccess?: (result: MidtransTransactionResult) => void;
                    onPending?: (result: MidtransTransactionResult) => void;
                    onError?: (result: MidtransTransactionResult) => void;
                    onClose?: () => void;
                },
            ) => void;
        };
    }
}
