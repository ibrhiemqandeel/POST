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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;


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
        // الأدمن يُحوَّل تلقائياً للوحة التحكم الحقيقية (admin.dashboard) بدل
        // صفحة "حسابي" العادية. أي مستخدم آخر يشوف صفحة حسابه فقط.
        if (auth()->user()?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // أحدث 5 طلبات للمستخدم الحالي، لعرضها فعلياً في صفحة "حسابي"
        // بدل النص الثابت القديم "You have no orders yet" الذي كان يظهر
        // دائماً حتى لو كان للمستخدم طلبات حقيقية.
        $recentOrders = auth()->user()->orders()->with('items')->latest()->take(5)->get();

        return view('account', compact('recentOrders'));
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Checkout & Orders (طلبات العميل - لازم يكون مسجل دخول)
    |----------------------------------------------------------------------
    */
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
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

    // استيراد منتجات من CJ Dropshipping (كانت هذه المسارات عامة بدون أي حماية،
    // ما يسمح لأي زائر بمناداة API الخارجي أو استيراد منتجات دون إذن).
    Route::get('/cj/products', [CjProductController::class, 'index'])->name('cj.products');
    Route::post('/cj/import-product', [CjProductController::class, 'importProduct'])->name('cj.import');

    // مزامنة المخزون بالجملة (يستخدم أمر app:sync-cj-products الموجود فعلاً
    // في app/Console/Commands/SyncCjProducts.php والذي لم يكن له أي واجهة استخدام).
    Route::post('/cj/sync', function (\Illuminate\Http\Request $request) {
        $exitCode = Artisan::call('app:sync-cj-products', [
            '--page'     => $request->input('page', 1),
            '--pageSize' => $request->input('pageSize', 20),
        ]);

        return response()->json([
            'success' => $exitCode === 0,
            'output'  => Artisan::output(),
        ]);
    })->name('cj.sync');

    // إدارة الطلبات
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // إدارة التصنيفات
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // إدارة العملاء
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');
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
    // الـ seeders (المستخدم الأدمن + الفئات + كتالوج المنتجات) كلها مبنية
    // بـ updateOrCreate، فتشغيلها أكثر من مرة آمن ولا يكرر أي بيانات.
    Artisan::call('db:seed', ['--force' => true]);

    return response()->json([
        'message' => 'تم مسح الكاش وتشغيل الميجريشن والـ seeders بنجاح!'
    ]);
});

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
