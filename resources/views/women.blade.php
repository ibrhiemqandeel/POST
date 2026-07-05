<x-muster title="Women" description="Quietly assured pieces made from named, traceable materials. Built to be worn for years, and to begin a story every time you reach for them.">
    <main>
        <section class="page-hero">
            <div class="container">
                <div class="crumb"><a href="index.html">Home</a><span>/</span>Women</div>
                <span class="eyebrow">The Women's Edit</span>
                <h1 style="margin-top:.7rem">Women</h1>
                <p>Quietly assured pieces made from named, traceable materials. Built to be worn for years, and to begin a story every time you reach for them.</p>
            </div>
        </section>

        <section class="section container">
            <div class="toolbar">
                <div class="pills">
                    <button class="pill active">All</button>
                    <button class="pill">Dresses</button>
                    <button class="pill">Knitwear</button>
                    <button class="pill">Outerwear</button>
                    <button class="pill">Tailoring</button>
                    <button class="pill">Skirts</button>
                </div>
                <div style="display:flex;align-items:center;gap:1.3rem">
                    <span class="result-count" id="womenCount">— pieces</span>
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

            <div class="prod-grid reveal" data-products data-filter="women" data-count="#womenCount"></div>

            <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button aria-label="Next page">→</button>
            </div>
        </section>
    </main>
</x-muster>
