<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#5A3A30">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">

    <!-- Open Graph / social sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ asset('post-logo.png') }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">

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

        /* Skeletons: reserve real header/footer height while app.js injects the
           actual markup, so the page doesn't jump (CLS) once it lands. Heights
           match utility-bar + nav (~104px) and footer (~340px) at desktop; adjust
           if you change those components' real height. */
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
            0% {
                background-position: 100% 0
            }

            100% {
                background-position: 0 0
            }
        }

        @media (prefers-reduced-motion:reduce) {

            .site-header-skeleton,
            .site-footer-skeleton {
                animation: none
            }
        }

        /* RTL support (Arabic / Hebrew / etc.) */
        html[dir="rtl"] .utility-bar__ship {
            flex-direction: row-reverse
        }

        html[dir="rtl"] .nav__links {
            flex-direction: row-reverse
        }

        html[dir="rtl"] .footer-grid {
            direction: rtl
        }

        html[dir="rtl"] .news__form {
            flex-direction: row-reverse
        }

        /* ---------- Utility bar ---------- */
        .utility-bar {
            background: var(--umber);
            color: #f2e3d8;
            font-size: .78rem
        }

        .utility-bar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-block: .55rem;
            gap: 1rem;
            flex-wrap: wrap
        }

        .utility-bar__ship {
            display: flex;
            align-items: center;
            gap: .5rem
        }

        .utility-bar__ship svg {
            width: 14px;
            height: 14px;
            opacity: .85
        }

        .switchers {
            display: flex;
            gap: 1.1rem;
            align-items: center
        }

        .switcher select {
            appearance: none;
            background: transparent;
            border: none;
            color: #f2e3d8;
            font: inherit;
            font-size: .78rem;
            cursor: pointer
        }

        .switcher select option {
            color: var(--ink)
        }

        @media (max-width:700px) {
            .utility-bar__ship span {
                display: none
            }
        }

        /* ---------- Header ---------- */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(246, 239, 230, .9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line)
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-block: 1rem
        }

        .wordmark {
            display: flex;
            align-items: center
        }

        .wordmark__logo {
            height: 28px;
            width: auto;
            display: block
        }

        .nav__links {
            display: flex;
            gap: 2.1rem;
            font-size: .92rem;
            font-weight: 500
        }

        .nav__links a {
            position: relative;
            padding-bottom: .2rem;
            color: var(--ink);
            text-decoration: none
        }

        .nav__links a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 1px;
            background: var(--rose-deep);
            transition: width .3s
        }

        .nav__links a:hover::after {
            width: 100%
        }

        .nav__icons {
            display: flex;
            gap: 1rem;
            align-items: center
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            color: var(--ink)
        }

        .icon-btn svg {
            width: 19px;
            height: 19px
        }

        .nav__burger {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer
        }

        .nav__burger span {
            width: 20px;
            height: 1.4px;
            background: var(--ink)
        }

        .nav__mobile {
            display: none;
            flex-direction: column;
            gap: .2rem;
            padding: 0 1.25rem 1rem;
            border-top: 1px solid var(--line)
        }

        .nav__mobile a {
            padding: .7rem 0;
            color: var(--ink);
            text-decoration: none;
            border-bottom: 1px solid var(--line)
        }

        @media (max-width:860px) {
            .nav__links {
                display: none
            }

            .nav__burger {
                display: flex
            }

            .nav__mobile.is-open {
                display: flex
            }
        }

        /* ---------- Newsletter ---------- */
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

        /* ---------- Footer ---------- */
        .site-footer {
            background: var(--ink);
            color: #d9cabd;
            padding-block: 3.5rem 2rem
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 2.5rem
        }

        @media (max-width:800px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr
            }
        }

        .footer-grid h4 {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #efe3d8;
            margin-bottom: 1rem
        }

        .footer-grid ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            font-size: .88rem;
            margin: 0;
            padding: 0
        }

        .footer-grid ul a {
            color: #d9cabd;
            text-decoration: none
        }

        .footer-grid ul a:hover {
            color: var(--rose-light)
        }

        .foot-word {
            font-family: 'Fraunces', serif;
            color: var(--cream);
            font-size: 1.4rem;
            text-decoration: none;
            display: inline-block;
            margin-bottom: .8rem
        }

        .foot-tagline {
            max-width: 30ch;
            font-size: .88rem;
            color: #c2b0a2
        }

        .foot-bottom {
            margin-top: 3rem;
            padding-top: 1.6rem;
            border-top: 1px solid rgba(217, 202, 189, .18);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: .78rem
        }
    </style>
</head>

<body data-page="index" data-locale="{{ app()->getLocale() }}">

    <!-- site-header is injected by app.js. Skeleton reserves the height (utility bar + nav) so nothing jumps once the real header lands. -->
    <div id="site-header" class="site-header-skeleton" aria-live="polite">
        <span class="sr-only">Loading…</span>
    </div>

    <main class="main-content">

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

    <!-- site-footer is injected by app.js -->
    <div id="site-footer" class="site-footer-skeleton" aria-live="polite">
        <span class="sr-only">Loading…</span>
    </div>
<script>
window.navLinks = {
    beauty: "{{ route('beauty') }}",
    accessories: "{{ route('accessories') }}",
    about: "{{ route('about') }}",
    login: "{{ route('login') }}",
    cart: "{{ route('cart') }}",

    adminDashboard: "{{ auth()->check() && auth()->user()->is_admin ? route('admin.dashboard') : '' }}",

    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},

    isAdmin: {{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }},

    locale: "{{ app()->getLocale() }}",

    currency: "{{ session('currency', 'USD') }}",

    availableLocales: @json(config('app.available_locales', ['en','fr','es','ar'])),

    availableCurrencies: @json(config('app.available_currencies', ['USD','EUR','GBP','AED']))
};
</script>

</body>

</html>
