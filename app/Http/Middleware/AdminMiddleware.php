<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من أن المستخدم مسجل الدخول وأن قيمة is_admin تساوي true
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // إذا لم يكن أدمن، يتم توجيهه إلى الصفحة الرئيسية مع إظهار رسالة تنبيه
        return redirect('/')->with('error', 'عذراً، لا تملك صلاحيات الوصول لهذه الصفحة.');
    }
}
