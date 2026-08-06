<x-muster title="POST" description="Premium Origin Stories & Thoughts">
    <main>

    <!-- ============ UTILITY BAR ============ -->
    <div class="utility-bar">
        <div class="container">
            <div class="utility-bar__ship">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12h20M12 2c2.8 3 4.2 6.4 4.2 10S14.8 19 12 22c-2.8-3-4.2-6.4-4.2-10S9.2 5 12 2Z"/></svg>
                <span>Shipping to 120+ countries · Free returns worldwide</span>
            </div>
            <div class="switchers">
                <label class="switcher">
                    <select aria-label="Language">
                        <option>English</option>
                        <option>Français</option>
                        <option>Español</option>
                        <option>العربية</option>
                    </select>
                </label>
                <label class="switcher">
                    <select aria-label="Region and currency">
                        <option>USD $</option>
                        <option>EUR €</option>
                        <option>GBP £</option>
                        <option>AED د.إ</option>
                    </select>
                </label>
            </div>
        </div>
    </div>

    <!-- ============ HEADER / NAVBAR ============ -->
    <header class="site-header">
        <div class="container nav">
            <a class="wordmark" href="{{ route('home') }}">
                <img src="{{ asset('post-logo.png') }}" alt="POST" class="wordmark__logo">
            </a>

            <nav class="nav__links">
                <a href="{{ route('women') }}">Women</a>
                <a href="{{ route('kids') }}">Children</a>
                <a href="{{ route('beauty') }}">Beauty</a>
                <a href="{{ route('accessories') }}">Accessories</a>
                <a href="{{ route('about') }}">Journal</a>
            </nav>

            <div class="nav__icons">
                <button class="icon-btn js-search-toggle" aria-label="Search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </button>

                @auth
                    @if(auth()->user()->is_admin)
                        <a class="icon-btn" href="{{ route('admin.dashboard') }}" aria-label="Dashboard">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        </a>
                    @endif
                    <a class="icon-btn" href="{{ route('account') ?? '#' }}" aria-label="Account">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                @else
                    <a class="icon-btn" href="{{ route('login') }}" aria-label="Login">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                @endauth

                <a class="icon-btn" href="{{ route('cart') }}" aria-label="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 7h12l-1 13H7L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
                </a>

                <button class="nav__burger js-nav-toggle" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>

        <!-- mobile menu -->
        <nav class="nav__mobile js-nav-mobile">
            <a href="{{ route('women') }}">Women</a>
            <a href="{{ route('kids') }}">Children</a>
            <a href="{{ route('beauty') }}">Beauty</a>
            <a href="{{ route('accessories') }}">Accessories</a>
            <a href="{{ route('about') }}">Journal</a>
        </nav>
    </header>

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



    <script>
        window.navLinks = {
            women: "{{ route('women') }}",
            kids: "{{ route('kids') }}",
            beauty: "{{ route('beauty') }}",
            accessories: "{{ route('accessories') }}",
            about: "{{ route('about') }}",
            login: "{{ route('login') }}",
            cart: "{{ route('cart') }}",
            // إضافة مسار لوحة التحكم فقط في حال كان المستخدم أدمن مسجل الدخول
            adminDashboard: "{{ auth()->check() && auth()->user()->is_admin ? route('admin.dashboard') : '' }}",
            isLoggedIn: "{{ auth()->check() ? 'true' : 'false' }}",
            isAdmin: "{{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }}"
        };
    </script>

</body>
</html>


    </main>
</x-muster>
