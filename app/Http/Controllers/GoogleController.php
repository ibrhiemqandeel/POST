<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * توجيه المستخدم إلى صفحة تسجيل الدخول عبر Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * معالجة استجابة Google وإنشاء/تسجيل دخول المستخدم
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // البحث عن المستخدم ببريده الإلكتروني أو إنشائه إن لم يكن موجوداً
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(24)), // كلمة مرور عشوائية
                    'email_verified_at' => now(),
                ]
            );

            // تسجيل دخول المستخدم
            Auth::login($user, true);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'حدث خطأ أثناء تسجيل الدخول بواسطة Google.');
        }
    }
}
