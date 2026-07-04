<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Children — POST</title>
  <meta name="description" content="Gentle, durable children's clothing in soft organic fibres — made to be played in, washed often, and handed down.">
  <link rel="icon" href="post-logo.png">
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body data-page="kids">
  <div id="site-header"></div>

  <main>
    <section class="page-hero">
      <div class="container">
        <div class="crumb"><a href="index.html">Home</a><span>/</span>Children</div>
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

  <div id="site-footer"></div>
</body>
</html>
