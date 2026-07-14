<!-- ============================================================================
                              merchandise.php

     Public page — merchandise / store items.

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->
﻿<?php
include('admin/functions.php');

// â”€â”€ Merchandise Data (admin-managed via admin/edit-merchandise.php) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Categories and products are now stored in the `merchandise_category` and
// `merchandise` tables and managed from the admin panel.

$categories = [];
$catRes = mysqli_query($db, "SELECT merchandise_category_id AS id, name FROM merchandise_category ORDER BY merchandise_category_order ASC, merchandise_category_id ASC");
if ($catRes) {
    while ($c = mysqli_fetch_assoc($catRes)) {
        $categories[] = ['id' => (int)$c['id'], 'name' => $c['name']];
    }
}

$products = [];
$prodRes = mysqli_query($db, "SELECT merchandise_id AS id, name, description, image, category_id AS cat, price, buy_url FROM merchandise ORDER BY merchandise_order ASC, merchandise_id ASC");
if ($prodRes) {
    while ($p = mysqli_fetch_assoc($prodRes)) {
        // Uploaded images live in assets/img/merchandise/; fall back to a placeholder if blank.
        $img = $p['image'] !== ''
            ? 'assets/img/merchandise/' . rawurlencode($p['image'])
            : 'https://placehold.co/600x600/1a2e44/cce8f4?text=KLTG';
        $products[] = [
            'id'      => (int)$p['id'],
            'name'    => $p['name'],
            'desc'    => $p['description'] ?? '',
            'cat'     => (int)$p['cat'],
            'img'     => $img,
            'price'   => $p['price'],
            'buy_url' => $p['buy_url'],
        ];
    }
}

// â”€â”€ Filtering â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
$active_cat = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search     = trim($_GET['search'] ?? '');

$filtered = array_filter($products, function ($p) use ($active_cat, $search) {
    if ($active_cat && $p['cat'] !== $active_cat) return false;
    if ($search !== '' && stripos($p['name'], $search) === false) return false;
    return true;
});

$cat_map = [];
foreach ($categories as $c) $cat_map[$c['id']] = $c['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Merchandise</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="description" content="Shop KL The Guide merchandise - souvenirs and branded items from Kuala Lumpur.">
  <meta name="keywords" content="KL The Guide merchandise, Kuala Lumpur souvenirs, KL souvenirs, Malaysia gifts, KLTG shop">
  <link rel="canonical" href="https://www.kltheguide.com.my/merchandise.php" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/merchandise.php" />
  <meta property="og:title" content="KL The Guide - Merchandise" />
  <meta property="og:description" content="Shop KL The Guide merchandise - souvenirs and branded items from Kuala Lumpur." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/merchandise.php" />
  <meta property="twitter:title" content="KL The Guide - Merchandise" />
  <meta property="twitter:description" content="Shop KL The Guide merchandise - souvenirs and branded items from Kuala Lumpur." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>

  <style>
    /* â”€â”€ Page base â”€â”€ */
    #merchandise {
      background: #f0f5f9;
      min-height: 100vh;
    }

    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       HERO BANNER
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .merch-hero {
      background: linear-gradient(135deg, #0a1628 0%, #1a3a5c 55%, #0d5286 100%);
      padding: 90px 24px 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    /* Background video */
    .merch-hero-video {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
      pointer-events: none;
      filter: brightness(1.18) saturate(1.35) contrast(1.05);
    }

    /* Overlay — light enough to keep the video vibrant, dark only behind text */
    .merch-hero-video-overlay {
      position: absolute;
      inset: 0;
      z-index: 1;
      pointer-events: none;
      background:
        linear-gradient(180deg, rgba(10,22,40,.28) 0%, rgba(10,22,40,.12) 40%, rgba(10,22,40,.42) 100%),
        radial-gradient(circle at 50% 42%, rgba(10,22,40,.42) 0%, transparent 62%);
    }

    /* dot-grid texture */
    .merch-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 1;
      background-image: radial-gradient(rgba(255,255,255,.12) 1px, transparent 1px);
      background-size: 28px 28px;
      pointer-events: none;
    }

    /* glowing orb accent */
    .merch-hero::after {
      content: '';
      position: absolute;
      z-index: 1;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(0,180,216,.25) 0%, transparent 70%);
      top: -200px;
      right: -100px;
      pointer-events: none;
    }

    .merch-hero-content {
      position: relative;
      z-index: 2;
      max-width: 680px;
      margin: 0 auto;
    }

    .merch-hero-eyebrow {
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 6px;
      color: #00d4ff;
      text-transform: uppercase;
      margin: 0 0 14px;
      text-shadow: 0 2px 10px rgba(0,0,0,.5);
    }

    .merch-hero h1 {
      font-size: clamp(2.4rem, 6vw, 4.2rem);
      font-weight: 900;
      color: #fff;
      letter-spacing: 8px;
      text-transform: uppercase;
      margin: 0 0 14px;
      line-height: 1.05;
      text-shadow: 0 3px 24px rgba(0,0,0,.6);
    }

    .merch-hero-tagline {
      color: rgba(255,255,255,.85);
      font-size: 1rem;
      margin: 0 0 42px;
      letter-spacing: .5px;
      text-shadow: 0 2px 12px rgba(0,0,0,.55);
    }

    /* Hero search bar */
    .merch-search-wrap {
      display: flex;
      max-width: 500px;
      margin: 0 auto 60px;
      background: rgba(255,255,255,.1);
      border: 1.5px solid rgba(255,255,255,.22);
      border-radius: 50px;
      overflow: hidden;
      backdrop-filter: blur(12px);
      transition: border-color .25s, box-shadow .25s;
    }

    .merch-search-wrap:focus-within {
      border-color: rgba(0,212,255,.65);
      box-shadow: 0 0 0 4px rgba(0,212,255,.12);
    }

    .merch-search-wrap input {
      flex: 1;
      background: transparent;
      border: none;
      outline: none;
      padding: 15px 22px;
      color: #fff;
      font-size: .95rem;
    }

    .merch-search-wrap input::placeholder {
      color: rgba(255,255,255,.45);
    }

    .merch-search-wrap button {
      background: linear-gradient(135deg, #0099cc, #005f9e);
      border: none;
      padding: 13px 26px;
      color: #fff;
      font-weight: 800;
      font-size: .82rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      cursor: pointer;
      transition: filter .2s;
      white-space: nowrap;
    }

    .merch-search-wrap button:hover {
      filter: brightness(1.15);
    }

    /* Wave divider */
    .merch-wave-wrap {
      background: linear-gradient(135deg, #0a1628 0%, #1a3a5c 55%, #0d5286 100%);
      line-height: 0;
      overflow: hidden;
    }

    .merch-wave-wrap svg {
      display: block;
      width: 100%;
    }

    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       STICKY FILTER BAR
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .merch-filter-bar {
      background: #fff;
      box-shadow: 0 2px 16px rgba(0,0,0,.07);
      position: sticky;
      top: 70px;
      z-index: 99;
    }

    .merch-filter-inner {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 32px;
      display: flex;
      align-items: center;
      gap: 10px;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .merch-filter-inner::-webkit-scrollbar { display: none; }

    .merch-filter-label {
      font-size: .7rem;
      font-weight: 800;
      letter-spacing: 2.5px;
      color: #b0bec5;
      text-transform: uppercase;
      white-space: nowrap;
      padding: 18px 0;
      margin-right: 4px;
      flex-shrink: 0;
    }

    .merch-pill {
      display: inline-flex;
      align-items: center;
      padding: 7px 20px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .82rem;
      letter-spacing: .5px;
      text-decoration: none;
      border: 2px solid #dde6ee;
      color: #556070;
      background: #fff;
      white-space: nowrap;
      flex-shrink: 0;
      transition: all .2s;
    }

    .merch-pill:hover {
      border-color: #0077b6;
      color: #0077b6;
      background: #f0f8ff;
      text-decoration: none;
    }

    .merch-pill.active {
      background: linear-gradient(135deg, #0077b6, #00b4d8);
      border-color: transparent;
      color: #fff;
      box-shadow: 0 4px 14px rgba(0,119,182,.3);
    }

    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       PRODUCT AREA
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .merch-container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 44px 32px 90px;
    }

    .merch-results-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 8px;
    }

    .merch-results-count {
      font-size: .85rem;
      color: #8a9aaa;
    }

    .merch-results-count strong {
      color: #1a2e44;
      font-weight: 800;
    }

    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       PRODUCT GRID
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .merch-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 28px;
      align-content: start;
    }

    /* â”€â”€ Card â”€â”€ */
    .merch-card {
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 22px rgba(0,0,0,.07);
      cursor: pointer;
      transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s;
      position: relative;
    }

    .merch-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 24px 56px rgba(0,0,0,.14);
    }

    /* Badge */
    .merch-badge {
      position: absolute;
      top: 14px;
      left: 14px;
      background: linear-gradient(135deg, #f4a261, #e76f51);
      color: #fff;
      font-size: .62rem;
      font-weight: 800;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 5px 13px;
      border-radius: 50px;
      z-index: 3;
      box-shadow: 0 3px 10px rgba(231,111,81,.35);
    }

    /* Image area */
    .merch-card-img-wrap {
      position: relative;
      aspect-ratio: 1 / 1;
      overflow: hidden;
      background: linear-gradient(135deg, #e3f0f8, #cce4f2);
    }

    .merch-card-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .45s ease;
    }

    .merch-card:hover .merch-card-img-wrap img {
      transform: scale(1.09);
    }

    /* Hover overlay */
    .merch-card-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(10,22,40,.7) 0%, rgba(10,22,40,.1) 60%, transparent 100%);
      display: flex;
      align-items: flex-end;
      justify-content: center;
      padding-bottom: 22px;
      opacity: 0;
      transition: opacity .3s;
    }

    .merch-card:hover .merch-card-overlay {
      opacity: 1;
    }

    .merch-overlay-btn {
      background: #fff;
      color: #0077b6;
      font-weight: 800;
      font-size: .75rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 10px 26px;
      border-radius: 50px;
      border: none;
      cursor: pointer;
      transform: translateY(12px);
      transition: transform .3s .05s, box-shadow .3s;
      box-shadow: 0 6px 20px rgba(0,0,0,.2);
    }

    .merch-card:hover .merch-overlay-btn {
      transform: translateY(0);
    }

    .merch-overlay-btn:hover {
      box-shadow: 0 8px 28px rgba(0,0,0,.28);
    }

    /* Card body */
    .merch-card-body {
      padding: 18px 20px 22px;
      border-top: 1px solid #f0f5f9;
    }

    .merch-card-cat {
      font-size: .68rem;
      font-weight: 800;
      letter-spacing: 2.5px;
      color: #00b4d8;
      text-transform: uppercase;
      margin: 0 0 6px;
    }

    .merch-card-name {
      font-weight: 800;
      font-size: .92rem;
      color: #1a2e44;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin: 0;
      line-height: 1.3;
    }

    .merch-card-desc {
      font-size: .8rem;
      color: #6b7885;
      line-height: 1.45;
      margin: 8px 0 0;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .merch-card-price {
      font-weight: 800;
      font-size: .95rem;
      color: #0077b6;
      margin: 8px 0 0;
      letter-spacing: .5px;
    }

    .merch-buy-btn {
      display: block;
      width: 100%;
      margin-top: 14px;
      padding: 11px 18px;
      text-align: center;
      background: linear-gradient(135deg, #0077b6, #00b4d8);
      color: #fff;
      font-weight: 800;
      font-size: .78rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 6px 18px rgba(0,119,182,.28);
      transition: filter .2s, transform .2s;
    }

    .merch-buy-btn:hover {
      filter: brightness(1.08);
      transform: translateY(-2px);
      color: #fff;
      text-decoration: none;
    }

    /* â”€â”€ Empty state â”€â”€ */
    .merch-empty {
      grid-column: 1 / -1;
      text-align: center;
      padding: 90px 20px;
    }

    .merch-empty-icon {
      font-size: 3.5rem;
      margin-bottom: 20px;
      opacity: .25;
      display: block;
    }

    .merch-empty p {
      color: #8a9aaa;
      font-size: 1.05rem;
      margin: 0;
    }

    .merch-empty a {
      color: #0077b6;
      font-weight: 700;
      text-decoration: none;
    }

    .merch-empty a:hover { text-decoration: underline; }

    /* â”€â”€ Responsive â”€â”€ */
    @media (max-width: 768px) {
      .merch-hero { padding: 70px 16px 0; }

      .merch-hero h1 { letter-spacing: 4px; }

      .merch-search-wrap { max-width: 100%; margin-bottom: 44px; }

      .merch-filter-inner { padding: 0 16px; }

      .merch-container { padding: 28px 16px 60px; }

      .merch-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
      }
    }

    @media (max-width: 420px) {
      .merch-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
  <?php include 'nav.php'; ?>

  <main id="merchandise">

    <!-- â”€â”€ Hero Banner â”€â”€ -->
    <section class="merch-hero">
      <video class="merch-hero-video" autoplay muted loop playsinline preload="auto"
             poster="assets/img/kltgseo.jpg">
        <source src="asset-backups/KL-merch.mp4" type="video/mp4">
      </video>
      <div class="merch-hero-video-overlay"></div>
      <div class="merch-hero-content">
        <p class="merch-hero-eyebrow">Official Store</p>
        <h1>Merchandise</h1>
        <p class="merch-hero-tagline">Carry a piece of Kuala Lumpur wherever you go</p>

        <form class="merch-search-wrap" method="GET" action="merchandise.php">
          <?php if ($active_cat): ?>
            <input type="hidden" name="category" value="<?php echo $active_cat; ?>">
          <?php endif; ?>
          <input type="text" name="search"
            placeholder="Search merchandise..."
            value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
          <button type="submit">Search</button>
        </form>
      </div>
    </section>

    <!-- â”€â”€ Wave divider â”€â”€ -->
    <div class="merch-wave-wrap">
      <svg viewBox="0 0 1440 72" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,36 C320,72 720,0 1440,48 L1440,72 L0,72 Z" fill="#f0f5f9"/>
      </svg>
    </div>

    <!-- â”€â”€ Sticky Filter Bar â”€â”€ -->
    <div class="merch-filter-bar">
      <div class="merch-filter-inner">
        <span class="merch-filter-label">Filter:</span>

        <a href="merchandise.php<?php echo $search ? '?search='.urlencode($search) : ''; ?>"
           class="merch-pill <?php echo !$active_cat ? 'active' : ''; ?>">All</a>

        <?php foreach ($categories as $cat): ?>
          <a href="merchandise.php?category=<?php echo $cat['id']; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>"
             class="merch-pill <?php echo $active_cat === $cat['id'] ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($cat['name'], ENT_QUOTES); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- â”€â”€ Product Grid â”€â”€ -->
    <div class="merch-container">

      <div class="merch-results-bar">
        <p class="merch-results-count">
          Showing <strong><?php echo count($filtered); ?></strong>
          product<?php echo count($filtered) !== 1 ? 's' : ''; ?>
          <?php if ($search): ?>
            for &ldquo;<strong><?php echo htmlspecialchars($search, ENT_QUOTES); ?></strong>&rdquo;
          <?php endif; ?>
        </p>
      </div>

      <div class="merch-grid">

        <?php if (empty($filtered)): ?>
          <div class="merch-empty">
            <span class="merch-empty-icon">&#128269;</span>
            <p>No products found. <a href="merchandise.php">Clear filters</a></p>
          </div>

        <?php else: ?>
          <?php foreach ($filtered as $p): ?>
            <?php $hasLink = !empty($p['buy_url']); ?>
            <div class="merch-card">
              <div class="merch-card-img-wrap">
                <img src="<?php echo htmlspecialchars($p['img'], ENT_QUOTES); ?>"
                     alt="<?php echo htmlspecialchars($p['name'], ENT_QUOTES); ?>"
                     loading="lazy">
                <?php if ($hasLink): ?>
                <div class="merch-card-overlay">
                  <a class="merch-overlay-btn" href="<?php echo htmlspecialchars($p['buy_url'], ENT_QUOTES); ?>"
                     target="_blank" rel="noopener">View Product</a>
                </div>
                <?php endif; ?>
              </div>
              <div class="merch-card-body">
                <p class="merch-card-cat"><?php echo htmlspecialchars($cat_map[$p['cat']] ?? 'KL The Guide', ENT_QUOTES); ?></p>
                <p class="merch-card-name"><?php echo htmlspecialchars($p['name'], ENT_QUOTES); ?></p>
                <?php if (trim($p['desc']) !== ''): ?>
                  <p class="merch-card-desc"><?php echo nl2br(htmlspecialchars($p['desc'], ENT_QUOTES)); ?></p>
                <?php endif; ?>
                <?php if ($p['price'] !== ''): ?>
                  <p class="merch-card-price"><?php echo htmlspecialchars($p['price'], ENT_QUOTES); ?></p>
                <?php endif; ?>
                <a class="merch-buy-btn" href="order.php?id=<?php echo (int)$p['id']; ?>">Buy Now</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>

  </main>

  <?php include 'footer.php'; ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>

