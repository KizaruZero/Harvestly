<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('HomeView');
})->name('home');
Route::get('/cart', function () {
    return Inertia::render('CartPage');
})->name('cart');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

// ============================================================
// 🔒 API Routes with Cookie-Based Authentication (Inertia)
// ============================================================
// Routes ini menggunakan web middleware (session + cookies)
// Compatible dengan Inertia.js authentication
Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
