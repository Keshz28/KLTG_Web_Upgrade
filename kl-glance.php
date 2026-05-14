<?php include('admin/functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>KL @ A Glance – KL The Guide</title>
  <meta name="description" content="Discover Kuala Lumpur's most iconic landmarks – the Petronas Twin Towers, KL Tower and beyond.">
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

    /* Dark backdrop so the transparent navbar has something to sit on top of
       (otherwise white-on-white = invisible nav items) */
    body.klag-page {
      background: #0a1018 url('asset-backups/KLGlanceBackground.jpg') center / cover no-repeat;
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
      transition: border-color .25s;
    }

    .klag-tab + .klag-tab { border-left: 1px solid rgba(255,255,255,.12); }

    .klag-tab.is-active { border-bottom-color: var(--klag-accent); }

    /* Background image layer inside each tab */
    .klag-tab__bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      filter: brightness(0.48);
      transition: filter .25s;
    }

    .klag-tab:hover .klag-tab__bg,
    .klag-tab.is-active .klag-tab__bg { filter: brightness(0.62); }

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
      background: url('asset-backups/KLGlanceBackground.jpg') center / cover no-repeat;
      transform: scale(1.07);
      transition: transform 1.5s cubic-bezier(.22,.68,0,1.1);
      will-change: transform;
    }
    .klag-slide.is-active .klag-bg { transform: scale(1); }

    .klag-bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(4, 11, 20, 0.64);
    }

    /* ── Giant outline text behind featured image ─────────── */
    .klag-bg-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 2;
      font-family: 'Arial Black', 'Helvetica Neue', Arial, sans-serif;
      font-size: clamp(4rem, 22vw, 18rem);
      font-weight: 900;
      color: transparent;
      -webkit-text-stroke: 1.5px rgba(255,255,255,.18);
      white-space: nowrap;
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

    /* ── Bottom content strip ─────────────────────────────── */
    .klag-content {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      z-index: 4;
      padding: 52px 52px 44px;
      background: linear-gradient(0deg, rgba(0,0,0,.78) 0%, transparent 100%);
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
      color: rgba(255,255,255,.82);
    }

    /* ── Intro slide ─────────────────────────────────────── */
    .klag-slide--intro { flex-direction: column; text-align: center; }
    .klag-slide--intro .klag-bg::after { background: rgba(4,11,20,.74); }

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
      font-size: clamp(4.5rem, 18vw, 12rem);
      font-weight: 900;
      color: #fff;
      line-height: .88;
      text-shadow: 0 6px 48px rgba(0,0,0,.5);
    }
    .klag-intro-sub {
      display: block;
      font-size: clamp(1rem, 3vw, 1.75rem);
      font-weight: 700;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--klag-accent);
      margin: 10px 0 28px;
    }
    .klag-intro-desc {
      max-width: 500px;
      margin: 0 auto 38px;
      font-size: clamp(.88rem, 1.5vw, 1.02rem);
      line-height: 1.68;
      color: rgba(255,255,255,.78);
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
      .klag-content { padding: 36px 22px 30px; }
      .klag-pager   { right: 10px; }
      .klag-bg-text { font-size: clamp(3rem, 24vw, 7rem); }
    }
    @media (max-width: 480px) {
      :root { --klag-tabs: 76px; }
      .klag-tab__label { font-size: 10.5px; letter-spacing: 0; }
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

    <!-- Slide 1 : Petronas Twin Towers ───────────────────── -->
    <section class="klag-slide" data-index="1" aria-label="Petronas Twin Towers">
      <div class="klag-bg"></div>
      <div class="klag-bg-text" aria-hidden="true">PETRONAS</div>
      <figure class="klag-featured" aria-hidden="true">
        <img src="asset-backups/klcc.jpg"
             alt="Petronas Twin Towers illuminated at night with colourful fountain show"
             loading="lazy">
      </figure>
      <div class="klag-content">
        <span class="klag-eyebrow">Iconic Landmark</span>
        <h2 class="klag-title">Twin Tower</h2>
        <p class="klag-desc">
          Soaring 452 metres into the KL skyline, the Petronas Twin Towers were the world's
          tallest buildings from 1998 to 2004. Connected by a sky bridge on the 41st and 42nd
          floors, they remain the defining symbol of modern Malaysia.
        </p>
      </div>
    </section>

    <!-- Slide 2 : KL Tower ───────────────────────────────── -->
    <section class="klag-slide" data-index="2" aria-label="KL Tower">
      <div class="klag-bg"></div>
      <div class="klag-bg-text" aria-hidden="true">KL TOWER</div>
      <figure class="klag-featured" aria-hidden="true">
        <img src="asset-backups/kltower.jpg"
             alt="KL Tower rising between city buildings under a blue sky"
             loading="lazy">
      </figure>
      <div class="klag-content">
        <span class="klag-eyebrow">Iconic Landmark</span>
        <h2 class="klag-title">KL Tower</h2>
        <p class="klag-desc">
          Standing 421 metres tall, Menara Kuala Lumpur is a telecommunications tower and
          observation deck offering 360° panoramic views of the city. Its distinctive
          pod-shaped observation deck has become a beloved part of the KL skyline.
        </p>
      </div>
    </section>

  </div><!-- /klag-viewport -->

  <!-- ── Right-side pager ──────────────────────────────────── -->
  <nav class="klag-pager" id="klagPager" aria-label="Landmark navigation">
    <button class="klag-pager__dot is-active" data-target="0"
            aria-label="Introduction" title="Introduction"></button>
    <div class="klag-pager__dash" aria-hidden="true"></div>
    <button class="klag-pager__dot" data-target="1"
            aria-label="Petronas Twin Towers" title="Petronas Twin Towers"></button>
    <div class="klag-pager__dash" aria-hidden="true"></div>
    <button class="klag-pager__dot" data-target="2"
            aria-label="KL Tower" title="KL Tower"></button>
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
