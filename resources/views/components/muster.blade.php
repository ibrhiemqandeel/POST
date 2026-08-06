<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#5A3A30">
    <title>{{ $title ?? config('app.name', 'POST') }}</title>
    <meta name="description" content="{{ $description ?? '' }}">

    <!-- Open Graph / social sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'POST') }}">
    <meta property="og:description" content="{{ $description ?? '' }}">
    <meta property="og:image" content="{{ asset('post-logo.png') }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? config('app.name', 'POST') }}">
    <meta name="twitter:description" content="{{ $description ?? '' }}">

    <link rel="icon" href="{{ asset('post-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('post-logo.png') }}">

    <!-- Brand typefaces: Fraunces (display) + Manrope (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,450;0,9..144,600;1,9..144,450&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="{{ asset('app.js') }}" defer></script>

    <style>
        :root {
            --cream: #F6EFE6;
            --cream-2: #EEE2D3;
            --white: #FFFBF6;
            --ink: #2B2018;
            --ink-soft: #5B4C40;
            --rose: #B0715C;
            --rose-deep: #8C4E38;
            --rose-light: #E6B6A2;
            --umber: #5A3A30;
            --blush: #E9C7BA;
            --line: #E1D2C1;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background: var(--cream);
            color: var(--ink);
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 clamp(1.25rem, 3vw, 2.5rem)
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0
        }

        .site-header-skeleton {
            min-height: 104px;
            background: linear-gradient(90deg, var(--cream) 25%, var(--cream-2) 37%, var(--cream) 63%);
            background-size: 400% 100%;
            animation: skeleton-pulse 1.6s ease-in-out infinite;
            border-bottom: 1px solid var(--line);
        }

        .site-footer-skeleton {
            min-height: 280px;
            background: linear-gradient(90deg, var(--umber) 25%, #6b4a3d 37%, var(--umber) 63%);
            background-size: 400% 100%;
            animation: skeleton-pulse 1.6s ease-in-out infinite;
        }

        @keyframes skeleton-pulse {
            0% { background-position: 100% 0 }
            100% { background-position: 0 0 }
        }

        @media (prefers-reduced-motion:reduce) {
            .site-header-skeleton, .site-footer-skeleton { animation: none }
        }

        /* RTL support */
        html[dir="rtl"] .utility-bar__ship { flex-direction: row-reverse }
        html[dir="rtl"] .nav__links { flex-direction: row-reverse }
        html[dir="rtl"] .footer-grid { direction: rtl }
        html[dir="rtl"] .news__form { flex-direction: row-reverse }

        /* Newsletter */
        .news {
            position: relative;
            background: var(--blush);
            border-radius: 14px;
            text-align: center;
            padding: clamp(2.5rem, 6vw, 4rem) 1.5rem;
            overflow: hidden
        }

        .news__form {
            display: flex;
            gap: .6rem;
            margin-top: 1.4rem;
            max-width: 420px;
            margin-inline: auto
        }

        .news__form input {
            flex: 1;
            padding: .85rem 1.1rem;
            border-radius: 999px;
            border: 1px solid var(--rose-deep);
            background: var(--white);
            font: inherit
        }

        .btn--light {
            background: var(--rose-deep);
            color: #fff;
            border: none;
            padding: .85rem 1.6rem;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer
        }
    </style>
</head>

<body data-page="index" data-locale="{{ app()->getLocale() }}">

    <!-- site-header Skeleton (تم حشف الهيدر الحقيقي ليتم حقنه ديناميكياً بواسطة app.js) -->
    <div id="site-header" class="site-header-skeleton" aria-live="polite">
        <span class="sr-only">Loading…</span>
    </div>

    <main class="main-content">

        {{-- المحتوى الذي سيتم تمريره من الصفحة الرئيسية --}}
        {{ $slot }}

        <section class="section container">
            <div class="news reveal">
                <div class="news__bg"></div>
                <span class="eyebrow" style="color:var(--rose-light)">Join the house</span>
                <h2 class="h-section" style="margin-top:.8rem">First stories, first access</h2>
                <p class="lead">Be the first to read new origin stories, see new collections, and receive a little something for your first order — wherever you are in the world.</p>
                <form class="news__form js-form" data-toast="Welcome to the house — check your inbox ✦">
                    <input type="email" required placeholder="Your email address" aria-label="Email address">
                    <button class="btn btn--light" type="submit">Subscribe</button>
                </form>
            </div>
        </section>

    </main>

    <!-- site-footer Skeleton -->
    <div id="site-footer" class="site-footer-skeleton" aria-live="polite">
        <span class="sr-only">Loading…</span>
    </div>

<script>
window.navLinks = {
    home: "{{ Route::has('home') ? route('home') : url('/') }}",
    women: "{{ Route::has('women') ? route('women') : '#' }}",
    kids: "{{ Route::has('kids') ? route('kids') : '#' }}",
    beauty: "{{ Route::has('beauty') ? route('beauty') : '#' }}",
    accessories: "{{ Route::has('accessories') ? route('accessories') : '#' }}",
    about: "{{ Route::has('about') ? route('about') : '#' }}",
    login: "{{ Route::has('login') ? route('login') : '#' }}",
    cart: "{{ Route::has('cart') ? route('cart') : '#' }}",

    adminDashboard: "{{ (auth()->check() && auth()->user()->is_admin && Route::has('admin.dashboard')) ? route('admin.dashboard') : '' }}",

    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
    isAdmin: {{ (auth()->check() && auth()->user()->is_admin) ? 'true' : 'false' }},

    locale: "{{ app()->getLocale() }}",
    currency: "{{ session('currency', 'USD') }}",

    availableLocales: @json(config('app.available_locales', ['en','fr','es','ar'])),
    availableCurrencies: @json(config('app.available_currencies', ['USD','EUR','GBP','AED']))
};
</script>

</body>
</html>
