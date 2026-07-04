<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accessories — POST</title>
  <meta name="description" content="The finishing touches — leather bags, fine jewellery, scarves and eyewear, each with a story of where it began.">
  <link rel="icon" href="post-logo.png">
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body data-page="accessories">
  <div id="site-header"></div>

  <main>
    <section class="page-hero">
      <div class="container">
        <div class="crumb"><a href="index.html">Home</a><span>/</span>Accessories</div>
        <span class="eyebrow">Finishing Touches</span>
        <h1 style="margin-top:.7rem">Accessories</h1>
        <p>The pieces that complete the story — leather carried for decades, jewellery made by hand, and small things made beautifully. Every one with an origin worth telling.</p>
      </div>
    </section>

    <section class="section container">
      <div class="toolbar">
        <div class="pills">
          <button class="pill active">All</button>
          <button class="pill">Bags</button>
          <button class="pill">Jewellery</button>
          <button class="pill">Scarves</button>
          <button class="pill">Eyewear</button>
          <button class="pill">Hats</button>
        </div>
        <div style="display:flex;align-items:center;gap:1.3rem">
          <span class="result-count" id="accessoriesCount">— pieces</span>
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

      <div class="prod-grid reveal" data-products data-filter="accessories" data-count="#accessoriesCount"></div>

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
