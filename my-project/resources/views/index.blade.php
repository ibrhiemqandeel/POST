<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POST — Premium Origin Stories & Thoughts</title>
  <meta name="description" content="Considered clothing, beauty and accessories for women and children — each piece carried for the story it begins.">
  <link rel="icon" href="post-logo.png">
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body data-page="index">
  <div id="site-header"></div>

  <main>
    <!-- HERO -->
    <section class="hero">
      <div class="hero__bg"></div>
      <div class="container hero__inner">
        <div class="hero__grid">
          <div class="hero__copy">
            <span class="eyebrow reveal in">Spring / Summer Collection</span>
            <h1 class="display reveal" data-d="1">Stories<br>worth <em>wearing.</em></h1>
            <p class="lead reveal" data-d="2">Considered clothing, beauty and accessories for women and children — each piece chosen for the story it begins, not just the season it fills.</p>
            <div class="hero__cta reveal" data-d="3">
              <a class="btn btn--rose" href="women.html">Shop Women</a>
              <a class="btn btn--ghost" href="kids.html">Shop Children</a>
            </div>
          </div>
          <div class="hero__visual reveal" data-d="2" style="background:linear-gradient(155deg,#E9C7BA,#B0715C 70%,#8C4E38);color:#fff2ea;display:grid;place-items:center">
            <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="width:62%;opacity:.92">
              <path d="M40 22 L33 31 L38 35 Q35 56 30 78 L70 78 Q65 56 62 35 L67 31 L60 22 Q50 28 40 22 Z"/>
              <path d="M44 23 Q50 27 56 23"/>
              <path d="M38 50 Q50 54 62 50"/>
            </svg>
            <div class="hero__floating">
              <span class="dot"></span>
              <div><small>Origin</small><strong>Como, Italy</strong></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- MARQUEE -->
    <div class="marquee" aria-hidden="true">
      <div class="marquee__track">
        <span>Made to be kept</span><span>Traceable materials</span><span>Carbon-neutral shipping</span><span>Designed in New York</span>
        <span>Made to be kept</span><span>Traceable materials</span><span>Carbon-neutral shipping</span><span>Designed in New York</span>
      </div>
    </div>

    <!-- CATEGORIES -->
    <section class="section container">
      <div class="sec-head">
        <div class="reveal">
          <span class="eyebrow">Find your way in</span>
          <h2 class="h-section sec-head__title">Four worlds, one quiet sensibility</h2>
        </div>
        <a class="link-underline muted reveal" data-d="1" href="women.html">Browse all</a>
      </div>
      <div class="cat-grid">
        <a class="cat-card reveal" href="women.html">
          <div class="cat-card__art" style="background:linear-gradient(150deg,#C9967E,#8C4E38)"></div>
          <span class="arrow">→</span>
          <div><small>For her</small><h3>Women</h3></div>
        </a>
        <a class="cat-card reveal" data-d="1" href="kids.html">
          <div class="cat-card__art" style="background:linear-gradient(150deg,#E6B6A2,#B0715C)"></div>
          <span class="arrow">→</span>
          <div><small>Little ones</small><h3>Children</h3></div>
        </a>
        <a class="cat-card reveal" data-d="2" href="beauty.html">
          <div class="cat-card__art" style="background:linear-gradient(150deg,#A8634F,#5a3a30)"></div>
          <span class="arrow">→</span>
          <div><small>Skin &amp; colour</small><h3>Beauty</h3></div>
        </a>
        <a class="cat-card reveal" data-d="3" href="accessories.html">
          <div class="cat-card__art" style="background:linear-gradient(150deg,#BBA189,#7E6E5C)"></div>
          <span class="arrow">→</span>
          <div><small>Finishing touches</small><h3>Accessories</h3></div>
        </a>
      </div>
    </section>

    <!-- FEATURED -->
    <section class="section--tight container">
      <div class="sec-head">
        <div class="reveal">
          <span class="eyebrow">The Edit</span>
          <h2 class="h-section sec-head__title">Pieces we're loving now</h2>
        </div>
        <a class="link-underline muted reveal" data-d="1" href="women.html">See more</a>
      </div>
      <div class="prod-grid reveal" data-products data-filter="featured" data-limit="8"></div>
    </section>

    <!-- EDITORIAL SPLIT -->
    <section class="section container">
      <div class="split reveal">
        <div class="split__media" style="background:linear-gradient(155deg,#5a3a30,#9E5F4D 60%,#C08B73);display:grid;place-items:center;color:#f4dccf">
          <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" style="width:46%;opacity:.9">
            <rect x="36" y="40" width="28" height="38" rx="6"/><rect x="44" y="27" width="12" height="13" rx="2"/><rect x="42" y="19" width="16" height="8" rx="2"/><line x1="36" y1="56" x2="64" y2="56"/>
          </svg>
        </div>
        <div class="split__body">
          <span class="eyebrow">Our name, our promise</span>
          <h2 class="h-section">Premium Origin Stories &amp; Thoughts</h2>
          <p class="lead" style="margin-bottom:1.6rem">Every garment, every shade, every accessory arrives with a card that tells you where it began — the mill, the maker, the hands. We believe knowing the origin is part of the pleasure of owning something well made.</p>
          <a class="btn btn--ghost" href="about.html">Read the house story</a>
        </div>
      </div>
    </section>

    <!-- PROMISE STRIP -->
    <section class="section--tight" style="background:var(--cream-2);border-block:1px solid var(--line)">
      <div class="container">
        <div class="values" style="grid-template-columns:repeat(4,1fr);gap:1rem">
          <div class="value reveal" style="background:transparent;border:0;padding:1rem .5rem;box-shadow:none">
            <h3 style="font-size:1.15rem">Traceable</h3>
            <p>Sourced from named mills and makers, never anonymous supply chains.</p>
          </div>
          <div class="value reveal" data-d="1" style="background:transparent;border:0;padding:1rem .5rem;box-shadow:none">
            <h3 style="font-size:1.15rem">Carbon-neutral</h3>
            <p>Every order ships carbon-neutral in recycled, plastic-free packaging.</p>
          </div>
          <div class="value reveal" data-d="2" style="background:transparent;border:0;padding:1rem .5rem;box-shadow:none">
            <h3 style="font-size:1.15rem">Made to last</h3>
            <p>Built for years of wear, with care notes to keep them at their best.</p>
          </div>
          <div class="value reveal" data-d="3" style="background:transparent;border:0;padding:1rem .5rem;box-shadow:none">
            <h3 style="font-size:1.15rem">Kind by default</h3>
            <p>Cruelty-free beauty and responsibly chosen fibres across the house.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- NEW ARRIVALS -->
    <section class="section container">
      <div class="sec-head">
        <div class="reveal">
          <span class="eyebrow">Just arrived</span>
          <h2 class="h-section sec-head__title">New this week</h2>
        </div>
        <a class="link-underline muted reveal" data-d="1" href="accessories.html">Shop new in</a>
      </div>
      <div class="prod-grid reveal" data-products data-filter="new" data-limit="4"></div>
    </section>

    <!-- NEWSLETTER -->
    <section class="section container">
      <div class="news reveal">
        <div class="news__bg"></div>
        <span class="eyebrow" style="color:var(--rose-light)">Join the house</span>
        <h2 class="h-section" style="margin-top:.8rem">First stories, first access</h2>
        <p class="lead">Be the first to read new origin stories, see new collections, and receive a little something for your first order.</p>
        <form class="news__form js-form" data-toast="Welcome to the house — check your inbox ✦">
          <input type="email" required placeholder="Your email address" aria-label="Email address">
          <button class="btn btn--light" type="submit">Subscribe</button>
        </form>
      </div>
    </section>
  </main>

  <div id="site-footer"></div>
</body>
</html>
