<x-muster title="Product" description="Discover the story behind this product, its origin, and the craftsmanship that went into creating it.">
    <main>
        @if(isset($product))
            {{-- عرض منتج فردي (ProductController::show) بنفس هوية الموقع البصرية --}}
            <section class="section container">
                <div class="split reveal" style="align-items:flex-start">
                    <div class="split__media" style="background:{{ !empty($product->image) ? "url('".e($product->image)."') center/cover" : 'linear-gradient(150deg,#E6B6A2,#B0715C)' }}"></div>
                    <div class="split__body">
                        <span class="eyebrow">{{ $product->category?->name ?? 'POST' }}</span>
                        <h1 class="h-section" style="margin-bottom:.6rem">{{ $product->name }}</h1>
                        <p class="lead" style="margin-bottom:1.3rem">${{ number_format((float) $product->price, 2) }}</p>
                        @if(!empty($product->description))
                            <p style="color:var(--ink-soft);line-height:1.85;margin-bottom:1.6rem">{{ $product->description }}</p>
                        @endif
                        <div class="hero__cta">
                            <button type="button" class="btn btn--rose js-add" data-id="{{ $product->id }}">Add to bag</button>
                            <a class="btn btn--ghost" href="{{ url('/products') }}">Back to all products</a>
                        </div>
                    </div>
                </div>
            </section>
        @elseif(isset($products))
            {{-- عرض قائمة منتجات مُرقّمة (ProductController::index) --}}
            <section class="section container">
                <div class="prod-grid reveal">
                    @forelse($products as $product)
                        <a class="prod-card" href="{{ url('/products/'.$product->id) }}">
                            <div class="prod-card__img" style="background:{{ !empty($product->image) ? "url('".e($product->image)."') center/cover" : 'linear-gradient(150deg,#E6B6A2,#B0715C)' }};"></div>
                            <div class="prod-card__name">{{ $product->name }}</div>
                            <div class="prod-card__meta">
                                <span>{{ $product->category?->name ?? 'General' }}</span>
                                <span class="prod-card__price">${{ number_format((float) $product->price, 2) }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="muted">لا توجد منتجات متاحة حالياً.</p>
                    @endforelse
                </div>

                @if(method_exists($products, 'links'))
                    <div style="display:flex;justify-content:center;margin-top:2rem">
                        {{ $products->links() }}
                    </div>
                @endif
            </section>
        @else
            {{-- الصفحة العامة /product بدون بيانات محددة (FrontController::product) --}}
            <section class="section container">
                <div id="pdp-root"></div>
            </section>
        @endif
    </main>
</x-muster>
