<x-muster title="Beauty" description="Cruelty-free formulas, refillable where we can, and an origin you can trace. Colour and care that feel as considered as the rest of your wardrobe.">
    <main>
        <section class="page-hero">
            <div class="container">
                <div class="crumb"><a href="{{ url('/') }}">Home</a><span>/</span>Beauty</div>
                <span class="eyebrow">Skin & Colour</span>
                <h1 style="margin-top:.7rem">Beauty</h1>
                <p>Cruelty-free formulas, refillable where we can, and an origin you can trace. Colour and care that feel as considered as the rest of your wardrobe.</p>
            </div>
        </section>

        <section class="section container">
            <div class="toolbar">
                <div class="pills">
                    <button class="pill active">All</button>
                    <button class="pill">Lips</button>
                    <button class="pill">Eyes</button>
                    <button class="pill">Skin</button>
                    <button class="pill">Fragrance</button>
                    <button class="pill">Cheeks</button>
                </div>
                <div style="display:flex;align-items:center;gap:1.3rem">
                    <span class="result-count" id="beautyCount">— pieces</span>
                    <div class="select">
                        <select aria-label="Sort products">
                            <option>Sort: Featured</option>
                            <option>Newest first</option>
                            <option>Price: low to high</option>
                            <option>Price: high to low</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="prod-grid reveal">
                @include('partials.product-grid', ['products' => $products, 'countSelector' => '#beautyCount'])
            </div>

            <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button aria-label="Next page">→</button>
            </div>
        </section>
    </main>
</x-muster>
