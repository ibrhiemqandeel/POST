<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // عرض صفحة إنشاء حساب
    public function showSignup()
    {
        return view('signup');
    }

    // معالجة إنشاء حساب جديد
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false, // التسجيل الافتراضي كمستخدم عادي
        ]);

        Auth::login($user);

        // التوجيه بناءً على الصلاحية
        // ملاحظة: كان يوجّه الأدمن إلى '/admin' وهو مسار غير معرّف فعلياً
        // (المسار الحقيقي هو '/admin/dashboard')، فكان يؤدي لخطأ 404.
        return $user->is_admin ? redirect()->intended(route('admin.dashboard')) : redirect()->intended('/');
    }

    // عرض صفحة تسجيل الدخول
    public function showLogin()
    {
        return view('login');
    }

    // معالجة تسجيل الدخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // التوجيه: إذا كان أدمن يُرسل للوحة التحكم، وإلا للصفحة الرئيسية
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('email');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
