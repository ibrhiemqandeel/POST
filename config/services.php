<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', 'https://post-z44n.onrender.com/auth/google/callback'),
],

    'cj' => [
        'email'   => env('CJ_EMAIL'),
        'api_key' => env('CJ_API_KEY'),
        // تُقرأ هنا (وليس عبر env() مباشرةً في الكود) حتى تظل تعمل بعد
        // تشغيل php artisan config:cache في الإنتاج.
        'token'   => env('CJ_ACCESS_TOKEN'),
        'proxy'   => env('CJ_PROXY'),
    ],

    /*
    | إعدادات عامة للتطبيق تُقرأ من .env لكن يجب المرور عبر config() في الكود
    | حتى لا تُفقد عند تفعيل الـ config cache.
    */
    'app_extra' => [
        'force_https' => (bool) env('FORCE_HTTPS', false),
        'setup_token' => env('SETUP_TOKEN'),
    ],

];
