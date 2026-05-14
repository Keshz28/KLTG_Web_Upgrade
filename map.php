<?php include('admin/functions.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide – Interactive Map of Kuala Lumpur</title>

  <meta name="description"
    content="Explore Kuala Lumpur with our interactive map. Find top attractions, landmarks, shopping, food, and transport hubs across KL.">
  <meta name="keywords"
    content="Kuala Lumpur map, KL map, KL attractions map, KLCC map, Petronas Twin Towers, KL Tower, Batu Caves, Bukit Bintang, Petaling Street, KL tourist map">

  <meta property="og:type"        content="website" />
  <meta property="og:url"         content="https://www.kltheguide.com.my/map.php" />
  <meta property="og:title"       content="KL The Guide – Interactive Map of Kuala Lumpur" />
  <meta property="og:description" content="Explore Kuala Lumpur with our interactive map. Find top attractions, landmarks, shopping, and transport across KL." />
  <meta property="og:image"       content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <meta property="twitter:card"        content="summary_large_image" />
  <meta property="twitter:url"         content="https://www.kltheguide.com.my/map.php" />
  <meta property="twitter:title"       content="KL The Guide – Interactive Map of Kuala Lumpur" />
  <meta property="twitter:description" content="Explore Kuala Lumpur with our interactive map. Find top attractions, landmarks, shopping, and transport across KL." />
  <meta property="twitter:image"       content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>

  <style>
    /* ═══════════════════════════════════════════════════
       MAP PAGE
    ═══════════════════════════════════════════════════ */

    /* ── Page header ──────────────────────────────────── */
    .map-header {
      padding: 110px 0 28px;
      text-align: center;
    }

    .map-header h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 800;
      margin-bottom: 10px;
      color: var(--color-secondary, #012970);
    }

    .map-header p {
      max-width: 640px;
      margin: 0 auto;
      color: #555;
      font-size: clamp(.9rem, 1.4vw, 1.05rem);
      line-height: 1.65;
    }

    /* ── Map frame ────────────────────────────────────── */
    .map-frame-wrap {
      position: relative;
      width: 100%;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 8px 40px rgba(0,0,0,.14);
      background: #e8edf2;
    }

    .map-frame-wrap iframe {
      display: block;
      width: 100%;
      height: 520px;
      border: 0;
    }

    /* Loading overlay while iframe loads */
    .map-frame-wrap .map-loading {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #e8edf2;
      color: #888;
      font-size: .9rem;
      pointer-events: none;
      transition: opacity .4s;
    }

    .map-frame-wrap.loaded .map-loading { opacity: 0; }

    /* ── Landmark selector chips ─────────────────────── */
    .landmark-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 20px 0 6px;
      justify-content: center;
    }

    .landmark-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 16px;
      border: 1.5px solid var(--color-primary, #0ea2bd);
      border-radius: 9999px;
      background: transparent;
      color: var(--color-primary, #0ea2bd);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s, color .2s, transform .15s;
      white-space: nowrap;
    }

    .landmark-chip:hover,
    .landmark-chip.is-active {
      background: var(--color-primary, #0ea2bd);
      color: #fff;
      transform: translateY(-1px);
    }

    .landmark-chip i { font-size: 14px; }

    /* ── Section divider label ───────────────────────── */
    .map-section-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: #aaa;
      text-align: center;
      margin: 34px 0 18px;
    }

    /* ── Landmark cards grid ─────────────────────────── */
    .landmark-card {
      display: flex;
      flex-direction: column;
      height: 100%;
      border-radius: 12px;
      border: 1px solid #e6eaf0;
      overflow: hidden;
      background: #fff;
      transition: box-shadow .22s, transform .22s;
    }

    .landmark-card:hover {
      box-shadow: 0 8px 28px rgba(0,0,0,.10);
      transform: translateY(-3px);
    }

    .landmark-card__icon {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 80px;
      font-size: 2.2rem;
      color: var(--color-primary, #0ea2bd);
      background: #f0fafd;
      flex-shrink: 0;
    }

    .landmark-card__body {
      padding: 14px 16px 18px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .landmark-card__cat {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--color-primary, #0ea2bd);
      margin-bottom: 4px;
    }

    .landmark-card__name {
      font-size: 15px;
      font-weight: 700;
      color: #1a2535;
      margin: 0 0 6px;
      line-height: 1.2;
    }

    .landmark-card__desc {
      font-size: 12.5px;
      color: #666;
      line-height: 1.55;
      flex: 1;
      margin: 0 0 14px;
    }

    .landmark-card__actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .landmark-card__btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none;
      transition: opacity .2s;
      cursor: pointer;
      border: none;
    }

    .landmark-card__btn--map {
      background: var(--color-primary, #0ea2bd);
      color: #fff;
    }

    .landmark-card__btn--dir {
      background: #f0f4f8;
      color: #333;
    }

    .landmark-card__btn:hover { opacity: .82; }

    /* ── Practical info strip ────────────────────────── */
    .map-info-strip {
      margin-top: 44px;
      padding: 32px 0 10px;
      border-top: 1px solid #e8ecf0;
    }

    .map-info-card {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 22px;
      border-radius: 12px;
      background: #f8fafc;
      border: 1px solid #e6eaf0;
      height: 100%;
    }

    .map-info-card__icon {
      font-size: 1.8rem;
      color: var(--color-primary, #0ea2bd);
      flex-shrink: 0;
      margin-top: 2px;
    }

    .map-info-card__title {
      font-size: 15px;
      font-weight: 700;
      color: #1a2535;
      margin: 0 0 5px;
    }

    .map-info-card__text {
      font-size: 13px;
      color: #666;
      line-height: 1.6;
      margin: 0;
    }

    .map-info-card a {
      color: var(--color-primary, #0ea2bd);
      font-weight: 600;
    }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 767px) {
      .map-frame-wrap iframe { height: 360px; }
      .landmark-chip         { font-size: 12px; padding: 6px 13px; }
    }
  </style>
</head>

<body>
  <?php include 'nav.php'; ?>

  <main id="map">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="container">
      <div class="map-header" data-aos="fade-up">
        <h1>Explore Kuala Lumpur</h1>
        <p>Navigate KL like a local. Use the interactive map below to discover top landmarks, plan
           your route, and find exactly where everything is in the city.</p>
      </div>
    </div>

    <!-- ── Map + chips ──────────────────────────────── -->
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <!-- Google Maps iframe -->
      <div class="map-frame-wrap" id="mapWrap">
        <div class="map-loading"><i class="bi bi-map me-2"></i> Loading map…</div>
        <iframe
          id="klMap"
          src="https://maps.google.com/maps?q=Kuala+Lumpur+City+Centre+Malaysia&t=&z=14&ie=UTF8&iwloc=&output=embed"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Interactive map of Kuala Lumpur">
        </iframe>
      </div>

      <!-- Landmark selector chips -->
      <div class="landmark-chips" id="chipBar">
        <button class="landmark-chip is-active" data-query="Kuala Lumpur City Centre Malaysia" data-zoom="14">
          <i class="bi bi-geo-alt-fill"></i> KL Overview
        </button>
        <button class="landmark-chip" data-query="Petronas Twin Towers Kuala Lumpur" data-zoom="16">
          <i class="bi bi-buildings"></i> Petronas Twin Towers
        </button>
        <button class="landmark-chip" data-query="KL Tower Menara Kuala Lumpur" data-zoom="16">
          <i class="bi bi-broadcast-pin"></i> KL Tower
        </button>
        <button class="landmark-chip" data-query="Batu Caves Selangor Malaysia" data-zoom="16">
          <i class="bi bi-triangle"></i> Batu Caves
        </button>
        <button class="landmark-chip" data-query="Masjid Jamek Kuala Lumpur" data-zoom="16">
          <i class="bi bi-moon-stars"></i> Masjid Jamek
        </button>
        <button class="landmark-chip" data-query="Petaling Street Chinatown Kuala Lumpur" data-zoom="17">
          <i class="bi bi-shop-window"></i> Petaling Street
        </button>
        <button class="landmark-chip" data-query="Bukit Bintang Kuala Lumpur" data-zoom="16">
          <i class="bi bi-stars"></i> Bukit Bintang
        </button>
        <button class="landmark-chip" data-query="KL Sentral Station Kuala Lumpur" data-zoom="16">
          <i class="bi bi-train-front-fill"></i> KL Sentral
        </button>
      </div>

    </div><!-- /container -->

    <!-- ── Landmark cards ───────────────────────────── -->
    <div class="container" data-aos="fade-up" data-aos-delay="150">
      <p class="map-section-label">Key Landmarks</p>

      <div class="row g-3">

        <!-- Petronas Twin Towers -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-buildings"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Attraction</span>
              <h3 class="landmark-card__name">Petronas Twin Towers</h3>
              <p class="landmark-card__desc">World-famous twin skyscrapers and KL's most iconic symbol. Visit the sky bridge on the 41st floor.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="Petronas Twin Towers Kuala Lumpur" data-zoom="16">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=Petronas+Twin+Towers+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- KL Tower -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-broadcast-pin"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Attraction</span>
              <h3 class="landmark-card__name">KL Tower</h3>
              <p class="landmark-card__desc">421-metre telecom tower with a 360° observation deck offering panoramic views over the city.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="KL Tower Menara Kuala Lumpur" data-zoom="16">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=KL+Tower+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Batu Caves -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-triangle"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Heritage</span>
              <h3 class="landmark-card__name">Batu Caves</h3>
              <p class="landmark-card__desc">Sacred Hindu shrine complex set inside limestone caves, topped by a 43-metre golden statue of Lord Murugan.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="Batu Caves Selangor Malaysia" data-zoom="16">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=Batu+Caves+Selangor"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Masjid Jamek -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-moon-stars"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Heritage</span>
              <h3 class="landmark-card__name">Masjid Jamek</h3>
              <p class="landmark-card__desc">KL's oldest mosque, built in 1909 at the confluence of the Klang and Gombak rivers — the city's historic birthplace.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="Masjid Jamek Kuala Lumpur" data-zoom="17">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=Masjid+Jamek+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Petaling Street -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-shop-window"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Shopping</span>
              <h3 class="landmark-card__name">Petaling Street</h3>
              <p class="landmark-card__desc">KL's vibrant Chinatown — a bustling street market famous for food, bargain goods, and authentic local culture.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="Petaling Street Chinatown Kuala Lumpur" data-zoom="17">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=Petaling+Street+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Bukit Bintang -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-stars"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Shopping &amp; Food</span>
              <h3 class="landmark-card__name">Bukit Bintang</h3>
              <p class="landmark-card__desc">KL's entertainment and lifestyle hub — home to Pavilion Mall, Jalan Alor night food street, and endless nightlife.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="Bukit Bintang Kuala Lumpur" data-zoom="16">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=Bukit+Bintang+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Central Market -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-bag-heart"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Culture</span>
              <h3 class="landmark-card__name">Central Market</h3>
              <p class="landmark-card__desc">A heritage art deco building turned cultural marketplace, packed with local crafts, batik, and Malaysian souvenirs.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="Central Market Kuala Lumpur" data-zoom="17">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=Central+Market+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- KL Sentral -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="landmark-card">
            <div class="landmark-card__icon"><i class="bi bi-train-front-fill"></i></div>
            <div class="landmark-card__body">
              <span class="landmark-card__cat">Transport Hub</span>
              <h3 class="landmark-card__name">KL Sentral</h3>
              <p class="landmark-card__desc">Malaysia's largest transportation hub — connecting the KLIA Express, LRT, MRT, KTM Komuter and KL Monorail lines.</p>
              <div class="landmark-card__actions">
                <button class="landmark-card__btn landmark-card__btn--map"
                        data-query="KL Sentral Station Kuala Lumpur" data-zoom="16">
                  <i class="bi bi-pin-map"></i> Show
                </button>
                <a class="landmark-card__btn landmark-card__btn--dir"
                   href="https://www.google.com/maps/dir/?api=1&destination=KL+Sentral+Kuala+Lumpur"
                   target="_blank" rel="noopener">
                  <i class="bi bi-sign-turn-right"></i> Directions
                </a>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /row -->
    </div><!-- /container -->

    <!-- ── Practical info strip ──────────────────────── -->
    <div class="container">
      <div class="map-info-strip" data-aos="fade-up">
        <p class="map-section-label">Practical Information</p>
        <div class="row g-3">

          <div class="col-12 col-md-4">
            <div class="map-info-card">
              <div class="map-info-card__icon"><i class="bi bi-signpost-2-fill"></i></div>
              <div>
                <p class="map-info-card__title">Getting Around KL</p>
                <p class="map-info-card__text">
                  KL has an extensive rail network — LRT, MRT, KTM Komuter, and the Monorail all connect
                  the main tourist zones. Buy a <strong>Touch 'n Go</strong> card for seamless travel.
                  <br><a href="highlights.php#tab-2">Full transport guide →</a>
                </p>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="map-info-card">
              <div class="map-info-card__icon"><i class="bi bi-telephone-fill"></i></div>
              <div>
                <p class="map-info-card__title">Emergency &amp; Tourist Helplines</p>
                <p class="map-info-card__text">
                  <strong>Police / Fire / Ambulance:</strong> 999<br>
                  <strong>Tourist Police:</strong> 03-2149 6590<br>
                  <strong>Malaysia Tourism:</strong> 1-300-88-5050<br>
                  <strong>Rapid KL Helpline:</strong> 03-7885 2585
                </p>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="map-info-card">
              <div class="map-info-card__icon"><i class="bi bi-phone-fill"></i></div>
              <div>
                <p class="map-info-card__title">Navigate Offline</p>
                <p class="map-info-card__text">
                  Download <strong>Google Maps</strong> or <strong>Maps.me</strong> for KL before your
                  trip to navigate without mobile data. Grab (ride-hailing) works throughout the city
                  and is the easiest way to reach any destination.
                </p>
              </div>
            </div>
          </div>

        </div><!-- /row -->
      </div>
    </div>

    <!-- ── Ad banner ─────────────────────────────────── -->
    <div class="container">
      <div class="row d-flex justify-content-center btmbanner mt-4">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3696733888071014"
          crossorigin="anonymous"></script>
        <ins class="adsbygoogle" align="center"
          data-ad-client="ca-pub-3696733888071014"
          data-ad-slot="5212427798"
          data-ad-format="auto"
          data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </div>
    </div>

  </main><!-- End #main -->

  <?php include 'footer.php'; ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    (function () {
      'use strict';

      var mapFrame  = document.getElementById('klMap');
      var mapWrap   = document.getElementById('mapWrap');
      var chips     = Array.from(document.querySelectorAll('.landmark-chip'));

      /* Mark wrapper as loaded once iframe fires load event */
      mapFrame.addEventListener('load', function () {
        mapWrap.classList.add('loaded');
      });

      /* Build map URL from a search query + zoom level */
      function buildMapSrc(query, zoom) {
        return 'https://maps.google.com/maps?q='
          + encodeURIComponent(query)
          + '&t=&z=' + (zoom || 14)
          + '&ie=UTF8&iwloc=&output=embed';
      }

      /* Update the map iframe */
      function loadMap(query, zoom) {
        mapWrap.classList.remove('loaded');
        mapFrame.src = buildMapSrc(query, zoom);
        /* Smooth scroll to map on mobile */
        if (window.innerWidth < 768) {
          mapWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }

      /* ── Chip buttons ── */
      chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
          chips.forEach(function (c) { c.classList.remove('is-active'); });
          this.classList.add('is-active');
          loadMap(this.dataset.query, this.dataset.zoom);
        });
      });

      /* ── Landmark card "Show" buttons ── */
      document.querySelectorAll('.landmark-card__btn--map').forEach(function (btn) {
        btn.addEventListener('click', function () {
          /* Deactivate all chips and activate "KL Overview" as fallback */
          chips.forEach(function (c) { c.classList.remove('is-active'); });

          /* Try to match a chip with same query */
          var q = btn.dataset.query;
          var matched = chips.find(function (c) { return c.dataset.query === q; });
          if (matched) matched.classList.add('is-active');

          loadMap(q, btn.dataset.zoom);

          /* Scroll map into view */
          mapWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      });
    })();
  </script>

</body>
</html>
