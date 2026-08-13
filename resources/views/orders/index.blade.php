<x-muster title="My Orders | POST" description="View your order history.">
    <main>
        <section class="page-hero">
            <div class="container">
                <span class="eyebrow">My account</span>
                <h1 style="margin-top:.7rem">Order history</h1>
                <p>Every story you've taken home, in one place.</p>
            </div>
        </section>

        <section class="section container">
            @if(session('success'))
                <div style="background:#eaf3e9;border:1px solid #b9d6b6;color:#3d6b3a;padding:1rem 1.2rem;border-radius:10px;margin-bottom:1.6rem">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($orders as $order)
                <a href="{{ route('orders.show', $order) }}" style="display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1.3rem 0;border-bottom:1px solid var(--line-soft)">
                    <div>
                        <div style="font-family:'Fraunces',serif;font-size:1.15rem">Order #{{ $order->id }}</div>
                        <div style="color:var(--ink-soft);font-size:.88rem">{{ $order->created_at->format('M d, Y') }} · {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'items' }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:1rem">
                        <span class="eyebrow" style="margin:0">{{ ucfirst($order->status) }}</span>
                        <span style="font-weight:700">${{ number_format($order->total, 2) }}</span>
                    </div>
                </a>
            @empty
                <p class="muted">You haven't placed any orders yet.</p>
                <div class="hero__cta" style="margin-top:1.5rem">
                    <a class="btn btn--rose" href="{{ url('/women') }}">Start shopping</a>
                </div>
            @endforelse
        </section>
    </main>
</x-muster>
