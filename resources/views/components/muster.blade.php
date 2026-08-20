<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#5A3A30">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'POST') }}</title>
    <meta name="description" content="{{ $description ?? '' }}">

    <!-- Open Graph / social sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'POST') }}">
    <meta property="og:description" content="{{ $description ?? '' }}">
    <meta property="og:image" content="{{ asset('images/post-logo.png') }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? config('app.name', 'POST') }}">
    <meta name="twitter:description" content="{{ $description ?? '' }}">

    <link rel="icon" href="{{ asset('images/post-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/post-logo.png') }}">

    <!-- Brand typefaces: Fraunces (display) + Manrope (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,450;0,9..144,600;1,9..144,450&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="{{ asset('app.js') }}" defer></script>

    <style>
        :root{
            --cream:#F6EFE6;
            --cream-2:#EEE2D3;
            --white:#FFFBF6;
            --ink:#2B2018;
            --ink-soft:#5B4C40;
            --rose:#B0715C;
            --rose-deep:#8C4E38;
            --umber:#5A3A30;
            --blush:#E9C7BA;
            --blush-2:#E6B6A2;
            --clay:#C9967E;
            --line:#E1D2C1;
            --line-soft:#EADFD0;
            --radius:14px;
            --ease:cubic-bezier(.22,.61,.36,1);
        }

        *{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}

        body{
            display:flex;
            flex-direction:column;
            min-height:100vh;
            background:var(--cream);
            color:var(--ink);
            font-family:'Manrope',sans-serif;
            font-size:16px;
            line-height:1.5;
            -webkit-font-smoothing:antialiased;
        }

        .main-content{flex:1;display:flex;flex-direction:column}

        img,svg{display:block;max-width:100%}
        a{color:inherit;text-decoration:none}
        h1,h2,h3{font-family:'Fraunces',serif;font-weight:450;letter-spacing:-.01em;color:var(--ink)}
        em{font-style:italic;color:var(--rose-deep)}

        .container{max-width:1200px;margin:0 auto;padding:0 clamp(1.25rem,3vw,2.5rem)}

        .sr-only{
            position:absolute;width:1px;height:1px;padding:0;margin:-1px;
            overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0
        }

        /* RTL support */
        html[dir="rtl"] .utility-bar__ship{flex-direction:row-reverse}
        html[dir="rtl"] .nav__links{flex-direction:row-reverse}
        html[dir="rtl"] .footer-grid{direction:rtl}
        html[dir="rtl"] .news__form{flex-direction:row-reverse}

        /* ---------- Utility reveal ---------- */
        .reveal{opacity:0;transform:translateY(18px);transition:opacity .8s var(--ease),transform .8s var(--ease)}
        .reveal.in{opacity:1;transform:none}
        .reveal[data-d="1"]{transition-delay:.08s}
        .reveal[data-d="2"]{transition-delay:.16s}
        .reveal[data-d="3"]{transition-delay:.24s}
        @media (prefers-reduced-motion: reduce){.reveal{opacity:1;transform:none;transition:none}}

        .eyebrow{display:inline-block;font-family:'Manrope',sans-serif;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--rose-deep);font-weight:700;margin-bottom:.6rem}
        .muted{color:var(--ink-soft)}
        .lead{font-size:1.08rem;color:var(--ink-soft);max-width:46ch}

        .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 1.6rem;border-radius:999px;font-weight:600;font-size:.92rem;transition:transform .3s var(--ease),background .3s,color .3s,border-color .3s}
        .btn--rose{background:var(--rose-deep);color:var(--white)}
        .btn--rose:hover{background:var(--umber);transform:translateY(-2px)}
        .btn--ghost{border:1px solid var(--ink);color:var(--ink)}
        .btn--ghost:hover{background:var(--ink);color:var(--white);transform:translateY(-2px)}
        .btn--light{background:var(--rose-deep);color:#fff;border:none;padding:.85rem 1.6rem;border-radius:999px;font-weight:600;cursor:pointer}

        /* ---------- Top utility bar (international) ---------- */
        .utility-bar{background:var(--umber);color:#f2e3d8;font-size:.78rem}
        .utility-bar .container{display:flex;align-items:center;justify-content:space-between;padding-block:.55rem;gap:1rem;flex-wrap:wrap}
        .utility-bar__ship{display:flex;align-items:center;gap:.5rem;letter-spacing:.02em}
        .utility-bar__ship svg{width:14px;height:14px;opacity:.85}
        .switchers{display:flex;gap:1.1rem;align-items:center}
        .switcher{position:relative}
        .switcher select{
            appearance:none;background:transparent;border:none;color:#f2e3d8;font:inherit;font-size:.78rem;
            padding-right:1.1rem;cursor:pointer;letter-spacing:.02em;
        }
        .switcher::after{content:"";position:absolute;right:0;top:50%;width:6px;height:6px;border-right:1.4px solid #f2e3d8;border-bottom:1.4px solid #f2e3d8;transform:translateY(-70%) rotate(45deg);pointer-events:none}
        .switcher select option{color:#2B2018}

        /* ---------- Header ---------- */
        header{position:sticky;top:0;z-index:40;background:rgba(246,239,230,.88);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
        .nav{display:flex;align-items:center;justify-content:space-between;padding-block:1.1rem}
        .wordmark{font-family:'Fraunces',serif;font-size:1.5rem;letter-spacing:.02em}
        .nav__links{display:flex;gap:2.1rem;font-size:.92rem;font-weight:500}
        .nav__links a{position:relative;padding-bottom:.2rem}
        .nav__links a::after{content:"";position:absolute;left:0;bottom:0;width:0;height:1px;background:var(--rose-deep);transition:width .3s var(--ease)}
        .nav__links a:hover::after{width:100%}
        .nav__icons{display:flex;gap:1.2rem;align-items:center}
        .nav__icons svg{width:19px;height:19px;stroke:var(--ink)}
        .icon-btn{background:none;border:none;padding:0;cursor:pointer;color:inherit;display:inline-flex;align-items:center;justify-content:center}
        .cart-badge{position:absolute;top:-8px;right:-10px;min-width:16px;height:16px;padding:0 4px;border-radius:999px;background:var(--rose-deep);color:var(--white);font-size:.65rem;line-height:16px;text-align:center;font-weight:700}
        @media (max-width:860px){ .nav__links{display:none} }

        /* ---------- Hero ---------- */
        .hero{position:relative;overflow:hidden}
        .hero__bg{position:absolute;inset:0;background:radial-gradient(circle at 82% 15%,var(--blush) 0%,transparent 55%);opacity:.55;pointer-events:none}
        .hero__inner{position:relative;padding-block:clamp(3.5rem,8vw,6.5rem)}
        .hero__grid{display:grid;grid-template-columns:1.05fr .95fr;gap:clamp(2rem,5vw,4rem);align-items:center}
        @media (max-width:900px){.hero__grid{grid-template-columns:1fr}}
        .display{font-size:clamp(2.6rem,6vw,4.4rem);line-height:1.03}
        .hero__cta{display:flex;gap:1rem;margin-top:2.1rem;flex-wrap:wrap}
        .hero__visual{aspect-ratio:4/5;border-radius:var(--radius);position:relative;overflow:hidden}
        .hero__floating{position:absolute;left:1.2rem;bottom:1.2rem;background:rgba(255,251,246,.92);color:var(--ink);border-radius:10px;padding:.75rem 1rem;display:flex;align-items:center;gap:.65rem;box-shadow:0 12px 30px -12px rgba(43,32,24,.35)}
        .hero__floating small{display:block;font-size:.68rem;color:var(--ink-soft);text-transform:uppercase;letter-spacing:.08em}
        .hero__floating strong{font-family:'Fraunces',serif;font-weight:500}
        .dot{width:9px;height:9px;border-radius:50%;background:var(--rose-deep);flex-shrink:0}

        /* ---------- Marquee ---------- */
        .marquee{background:var(--ink);color:var(--cream);overflow:hidden;padding-block:.75rem}
        .marquee__track{display:flex;gap:2.5rem;white-space:nowrap;animation:scroll 26s linear infinite;font-size:.82rem;letter-spacing:.04em;text-transform:uppercase}
        .marquee__track span{display:flex;align-items:center;gap:2.5rem}
        .marquee__track span::after{content:"✦";color:var(--blush-2);font-size:.7rem}
        @keyframes scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}

        /* ---------- Sections generic ---------- */
        .section{padding-block:clamp(3.5rem,7vw,6rem)}
        .section--tight{padding-block:clamp(2.5rem,5vw,4rem)}
        .sec-head{display:flex;align-items:flex-end;justify-content:space-between;gap:1.5rem;margin-bottom:2.4rem;flex-wrap:wrap}
        .h-section{font-size:clamp(1.7rem,3.4vw,2.4rem)}
        .link-underline{font-size:.9rem;font-weight:600;border-bottom:1px solid var(--ink);padding-bottom:.15rem}

        /* ---------- Categories ---------- */
        .cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.2rem}
        @media (max-width:900px){.cat-grid{grid-template-columns:repeat(2,1fr)}}
        .cat-card{position:relative;border-radius:var(--radius);overflow:hidden;aspect-ratio:3/4;display:flex;flex-direction:column;justify-content:flex-end;padding:1.3rem;color:#fff;isolation:isolate}
        .cat-card__art{position:absolute;inset:0;z-index:-1;transition:transform .6s var(--ease)}
        .cat-card:hover .cat-card__art{transform:scale(1.06)}
        .cat-card::before{content:"";position:absolute;inset:0;background:linear-gradient(to top,rgba(20,12,8,.65),transparent 55%);z-index:-1}
        .cat-card small{opacity:.85;font-size:.72rem;text-transform:uppercase;letter-spacing:.08em}
        .cat-card h3{color:#fff;font-size:1.35rem;margin-top:.15rem}
        .cat-card .arrow{position:absolute;top:1.1rem;right:1.1rem;width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.18);display:grid;place-items:center;transition:transform .35s var(--ease),background .35s}
        .cat-card:hover .arrow{background:var(--rose-deep);transform:translate(3px,-3px)}

        /* ---------- Product grid ---------- */
        .prod-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.4rem 1.2rem}
        @media (max-width:900px){.prod-grid{grid-template-columns:repeat(2,1fr)}}
        .prod-card{cursor:pointer}
        .prod-card__img{aspect-ratio:3/4;border-radius:10px;overflow:hidden;position:relative;margin-bottom:.85rem}
        .prod-card__img span.tag{position:absolute;top:.7rem;left:.7rem;background:var(--white);color:var(--rose-deep);font-size:.66rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:.3rem .6rem;border-radius:999px}
        .prod-card__name{font-family:'Fraunces',serif;font-size:1.02rem;margin-bottom:.15rem}
        .prod-card__meta{display:flex;justify-content:space-between;font-size:.86rem;color:var(--ink-soft)}
        .prod-card__price{color:var(--ink);font-weight:600}

        /* ---------- Split ---------- */
        .split{display:grid;grid-template-columns:1fr 1fr;gap:clamp(2rem,5vw,4rem);align-items:center}
        @media (max-width:900px){.split{grid-template-columns:1fr}}
        .split__media{aspect-ratio:5/4;border-radius:var(--radius);overflow:hidden}
        .split__body p{margin-bottom:0}

        /* ---------- Values ---------- */
        .values-strip{background:var(--cream-2);border-block:1px solid var(--line)}
        .values{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem}
        @media (max-width:900px){.values{grid-template-columns:repeat(2,1fr)}}
        .value{padding:1.6rem 1.1rem}
        .value h3{font-size:1.1rem;margin-bottom:.4rem}
        .value p{font-size:.88rem;color:var(--ink-soft)}
        .value .num{font-size:.72rem;letter-spacing:.1em;color:var(--rose-deep);font-weight:700;margin-bottom:.55rem;display:block}

        /* ---------- International / signature section ---------- */
        .world{position:relative;background:var(--umber);color:var(--cream);overflow:hidden}
        .world .container{position:relative;padding-block:clamp(4rem,8vw,6.5rem);display:grid;grid-template-columns:1fr 1fr;gap:clamp(2rem,5vw,3.5rem);align-items:center}
        @media (max-width:900px){.world .container{grid-template-columns:1fr}}
        .world .eyebrow{color:var(--blush-2)}
        .world h2{color:var(--cream)}
        .world p{color:#e4d3c6}
        .world-map{width:100%;stroke:#e4d3c6;opacity:.9}
        .world-map .route{stroke-dasharray:4 5;animation:dash 22s linear infinite}
        .world-map .city{fill:var(--blush-2)}
        @keyframes dash{to{stroke-dashoffset:-200}}
        .lang-chips{display:flex;flex-wrap:wrap;gap:.55rem;margin-top:1.8rem}
        .lang-chips span{border:1px solid rgba(244,234,224,.35);border-radius:999px;padding:.42rem .95rem;font-size:.78rem;letter-spacing:.03em}
        .ship-stats{display:flex;gap:2.4rem;margin-top:2rem;flex-wrap:wrap}
        .ship-stats div strong{display:block;font-family:'Fraunces',serif;font-size:1.9rem;color:var(--cream)}
        .ship-stats div small{font-size:.75rem;color:#cbb8aa;letter-spacing:.04em;text-transform:uppercase}

        /* ---------- Newsletter (used in this layout as .news) ---------- */
        .news{position:relative;background:var(--blush);border-radius:var(--radius);text-align:center;padding:clamp(2.5rem,6vw,4rem) 1.5rem;overflow:hidden}
        .news__form{display:flex;gap:.6rem;margin-top:1.4rem;max-width:420px;margin-inline:auto}
        .news__form input{flex:1;padding:.85rem 1.1rem;border-radius:999px;border:1px solid var(--rose-deep);background:var(--white);font:inherit}
        .news input:focus-visible,a:focus-visible,button:focus-visible,select:focus-visible{outline:2px solid var(--rose-deep);outline-offset:2px}

        /* ---------- Footer ---------- */
        footer{background:var(--ink);color:#d9cabd;padding-block:3.5rem 2rem}
        .footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:2.5rem}
        @media (max-width:800px){.footer-grid{grid-template-columns:1fr 1fr}}
        .footer-grid h4{font-size:.78rem;text-transform:uppercase;letter-spacing:.1em;color:#efe3d8;margin-bottom:1rem}
        .footer-grid ul{list-style:none;display:flex;flex-direction:column;gap:.6rem;font-size:.88rem}
        .footer-grid ul a:hover{color:var(--blush-2)}
        .foot-word{font-family:'Fraunces',serif;color:var(--cream);font-size:1.4rem;margin-bottom:.8rem}
        .foot-bottom{margin-top:3rem;padding-top:1.6rem;border-top:1px solid rgba(217,202,189,.18);display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;font-size:.78rem}

        /* ---------- Toast (رسائل نجاح/خطأ سريعة، مثلاً عند إضافة منتج للسلة) ---------- */
        #toastStack{position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:9999;display:flex;flex-direction:column;gap:.6rem;align-items:center;pointer-events:none}
        .toast{pointer-events:auto;background:var(--ink);color:#fff;padding:.85rem 1.4rem;border-radius:999px;font-size:.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.18);opacity:0;transform:translateY(10px);transition:opacity .25s ease,transform .25s ease;max-width:90vw;text-align:center}
        .toast.show{opacity:1;transform:translateY(0)}
        .toast.toast--error{background:#A23B2E}
        .toast.toast--success{background:var(--rose-deep)}
    </style>
</head>

<body data-page="index" data-locale="{{ app()->getLocale() }}">

<div id="toastStack" aria-live="polite"></div>

@php
    // بنحسب هالقيم مرة وحدة هون فوق، بدل ما نكررهم بأكثر من مكان بالصفحة
    // (بالهيدر وبـ window.navLinks). استخدام ?-> (nullsafe) بيمنع Fatal Error
    // لو auth()->user() رجع null رغم إن auth()->check() صار true.
    $isLoggedIn = auth()->check();
    $isAdmin = $isLoggedIn && auth()->user()?->is_admin;
    $adminDashboardUrl = ($isAdmin && Route::has('admin.dashboard')) ? route('admin.dashboard') : '';
    // عدد قطع السلة الحقيقي (بدون إنشاء سلة جديدة لكل زائر، انظر Cart::currentCount)
    $cartCount = \App\Models\Cart::currentCount();
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

                <a href="{{ Route::has('cart') ? route('cart') : '#' }}" class="icon-btn" style="position:relative" aria-label="{{ __('Cart') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" focusable="false">
                        <path d="M6 7h12l-1 13H7L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
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
                <span class="eyebrow" style="color:var(--blush-2)">{{ __('Join the house') }}</span>
                <h2 class="h-section" style="margin-top:.8rem">{{ __('First stories, first access') }}</h2>
                <p class="lead" style="margin-inline:auto">{{ __('Be the first to read new origin stories, see new collections, and receive a little something for your first order — wherever you are in the world.') }}</p>
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
                    @php
                        // روابط السوشيال ميديا قابلة للتعديل من لوحة التحكم (admin/settings).
                        // واتساب: نقبل رابطاً جاهزاً أو رقماً فنبني منه رابط wa.me.
                        $waRaw = \App\Models\Setting::get('social_whatsapp');
                        $waUrl = $waRaw
                            ? (\Illuminate\Support\Str::startsWith($waRaw, ['http://', 'https://'])
                                ? $waRaw
                                : 'https://wa.me/' . preg_replace('/\D+/', '', $waRaw))
                            : null;
                        $socials = array_filter([
                            'Instagram' => \App\Models\Setting::get('social_instagram'),
                            'Facebook'  => \App\Models\Setting::get('social_facebook'),
                            'TikTok'    => \App\Models\Setting::get('social_tiktok'),
                            'YouTube'   => \App\Models\Setting::get('social_youtube'),
                            'X'         => \App\Models\Setting::get('social_twitter'),
                            'WhatsApp'  => $waUrl,
                        ]);
                        $socialIcons = [
                            'Instagram' => '📸', 'Facebook' => '📘', 'TikTok' => '🎵',
                            'YouTube' => '▶️', 'X' => '✖️', 'WhatsApp' => '💬',
                        ];
                    @endphp
                    @if(!empty($socials))
                        <div style="display:flex;gap:.8rem;margin-top:1.2rem;flex-wrap:wrap">
                            @foreach($socials as $label => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="{{ $label }}" title="{{ $label }}"
                                   style="width:36px;height:36px;border-radius:50%;background:rgba(217,202,189,.12);display:grid;place-items:center;font-size:1rem;transition:background .3s">{{ $socialIcons[$label] ?? '🔗' }}</a>
                            @endforeach
                        </div>
                    @endif
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
        contact: "{{ Route::has('contact') ? route('contact') : '#' }}",

        adminDashboard: "{{ $adminDashboardUrl }}",
        isLoggedIn: "{{ $isLoggedIn ? 'true' : 'false' }}",
        isAdmin: "{{ $isAdmin ? 'true' : 'false' }}",

        locale: "{{ app()->getLocale() }}",
        currency: "{{ session('currency', 'USD') }}",
    };

    // عدد قطع السلة الحقيقي القادم من قاعدة البيانات (يُستخدم في public/app.js
    // لتحديث عدّاد السلة في الهيدر عند تحميل أي صفحة).
    window.cartCount = {{ (int) $cartCount }};

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
