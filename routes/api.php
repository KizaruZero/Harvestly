<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MidtransWebhookController;


// ============================================================
// 🔓 Public API Routes (No Authentication)
// ============================================================
// Routes ini untuk external access (Mobile apps, etc) atau public data

Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::post('/products', [ProductController::class, 'store'])->name('api.products.store');

// ============================================================
// 🔔 Midtrans Webhook (MUST be public, no CSRF, no auth)
// ============================================================
// Webhook dari Midtrans untuk notifikasi payment status
Route::post('/midtrans-notification', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.notification');

// ============================================================
// 💡 NOTE: Protected Routes Moved to web.php
// ============================================================
// Order routes yang perlu authentication dipindah ke routes/web.php
// karena Inertia.js menggunakan cookie-based authentication (web middleware)
// bukan token-based (sanctum middleware)