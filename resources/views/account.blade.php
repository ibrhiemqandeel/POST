<x-muster title="My Account | POST" description="Manage your POST account, orders and details.">
    <main>
        <section class="page-hero">
            <div class="container">
                <span class="eyebrow">Welcome back</span>
                <h1 style="margin-top:.7rem">{{ auth()->user()->name }}</h1>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </section>

        <section class="section container">
            <div class="split reveal" style="align-items:flex-start">
                <div class="split__body">
                    <span class="eyebrow">Account</span>
                    <h2 class="h-section" style="margin-bottom:1rem">Your details</h2>
                    <p class="lead" style="margin-bottom:.4rem"><strong>Name:</strong> {{ auth()->user()->name }}</p>
                    <p class="lead" style="margin-bottom:1.6rem"><strong>Email:</strong> {{ auth()->user()->email }}</p>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn--ghost">Sign out</button>
                    </form>
                </div>

                <div class="split__body">
                    <span class="eyebrow">Orders</span>
                    <h2 class="h-section" style="margin-bottom:1rem">Order history</h2>
                    <p class="lead">You have no orders yet. Once you place an order, it will appear here.</p>
                    <div class="hero__cta">
                        <a class="btn btn--rose" href="{{ url('/women') }}">Start shopping</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-muster>
