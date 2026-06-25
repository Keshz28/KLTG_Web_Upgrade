<?php
/* ============================================================================
 *                          getting-around-kl.php
 *
 *   Public page — transport / getting-around-KL guide.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('admin/functions.php');
$query = "SELECT tile1_title1, tile1_title2, tile1_title3 FROM indexpage";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_assoc($result);
$tile1_title1 = $row['tile1_title1'];
$tile1_title2 = $row['tile1_title2'];
$tile1_title3 = $row['tile1_title3'];

$transports = [
  ['name' => 'Light Rail Transit (LRT)', 'img' => 'asset-backups/opt/LRT_Logo.jpg', 'modal' => 'lrt'],
  ['name' => 'Mass Rapid Transit (MRT)', 'img' => 'asset-backups/opt/MRT_Logo.jpg', 'modal' => 'mrt'],
  ['name' => 'KTM Komuter',              'img' => 'asset-backups/opt/KTM_Logo.jpg',      'modal' => 'ktm'],
  ['name' => 'KL Monorail',              'img' => 'asset-backups/opt/Monorai_Logo.jpg',  'modal' => 'monorail'],
  ['name' => 'KLIA Express',             'img' => 'asset-backups/opt/KLIA_Exp_Logo.png', 'modal' => 'klia'],
  ['name' => 'MRT Bus',                  'img' => 'asset-backups/opt/MRTBus_Logo.jpg',   'modal' => 'mrtbus'],
  ['name' => 'Rapid KL Bus',             'img' => 'asset-backups/opt/RapidKL_Logo.jpg',  'modal' => 'rapidkl'],
  ['name' => 'GOKL Bus',                 'img' => 'asset-backups/opt/GoKL_Logo.jpg',     'modal' => 'gokl'],
  ['name' => 'KL Hop on Hop off Bus',    'img' => 'asset-backups/opt/KLHopOn_Logo.jpg',  'modal' => 'hopon'],
  ['name' => 'E-Hailing Services',       'img' => 'asset-backups/opt/Taxi.jpg',          'modal' => 'grab'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Getting Around KL</title>

  <link rel="canonical" href="https://www.kltheguide.com.my/getting-around-kl.php" />
  <meta name="description" content="A guide to public transport in Kuala Lumpur: LRT, MRT, KTM Komuter, Monorail, KLIA Express, buses and ride-hailing.">
  <meta name="keywords" content="KL transport, Kuala Lumpur LRT, MRT KL, KTM Komuter, KL Monorail, KLIA Express, Rapid KL, GoKL, Hop on Hop off, Grab Malaysia">

  <!-- Open Graph -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/getting-around-kl.php" />
  <meta property="og:title" content="KL The Guide - Getting Around KL" />
  <meta property="og:description" content="A guide to public transport in Kuala Lumpur." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/getting-around-kl.php" />
  <meta property="twitter:title" content="KL The Guide - Getting Around KL" />
  <meta property="twitter:description" content="A guide to public transport in Kuala Lumpur." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>

  <style>
    /* ===== Tabs Navigation Bar (shared look with KL@A Glance) ===== */
    .klag-tab-bar {
      position: fixed;
      top: 70px;
      left: 0;
      right: 0;
      height: 120px;
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
      border-bottom-color: #0ea2bd;
      transform: translateY(-8px);
      box-shadow: 0 12px 32px rgba(0,0,0,.55);
      z-index: 2;
    }

    .klag-tab__bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      filter: brightness(0.48);
      transition: filter .3s;
    }

    .klag-tab:hover .klag-tab__bg { filter: brightness(0.68); }
    .klag-tab.is-active .klag-tab__bg { filter: brightness(0.85); }

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

    @media (max-width: 768px) {
      .klag-tab-bar { height: 90px; }
      .klag-tab__label { font-size: 12px; }
    }

    @media (max-width: 480px) {
      .klag-tab-bar { height: 76px; }
      .klag-tab__label { font-size: 10.5px; letter-spacing: 0; }
    }

    /* ===== Getting Around KL ===== */
    #gak-section {
      position: relative;
      min-height: 100vh;
      padding: 210px 0 90px;
      overflow: hidden;
    }

    @media (max-width: 768px) { #gak-section { padding-top: 175px; } }
    @media (max-width: 480px) { #gak-section { padding-top: 160px; } }

    #gak-section .gak-bg {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 30% 20%, #24426e 0%, #1a3157 55%, #142844 100%);
      z-index: 0;
    }

    #gak-section .gak-overlay {
      position: absolute;
      inset: 0;
      background: transparent;
      z-index: 1;
    }

    .gak-wrap {
      position: relative;
      z-index: 2;
      max-width: 1320px;
      margin: 0 auto;
      padding: 0 28px;
    }

    .gak-heading {
      text-align: center;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: clamp(2rem, 4vw, 2.8rem);
      font-weight: 800;
      letter-spacing: 0.5px;
      margin: 0 0 44px;
      text-shadow: 0 4px 22px rgba(0, 0, 0, 0.5);
    }

    .gak-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 22px;
    }

    .gak-card {
      position: relative;
      aspect-ratio: 1 / 1;
      border-radius: 14px;
      overflow: hidden;
      cursor: pointer;
      box-shadow: 0 10px 28px rgba(0, 0, 0, 0.4);
      transition: transform 0.35s ease, box-shadow 0.35s ease;
      text-decoration: none;
      display: block;
    }

    .gak-card img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.45s ease, filter 0.3s ease;
    }

    .gak-card::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(0,0,0,0) 45%, rgba(0,0,0,0.78) 100%);
      pointer-events: none;
    }

    .gak-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 38px rgba(0, 0, 0, 0.55);
    }

    .gak-card:hover img {
      transform: scale(1.08);
      filter: brightness(1.08);
    }

    .gak-card-label {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 14px;
      z-index: 2;
      text-align: center;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: clamp(0.78rem, 1vw, 0.95rem);
      font-weight: 700;
      padding: 0 10px;
      line-height: 1.22;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.75);
    }

    /* Responsive grid: keep 5 across as long as practical, then step down */
    @media (max-width: 1024px) {
      .gak-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 820px) {
      .gak-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 540px) {
      .gak-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
    }

    /* ===== Transport Modal ===== */
    .tmodal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.72);
      z-index: 900;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 198px 20px 20px; /* 70px navbar + 120px tab bar + 8px gap */
      overflow-y: auto;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }
    @media (max-width: 768px) {
      .tmodal-backdrop { padding-top: 168px; } /* 70px + 90px tab bar + 8px */
    }
    @media (max-width: 480px) {
      .tmodal-backdrop { padding-top: 154px; } /* 70px + 76px tab bar + 8px */
    }
    .tmodal-backdrop.is-open {
      opacity: 1;
      pointer-events: auto;
    }

    .tmodal {
      background: #fff;
      border-radius: 14px;
      width: 100%;
      max-width: 820px;
      max-height: calc(100vh - 218px); /* viewport minus backdrop padding top+bottom */
      overflow: hidden;
      display: flex;
      flex-direction: column;
      position: relative;
      transform: translateY(18px);
      transition: transform 0.28s ease;
    }
    @media (max-width: 768px) {
      .tmodal { max-height: calc(100vh - 188px); }
    }
    @media (max-width: 480px) {
      .tmodal { max-height: calc(100vh - 174px); }
    }
    .tmodal-backdrop.is-open .tmodal {
      transform: translateY(0);
    }
    .tmodal__inner { overflow-y: auto; flex: 1 1 auto; scroll-behavior: smooth; }
    .tmodal__inner::-webkit-scrollbar { width: 5px; }
    .tmodal__inner::-webkit-scrollbar-thumb { background: #ccc; border-radius: 99px; }

    /* Close button */
    .tmodal__close {
      position: absolute;
      top: 12px;
      right: 14px;
      z-index: 10;
      background: rgba(0, 0, 0, 0.45);
      border: none;
      color: #fff;
      width: 34px;
      height: 34px;
      border-radius: 50%;
      font-size: 1.2rem;
      line-height: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }
    .tmodal__close:hover { background: rgba(0, 0, 0, 0.7); }

    /* Hero banner */
    .tmodal__hero {
      position: relative;
      width: 100%;
      aspect-ratio: 16 / 6;
      border-radius: 14px 14px 0 0;
      overflow: hidden;
    }
    .tmodal__hero img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .tmodal__hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(0,0,0,.1) 0%, rgba(0,0,0,.55) 100%);
    }
    .tmodal__hero-title {
      position: absolute;
      inset: 0;
      z-index: 2;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: clamp(1.4rem, 3.5vw, 2.4rem);
      text-align: center;
      text-shadow: 0 3px 18px rgba(0,0,0,.6);
      margin: 0;
      padding: 0 20px;
    }

    /* Body */
    .tmodal__body {
      padding: 28px 32px 32px;
    }
    @media (max-width: 540px) { .tmodal__body { padding: 20px; } }

    .tmodal__desc {
      font-size: 0.97rem;
      line-height: 1.7;
      color: #333;
      text-align: center;
      margin: 0 0 16px;
    }
    .tmodal__meta {
      display: flex;
      flex-wrap: wrap;
      gap: 12px 32px;
      justify-content: center;
      margin-bottom: 28px;
      font-size: 0.93rem;
      color: #444;
    }
    .tmodal__meta a {
      color: #0ea2bd;
      word-break: break-all;
    }
    .tmodal__meta a:hover { text-decoration: underline; }

    /* Route tabs */
    .tmodal__route-heading {
      font-family: 'Poppins', sans-serif;
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #888;
      margin: 0 0 12px;
    }
    .tmodal__tabs {
      display: flex;
      gap: 0;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
      border: 1px solid #ddd;
    }
    .tmodal__tab {
      flex: 1;
      padding: 11px 6px;
      border: none;
      background: #f0f0f0;
      color: #555;
      font-family: 'Poppins', sans-serif;
      font-size: clamp(0.68rem, 1.4vw, 0.82rem);
      font-weight: 700;
      letter-spacing: 0.04em;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      border-right: 1px solid #ddd;
    }
    .tmodal__tab:last-child { border-right: none; }
    .tmodal__tab:hover { background: #e0e0e0; }
    .tmodal__tab.is-active {
      background: #1a2744;
      color: #fff;
    }

    /* Route map panels */
    .tmodal__panel { display: none; }
    .tmodal__panel.is-active { display: block; }

    /* ── Inline route diagram ── */
    .route-diagram { padding: 4px 0 8px; }

    .route-cols {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 12px;
      align-items: start;
    }
    @media (max-width: 480px) { .route-cols { grid-template-columns: 1fr; } }

    .route-col-dir {
      grid-column: 1 / -1;
      text-align: center;
      font-size: 0.7rem;
      color: #aaa;
      padding: 2px 0 6px;
      font-style: italic;
    }

    /* Each station list */
    .route-list {
      list-style: none;
      margin: 0;
      padding: 0;
      position: relative;
      --lc: #0065BD;
      --dx: 8px;            /* dot/line x-centre */
    }
    /* Continuous vertical line */
    .route-list::before {
      content: "";
      position: absolute;
      left: calc(var(--dx) - 1px);
      top: 10px;
      bottom: 10px;
      width: 2px;
      background: var(--lc);
      border-radius: 2px;
    }

    .rs {
      position: relative;
      padding: 4px 4px 4px calc(var(--dx) * 2 + 8px);
      font-size: 0.82rem;
      color: #333;
      font-family: 'Poppins', sans-serif;
      line-height: 1.35;
    }
    /* Station dot */
    .rs::before {
      content: "";
      position: absolute;
      left: calc(var(--dx) - 5px);
      top: 50%;
      transform: translateY(-50%);
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--lc);
      border: 2px solid #fff;
      box-sizing: border-box;
      z-index: 1;
    }
    /* Terminal — square cap */
    .rs.terminal::before {
      border-radius: 2px;
      width: 12px;
      height: 12px;
      left: calc(var(--dx) - 6px);
    }
    /* Interchange — hollow ring */
    .rs.interchange::before {
      background: #fff;
      border: 2.5px solid var(--lc);
      box-shadow: 0 0 0 1px var(--lc);
    }
    .rs.interchange { font-weight: 600; color: #111; }

    /* Small interchange/note tag */
    .rs em {
      font-style: normal;
      font-size: 0.68rem;
      color: #888;
      margin-left: 5px;
      background: #f0f0f0;
      border-radius: 3px;
      padding: 0 4px;
      white-space: nowrap;
    }

    .route-note {
      text-align: center;
      font-size: 0.7rem;
      color: #bbb;
      margin-top: 14px;
      font-style: italic;
    }

    /* MRT modal — purple active tab */
    #mrtModal .tmodal__tab.is-active { background: #5B3FA6; }

    /* KTM modal — dark red active tab */
    #ktmModal .tmodal__tab.is-active { background: #8B1A1A; }

    /* MRT route map image */
    .mrt-route-img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      display: block;
      min-height: 180px;
      background: #f5f5f5;
    }

    /* Abbreviation legend strip */
    .route-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 14px;
      background: #f5f5f5;
      border-radius: 8px;
      padding: 10px 14px;
      margin-bottom: 18px;
      align-items: center;
    }
    .rl-item {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.72rem;
      color: #555;
      font-family: 'Poppins', sans-serif;
    }
    .rl-badge {
      display: inline-block;
      background: var(--lc, #888);
      color: #fff;
      font-size: 0.62rem;
      font-weight: 700;
      border-radius: 3px;
      padding: 2px 6px;
      letter-spacing: 0.05em;
    }

    /* Hub station — connects to Monorail / BRT Sunway / KLIA Express */
    .rs.hub::before {
      box-shadow: 0 0 0 3px #f0a500, 0 0 0 5px rgba(240,165,0,0.18);
    }
    .rs.hub em { background: #fff3cd; color: #7a5000; font-weight: 600; }

    /* ===== "Useful Routes" cards (MRT Bus & Rapid KL Bus) ===== */
    .useful-routes {
      display: grid;
      gap: 10px;
      margin-bottom: 18px;
    }
    .useful-route {
      display: grid;
      grid-template-columns: auto 1fr;
      align-items: start;
      gap: 12px;
      border: 1px solid #e5e7eb;
      border-left: 4px solid #0ea2bd;
      border-radius: 8px;
      padding: 12px 14px;
      background: #fafbfc;
      transition: background .2s, transform .2s;
    }
    .useful-route:hover {
      background: #f5f7fa;
      transform: translateX(2px);
    }
    .useful-route__number {
      display: inline-block;
      background: #1a2744;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      border-radius: 5px;
      padding: 5px 9px;
      min-width: 52px;
      text-align: center;
      line-height: 1.1;
      white-space: nowrap;
    }
    .useful-route__text {
      display: block;
    }
    .useful-route__name {
      display: block;
      font-family: 'Poppins', sans-serif;
      font-size: 0.92rem;
      font-weight: 700;
      color: #1a2744;
      line-height: 1.3;
      margin-bottom: 2px;
    }
    .useful-route__desc {
      display: block;
      font-size: 0.8rem;
      color: #555;
      line-height: 1.45;
    }

    /* "Plan your route" CTA button */
    .tmodal__planner-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin: 8px auto 4px;
      background: #0ea2bd;
      color: #fff;
      text-decoration: none;
      padding: 12px 24px;
      border-radius: 28px;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 0.88rem;
      letter-spacing: 0.02em;
      box-shadow: 0 6px 16px rgba(14, 162, 189, 0.28);
      transition: background .2s, transform .2s, box-shadow .2s;
    }
    .tmodal__planner-btn:hover,
    .tmodal__planner-btn:focus {
      background: #0c8aa3;
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(14, 162, 189, 0.4);
    }
    .tmodal__planner-wrap {
      text-align: center;
      margin-top: 8px;
    }

    /* ===== Grab modal: How-to steps, ride types, app buttons ===== */
    .howto-steps {
      display: grid;
      gap: 12px;
      margin-bottom: 22px;
    }
    .howto-step {
      display: grid;
      grid-template-columns: 34px 1fr;
      gap: 12px;
      align-items: start;
    }
    .howto-step__num {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #0ea2bd;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 0.92rem;
      font-weight: 700;
      line-height: 1;
      box-shadow: 0 4px 10px rgba(14, 162, 189, 0.32);
    }
    .howto-step__text {
      font-size: 0.88rem;
      color: #333;
      line-height: 1.5;
      padding-top: 6px;
    }
    .howto-step__text strong { color: #1a2744; }

    .ride-types {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-bottom: 20px;
    }
    @media (max-width: 540px) {
      .ride-types { grid-template-columns: 1fr; }
    }
    .ride-type {
      border: 1px solid #e5e7eb;
      border-top: 3px solid #00B14F;
      border-radius: 8px;
      padding: 12px 14px;
      background: #fafbfc;
      transition: transform .2s, box-shadow .2s;
    }
    .ride-type:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }
    .ride-type__name {
      display: block;
      font-family: 'Poppins', sans-serif;
      font-size: 0.92rem;
      font-weight: 700;
      color: #1a2744;
      margin-bottom: 4px;
    }
    .ride-type__when {
      display: block;
      font-size: 0.78rem;
      color: #555;
      line-height: 1.45;
    }

    .app-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: center;
      margin: 6px 0 14px;
    }
    .app-btn {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      background: #1a2744;
      color: #fff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 12px;
      font-family: 'Poppins', sans-serif;
      transition: background .2s, transform .2s, box-shadow .2s;
      box-shadow: 0 4px 12px rgba(26, 39, 68, 0.22);
    }
    .app-btn:hover, .app-btn:focus {
      background: #0c1530;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(26, 39, 68, 0.35);
    }
    .app-btn__icon {
      font-size: 1.7rem;
      line-height: 1;
    }
    .app-btn__sub {
      display: block;
      font-size: 0.66rem;
      font-weight: 400;
      opacity: .82;
      letter-spacing: 0.04em;
    }
    .app-btn__main {
      display: block;
      font-size: 0.95rem;
      font-weight: 700;
      margin-top: 1px;
      letter-spacing: 0.01em;
    }
    .grab-alternatives {
      text-align: center;
      font-size: 0.78rem;
      color: #777;
      margin-top: 14px;
      padding-top: 14px;
      border-top: 1px dashed #e5e7eb;
      line-height: 1.55;
    }
    .grab-alternatives strong { color: #1a2744; }

    /* E-hailing modal: gradient hero (no single-brand logo) + provider links */
    .tmodal__hero--ehailing {
      background: linear-gradient(135deg, #0ea2bd 0%, #1a2744 100%);
    }
    .ride-type__link {
      display: inline-block;
      margin-top: 9px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.74rem;
      font-weight: 600;
      color: #0ea2bd;
      text-decoration: none;
    }
    .ride-type__link:hover { text-decoration: underline; }
    .ride-type__img {
      display: block;
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 6px;
      margin-bottom: 10px;
    }

    /* Modal accent overrides */
    #kliaModal .tmodal__tab.is-active { background: #C8102E; }
    #goklModal .tmodal__tab.is-active { background: #00843D; }
    #goklModal .tmodal__tab[data-tab="gokl-purple"].is-active { background: #5B3FA6; }
    #goklModal .tmodal__tab[data-tab="gokl-red"].is-active    { background: #CC0000; }
    #goklModal .tmodal__tab[data-tab="gokl-blue"].is-active   { background: #0065BD; }
    #hoponModal .tmodal__tab.is-active { background: #C8102E; }
    #mrtbusModal .useful-route { border-left-color: #5B3FA6; }
    #mrtbusModal .useful-route__number { background: #5B3FA6; }
    #rapidklModal .useful-route { border-left-color: #C8102E; }
    #rapidklModal .useful-route__number { background: #C8102E; }
  </style>

</head>

<body class="has-video-hero">

  <?php include 'nav.php'; ?>

  <!-- ── Thumbnail tab bar ──────────────────────────────────── -->
  <div class="klag-tab-bar" role="navigation" aria-label="KL Highlights sections">

    <a class="klag-tab" href="kl-glance.php">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/opt/kl@aglance.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title1), ENT_QUOTES); ?>
      </span>
    </a>

    <a class="klag-tab is-active" href="getting-around-kl.php" aria-current="page">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/opt/gettingaroundkl.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title2), ENT_QUOTES); ?>
      </span>
    </a>

    <a class="klag-tab" href="travel-tips.php">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/opt/traveltips.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title3), ENT_QUOTES); ?>
      </span>
    </a>

  </div>

  <main id="getting-around-kl-page">

    <section id="gak-section">
      <div class="gak-bg"></div>
      <div class="gak-overlay"></div>

      <div class="gak-wrap">
        <h2 class="gak-heading">Getting Around KL</h2>

        <div class="gak-grid">
          <?php foreach ($transports as $t): ?>
            <a class="gak-card"
               href="#"
               <?php if (!empty($t['modal'])): ?>data-modal="<?php echo htmlspecialchars($t['modal'], ENT_QUOTES); ?>"<?php endif; ?>>
              <img src="<?php echo htmlspecialchars($t['img'], ENT_QUOTES); ?>"
                   alt="<?php echo htmlspecialchars($t['name'], ENT_QUOTES); ?>"
                   width="540" height="540"
                   loading="lazy" decoding="async">
              <span class="gak-card-label"><?php echo htmlspecialchars($t['name'], ENT_QUOTES); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

  </main><!-- /#getting-around-kl-page -->

  <?php include 'footer.php'; ?>

  <!-- ── MRT Modal ─────────────────────────────────────────── -->
  <div class="tmodal-backdrop" id="mrtBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="mrtModalTitle" aria-hidden="true">
    <div class="tmodal" id="mrtModal">

      <button class="tmodal__close" id="mrtClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <!-- Hero banner -->
      <div class="tmodal__hero">
        <img data-src="asset-backups/opt/MRT_Logo.jpg" src="" alt="Mass Rapid Transit MRT Kuala Lumpur">
        <h2 class="tmodal__hero-title" id="mrtModalTitle">Mass Rapid Transit (MRT)</h2>
      </div>

      <!-- Info -->
      <div class="tmodal__body">
        <p class="tmodal__desc">
          The Mass Rapid Transit (MRT) is a premier high-capacity rail network designed for seamless
          travel across Greater Kuala Lumpur. The MRT currently consists of two primary lines: the
          Kajang Line and the Putrajaya Line. This is the ideal mode of transport if you are looking
          to commute from the outer suburbs or visit major landmarks and shopping districts that lie
          beyond the immediate city center.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> +603-2095 3030</span>
          <span>
            <a href="https://www.mymrt.com.my/ms/" target="_blank" rel="noopener noreferrer">
              https://www.mymrt.com.my/ms/
            </a>
          </span>
        </div>

        <!-- Route map tabs -->
        <p class="tmodal__route-heading">Route Map</p>
        <div class="tmodal__tabs" role="tablist">
          <button class="tmodal__tab is-active" role="tab" aria-selected="true"
                  data-tab="mrt-kajang">KAJANG LINE</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="mrt-putrajaya">PUTRAJAYA LINE</button>
        </div>

        <!-- Legend -->
        <div class="route-legend">
          <span class="rl-item"><span class="rl-badge" style="--lc:#CC0000">KG</span> Kajang Line</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5B3FA6">PY</span> Putrajaya Line</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#0065BD">KJ</span> LRT Kelana Jaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#007A3D">SP</span> LRT Sri Petaling</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#9B1E32">AG</span> LRT Ampang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5C3A1E">KTM</span> KTM Komuter</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">ERL</span> KLIA Express</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#E8720C">Mono</span> KL Monorail</span>
        </div>

        <!-- MRT KAJANG LINE -->
        <div class="tmodal__panel is-active" id="panel-mrt-kajang" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Kwasa Damansara → Kajang</div>

              <!-- Column 1: Kwasa Damansara → Tun Razak Exchange -->
              <ul class="route-list" style="--lc:#CC0000">
                <li class="rs terminal interchange">Kwasa Damansara <em>↔ MRT PY</em></li>
                <li class="rs">Kwasa Sentral</li>
                <li class="rs">Kota Damansara</li>
                <li class="rs">Surian</li>
                <li class="rs">Mutiara Damansara</li>
                <li class="rs">Bandar Utama</li>
                <li class="rs">TTDI</li>
                <li class="rs">Phileo Damansara</li>
                <li class="rs">Pusat Bandar Damansara</li>
                <li class="rs">Semantan</li>
                <li class="rs">Muzium Negara</li>
                <li class="rs interchange">Pasar Seni <em>↔ LRT KJ</em></li>
                <li class="rs interchange">Merdeka <em>↔ LRT SP/AG</em></li>
                <li class="rs interchange hub">Bukit Bintang <em>↔ Mono</em></li>
                <li class="rs interchange">Tun Razak Exchange <em>↔ MRT PY</em></li>
              </ul>

              <!-- Column 2: Cochrane → Kajang -->
              <ul class="route-list" style="--lc:#CC0000">
                <li class="rs">Cochrane</li>
                <li class="rs interchange">Maluri <em>↔ LRT AG</em></li>
                <li class="rs">Taman Pertama</li>
                <li class="rs">Taman Midah</li>
                <li class="rs">Taman Mutiara</li>
                <li class="rs">Taman Connaught</li>
                <li class="rs">Taman Suntex</li>
                <li class="rs">Sri Raya</li>
                <li class="rs">Bandar Tun Hussein Onn</li>
                <li class="rs">Batu 11 Cheras</li>
                <li class="rs">Bukit Dukung</li>
                <li class="rs">Sungai Jernih</li>
                <li class="rs interchange">Stadium Kajang <em>↔ KTM</em></li>
                <li class="rs terminal">Kajang</li>
              </ul>
            </div>
            <p class="route-note">29 stations &nbsp;·&nbsp; Interchange stations shown with ring markers</p>
          </div>
        </div>

        <!-- MRT PUTRAJAYA LINE -->
        <div class="tmodal__panel" id="panel-mrt-putrajaya" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Kwasa Damansara → Putrajaya Sentral</div>

              <!-- Column 1: Kwasa Damansara → Hospital KL -->
              <ul class="route-list" style="--lc:#5B3FA6">
                <li class="rs terminal interchange">Kwasa Damansara <em>↔ MRT KG</em></li>
                <li class="rs">Rubber Research Institution</li>
                <li class="rs">Kampung Selamat</li>
                <li class="rs interchange hub">Sungai Buloh <em>↔ KTM</em></li>
                <li class="rs">Damansara Damai</li>
                <li class="rs">Sri Damansara Barat</li>
                <li class="rs">Sri Damansara Sentral</li>
                <li class="rs interchange">Sri Damansara Timur <em>↔ KTM</em></li>
                <li class="rs">Metro Prima</li>
                <li class="rs">Kepong Baru</li>
                <li class="rs">Jinjang</li>
                <li class="rs">Sri Delima</li>
                <li class="rs interchange">Kampung Batu <em>↔ KTM</em></li>
                <li class="rs">Kentonmen</li>
                <li class="rs">Jalan Ipoh</li>
                <li class="rs">Sentul Barat</li>
                <li class="rs interchange hub">Titiwangsa <em>↔ LRT SP/AG / Mono</em></li>
                <li class="rs">Hospital Kuala Lumpur</li>
              </ul>

              <!-- Column 2: Raja Uda-UTM → Putrajaya Sentral -->
              <ul class="route-list" style="--lc:#5B3FA6">
                <li class="rs">Raja Uda–UTM</li>
                <li class="rs interchange">Ampang Park <em>↔ LRT KJ</em></li>
                <li class="rs">Persiaran KLCC</li>
                <li class="rs">Conlay–Kompleks Kraf</li>
                <li class="rs interchange">Tun Razak Exchange <em>↔ MRT KG</em></li>
                <li class="rs interchange">Chan Sow Lin <em>↔ LRT SP/AG</em></li>
                <li class="rs">Bandar Malaysia Utara</li>
                <li class="rs">Bandar Malaysia Selatan</li>
                <li class="rs">Kuchai</li>
                <li class="rs">Taman Naga Emas</li>
                <li class="rs interchange">Sungai Besi <em>↔ LRT SP</em></li>
                <li class="rs">Taman Teknologi</li>
                <li class="rs">Serdang Raya Utara</li>
                <li class="rs">Serdang Raya Selatan</li>
                <li class="rs">Serdang Jaya</li>
                <li class="rs">UPM</li>
                <li class="rs">Taman Universiti</li>
                <li class="rs terminal interchange hub">Putrajaya Sentral <em>↔ ERL</em></li>
              </ul>
            </div>
            <p class="route-note">36 stations &nbsp;·&nbsp; Interchange stations shown with ring markers</p>
          </div>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /MRT Modal -->

  <script>
    (function () {
      var backdrop = document.getElementById('mrtBackdrop');
      var closeBtn = document.getElementById('mrtClose');

      document.querySelectorAll('[data-modal="mrt"]').forEach(function (card) {
        card.addEventListener('click', function (e) {
          e.preventDefault();
          var heroImg = backdrop.querySelector('.tmodal__hero img[data-src]');
          if (heroImg) { heroImg.src = heroImg.dataset.src; heroImg.removeAttribute('data-src'); }
          backdrop.classList.add('is-open');
          backdrop.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
          closeBtn.focus();
        });
      });

      function closeModal() {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
      closeBtn.addEventListener('click', closeModal);
      backdrop.addEventListener('click', function (e) { if (e.target === backdrop) closeModal(); });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && backdrop.classList.contains('is-open')) closeModal();
      });

      document.querySelectorAll('#mrtModal .tmodal__tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
          document.querySelectorAll('#mrtModal .tmodal__tab').forEach(function (t) {
            t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false');
          });
          document.querySelectorAll('#mrtModal .tmodal__panel').forEach(function (p) {
            p.classList.remove('is-active');
          });
          tab.classList.add('is-active');
          tab.setAttribute('aria-selected', 'true');
          var panel = document.getElementById('panel-' + tab.getAttribute('data-tab'));
          if (panel) panel.classList.add('is-active');
        });
      });
    })();
  </script>

  <!-- ── LRT Modal ─────────────────────────────────────────── -->
  <div class="tmodal-backdrop" id="lrtBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="lrtModalTitle" aria-hidden="true">
    <div class="tmodal" id="lrtModal">

      <button class="tmodal__close" id="lrtClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <!-- Hero banner -->
      <div class="tmodal__hero">
        <img data-src="asset-backups/LRT_Title.jpg" src="" alt="Light Rail Transit LRT Kuala Lumpur">
        <h2 class="tmodal__hero-title" id="lrtModalTitle">Light Rail Transit (LRT)</h2>
      </div>

      <!-- Info -->
      <div class="tmodal__body">
        <p class="tmodal__desc">
          The Light Rail Transit (LRT) is one of the most commonly used public rail transport.
          The LRT is divided into two lines; Ampang/Sri Petaling and Kelana Jaya. This is the best
          means of transport if you are thinking of visiting places that aren't within walking
          distance of Kuala Lumpur city centre.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> 03-7885 2585</span>
          <span>
            <a href="https://myrapid.com.my/bus-train/rapid-kl/lrt/" target="_blank" rel="noopener noreferrer">
              https://myrapid.com.my/bus-train/rapid-kl/lrt/
            </a>
          </span>
        </div>

        <!-- Route map tabs -->
        <p class="tmodal__route-heading">Route Map</p>
        <div class="tmodal__tabs" role="tablist">
          <button class="tmodal__tab is-active" role="tab" aria-selected="true"
                  data-tab="kelana">KELANA JAYA LINE</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="sri-petaling">SRI PETALING LINE</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="ampang">AMPANG LINE</button>
        </div>

        <!-- Abbreviation legend -->
        <div class="route-legend">
          <span class="rl-item"><span class="rl-badge" style="--lc:#0065BD">KJ</span> Kelana Jaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#007A3D">SP</span> Sri Petaling</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#9B1E32">AG</span> Ampang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5B3FA6">MRT</span> MRT Lines</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5C3A1E">KTM</span> KTM Komuter</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">ERL</span> KLIA Express</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#E8720C">Mono</span> KL Monorail</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#D4A000">BRT</span> BRT Sunway</span>
        </div>

        <!-- KELANA JAYA LINE -->
        <div class="tmodal__panel is-active" id="panel-kelana" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Gombak → Putra Heights</div>

              <!-- Column 1: Gombak → Asia Jaya -->
              <ul class="route-list" style="--lc:#0065BD">
                <li class="rs terminal">Gombak</li>
                <li class="rs">Taman Melati</li>
                <li class="rs">Wangsa Maju</li>
                <li class="rs">Sri Rampai</li>
                <li class="rs">Setapak Jaya</li>
                <li class="rs">Jelatek</li>
                <li class="rs">Damai</li>
                <li class="rs">Ampang Park</li>
                <li class="rs">KLCC</li>
                <li class="rs">Kampung Baru</li>
                <li class="rs">Dang Wangi</li>
                <li class="rs interchange">Masjid Jamek <em>↔ SP / AG</em></li>
                <li class="rs interchange">Pasar Seni <em>↔ SP / AG</em></li>
                <li class="rs interchange hub">KL Sentral <em>↔ SP / AG / KTM / ERL / Mono</em></li>
                <li class="rs">Abdullah Hukum</li>
                <li class="rs">Bangsar</li>
                <li class="rs">Universiti</li>
                <li class="rs">Kerinchi</li>
                <li class="rs">Taman Jaya</li>
                <li class="rs">Asia Jaya</li>
              </ul>

              <!-- Column 2: Taman Paramount → Putra Heights -->
              <ul class="route-list" style="--lc:#0065BD">
                <li class="rs">Taman Paramount</li>
                <li class="rs">Taman Bahagia</li>
                <li class="rs">Ara Damansara</li>
                <li class="rs">Lembah Subang</li>
                <li class="rs">Kelana Jaya</li>
                <li class="rs">SS 15</li>
                <li class="rs">SS 18</li>
                <li class="rs interchange hub">Subang Jaya <em>↔ BRT Sunway</em></li>
                <li class="rs">USJ 7</li>
                <li class="rs">USJ 10</li>
                <li class="rs">USJ 21</li>
                <li class="rs">Taipan</li>
                <li class="rs">Pusat Bandar Puchong</li>
                <li class="rs">IOI Kinrara</li>
                <li class="rs">Awan Besar</li>
                <li class="rs interchange">Bukit Jalil <em>↔ SP</em></li>
                <li class="rs">Muhibbah</li>
                <li class="rs">Alam Sutera</li>
                <li class="rs terminal">Putra Heights <em>↔ SP</em></li>
              </ul>
            </div>
            <p class="route-note">39 stations &nbsp;·&nbsp; Interchange stations shown with ring markers</p>
          </div>
        </div>

        <!-- SRI PETALING LINE -->
        <div class="tmodal__panel" id="panel-sri-petaling" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Sentul Timur → Putra Heights</div>

              <!-- Column 1: Sentul Timur → KL Sentral -->
              <ul class="route-list" style="--lc:#007A3D">
                <li class="rs terminal">Sentul Timur</li>
                <li class="rs">Sentul</li>
                <li class="rs interchange hub">Titiwangsa <em>↔ MRT / Mono</em></li>
                <li class="rs">PWTC</li>
                <li class="rs">Sultan Ismail</li>
                <li class="rs">Bandaraya</li>
                <li class="rs interchange">Masjid Jamek <em>↔ KJ</em></li>
                <li class="rs interchange">Pasar Seni <em>↔ KJ</em></li>
                <li class="rs interchange hub">KL Sentral <em>↔ KJ / KTM / ERL / Mono</em></li>
                <li class="rs">Bangsar</li>
                <li class="rs interchange">Chan Sow Lin <em>↔ AG branch</em></li>
              </ul>

              <!-- Column 2: Salak Selatan → Putra Heights -->
              <ul class="route-list" style="--lc:#007A3D">
                <li class="rs">Salak Selatan</li>
                <li class="rs">Bandar Tun Razak</li>
                <li class="rs interchange">Bandar Tasik Selatan <em>↔ KTM</em></li>
                <li class="rs">Sungai Besi</li>
                <li class="rs">Petaling</li>
                <li class="rs">Sri Petaling</li>
                <li class="rs interchange">Bukit Jalil <em>↔ KJ</em></li>
                <li class="rs">Muhibbah</li>
                <li class="rs">Alam Sutera</li>
                <li class="rs terminal">Putra Heights <em>↔ KJ</em></li>
              </ul>
            </div>
            <p class="route-note">Shares Sentul Timur → Chan Sow Lin section with the Ampang Line</p>
          </div>
        </div>

        <!-- AMPANG LINE -->
        <div class="tmodal__panel" id="panel-ampang" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Sentul Timur → Ampang</div>

              <!-- Column 1: Sentul Timur → Chan Sow Lin (shared with SP) -->
              <ul class="route-list" style="--lc:#9B1E32">
                <li class="rs terminal">Sentul Timur</li>
                <li class="rs">Sentul</li>
                <li class="rs interchange hub">Titiwangsa <em>↔ MRT / Mono</em></li>
                <li class="rs">PWTC</li>
                <li class="rs">Sultan Ismail</li>
                <li class="rs">Bandaraya</li>
                <li class="rs interchange">Masjid Jamek <em>↔ KJ</em></li>
                <li class="rs interchange">Pasar Seni <em>↔ KJ</em></li>
                <li class="rs interchange hub">KL Sentral <em>↔ KJ / KTM / ERL / Mono</em></li>
                <li class="rs">Bangsar</li>
                <li class="rs interchange">Chan Sow Lin <em>↔ SP branch</em></li>
                <li class="rs">Miharja</li>
                <li class="rs">Maluri</li>
                <li class="rs">Cochrane</li>
              </ul>

              <!-- Column 2: Cheras → Ampang -->
              <ul class="route-list" style="--lc:#9B1E32">
                <li class="rs">Cheras</li>
                <li class="rs">Bukit Dinding</li>
                <li class="rs">Pandan Jaya</li>
                <li class="rs">Pandan Indah</li>
                <li class="rs">Pandan Mewah</li>
                <li class="rs">Taman Midah</li>
                <li class="rs">Taman Mutiara</li>
                <li class="rs">Taman Connaught</li>
                <li class="rs">Taman Suntex</li>
                <li class="rs">Sri Raya</li>
                <li class="rs">Bandar Tun Hussein Onn</li>
                <li class="rs">Batu 11 Cheras</li>
                <li class="rs">Bukit Mahkota</li>
                <li class="rs terminal">Ampang</li>
              </ul>
            </div>
            <p class="route-note">Shares Sentul Timur → Chan Sow Lin section with the Sri Petaling Line</p>
          </div>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /LRT Modal -->

  <script>
    (function () {
      var backdrop = document.getElementById('lrtBackdrop');
      var modal    = document.getElementById('lrtModal');
      var closeBtn = document.getElementById('lrtClose');

      /* Open */
      document.querySelectorAll('[data-modal="lrt"]').forEach(function (card) {
        card.addEventListener('click', function (e) {
          e.preventDefault();
          var heroImg = backdrop.querySelector('.tmodal__hero img[data-src]');
          if (heroImg) { heroImg.src = heroImg.dataset.src; heroImg.removeAttribute('data-src'); }
          backdrop.classList.add('is-open');
          backdrop.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
          closeBtn.focus();
        });
      });

      /* Close helpers */
      function closeModal() {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }

      closeBtn.addEventListener('click', closeModal);

      /* Click outside modal box → close */
      backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeModal();
      });

      /* Escape key → close */
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && backdrop.classList.contains('is-open')) closeModal();
      });

      /* Tabs — scoped to #lrtModal */
      var tabs   = document.querySelectorAll('#lrtModal .tmodal__tab');
      var panels = document.querySelectorAll('#lrtModal .tmodal__panel');

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          var target = tab.getAttribute('data-tab');

          tabs.forEach(function (t) {
            t.classList.remove('is-active');
            t.setAttribute('aria-selected', 'false');
          });
          panels.forEach(function (p) { p.classList.remove('is-active'); });

          tab.classList.add('is-active');
          tab.setAttribute('aria-selected', 'true');
          var panel = document.getElementById('panel-' + target);
          if (panel) panel.classList.add('is-active');
        });
      });
    })();
  </script>


  <!-- ── KTM Komuter Modal ──────────────────────────────────────── -->
  <div class="tmodal-backdrop" id="ktmBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="ktmModalTitle" aria-hidden="true">
    <div class="tmodal" id="ktmModal">

      <button class="tmodal__close" id="ktmClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/KTM_Title.jpg" src="" alt="KTM Komuter Kuala Lumpur">
        <h2 class="tmodal__hero-title" id="ktmModalTitle">KTM Komuter</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          KTM Komuter is a suburban rail service running two lines across Greater Kuala Lumpur and
          beyond. It connects KL city centre with surrounding suburbs and towns, making it ideal for
          reaching areas further afield — including Batu Caves, Shah Alam, Klang, Kajang, and Seremban.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> 1-300-88-5862</span>
          <span>
            <a href="https://www.ktmb.com.my/" target="_blank" rel="noopener noreferrer">
              https://www.ktmb.com.my/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Route Map</p>
        <div class="tmodal__tabs" role="tablist">
          <button class="tmodal__tab is-active" role="tab" aria-selected="true"
                  data-tab="ktm-bc">BATU CAVES LINE</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="ktm-tm">TANJUNG MALIM LINE</button>
        </div>

        <div class="route-legend">
          <span class="rl-item"><span class="rl-badge" style="--lc:#CC0000">KG</span> MRT Kajang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5B3FA6">PY</span> MRT Putrajaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#0065BD">KJ</span> LRT Kelana Jaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#007A3D">SP</span> LRT Sri Petaling</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#9B1E32">AG</span> LRT Ampang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">ERL</span> KLIA Express</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#E8720C">Mono</span> KL Monorail</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#D4A000">BRT</span> BRT Sunway</span>
        </div>

        <!-- BATU CAVES LINE -->
        <div class="tmodal__panel is-active" id="panel-ktm-bc" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Batu Caves → Pulau Sebang/Tampin</div>

              <ul class="route-list" style="--lc:#8B1A1A">
                <li class="rs terminal">Batu Caves</li>
                <li class="rs">Taman Wahyu</li>
                <li class="rs interchange">Kampung Batu <em>↔ MRT PY</em></li>
                <li class="rs">Batu Kentonmen</li>
                <li class="rs">Sentul</li>
                <li class="rs interchange">Putra <em>↔ LRT SP/AG</em></li>
                <li class="rs interchange">Bank Negara <em>↔ LRT SP/AG</em></li>
                <li class="rs interchange">Kuala Lumpur <em>↔ LRT KJ / MRT KG</em></li>
                <li class="rs interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / ERL / Mono</em></li>
                <li class="rs">Mid Valley</li>
                <li class="rs">Seputeh</li>
                <li class="rs">Salak Selatan</li>
                <li class="rs interchange">Bandar Tasik Selatan <em>↔ LRT SP</em></li>
              </ul>

              <ul class="route-list" style="--lc:#8B1A1A">
                <li class="rs">Serdang</li>
                <li class="rs interchange">Kajang <em>↔ MRT KG</em></li>
                <li class="rs">Kajang 2</li>
                <li class="rs">UKM</li>
                <li class="rs">Bangi</li>
                <li class="rs">Batang Benar</li>
                <li class="rs">Nilai</li>
                <li class="rs">Labu</li>
                <li class="rs">Tiroi</li>
                <li class="rs">Seremban</li>
                <li class="rs">Senawang</li>
                <li class="rs">Sungai Gadut</li>
                <li class="rs">Rembau</li>
                <li class="rs terminal">Pulau Sebang/Tampin</li>
              </ul>
            </div>
            <p class="route-note">27 stations &nbsp;·&nbsp; Interchange stations shown with ring markers</p>
          </div>
        </div>

        <!-- TANJUNG MALIM LINE -->
        <div class="tmodal__panel" id="panel-ktm-tm" role="tabpanel">
          <div class="route-diagram">
            <div class="route-cols">
              <div class="route-col-dir">Tanjung Malim → Port Klang</div>

              <ul class="route-list" style="--lc:#8B1A1A">
                <li class="rs terminal">Tanjung Malim</li>
                <li class="rs">Kuala Kubu Bharu</li>
                <li class="rs">Rasa</li>
                <li class="rs">Batang Kali</li>
                <li class="rs">Serendah</li>
                <li class="rs">Rawang</li>
                <li class="rs">Kuang</li>
                <li class="rs interchange hub">Sungai Buloh <em>↔ MRT PY</em></li>
                <li class="rs">Kepong Sentral</li>
                <li class="rs">Kepong</li>
                <li class="rs">Segambut Utara</li>
                <li class="rs">Segambut</li>
                <li class="rs interchange">Putra <em>↔ LRT SP/AG</em></li>
                <li class="rs interchange">Bank Negara <em>↔ LRT SP/AG</em></li>
                <li class="rs interchange">Kuala Lumpur <em>↔ LRT KJ / MRT KG</em></li>
                <li class="rs interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / ERL / Mono</em></li>
              </ul>

              <ul class="route-list" style="--lc:#8B1A1A">
                <li class="rs">Abdullah Hukum</li>
                <li class="rs">Angkasapuri</li>
                <li class="rs">Pantai Dalam</li>
                <li class="rs">Petaling</li>
                <li class="rs">Jalan Templer</li>
                <li class="rs">Kampung Dato Harun</li>
                <li class="rs">Seri Setia</li>
                <li class="rs">Setia Jaya</li>
                <li class="rs interchange hub">Subang Jaya <em>↔ LRT KJ / BRT Sunway</em></li>
                <li class="rs">Batu Tiga</li>
                <li class="rs">Shah Alam</li>
                <li class="rs">Padang Jawa</li>
                <li class="rs">Bukit Badak</li>
                <li class="rs">Klang</li>
                <li class="rs">Teluk Pulai</li>
                <li class="rs">Teluk Gadong</li>
                <li class="rs">Kampung Raja Uda</li>
                <li class="rs">Jalan Kastam</li>
                <li class="rs terminal">Port Klang</li>
              </ul>
            </div>
            <p class="route-note">35 stations &nbsp;·&nbsp; Interchange stations shown with ring markers</p>
          </div>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /KTM Modal -->

  <script>
    (function () {
      var backdrop = document.getElementById('ktmBackdrop');
      var closeBtn = document.getElementById('ktmClose');

      document.querySelectorAll('[data-modal="ktm"]').forEach(function (card) {
        card.addEventListener('click', function (e) {
          e.preventDefault();
          var heroImg = backdrop.querySelector('.tmodal__hero img[data-src]');
          if (heroImg) { heroImg.src = heroImg.dataset.src; heroImg.removeAttribute('data-src'); }
          backdrop.classList.add('is-open');
          backdrop.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
          closeBtn.focus();
        });
      });

      function closeModal() {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
      closeBtn.addEventListener('click', closeModal);
      backdrop.addEventListener('click', function (e) { if (e.target === backdrop) closeModal(); });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && backdrop.classList.contains('is-open')) closeModal();
      });

      document.querySelectorAll('#ktmModal .tmodal__tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
          document.querySelectorAll('#ktmModal .tmodal__tab').forEach(function (t) {
            t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false');
          });
          document.querySelectorAll('#ktmModal .tmodal__panel').forEach(function (p) {
            p.classList.remove('is-active');
          });
          tab.classList.add('is-active');
          tab.setAttribute('aria-selected', 'true');
          var panel = document.getElementById('panel-' + tab.getAttribute('data-tab'));
          if (panel) panel.classList.add('is-active');
        });
      });
    })();
  </script>

  <!-- ── KL Monorail Modal ──────────────────────────────────────── -->
  <div class="tmodal-backdrop" id="monorailBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="monorailModalTitle" aria-hidden="true">
    <div class="tmodal" id="monorailModal">

      <button class="tmodal__close" id="monorailClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/Monorail_Title.jpg" src="" alt="KL Monorail Kuala Lumpur">
        <h2 class="tmodal__hero-title" id="monorailModalTitle">KL Monorail</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          The KL Monorail is a single elevated line connecting KL Sentral to Titiwangsa, passing
          through the city's prime shopping and entertainment belt. It is the quickest way to reach
          Bukit Bintang, KLCC, and Brickfields from KL Sentral.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> 03-7885 2585</span>
          <span>
            <a href="https://myrapid.com.my/bus-train/rapid-kl/monorail/" target="_blank" rel="noopener noreferrer">
              https://myrapid.com.my/bus-train/rapid-kl/monorail/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Route Map</p>

        <div class="route-legend">
          <span class="rl-item"><span class="rl-badge" style="--lc:#CC0000">KG</span> MRT Kajang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5B3FA6">PY</span> MRT Putrajaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#0065BD">KJ</span> LRT Kelana Jaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#007A3D">SP</span> LRT Sri Petaling</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#9B1E32">AG</span> LRT Ampang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5C3A1E">KTM</span> KTM Komuter</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">ERL</span> KLIA Express</span>
        </div>

        <div class="route-diagram">
          <div class="route-col-dir">KL Sentral → Titiwangsa</div>
          <ul class="route-list" style="--lc:#E8720C; max-width:300px; margin:0 auto;">
            <li class="rs terminal interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / KTM / ERL</em></li>
            <li class="rs">Tun Sambanthan</li>
            <li class="rs">Maharajalela</li>
            <li class="rs interchange">Hang Tuah <em>↔ LRT SP/AG</em></li>
            <li class="rs">Imbi</li>
            <li class="rs interchange">Bukit Bintang <em>↔ MRT KG</em></li>
            <li class="rs">Raja Chulan</li>
            <li class="rs">Bukit Nanas</li>
            <li class="rs">Medan Tuanku</li>
            <li class="rs">Chow Kit</li>
            <li class="rs terminal interchange hub">Titiwangsa <em>↔ LRT SP/AG / MRT PY</em></li>
          </ul>
          <p class="route-note">11 stations &nbsp;·&nbsp; Interchange stations shown with ring markers</p>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /Monorail Modal -->

  <script>
    (function () {
      var backdrop = document.getElementById('monorailBackdrop');
      var closeBtn = document.getElementById('monorailClose');

      document.querySelectorAll('[data-modal="monorail"]').forEach(function (card) {
        card.addEventListener('click', function (e) {
          e.preventDefault();
          var heroImg = backdrop.querySelector('.tmodal__hero img[data-src]');
          if (heroImg) { heroImg.src = heroImg.dataset.src; heroImg.removeAttribute('data-src'); }
          backdrop.classList.add('is-open');
          backdrop.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
          closeBtn.focus();
        });
      });

      function closeModal() {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
      closeBtn.addEventListener('click', closeModal);
      backdrop.addEventListener('click', function (e) { if (e.target === backdrop) closeModal(); });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && backdrop.classList.contains('is-open')) closeModal();
      });
    })();
  </script>


  <!-- ── KLIA Express Modal ──────────────────────────────────── -->
  <div class="tmodal-backdrop" id="kliaBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="kliaModalTitle" aria-hidden="true">
    <div class="tmodal" id="kliaModal">

      <button class="tmodal__close" id="kliaClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/KLIA_Exp_Title.png" src="" alt="KLIA Express airport rail link">
        <h2 class="tmodal__hero-title" id="kliaModalTitle">KLIA Express</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          KLIA Express is a premium non-stop airport rail link connecting KL Sentral
          directly to Kuala Lumpur International Airport (KLIA Terminals 1 &amp; 2) in
          about 33 minutes. It is the fastest way to travel between the city centre
          and the airport, running every 15–20 minutes throughout the day.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> +603-2267 8000</span>
          <span>
            <a href="https://www.kliaekspres.com/" target="_blank" rel="noopener noreferrer">
              https://www.kliaekspres.com/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Route Map</p>

        <div class="route-legend">
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">ERL</span> KLIA Express</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#0065BD">KJ</span> LRT Kelana Jaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#007A3D">SP</span> LRT Sri Petaling</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#9B1E32">AG</span> LRT Ampang</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5C3A1E">KTM</span> KTM Komuter</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#E8720C">Mono</span> KL Monorail</span>
        </div>

        <div class="route-diagram">
          <div class="route-col-dir">KL Sentral → KLIA Terminal 2</div>
          <ul class="route-list" style="--lc:#C8102E; max-width:320px; margin:0 auto;">
            <li class="rs terminal interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / KTM / Mono</em></li>
            <li class="rs">KLIA Terminal 1</li>
            <li class="rs terminal">KLIA Terminal 2</li>
          </ul>
          <p class="route-note">3 stations &nbsp;·&nbsp; ~33 min one-way journey</p>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /KLIA Express Modal -->


  <!-- ── GOKL Bus Modal ──────────────────────────────────────── -->
  <div class="tmodal-backdrop" id="goklBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="goklModalTitle" aria-hidden="true">
    <div class="tmodal" id="goklModal">

      <button class="tmodal__close" id="goklClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/GOKL_Title.jpg" src="" alt="GoKL free city circulator bus">
        <h2 class="tmodal__hero-title" id="goklModalTitle">GoKL City Bus</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          GoKL is a free city circulator bus service running across central Kuala Lumpur.
          It operates four colour-coded loops — Green, Purple, Red, and Blue — connecting
          major shopping, tourism, and transit hubs. Completely free for everyone,
          locals and visitors alike.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> 03-7885 2585</span>
          <span>
            <a href="https://myrapid.com.my/bus-train/rapid-kl/gokl-city-bus/" target="_blank" rel="noopener noreferrer">
              https://myrapid.com.my/bus-train/rapid-kl/gokl-city-bus/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Route Map</p>
        <div class="tmodal__tabs" role="tablist">
          <button class="tmodal__tab is-active" role="tab" aria-selected="true"
                  data-tab="gokl-green">GREEN</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="gokl-purple">PURPLE</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="gokl-red">RED</button>
          <button class="tmodal__tab" role="tab" aria-selected="false"
                  data-tab="gokl-blue">BLUE</button>
        </div>

        <!-- GREEN LINE -->
        <div class="tmodal__panel is-active" id="panel-gokl-green" role="tabpanel">
          <div class="route-diagram">
            <div class="route-col-dir">Bukit Bintang ↔ KL Sentral loop</div>
            <ul class="route-list" style="--lc:#00843D; max-width:340px; margin:0 auto;">
              <li class="rs terminal">Bukit Bintang</li>
              <li class="rs">Pavilion KL</li>
              <li class="rs">Sungei Wang Plaza</li>
              <li class="rs">Berjaya Times Square</li>
              <li class="rs">Maharajalela</li>
              <li class="rs">Petaling Street (Chinatown)</li>
              <li class="rs">Central Market</li>
              <li class="rs interchange">Pasar Seni <em>↔ LRT KJ / MRT KG</em></li>
              <li class="rs">Brickfields</li>
              <li class="rs terminal interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / KTM / ERL / Mono</em></li>
            </ul>
            <p class="route-note">Free service · Major stops shown · Check official site for full schedule</p>
          </div>
        </div>

        <!-- PURPLE LINE -->
        <div class="tmodal__panel" id="panel-gokl-purple" role="tabpanel">
          <div class="route-diagram">
            <div class="route-col-dir">KL Sentral ↔ KLCC loop</div>
            <ul class="route-list" style="--lc:#5B3FA6; max-width:340px; margin:0 auto;">
              <li class="rs terminal interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / KTM / ERL / Mono</em></li>
              <li class="rs">Brickfields</li>
              <li class="rs">National Mosque (Masjid Negara)</li>
              <li class="rs">Merdeka Square (Dataran Merdeka)</li>
              <li class="rs interchange">Masjid Jamek <em>↔ LRT KJ/SP/AG</em></li>
              <li class="rs">Sogo</li>
              <li class="rs">Maju Junction</li>
              <li class="rs">Bukit Nanas</li>
              <li class="rs terminal interchange">KLCC <em>↔ LRT KJ</em></li>
            </ul>
            <p class="route-note">Free service · Major stops shown · Check official site for full schedule</p>
          </div>
        </div>

        <!-- RED LINE -->
        <div class="tmodal__panel" id="panel-gokl-red" role="tabpanel">
          <div class="route-diagram">
            <div class="route-col-dir">Pasar Seni ↔ Titiwangsa loop</div>
            <ul class="route-list" style="--lc:#CC0000; max-width:340px; margin:0 auto;">
              <li class="rs terminal interchange">Pasar Seni <em>↔ LRT KJ / MRT KG</em></li>
              <li class="rs">Central Market</li>
              <li class="rs interchange">Masjid Jamek <em>↔ LRT KJ/SP/AG</em></li>
              <li class="rs">Sogo</li>
              <li class="rs">Maju Junction</li>
              <li class="rs">PWTC</li>
              <li class="rs">Chow Kit</li>
              <li class="rs">Medan Tuanku</li>
              <li class="rs terminal interchange hub">Titiwangsa <em>↔ LRT SP/AG / MRT PY / Mono</em></li>
            </ul>
            <p class="route-note">Free service · Major stops shown · Check official site for full schedule</p>
          </div>
        </div>

        <!-- BLUE LINE -->
        <div class="tmodal__panel" id="panel-gokl-blue" role="tabpanel">
          <div class="route-diagram">
            <div class="route-col-dir">Bukit Bintang ↔ KLCC loop</div>
            <ul class="route-list" style="--lc:#0065BD; max-width:340px; margin:0 auto;">
              <li class="rs terminal">Bukit Bintang</li>
              <li class="rs">Pavilion KL</li>
              <li class="rs">Raja Chulan</li>
              <li class="rs">Sogo</li>
              <li class="rs">Maju Junction</li>
              <li class="rs">KL Convention Centre</li>
              <li class="rs">Aquaria KLCC</li>
              <li class="rs terminal interchange">KLCC <em>↔ LRT KJ</em></li>
            </ul>
            <p class="route-note">Free service · Major stops shown · Check official site for full schedule</p>
          </div>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /GOKL Bus Modal -->


  <!-- ── KL Hop on Hop off Bus Modal ─────────────────────────── -->
  <div class="tmodal-backdrop" id="hoponBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="hoponModalTitle" aria-hidden="true">
    <div class="tmodal" id="hoponModal">

      <button class="tmodal__close" id="hoponClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/opt/KLHopOn_Logo.jpg" src="" alt="KL Hop on Hop off Bus">
        <h2 class="tmodal__hero-title" id="hoponModalTitle">KL Hop on Hop off Bus</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          The KL Hop on Hop off Bus is a tourist-friendly double-decker service running
          a circular route around Kuala Lumpur's most popular attractions. Buy a single
          ticket and ride all day — hop off at any of the stops to explore, then hop back
          on the next bus. Audio commentary in 8 languages on board.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> +603-2691 1382</span>
          <span>
            <a href="https://www.myhoponhopoff.com/" target="_blank" rel="noopener noreferrer">
              https://www.myhoponhopoff.com/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Route Map</p>

        <div class="route-legend">
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">HOHO</span> Hop on Hop off</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#0065BD">KJ</span> LRT Kelana Jaya</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#5C3A1E">KTM</span> KTM Komuter</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#E8720C">Mono</span> KL Monorail</span>
          <span class="rl-item"><span class="rl-badge" style="--lc:#C8102E">ERL</span> KLIA Express</span>
        </div>

        <div class="route-diagram">
          <div class="route-col-dir">Circular tourist loop · ~27 stops · 2.5 hrs full loop</div>
          <div class="route-cols">

            <!-- Column 1: stops 1–14 -->
            <ul class="route-list" style="--lc:#C8102E">
              <li class="rs terminal">MaTiC (Malaysia Tourism Centre)</li>
              <li class="rs">KL Tower</li>
              <li class="rs">Aquaria KLCC</li>
              <li class="rs interchange hub">KLCC / Petronas Twin Towers <em>↔ LRT KJ</em></li>
              <li class="rs">Karyaneka (Craft Complex)</li>
              <li class="rs">Heritage Hotel</li>
              <li class="rs">Bukit Bintang</li>
              <li class="rs">Pavilion KL</li>
              <li class="rs">Sungei Wang Plaza</li>
              <li class="rs">Berjaya Times Square</li>
              <li class="rs">Petaling Street (Chinatown)</li>
              <li class="rs">Central Market</li>
              <li class="rs">Merdeka Square</li>
              <li class="rs">KL Old Railway Station</li>
            </ul>

            <!-- Column 2: stops 15–27 -->
            <ul class="route-list" style="--lc:#C8102E">
              <li class="rs">National Mosque (Masjid Negara)</li>
              <li class="rs">KL Bird Park / Lake Gardens</li>
              <li class="rs">National Museum (Muzium Negara)</li>
              <li class="rs">National Palace (Istana Negara)</li>
              <li class="rs interchange hub">KL Sentral <em>↔ LRT KJ/SP/AG / KTM / ERL / Mono</em></li>
              <li class="rs">Brickfields (Little India)</li>
              <li class="rs interchange">Maharajalela <em>↔ Mono</em></li>
              <li class="rs interchange">Hang Tuah <em>↔ Mono / LRT SP/AG</em></li>
              <li class="rs">Concorde Hotel / Bintang Walk</li>
              <li class="rs">Sogo</li>
              <li class="rs">Chow Kit</li>
              <li class="rs">PWTC</li>
              <li class="rs terminal interchange hub">Titiwangsa <em>↔ Mono / LRT SP/AG / MRT PY</em></li>
            </ul>

          </div>
          <p class="route-note">Buy a 24h or 48h ticket · Hop on/off freely at any stop · Stops may change seasonally</p>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /Hop on Hop off Modal -->


  <!-- ── MRT Bus Modal (info + curated routes) ───────────────── -->
  <div class="tmodal-backdrop" id="mrtbusBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="mrtbusModalTitle" aria-hidden="true">
    <div class="tmodal" id="mrtbusModal">

      <button class="tmodal__close" id="mrtbusClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/MRTBus_Title.jpg" src="" alt="MRT Feeder Bus">
        <h2 class="tmodal__hero-title" id="mrtbusModalTitle">MRT Feeder Bus</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          MRT Feeder Buses (T-routes) are short-distance connector buses that link MRT
          stations to nearby residential areas, malls, and business districts. They're
          designed for "first &amp; last mile" connectivity — most useful if you're staying
          in a suburb and need to reach the nearest MRT station. Pay with Touch 'n Go or
          MyRapid card; rides are typically RM1.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> 03-7885 2585</span>
          <span>
            <a href="https://myrapid.com.my/bus-train/rapid-kl/mrt-feeder/" target="_blank" rel="noopener noreferrer">
              https://myrapid.com.my/bus-train/rapid-kl/mrt-feeder/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Useful Routes for Visitors</p>

        <div class="useful-routes">
          <div class="useful-route">
            <span class="useful-route__number">T100</span>
            <span class="useful-route__text">
              <span class="useful-route__name">Pasar Seni MRT ↔ Chinatown loop</span>
              <span class="useful-route__desc">Connects Pasar Seni MRT station with Petaling Street and the Central Market tourist area.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">T200</span>
            <span class="useful-route__text">
              <span class="useful-route__name">Bukit Bintang MRT ↔ Shopping belt</span>
              <span class="useful-route__desc">Loops around the Bukit Bintang shopping district — useful for reaching Pavilion, Lot 10, and Fahrenheit 88.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">T407</span>
            <span class="useful-route__text">
              <span class="useful-route__name">TTDI MRT ↔ TTDI town centre</span>
              <span class="useful-route__desc">Convenient feeder if you're staying in the Taman Tun Dr Ismail area.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">T543</span>
            <span class="useful-route__text">
              <span class="useful-route__name">Maluri MRT ↔ Sunway Velocity</span>
              <span class="useful-route__desc">Direct connection to Sunway Velocity Mall and IKEA Cheras from Maluri MRT station.</span>
            </span>
          </div>
        </div>

        <p class="route-note" style="text-align:center;">Route numbers shown are illustrative — confirm current routes with the official Rapid KL journey planner.</p>

        <div class="tmodal__planner-wrap">
          <a class="tmodal__planner-btn" href="https://myrapid.com.my/journey-planner/" target="_blank" rel="noopener noreferrer">
            Find Your Route →
          </a>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /MRT Bus Modal -->


  <!-- ── Rapid KL Bus Modal (info + curated routes) ──────────── -->
  <div class="tmodal-backdrop" id="rapidklBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="rapidklModalTitle" aria-hidden="true">
    <div class="tmodal" id="rapidklModal">

      <button class="tmodal__close" id="rapidklClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero">
        <img data-src="asset-backups/RapidKL_Title.jpg" src="" alt="Rapid KL Bus">
        <h2 class="tmodal__hero-title" id="rapidklModalTitle">Rapid KL Bus</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          Rapid KL operates the largest bus network across Greater Kuala Lumpur, with
          over 170 routes spanning city centre, trunk, and local services. Buses are
          air-conditioned, frequent, and accept Touch 'n Go and MyRapid cards. Most
          single rides cost between RM1–RM3, with unlimited-travel My50 monthly passes
          also available.
        </p>
        <div class="tmodal__meta">
          <span><strong>Helpline:</strong> 03-7885 2585</span>
          <span>
            <a href="https://myrapid.com.my/bus-train/rapid-kl/bus/" target="_blank" rel="noopener noreferrer">
              https://myrapid.com.my/bus-train/rapid-kl/bus/
            </a>
          </span>
        </div>

        <p class="tmodal__route-heading">Useful Routes for Visitors</p>

        <div class="useful-routes">
          <div class="useful-route">
            <span class="useful-route__number">851</span>
            <span class="useful-route__text">
              <span class="useful-route__name">KLCC ↔ KL Sentral</span>
              <span class="useful-route__desc">One of the most useful routes for tourists — links KLCC and Bukit Bintang area to KL Sentral.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">821</span>
            <span class="useful-route__text">
              <span class="useful-route__name">KL Sentral ↔ Sunway Pyramid</span>
              <span class="useful-route__desc">Direct service to Sunway Pyramid mall and Sunway Lagoon theme park.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">802</span>
            <span class="useful-route__text">
              <span class="useful-route__name">KL Sentral ↔ Subang Jaya</span>
              <span class="useful-route__desc">Connects KL Sentral with the Subang Jaya area, including SS15.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">B114</span>
            <span class="useful-route__text">
              <span class="useful-route__name">Brickfields ↔ Bukit Bintang</span>
              <span class="useful-route__desc">Useful city shuttle route linking Little India / KL Sentral with the Bukit Bintang shopping belt.</span>
            </span>
          </div>
          <div class="useful-route">
            <span class="useful-route__number">300</span>
            <span class="useful-route__text">
              <span class="useful-route__name">Lebuh Pudu ↔ Ampang</span>
              <span class="useful-route__desc">Heritage core to Ampang Point — convenient if you're visiting the embassy district.</span>
            </span>
          </div>
        </div>

        <p class="route-note" style="text-align:center;">Route numbers shown are illustrative — confirm current routes with the official Rapid KL journey planner.</p>

        <div class="tmodal__planner-wrap">
          <a class="tmodal__planner-btn" href="https://myrapid.com.my/journey-planner/" target="_blank" rel="noopener noreferrer">
            Find Your Route →
          </a>
        </div>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /Rapid KL Bus Modal -->


  <!-- ── Grab Modal (app-based service pattern) ───────────────── -->
  <div class="tmodal-backdrop" id="grabBackdrop" role="dialog" aria-modal="true"
       aria-labelledby="grabModalTitle" aria-hidden="true">
    <div class="tmodal" id="grabModal">

      <button class="tmodal__close" id="grabClose" aria-label="Close modal">&times;</button>

      <div class="tmodal__inner">
      <div class="tmodal__hero tmodal__hero--ehailing">
        <img data-src="asset-backups/Grab.jpg" src="" alt="E-hailing services in Kuala Lumpur">
        <h2 class="tmodal__hero-title" id="grabModalTitle">E-Hailing Services</h2>
      </div>

      <div class="tmodal__body">
        <p class="tmodal__desc">
          E-hailing (ride-hailing) is the easiest and most popular way to get around Kuala Lumpur.
          Instead of flagging down a taxi, you book a car through a smartphone app that shows the
          fare upfront, tracks your driver in real time, and lets you pay by card or cash. Several
          apps operate across KL and the Klang Valley — Grab is the biggest, but the others are
          often cheaper or let you set your own fare.
        </p>

        <p class="tmodal__route-heading">How E-Hailing Works</p>
        <div class="howto-steps">
          <div class="howto-step">
            <span class="howto-step__num">1</span>
            <span class="howto-step__text">
              <strong>Download an e-hailing app</strong> from the App Store or Google Play and sign up.
              An overseas SIM or phone number works fine.
            </span>
          </div>
          <div class="howto-step">
            <span class="howto-step__num">2</span>
            <span class="howto-step__text">
              <strong>Set your pickup point and destination.</strong> The app shows the exact fare
              upfront — no haggling or surprise charges.
            </span>
          </div>
          <div class="howto-step">
            <span class="howto-step__num">3</span>
            <span class="howto-step__text">
              <strong>Choose a ride type and confirm.</strong> You'll see the driver's name, plate
              number, vehicle, and estimated arrival time.
            </span>
          </div>
          <div class="howto-step">
            <span class="howto-step__num">4</span>
            <span class="howto-step__text">
              <strong>Pay digitally or in cash.</strong> Add a credit/debit card, top up the in-app
              wallet, or simply pay the driver in cash at the end of the trip.
            </span>
          </div>
        </div>

        <p class="tmodal__route-heading">E-Hailing Apps in KL</p>
        <div class="ride-types">
          <div class="ride-type" style="border-top-color:#00B14F">
            <span class="ride-type__name">Grab</span>
            <span class="ride-type__when">Southeast Asia's largest ride-hailing app — the most drivers and shortest waits, plus food and parcel delivery. The default choice for most visitors.</span>
            <a class="ride-type__link" href="https://www.grab.com/my/" target="_blank" rel="noopener noreferrer">grab.com/my →</a>
          </div>
          <div class="ride-type" style="border-top-color:#FF0000">
            <span class="ride-type__name">AirAsia Ride (airasia MOVE)</span>
            <span class="ride-type__when">Run by AirAsia and often cheaper than Grab. Bundles flights, hotels, and rides in a single app — handy for airport trips.</span>
            <a class="ride-type__link" href="https://ride.airasia.com/" target="_blank" rel="noopener noreferrer">ride.airasia.com →</a>
          </div>
          <div class="ride-type" style="border-top-color:#A0D000">
            <span class="ride-type__name">InDrive</span>
            <span class="ride-type__when">You name your fare and nearby drivers accept or counter-offer. Great for negotiating and frequently the cheapest option.</span>
            <a class="ride-type__link" href="https://indrive.com/" target="_blank" rel="noopener noreferrer">indrive.com →</a>
          </div>
          <div class="ride-type" style="border-top-color:#FDB913">
            <span class="ride-type__name">Maxim</span>
            <span class="ride-type__when">Budget-friendly app popular for short trips and motorbike rides (Maxim Bike). Lower fares with fast-growing coverage across the Klang Valley.</span>
            <a class="ride-type__link" href="https://taximaxim.com/" target="_blank" rel="noopener noreferrer">taximaxim.com →</a>
          </div>
          <div class="ride-type" style="border-top-color:#34D186">
            <span class="ride-type__name">Bolt</span>
            <span class="ride-type__when">European ride-hailing app with competitive pricing and frequent promo codes. A solid backup when Grab fares are surging.</span>
            <a class="ride-type__link" href="https://bolt.eu/" target="_blank" rel="noopener noreferrer">bolt.eu →</a>
          </div>
        </div>

        <p class="grab-alternatives">
          <strong>Tip:</strong> Install at least two apps before you travel. Fares vary between them,
          and having a backup helps you find a ride faster during rain, rush hour, or peak demand.
        </p>
      </div>

      </div><!-- /.tmodal__inner -->
    </div>
  </div><!-- /Grab Modal -->


  <!-- ── Generic Modal Init for the 6 new transports ─────────── -->
  <script>
    (function () {
      function initTransportModal(id) {
        var backdrop = document.getElementById(id + 'Backdrop');
        var modalEl  = document.getElementById(id + 'Modal');
        var closeBtn = document.getElementById(id + 'Close');
        if (!backdrop || !modalEl || !closeBtn) return;

        /* Open */
        document.querySelectorAll('[data-modal="' + id + '"]').forEach(function (card) {
          card.addEventListener('click', function (e) {
            e.preventDefault();
            var heroImg = backdrop.querySelector('.tmodal__hero img[data-src]');
            if (heroImg) { heroImg.src = heroImg.dataset.src; heroImg.removeAttribute('data-src'); }
            backdrop.classList.add('is-open');
            backdrop.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
          });
        });

        /* Close */
        function closeModal() {
          backdrop.classList.remove('is-open');
          backdrop.setAttribute('aria-hidden', 'true');
          document.body.style.overflow = '';
        }
        closeBtn.addEventListener('click', closeModal);
        backdrop.addEventListener('click', function (e) { if (e.target === backdrop) closeModal(); });
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && backdrop.classList.contains('is-open')) closeModal();
        });

        /* Tabs — scoped to this modal */
        var tabs   = modalEl.querySelectorAll('.tmodal__tab');
        var panels = modalEl.querySelectorAll('.tmodal__panel');
        tabs.forEach(function (tab) {
          tab.addEventListener('click', function () {
            tabs.forEach(function (t) {
              t.classList.remove('is-active');
              t.setAttribute('aria-selected', 'false');
            });
            panels.forEach(function (p) { p.classList.remove('is-active'); });
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');
            var panel = document.getElementById('panel-' + tab.getAttribute('data-tab'));
            if (panel) panel.classList.add('is-active');
          });
        });
      }

      ['klia', 'gokl', 'hopon', 'mrtbus', 'rapidkl', 'grab'].forEach(initTransportModal);
    })();
  </script>

</body>
</html>
