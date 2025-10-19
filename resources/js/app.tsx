import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import axios from 'axios';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';

// ============================================================
// 🔐 AXIOS GLOBAL CONFIGURATION (Best Practice)
// ============================================================
// Konfigurasi ini berlaku untuk semua axios request di aplikasi
axios.defaults.withCredentials = true; // Penting! Kirim cookies pada setiap request
axios.defaults.withXSRFToken = true; // Otomatis kirim CSRF token dari cookie
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Base URL (opsional, tapi recommended)
axios.defaults.baseURL = window.location.origin;

// ============================================================
// 🔄 AXIOS INTERCEPTOR (Optional, tapi sangat berguna)
// ============================================================
// Handle error globally
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        // Jika unauthorized (401), redirect ke login
        if (error.response?.status === 401) {
            window.location.href = '/login';
        }

        // Jika forbidden (403)
        if (error.response?.status === 403) {
            console.error(
                'Forbidden: You do not have permission to access this resource',
            );
        }

        return Promise.reject(error);
    },
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
// initializeTheme();
