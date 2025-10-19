# 🔐 Authentication & Authorization Guide

## Overview

Aplikasi ini menggunakan **Laravel Sanctum Cookie-Based Authentication** untuk SPA (Single Page Application) dengan Inertia.js dan React.

---

## 📚 Table of Contents

1. [Konsep Dasar](#konsep-dasar)
2. [Architecture Flow](#architecture-flow)
3. [Setup & Configuration](#setup--configuration)
4. [Best Practices](#best-practices)
5. [Common Issues & Solutions](#common-issues--solutions)

---

## Konsep Dasar

### Cookie-Based vs Token-Based Authentication

| Aspect              | Cookie-Based (Kami Pakai)  | Token-Based (Bearer)            |
| ------------------- | -------------------------- | ------------------------------- |
| **Use Case**        | SPA dalam domain yang sama | Mobile app, API eksternal       |
| **Storage**         | HTTP-only cookies          | localStorage / sessionStorage   |
| **Security**        | Lebih aman (XSS protected) | Vulnerable to XSS               |
| **Implementation**  | Otomatis via cookies       | Manual via Authorization header |
| **CSRF Protection** | Required                   | Not required                    |

### Kenapa Cookie-Based untuk Inertia.js?

✅ **Lebih aman** - Cookies di-set sebagai `HttpOnly`, tidak bisa diakses JavaScript  
✅ **Otomatis** - Browser otomatis kirim cookies setiap request  
✅ **Built-in** - Laravel & Sanctum sudah support out-of-the-box  
✅ **No token management** - Tidak perlu store/refresh token manually

---

## Architecture Flow

### 🔄 Request Flow

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│   Browser   │         │   Laravel    │         │  Database   │
│  (React)    │         │   Backend    │         │             │
└─────────────┘         └──────────────┘         └─────────────┘
       │                        │                        │
       │  1. User Login         │                        │
       ├───────────────────────>│                        │
       │                        │                        │
       │  2. Verify Credentials │                        │
       │                        ├───────────────────────>│
       │                        │<───────────────────────│
       │                        │                        │
       │  3. Set Session Cookie │                        │
       │<───────────────────────│                        │
       │                        │                        │
       │  4. API Request        │                        │
       │  (with Cookie + CSRF)  │                        │
       ├───────────────────────>│                        │
       │                        │                        │
       │  5. Verify Session     │                        │
       │                        ├───────────────────────>│
       │                        │<───────────────────────│
       │                        │                        │
       │  6. Return Response    │                        │
       │<───────────────────────│                        │
       │                        │                        │
```

### 🔐 Authentication Flow Details

1. **User login** via `/login` endpoint
2. Laravel **creates session** & sets `laravel_session` cookie
3. Browser **automatically stores** the cookie
4. Subsequent requests **automatically include** the cookie
5. Laravel **verifies session** from cookie
6. Middleware `auth:sanctum` **validates** the authenticated user

---

## Setup & Configuration

### 1. Axios Global Configuration (app.tsx)

**📍 Location:** `resources/js/app.tsx`  
**⏰ Timing:** Run once saat aplikasi load  
**🎯 Purpose:** Configure axios untuk semua komponen

```typescript
import axios from 'axios';

// Konfigurasi Global
axios.defaults.withCredentials = true; // ✅ Kirim cookies setiap request
axios.defaults.withXSRFToken = true; // ✅ Auto-attach CSRF token
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.baseURL = window.location.origin;

// Global Error Handler
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Redirect ke login jika unauthorized
            window.location.href = '/login';
        }
        return Promise.reject(error);
    },
);
```

**❓ Mengapa di `app.tsx`?**

✅ **Single source of truth** - Semua axios config di satu tempat  
✅ **Global scope** - Berlaku untuk semua komponen  
✅ **Run once** - Dieksekusi saat app initialize  
✅ **Maintainable** - Mudah di-update dan track changes

**❌ Jangan letakkan di:**

- ❌ **Layout** - Akan re-run setiap layout render
- ❌ **Individual Components** - Redundant & inconsistent
- ❌ **Hooks** - Overhead & complexity
- ❌ **Context** - Tidak perlu state management untuk config statis

---

### 2. CSRF Cookie Helper (csrf.ts)

**📍 Location:** `resources/js/lib/csrf.ts`  
**⏰ Timing:** Sebelum API request pertama  
**🎯 Purpose:** Ensure CSRF cookie tersedia

```typescript
import axios from 'axios';

let csrfCookieInitialized = false;

export async function ensureCsrfCookie(): Promise<void> {
    if (csrfCookieInitialized) {
        return; // Skip jika sudah initialized
    }

    await axios.get('/sanctum/csrf-cookie');
    csrfCookieInitialized = true;
}
```

**📖 Kapan menggunakan:**

✅ Sebelum POST/PUT/DELETE request penting (checkout, payment)  
✅ Pada form yang critical & butuh high security  
✅ Jika user langsung ke halaman tanpa load page lain

**🚫 Tidak perlu jika:**

❌ User sudah login & browse normally (cookie sudah ada)  
❌ Request hanya GET (tidak perlu CSRF protection)  
❌ Sudah pasti cookie available (setelah page load)

---

### 3. Component Implementation (CheckoutButton.tsx)

**📍 Location:** `resources/js/pages/components/CheckoutButton.tsx`  
**⏰ Timing:** Saat user click checkout button  
**🎯 Purpose:** Send API request dengan authentication

```typescript
import axios from 'axios';
import { ensureCsrfCookie } from '@/lib/csrf';

const CheckoutButton = ({ productId, quantity }) => {
    const handleCheckout = async () => {
        try {
            // 1. Ensure CSRF cookie (optional tapi recommended)
            await ensureCsrfCookie();

            // 2. API Request - NO HEADERS NEEDED!
            const response = await axios.post('/api/orders', {
                product_id: productId,
                quantity: quantity,
            });

            // 3. Handle response
            console.log(response.data);
        } catch (error) {
            console.error(error);
        }
    };

    return <button onClick={handleCheckout}>Checkout</button>;
};
```

**✅ Yang TIDAK perlu:**

```typescript
// ❌ JANGAN lakukan ini (redundant!)
const response = await axios.post('/api/orders', data, {
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: 'Bearer ...', // ❌ Tidak perlu token
    },
    withCredentials: true, // ❌ Sudah di-set globally
});
```

---

### 4. Backend Controller

**📍 Location:** `app/Http/Controllers/OrderController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // ✅ User otomatis available dari session
        $user = $request->user(); // atau auth()->user()

        // Validate
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create order
        $order = Order::create([
            'user_id' => $user->id,  // ✅ User ID dari authenticated session
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        return response()->json([
            'success' => true,
            'order' => $order,
            'snap_token' => 'midtrans_snap_token_here',
        ]);
    }
}
```

---

### 5. API Routes Configuration

**📍 Location:** `routes/api.php`

```php
<?php

use App\Http\Controllers\OrderController;

// ✅ Group dengan middleware auth:sanctum
Route::group(['prefix' => 'api'], function () {
    Route::post('/orders', [OrderController::class, 'store'])
        ->name('orders.store');
})->middleware('auth:sanctum');

// Alternative (jika API routes sudah prefix 'api' otomatis):
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});
```

---

## Best Practices

### ✅ DO's

1. **Configure axios globally di `app.tsx`**

    ```typescript
    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;
    ```

2. **Use interceptors untuk global error handling**

    ```typescript
    axios.interceptors.response.use(
        (response) => response,
        (error) => {
            if (error.response?.status === 401) {
                window.location.href = '/login';
            }
            return Promise.reject(error);
        },
    );
    ```

3. **Call `ensureCsrfCookie()` sebelum critical requests**

    ```typescript
    await ensureCsrfCookie();
    await axios.post('/api/orders', data);
    ```

4. **Use TypeScript untuk type safety**

    ```typescript
    interface OrderResponse {
        success: boolean;
        order: Order;
        snap_token: string;
    }

    const response = await axios.post<OrderResponse>('/api/orders', data);
    ```

5. **Handle errors gracefully**
    ```typescript
    try {
        await axios.post('/api/orders', data);
    } catch (error) {
        if (axios.isAxiosError(error)) {
            console.error(error.response?.data.message);
        }
    }
    ```

---

### ❌ DON'Ts

1. **JANGAN set headers di setiap component**

    ```typescript
    // ❌ BAD - Redundant
    axios.post('/api/orders', data, {
        headers: { Accept: 'application/json' },
    });

    // ✅ GOOD - Sudah di-set globally
    axios.post('/api/orders', data);
    ```

2. **JANGAN gunakan Bearer token untuk cookie-based auth**

    ```typescript
    // ❌ BAD - Tidak perlu token
    headers: { 'Authorization': 'Bearer token123' }

    // ✅ GOOD - Cookies handle authentication
    // No Authorization header needed
    ```

3. **JANGAN simpan credentials di localStorage**

    ```typescript
    // ❌ BAD - Security risk (XSS vulnerable)
    localStorage.setItem('token', 'abc123');

    // ✅ GOOD - Use HttpOnly cookies (Laravel handles this)
    ```

4. **JANGAN configure axios di banyak tempat**

    ```typescript
    // ❌ BAD - Inconsistent & hard to maintain
    // Component A
    axios.defaults.baseURL = 'https://api.com';

    // Component B
    axios.defaults.baseURL = 'https://api2.com';

    // ✅ GOOD - Single configuration di app.tsx
    ```

5. **JANGAN handle 401 di setiap component**

    ```typescript
    // ❌ BAD - Repetitive
    catch (error) {
        if (error.response.status === 401) {
            window.location.href = '/login';
        }
    }

    // ✅ GOOD - Global interceptor handles this
    ```

---

## Common Issues & Solutions

### 🚨 Issue 1: "401 Unauthenticated"

**Symptoms:**

```
Error: Request failed with status code 401
Response: { "message": "Unauthenticated." }
```

**Possible Causes:**

1. ❌ User belum login

    ```typescript
    // Solution: Redirect ke login atau check auth state dulu
    if (!auth.user) {
        router.visit('/login');
        return;
    }
    ```

2. ❌ `withCredentials` tidak di-set

    ```typescript
    // Solution: Set di app.tsx
    axios.defaults.withCredentials = true;
    ```

3. ❌ Session expired
    ```typescript
    // Solution: Handle via interceptor
    axios.interceptors.response.use(
        (response) => response,
        (error) => {
            if (error.response?.status === 401) {
                window.location.href = '/login';
            }
            return Promise.reject(error);
        },
    );
    ```

---

### 🚨 Issue 2: "419 CSRF Token Mismatch"

**Symptoms:**

```
Error: Request failed with status code 419
Response: { "message": "CSRF token mismatch." }
```

**Solutions:**

1. ✅ Set `withXSRFToken` di axios config

    ```typescript
    axios.defaults.withXSRFToken = true;
    ```

2. ✅ Call `/sanctum/csrf-cookie` dulu

    ```typescript
    await ensureCsrfCookie();
    await axios.post('/api/orders', data);
    ```

3. ✅ Pastikan `sanctum.php` config benar
    ```php
    // config/sanctum.php
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000'
    )),
    ```

---

### 🚨 Issue 3: "CORS Error"

**Symptoms:**

```
Access to XMLHttpRequest blocked by CORS policy
```

**Solutions:**

1. ✅ Install & configure `laravel-cors`

    ```bash
    composer require fruitcake/laravel-cors
    ```

2. ✅ Update `cors.php`

    ```php
    // config/cors.php
    'supports_credentials' => true,
    'allowed_origins' => ['http://localhost:5173'],
    ```

3. ✅ Add CORS middleware
    ```php
    // app/Http/Kernel.php
    protected $middleware = [
        \Fruitcake\Cors\HandleCors::class,
    ];
    ```

---

### 🚨 Issue 4: Cookies Tidak Tersimpan

**Symptoms:**

- Request tidak membawa session cookie
- Setiap request dianggap unauthenticated

**Solutions:**

1. ✅ Pastikan `withCredentials: true`

    ```typescript
    axios.defaults.withCredentials = true;
    ```

2. ✅ Pastikan domain match di `sanctum.php`

    ```php
    'stateful' => ['localhost', 'localhost:5173'],
    ```

3. ✅ Pastikan `SESSION_DOMAIN` di `.env`
    ```env
    SESSION_DOMAIN=localhost
    SESSION_SECURE_COOKIE=false  # true only for HTTPS
    ```

---

## 📊 Summary

### Configuration Hierarchy

```
app.tsx (Global Config)
    ├── axios.defaults.* (Applied to all requests)
    ├── axios.interceptors.* (Global error handling)
    │
    └── Components
            ├── CheckoutButton.tsx
            ├── OrderForm.tsx
            └── ... (All inherit global config)
```

### Request Checklist

- ✅ Axios configured di `app.tsx`
- ✅ `withCredentials: true`
- ✅ `withXSRFToken: true`
- ✅ CSRF cookie initialized (if needed)
- ✅ User authenticated (login)
- ✅ Middleware `auth:sanctum` applied
- ✅ Error handling via interceptor

---

## 🎓 Further Reading

- [Laravel Sanctum Docs](https://laravel.com/docs/sanctum)
- [Axios Interceptors](https://axios-http.com/docs/interceptors)
- [Inertia.js Authentication](https://inertiajs.com/authentication)
- [Laravel CSRF Protection](https://laravel.com/docs/csrf)

---

**Last Updated:** October 19, 2025  
**Author:** AI Assistant  
**Project:** Harvestly
