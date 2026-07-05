<x-muster title="Children" description="Soft organic fibres, room to grow, and the kind of make that survives the laundry pile. Gentle on skin, kind to the planet, built to be passed on.">
    <main>
        <section class="page-hero">
            <div class="container">
                {{-- تعديل: استبدال index.html برابط لارافل الصحيح --}}
                <div class="crumb"><a href="{{ url('/') }}">Home</a><span>/</span>Children</div>
                <span class="eyebrow">For Little Ones</span>
                <h1 style="margin-top:.7rem">Children</h1>
                <p>Soft organic fibres, room to grow, and the kind of make that survives the laundry pile. Gentle on skin, kind to the planet, built to be passed on.</p>
            </div>
        </section>

        <section class="section container">
            <div class="toolbar">
                <div class="pills">
                    <button class="pill active">All</button>
                    <button class="pill">Newborn</button>
                    <button class="pill">Tops</button>
                    <button class="pill">Bottoms</button>
                    <button class="pill">Sets</button>
                    <button class="pill">Outerwear</button>
                </div>
                <div style="display:flex;align-items:center;gap:1.3rem">
                    <span class="result-count" id="kidsCount">— pieces</span>
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

            <div class="prod-grid reveal" data-products data-filter="kids" data-count="#kidsCount"></div>

            <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button aria-label="Next page">→</button>
            </div>
        </section>
    </main>
</x-muster>
