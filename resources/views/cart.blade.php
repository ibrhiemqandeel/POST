<x-muster title="Your Bag" description="Review your pieces below. Complimentary, carbon-neutral shipping on orders over $120.">
<style>
  :root{
    --ink:#211f1a;
    --ecru:#efeae1;
    --paper:#f7f4ee;
    --stone:#cdc4b3;
    --stone-dim:#e2dccd;
    --clay:#a15c3a;
    --clay-dim:#c78f70;
    --olive:#4c5240;
    --green-line: rgba(76,82,64,0.35);
    --radius: 2px;
    --max: 1180px;
  }

  *{box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{
    margin:0;
    background:var(--paper);
    color:var(--ink);
    font-family:'Inter', sans-serif;
    font-size:15px;
    line-height:1.55;
    -webkit-font-smoothing:antialiased;
  }
  ::selection{background:var(--clay); color:var(--paper);}
  a{color:inherit; text-decoration:none;}
  button{font-family:inherit;}

  :focus-visible{
    outline: 2px solid var(--clay);
    outline-offset: 3px;
  }

  .eyebrow{
    text-transform:uppercase;
    letter-spacing:.16em;
    font-size:11px;
    font-weight:500;
    color:var(--olive);
  }

  /* ---------- Header ---------- */
  header{
    position:sticky; top:0; z-index:40;
    background:rgba(247,244,238,0.92);
    backdrop-filter:blur(6px);
    border-bottom:1px solid var(--stone-dim);
  }
  .bar{
    max-width:var(--max);
    margin:0 auto;
    padding:20px 32px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:24px;
  }
  .logo{
    font-family:'Fraunces', serif;
    font-size:22px;
    letter-spacing:.22em;
    font-weight:500;
  }
  nav.primary{
    display:flex;
    gap:34px;
  }
  nav.primary a{
    font-size:13px;
    letter-spacing:.04em;
    color:var(--ink);
    opacity:.72;
    transition:opacity .2s ease;
  }
  nav.primary a:hover{opacity:1;}
  .bar-right{
    display:flex;
    align-items:center;
    gap:22px;
  }
  .icon-btn{
    display:inline-flex; align-items:center; gap:8px;
    font-size:13px;
    opacity:.85;
  }
  .bag-count{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:18px; height:18px;
    padding:0 4px;
    background:var(--ink);
    color:var(--paper);
    border-radius:50%;
    font-size:10px;
  }

  @media (max-width: 760px){
    nav.primary{display:none;}
    .bar{padding:16px 20px;}
  }

  /* ---------- Breadcrumb / title ---------- */
  .crumbtitle{
    max-width:var(--max);
    margin: 46px auto 8px;
    padding:0 32px;
  }
  .crumb{
    font-size:12px;
    color:var(--olive);
    opacity:.8;
    margin-bottom:14px;
  }
  .crumb a{opacity:.75;}
  .crumb a:hover{opacity:1; text-decoration:underline; text-underline-offset:3px;}
  h1.page-title{
    font-family:'Fraunces', serif;
    font-weight:400;
    font-size:clamp(34px, 5vw, 52px);
    margin:0;
    letter-spacing:-0.01em;
  }
  h1.page-title em{
    font-style:italic;
    font-weight:400;
    color:var(--clay);
  }
  .page-sub{
    margin:10px 0 0;
    max-width:520px;
    color:var(--ink);
    opacity:.65;
    font-size:14px;
  }

  /* ---------- Layout ---------- */
  .cart-shell{
    max-width:var(--max);
    margin:0 auto;
    padding:36px 32px 100px;
    display:grid;
    grid-template-columns: 1fr 380px;
    gap:64px;
    align-items:start;
  }
  @media (max-width: 980px){
    .cart-shell{grid-template-columns:1fr; gap:48px;}
  }

  /* ---------- Items list ---------- */
  .list-head{
    display:flex;
    justify-content:space-between;
    align-items:baseline;
    border-bottom:1px solid var(--ink);
    padding-bottom:12px;
    margin-bottom:6px;
  }
  .list-head .eyebrow{color:var(--ink); opacity:.55;}

  .item{
    display:grid;
    grid-template-columns: 34px 96px 1fr auto;
    gap:22px;
    align-items:flex-start;
    padding:30px 0;
    border-bottom:1px solid var(--stone-dim);
  }
  .item-index{
    font-family:'Fraunces', serif;
    font-style:italic;
    font-size:15px;
    color:var(--olive);
    opacity:.6;
    padding-top:6px;
  }
  .swatch{
    width:96px; height:120px;
    border-radius:var(--radius);
    position:relative;
    overflow:hidden;
    background:
      linear-gradient(155deg, var(--sw2,#cdb9a5) 0%, var(--sw1,#a15c3a) 100%);
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.06);
  }
  .swatch::after{
    content:attr(data-mono);
    position:absolute;
    right:8px; bottom:8px;
    font-family:'Fraunces', serif;
    font-style:italic;
    font-size:12px;
    color:rgba(255,255,255,0.85);
    letter-spacing:.03em;
  }

  .item-info{padding-top:2px;}
  .item-cat{
    font-size:10.5px;
    text-transform:uppercase;
    letter-spacing:.14em;
    color:var(--olive);
    margin-bottom:6px;
  }
  .item-name{
    font-family:'Fraunces', serif;
    font-size:19px;
    font-weight:400;
    margin:0 0 6px;
  }
  .item-meta{
    font-size:12.5px;
    color:var(--ink);
    opacity:.6;
    margin-bottom:14px;
  }
  .item-meta span + span::before{
    content:"·";
    margin:0 7px;
    opacity:.6;
  }

  .qty-row{
    display:flex;
    align-items:center;
    gap:14px;
  }
  .qty{
    display:inline-flex;
    align-items:center;
    border:1px solid var(--stone);
    border-radius:999px;
  }
  .qty button{
    width:28px; height:28px;
    border:none;
    background:none;
    cursor:pointer;
    font-size:14px;
    color:var(--ink);
    display:flex; align-items:center; justify-content:center;
  }
  .qty button:hover{color:var(--clay);}
  .qty span{
    min-width:20px;
    text-align:center;
    font-size:13px;
  }
  .remove-btn{
    background:none; border:none; cursor:pointer;
    font-size:12px;
    text-decoration:underline;
    text-underline-offset:3px;
    color:var(--ink);
    opacity:.55;
    padding:0;
  }
  .remove-btn:hover{opacity:1; color:var(--clay);}

  .item-price{
    text-align:right;
    padding-top:4px;
    white-space:nowrap;
  }
  .price-now{
    font-family:'Fraunces', serif;
    font-size:18px;
  }
  .price-was{
    display:block;
    font-size:12px;
    text-decoration:line-through;
    opacity:.4;
    margin-bottom:2px;
  }

  @media (max-width: 620px){
    .item{grid-template-columns: 26px 72px 1fr; row-gap:10px;}
    .item-price{
      grid-column: 2 / 4;
      text-align:left;
      display:flex;
      align-items:baseline;
      gap:10px;
      padding-top:0;
    }
  }

  .continue-row{
    margin-top:34px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:16px;
  }
  .continue-link{
    font-size:13px;
    display:inline-flex;
    align-items:center;
    gap:8px;
    opacity:.75;
  }
  .continue-link:hover{opacity:1;}
  .continue-link .arrow{transition:transform .2s ease;}
  .continue-link:hover .arrow{transform:translateX(-4px);}

  .gift-note{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:12.5px;
    color:var(--olive);
  }
  .gift-note input{accent-color:var(--olive);}

  /* ---------- Empty state ---------- */
  .empty{
    border-top:1px solid var(--ink);
    padding:70px 0 60px;
    text-align:left;
    max-width:440px;
  }
  .empty p{
    opacity:.65;
    margin:14px 0 26px;
  }

  /* ---------- Summary / Origin card ---------- */
  .origin-card{
    position:sticky;
    top:104px;
    background:var(--ecru);
    border:1px solid var(--stone);
    border-radius:6px;
    padding:32px 30px;
  }
  .origin-card .stitch{
    border:1px dashed var(--stone);
    border-radius:4px;
    padding:22px 22px 26px;
  }
  .oc-eyebrow{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:18px;
  }
  .oc-title{
    font-family:'Fraunces', serif;
    font-style:italic;
    font-size:20px;
    margin:0 0 2px;
  }
  .oc-sub{
    font-size:12px;
    opacity:.6;
    margin:0 0 22px;
  }

  .row{
    display:flex;
    justify-content:space-between;
    font-size:13.5px;
    padding:9px 0;
  }
  .row.dim{opacity:.65;}
  .row .val{font-variant-numeric: tabular-nums;}

  .promo{
    display:flex;
    gap:8px;
    margin:18px 0 6px;
  }
  .promo input{
    flex:1;
    border:1px solid var(--stone);
    background:transparent;
    border-radius:999px;
    padding:9px 14px;
    font-size:12.5px;
    color:var(--ink);
  }
  .promo input::placeholder{color:var(--ink); opacity:.4;}
  .promo button{
    border:1px solid var(--ink);
    background:transparent;
    border-radius:999px;
    padding:9px 16px;
    font-size:12px;
    letter-spacing:.03em;
    cursor:pointer;
    white-space:nowrap;
  }
  .promo button:hover{background:var(--ink); color:var(--paper);}

  .divider{
    height:1px;
    background:var(--stone);
    margin:16px 0;
  }

  .row.total{
    font-size:16px;
    padding-top:6px;
  }
  .row.total .oc-title-inline{
    font-family:'Fraunces', serif;
  }

  .checkout-btn{
    width:100%;
    margin-top:20px;
    padding:15px 18px;
    background:var(--ink);
    color:var(--paper);
    border:none;
    border-radius:999px;
    font-size:13.5px;
    letter-spacing:.05em;
    cursor:pointer;
    transition:background .2s ease;
  }
  .checkout-btn:hover{background:var(--clay);}

  .assurances{
    margin-top:22px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px 10px;
  }
  .assurance{
    display:flex;
    gap:9px;
    align-items:flex-start;
    font-size:11.5px;
    opacity:.75;
    line-height:1.4;
  }
  .assurance svg{flex:none; margin-top:1px;}

  .origin-note{
    margin-top:22px;
    padding-top:18px;
    border-top:1px solid var(--stone);
    font-size:11.5px;
    color:var(--olive);
    display:flex;
    gap:10px;
    align-items:flex-start;
  }

  /* ---------- Footer ---------- */
  footer{
    border-top:1px solid var(--stone-dim);
    padding:48px 32px 40px;
  }
  .foot-inner{
    max-width:var(--max);
    margin:0 auto;
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:20px;
    font-size:12px;
    opacity:.55;
  }
  .foot-inner .logo-mini{
    font-family:'Fraunces', serif;
    letter-spacing:.2em;
    font-size:13px;
    opacity:1;
  }
</style>
<div class="crumbtitle">
  <div class="crumb"><a href="{{ url('/') }}">Home</a> / Bag</div>
  <h1 class="page-title">Your <em>bag.</em></h1>
  <p class="page-sub">Everything here still has its story to finish. Review your pieces before they begin their journey to you.</p>
</div>

<main class="cart-shell">
  <!-- ITEMS -->
  <section aria-label="Cart items">
    <div class="list-head" @if($cart->items->isEmpty()) hidden @endif>
      <span class="eyebrow">{{ $cart->count() }} {{ $cart->count() === 1 ? 'piece' : 'pieces' }}</span>
      <span class="eyebrow">Price</span>
    </div>

    <div id="itemsList" @if($cart->items->isEmpty()) hidden @endif>
      @foreach($cart->items as $item)
        <article class="item" data-item-id="{{ $item->id }}" data-price="{{ $item->price }}" data-qty="{{ $item->quantity }}">
          <span class="item-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
          <div class="swatch" style="--sw1:#8a4a2c; --sw2:#d8c3a8;" data-mono="{{ strtoupper(substr($item->product->name ?? 'P', 0, 1)) }}"></div>
          <div class="item-info">
            <div class="item-cat">{{ $item->product->category?->name ?? 'POST' }}</div>
            <h3 class="item-name">{{ $item->product->name ?? 'Product' }}</h3>
            <div class="item-meta"><span>SKU {{ $item->product->sku ?? '—' }}</span></div>
            <div class="qty-row">
              <div class="qty">
                <button type="button" class="qty-dec" aria-label="Decrease quantity">–</button>
                <span class="qty-val">{{ $item->quantity }}</span>
                <button type="button" class="qty-inc" aria-label="Increase quantity">+</button>
              </div>
              <button type="button" class="remove-btn">Remove</button>
            </div>
          </div>
          <div class="item-price">
            <span class="price-now line-total">{{ '$'.number_format($item->lineTotal(), 2) }}</span>
          </div>
        </article>
      @endforeach
    </div>

    <div class="continue-row" @if($cart->items->isEmpty()) hidden @endif>
      <a class="continue-link" href="{{ url('/') }}">
        <span class="arrow">←</span> Continue reading the collection
      </a>
      <label class="gift-note">
        <input type="checkbox" id="giftNote"> Include a handwritten origin note
      </label>
    </div>

    <!-- Empty state -->
    <div class="empty" id="emptyState" @unless($cart->items->isEmpty()) hidden @endunless>
      <p class="eyebrow">Your bag</p>
      <h2 style="font-family:'Fraunces', serif; font-weight:400; font-size:26px; margin:8px 0 0;">No stories in here yet.</h2>
      <p>Every piece in the house arrives with an origin card. Find one worth carrying.</p>
      <a href="{{ url('/') }}" style="display:inline-block; padding:13px 26px; background:var(--ink); color:var(--paper); border-radius:999px; font-size:13px; letter-spacing:.04em;">Browse the collection</a>
    </div>
  </section>

  <!-- SUMMARY -->
  <aside aria-label="Order summary">
    <div class="origin-card">
      <div class="stitch">
        <div class="oc-eyebrow">
          <span class="eyebrow" style="color:var(--olive);">Origin card</span>
          <span class="eyebrow" style="color:var(--olive);">No. 0347</span>
        </div>
        <h2 class="oc-title">Where this order begins.</h2>
        <p class="oc-sub">Packed in Como, shipped carbon-neutral.</p>

        <div class="row dim"><span>Subtotal</span><span class="val" id="subtotalVal">{{ '$'.number_format($cart->total(), 2) }}</span></div>
        <div class="row dim"><span>Shipping</span><span class="val">Carbon-neutral — Free</span></div>
        <div class="row dim"><span>Estimated duties</span><span class="val">Calculated at checkout</span></div>

        <div class="promo">
          <input type="text" placeholder="Gift or promo code">
          <button type="button">Apply</button>
        </div>

        <div class="divider"></div>

        <div class="row total">
          <span class="oc-title-inline">Total</span>
          <span class="val oc-title-inline" id="totalVal">{{ '$'.number_format($cart->total(), 2) }}</span>
        </div>

        <button class="checkout-btn" type="button">Continue to checkout</button>

        <div class="assurances">
          <div class="assurance">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12l5 5L20 6"/></svg>
            Traceable to the maker
          </div>
          <div class="assurance">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12l5 5L20 6"/></svg>
            Carbon-neutral shipping
          </div>
          <div class="assurance">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12l5 5L20 6"/></svg>
            Free returns, 30 days
          </div>
          <div class="assurance">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12l5 5L20 6"/></svg>
            Made to be kept
          </div>
        </div>
      </div>

      <div class="origin-note">
        <span>✎</span>
        <span>Every order leaves with a card naming the mill, the maker, the hands — this one included.</span>
      </div>
    </div>
  </aside>
</main>

<footer>
  <div class="foot-inner">
    <span class="logo-mini">POST</span>
    <span>Premium Origin Stories &amp; Thoughts</span>
    <span>© 2026 POST. Designed in New York.</span>
  </div>
</footer>

<script>
  // ملاحظة: كانت هذه السلة وهمية بالكامل (3 عناصر ثابتة، وتُحسب الكميات
  // محلياً في المتصفح فقط دون أي اتصال بالسيرفر). الآن كل تعديل على الكمية
  // أو الحذف يُرسل فعلياً إلى /cart/items/{id} ويُحدَّث من رد السيرفر
  // (الذي يحسب الإجمالي الحقيقي من قاعدة البيانات).
  function formatUSD(n){ return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

  function csrfToken(){
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
  }

  function updateTotals(total){
    document.getElementById('subtotalVal').textContent = formatUSD(total);
    document.getElementById('totalVal').textContent = formatUSD(total);
  }

  function updateHeaderBadge(count){
    document.querySelectorAll('.bag-count, .cart-badge').forEach(el => el.textContent = count);
  }

  function renumberItems(){
    document.querySelectorAll('.item').forEach((el, i) => {
      el.querySelector('.item-index').textContent = String(i + 1).padStart(2, '0');
    });
  }

  function toggleEmptyState(){
    const hasItems = document.querySelectorAll('.item').length > 0;
    document.getElementById('itemsList').hidden = !hasItems;
    document.querySelector('.list-head').hidden = !hasItems;
    document.querySelector('.continue-row').hidden = !hasItems;
    document.getElementById('emptyState').hidden = hasItems;
  }

  document.getElementById('itemsList').addEventListener('click', (e) => {
    const item = e.target.closest('.item');
    if(!item) return;
    const itemId = item.dataset.itemId;

    if(e.target.classList.contains('qty-inc') || e.target.classList.contains('qty-dec')){
      const current = parseInt(item.dataset.qty, 10);
      const next = e.target.classList.contains('qty-inc') ? current + 1 : Math.max(1, current - 1);
      if(next === current) return;

      fetch(`/cart/items/${itemId}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        },
        body: JSON.stringify({ quantity: next })
      })
      .then(r => r.json())
      .then(data => {
        if(!data.success) return;
        item.dataset.qty = next;
        item.querySelector('.qty-val').textContent = next;
        item.querySelector('.line-total').textContent = formatUSD(data.line_total);
        updateTotals(data.total);
        updateHeaderBadge(data.count);
      });
    }

    if(e.target.classList.contains('remove-btn')){
      fetch(`/cart/items/${itemId}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        }
      })
      .then(r => r.json())
      .then(data => {
        if(!data.success) return;
        item.remove();
        renumberItems();
        updateTotals(data.total);
        updateHeaderBadge(data.count);
        toggleEmptyState();
      });
    }
  });
</script>

</x-muster>
