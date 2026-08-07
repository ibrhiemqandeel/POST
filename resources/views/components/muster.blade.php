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

        /* Icon buttons in header (search / account / cart) */
        .icon-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body data-page="index" data-locale="{{ app()->getLocale() }}">

@php
    // بنحسب هالقيم مرة وحدة هون فوق، بدل ما نكررهم بأكثر من مكان بالصفحة
    // (بالهيدر وبـ window.navLinks). استخدام ?-> (nullsafe) بيمنع Fatal Error
    // لو auth()->user() رجع null رغم إن auth()->check() صار true.
    $isLoggedIn = auth()->check();
    $isAdmin = $isLoggedIn && auth()->user()?->is_admin;
    $adminDashboardUrl = ($isAdmin && Route::has('admin.dashboard')) ? route('admin.dashboard') : '';
@endphp

    {{-- ============================= HEADER ============================= --}}
    <div class="utility-bar">
        <div class="container">
            <div class="utility-bar__ship">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" focusable="false">
                    <path d="M2 12h20M12 2c2.8 3 4.2 6.4 4.2 10S14.8 19 12 22c-2.8-3-4.2-6.4-4.2-10S9.2 5 12 2Z"/>
                </svg>
                <span>{{ __('Shipping to 120+ countries · Free returns worldwide') }}</span>
            </div>
            <div class="switchers">
                <label class="switcher">
                    <select aria-label="{{ __('Language') }}" name="locale" class="js-locale-switch">
                        <option value="en" @selected(app()->getLocale() === 'en')>English</option>
                        <option value="fr" @selected(app()->getLocale() === 'fr')>Français</option>
                        <option value="es" @selected(app()->getLocale() === 'es')>Español</option>
                        <option value="de" @selected(app()->getLocale() === 'de')>Deutsch</option>
                        <option value="ja" @selected(app()->getLocale() === 'ja')>日本語</option>
                        <option value="ar" @selected(app()->getLocale() === 'ar')>العربية</option>
                    </select>
                </label>
                <label class="switcher">
                    <select aria-label="{{ __('Region and currency') }}" name="currency" class="js-currency-switch">
                        <option value="USD" @selected(session('currency', 'USD') === 'USD')>USD $ — United States</option>
                        <option value="EUR" @selected(session('currency', 'USD') === 'EUR')>EUR € — European Union</option>
                        <option value="GBP" @selected(session('currency', 'USD') === 'GBP')>GBP £ — United Kingdom</option>
                        <option value="JPY" @selected(session('currency', 'USD') === 'JPY')>JPY ¥ — Japan</option>
                        <option value="AED" @selected(session('currency', 'USD') === 'AED')>AED د.إ — UAE</option>
                    </select>
                </label>
            </div>
        </div>
    </div>

    <header>
        <div class="container nav">
            <a class="wordmark" href="{{ Route::has('home') ? route('home') : url('/') }}">POST</a>

            <nav class="nav__links" aria-label="{{ __('Main navigation') }}">
                <a href="{{ Route::has('women') ? route('women') : '#' }}">{{ __('Women') }}</a>
                <a href="{{ Route::has('kids') ? route('kids') : '#' }}">{{ __('Children') }}</a>
                <a href="{{ Route::has('beauty') ? route('beauty') : '#' }}">{{ __('Beauty') }}</a>
                <a href="{{ Route::has('accessories') ? route('accessories') : '#' }}">{{ __('Accessories') }}</a>
                <a href="{{ Route::has('about') ? route('about') : '#' }}">{{ __('Journal') }}</a>
                @if($adminDashboardUrl)
                    <a href="{{ $adminDashboardUrl }}">{{ __('Admin') }}</a>
                @endif
            </nav>

            <div class="nav__icons">
                <button type="button" class="icon-btn js-search-toggle" aria-label="{{ __('Search') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" focusable="false">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                    </svg>
                </button>

                <a href="{{ $isLoggedIn ? (Route::has('dashboard') ? route('dashboard') : '#') : (Route::has('login') ? route('login') : '#') }}"
                   class="icon-btn" aria-label="{{ $isLoggedIn ? __('My account') : __('Login') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" focusable="false">
                        <path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </a>

                <a href="{{ Route::has('cart') ? route('cart') : '#' }}" class="icon-btn" aria-label="{{ __('Cart') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" focusable="false">
                        <path d="M6 7h12l-1 13H7L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>
    {{-- =========================== END HEADER ============================ --}}

    <main class="main-content">

        {{-- المحتوى الذي سيتم تمريره من الصفحة الرئيسية --}}
        {{ $slot }}

        <section class="section container">
            <div class="news reveal">
                <div class="news__bg"></div>
                <span class="eyebrow" style="color:var(--rose-light)">{{ __('Join the house') }}</span>
                <h2 class="h-section" style="margin-top:.8rem">{{ __('First stories, first access') }}</h2>
                <p class="lead">{{ __('Be the first to read new origin stories, see new collections, and receive a little something for your first order — wherever you are in the world.') }}</p>
                <form class="news__form js-form" data-toast="{{ __('Welcome to the house — check your inbox ✦') }}">
                    <input type="email" required placeholder="{{ __('Your email address') }}" aria-label="{{ __('Email address') }}">
                    <button class="btn btn--light" type="submit">{{ __('Subscribe') }}</button>
                </form>
            </div>
        </section>

    </main>

    {{-- ============================= FOOTER =============================== --}}
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="foot-word">POST</div>
                    <p style="max-width:30ch;font-size:.88rem;color:#c2b0a2">{{ __('Premium origin stories and thoughts, designed in New York and worn everywhere.') }}</p>
                </div>
                <div>
                    <h4>{{ __('Shop') }}</h4>
                    <ul>
                        <li><a href="{{ Route::has('women') ? route('women') : '#' }}">{{ __('Women') }}</a></li>
                        <li><a href="{{ Route::has('kids') ? route('kids') : '#' }}">{{ __('Children') }}</a></li>
                        <li><a href="{{ Route::has('beauty') ? route('beauty') : '#' }}">{{ __('Beauty') }}</a></li>
                        <li><a href="{{ Route::has('accessories') ? route('accessories') : '#' }}">{{ __('Accessories') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4>{{ __('House') }}</h4>
                    <ul>
                        <li><a href="{{ Route::has('about') ? route('about') : '#' }}">{{ __('Our story') }}</a></li>
                        <li><a href="#">{{ __('Journal') }}</a></li>
                        <li><a href="#">{{ __('Sustainability') }}</a></li>
                        <li><a href="#">{{ __('Careers') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4>{{ __('Worldwide') }}</h4>
                    <ul>
                        <li><a href="#">{{ __('Shipping & duties') }}</a></li>
                        <li><a href="#">{{ __('Returns') }}</a></li>
                        <li><a href="#">{{ __('Size guide') }}</a></li>
                        <li><a href="#">{{ __('Contact') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="foot-bottom">
                <span>© {{ date('Y') }} POST. {{ __('All rights reserved.') }}</span>
                <span>{{ __('Shipping worldwide from New York & Como') }}</span>
            </div>
        </div>
    </footer>
    {{-- =========================== END FOOTER =============================== --}}

@php
    // بنحسب هالقيم هون بـ PHP عادي بدل ما نحطها كـ array literal جوا @json()
    // مباشرة، لأنه هذا كان يسبب خطأ "Unclosed '[' does not match ')'" بمحول Blade.
    $availableLocales = config('app.available_locales', ['en', 'fr', 'es', 'ar']);
    $availableCurrencies = config('app.available_currencies', ['USD', 'EUR', 'GBP', 'AED']);
        availableLocales: @json($availableLocales);
        availableCurrencies: @json($availableCurrencies)
@endphp

<script>
    // بيانات عامة يستخدمها app.js في باقي الصفحة (سلة، بحث، منتجات...)
    window.navLinks = {
        home: "{{ Route::has('home') ? route('home') : url('/') }}",
        women: "{{ Route::has('women') ? route('women') : '#' }}",
        kids: "{{ Route::has('kids') ? route('kids') : '#' }}",
        beauty: "{{ Route::has('beauty') ? route('beauty') : '#' }}",
        accessories: "{{ Route::has('accessories') ? route('accessories') : '#' }}",
        about: "{{ Route::has('about') ? route('about') : '#' }}",
        login: "{{ Route::has('login') ? route('login') : '#' }}",
        cart: "{{ Route::has('cart') ? route('cart') : '#' }}",

        adminDashboard: "{{ $adminDashboardUrl }}",
isLoggedIn: "{{ $isLoggedIn ? 'true' : 'false' }}",
isAdmin: "{{ $isAdmin ? 'true' : 'false' }}",

locale: "{{ app()->getLocale() }}",
currency: "{{ session('currency', 'USD') }}",


    };

    // تبديل اللغة والعملة من الهيدر
    document.addEventListener('DOMContentLoaded', function () {
        var localeSelect = document.querySelector('.js-locale-switch');
        if (localeSelect) {
            localeSelect.addEventListener('change', function (e) {
                window.location.href = '/locale/' + encodeURIComponent(e.target.value);
            });
        }

        var currencySelect = document.querySelector('.js-currency-switch');
        if (currencySelect) {
            currencySelect.addEventListener('change', function (e) {
                window.location.href = '/currency/' + encodeURIComponent(e.target.value);
            });
        }

        var searchToggle = document.querySelector('.js-search-toggle');
        if (searchToggle) {
            searchToggle.addEventListener('click', function () {
                document.body.classList.toggle('search-open');
            });
        }
    });
</script>

</body>
</html>
