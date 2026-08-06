<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Front & Public Routes
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
Route::get('/signup', [FrontController::class, 'signup'])->name('signup');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Direct Closures)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'البيانات المدخلة غير صحيحة.',
    ])->onlyInput('email');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Google OAuth Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

// مسارات الزوار (أنشئ حساب / سجل دخول)
Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// مسار تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
