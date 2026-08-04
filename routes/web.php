<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

Route::get('/', [FrontController::class, 'index'])->name('index');
Route::get('/about', [FrontController::class, 'about'])->name('about');
Route::get('/accessories', [FrontController::class, 'accessories'])->name('accessories');
Route::get('/beauty', [FrontController::class, 'beauty'])->name('beauty');
Route::get('/cart', [FrontController::class, 'cart'])->name('cart');
Route::get('/kids', [FrontController::class, 'kids'])->name('kids');
Route::get('/product', [FrontController::class, 'product'])->name('product');
Route::get('/women', [FrontController::class, 'women'])->name('women');
Route::get('/muster', [FrontController::class, 'muster'])->name('muster');
Route::get('/login', [FrontController::class, 'login'])->name('login');
Route::get('/dashboard', [FrontController::class, 'dashboard'])->name('dashboard');




use Database\Seeders\AdminUserSeeder;
use Illuminate\Support\Facades\Artisan;

Route::get('/run-admin-seeder', function () {
    Artisan::call('db:seed', [
        '--class' => AdminUserSeeder::class,
        '--force' => true,
    ]);

    return 'تم إنشاء حساب الأدمن بنجاح!';
});
