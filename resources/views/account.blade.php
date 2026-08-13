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

                    @forelse($recentOrders as $order)
                        <a href="{{ route('orders.show', $order) }}" style="display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:.9rem 0;border-bottom:1px solid var(--line-soft)">
                            <div>
                                <div style="font-family:'Fraunces',serif;font-size:1rem">Order #{{ $order->id }}</div>
                                <div style="color:var(--ink-soft);font-size:.82rem">{{ $order->created_at->format('M d, Y') }} · {{ ucfirst($order->status) }}</div>
                            </div>
                            <span style="font-weight:700">${{ number_format($order->total, 2) }}</span>
                        </a>
                    @empty
                        <p class="lead">You have no orders yet. Once you place an order, it will appear here.</p>
                    @endforelse

                    <div class="hero__cta" style="margin-top:1.4rem">
                        @if($recentOrders->isNotEmpty())
                            <a class="btn btn--ghost" href="{{ route('orders.index') }}">View all orders</a>
                        @else
                            <a class="btn btn--rose" href="{{ url('/women') }}">Start shopping</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-muster>
