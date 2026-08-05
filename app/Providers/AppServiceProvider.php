<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        // الثقة بالـ Proxies الخاصة بـ Render لضمان التعرف على HTTPS
        $request->setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO
        );

        // إجبار بروتوكول HTTPS في بيئة الإنتاج أو Render
        if (app()->environment('production') || env('FORCE_HTTPS', false) || $request->server->has('HTTP_X_FORWARDED_PROTO')) {
            URL::forceScheme('https');
        }
    }
}
