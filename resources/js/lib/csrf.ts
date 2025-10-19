import axios from 'axios';

/**
 * 🛡️ CSRF Protection Helper
 *
 * Untuk Sanctum cookie-based authentication, Laravel memerlukan CSRF token.
 * Function ini memastikan CSRF cookie sudah tersedia sebelum melakukan API request.
 *
 * KAPAN MENGGUNAKAN INI:
 * - Sebelum API request pertama (jika user langsung ke halaman tanpa load page lain)
 * - Pada form submission penting (checkout, payment, etc)
 *
 * Tidak perlu dipanggil setiap kali karena cookie akan persist di browser.
 */

let csrfCookieInitialized = false;

export async function ensureCsrfCookie(): Promise<void> {
    // Jika sudah pernah di-call, skip
    if (csrfCookieInitialized) {
        return;
    }

    try {
        // Hit endpoint untuk set CSRF cookie
        await axios.get('/sanctum/csrf-cookie');
        csrfCookieInitialized = true;
    } catch (error) {
        console.error('Failed to initialize CSRF cookie:', error);
        throw error;
    }
}

/**
 * Reset flag (berguna untuk testing atau setelah logout)
 */
export function resetCsrfCookie(): void {
    csrfCookieInitialized = false;
}
