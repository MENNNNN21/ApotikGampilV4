<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\CheckoutController;




Auth::routes();
// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
// Services Routes
Route::get('/services', [ServiceController::class, 'index'])->name('services'); // Perbaikan nama route
Route::get('/services/whatsapp/{id}', [ServiceController::class, 'redirectToWhatsApp'])->name('services.whatsapp');

Route::get('/articles', [ArtikelController::class, 'index'])->name('articles');
Route::get('/articles/{slug}', [ArtikelController::class, 'show'])->name('articles.show');
Route::get('/consultation', [KonsultasiController::class, 'index'])->name('consultation.index');
Route::post('/consultation', [KonsultasiController::class, 'submit'])->name('consultation.submit');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/category/{slug}', [ProductController::class, 'category'])->name('products.category');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    
    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::post('/orders/purchase', [OrderController::class, 'purchase'])->name('orders.purchase');



    
    // AJAX Routes for shipping
    Route::post('/api/calculate-shipping', [OrderController::class, 'calculateShipping'])->name('api.calculate-shipping');
    Route::post('/api/get-area', [OrderController::class, 'getAreaByPostalCode'])->name('api.get-area');
});

Route::post('/checkout/payment', [CheckoutController::class, 'showCheckoutPage'])->name('checkout.show')->middleware('auth');
Route::post('/checkout/process', [CheckoutController::class, 'processOrder'])->name('checkout.process')->middleware('auth');

Route::get('/checkout/payment', [OrderController::class, 'showPaymentForm'])->name('checkout.payment');
Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');

// Cart Routes


// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// Rute untuk proses Checkout (membutuhkan login pengguna)
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'showCheckoutPage'])->name('checkout.show');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'processOrder'])->name('checkout.process');
});

// Admin routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\KategoriController::class);
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/verify', [\App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])->name('orders.verify');
    Route::post('/orders/{id}/reject', [\App\Http\Controllers\Admin\OrderController::class, 'rejectPayment'])->name('orders.reject');;
});



Route::prefix('admin')->middleware(['auth', 'is_admin'])->name('admin.')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/verify', [\App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])->name('orders.verify');
    Route::post('/orders/{id}/reject', [\App\Http\Controllers\Admin\OrderController::class, 'rejectPayment'])->name('orders.reject');
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');