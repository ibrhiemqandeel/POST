<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; // <--- تعديل مسار الاستدعاء
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CjProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;


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
        // ملاحظة: view('dashboard') هي واجهة إدارة المنتجات المخصصة للأدمن،
        // لذلك المستخدم العادي يُحوَّل الآن إلى صفحة "حسابي" الخاصة به بدل
        // عرض واجهة الأدمن عن طريق الخطأ (راجع resources/views/account.blade.php).
        return view('account');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Locale & Currency Switchers (تبديل اللغة والعملة من الهيدر)
|--------------------------------------------------------------------------
*/
Route::get('/locale/{locale}', function (string $locale) {
    $available = config('app.available_locales', ['en', 'fr', 'es', 'de', 'ja', 'ar']);

    if (in_array($locale, $available, true)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return back();
})->name('locale.switch');

Route::get('/currency/{currency}', function (string $currency) {
    $available = config('app.available_currencies', ['USD', 'EUR', 'GBP', 'JPY', 'AED']);

    if (in_array($currency, $available, true)) {
        session(['currency' => $currency]);
    }

    return back();
})->name('currency.switch');

/*
|--------------------------------------------------------------------------
| Admin Routes (لوحة التحكم - محمي بـ Auth & Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // <--- استخدام الكلاس المحدث

    // إدارة المنتجات من لوحة التحكم: إضافة / تعديل / حذف فعلي في قاعدة البيانات
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

// حماية أمنية: هذا المسار كان بدون أي حماية ويسمح لأي زائر بتشغيل migrate
// --force على قاعدة البيانات. الآن يتطلب توكن سري معرّف في .env (SETUP_TOKEN)
// ولن يعمل إطلاقاً إذا لم يكن هذا المتغير معرّفاً.
Route::get('/run-setup', function (\Illuminate\Http\Request $request) {
    $setupToken = env('SETUP_TOKEN');

    if (empty($setupToken) || $request->query('token') !== $setupToken) {
        abort(404);
    }

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
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products');

/*
|--------------------------------------------------------------------------
| Cart (السلة الحقيقية - تعمل للزوار والمستخدمين المسجلين معاً)
|--------------------------------------------------------------------------
*/
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{item}', [CartController::class, 'remove'])->name('cart.remove');
