<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// Auth Routes (Guest only - untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Protected Routes (harus login dulu)
Route::middleware('auth')->group(function () {
    
    // Redirect root ke orders
    Route::get('/', function () {
        return redirect()->route('orders.index');
    });

    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware('auth');

    // POS/Order Routes
    Route::get('/pos', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/report/daily', [OrderController::class, 'report'])->name('orders.report');
    Route::get('/orders/report/monthly', [OrderController::class, 'monthlyReport'])->name('orders.report.monthly');
    Route::get('/orders/report/yearly', [OrderController::class, 'yearlyReport'])->name('orders.report.yearly');

    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Product Routes
    Route::resource('products', ProductController::class);

    // Customer Routes
    Route::resource('customers', CustomerController::class);

    // Profile Routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});