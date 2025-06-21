<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    HomeController,
    ServiceController,
    ProductController,
    ArtikelController,
    KonsultasiController,
    ContactController,
    ArticleController,
    ConsultationController,
    Auth\AdminLoginController,
    Admin\DashboardController,
    KategoriController,
    OrderController,
    PurchaseController,
    CartController,
    ProfileController,
    ShippingController,
    CheckoutController
};

Auth::routes();

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/whatsapp/{id}', [ServiceController::class, 'redirectToWhatsApp'])->name('services.whatsapp');
Route::get('/articles', [ArtikelController::class, 'index'])->name('articles');
Route::get('/articles/{slug}', [ArtikelController::class, 'show'])->name('articles.show');
Route::get('/consultation', [KonsultasiController::class, 'index'])->name('consultation.index');
Route::post('/consultation', [KonsultasiController::class, 'submit'])->name('consultation.submit');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

/*
|--------------------------------------------------------------------------
| USER ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/category/{slug}', [ProductController::class, 'category'])->name('products.category');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::post('/orders/purchase', [OrderController::class, 'purchase'])->name('orders.purchase');

    // Checkout Routes
    

    // Shipping AJAX
    Route::post('/api/calculate-shipping', [OrderController::class, 'calculateShipping'])->name('api.calculate-shipping');
    Route::post('/api/get-area', [OrderController::class, 'getAreaByPostalCode'])->name('api.get-area');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class);
    Route::resource('categories', App\Http\Controllers\Admin\KategoriController::class);

    // Admin Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/verify', [App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])->name('orders.verify');
    Route::post('/orders/{id}/reject', [App\Http\Controllers\Admin\OrderController::class, 'rejectPayment'])->name('orders.reject');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
