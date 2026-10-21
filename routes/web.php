<?php

use Illuminate\Support\Facades\Route;

// Controllers - General & Auth
use App\Http\Controllers\AuthController;

// Controllers - Admin
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BundleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PreOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SerialController;
use App\Http\Controllers\Admin\WarrantyController;

// Controllers - Customer / Store
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. المتجر العام والصفحة الرئيسية (Public Store Routes)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('store.home');

// الكتالوج والمنتجات والعروض
Route::prefix('catalog')->group(function () {
    // تعديل اسم المسار إلى store.catalog
    Route::get('/', [CatalogController::class, 'index'])->name('store.catalog');
    Route::get('/product/{product}', [CatalogController::class, 'show'])->name('store.product.show');
});
// العروض الخاصة والحزم
Route::get('/bundles', [CatalogController::class, 'index'])->name('store.bundles');

// سلة التسوق (Cart)
Route::prefix('cart')->name('store.cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add'])->name('.add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('.remove');
});

// الدفع / إنهاء الطلب (Checkout)
Route::prefix('checkout')->name('store.checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
});


// ==========================================
// 2. المصادقة وإدارة الحساب (Authentication Routes)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// التوجيه الذكي المركزي عند زيارة /dashboard
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');


// ==========================================
// 3. لوحة تحكم الزبون (Customer Area)
// ==========================================
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/{id}', [CustomerDashboardController::class, 'showOrder'])->name('orders.show');
});


// ==========================================
// 4. لوحة تحكم الإدارة (Admin Dashboard Routes)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // الرئيسية / لوحة المؤشرات
    Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('dashboard');

    // إدارة الماركات التجارية (Brands)
    Route::resource('brands', BrandController::class)->only(['index', 'store', 'destroy']);

    // إدارة الأقسام (Categories)
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // إدارة المنتجات والأجهزة (Products)
    Route::resource('products', ProductController::class);

    // إدارة سياسات الضمان (Warranties)
    Route::resource('warranties', WarrantyController::class)->only(['index', 'store', 'update', 'destroy']);

    // إدارة الحزم والعروض المجمعة (Bundles)
    Route::resource('bundles', BundleController::class)->only(['index', 'store', 'destroy']);
    Route::patch('bundles/{bundle}/toggle-status', [BundleController::class, 'toggleStatus'])->name('bundles.toggle-status');

    // إدارة الحجز المسبق (Pre-Orders)
    Route::resource('pre-orders', PreOrderController::class)->only(['index', 'store', 'destroy']);
    Route::patch('pre-orders/{preOrder}/status', [PreOrderController::class, 'updateStatus'])->name('pre-orders.update-status');

    // إدارة وتتبع السيريالات والمخزون (Product Serials)
    Route::resource('serials', SerialController::class)->only(['index', 'store', 'destroy']);
    Route::patch('serials/{serial}/status', [SerialController::class, 'updateStatus'])->name('serials.update-status');

});