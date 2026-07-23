<?php
/* ============================================================================
 *                              kl-glance.php
 *
 *   Public page — 'KL at a Glance' landmark slides.
 *   CMS-managed via the klglance table (editor: admin/edit-klglance.php).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('admin/functions.php');

// Landmark slides (managed via admin → Edit Pages → KL @ A Glance)
$klag_landmarks = [];
$klag_result = mysqli_query($db, "SELECT * FROM klglance ORDER BY klglance_order ASC");
if ($klag_result) {
    while ($klag_row = mysqli_fetch_assoc($klag_result)) {
        $klag_landmarks[] = $klag_row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>KL @ A Glance – KL The Guide</title>
  <meta name="description" content="Discover Kuala Lumpur's most iconic landmarks – the Petronas Twin Towers, KL Tower and beyond.">
  <link rel="canonical" href="https://www.kltheguide.com.my/kl-glance.php" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/kl-glance.php" />
  <meta property="og:title" content="KL @ A Glance – KL The Guide" />
  <meta property="og:description" content="Discover Kuala Lumpur's most iconic landmarks – the Petronas Twin Towers, KL Tower and beyond." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/kl-glance.php" />
  <meta property="twitter:title" content="KL @ A Glance – KL The Guide" />
  <meta property="twitter:description" content="Discover Kuala Lumpur's most iconic landmarks – the Petronas Twin Towers, KL Tower and beyond." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>

  <style>
    /* ═══════════════════════════════════════════════════════
       KL @ A GLANCE  –  Sticky-Scroll Experience
    ═══════════════════════════════════════════════════════ */
    :root {
      --klag-nav:    70px;   /* main fixed navbar height  */
      --klag-tabs:   120px;  /* thumbnail tab-bar height  */
      --klag-top:    calc(var(--klag-nav) + var(--klag-tabs));
      --klag-accent: #0ea2bd;
    }

    /* Lock body scroll – the fixed viewport handles all scrolling */
    html, body { height: 100%; overflow: hidden; }

    /* Solid deep-navy backdrop (replaces the photo background) so the
       transparent navbar has something to sit on top of */
    body.klag-page {
      background: radial-gradient(circle at 72% 28%, #2f8fe6 0%, #1f6cc4 45%, #164f9c 100%) fixed;
    }
    body.klag-page::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: var(--klag-nav);
      background: linear-gradient(180deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.25) 100%);
      z-index: 140;
      pointer-events: none;
    }

    /* ── Thumbnail tab bar ────────────────────────────────── */
    .klag-tab-bar {
      position: fixed;
      top: var(--klag-nav);
      left: 0;
      right: 0;
      height: var(--klag-tabs);
      z-index: 150;
      display: flex;
    }

    .klag-tab {
      flex: 1;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      text-decoration: none;
      border-bottom: 4px solid transparent;
      z-index: 0;
      transition: border-color .25s, transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease;
    }

    .klag-tab + .klag-tab { border-left: 1px solid rgba(255,255,255,.12); }

    .klag-tab:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,.45);
      z-index: 1;
    }

    .klag-tab.is-active {
      transform: translateY(-8px) scale(1.02);
      /* bright colour lining (inset ring) + glow so the active page pops */
      box-shadow: 0 14px 38px rgba(0,0,0,.6),
                  inset 0 0 0 3px #19c7e6,
                  0 0 22px rgba(25,199,230,.55);
      z-index: 3;
    }

    /* Background image layer inside each tab */
    .klag-tab__bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      filter: brightness(0.7);
      transition: filter .3s;
    }

    .klag-tab:hover .klag-tab__bg { filter: brightness(0.9); }
    .klag-tab.is-active .klag-tab__bg { filter: brightness(1.08); }

    .klag-tab__label {
      position: relative;
      z-index: 1;
      color: #fff;
      font-size: clamp(12px, 1.8vw, 17px);
      font-weight: 700;
      letter-spacing: 0.02em;
      text-align: center;
      padding: 0 12px;
      text-shadow: 0 2px 10px rgba(0,0,0,.7);
    }

    /* ── Scroll viewport ──────────────────────────────────── */
    .klag-viewport {
      position: fixed;
      top: var(--klag-top);
      left: 0;
      right: 0;
      bottom: 0;
      overflow-y: scroll;
      scroll-snap-type: y mandatory;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .klag-viewport::-webkit-scrollbar { display: none; }

    /* ── Each slide fills the viewport ───────────────────── */
    .klag-slide {
      position: relative;
      height: 100%;
      scroll-snap-align: start;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ── Shared ambient background ────────────────────────── */
    .klag-bg {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 72% 28%, #2f8fe6 0%, #1f6cc4 45%, #164f9c 100%);
    }

    .klag-bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background: transparent;
    }

    /* ── Giant outline text behind featured image ─────────── */
    .klag-bg-text {
      position: absolute;
      top: 50%;
      left: auto;
      right: 3%;
      transform: translate(0, -50%);
      z-index: 2;
      font-family: 'Arial Black', 'Helvetica Neue', Arial, sans-serif;
      font-size: clamp(3.5rem, 18vw, 15rem);
      font-weight: 900;
      color: transparent;
      -webkit-text-stroke: 1.5px rgba(255,255,255,.30);
      white-space: nowrap;
      text-align: right;
      letter-spacing: .02em;
      text-transform: uppercase;
      pointer-events: none;
      user-select: none;
      opacity: 0;
      transition: opacity .95s ease .2s, letter-spacing 1s ease .15s;
    }
    .klag-slide.is-active .klag-bg-text { opacity: 1; letter-spacing: .08em; }

    /* ── Floating landmark image (above outline text) ─────── */
    .klag-featured {
      position: relative;
      z-index: 3;
      transform: scale(.90) translateY(14px);
      opacity: 0;
      transition:
        transform 1.1s cubic-bezier(.22,.68,0,1.1) .1s,
        opacity   .95s ease .1s;
      filter: drop-shadow(0 24px 64px rgba(0,0,0,.65));
    }
    .klag-featured img {
      max-height: 60vh;
      max-width: 68vw;
      object-fit: contain;
      display: block;
    }
    .klag-slide.is-active .klag-featured { transform: scale(1) translateY(0); opacity: 1; }

    /* Landmark slides only (not the intro): keep the image on the left so the
       giant outline word on the right stays fully visible beside it. */
    .klag-slide:not(.klag-slide--intro) { justify-content: flex-start; }
    .klag-slide:not(.klag-slide--intro) .klag-featured {
      margin-left: clamp(24px, 6vw, 110px);
    }

    /* ── Bottom content strip ─────────────────────────────── */
    .klag-content {
      position: absolute;
      top: 0; bottom: 0; right: 0; left: auto;
      width: min(52%, 640px);
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: left;
      z-index: 4;
      padding: 0 clamp(28px, 4vw, 60px) 0 8px;
      background: none;
      transform: translateY(14px);
      opacity: 0;
      transition: transform .85s ease .48s, opacity .85s ease .48s;
    }
    .klag-slide.is-active .klag-content { transform: translateY(0); opacity: 1; }

    .klag-eyebrow {
      display: block;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .28em;
      text-transform: uppercase;
      color: var(--klag-accent);
      margin-bottom: 6px;
    }
    .klag-title {
      margin: 0 0 11px;
      font-size: clamp(2rem, 5vw, 3.6rem);
      font-weight: 800;
      color: #fff;
      line-height: 1;
      text-shadow: 0 4px 22px rgba(0,0,0,.5);
    }
    .klag-desc {
      max-width: 560px;
      margin: 0;
      font-size: clamp(.84rem, 1.3vw, .97rem);
      line-height: 1.68;
      color: rgba(255,255,255,.9);
      text-shadow: 0 2px 12px rgba(0,0,0,.35);
    }

    /* ── Intro slide ─────────────────────────────────────── */
    .klag-slide--intro { flex-direction: column; text-align: center; }
    .klag-slide--intro .klag-bg::after { background: transparent; }

    .klag-intro-center {
      position: relative;
      z-index: 3;
      text-align: center;
      padding: 0 32px;
      transform: translateY(18px);
      opacity: 0;
      transition: transform 1.1s ease .2s, opacity 1.1s ease .2s;
    }
    .klag-slide.is-active .klag-intro-center { transform: translateY(0); opacity: 1; }

    .klag-intro-kl {
      display: block;
      font-family: 'Arial Black', 'Helvetica Neue', sans-serif;
      /* vmin (not vw) so the size backs off on short-but-wide laptop
         screens too, not just narrow ones */
      font-size: clamp(3.2rem, 13vmin, 8.5rem);
      font-weight: 900;
      color: #fff;
      line-height: .88;
      text-shadow: 0 6px 48px rgba(0,0,0,.5);
    }
    .klag-intro-sub {
      display: block;
      font-size: clamp(.9rem, 2.6vmin, 1.5rem);
      font-weight: 700;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--klag-accent);
      margin: 8px 0 22px;
    }
    .klag-intro-desc {
      max-width: 500px;
      margin: 0 auto 28px;
      font-size: clamp(.85rem, 1.4vmin, .98rem);
      line-height: 1.6;
      color: rgba(255,255,255,.96);
    }

    /* Animated scroll hint */
    .klag-scroll-hint {
      display: inline-flex;
      flex-direction: column;
      align-items: center;
      gap: 7px;
      color: rgba(255,255,255,.38);
      font-size: 9px;
      letter-spacing: .22em;
      text-transform: uppercase;
    }
    .klag-scroll-hint__line {
      width: 1px;
      height: 36px;
      background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,.42));
      animation: klagPulse 1.7s ease-in-out infinite;
    }
    @keyframes klagPulse {
      0%,100% { opacity:.35; transform:scaleY(.55) translateY(-8px); }
      50%      { opacity:1;   transform:scaleY(1)   translateY(0);    }
    }

    /* ── Right-side indicator ─────────────────────────────── */
    .klag-pager {
      position: fixed;
      right: 24px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 500;
      display: flex;
      flex-direction: column;
      align-items: center;
      pointer-events: none;
    }
    .klag-pager__dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      border: none;
      background: rgba(255,255,255,.32);
      cursor: pointer;
      pointer-events: all;
      flex-shrink: 0;
      padding: 0;
      transition: background .3s, transform .3s;
    }
    .klag-pager__dot.is-active  { background: #fff; transform: scale(1.6); }
    .klag-pager__dot:hover:not(.is-active) { background: rgba(255,255,255,.62); }
    .klag-pager__dash {
      width: 1px; height: 28px;
      margin: 5px 0;
      background: rgba(255,255,255,.20);
      flex-shrink: 0;
    }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 768px) {
      :root { --klag-tabs: 90px; }
      .klag-tab__label { font-size: 12px; }
      .klag-featured img { max-height: 44vh; max-width: 88vw; }
      .klag-content { top: auto; bottom: 0; display: block; padding: 36px 22px 30px; width: auto; left: 0; right: 0; text-align: left; background: linear-gradient(0deg, rgba(0,0,0,.72) 0%, transparent 100%); }
      .klag-desc { margin: 0; }
      .klag-pager   { right: 10px; }
      .klag-bg-text { right: 2%; font-size: clamp(3rem, 24vw, 7rem); }
      .klag-slide:not(.klag-slide--intro) { justify-content: center; }
      .klag-slide:not(.klag-slide--intro) .klag-featured { margin-left: 0; margin-bottom: 20vh; }
    }
    @media (max-width: 480px) {
      :root { --klag-tabs: 76px; }
      .klag-tab__label { font-size: 10.5px; letter-spacing: 0; }
    }

    /* Small-laptop screens (short viewport height, e.g. 1366x768 with
       browser chrome) — the intro text is vmin-based already, this just
       reclaims a bit more vertical room so nothing gets clipped. */
    @media (max-height: 760px) {
      :root { --klag-tabs: 96px; }
      .klag-intro-desc { margin-bottom: 18px; }
      .klag-scroll-hint__line { height: 24px; }
    }
  </style>
</head>

<body class="klag-page has-video-hero">
  <?php include 'nav.php'; ?>

  <!-- ── Thumbnail tab bar ──────────────────────────────────── -->
  <div class="klag-tab-bar" role="navigation" aria-label="KL Highlights sections">

    <a class="klag-tab is-active" href="kl-glance.php" aria-current="page">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/kl@aglance.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title1), ENT_QUOTES); ?>
      </span>
    </a>

    <a class="klag-tab" href="getting-around-kl.php">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/gettingaroundkl.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title2), ENT_QUOTES); ?>
      </span>
    </a>

    <a class="klag-tab" href="travel-tips.php">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/traveltips.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title3), ENT_QUOTES); ?>
      </span>
    </a>

  </div>

  <!-- ── Scroll viewport ───────────────────────────────────── -->
  <div class="klag-viewport" id="klagVP" role="region" aria-label="KL Landmarks">

    <!-- Slide 0 : Intro ───────────────────────────────────── -->
    <section class="klag-slide klag-slide--intro" data-index="0" aria-label="Introduction">
      <div class="klag-bg"></div>
      <div class="klag-intro-center">
        <span class="klag-intro-kl">KL</span>
        <span class="klag-intro-sub">@ A Glance</span>
        <p class="klag-intro-desc">
          Kuala Lumpur is a city of towering ambition — where colonial heritage meets a
          futuristic skyline and every street corner tells a story.
          Scroll down to discover the landmarks that define the KL experience.
        </p>
        <span class="klag-scroll-hint" aria-hidden="true">
          <span class="klag-scroll-hint__line"></span>
          Scroll
        </span>
      </div>
    </section>

    <!-- Landmark slides (managed via admin → Edit Pages → KL @ A Glance) -->
    <?php foreach ($klag_landmarks as $klag_i => $klag_item):
      $klag_index  = $klag_i + 1;                 // slide 0 is the intro
      $klag_title  = $klag_item['klglance_title'];
      $klag_desc   = $klag_item['klglance_content'];
      $klag_img    = $klag_item['klglance_image'];
      $klag_bgword = strtoupper(strtok(trim($klag_title), ' '));
    ?>
    <section class="klag-slide" data-index="<?php echo $klag_index; ?>"
             aria-label="<?php echo htmlspecialchars($klag_title, ENT_QUOTES); ?>">
      <div class="klag-bg"></div>
      <div class="klag-bg-text" aria-hidden="true"><?php echo htmlspecialchars($klag_bgword, ENT_QUOTES); ?></div>
      <figure class="klag-featured" aria-hidden="true">
        <img src="assets/img/kl_glance/<?php echo htmlspecialchars($klag_img, ENT_QUOTES); ?>"
             alt="<?php echo htmlspecialchars($klag_title, ENT_QUOTES); ?>"
             loading="lazy">
      </figure>
      <div class="klag-content">
        <h2 class="klag-title"><?php echo htmlspecialchars($klag_title, ENT_QUOTES); ?></h2>
        <p class="klag-desc"><?php echo nl2br(htmlspecialchars($klag_desc, ENT_QUOTES)); ?></p>
      </div>
    </section>
    <?php endforeach; ?>

  </div><!-- /klag-viewport -->

  <!-- ── Right-side pager ──────────────────────────────────── -->
  <nav class="klag-pager" id="klagPager" aria-label="Landmark navigation">
    <button class="klag-pager__dot is-active" data-target="0"
            aria-label="Introduction" title="Introduction"></button>
    <?php foreach ($klag_landmarks as $klag_i => $klag_item):
      $klag_index = $klag_i + 1;
      $klag_label = htmlspecialchars($klag_item['klglance_title'], ENT_QUOTES);
    ?>
    <div class="klag-pager__dash" aria-hidden="true"></div>
    <button class="klag-pager__dot" data-target="<?php echo $klag_index; ?>"
            aria-label="<?php echo $klag_label; ?>" title="<?php echo $klag_label; ?>"></button>
    <?php endforeach; ?>
  </nav>

  <!-- Bootstrap JS – needed for nav dropdowns -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    (function () {
      'use strict';

      /* Sync --klag-nav to the actual rendered navbar height */
      var hdr = document.getElementById('header');
      if (hdr) {
        var h = hdr.getBoundingClientRect().height;
        if (h > 0) document.documentElement.style.setProperty('--klag-nav', h + 'px');
      }

      var vp      = document.getElementById('klagVP');
      var slides  = Array.from(vp.querySelectorAll('.klag-slide'));
      var dots    = Array.from(document.querySelectorAll('.klag-pager__dot'));
      var current = 0;

      /* Activate first slide on load */
      slides[0].classList.add('is-active');

      function activate(idx) {
        if (idx === current) return;
        slides[current].classList.remove('is-active');
        dots[current].classList.remove('is-active');
        current = idx;
        slides[current].classList.add('is-active');
        dots[current].classList.add('is-active');
      }

      function goTo(idx) {
        slides[idx].scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      /* IntersectionObserver – fires when slide reaches 55% visibility */
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) activate(slides.indexOf(entry.target));
        });
      }, { root: vp, threshold: 0.55 });

      slides.forEach(function (s) { io.observe(s); });

      /* Dot clicks */
      dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { goTo(i); });
        dot.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); goTo(i); }
        });
      });

      /* Arrow-key navigation */
      document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' && current < slides.length - 1) goTo(current + 1);
        if (e.key === 'ArrowUp'   && current > 0)                 goTo(current - 1);
      });
    })();
  </script>
</body>
</html>
