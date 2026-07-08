<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="icon" href="{{ asset('post-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="{{ asset('app.js') }}" defer></script>
</head>

<body data-page="index">
    <div id="site-header"></div>

    <main class="main-content">

        {{ $slot }}

        <section class="section container">
            <div class="news reveal">
                <div class="news__bg"></div>
                <span class="eyebrow" style="color:var(--rose-light)">Join the house</span>
                <h2 class="h-section" style="margin-top:.8rem">First stories, first access</h2>
                <p class="lead">Be the first to read new origin stories, see new collections, and receive a little something for your first order.</p>
                <form class="news__form js-form" data-toast="Welcome to the house — check your inbox ✦">
                    <input type="email" required placeholder="Your email address" aria-label="Email address">
                    <button class="btn btn--light" type="submit">Subscribe</button>
                </form>
            </div>
        </section>

    </main>
    <div id="site-footer"></div>
</body>

<style>
    /* تنسيق Flexbox لضمان توزيع العناصر عمودياً */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    /* الـ main يأخذ كل المساحة المتاحة ويدفع الفوتر للأسفل */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* تأمين ظهور الفوتر بشكل سليم */
    #site-footer {
        background-color: #f8f8f8;
        padding: 20px;
        text-align: center;
        width: 100%;
    }
</style>

<script>
    window.navLinks = {
        women: "{{ route('women') }}",
        kids: "{{ route('kids') }}",
        beauty: "{{ route('beauty') }}",
        accessories: "{{ route('accessories') }}",
        about: "{{ route('about') }}",
        login: "{{ route('login') }}"
    };
</script>

</html>
