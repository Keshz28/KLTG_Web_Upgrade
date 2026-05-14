<?php
include('admin/functions.php');

// ── Merchandise Data ─────────────────────────────────────────────────────────
// Replace these arrays with database queries when the admin panel is ready.

$categories = [
    ['id' => 1, 'name' => 'KL The Guide'],
];

$products = [
    ['id' => 1, 'name' => 'KLTG1 MERCH', 'cat' => 1, 'img' => 'https://placehold.co/600x600/1a2e44/cce8f4?text=KLTG1'],
    ['id' => 2, 'name' => 'KLTG2 MERCH', 'cat' => 1, 'img' => 'https://placehold.co/600x600/0077b6/ffffff?text=KLTG2'],
    ['id' => 3, 'name' => 'KLTG3 MERCH', 'cat' => 1, 'img' => 'https://placehold.co/600x600/00b4d8/1a2e44?text=KLTG3'],
];

// ── Filtering ─────────────────────────────────────────────────────────────────
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
  <meta content="Shop KL The Guide merchandise — souvenirs and branded items from Kuala Lumpur." name="description">

  <?php include 'header.php'; ?>

  <style>
    /* ── Page base ── */
    #merchandise {
      background: #f0f5f9;
      min-height: 100vh;
    }

    /* ════════════════════════════════════════
       HERO BANNER
    ════════════════════════════════════════ */
    .merch-hero {
      background: linear-gradient(135deg, #0a1628 0%, #1a3a5c 55%, #0d5286 100%);
      padding: 90px 24px 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    /* dot-grid texture */
    .merch-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255,255,255,.12) 1px, transparent 1px);
      background-size: 28px 28px;
      pointer-events: none;
    }

    /* glowing orb accent */
    .merch-hero::after {
      content: '';
      position: absolute;
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
    }

    .merch-hero h1 {
      font-size: clamp(2.4rem, 6vw, 4.2rem);
      font-weight: 900;
      color: #fff;
      letter-spacing: 8px;
      text-transform: uppercase;
      margin: 0 0 14px;
      line-height: 1.05;
    }

    .merch-hero-tagline {
      color: rgba(255,255,255,.55);
      font-size: 1rem;
      margin: 0 0 42px;
      letter-spacing: .5px;
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

    /* ════════════════════════════════════════
       STICKY FILTER BAR
    ════════════════════════════════════════ */
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

    /* ════════════════════════════════════════
       PRODUCT AREA
    ════════════════════════════════════════ */
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

    /* ════════════════════════════════════════
       PRODUCT GRID
    ════════════════════════════════════════ */
    .merch-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 28px;
      align-content: start;
    }

    /* ── Card ── */
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

    /* ── Empty state ── */
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

    /* ── Responsive ── */
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

    <!-- ── Hero Banner ── -->
    <section class="merch-hero">
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

    <!-- ── Wave divider ── -->
    <div class="merch-wave-wrap">
      <svg viewBox="0 0 1440 72" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,36 C320,72 720,0 1440,48 L1440,72 L0,72 Z" fill="#f0f5f9"/>
      </svg>
    </div>

    <!-- ── Sticky Filter Bar ── -->
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

    <!-- ── Product Grid ── -->
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
            <div class="merch-card">
              <span class="merch-badge">New</span>
              <div class="merch-card-img-wrap">
                <img src="<?php echo htmlspecialchars($p['img'], ENT_QUOTES); ?>"
                     alt="<?php echo htmlspecialchars($p['name'], ENT_QUOTES); ?>"
                     loading="lazy">
                <div class="merch-card-overlay">
                  <button class="merch-overlay-btn">View Product</button>
                </div>
              </div>
              <div class="merch-card-body">
                <p class="merch-card-cat"><?php echo htmlspecialchars($cat_map[$p['cat']] ?? 'KL The Guide', ENT_QUOTES); ?></p>
                <p class="merch-card-name"><?php echo htmlspecialchars($p['name'], ENT_QUOTES); ?></p>
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
