<?php
/* ============================================================================
 *                                index.php
 *
 *   Public HOMEPAGE. Hero + featured sections; content from the indexpage table.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include('admin/functions.php');

$query  = "SELECT * FROM indexpage";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $hero_title    = $row['hero_title'];
  $hero_title2   = $row['hero_title2'];
  $hero_subtitle = $row['hero_subtitle'];
  $tile1_title   = $row['tile1_title'];
  $tile1_subtitle= $row['tile1_subtitle'];
  $tile1_photo1  = $row['tile1_photo1'];
  $tile1_photo2  = $row['tile1_photo2'];
  $tile1_photo3  = $row['tile1_photo3'];
  $tile1_photo4  = $row['tile1_photo4'];
  $tile1_title1  = $row['tile1_title1'];
  $tile1_title2  = $row['tile1_title2'];
  $tile1_title3  = $row['tile1_title3'];
  $tile1_title4  = $row['tile1_title4'];
  $tile2_title   = $row['tile2_title'];
  $tile2_subtitle= $row['tile2_subtitle'];
  $tile2_photo1  = $row['tile2_photo1'];
  $tile2_photo2  = $row['tile2_photo2'];
  $tile2_photo3  = $row['tile2_photo3'];
  $tile2_photo4  = $row['tile2_photo4'];
  $tile2_photo5  = $row['tile2_photo5'];
  $tile2_photo6  = $row['tile2_photo6'];
  $tile2_title1  = $row['tile2_title1'];
  $tile2_title2  = $row['tile2_title2'];
  $tile2_title3  = $row['tile2_title3'];
  $tile2_title4  = $row['tile2_title4'];
  $tile2_title5  = $row['tile2_title5'];
  $tile2_title6  = $row['tile2_title6'];
  $tile3_title   = $row['tile3_title'];
  $tile3_subtitle= $row['tile3_subtitle'];
  $tile4_title   = $row['tile4_title'];
  $tile4_subtitle= $row['tile4_subtitle'];
}
?>

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
    width: min(92%, 920px);
    background: transparent;
    overflow: visible;
    border-radius: 14px;
  }

  /* Fixed viewport — stays put, clips the moving track */
  #hero.home-ad-carousel .carousel-inner {
    position: relative;
    overflow: hidden;
    width: 100%;
    border-radius: 14px;
    box-shadow: 0 18px 44px rgba(0, 0, 0, .45);
    cursor: grab;
  }

  /* The sliding track — this is what moves */
  #hero.home-ad-carousel .carousel-track {
    display: flex;
    flex-wrap: nowrap;
    width: 100%;
    transition: transform .5s ease;
    will-change: transform;
  }

  #hero.home-ad-carousel .carousel-track.dragging {
    transition: none;
  }

  #hero.home-ad-carousel .carousel-inner.grabbing {
    cursor: grabbing;
  }

  #hero.home-ad-carousel .carousel-item {
    flex: 0 0 100%;
    width: 100%;
    /* Override Bootstrap's .carousel-item { margin-right:-100%; float:left }
       which otherwise collapses every slide onto position 0 (all left=0),
       so only the first banner shows and the rest go blank when sliding. */
    margin-right: 0 !important;
    float: none !important;
    display: block !important;
    background: transparent;
    -webkit-user-select: none;
    user-select: none;
  }

  #hero.home-ad-carousel .carousel-item img {
    -webkit-user-drag: none;
    user-drag: none;
    pointer-events: none;
  }

  #hero.home-ad-carousel .hero-ad-link,
  #hero.home-ad-carousel .hero-ad-frame,
  #hero.home-ad-carousel picture {
    display: block;
    width: 100%;
  }

  #hero.home-ad-carousel .hero-ad-frame {
    aspect-ratio: 1920 / 300;
    overflow: hidden;
  }

  #hero.home-ad-carousel .hero-ad-img {
    display: block;
    height: 100%;
    max-height: none;
    object-fit: contain;
    object-position: center;
    width: 100%;
  }

  /* Progress-bar slide navigation */
  #hero.home-ad-carousel .banner-progress-nav {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    gap: 5px;
    padding: 0 10px 8px;
    z-index: 10;
  }

  #hero.home-ad-carousel .banner-progress-track {
    flex: 1;
    height: 3px;
    background: rgba(255, 255, 255, .28);
    border-radius: 3px;
    cursor: pointer;
    overflow: hidden;
    transition: background .2s;
  }

  #hero.home-ad-carousel .banner-progress-track:hover {
    background: rgba(255, 255, 255, .55);
  }

  #hero.home-ad-carousel .banner-progress-fill {
    height: 100%;
    width: 0;
    background: #fff;
    border-radius: 3px;
  }

  #hero.home-ad-carousel .banner-progress-fill.active-fill {
    animation: bannerFill 5s linear forwards;
  }

  #hero.home-ad-carousel .banner-progress-fill.done-fill {
    width: 100%;
  }

  @keyframes bannerFill {
    from { width: 0; }
    to   { width: 100%; }
  }

  #hero-animated.home-video-hero {
    position: relative;
    isolation: isolate;
    height: 100vh !important;
    min-height: 640px !important;
    padding: 80px 16px 230px !important;
    overflow: hidden;
    align-items: center !important;
    background: #071926;
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
    /* Punch up the footage so the hero reads bright & vibrant */
    filter: saturate(1.32) brightness(1.14) contrast(1.05);
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
      aspect-ratio: 1920 / 300;
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

  /* ===== Home: one section per screen (gentle snap) ===== */
  html {
    scroll-snap-type: y proximity;
    scroll-padding-top: 80px;
  }

  #index #featured-services.home-highlights,
  #index #services.excl-recos,
  #index #portfolio,
  #index #recent-blog-posts {
    min-height: 100vh;
    scroll-snap-align: start;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  /* ===== Home: unified section titles (Carter One / #1520A6) ===== */
  #index .section-header h2,
  #index .home-highlights__header h2 {
    font-family: "Carter One", cursive;
    color: #1520A6;
    font-weight: 400;
  }

  /* ===== KL Highlights — big image left + interactive cards right ===== */
  .featured-services.home-highlights {
    padding: 0;
    background: #fff;
    overflow: hidden;
  }

  /* fit the whole section in the visible area below the fixed navbar */
  #index #featured-services.home-highlights {
    min-height: calc(100vh - 84px);
  }

  .home-highlights__layout {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    min-height: calc(100vh - 84px);
  }

  /* Centered single column — header + interactive image cards */
  .home-highlights__content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    padding: 48px clamp(20px, 5vw, 60px);
    background: #fff;
  }

  .home-highlights__header {
    width: 100%;
    max-width: 760px;
    margin: 0 auto 30px;
    text-align: center;
  }

  .home-highlights__header h2 {
    margin: 0 0 12px;
    font-size: clamp(44px, 4vw, 60px);
    line-height: 1;
    letter-spacing: 0;
  }

  .home-highlights__header p {
    max-width: 640px;
    margin: 0 auto;
    color: #050505;
    font-family: var(--font-secondary);
    font-size: clamp(16px, 1.25vw, 20px);
    font-weight: 600;
    line-height: 1.25;
  }

  .home-highlights__cards {
    display: flex;
    flex-direction: column;
    gap: 24px;
    width: 100%;
    max-width: 900px;
    min-height: 0;
    margin: 0 auto;
  }

  .home-highlights__card {
    position: relative;
    display: flex;
    align-items: flex-end;
    flex: 0 0 auto;
    min-height: 175px;
    border-radius: 14px;
    overflow: hidden;
    text-decoration: none;
    color: #fff;
    background: #0d1117;
    box-shadow: 0 10px 26px rgba(13, 17, 23, .14);
    transition: transform .4s ease, box-shadow .4s ease;
  }

  .home-highlights__card img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1.01);
    transition: transform .55s ease;
    /* Brighter, more vibrant card imagery */
    filter: saturate(1.28) brightness(1.14) contrast(1.04);
  }

  /* dark/blue gradient for legibility */
  .home-highlights__card::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(180deg, rgba(0, 0, 0, .05) 0%, rgba(0, 0, 0, .58) 100%);
    transition: background .4s ease;
  }

  /* #1520A6 accent bar that sweeps in on hover */
  .home-highlights__card::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    z-index: 3;
    width: 100%;
    height: 5px;
    background: #1520A6;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .45s ease;
  }

  .home-highlights__card-body {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
    padding: 18px 24px;
  }

  .home-highlights__card-num {
    font-family: "Carter One", cursive;
    font-size: clamp(20px, 1.6vw, 28px);
    color: rgba(255, 255, 255, .82);
    transition: color .3s ease;
  }

  .home-highlights__card-title {
    flex: 1;
    font-family: var(--font-secondary);
    font-size: clamp(20px, 1.8vw, 28px);
    font-weight: 700;
    line-height: 1.1;
    text-shadow: 0 2px 8px rgba(0, 0, 0, .55);
    transform: translateX(0);
    transition: transform .35s ease;
  }

  .home-highlights__card-arrow {
    font-size: 24px;
    line-height: 1;
    color: #fff;
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity .35s ease, transform .35s ease;
  }

  .home-highlights__card:hover,
  .home-highlights__card:focus-visible {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(21, 32, 166, .28);
  }

  .home-highlights__card:hover img,
  .home-highlights__card:focus-visible img {
    transform: scale(1.08);
  }

  .home-highlights__card:hover::before,
  .home-highlights__card:focus-visible::before {
    background: linear-gradient(180deg, rgba(21, 32, 166, .18) 0%, rgba(0, 0, 0, .68) 100%);
  }

  .home-highlights__card:hover::after,
  .home-highlights__card:focus-visible::after {
    transform: scaleX(1);
  }

  .home-highlights__card:hover .home-highlights__card-title,
  .home-highlights__card:focus-visible .home-highlights__card-title {
    transform: translateX(6px);
  }

  .home-highlights__card:hover .home-highlights__card-arrow,
  .home-highlights__card:focus-visible .home-highlights__card-arrow {
    opacity: 1;
    transform: translateX(0);
  }

  .home-highlights__card:focus-visible {
    outline: 3px solid #1520A6;
    outline-offset: 3px;
  }

  @media (max-width: 991px) {
    html {
      scroll-snap-type: none;
    }

    #index #featured-services.home-highlights,
    #index #services.excl-recos,
    #index #portfolio,
    #index #recent-blog-posts {
      min-height: 0;
      scroll-snap-align: none;
    }

    .home-highlights__layout {
      min-height: 0;
    }

    .home-highlights__content {
      padding: 34px 18px 40px;
    }

    .home-highlights__card {
      min-height: 150px;
    }
  }

  @media (max-width: 575px) {
    .home-highlights__card {
      min-height: 130px;
    }

    .home-highlights__header h2 {
      font-size: 42px;
    }

    .home-highlights__header p {
      font-size: 16px;
    }

    .home-highlights__card-title {
      font-size: 20px;
    }

    .home-highlights__card-body {
      padding: 14px 18px;
    }
  }

  /* ===== Exclusive Recommendations — brighter, more vibrant imagery ===== */
  #index #services.excl-recos .recos-panel img {
    filter: saturate(1.28) brightness(1.12) contrast(1.04);
  }

  /* Lighten the dark overlay so the vibrancy shows through */
  #index #services.excl-recos .recos-panel::after {
    background: linear-gradient(160deg, rgba(0, 0, 0, .02) 0%, rgba(0, 0, 0, .42) 100%);
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

  <link rel="canonical" href="https://www.kltheguide.com.my/" />

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
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Carter+One&display=swap" rel="stylesheet">


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
          rgba(0, 0, 0, .28) 0%,
          rgba(0, 0, 0, .12) 40%,
          rgba(0, 0, 0, .34) 100%) !important;
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

    /* Hide the library's little dropdown caret next to the flag — the flag/input
       stays fully clickable so the country picker still works. */
    #hero-animated.home-video-hero .country-select .arrow,
    #hero-animated.home-video-hero .country-select .iti-arrow,
    #hero-animated.home-video-hero .country-select .selected-flag .arrow {
      display: none !important;
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
      <video class="home-video-hero__video" id="hero-video" autoplay muted loop playsinline preload="auto" aria-hidden="true">
        <source src="assets/img/KLOverview.mp4" type="video/mp4">
      </video>
      <!-- Animated WebP fallback for hosts that block .mp4 (e.g. InfinityFree) -->
      <img id="hero-webp" src="assets/img/KLOverview3.webp" aria-hidden="true"
           style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;">
      <script>
        (function(){
          var v = document.getElementById('hero-video');
          var w = document.getElementById('hero-webp');
          var swapped = false;
          function showWebp(){
            if (swapped) return;
            swapped = true;
            v.style.display = 'none';
            w.style.display = 'block';
          }
          // 1. The <source> itself failed to load (e.g. host blocks .mp4)
          var src = v.querySelector('source');
          if (src) src.addEventListener('error', showWebp);
          // 2. The <video> element errored or stalled
          v.addEventListener('error', showWebp);
          v.addEventListener('stalled', showWebp);
          // 3. Most reliable check: if real playback never starts, fall back.
          //    'timeupdate'/'playing' only fire when the video is actually running.
          var started = false;
          v.addEventListener('timeupdate', function(){ started = true; });
          v.addEventListener('playing', function(){ started = true; });
          setTimeout(function(){ if (!started) showWebp(); }, 8000);
        })();
      </script>
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
                  <!-- honeypot: hidden from humans, bots fill it -->
                  <input type="text" name="hp_email" tabindex="-1" autocomplete="off" aria-hidden="true"
                    style="position:absolute;left:-9999px;top:-9999px;height:0;width:0;opacity:0;">
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
      <section id="hero" class="hero home-ad-carousel lazy">

        <div class="carousel-inner">
        <div class="carousel-track">
        <?php
        $query = "SELECT * FROM banner WHERE status='1' OR status='2' ORDER BY banner_order ASC ";
        $result = mysqli_query($db, $query);
        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          $bannerFile = htmlspecialchars($row['banner_filename'], ENT_QUOTES);
          $bannerName = htmlspecialchars($row['banner_name'], ENT_QUOTES);
          $bannerMobile = !empty($row['banner_filename2']) ? htmlspecialchars($row['banner_filename2'], ENT_QUOTES) : '';
          // All banner slides load eagerly: lazy slides inside the transform
          // carousel never enter the viewport normally, so they'd stay blank.
          $loadingAttr = 'eager';

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
        </div>
        </div>
        <div class="banner-progress-nav" id="bannerProgressNav"></div>
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
      <?php
      $home_highlights_items = [
        [
          'img'   => $home_highlights_glance_image,
          'title' => urldecode($tile1_title1),
          'href'  => 'kl-glance.php',
          'alt'   => 'Kuala Lumpur skyline at sunset',
        ],
        [
          'img'   => $home_highlights_getting_around_image,
          'title' => urldecode($tile1_title2),
          'href'  => 'getting-around-kl.php',
          'alt'   => 'Rapid KL train in Kuala Lumpur',
        ],
        [
          'img'   => $home_highlights_travel_tips_image,
          'title' => urldecode($tile1_title3),
          'href'  => 'travel-tips.php',
          'alt'   => 'Aerial view of Kuala Lumpur city centre',
        ],
      ];
      ?>
      <div class="home-highlights__layout">
        <div class="home-highlights__content">
          <div class="home-highlights__header">
            <h2 id="homeHighlightsTitle"><?php echo htmlspecialchars(urldecode($tile1_title), ENT_QUOTES); ?></h2>
            <p><?php echo htmlspecialchars(urldecode($tile1_subtitle), ENT_QUOTES); ?></p>
          </div>

          <div class="home-highlights__cards">
            <?php foreach ($home_highlights_items as $i => $hl): ?>
              <a class="home-highlights__card"
                href="<?php echo htmlspecialchars($hl['href'], ENT_QUOTES); ?>"
                aria-label="Open <?php echo htmlspecialchars($hl['title'], ENT_QUOTES); ?>">
                <img src="assets/img/<?php echo htmlspecialchars($hl['img'], ENT_QUOTES); ?>"
                  alt="<?php echo htmlspecialchars($hl['alt'], ENT_QUOTES); ?>" loading="lazy" decoding="async">
                <span class="home-highlights__card-body">
                  <span class="home-highlights__card-num"><?php printf('%02d', $i + 1); ?></span>
                  <span class="home-highlights__card-title"><?php echo htmlspecialchars($hl['title'], ENT_QUOTES); ?></span>
                  <span class="home-highlights__card-arrow" aria-hidden="true">&rarr;</span>
                </span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section><!-- End Featured Services Section -->




    <!-- ======= Services Section ======= -->
    <section id="services" class="services excl-recos">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2 class="text-break">
            <?php echo urldecode($tile2_title) ?>
          </h2>
          <p>
            <?php echo urldecode($tile2_subtitle) ?>
          </p>
        </div>

      </div><!-- /container -->

      <div class="recos-carousel">
        <div class="recos-accordion" id="recosAccordion"
             role="region" aria-label="Exclusive recommendations">

          <div class="recos-panel is-active" data-index="0">
            <a href="explorekl.php#explorekl" aria-label="<?php echo strip_tags($tile2_title1) ?>">
              <img src="assets/img/recommendation/ExploringKL.jpg"
                alt="Explore KL" loading="eager" draggable="false">
              <span class="recos-panel__label"><?php echo $tile2_title1 ?></span>
            </a>
          </div>

          <div class="recos-panel" data-index="1">
            <a href="where-to-shop.php" aria-label="<?php echo strip_tags($tile2_title2) ?>">
              <img src="assets/img/recommendation/ShopLikeLocal.jpg"
                alt="Shop Like Locals" loading="lazy" draggable="false">
              <span class="recos-panel__label"><?php echo $tile2_title2 ?></span>
            </a>
          </div>

          <div class="recos-panel" data-index="2">
            <a href="accommodation.php#placetostay" aria-label="<?php echo strip_tags($tile2_title3) ?>">
              <img src="assets/img/recommendation/PlaceToStay.jpg"
                alt="Places To Stay" loading="lazy" draggable="false">
              <span class="recos-panel__label"><?php echo $tile2_title3 ?></span>
            </a>
          </div>

          <div class="recos-panel" data-index="3">
            <a href="spa.php" aria-label="<?php echo strip_tags($tile2_title4) ?>">
              <img src="assets/img/recommendation/SpaTime.jpg"
                alt="Spa Time" loading="lazy" draggable="false">
              <span class="recos-panel__label"><?php echo $tile2_title4 ?></span>
            </a>
          </div>

          <div class="recos-panel" data-index="4">
            <a href="medical-tourism.php#medicaltourism" aria-label="<?php echo strip_tags($tile2_title5) ?>">
              <img src="assets/img/recommendation/MedicalTourism.jpg"
                alt="Medical Tourism" loading="lazy" draggable="false">
              <span class="recos-panel__label"><?php echo $tile2_title5 ?></span>
            </a>
          </div>

          <div class="recos-panel" data-index="5">
            <a href="beyondkl.php#beyondkl" aria-label="<?php echo strip_tags($tile2_title6) ?>">
              <img src="assets/img/recommendation/BeyondKL.jpg"
                alt="Beyond KL" loading="lazy" draggable="false">
              <span class="recos-panel__label"><?php echo $tile2_title6 ?></span>
            </a>
          </div>

        </div><!-- /recos-accordion -->

        <div class="recos-bars" id="recosBars" role="tablist" aria-label="Slide indicators"></div>
      </div><!-- /recos-carousel -->

    </section><!-- End Services Section -->

    <script>
      (function () {
        var accordion = document.getElementById('recosAccordion');
        var barsWrap  = document.getElementById('recosBars');
        if (!accordion || !barsWrap) return;

        var panels  = Array.prototype.slice.call(accordion.querySelectorAll('.recos-panel'));
        var reduce  = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var DELAY   = 4000;
        var timer   = null;
        var current = 0;

        panels.forEach(function (_, i) {
          var b = document.createElement('button');
          b.type = 'button';
          b.className = 'recos-bar' + (i === 0 ? ' is-active' : '');
          b.setAttribute('role', 'tab');
          b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
          b.addEventListener('click', function () { stop(); goTo(i); start(); });
          barsWrap.appendChild(b);
        });
        var bars = Array.prototype.slice.call(barsWrap.children);

        function goTo(idx) {
          current = ((idx % panels.length) + panels.length) % panels.length;
          panels.forEach(function (p, i) { p.classList.toggle('is-active', i === current); });
          bars.forEach(function (b, i)   { b.classList.toggle('is-active', i === current); });
        }

        function next()  { goTo(current + 1); }
        function start() { if (reduce || timer) return; timer = setInterval(next, DELAY); }
        function stop()  { clearInterval(timer); timer = null; }

        // Hover (or keyboard focus) expands a panel; a click follows the
        // panel's link straight to its page.
        panels.forEach(function (panel, i) {
          panel.addEventListener('mouseenter', function () { stop(); goTo(i); });
          panel.addEventListener('focusin', function () { stop(); goTo(i); });
        });

        accordion.addEventListener('mouseenter', stop);
        accordion.addEventListener('mouseleave', start);
        document.addEventListener('visibilitychange', function () {
          document.hidden ? stop() : start();
        });

        goTo(0);
        start();
      })();
    </script>






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

    <?php
    /* DevPanel house ads (image banners managed from xp.php → Ads tab). Renders
       only on the homepage and only when ads are NOT hidden for this visitor, so
       blocked IPs never see them. Empty string when there are no active ads. */
    if (function_exists('kltg_render_house_ads')) {
        echo kltg_render_house_ads($db);
    }
    ?>

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

  <script>
    var myCarousel = document.getElementById('hero');

    // Transform-based banner slider (auto-advance + click bars + drag)
    (function () {
      var INTERVAL   = 5000; // matches the 5s progress-bar fill animation
      var inner      = myCarousel.querySelector('.carousel-inner');   // fixed viewport
      var slideTrack = myCarousel.querySelector('.carousel-track');   // the part that moves
      var slides     = Array.prototype.slice.call(myCarousel.querySelectorAll('.carousel-item'));
      var nav        = document.getElementById('bannerProgressNav');
      var current    = 0;
      var timer      = null;
      var paused     = false;

      if (!inner || !slideTrack || slides.length === 0) return;

      // Build progress bars
      slides.forEach(function (_, i) {
        var track = document.createElement('div');
        track.className = 'banner-progress-track';
        var fill = document.createElement('div');
        fill.className = 'banner-progress-fill';
        track.appendChild(fill);
        track.addEventListener('click', function () { goTo(i); });
        nav.appendChild(track);
      });

      var fills = Array.prototype.slice.call(nav.querySelectorAll('.banner-progress-fill'));

      function width() { return inner.offsetWidth; }

      function updateBars(idx) {
        fills.forEach(function (fill, i) {
          fill.classList.remove('active-fill', 'done-fill');
          fill.style.animationPlayState = '';
          if (i < idx) {
            fill.classList.add('done-fill');
          } else if (i === idx) {
            void fill.offsetWidth; // reflow to restart animation
            fill.classList.add('active-fill');
            if (paused) fill.style.animationPlayState = 'paused';
          }
        });
      }

      function trackView(idx) {
        var slide = slides[idx];
        var fn = slide.dataset.filename;
        var nm = slide.dataset.name;
        if (!fn || !nm) return;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'banner.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('banner=banner&banner_filename=' + fn + '&banner_name=' + nm + '&clicks=0');
      }

      function move(px) {
        slideTrack.style.transform = 'translateX(' + px + 'px)';
      }

      function goTo(idx) {
        if (idx < 0) idx = 0;
        if (idx > slides.length - 1) idx = slides.length - 1;
        current = idx;
        slideTrack.classList.remove('dragging'); // re-enable smooth transition
        move(-idx * width());
        updateBars(idx);
        trackView(idx);
        resetTimer();
      }

      function resetTimer() {
        clearInterval(timer);
        if (paused || slides.length < 2) return;
        timer = setInterval(function () {
          goTo((current + 1) % slides.length);
        }, INTERVAL);
      }

      // Initial position
      move(0);
      updateBars(0);
      resetTimer();

      // Keep alignment correct on resize
      window.addEventListener('resize', function () {
        slideTrack.classList.add('dragging'); // no animation while snapping
        move(-current * width());
        requestAnimationFrame(function () { slideTrack.classList.remove('dragging'); });
      });

      // Hover pause / resume
      myCarousel.addEventListener('mouseenter', function () {
        paused = true;
        clearInterval(timer);
        fills.forEach(function (f) {
          if (f.classList.contains('active-fill')) f.style.animationPlayState = 'paused';
        });
      });

      myCarousel.addEventListener('mouseleave', function () {
        paused = false;
        fills.forEach(function (f) {
          if (f.classList.contains('active-fill')) f.style.animationPlayState = 'running';
        });
        resetTimer();
      });

      // ---- Mouse / touch drag ----
      var dragging = false;
      var startX   = 0;
      var baseX    = 0;
      var moved    = 0;

      function dragStart(pageX) {
        dragging = true;
        startX   = pageX;
        baseX    = -current * width();
        moved    = 0;
        slideTrack.classList.add('dragging');
        inner.classList.add('grabbing');
        clearInterval(timer);
      }

      function dragMove(pageX) {
        if (!dragging) return;
        moved = pageX - startX;
        move(baseX + moved);
      }

      function dragEnd() {
        if (!dragging) return;
        dragging = false;
        slideTrack.classList.remove('dragging');
        inner.classList.remove('grabbing');
        var threshold = width() * 0.15;
        if (moved < -threshold)      goTo(Math.min(current + 1, slides.length - 1));
        else if (moved > threshold)  goTo(Math.max(current - 1, 0));
        else                         goTo(current);
      }

      // Mouse
      inner.addEventListener('mousedown', function (e) {
        dragStart(e.pageX);
        e.preventDefault();
      });
      document.addEventListener('mousemove', function (e) { dragMove(e.pageX); });
      document.addEventListener('mouseup', dragEnd);

      // Touch
      inner.addEventListener('touchstart', function (e) {
        dragStart(e.touches[0].pageX);
      }, { passive: true });
      inner.addEventListener('touchmove', function (e) {
        dragMove(e.touches[0].pageX);
      }, { passive: true });
      inner.addEventListener('touchend', dragEnd);

      // Suppress banner link click if it was actually a drag
      inner.addEventListener('click', function (e) {
        if (Math.abs(moved) > 6) {
          e.preventDefault();
          e.stopPropagation();
        }
      }, true);
    })();

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
            if (data.status === 'duplicate') {
              alert('✅ This email is already subscribed!');
            } else {
              alert('🎉 You\'re subscribed! Thanks for joining KL The Guide.');
            }
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
