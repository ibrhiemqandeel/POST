<x-muster title="Checkout | POST" description="Complete your order.">
    <main>
        <section class="page-hero">
            <div class="container">
                <span class="eyebrow">Almost there</span>
                <h1 style="margin-top:.7rem">Checkout</h1>
                <p>Review your order and enter your shipping details to finish.</p>
            </div>
        </section>

        <section class="section container">
            @if ($errors->any())
                <div style="background:#fbeceb;border:1px solid #e3a9a3;color:#8a4a34;padding:1rem 1.2rem;border-radius:10px;margin-bottom:1.6rem">
                    @foreach ($errors->all() as $error)
                        <p style="margin:0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="split" style="align-items:flex-start">
                <div class="split__body">
                    <span class="eyebrow">Shipping details</span>
                    <h2 class="h-section" style="margin-bottom:1.2rem">Where should we send it?</h2>

                    <form method="POST" action="{{ route('checkout.store') }}" style="display:flex;flex-direction:column;gap:1rem">
                        @csrf

                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;margin-bottom:.35rem">Full name</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required
                                style="width:100%;padding:.75rem 1rem;border:1px solid var(--line);border-radius:8px;font:inherit">
                        </div>

                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;margin-bottom:.35rem">Email</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required
                                style="width:100%;padding:.75rem 1rem;border:1px solid var(--line);border-radius:8px;font:inherit">
                        </div>

                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;margin-bottom:.35rem">Phone</label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                style="width:100%;padding:.75rem 1rem;border:1px solid var(--line);border-radius:8px;font:inherit">
                        </div>

                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;margin-bottom:.35rem">City</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                style="width:100%;padding:.75rem 1rem;border:1px solid var(--line);border-radius:8px;font:inherit">
                        </div>

                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;margin-bottom:.35rem">Address</label>
                            <textarea name="shipping_address" rows="3" required
                                style="width:100%;padding:.75rem 1rem;border:1px solid var(--line);border-radius:8px;font:inherit">{{ old('shipping_address') }}</textarea>
                        </div>

                        <div>
                            <label style="display:block;font-size:.82rem;font-weight:600;margin-bottom:.35rem">Order notes (optional)</label>
                            <textarea name="notes" rows="2"
                                style="width:100%;padding:.75rem 1rem;border:1px solid var(--line);border-radius:8px;font:inherit">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn--rose" style="margin-top:.5rem">Place order</button>
                    </form>
                </div>

                <div class="split__body">
                    <span class="eyebrow">Order summary</span>
                    <h2 class="h-section" style="margin-bottom:1.2rem">{{ $cart->count() }} {{ $cart->count() === 1 ? 'piece' : 'pieces' }}</h2>

                    <div style="display:flex;flex-direction:column;gap:.9rem;margin-bottom:1.4rem">
                        @foreach($cart->items as $item)
                            <div style="display:flex;justify-content:space-between;gap:1rem;font-size:.92rem;border-bottom:1px solid var(--line-soft);padding-bottom:.7rem">
                                <span>{{ $item->product->name ?? 'Product' }} <span style="color:var(--ink-soft)">× {{ $item->quantity }}</span></span>
                                <span style="font-weight:600">${{ number_format($item->lineTotal(), 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div style="display:flex;justify-content:space-between;font-size:1.1rem;font-weight:700;border-top:1px solid var(--line);padding-top:1rem">
                        <span>Total</span>
                        <span>${{ number_format($cart->total(), 2) }}</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-muster>
