<x-muster>
    <x-slot name="title">POST — Stories worth wearing</x-slot>
    <x-slot name="description">Considered clothing, beauty and accessories for women and children — each piece chosen for the story it begins, not just the season it fills. Shipped from New York to wherever you call home.</x-slot>
    <x-slot name="url">{{ url()->current() }}</x-slot>

    <style>
        /* ---------- Variables & Root Properties ---------- */
        :root {
            --cream: #F6EFE6;
            --cream-2: #EEE2D3;
            --white: #FFFBF6;
            --ink: #2B2018;
            --ink-soft: #5B4C40;
            --rose: #B0715C;
            --rose-deep: #8C4E38;
            --umber: #5A3A30;
            --blush: #E9C7BA;
            --blush-2: #E6B6A2;
            --clay: #C9967E;
            --line: #E1D2C1;
            --line-soft: #EADFD0;
            --radius: 14px;
            --ease: cubic-bezier(.22, .61, .36, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { background: var(--cream); color: var(--ink); font-family: 'Manrope', sans-serif; font-size: 16px; line-height: 1.5; -webkit-font-smoothing: antialiased; }
        img, svg { display: block; max-width: 100%; }
        a { color: inherit; text-decoration: none; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 clamp(1.25rem, 3vw, 2.5rem); }
        h1, h2, h3 { font-family: 'Fraunces', serif; font-weight: 450; letter-spacing: -.01em; color: var(--ink); }
        em { font-style: italic; color: var(--rose-deep); }

        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .8s var(--ease), transform .8s var(--ease); }
        .reveal.in { opacity: 1; transform: none; }
        .reveal[data-d="1"] { transition-delay: .08s; }
        .reveal[data-d="2"] { transition-delay: .16s; }
        .reveal[data-d="3"] { transition-delay: .24s; }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
        }

        .eyebrow { display: inline-block; font-size: .72rem; letter-spacing: .16em; text-transform: uppercase; color: var(--rose-deep); font-weight: 700; margin-bottom: .6rem; }
        .muted { color: var(--ink-soft); }
        .lead { font-size: 1.08rem; color: var(--ink-soft); max-width: 46ch; }

        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .85rem 1.6rem; border-radius: 999px; font-weight: 600; font-size: .92rem; cursor: pointer; border: none; transition: transform .3s var(--ease), background .3s, color .3s; }
        .btn--rose { background: var(--rose-deep); color: var(--white); }
        .btn--rose:hover { background: var(--umber); transform: translateY(-2px); }
        .btn--ghost { border: 1px solid var(--ink); color: var(--ink); background: transparent; }
        .btn--ghost:hover { background: var(--ink); color: var(--white); transform: translateY(-2px); }

        .hero { position: relative; overflow: hidden; }
        .hero__bg { position: absolute; inset: 0; background: radial-gradient(circle at 82% 15%, var(--blush) 0%, transparent 55%); opacity: .55; pointer-events: none; }
        .hero__inner { position: relative; padding-block: clamp(3.5rem, 8vw, 6.5rem); }
        .hero__grid { display: grid; grid-template-columns: 1.05fr .95fr; gap: clamp(2rem, 5vw, 4rem); align-items: center; }
        @media (max-width: 900px) { .hero__grid { grid-template-columns: 1fr; } }
        .display { font-size: clamp(2.6rem, 6vw, 4.4rem); line-height: 1.03; }
        .hero__cta { display: flex; gap: 1rem; margin-top: 2.1rem; flex-wrap: wrap; }
        .hero__visual { aspect-ratio: 4/5; border-radius: var(--radius); position: relative; overflow: hidden; }
        .hero__floating { position: absolute; left: 1.2rem; bottom: 1.2rem; background: rgba(255, 251, 246, .92); color: var(--ink); border-radius: 10px; padding: .75rem 1rem; display: flex; align-items: center; gap: .65rem; box-shadow: 0 12px 30px -12px rgba(43, 32, 24, .35); }
        .hero__floating small { display: block; font-size: .68rem; color: var(--ink-soft); text-transform: uppercase; letter-spacing: .08em; }
        .hero__floating strong { font-family: 'Fraunces', serif; font-weight: 500; }
        .dot { width: 9px; height: 9px; border-radius: 50%; background: var(--rose-deep); flex-shrink: 0; }

        .marquee { background: var(--ink); color: var(--cream); overflow: hidden; padding-block: .75rem; }
        .marquee__track { display: flex; gap: 2.5rem; white-space: nowrap; animation: scroll 26s linear infinite; font-size: .82rem; letter-spacing: .04em; text-transform: uppercase; }
        .marquee__track span { display: flex; align-items: center; gap: 2.5rem; }
        .marquee__track span::after { content: "✦"; color: var(--blush-2); font-size: .7rem; }
        @keyframes scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        .section { padding-block: clamp(3.5rem, 7vw, 6rem); }
        .section--tight { padding-block: clamp(2.5rem, 5vw, 4rem); }
        .sec-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; margin-bottom: 2.4rem; flex-wrap: wrap; }
        .h-section { font-size: clamp(1.7rem, 3.4vw, 2.4rem); }
        .link-underline { font-size: .9rem; font-weight: 600; border-bottom: 1px solid var(--ink); padding-bottom: .15rem; }

        .cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem; }
        @media (max-width: 900px) { .cat-grid { grid-template-columns: repeat(2, 1fr); } }
        .cat-card { position: relative; border-radius: var(--radius); overflow: hidden; aspect-ratio: 3/4; display: flex; flex-direction: column; justify-content: flex-end; padding: 1.3rem; color: #fff; isolation: isolate; }
        .cat-card__art { position: absolute; inset: 0; z-index: -1; transition: transform .6s var(--ease); }
        .cat-card:hover .cat-card__art { transform: scale(1.06); }
        .cat-card::before { content: ""; position: absolute; inset: 0; background: linear-gradient(to top, rgba(20, 12, 8, .65), transparent 55%); z-index: -1; }
        .cat-card small { opacity: .85; font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; }
        .cat-card h3 { color: #fff; font-size: 1.35rem; margin-top: .15rem; }
        .cat-card .arrow { position: absolute; top: 1.1rem; right: 1.1rem; width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 255, 255, .18); display: grid; place-items: center; transition: transform .35s var(--ease), background .35s; }
        .cat-card:hover .arrow { background: var(--rose-deep); transform: translate(3px, -3px); }

        .prod-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.4rem 1.2rem; }
        @media (max-width: 900px) { .prod-grid { grid-template-columns: repeat(2, 1fr); } }
        .prod-card { cursor: pointer; }
        .prod-card__img { aspect-ratio: 3/4; border-radius: 10px; overflow: hidden; position: relative; margin-bottom: .85rem; background-size: cover; background-position: center; }
        .prod-card__img span.tag { position: absolute; top: .7rem; left: .7rem; background: var(--white); color: var(--rose-deep); font-size: .66rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: .3rem .6rem; border-radius: 999px; }
        .prod-card__name { font-family: 'Fraunces', serif; font-size: 1.02rem; margin-bottom: .15rem; }
        .prod-card__meta { display: flex; justify-content: space-between; font-size: .86rem; color: var(--ink-soft); }
        .prod-card__price { color: var(--ink); font-weight: 600; }

        .split { display: grid; grid-template-columns: 1fr 1fr; gap: clamp(2rem, 5vw, 4rem); align-items: center; }
        @media (max-width: 900px) { .split { grid-template-columns: 1fr; } }
        .split__media { aspect-ratio: 5/4; border-radius: var(--radius); overflow: hidden; }
        .split__media--art { background: linear-gradient(155deg, #5a3a30, #9E5F4D 60%, #C08B73); display: grid; place-items: center; color: #f4dccf; }
        .split__media-icon { width: 42%; opacity: .9; }

        /* ---------- Inline styles moved here ---------- */
        .hero__visual--art { background: linear-gradient(155deg, #E9C7BA, #B0715C 70%, #8C4E38); color: #fff2ea; display: grid; place-items: center; }
        .hero__visual-icon { width: 56%; opacity: .92; }
        .cat-card__art--women { background: linear-gradient(150deg, #C9967E, #8C4E38); }
        .cat-card__art--kids { background: linear-gradient(150deg, #E6B6A2, #B0715C); }
        .cat-card__art--beauty { background: linear-gradient(150deg, #A8634F, #5a3a30); }
        .cat-card__art--accessories { background: linear-gradient(150deg, #BBA189, #7E6E5C); }
        .origin-title { margin-bottom: 1rem; }
        .origin-lead { margin-bottom: 1.6rem; }
        .world-lead { max-width: 44ch; }
        .newsletter-lead { margin-inline: auto; }

        .values-strip { background: var(--cream-2); border-block: 1px solid var(--line); }
        .values { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        @media (max-width: 900px) { .values { grid-template-columns: repeat(2, 1fr); } }
        .value { padding: 1.6rem 1.1rem; }
        .value h3 { font-size: 1.1rem; margin-bottom: .4rem; }
        .value p { font-size: .88rem; color: var(--ink-soft); }
        .value .num { font-size: .72rem; letter-spacing: .1em; color: var(--rose-deep); font-weight: 700; margin-bottom: .55rem; display: block; }

        .world { position: relative; background: var(--umber); color: var(--cream); overflow: hidden; }
        .world .container { position: relative; padding-block: clamp(4rem, 8vw, 6.5rem); display: grid; grid-template-columns: 1fr 1fr; gap: clamp(2rem, 5vw, 3.5rem); align-items: center; }
        @media (max-width: 900px) { .world .container { grid-template-columns: 1fr; } }
        .world .eyebrow { color: var(--blush-2); }
        .world h2 { color: var(--cream); }
        .world p { color: #e4d3c6; }
        .world-map { width: 100%; stroke: #e4d3c6; opacity: .9; }
        .world-map .route { stroke-dasharray: 4 5; animation: dash 22s linear infinite; }
        .world-map .city { fill: var(--blush-2); }
        @keyframes dash { to { stroke-dashoffset: -200; } }

        .lang-chips { display: flex; flex-wrap: wrap; gap: .55rem; margin-top: 1.8rem; }
        .lang-chips span { border: 1px solid rgba(244, 234, 224, .35); border-radius: 999px; padding: .42rem .95rem; font-size: .78rem; letter-spacing: .03em; }
        .ship-stats { display: flex; gap: 2.4rem; margin-top: 2rem; flex-wrap: wrap; }
        .ship-stats div strong { display: block; font-family: 'Fraunces', serif; font-size: 1.9rem; color: var(--cream); }
        .ship-stats div small { font-size: .75rem; color: #cbb8aa; letter-spacing: .04em; text-transform: uppercase; }

        .newsletter { background: var(--blush); }
        .newsletter .container { padding-block: clamp(3rem, 6vw, 4.5rem); text-align: center; max-width: 640px; }
        .newsletter form { display: flex; gap: .6rem; margin-top: 1.6rem; max-width: 420px; margin-inline: auto; }
        .newsletter input { flex: 1; padding: .85rem 1.1rem; border-radius: 999px; border: 1px solid var(--rose-deep); background: var(--white); font: inherit; font-size: .9rem; }
        .newsletter input:focus-visible, a:focus-visible, button:focus-visible { outline: 2px solid var(--rose-deep); outline-offset: 2px; }
    </style>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero__bg"></div>
        <div class="container hero__inner">
            <div class="hero__grid">
                <div class="hero__copy">
                    <span class="eyebrow reveal in">Spring / Summer Collection</span>
                    <h1 class="display reveal" data-d="1">Stories<br>worth <em>wearing.</em></h1>
                    <p class="lead reveal" data-d="2">Considered clothing, beauty and accessories for women and children — each piece chosen for the story it begins, not just the season it fills. Shipped from New York to wherever you call home.</p>
                    <div class="hero__cta reveal" data-d="3">
                        <a class="btn btn--rose" href="#women">Shop Women</a>
                        <a class="btn btn--ghost" href="#kids">Shop Children</a>
                    </div>
                </div>
                <div class="hero__visual hero__visual--art reveal" data-d="2">
                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" class="hero__visual-icon" aria-hidden="true">
                        <path d="M40 22 L33 31 L38 35 Q35 56 30 78 L70 78 Q65 56 62 35 L67 31 L60 22 Q50 28 40 22 Z" />
                        <path d="M44 23 Q50 27 56 23" />
                        <path d="M38 50 Q50 54 62 50" />
                    </svg>
                    <div class="hero__floating">
                        <span class="dot"></span>
                        <div><small>Origin</small><strong>Como, Italy</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MARQUEE -->
    <div class="marquee" aria-hidden="true">
        <div class="marquee__track">
            <span>Made to be kept</span><span>Traceable materials</span><span>Carbon-neutral shipping</span><span>Designed in New York</span><span>Delivered worldwide</span>
            <span>Made to be kept</span><span>Traceable materials</span><span>Carbon-neutral shipping</span><span>Designed in New York</span><span>Delivered worldwide</span>
        </div>
    </div>

    <!-- CATEGORIES -->
    <section class="section container">
        <div class="sec-head">
            <div class="reveal">
                <span class="eyebrow">Find your way in</span>
                <h2 class="h-section">Four worlds, one quiet sensibility</h2>
            </div>
            <a class="link-underline muted reveal" data-d="1" href="#women">Browse all</a>
        </div>
        <div class="cat-grid">
            <a class="cat-card reveal" id="women" href="/women">
                <div class="cat-card__art cat-card__art--women"></div>
                <span class="arrow">→</span>
                <div><small>For her</small><h3>Women</h3></div>
            </a>
            <a class="cat-card reveal" data-d="1" id="kids" href="/kids">
                <div class="cat-card__art cat-card__art--kids"></div>
                <span class="arrow">→</span>
                <div><small>Little ones</small><h3>Children</h3></div>
            </a>
            <a class="cat-card reveal" data-d="2" href="/beauty">
                <div class="cat-card__art cat-card__art--beauty"></div>
                <span class="arrow">→</span>
                <div><small>Skin &amp; colour</small><h3>Beauty</h3></div>
            </a>
            <a class="cat-card reveal" data-d="3" href="/accessories">
                <div class="cat-card__art cat-card__art--accessories"></div>
                <span class="arrow">→</span>
                <div><small>Finishing touches</small><h3>Accessories</h3></div>
            </a>
        </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <section class="section--tight container">
        <div class="sec-head">
            <div class="reveal">
                <span class="eyebrow">The Edit</span>
                <h2 class="h-section">Pieces we're loving now</h2>
            </div>
            <a class="link-underline muted reveal" data-d="1" href="#">See more</a>
        </div>

        <div class="prod-grid reveal">
            @forelse($products as $product)
                <a class="prod-card" href="{{ url('/products/'.$product->id) }}">
                    {{-- عرض صورة المنتج الفعلية من قاعدة البيانات، أو خلفية تدرج افتراضية إذا لم تتوفر صورة --}}
                    <div class="prod-card__img"
                         style="background: '{{ $product->image ? "url('".e($product->image)."') center/cover" : 'linear-gradient(150deg,#E6B6A2,#B0715C)' }};">
                        @if(!empty($product->tag))
                            <span class="tag">{{ $product->tag }}</span>
                        @endif
                    </div>

                    <div class="prod-card__name">{{ $product->name }}</div>

                    <div class="prod-card__meta">
                        <span>{{ $product->category?->name ?? $product->category ?? 'General' }}</span>
                        <span class="prod-card__price">${{ number_format($product->price, 2) }}</span>
                    </div>
                </a>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="muted">لا توجد منتجات متاحة حالياً.</p>
                </div>
            @endforelse
        </div>

        {{-- إمكانية الترقيم Pagination إذا كانت البيانات مقسمة --}}
        @if(method_exists($products, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </section>

    <!-- EDITORIAL SPLIT -->
    <section class="section container">
        <div class="split reveal">
            <div class="split__media split__media--art">
                <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="split__media-icon" aria-hidden="true">
                    <rect x="36" y="40" width="28" height="38" rx="6" />
                    <rect x="44" y="27" width="12" height="13" rx="2" />
                    <rect x="42" y="19" width="16" height="8" rx="2" />
                    <line x1="36" y1="56" x2="64" y2="56" />
                </svg>
            </div>
            <div class="split__body">
                <span class="eyebrow">Our name, our promise</span>
                <h2 class="h-section origin-title">Premium Origin Stories &amp; Thoughts</h2>
                <p class="lead origin-lead">Every garment, every shade, every accessory arrives with a card that tells you where it began — the mill, the maker, the hands. We believe knowing the origin is part of the pleasure of owning something well made.</p>
                <a class="btn btn--ghost" href="#">Read the house story</a>
            </div>
        </div>
    </section>

    <!-- VALUES -->
    <section class="values-strip">
        <div class="container section--tight">
            <div class="values">
                <div class="value reveal"><span class="num">TRACE</span><h3>Traceable</h3><p>Sourced from named mills and makers, never anonymous supply chains.</p></div>
                <div class="value reveal" data-d="1"><span class="num">NEUTRAL</span><h3>Carbon-neutral</h3><p>Every order ships carbon-neutral in recycled, plastic-free packaging.</p></div>
                <div class="value reveal" data-d="2"><span class="num">LASTING</span><h3>Made to last</h3><p>Built for years of wear, with care notes to keep them at their best.</p></div>
                <div class="value reveal" data-d="3"><span class="num">KIND</span><h3>Kind by default</h3><p>Cruelty-free beauty and responsibly chosen fibres across the house.</p></div>
            </div>
        </div>
    </section>

    <!-- INTERNATIONAL / SIGNATURE SECTION -->
    <section class="world">
        <div class="container">
            <div class="reveal">
                <span class="eyebrow">One house, everywhere</span>
                <h2 class="h-section">A story that travels well</h2>
                <p class="lead world-lead">From the mill in Como to a doorstep in Seoul, Toronto or Lagos — POST ships to more than 120 countries, in your language and your currency, with duties calculated before you check out.</p>
                <div class="lang-chips">
                    <span>EN</span><span>FR</span><span>ES</span><span>DE</span><span>日本語</span><span>العربية</span><span>+ 14 more</span>
                </div>
                <div class="ship-stats">
                    <div><strong>120+</strong><small>Countries served</small></div>
                    <div><strong>9</strong><small>Languages</small></div>
                    <div><strong>2–6</strong><small>Days to major cities</small></div>
                </div>
            </div>
            <svg class="world-map reveal" data-d="2" viewBox="0 0 400 220" fill="none" stroke-width="1" aria-hidden="true">
                <path d="M20 90 Q60 40 120 55 T220 45 T320 70 T380 95" stroke-opacity=".35"/>
                <path d="M10 140 Q90 170 170 130 T300 150 T390 130" stroke-opacity=".35"/>
                <path class="route" d="M60 60 Q160 20 260 75" stroke-opacity=".9"/>
                <path class="route" d="M60 60 Q40 130 150 165" stroke-opacity=".9"/>
                <path class="route" d="M260 75 Q320 40 355 90" stroke-opacity=".9"/>
                <circle class="city" cx="60" cy="60" r="3.2"/>
                <circle class="city" cx="260" cy="75" r="3.2"/>
                <circle class="city" cx="150" cy="165" r="3.2"/>
                <circle class="city" cx="355" cy="90" r="3.2"/>
                <circle class="city" cx="20" cy="90" r="2.4"/>
            </svg>
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section class="newsletter">
        <div class="container">
            <span class="eyebrow">Join the house</span>
            <h2 class="h-section">First stories, first access</h2>
            <p class="lead newsletter-lead">Be the first to read new origin stories, see new collections, and receive a little something for your first order — wherever you are in the world.</p>
            <form onsubmit="event.preventDefault(); this.querySelector('button').textContent='Subscribed ✓';">
                <input type="email" placeholder="Your email address" required aria-label="Email address">
                <button class="btn btn--rose" type="submit">Subscribe</button>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealEls = document.querySelectorAll('.reveal');
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: .15 });
            revealEls.forEach(el => io.observe(el));
        });
    </script>
</x-muster>
