<?php
/* ============================================================================
 *                             travel-tips.php
 *
 *   Public page — travel tips.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('admin/functions.php');
$query = "SELECT tile1_title1, tile1_title2, tile1_title3 FROM indexpage";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_assoc($result);
$tile1_title1 = $row['tile1_title1'];
$tile1_title2 = $row['tile1_title2'];
$tile1_title3 = $row['tile1_title3'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Travel Tips</title>

  <link rel="canonical" href="https://www.kltheguide.com.my/travel-tips.php" />
  <meta name="description" content="Essential travel tips for visiting Kuala Lumpur, Malaysia. Communication, safety, finance, packing, and logistics advice for tourists.">
  <meta name="keywords" content="KL travel tips, Kuala Lumpur tips, Malaysia travel advice, KL tourist guide, KL safety, KL currency, KL SIM card, KL accommodation">

  <!-- Open Graph -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/travel-tips.php" />
  <meta property="og:title" content="KL The Guide - Travel Tips" />
  <meta property="og:description" content="Essential travel tips for visiting Kuala Lumpur, Malaysia." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/travel-tips.php" />
  <meta property="twitter:title" content="KL The Guide - Travel Tips" />
  <meta property="twitter:description" content="Essential travel tips for visiting Kuala Lumpur, Malaysia." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>

  <style>
    /* ===== Tabs Navigation Bar ===== */
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
      transform: translateY(-8px) scale(1.02);
      /* bright colour lining (inset ring) + glow so the active page pops */
      box-shadow: 0 14px 38px rgba(0,0,0,.6),
                  inset 0 0 0 3px #19c7e6,
                  0 0 22px rgba(25,199,230,.55);
      z-index: 3;
    }

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

    @media (max-width: 768px) {
      .klag-tab-bar { height: 90px; }
      .klag-tab__label { font-size: 12px; }
    }

    @media (max-width: 480px) {
      .klag-tab-bar { height: 76px; }
      .klag-tab__label { font-size: 10.5px; letter-spacing: 0; }
    }

    /* ===== Travel Tips FAQ ===== */
    #tt-section {
      position: relative;
      min-height: 100vh;
      padding: 200px 0 70px;
      overflow: hidden;
    }

    @media (max-width: 768px) {
      #tt-section { padding-top: 170px; }
    }

    @media (max-width: 480px) {
      #tt-section { padding-top: 156px; }
    }

    #tt-section .tt-bg {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 72% 28%, #2f8fe6 0%, #1f6cc4 45%, #164f9c 100%);
      z-index: 0;
    }

    #tt-section .tt-overlay {
      position: absolute;
      inset: 0;
      background: transparent;
      z-index: 1;
    }

    .tt-wrap {
      position: relative;
      z-index: 2;
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .tt-heading {
      text-align: center;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 2.4rem;
      font-weight: 700;
      margin-bottom: 42px;
      letter-spacing: 0.5px;
    }

    .tt-layout {
      display: flex;
      gap: 24px;
      align-items: flex-start;
    }

    /* ===== Left: Section Tabs ===== */
    .tt-tabs {
      flex: 0 0 265px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .tt-tab {
      background: rgba(10, 26, 52, 0.55);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(120, 190, 255, 0.18);
      color: rgba(255, 255, 255, 0.8);
      padding: 14px 18px;
      border-radius: 10px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      line-height: 1.4;
      text-align: center;
      transition: background 0.25s, color 0.25s, border-color 0.25s, box-shadow 0.25s;
      outline: none;
      /* Stack the section label on top, name underneath — uniform height so
         the tabs line up as a clean column */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 5px;
      min-height: 92px;
    }

    .tt-tab__icon {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1px;
    }

    .tt-tab__icon svg {
      width: 22px;
      height: 22px;
      fill: none;
      stroke: rgba(255, 255, 255, 0.7);
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
      transition: stroke 0.25s;
    }

    .tt-tab:hover .tt-tab__icon svg { stroke: #fff; }
    .tt-tab.active .tt-tab__icon svg { stroke: #fff; }

    .tt-tab__label {
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      opacity: 0.65;
    }

    .tt-tab__name {
      font-size: 0.9rem;
      font-weight: 600;
    }

    .tt-tab.active .tt-tab__label { opacity: 0.85; }

    .tt-tab:hover {
      background: rgba(14, 34, 66, 0.72);
      color: #fff;
      border-color: rgba(25, 199, 230, 0.4);
    }

    .tt-tab.active {
      background: linear-gradient(135deg, #0ea2bd 0%, #0a6f81 100%);
      border-color: #19c7e6;
      color: #fff;
      font-weight: 600;
      box-shadow: 0 6px 22px rgba(14, 162, 189, 0.5);
    }

    /* ===== Right: Accordion Panel ===== */
    .tt-panel {
      flex: 1;
      background: linear-gradient(135deg, rgba(12, 30, 58, 0.55) 0%, rgba(8, 20, 42, 0.62) 100%);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(120, 190, 255, 0.18);
      border-radius: 14px;
      padding: 30px 36px;
      min-height: 440px;
    }

    .tt-content {
      display: none;
      animation: ttFade 0.3s ease;
    }

    .tt-content.active {
      display: block;
    }

    @keyframes ttFade {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ===== Accordion Items ===== */
    .tt-acc {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tt-acc:last-child {
      border-bottom: none;
    }

    .tt-acc-hd {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
      cursor: pointer;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      user-select: none;
      transition: color 0.2s;
    }

    .tt-acc-hd:hover {
      color: #4fd6ea;
    }

    .tt-acc-icon {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      border: 1.5px solid rgba(255, 255, 255, 0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-left: 14px;
      transition: transform 0.35s ease, border-color 0.25s;
    }

    .tt-acc-icon svg {
      width: 12px;
      height: 12px;
      fill: none;
      stroke: rgba(255, 255, 255, 0.7);
      stroke-width: 2.5;
      stroke-linecap: round;
      stroke-linejoin: round;
      transition: stroke 0.2s;
    }

    .tt-acc.open .tt-acc-icon {
      transform: rotate(180deg);
      border-color: #19c7e6;
    }

    .tt-acc.open .tt-acc-icon svg {
      stroke: #4fd6ea;
    }

    .tt-acc-bd {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .tt-acc.open .tt-acc-bd {
      max-height: 900px;
    }

    .tt-acc-inner {
      padding-bottom: 24px;
    }

    .tt-q {
      color: rgba(255, 255, 255, 0.92);
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      font-weight: 500;
      font-style: italic;
      margin-bottom: 12px;
    }

    .tt-a {
      color: rgba(255, 255, 255, 0.72);
      font-family: 'Open Sans', sans-serif;
      font-size: 0.875rem;
      line-height: 1.82;
      margin-bottom: 16px;
    }

    .tt-cta-row {
      display: flex;
      justify-content: flex-end;
    }

    .tt-cta {
      background: rgba(255, 255, 255, 0.92);
      color: #123a6b;
      padding: 8px 20px;
      border-radius: 6px;
      font-family: 'Poppins', sans-serif;
      font-size: 0.8rem;
      font-weight: 700;
      border: none;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: background 0.2s, transform 0.15s;
      display: inline-block;
      text-decoration: none;
    }

    a.tt-cta:hover { color: #123a6b; text-decoration: none; }

    .tt-legal-list {
      color: rgba(255, 255, 255, 0.78);
      font-family: 'Open Sans', sans-serif;
      font-size: 0.875rem;
      line-height: 1.75;
      margin: 0 0 16px 0;
      padding-left: 18px;
    }
    .tt-legal-list li { margin-bottom: 8px; }
    .tt-legal-list strong { color: #4fd6ea; font-weight: 600; }
    .tt-legal-sub {
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      margin: 14px 0 10px;
    }

    .tt-cta:hover {
      background: #fff;
      transform: translateY(-1px);
    }

    .tt-cta:disabled {
      opacity: 0.65;
      cursor: wait;
      transform: none;
    }

    /* ===== Responsive ===== */
    @media (max-width: 900px) {
      .tt-layout {
        flex-direction: column;
      }

      .tt-tabs {
        flex: none;
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
      }

      .tt-tab {
        flex: 1 1 calc(50% - 4px);
        font-size: 0.8rem;
        padding: 12px 10px;
      }

      .tt-panel {
        padding: 24px 20px;
        width: 100%;
      }

      .tt-heading {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .tt-tab {
        flex: 1 1 100%;
      }

      .tt-heading {
        font-size: 1.65rem;
      }
    }
  </style>

</head>

<body class="has-video-hero">

  <?php include 'nav.php'; ?>

  <!-- ── Thumbnail tab bar ──────────────────────────────────── -->
  <div class="klag-tab-bar" role="navigation" aria-label="KL Highlights sections">

    <a class="klag-tab" href="kl-glance.php">
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

    <a class="klag-tab is-active" href="travel-tips.php" aria-current="page">
      <div class="klag-tab__bg" style="background-image:url('asset-backups/traveltips.jpg')"></div>
      <span class="klag-tab__label">
        <?php echo htmlspecialchars(urldecode($tile1_title3), ENT_QUOTES); ?>
      </span>
    </a>

  </div>

  <main id="travel-tips-page">

    <!-- Travel Tips FAQ -->
    <section id="tt-section">
      <div class="tt-bg"></div>
      <div class="tt-overlay"></div>

      <div class="tt-wrap">
        <h2 class="tt-heading">Travel Tips</h2>

        <div class="tt-layout">

          <!-- LEFT: Section Tabs -->
          <nav class="tt-tabs" aria-label="Travel tip sections">
            <button class="tt-tab active" data-target="tt-a">
              <span class="tt-tab__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
              </span>
              <span class="tt-tab__label">Section A</span>
              <span class="tt-tab__name">Communication &amp; Connectivity</span>
            </button>
            <button class="tt-tab" data-target="tt-b">
              <span class="tt-tab__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
              </span>
              <span class="tt-tab__label">Section B</span>
              <span class="tt-tab__name">Essential Information</span>
            </button>
            <button class="tt-tab" data-target="tt-c">
              <span class="tt-tab__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              </span>
              <span class="tt-tab__label">Section C</span>
              <span class="tt-tab__name">Finance &amp; Documents</span>
            </button>
            <button class="tt-tab" data-target="tt-d">
              <span class="tt-tab__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
              </span>
              <span class="tt-tab__label">Section D</span>
              <span class="tt-tab__name">Packing &amp; Gear</span>
            </button>
            <button class="tt-tab" data-target="tt-e">
              <span class="tt-tab__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
              </span>
              <span class="tt-tab__label">Section E</span>
              <span class="tt-tab__name">Logistics</span>
            </button>
          </nav>

          <!-- RIGHT: Accordion Panel -->
          <!-- Items are CMS-managed (traveltips table, admin/edit-traveltips.php).
               The 5 sections a–e and their icons stay fixed in the tab bar above. -->
          <div class="tt-panel">
            <?php foreach (['a', 'b', 'c', 'd', 'e'] as $tt_skey):
                $tt_items = mysqli_query($db, "SELECT * FROM traveltips WHERE tt_section = '" . $tt_skey . "' ORDER BY tt_order ASC, tt_id ASC");
            ?>
            <div class="tt-content<?php echo $tt_skey === 'a' ? ' active' : ''; ?>" id="tt-<?php echo $tt_skey; ?>">
              <?php
                $tt_first = true;
                while ($tt_items && $tt = mysqli_fetch_assoc($tt_items)):
              ?>
              <div class="tt-acc<?php echo $tt_first ? ' open' : ''; ?>">
                <div class="tt-acc-hd">
                  <?php echo htmlspecialchars($tt['tt_header']); ?>
                  <span class="tt-acc-icon"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></span>
                </div>
                <div class="tt-acc-bd">
                  <div class="tt-acc-inner">
                    <?php if (trim((string)$tt['tt_question']) !== ''): ?>
                    <p class="tt-q"><?php echo htmlspecialchars($tt['tt_question']); ?></p>
                    <?php endif; ?>
                    <?php if (trim((string)$tt['tt_answer']) !== ''): ?>
                    <p class="tt-a"><?php echo htmlspecialchars($tt['tt_answer']); ?></p>
                    <?php endif; ?>
                    <?php if (trim((string)$tt['tt_extra']) !== ''): ?>
                    <?php echo $tt['tt_extra']; // raw HTML, admin-managed ?>
                    <?php endif; ?>
                    <?php if ($tt['tt_cta_type'] === 'map' && trim((string)$tt['tt_cta_value']) !== ''): ?>
                    <div class="tt-cta-row">
                      <button class="tt-cta tt-map-btn" data-map-query="<?php echo htmlspecialchars($tt['tt_cta_value'], ENT_QUOTES); ?>">
                        <svg width="11" height="14" viewBox="0 0 11 14" fill="currentColor" style="margin-right:5px;vertical-align:-1px" aria-hidden="true"><path d="M5.5 0C3.02 0 1 2.02 1 4.5c0 3.37 4.5 9.5 4.5 9.5S10 7.87 10 4.5C10 2.02 7.98 0 5.5 0zm0 6.25a1.75 1.75 0 1 1 0-3.5 1.75 1.75 0 0 1 0 3.5z"/></svg><?php echo htmlspecialchars($tt['tt_cta_label']); ?>
                      </button>
                    </div>
                    <?php elseif ($tt['tt_cta_type'] === 'link' && trim((string)$tt['tt_cta_value']) !== ''): ?>
                    <div class="tt-cta-row"><a class="tt-cta" href="<?php echo htmlspecialchars($tt['tt_cta_value'], ENT_QUOTES); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($tt['tt_cta_label']); ?></a></div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <?php $tt_first = false; endwhile; ?>
            </div><!-- /tt-<?php echo $tt_skey; ?> -->
            <?php endforeach; ?>
          </div><!-- /tt-panel -->
        </div><!-- /tt-layout -->
      </div><!-- /tt-wrap -->
    </section>

  </main><!-- /#travel-tips-page -->

  <?php include 'footer.php'; ?>

  <script>
    (function () {
      'use strict';

      // Tab switching
      var tabs = document.querySelectorAll('.tt-tab');
      var contents = document.querySelectorAll('.tt-content');

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          tabs.forEach(function (t) { t.classList.remove('active'); });
          contents.forEach(function (c) { c.classList.remove('active'); });
          tab.classList.add('active');
          var target = document.getElementById(tab.dataset.target);
          if (target) { target.classList.add('active'); }
        });
      });

      // Accordion toggle
      document.querySelectorAll('.tt-acc-hd').forEach(function (hd) {
        hd.addEventListener('click', function () {
          hd.parentElement.classList.toggle('open');
        });
      });

      // Map CTA buttons — open Google Maps near user, fallback to KL city centre
      document.querySelectorAll('.tt-map-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var query   = encodeURIComponent(btn.dataset.mapQuery || 'shop');
          var fallLat = 3.1390, fallLng = 101.6869, zoom = 15;

          function openMap(lat, lng) {
            window.open(
              'https://www.google.com/maps/search/' + query + '/@' + lat + ',' + lng + ',' + zoom + 'z',
              '_blank', 'noopener,noreferrer'
            );
          }

          if (!navigator.geolocation) { openMap(fallLat, fallLng); return; }

          var origHTML = btn.innerHTML;
          btn.textContent = 'Locating…';
          btn.disabled = true;

          navigator.geolocation.getCurrentPosition(
            function (pos) {
              btn.innerHTML  = origHTML;
              btn.disabled   = false;
              openMap(pos.coords.latitude, pos.coords.longitude);
            },
            function () {
              btn.innerHTML  = origHTML;
              btn.disabled   = false;
              openMap(fallLat, fallLng);
            },
            { timeout: 8000, maximumAge: 60000 }
          );
        });
      });
    }());
  </script>

</body>
</html>
