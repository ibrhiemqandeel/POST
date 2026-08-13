<x-muster :title="'Order #'.$order->id.' | POST'" description="Order details.">
    <main>
        <section class="page-hero">
            <div class="container">
                <span class="eyebrow">Order #{{ $order->id }}</span>
                <h1 style="margin-top:.7rem">{{ ucfirst($order->status) }}</h1>
                <p>Placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
            </div>
        </section>

        <section class="section container">
            @if(session('success'))
                <div style="background:#eaf3e9;border:1px solid #b9d6b6;color:#3d6b3a;padding:1rem 1.2rem;border-radius:10px;margin-bottom:1.6rem">
                    {{ session('success') }}
                </div>
            @endif

            <div class="split" style="align-items:flex-start">
                <div class="split__body">
                    <span class="eyebrow">Items</span>
                    <h2 class="h-section" style="margin-bottom:1.2rem">What you ordered</h2>

                    <div style="display:flex;flex-direction:column;gap:.9rem">
                        @foreach($order->items as $item)
                            <div style="display:flex;justify-content:space-between;gap:1rem;font-size:.95rem;border-bottom:1px solid var(--line-soft);padding-bottom:.8rem">
                                <span>{{ $item->product_name }} <span style="color:var(--ink-soft)">× {{ $item->quantity }}</span></span>
                                <span style="font-weight:600">${{ number_format($item->lineTotal(), 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div style="display:flex;justify-content:space-between;font-size:1.1rem;font-weight:700;border-top:1px solid var(--line);padding-top:1rem;margin-top:1rem">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <div class="split__body">
                    <span class="eyebrow">Shipping to</span>
                    <h2 class="h-section" style="margin-bottom:1.2rem">Delivery details</h2>
                    <p class="lead" style="margin-bottom:.3rem"><strong>{{ $order->shipping_name }}</strong></p>
                    <p class="lead" style="margin-bottom:.3rem">{{ $order->shipping_email }} · {{ $order->shipping_phone }}</p>
                    <p class="lead" style="margin-bottom:.3rem">{{ $order->shipping_city }}</p>
                    <p class="lead">{{ $order->shipping_address }}</p>

                    @if($order->notes)
                        <p class="lead" style="margin-top:1rem"><strong>Notes:</strong> {{ $order->notes }}</p>
                    @endif

                    <div class="hero__cta" style="margin-top:1.6rem">
                        <a class="btn btn--ghost" href="{{ route('orders.index') }}">Back to orders</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-muster>
