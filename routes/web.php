<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; // <--- تعديل مسار الاستدعاء
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CjProductController;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Public Routes (الصفحات العامة والمنتجات)
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontController::class, 'index'])->name('index');
Route::get('/about', [FrontController::class, 'about'])->name('about');
Route::get('/accessories', [FrontController::class, 'accessories'])->name('accessories');
Route::get('/beauty', [FrontController::class, 'beauty'])->name('beauty');
Route::get('/cart', [FrontController::class, 'cart'])->name('cart');
Route::get('/kids', [FrontController::class, 'kids'])->name('kids');
Route::get('/product', [FrontController::class, 'product'])->name('product');
Route::get('/women', [FrontController::class, 'women'])->name('women');

/*
|--------------------------------------------------------------------------
| Guest Routes (لغير المسجلين: تسجيل الدخول وإنشاء الحساب)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

/*
|--------------------------------------------------------------------------
| User Authenticated Routes (المستخدمين المسجلين)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (لوحة التحكم - محمي بـ Auth & Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // <--- استخدام الكلاس المحدث
});

Route::get('/run-setup', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('migrate', ['--force' => true]);

    return response()->json([
        'message' => 'تم مسح الكاش وتشغيل الميجريشن بنجاح!'
    ]);
});

// مسارات CJ Dropshipping مباشرة
Route::get('/cj/products', [CjProductController::class, 'index']);
Route::post('/cj/import-product', [CjProductController::class, 'importProduct']);



Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
