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
<mian>

<div class="crumbtitle">
  <div class="crumb"><a href="https://post-z44n.onrender.com/">Home</a> / Bag</div>
  <h1 class="page-title">Your <em>bag.</em></h1>
  <p class="page-sub">Everything here still has its story to finish. Review your pieces before they begin their journey to you.</p>
</div>

<main class="cart-shell">
  <!-- ITEMS -->
  <section aria-label="Cart items">
    <div class="list-head">
      <span class="eyebrow">3 pieces</span>
      <span class="eyebrow">Price</span>
    </div>

    <div id="itemsList">
      <article class="item" data-price="420" data-qty="1">
        <span class="item-index">01</span>
        <div class="swatch" style="--sw1:#8a4a2c; --sw2:#d8c3a8;" data-mono="W"></div>
        <div class="item-info">
          <div class="item-cat">Women — Outerwear</div>
          <h3 class="item-name">Wool Column Coat</h3>
          <div class="item-meta"><span>Stone</span><span>Size M</span><span>Made in Como</span></div>
          <div class="qty-row">
            <div class="qty">
              <button type="button" class="qty-dec" aria-label="Decrease quantity">–</button>
              <span class="qty-val">1</span>
              <button type="button" class="qty-inc" aria-label="Increase quantity">+</button>
            </div>
            <button type="button" class="remove-btn">Remove</button>
          </div>
        </div>
        <div class="item-price">
          <span class="price-now line-total">$420</span>
        </div>
      </article>

      <article class="item" data-price="86" data-qty="2">
        <span class="item-index">02</span>
        <div class="swatch" style="--sw1:#6b7a5a; --sw2:#e4ddc9;" data-mono="C"></div>
        <div class="item-info">
          <div class="item-cat">Children — Everyday</div>
          <h3 class="item-name">Linen Popover Shirt</h3>
          <div class="item-meta"><span>Sand</span><span>Age 6</span><span>Organic linen</span></div>
          <div class="qty-row">
            <div class="qty">
              <button type="button" class="qty-dec" aria-label="Decrease quantity">–</button>
              <span class="qty-val">2</span>
              <button type="button" class="qty-inc" aria-label="Increase quantity">+</button>
            </div>
            <button type="button" class="remove-btn">Remove</button>
          </div>
        </div>
        <div class="item-price">
          <span class="price-now line-total">$172</span>
        </div>
      </article>

      <article class="item" data-price="38" data-qty="1">
        <span class="item-index">03</span>
        <div class="swatch" style="--sw1:#b5623b; --sw2:#f1d9c4;" data-mono="B"></div>
        <div class="item-info">
          <div class="item-cat">Beauty — Skin</div>
          <h3 class="item-name">Overnight Repair Balm</h3>
          <div class="item-meta"><span>50ml</span><span>Cruelty-free</span></div>
          <div class="qty-row">
            <div class="qty">
              <button type="button" class="qty-dec" aria-label="Decrease quantity">–</button>
              <span class="qty-val">1</span>
              <button type="button" class="qty-inc" aria-label="Increase quantity">+</button>
            </div>
            <button type="button" class="remove-btn">Remove</button>
          </div>
        </div>
        <div class="item-price">
          <span class="price-was">$44</span>
          <span class="price-now line-total">$38</span>
        </div>
      </article>
    </div>

    <div class="continue-row">
      <a class="continue-link" href="https://post-z44n.onrender.com/">
        <span class="arrow">←</span> Continue reading the collection
      </a>
      <label class="gift-note">
        <input type="checkbox" id="giftNote"> Include a handwritten origin note
      </label>
    </div>

    <!-- Empty state (hidden by default, shown via JS when cart is emptied) -->
    <div class="empty" id="emptyState" hidden>
      <p class="eyebrow">Your bag</p>
      <h2 style="font-family:'Fraunces', serif; font-weight:400; font-size:26px; margin:8px 0 0;">No stories in here yet.</h2>
      <p>Every piece in the house arrives with an origin card. Find one worth carrying.</p>
      <a href="https://post-z44n.onrender.com/" style="display:inline-block; padding:13px 26px; background:var(--ink); color:var(--paper); border-radius:999px; font-size:13px; letter-spacing:.04em;">Browse the collection</a>
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

        <div class="row dim"><span>Subtotal</span><span class="val" id="subtotalVal">$630</span></div>
        <div class="row dim"><span>Shipping</span><span class="val">Carbon-neutral — Free</span></div>
        <div class="row dim"><span>Estimated duties</span><span class="val">Calculated at checkout</span></div>

        <div class="promo">
          <input type="text" placeholder="Gift or promo code">
          <button type="button">Apply</button>
        </div>

        <div class="divider"></div>

        <div class="row total">
          <span class="oc-title-inline">Total</span>
          <span class="val oc-title-inline" id="totalVal">$630</span>
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
  function formatUSD(n){ return '$' + n.toLocaleString('en-US'); }

  function recalc(){
    let subtotal = 0;
    let count = 0;
    document.querySelectorAll('.item').forEach(item => {
      const price = parseFloat(item.dataset.price);
      const qty = parseInt(item.dataset.qty, 10);
      subtotal += price * qty;
      count += qty;
      item.querySelector('.line-total').textContent = formatUSD(price * qty);
    });
    document.getElementById('subtotalVal').textContent = formatUSD(subtotal);
    document.getElementById('totalVal').textContent = formatUSD(subtotal);
    document.getElementById('headerCount').textContent = count;

    const list = document.getElementById('itemsList');
    const empty = document.getElementById('emptyState');
    const hasItems = document.querySelectorAll('.item').length > 0;
    list.hidden = !hasItems;
    document.querySelector('.list-head').hidden = !hasItems;
    document.querySelector('.continue-row').hidden = !hasItems;
    empty.hidden = hasItems;
  }

  document.getElementById('itemsList').addEventListener('click', (e) => {
    const item = e.target.closest('.item');
    if(!item) return;

    if(e.target.classList.contains('qty-inc')){
      item.dataset.qty = parseInt(item.dataset.qty,10) + 1;
      item.querySelector('.qty-val').textContent = item.dataset.qty;
      recalc();
    }
    if(e.target.classList.contains('qty-dec')){
      const next = Math.max(1, parseInt(item.dataset.qty,10) - 1);
      item.dataset.qty = next;
      item.querySelector('.qty-val').textContent = next;
      recalc();
    }
    if(e.target.classList.contains('remove-btn')){
      item.remove();
      document.querySelectorAll('.item').forEach((el, i) => {
        el.querySelector('.item-index').textContent = String(i+1).padStart(2,'0');
      });
      recalc();
    }
  });

  recalc();
</script>

</body>
</html>
</x-muster>
