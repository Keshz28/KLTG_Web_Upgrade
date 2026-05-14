<?php include('admin/functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
<style>
  .country-list {
    z-index: 9999 !important;
    background: rgba(255, 255, 255, 0.82) !important;
    backdrop-filter: blur(18px) !important;
    -webkit-backdrop-filter: blur(18px) !important;
    border: 1px solid rgba(255, 255, 255, 0.45) !important;
    border-radius: 10px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.22) !important;
  }

  .country-list li,
  .country-list .country-name,
  .country-list .dial-code {
    color: #111 !important;
  }

  .country-list li:hover,
  .country-list li.highlight {
    background: rgba(0, 180, 216, 0.12) !important;
  }

  #hero.home-ad-carousel {
    position: absolute;
    left: 50%;
    bottom: 28px;
    transform: translateX(-50%);
    z-index: 4;
    display: block;
    margin: 0;
    padding: 0;
    width: min(96%, 1180px);
    background: transparent;
    overflow: visible;
    border-radius: 14px;
  }

  #hero.home-ad-carousel .carousel-inner {
    overflow: hidden;
    border-radius: 14px;
    box-shadow: 0 18px 44px rgba(0, 0, 0, .45);
  }

  #hero.home-ad-carousel .carousel-item {
    background: transparent;
  }

  #hero.home-ad-carousel .hero-ad-link,
  #hero.home-ad-carousel .hero-ad-frame,
  #hero.home-ad-carousel picture {
    display: block;
    width: 100%;
  }

  #hero.home-ad-carousel .hero-ad-frame {
    aspect-ratio: 1920 / 300;
    max-height: 170px;
    min-height: 110px;
    overflow: hidden;
  }

  #hero.home-ad-carousel .hero-ad-img {
    display: block;
    height: 100%;
    max-height: none;
    object-fit: cover;
    object-position: center;
    width: 100%;
  }

  #hero.home-ad-carousel .carousel-control-prev,
  #hero.home-ad-carousel .carousel-control-next {
    left: auto;
    right: auto;
    width: 36px;
    height: 36px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(14, 162, 189, .88);
    border-radius: 50%;
    opacity: .92;
  }

  #hero.home-ad-carousel .carousel-control-prev {
    left: 12px;
  }

  #hero.home-ad-carousel .carousel-control-next {
    right: 12px;
  }

  #hero.home-ad-carousel .carousel-indicators {
    position: static;
    margin: 10px 0 0;
    gap: 7px;
  }

  #hero.home-ad-carousel .carousel-indicators li {
    width: 9px;
    height: 9px;
    margin: 0;
    background-color: rgba(255, 255, 255, .85);
    border: 0;
    border-radius: 50%;
    opacity: .65;
  }

  #hero.home-ad-carousel .carousel-indicators li.active {
    opacity: 1;
    background-color: #fff;
  }

  #hero-animated.home-video-hero {
    position: relative;
    isolation: isolate;
    height: 100vh !important;
    min-height: 640px !important;
    padding: 80px 16px 230px !important;
    overflow: hidden;
    align-items: center !important;
    background: #071926 url("assets/img/kltgseohp.jpeg") center / cover no-repeat;
  }

  #hero-animated.home-video-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    background:
      linear-gradient(90deg, rgba(3, 18, 31, .66), rgba(3, 18, 31, .34) 48%, rgba(3, 18, 31, .62)),
      linear-gradient(180deg, rgba(0, 0, 0, .18), rgba(0, 0, 0, .48));
  }

  #hero-animated.home-video-hero .home-video-hero__video {
    position: absolute;
    inset: 0;
    z-index: -2;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  #hero-animated.home-video-hero .home-video-hero__content {
    max-width: 1060px;
    margin: 0 auto;
    color: #fff;
  }

  #hero-animated.home-video-hero h1 {
    max-width: 100%;
    margin: 0 0 12px;
    color: #fff;
    font-family: var(--font-secondary);
    font-size: clamp(2.4rem, 5.5vw, 4.2rem);
    font-weight: 800;
    line-height: .96;
    letter-spacing: 0;
    text-shadow: 0 6px 28px rgba(0, 0, 0, .48);
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
  }

  #hero-animated.home-video-hero .home-video-hero__eyebrow {
    display: block;
    margin-bottom: 4px;
    color: #d5f8ff;
    font-size: clamp(1rem, 1.6vw, 1.4rem);
    font-weight: 700;
    line-height: 1.1;
  }

  #hero-animated.home-video-hero .home-video-hero__title-accent {
    display: block;
    color: #fff;
  }

  #hero-animated.home-video-hero p {
    max-width: 980px;
    margin: 0 auto 18px;
    color: rgba(255, 255, 255, .96);
    font-size: clamp(0.9rem, 1.2vw, 1rem);
    font-weight: 600;
    line-height: 1.4;
    text-shadow: 0 3px 18px rgba(0, 0, 0, .5);
  }

  #hero-animated.home-video-hero .home-video-hero__form {
    width: min(100%, 830px);
    margin: 0 auto;
  }

  #hero-animated.home-video-hero .home-video-hero__fields {
    display: flex;
    justify-content: center;
    align-items: stretch;
    flex-wrap: wrap;
    gap: 0;
  }

  #hero-animated.home-video-hero .inputemailsub,
  #hero-animated.home-video-hero .location {
    box-sizing: border-box;
    min-height: 54px;
    border: 1px solid rgba(255, 255, 255, .72);
    background: rgba(255, 255, 255, .96);
    color: #17212b;
    font-size: 16px;
    outline: 0;
    box-shadow: 0 12px 30px rgba(0, 0, 0, .16);
  }

  #hero-animated.home-video-hero .inputemailsub {
    width: min(100%, 330px);
    padding: 0 18px;
    border-radius: 8px 0 0 8px;
  }

  #hero-animated.home-video-hero .country-select {
    flex: 0 1 260px;
  }

  #hero-animated.home-video-hero .country-select .location {
    width: 100%;
    padding: 0 12px 0 44px;
    border-left: 0;
    border-radius: 0;
  }

  #hero-animated.home-video-hero .inputemailsubbtn {
    box-sizing: border-box;
    min-height: 54px;
    border: 0;
    border-radius: 0 8px 8px 0;
    background: var(--color-primary);
    color: #fff;
    padding: 0 28px;
    font-size: 16px;
    font-weight: 700;
    transition: .25s ease;
    box-shadow: 0 12px 30px rgba(0, 0, 0, .18);
  }

  #hero-animated.home-video-hero .inputemailsubbtn:hover,
  #hero-animated.home-video-hero .inputemailsubbtn:focus {
    background: var(--color-primary-light);
  }

  #hero-animated.home-video-hero .home-video-hero__consent {
    color: rgba(255, 255, 255, .94);
    text-shadow: 0 2px 10px rgba(0, 0, 0, .45);
  }

  #hero-animated.home-video-hero .form-check-input {
    width: 18px;
    height: 18px;
    margin-top: .18rem;
  }

  @media (max-width: 767px) {
    #hero.home-ad-carousel {
      bottom: 20px;
      width: 92%;
    }

    #hero.home-ad-carousel .hero-ad-frame {
      aspect-ratio: 16 / 9;
      max-height: 160px;
      min-height: 100px;
    }

    #hero.home-ad-carousel .carousel-control-prev,
    #hero.home-ad-carousel .carousel-control-next {
      width: 30px;
      height: 30px;
    }

    #hero-animated.home-video-hero {
      height: 100vh !important;
      min-height: 600px !important;
      padding: 80px 14px 220px !important;
      align-items: center !important;
    }

    #hero-animated.home-video-hero .container-fluid {
      width: 100vw;
      max-width: 100vw;
      padding-left: 14px;
      padding-right: 14px;
    }

    #hero-animated.home-video-hero .row {
      margin-left: 0;
      margin-right: 0;
    }

    #hero-animated.home-video-hero [class*="col-"] {
      padding-left: 0;
      padding-right: 0;
    }

    #hero-animated.home-video-hero .home-video-hero__content {
      width: 100%;
      max-width: 350px;
      margin-left: 0;
      margin-right: auto;
    }

    #hero-animated.home-video-hero h1 {
      font-size: clamp(1.95rem, 8.5vw, 2.45rem);
      line-height: 1.02;
    }

    #hero-animated.home-video-hero .home-video-hero__eyebrow {
      font-size: clamp(1.05rem, 5vw, 1.3rem);
    }

    #hero-animated.home-video-hero p {
      max-width: 100%;
      font-size: .98rem;
    }

    #hero-animated.home-video-hero .home-video-hero__form {
      width: 100%;
      max-width: 360px;
    }

    #hero-animated.home-video-hero .home-video-hero__fields {
      flex-direction: column;
      gap: 10px;
      width: 100%;
    }

    #hero-animated.home-video-hero .inputemailsub,
    #hero-animated.home-video-hero .country-select,
    #hero-animated.home-video-hero .inputemailsubbtn {
      width: 100%;
      flex-basis: auto;
      border-radius: 8px;
    }

    #hero-animated.home-video-hero .country-select .location {
      border-left: 1px solid rgba(255, 255, 255, .72);
      border-radius: 8px;
    }
  }

  @media (prefers-reduced-motion: reduce) {
    #hero-animated.home-video-hero .home-video-hero__video {
      display: none;
    }
  }

  .featured-services.home-highlights {
    padding: 0;
    background: #fff;
    overflow: hidden;
  }

  .home-highlights__layout {
    display: grid;
    grid-template-columns: 45.5% 54.5%;
    min-height: 650px;
  }

  .home-highlights__feature {
    min-height: 650px;
    margin: 0;
    overflow: hidden;
  }

  .home-highlights__feature img,
  .home-highlights__card img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .home-highlights__feature img {
    object-position: center;
  }

  .home-highlights__content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 38px clamp(34px, 6vw, 105px) 44px;
    background: #fff;
  }

  .home-highlights__header {
    width: 100%;
    max-width: 760px;
    margin-bottom: 28px;
    text-align: center;
  }

  .home-highlights__header h2 {
    margin: 0 0 12px;
    color: #e60012;
    font-family: "Caveat", "Comic Sans MS", cursive;
    font-size: clamp(44px, 4vw, 60px);
    font-weight: 700;
    line-height: .9;
    letter-spacing: 0;
  }

  .home-highlights__header p {
    max-width: 760px;
    margin: 0 auto;
    color: #050505;
    font-family: var(--font-secondary);
    font-size: clamp(18px, 1.45vw, 23px);
    font-weight: 700;
    line-height: 1.12;
    letter-spacing: 0;
  }

  .home-highlights__cards {
    display: grid;
    gap: 20px;
    width: 100%;
    max-width: 760px;
  }

  .home-highlights__card {
    position: relative;
    display: block;
    height: 128px;
    overflow: hidden;
    background: #1a1f24;
    color: #fff;
  }

  .home-highlights__card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, .18);
    transition: background .25s ease;
  }

  .home-highlights__card img {
    transform: scale(1.01);
    transition: transform .35s ease;
  }

  .home-highlights__card span {
    position: absolute;
    inset: 0;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 18px;
    color: #fff;
    font-family: "Comic Sans MS", "Trebuchet MS", var(--font-secondary);
    font-size: clamp(18px, 1.95vw, 25px);
    font-weight: 800;
    line-height: 1.1;
    text-align: center;
    text-shadow: 0 2px 7px rgba(0, 0, 0, .65);
    letter-spacing: 0;
  }

  .home-highlights__card:hover img,
  .home-highlights__card:focus img {
    transform: scale(1.055);
  }

  .home-highlights__card:hover::after,
  .home-highlights__card:focus::after {
    background: rgba(0, 0, 0, .28);
  }

  .home-highlights__card:focus-visible {
    outline: 3px solid var(--color-primary);
    outline-offset: 4px;
  }

  @media (max-width: 991px) {
    .home-highlights__layout {
      grid-template-columns: 1fr;
      min-height: 0;
    }

    .home-highlights__feature {
      min-height: 0;
      aspect-ratio: 16 / 10;
    }

    .home-highlights__content {
      padding: 34px 18px 40px;
    }
  }

  @media (max-width: 575px) {
    .home-highlights__feature {
      aspect-ratio: 4 / 3;
    }

    .home-highlights__content {
      align-items: flex-start;
    }

    .home-highlights__header {
      max-width: 350px;
      margin-bottom: 22px;
    }

    .home-highlights__header h2 {
      font-size: 42px;
    }

    .home-highlights__header p {
      font-size: 16px;
    }

    .home-highlights__cards {
      gap: 14px;
      max-width: 350px;
    }

    .home-highlights__card {
      height: 98px;
    }

    .home-highlights__card span {
      font-size: 18px;
    }
  }
</style>

<head>
  <title>KL The Guide - Comprehensive Travel Guide to Kuala Lumpur</title>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Bluedale Publishing is dedicated to helping people make the most of their open-ended travel experiences, so we feel a deep sense of responsibility and privilege when we help someone create their own stories.">
  <meta name="keywords" content="About Us, Bluedale Publishing, Bluedale, BGOC, travel, tourism, Malaysia, 
	KL The Guide E-Book, KLTG ebook, KL The Guide, travel guidebook, Malaysia's capital city, e-book, Kuala Lumpur, KL,
	Dataran Merdeka, Petaling Street, travel guide app, travel guide, KLCC, KL Tower, Batu Caves, Google Play Store, Apple App Store, KL The Guide, Kuala Lumpur city">
  <meta name="robots" content="index, follow">

  <link rel="canonical" href="https://kltheguide.com.my/">

  <meta itemprop="name" content="KL The Guide">
  <meta itemprop="description" content="KL The Guide provides comprehensive information about Kuala Lumpur, including top attractions, travel tips, and local insights.">
  <meta itemprop="image" content="https://www.kltheguide.com.my/assets/img/kltgseohp.jpeg">

  <!-- Country Select JS Plugin -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/country-select-js@2.0.1/build/css/countrySelect.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/country-select-js@2.0.1/build/js/countrySelect.min.js"></script>
  
  <!-- Preload Non-Critical CSS (style.css, aos.css if used here) -->
  <link rel="preload" href="assets/css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="assets/css/style.css">
    </noscript>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my" />
  <meta property="og:title" content="KL The Guide - Explore Kuala Lumpur" />
  <meta property="og:description" content="Your ultimate Kuala Lumpur travel companion. Explore top attractions, must-visit places, and discover everything you need to know about KL at your fingertips." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseohp.jpeg" />

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my" />
  <meta property="twitter:title" content="KL The Guide - Your Ultimate Kuala Lumpur Travel Resource" />
  <meta property="twitter:description" content="Your ultimate Kuala Lumpur travel companion. Explore top attractions, must-visit places, and discover everything you need to know about KL at your fingertips." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseohp.jpeg" />

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "KL The Guide",
      "description": "KL The Guide provides comprehensive information about Kuala Lumpur, including top attractions, travel tips, and local insights.",
      "url": "https://www.kltheguide.com.my",
      "image": "https://www.kltheguide.com.my/assets/img/kltgseo.jpg"
    }
  </script>

  <?php include 'header.php'; ?>

  <style>
    /* Reinforce full-viewport video hero — these must match the rules above */
    #hero-animated.home-video-hero {
      height: 100vh !important;
      min-height: 600px !important;
      align-items: center !important;
      padding: 80px 16px 50px !important;
    }

    /* Video and overlay fill the full section */
    #hero-animated.home-video-hero::before,
    #hero-animated.home-video-hero .home-video-hero__video {
      height: 100% !important;
      min-height: 100% !important;
    }

    /* Centre-weighted gradient — text stays readable on any video frame */
    #hero-animated.home-video-hero::before {
      background:
        linear-gradient(180deg,
          rgba(0, 0, 0, .40) 0%,
          rgba(0, 0, 0, .25) 40%,
          rgba(0, 0, 0, .45) 100%) !important;
    }

    /* Carousel now sits below the video hero — no top margin needed */
    #hero.home-ad-carousel {
      margin-top: 0;
    }

    @media (max-width: 767px) {
      #hero-animated.home-video-hero {
        height: 100vh !important;
        min-height: 560px !important;
        padding: 70px 14px 50px !important;
        align-items: center !important;
      }
    }

    /* ════════════════════════════════════════════════════════
       GLASSMORPHISM SUBSCRIBE BAR
    ════════════════════════════════════════════════════════ */

    /* Tighten form width to keep the pill compact and fit in grey area */
    #hero-animated.home-video-hero .home-video-hero__form {
      width: min(100%, 420px) !important;
      margin: 0 auto !important;
    }

    /* ── Pill wrapper — compact version ── */
    #hero-animated.home-video-hero .home-video-hero__fields {
      display: flex !important;
      align-items: center !important;
      flex-wrap: nowrap !important;
      justify-content: flex-start !important;
      gap: 0 !important;
      /* glass effect */
      background: rgba(255, 255, 255, 0.14) !important;
      backdrop-filter: blur(16px) !important;
      -webkit-backdrop-filter: blur(16px) !important;
      border: 1.5px solid rgba(255, 255, 255, 0.28) !important;
      border-radius: 9999px !important;
      padding: 2px !important;
      box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.24),
        inset 0 1px 0 rgba(255, 255, 255, 0.16) !important;
    }

    /* ── Strip inherited solid styling from both inputs ── */
    #hero-animated.home-video-hero .inputemailsub,
    #hero-animated.home-video-hero .location {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
      min-height: unset !important;
    }

    /* ── Country selector region ── */
    #hero-animated.home-video-hero .country-select {
      flex: 0 0 auto !important;
      position: relative !important;
      /* vertical divider on the right */
      border-right: 1px solid rgba(255, 255, 255, 0.28) !important;
    }

    /* The text input inside the country selector (has absolute-positioned flag from library) */
    #hero-animated.home-video-hero .country-select .location {
      color: rgba(255, 255, 255, 0.90) !important;
      font-size: 12px !important;
      height: 34px !important;
      width: 110px !important;
      padding: 0 8px 0 36px !important; /* 36px leaves room for flag icon */
      cursor: pointer !important;
      border-radius: 0 !important;
      border-left: none !important;
    }

    /* ── Email input ── */
    #hero-animated.home-video-hero .inputemailsub {
      flex: 1 1 auto !important;
      min-width: 0 !important;
      color: #ffffff !important;
      font-size: 13px !important;
      height: 34px !important;
      padding: 0 12px !important;
      border-radius: 0 !important;
      width: auto !important;
    }

    #hero-animated.home-video-hero .inputemailsub::placeholder {
      color: rgba(255, 255, 255, 0.52) !important;
    }

    #hero-animated.home-video-hero .inputemailsub:focus {
      outline: none !important;
      box-shadow: none !important;
    }

    /* ── Subscribe button — pill inside the pill ── */
    #hero-animated.home-video-hero .inputemailsubbtn {
      flex: 0 0 auto !important;
      background: var(--color-primary) !important;
      color: #fff !important;
      border: none !important;
      border-radius: 9999px !important;
      padding: 0 18px !important;
      height: 34px !important;
      min-height: unset !important;
      font-size: 13px !important;
      font-weight: 700 !important;
      letter-spacing: 0.2px !important;
      cursor: pointer !important;
      white-space: nowrap !important;
      box-shadow: 0 4px 14px rgba(0, 180, 216, 0.38) !important;
      transition: filter .2s ease, box-shadow .2s ease !important;
    }

    #hero-animated.home-video-hero .inputemailsubbtn:hover,
    #hero-animated.home-video-hero .inputemailsubbtn:focus {
      background: var(--color-primary) !important;
      filter: brightness(1.14) !important;
      box-shadow: 0 6px 22px rgba(0, 180, 216, 0.55) !important;
    }

    /* ── Consent line ── */
    #hero-animated.home-video-hero .home-video-hero__consent {
      margin-top: 8px !important;
      font-size: 0.75rem !important;
    }

    #hero-animated.home-video-hero .form-check-input {
      width: 15px !important;
      height: 15px !important;
      margin-top: 0 !important;
    }


    /* ── Mobile: stack gracefully ── */
    @media (max-width: 600px) {
      #hero-animated.home-video-hero .home-video-hero__fields {
        flex-wrap: wrap !important;
        border-radius: 14px !important;
        padding: 6px !important;
        gap: 6px !important;
      }

      #hero-animated.home-video-hero .country-select {
        flex: 0 0 100% !important;
        border-right: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.25) !important;
        padding-bottom: 6px !important;
      }

      #hero-animated.home-video-hero .country-select .location {
        width: 100% !important;
        height: 32px !important;
        font-size: 12px !important;
      }

      #hero-animated.home-video-hero .inputemailsub {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        height: 32px !important;
        font-size: 12px !important;
      }

      #hero-animated.home-video-hero .inputemailsubbtn {
        flex: 0 0 auto !important;
        height: 32px !important;
        padding: 0 16px !important;
        font-size: 12px !important;
      }

    }
  </style>



</head>

<body class="has-video-hero">
  <?php include 'nav.php'; ?>
  <div id="fb-root"></div>
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v20.0&appId=1469540920331510" nonce="7D6fqBsd"></script>



  <main id="index">

    <!-- ======= Video Hero (sits under transparent navbar) ======= -->
    <section id="hero-animated" class="home-video-hero d-flex align-items-center justify-content-center w-100">
      <video class="home-video-hero__video" autoplay muted loop playsinline poster="assets/img/kltgseohp.jpeg" aria-hidden="true">
        <source src="asset-backups/KLOverview.mp4" type="video/mp4">
      </video>
      <div class="container-fluid position-relative">
        <div class="row justify-content-center">
          <div class="col-12 col-xl-10">
            <div class="home-video-hero__content text-center">
              <h1>
                <span class="home-video-hero__eyebrow"><?php echo $hero_title ?></span>
                <span class="home-video-hero__title-accent"><?php echo $hero_title2 ?></span>
              </h1>
              <p>
                <?php echo $hero_subtitle ?>
              </p>

              <form id="subscribeForm" method="post" class="home-video-hero__form">
                <div class="home-video-hero__fields">
                  <input type="email" name="email" id="emailsubscribe" placeholder="Your email address" class="inputemailsub" required>
                  <input type="text" id="country_selector" name="country" class="location" aria-label="Country">
                  <input type="submit" value="Subscribe" name="subscribe" class="inputemailsubbtn">
                </div>
                <div class="form-check home-video-hero__consent d-flex justify-content-center align-items-start mt-3">
                  <input class="form-check-input me-2" type="checkbox" value="1" id="monthlyUpdates" name="consent">
                  <label class="form-check-label" for="monthlyUpdates">
                    I want to receive monthly updates from KL The Guide
                  </label>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- ======= Banner Carousel (overlay inside hero video) ======= -->
      <section id="hero" class="hero home-ad-carousel carousel lazy carousel-fade" data-bs-ride="carousel" data-bs-interval="5000"
        touch="true">

        <div class="carousel-inner">
        <?php
        $query = "SELECT * FROM banner WHERE status='1' OR status='2' ORDER BY banner_order ASC ";
        $result = mysqli_query($db, $query);
        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          $bannerFile = htmlspecialchars($row['banner_filename'], ENT_QUOTES);
          $bannerName = htmlspecialchars($row['banner_name'], ENT_QUOTES);
          $bannerMobile = !empty($row['banner_filename2']) ? htmlspecialchars($row['banner_filename2'], ENT_QUOTES) : '';
          $loadingAttr = $counter == 1 ? 'eager' : 'lazy';

          if ($counter == 1) {
            echo '<div class="carousel-item active" data-filename="' . $bannerFile . '" data-name="' . $bannerName . '">';
          } else {
            echo '<div class="carousel-item" data-filename="' . $bannerFile . '" data-name="' . $bannerName . '">';
          }

          if ($row['banner_url']) {
            // Pass the banner data directly in the onclick function
            echo '<a class="hero-ad-link" href="' . htmlspecialchars($row['banner_url'], ENT_QUOTES) . '" onclick="banner_clicks(\'' . addslashes($row['banner_filename']) . '\', \'' . addslashes($row['banner_name']) . '\'); return true;">';
          }

          echo '<div class="hero-ad-frame">';
          echo '<picture>';
          if ($bannerMobile) {
            echo '<source media="(max-width: 767px)" srcset="assets/img/banner/' . $bannerMobile . '">';
          }
          echo '<img src="assets/img/banner/' . $bannerFile . '" alt="' . $bannerName . '" class="hero-ad-img" loading="' . $loadingAttr . '" decoding="async">';
          echo '</picture>';
          echo '</div>';

          if ($row['banner_url']) {
            echo '</a>';
          }

          echo '</div>';
          $counter++;
        }
        ?>

          <a class="carousel-control-prev" href="#hero" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
          </a>

          <a class="carousel-control-next" href="#hero" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
          </a>
        </div>
        <!-- Indicators BELOW -->
        <ol class="carousel-indicators"></ol>
      </section>
      <!-- End Banner Carousel -->
    </section>
    <!-- End Video Hero -->


    <!-- ======= Featured Services Section ======= -->
    <section id="featured-services" class="featured-services home-highlights" aria-labelledby="homeHighlightsTitle">
      <?php
      $home_highlights_main_image = 'highlights/klhighlightside.jpg';
      $home_highlights_glance_image = 'highlights/kl@aglance.jpg';
      $home_highlights_getting_around_image = 'highlights/gettingaroundkl.jpg';
      $home_highlights_travel_tips_image = 'highlights/traveltips.jpg';

      if (!file_exists(__DIR__ . '/assets/img/' . $home_highlights_main_image)) {
        $home_highlights_main_image = 'highlights/' . urldecode($tile1_photo1);
      }

      if (!file_exists(__DIR__ . '/assets/img/' . $home_highlights_glance_image)) {
        $home_highlights_glance_image = 'highlights/' . urldecode($tile1_photo1);
      }

      if (!file_exists(__DIR__ . '/assets/img/' . $home_highlights_getting_around_image)) {
        $home_highlights_getting_around_image = 'highlights/' . urldecode($tile1_photo2);
      }

      if (!file_exists(__DIR__ . '/assets/img/' . $home_highlights_travel_tips_image)) {
        $home_highlights_travel_tips_image = 'highlights/' . urldecode($tile1_photo3);
      }
      ?>
      <div class="home-highlights__layout">
        <figure class="home-highlights__feature">
          <img src="assets/img/<?php echo htmlspecialchars($home_highlights_main_image, ENT_QUOTES); ?>"
            alt="Kuala Lumpur skyline with Petronas Twin Towers" loading="lazy" decoding="async">
        </figure>
        <div class="home-highlights__content">
          <div class="home-highlights__header">
            <h2 id="homeHighlightsTitle"><?php echo htmlspecialchars(urldecode($tile1_title), ENT_QUOTES); ?></h2>
            <p><?php echo htmlspecialchars(urldecode($tile1_subtitle), ENT_QUOTES); ?></p>
          </div>

          <div class="home-highlights__cards">
            <a class="home-highlights__card" href="kl-glance.php"
              aria-label="Open <?php echo htmlspecialchars(urldecode($tile1_title1), ENT_QUOTES); ?>">
              <img src="assets/img/<?php echo htmlspecialchars($home_highlights_glance_image, ENT_QUOTES); ?>"
                alt="Kuala Lumpur skyline at sunset" loading="lazy" decoding="async">
              <span><?php echo htmlspecialchars(urldecode($tile1_title1), ENT_QUOTES); ?></span>
            </a>

            <a class="home-highlights__card" href="highlights.php#tab-2"
              aria-label="Open <?php echo htmlspecialchars(urldecode($tile1_title2), ENT_QUOTES); ?>">
              <img src="assets/img/<?php echo htmlspecialchars($home_highlights_getting_around_image, ENT_QUOTES); ?>"
                alt="Rapid KL train in Kuala Lumpur" loading="lazy" decoding="async">
              <span><?php echo htmlspecialchars(urldecode($tile1_title2), ENT_QUOTES); ?></span>
            </a>

            <a class="home-highlights__card" href="travel-tips.php"
              aria-label="Open <?php echo htmlspecialchars(urldecode($tile1_title3), ENT_QUOTES); ?>">
              <img src="assets/img/<?php echo htmlspecialchars($home_highlights_travel_tips_image, ENT_QUOTES); ?>"
                alt="Aerial view of Kuala Lumpur city centre" loading="lazy" decoding="async">
              <span><?php echo htmlspecialchars(urldecode($tile1_title3), ENT_QUOTES); ?></span>
            </a>
          </div>
        </div>
      </div>
    </section><!-- End Featured Services Section -->




    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2 class="text-break">
            <?php echo urldecode($tile2_title) ?>
          </h2>
          <p>
            <?php echo urldecode($tile2_subtitle) ?>
          </p>
        </div>

        <div class="row gy-5 d-flex justify-content-center ">

          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="explorekl.php#explorekl" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo1 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Explore KL">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title1 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>

                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="where-to-shop.php" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo2 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Shop Like Locals">
                </div>
                <div class="details position-relative">

                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title2 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="accommodation.php#placetostay" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo3 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Place To Stay">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title3 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="spa.php" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo4 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Spa Time">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title4 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="medical-tourism.php#medicaltourism" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo5 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Medical Tourism">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title5 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- </a> -->
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="beyondkl.php#beyondkl" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo6 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Beyond KL">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title6 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- </a> -->

                </div>
              </a>

            </div>
          </div><!-- End Service Item -->


        </div>

      </div>
    </section><!-- End Services Section -->






    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio" data-aos="fade-up">

      <div class="container">

        <div class="section-header">
          <h2>
            <?php echo $tile3_title ?>
          </h2>
          <p>
            <?php echo $tile3_subtitle ?>
          </p>
        </div>

      </div>

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="200">

        <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry"
          data-portfolio-sort="original-order">

          <ul class="portfolio-filters"> <!-- Corrected class name if typo -->
            <li data-filter="*" class="filter-active">All</li>
            <?php
            $query = "SELECT DISTINCT recommendation_category FROM recommendation";
            $result = mysqli_query($db, $query);

            // Check if the query was successful
            if ($result) {
              $counter = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                // Escape output for security (optional but recommended)
                $category = htmlspecialchars($row['recommendation_category'], ENT_QUOTES, 'UTF-8');
                echo '<li class="" data-filter=".filter-' . $category . '">' . $category . '</li>';
                $counter++;
              }
              // Free the result set after use (good practice)
              mysqli_free_result($result);
            } else {
              // Handle the query error - display a message or log it
              echo "<!-- Error in query: " . mysqli_error($db) . " -->";
              // Or log it: error_log("Database Error: " . mysqli_error($db));
            }
            ?>
          </ul><!-- End Portfolio Filters -->

          <div class="row g-0 portfolio-container">
            <?php
            // Second Query
            $query = "SELECT * FROM recommendation "; // Add WHERE clause if needed
            $result = mysqli_query($db, $query);

            if ($result) { // Check if the second query was successful
              $counter = 1; // Initialize counter for this loop if needed
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item filter-' . htmlspecialchars($row['recommendation_category'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<img src="https://' . htmlspecialchars(urldecode($row['recommendation_image']), ENT_QUOTES, 'UTF-8') . '" class="img-fluid" alt="' . htmlspecialchars(urldecode($row['recommendation_name']), ENT_QUOTES, 'UTF-8') . '" loading="lazy">';
                echo '<div class="portfolio-info">';
                echo '<h4>' . htmlspecialchars(urldecode($row['recommendation_name']), ENT_QUOTES, 'UTF-8') . '</h4>';
                echo '<a href="blog-details.php?postid=' . (int)$row['recommendation_postid'] . '" title="More Details" class="details-link">'; // Cast ID to int for safety
                echo '<i class="bi bi-link-45deg"></i></a>';
                echo '</div>';
                echo '</div>';
                $counter++;
              }
              mysqli_free_result($result); // Free the second result set
            } else {
              // Handle the error for the second query specifically
              echo "<!-- Error in main recommendation query: " . mysqli_error($db) . " -->";
              // Or log it: error_log("Database Error (Main Query): " . mysqli_error($db));
            }
            ?>
            <!-- End Portfolio Item -->
          </div><!-- End Portfolio Container -->

        </div>

      </div>
    </section><!-- End Portfolio Section -->



    <!-- ======= Recent Blog Posts Section ======= -->
    <section id="recent-blog-posts" class="recent-blog-posts">

      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>
            <?php echo $tile4_title ?>
          </h2>
          <p>
            <?php echo $tile4_subtitle ?>
          </p>
        </div>

        <div class="row" id="postlist">


        </div>

      </div>

    </section><!-- End Recent Blog Posts Section -->

    <!-- Start Instagram Section -->
    <section class="insta-feed">

      <div class="section-header">
        <h2>Instagram</h2>
      </div>

      <div class="profile-info">
        <img src="assets/img/kltginstapp.png" alt="Profile Picture" class="profile-pic">
        <div class="profile-details">
          <h2 class="profile-name">@kltheguide</h2>
          <p class="profile-bio">Your ultimate travel guide to Kuala Lumpur, Malaysia 🇲🇾. Food, Sightseeing, Shopping
            <br> #KLTheGuide in your postings to get featured!
          </p>
          <div class="profile-actions">
            <a href="https://www.instagram.com/kltheguide" target="_blank" class="follow-btn">Follow</a>
          </div>
        </div>
      </div>

      <div class="insta-container" id="instafeed-container"></div>
      <div class="profile-info" id="profile-info"></div>
    </section>

    <div class="row d-flex justify-content-center btmbanner mt-4">
      <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3696733888071014"
        crossorigin="anonymous"></script>
      <!-- Index Hero KLTG -->
      <ins class="adsbygoogle" align="center" data-ad-client="ca-pub-3696733888071014" data-ad-slot="5212427798"
        data-ad-format="auto" data-full-width-responsive="true"></ins>
      <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>


  </main><!-- End #main -->

  <div class="fb-button-container">
    <div class="fb-like"
      data-href="https://facebook.com/kltheguide/"
      data-width=""
      data-layout="standard"
      data-action="like"
      data-size="small"
      data-share="true">
    </div>
  </div>

  <!-- ======= Footer ======= -->
  <?php include 'footer.php'; ?>
  <!-- End Footer -->



  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/index.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/stevenschobert/instafeed.js@2.0.0rc1/src/instafeed.min.js"></script>

  <script>
    var myCarousel = document.getElementById('hero');

    myCarousel.addEventListener('slide.bs.carousel', event => {
      // do something...
      var activeslide = document.getElementsByClassName('carousel-item active');

      let banner_filename = activeslide[0].dataset.filename;
      let banner_name = activeslide[0].dataset.name;
      var xhttp = new XMLHttpRequest();


      // xhttp.onreadystatechange = function () {
      //   if (this.readyState == 4 && this.status == 200) {
      //    console.log(this.responseText);
      //   }
      // };
      xhttp.open("POST", "banner.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("banner=banner&banner_filename=" + banner_filename + "&banner_name=" + banner_name + "&clicks=0");
    })


    function banner_clicks(banner_filename, banner_name) {
      console.log("Banner clicked: " + banner_filename + ", " + banner_name);

      var xhttp = new XMLHttpRequest();

      xhttp.onreadystatechange = function() {
        if (xhttp.readyState === 4) {
          console.log("AJAX Response - Status: " + xhttp.status + ", Response: " + xhttp.responseText);
          if (xhttp.status !== 200) {
            console.error("AJAX Error: " + xhttp.status);
          }
        }
      };

      xhttp.open("POST", "banner.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("banner=banner&banner_filename=" + encodeURIComponent(banner_filename) + "&banner_name=" + encodeURIComponent(banner_name) + "&clicks=1");
    }
  </script>
  <script type="text/javascript">
    $("#country_selector").countrySelect({
      preferredCountries: ['my', 'sg', 'id', 'th'] // Optional: prioritize Malaysia, Singapore, Indonesia, Thailand
    });

    // Wait a moment for DOM to build, then clean up the native names
    setTimeout(function() {
      $('.country-list .country-name').each(function() {
        // Remove native text in parentheses
        let cleanText = $(this).text().replace(/\s*\(.*?\)/, '').trim();
        $(this).text(cleanText);
      });
    }, 100); // slight delay to ensure the list is rendered



    var userFeed = new Instafeed({
      get: 'user',
      userId: '1951282339',
      target: "instafeed-container",
      limit: 8,
      resolution: 'standard_resolution', // Change to standard_resolution for higher quality images
      accessToken: 'IGQWROTENqTVd2OExabl9hajhFQmlxQ0pyazZAydmNBdWEzaGdZAVXh3bUdJR0ktU0U2NlgxRlNjYUdtakZAzWFRienVudlUwbFg2YkFLUk0zbk9ISDNJSzJsb1hIa3hGVGpUX0lEdVh2RU1TRzlEYzIxaGdqWVhyZADAZD', // Replace with your Instagram access token
      template: '<a class="instafeed-item" href="{{link}}" target="_blank"><img src="{{image}}" alt="{{caption}}"/><div class="caption"><p>{{caption}}</p><p>❤️ {{likes}}</p></div></a>',
      filter: function(image) {
        // Check if the post is not a video
        return image.type !== 'video';
      }
    });

    userFeed.run();

    // Updated form submission handler with duplicate click prevention
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('subscribeForm');
      const submitButton = form.querySelector('input[name="subscribe"]'); // Get the submit button element

      if (!form || !submitButton) {
        console.error('Form or submit button not found!');
        return;
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // --- Prevent Duplicate Clicks ---
        if (submitButton.disabled) {
          console.log("Submission already in progress, ignoring duplicate click.");
          return; // Exit if already submitting
        }

        // Disable the button and optionally change its text/value to indicate processing
        submitButton.disabled = true;
        const originalButtonText = submitButton.value; // Store original text
        submitButton.value = 'Subscribing...'; // Change button text
        // You could also add a class for visual feedback, e.g., submitButton.classList.add('processing');

        const fd = new FormData(form);

        console.log('Submitting subscription...');

        try {
          const res = await fetch('admin/sub_handler.php?action=subscribe', {
            method: 'POST',
            body: fd
          });

          const text = await res.text();
          console.log('Raw response:', text);
          let data = {};
          try {
            data = JSON.parse(text);
          } catch (e) {
            console.error("Failed to parse JSON response:", e);
            // Consider showing a generic error if JSON parsing fails
            alert('⚠️ An error occurred. Please try again.');
            return; // Exit after error, but still re-enable button
          }

          if (data.ok) {
            alert('🎉 Subscription successful!');
            form.reset(); // Reset form fields
            // Optionally, you could display a success message in the UI instead of an alert
            // document.getElementById('subscription-message').textContent = 'Thank you for subscribing!';
          } else {
            // Use the error message from the server, or a default one
            alert('❌ Subscription failed: ' + (data.error || 'Unknown error'));
          }
        } catch (err) {
          console.error('Fetch error:', err);
          alert('⚠️ Network error. Please try again.');
        } finally {
          // --- Re-enable the button regardless of success or failure ---
          submitButton.disabled = false;
          submitButton.value = originalButtonText; // Restore original text
          // Remove any processing class if added: submitButton.classList.remove('processing');
        }
      });
    });
  </script>

</body>

</html>
