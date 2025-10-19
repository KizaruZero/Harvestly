# 🚀 Quick Reference Guide - Authentication & Authorization

## TL;DR - Jawaban Langsung

### ❓ Dimana taruh konfigurasi axios untuk authorization?

**📍 Jawaban: Di `resources/js/app.tsx`**

```typescript
// resources/js/app.tsx
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
```

### ❓ Kenapa di `app.tsx`, bukan di layout/component?

| Location    | ✅ Pros                                                                | ❌ Cons                                 |
| ----------- | ---------------------------------------------------------------------- | --------------------------------------- |
| **app.tsx** | ✅ Run once<br>✅ Global scope<br>✅ Single source<br>✅ Best practice | -                                       |
| Layout      | -                                                                      | ❌ Re-run setiap render<br>❌ Redundant |
| Component   | -                                                                      | ❌ Inconsistent<br>❌ Hard to maintain  |
| Context     | -                                                                      | ❌ Overkill for static config           |

### ❓ Apakah perlu Bearer token?

**❌ TIDAK!** Karena Anda menggunakan **Cookie-Based Authentication**

```typescript
// ❌ JANGAN lakukan ini
headers: { 'Authorization': 'Bearer token123' }

// ✅ CUKUP ini (cookies otomatis terkirim)
axios.post('/api/orders', data);
```

---

## 📦 File Structure

```
Harvestly/
├── resources/js/
│   ├── app.tsx                      ⭐ SETUP AXIOS DI SINI
│   ├── lib/
│   │   └── csrf.ts                  🛡️ CSRF helper
│   └── pages/components/
│       └── CheckoutButton.tsx       🛒 Component yang pakai axios
│
├── app/Http/Controllers/
│   └── OrderController.php          🎯 Backend controller
│
├── routes/
│   └── api.php                      🛣️ Protected routes
│
└── docs/
    ├── AUTHENTICATION.md            📚 Full documentation
    └── QUICK_REFERENCE.md           ⚡ This file
```

---

## 🔥 Implementation Steps

### Step 1: Setup Axios (app.tsx)

```typescript
// resources/js/app.tsx
import axios from 'axios';

// Global configuration
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Global error handler
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

### Step 2: Create CSRF Helper (csrf.ts)

```typescript
// resources/js/lib/csrf.ts
import axios from 'axios';

let csrfCookieInitialized = false;

export async function ensureCsrfCookie(): Promise<void> {
    if (csrfCookieInitialized) return;
    await axios.get('/sanctum/csrf-cookie');
    csrfCookieInitialized = true;
}
```

### Step 3: Use in Component (CheckoutButton.tsx)

```typescript
// resources/js/pages/components/CheckoutButton.tsx
import axios from 'axios';
import { ensureCsrfCookie } from '@/lib/csrf';

const handleCheckout = async () => {
    try {
        await ensureCsrfCookie();
        const response = await axios.post('/api/orders', {
            product_id: productId,
            quantity: quantity,
        });
        // Handle response...
    } catch (error) {
        console.error(error);
    }
};
```

### Step 4: Backend Controller (OrderController.php)

```php
// app/Http/Controllers/OrderController.php
public function store(Request $request): JsonResponse
{
    $user = $request->user(); // ✅ User otomatis dari session

    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $order = Order::create([
        'user_id' => $user->id,
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
    ]);

    return response()->json(['order' => $order]);
}
```

### Step 5: Protected Routes (api.php)

```php
// routes/api.php
Route::group(['prefix' => 'api'], function () {
    Route::post('/orders', [OrderController::class, 'store']);
})->middleware('auth:sanctum');
```

---

## 🎯 Key Concepts

### Cookie-Based vs Token-Based

```
┌─────────────────────────────────────────────────────┐
│  COOKIE-BASED (✅ Anda gunakan ini)                 │
├─────────────────────────────────────────────────────┤
│  Request Headers:                                   │
│  Cookie: laravel_session=abc123; XSRF-TOKEN=xyz     │
│  X-XSRF-TOKEN: xyz                                  │
│                                                      │
│  ✅ Otomatis oleh browser                           │
│  ✅ HttpOnly (aman dari XSS)                        │
│  ✅ Tidak perlu state management                    │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  TOKEN-BASED (❌ BUKAN ini)                         │
├─────────────────────────────────────────────────────┤
│  Request Headers:                                   │
│  Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbG...   │
│                                                      │
│  ❌ Manual setup setiap request                     │
│  ❌ Store di localStorage (XSS risk)                │
│  ❌ Perlu refresh token logic                       │
└─────────────────────────────────────────────────────┘
```

---

## 🔍 Debugging Checklist

Jika API request gagal, check:

- [ ] User sudah login?
- [ ] `axios.defaults.withCredentials = true` sudah di-set?
- [ ] `axios.defaults.withXSRFToken = true` sudah di-set?
- [ ] CSRF cookie sudah initialized?
- [ ] Middleware `auth:sanctum` sudah applied di route?
- [ ] Domain match dengan `SANCTUM_STATEFUL_DOMAINS`?

---

## 📊 Request Flow

```
User Click Button
       ↓
ensureCsrfCookie()
       ↓
axios.post('/api/orders')
       ↓
Browser adds cookies automatically:
  - laravel_session
  - XSRF-TOKEN
       ↓
Laravel receives request
       ↓
Middleware auth:sanctum
       ↓
Verify session from cookie
       ↓
$request->user() available
       ↓
Controller logic
       ↓
Return response
```

---

## ❓ Common Questions

### Q: Apakah perlu kirim token di header?

**A:** ❌ TIDAK! Cookies otomatis terkirim.

### Q: Bagaimana Laravel tahu siapa user-nya?

**A:** Dari `laravel_session` cookie yang otomatis terkirim setiap request.

### Q: Apakah perlu setup di setiap component?

**A:** ❌ TIDAK! Setup sekali di `app.tsx`, semua component inherit.

### Q: Kenapa 401 Unauthenticated?

**A:**

- User belum login, ATAU
- `withCredentials: true` belum di-set, ATAU
- Session expired

### Q: Kenapa 419 CSRF Token Mismatch?

**A:**

- `withXSRFToken: true` belum di-set, ATAU
- Belum hit `/sanctum/csrf-cookie`, ATAU
- Domain tidak match di config

---

## 🎨 Code Snippets

### Protected API Request (Full Example)

```typescript
import axios from 'axios';
import { ensureCsrfCookie } from '@/lib/csrf';

const createOrder = async (productId: number, quantity: number) => {
    try {
        // 1. Ensure CSRF cookie
        await ensureCsrfCookie();

        // 2. Make request (auth otomatis dari cookies)
        const response = await axios.post('/api/orders', {
            product_id: productId,
            quantity: quantity,
        });

        // 3. Handle success
        console.log('Order created:', response.data);
        return response.data;
    } catch (error) {
        // 4. Handle error (401 otomatis redirect via interceptor)
        if (axios.isAxiosError(error)) {
            console.error('Error:', error.response?.data.message);
        }
        throw error;
    }
};
```

### TypeScript Types

```typescript
// types/index.d.ts
interface Order {
    id: number;
    user_id: number;
    product_id: number;
    quantity: number;
    total_price: number;
    status: 'pending' | 'paid' | 'cancelled' | 'shipped' | 'completed';
    created_at: string;
    updated_at: string;
}

interface OrderResponse {
    success: boolean;
    message: string;
    order: Order;
    snap_token: string;
}

// Usage
const response = await axios.post<OrderResponse>('/api/orders', data);
```

---

## 🚨 Do's and Don'ts

### ✅ DO

```typescript
// ✅ Setup once globally
axios.defaults.withCredentials = true;

// ✅ Use interceptor for errors
axios.interceptors.response.use(...);

// ✅ Call ensureCsrfCookie before critical operations
await ensureCsrfCookie();

// ✅ Use TypeScript types
const response = await axios.post<OrderResponse>('/api/orders', data);
```

### ❌ DON'T

```typescript
// ❌ JANGAN set headers di component
axios.post('/api/orders', data, {
    headers: { Authorization: 'Bearer token' },
});

// ❌ JANGAN simpan credentials di localStorage
localStorage.setItem('token', 'abc123');

// ❌ JANGAN configure axios di multiple places
// Setup di app.tsx aja!

// ❌ JANGAN handle 401 di setiap component
// Use interceptor!
```

---

## 📚 Related Files

| File                       | Purpose             | Priority           |
| -------------------------- | ------------------- | ------------------ |
| `docs/AUTHENTICATION.md`   | Dokumentasi lengkap | 📖 Read first      |
| `resources/js/app.tsx`     | Axios setup         | ⭐ Must configure  |
| `resources/js/lib/csrf.ts` | CSRF helper         | 🛡️ Use when needed |
| `config/sanctum.php`       | Laravel config      | ⚙️ Check if issues |

---

## 🎓 Next Steps

1. ✅ Setup axios di `app.tsx` (Done!)
2. ✅ Create CSRF helper (Done!)
3. ✅ Update CheckoutButton (Done!)
4. ⏭️ Test end-to-end flow
5. ⏭️ Integrate Midtrans payment
6. ⏭️ Add error handling UI
7. ⏭️ Add loading states

---

**💡 Pro Tip:** Untuk production, tambahkan logging dan monitoring untuk track authentication issues!

**Last Updated:** October 19, 2025
