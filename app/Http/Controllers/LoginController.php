<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 1. عرض صفحة تسجيل الدخول
Route::get('/login', function () {
    return view('login');
})->name('login');

// 2. معالجة تسجيل الدخول (POST)
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

// 3. مسار لوحة التحكم المحمي
Route::get('/dashboard', function () {
    return view('dashboard'); // أو إرجاع نص مؤقت لغرض الاختبار
})->middleware('auth')->name('dashboard');

// 4. مسار تسجيل الخروج (Logout)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');
