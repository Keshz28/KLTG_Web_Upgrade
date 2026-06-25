<?php
/* ============================================================================
 *                                 map.php
 *
 *   Public interactive map page.
 *   Target of every 'View on Map' deep-link button across the site.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('admin/functions.php'); ?>
<?php
/**
 * Renders one Explore KL sub-section on the map page: an embedded map, a
 * "View All" chip, and a 3-per-slide carousel of place cards pulled from
 * $opts['table']. Mirrors the hand-written What-To-Do / Historical Sites
 * blocks so every sub-tab looks and behaves the same.
 *
 * $opts keys: key, table, prefix, imgPath, heading, mapQuery, icon, decode.
 * Columns are derived from prefix: {prefix}_title/_content/_location/_locationurl/_image.
 * decode=true url-decodes text fields (pwor/nl/kl4k); wte_r stores plain text so it passes false.
 */
function mapExploreSubtab($db, array $opts)
{
  $key      = $opts['key'];
  $table    = $opts['table'];
  $prefix   = $opts['prefix'];
  $imgPath  = $opts['imgPath'];
  $heading  = $opts['heading'];
  $mapQuery = $opts['mapQuery'];
  $icon     = $opts['icon'];
  $decode   = $opts['decode'] ?? true;
  $active   = $opts['active'] ?? false;          // mark this sub-tab open by default
  $labelText = $opts['linkLabelText'] ?? null;   // static link text; else the location field

  $titleCol = $prefix . '_title';
  $contentCol = $prefix . '_content';
  $locCol = $prefix . '_location';
  $urlCol = $prefix . '_locationurl';
  $imgCol = $prefix . '_image';

  $dec = static function ($v) use ($decode) {
    return $decode ? urldecode((string) $v) : (string) $v;
  };

  $perSlide = 3;
  $limit = 9;
  $total = (int) (mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) AS total FROM `$table`"))['total'] ?? 0);
  $display = min($total, $limit);           // we only render LIMIT 9 rows...
  $slides = max(1, (int) ceil($display / $perSlide)); // ...so indicators match
  ?>
  <div class="explorekl-subtab<?php echo $active ? ' active' : '' ?>" data-subtab="<?php echo $key ?>">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="map-frame-wrap" id="mapWrap-<?php echo $key ?>">
        <iframe id="klMap-<?php echo $key ?>"
          src="https://maps.google.com/maps?q=<?php echo urlencode($mapQuery) ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"
          allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="landmark-chips" id="chipBar-<?php echo $key ?>">
        <button class="landmark-chip is-active" data-query="<?php echo htmlspecialchars($mapQuery, ENT_QUOTES) ?>" data-zoom="13">
          <i class="bi <?php echo $icon ?>"></i> View All
        </button>
      </div>
    </div>
    <div class="container">
      <div class="section-content">
        <h3><?php echo $heading ?></h3>
        <div id="<?php echo $key ?>Carousel" class="carousel slide place-carousel" data-bs-ride="false" data-bs-interval="false">
          <div class="carousel-indicators">
            <?php for ($i = 0; $i < $slides; $i++) {
              echo '<button type="button" data-bs-target="#' . $key . 'Carousel" data-bs-slide-to="' . $i . '" class="' . ($i === 0 ? 'active' : '') . '"></button>';
            } ?>
          </div>
          <div class="carousel-inner">
            <?php
            $result = mysqli_query($db, "SELECT * FROM `$table` LIMIT $limit");
            $counter = 1;
            $slide_count = 0;
            while ($row = mysqli_fetch_assoc($result)) {
              if ($counter % $perSlide == 1) {
                echo '<div class="carousel-item ' . ($slide_count === 0 ? 'active' : '') . '"><div class="row g-4">';
                $slide_count++;
              }
            ?>
              <div class="col-md-4">
                <div class="place-card">
                  <img src="<?php echo $imgPath . $row[$imgCol] ?>"
                    alt="<?php echo htmlspecialchars($dec($row[$titleCol]), ENT_QUOTES) ?>" loading="lazy">
                  <div class="place-card__body">
                    <h5 class="place-card__title"><?php echo $dec($row[$titleCol]) ?></h5>
                    <p class="place-card__desc"><?php echo $dec($row[$contentCol]) ?></p>
                    <?php if (trim((string) $row[$locCol]) !== '') { ?>
                      <div class="place-info">
                        <i class="bi bi-geo-alt-fill"></i>
                        <a href="<?php echo urldecode((string) $row[$urlCol]) ?>" target="_blank">
                          <?php echo $labelText !== null ? htmlspecialchars($labelText, ENT_QUOTES) : $dec($row[$locCol]) ?>
                        </a>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            <?php
              if ($counter % $perSlide == 0 || $counter == $display) {
                echo '</div></div>';
              }
              $counter++;
            }
            ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $key ?>Carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $key ?>Carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
<?php }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide – Interactive Map of Kuala Lumpur</title>
  <link rel="canonical" href="https://www.kltheguide.com.my/map.php" />

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

  <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

  <?php include 'header.php'; ?>

  <style>
    /* MAP PAGE */

    .map-header {
      padding: 110px 0 28px;
      text-align: center;
    }

    .map-header h1 {
      font-family: 'Carter One', cursive;
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 700;
      margin-bottom: 10px;
      color: #1520A6;
    }

    .map-header p {
      max-width: 640px;
      margin: 0 auto;
      color: #555;
      font-size: clamp(.9rem, 1.4vw, 1.05rem);
      line-height: 1.65;
    }

    /* Category tabs */
    .map-category-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 20px 0;
      justify-content: center;
      border-bottom: 2px solid #e8ecf0;
      margin-bottom: 30px;
    }

    .map-category-tab {
      padding: 10px 18px;
      border: none;
      background: transparent;
      color: #666;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all .25s ease;
      position: relative;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
    }

    .map-category-tab:hover {
      color: var(--color-primary, #0ea2bd);
      transform: translateY(-2px);
    }

    .map-category-tab.active {
      color: var(--color-primary, #0ea2bd);
      border-bottom-color: var(--color-primary, #0ea2bd);
    }

    /* Explore KL sub-tabs with animation */
    .explorekl-sub-tabs {
      display: none;
      overflow: hidden;
      max-height: 0;
      opacity: 0;
      transition: max-height .4s ease, opacity .4s ease, padding .4s ease;
    }

    .explorekl-sub-tabs.active {
      display: flex;
      max-height: 100px;
      opacity: 1;
      padding: 15px 0;
    }

    .explorekl-sub-tabs {
      flex-wrap: wrap;
      gap: 8px;
      justify-content: center;
      border-bottom: 1px solid #f0f0f0;
    }

    .explorekl-sub-tabs .map-category-tab {
      font-size: 13px;
      padding: 8px 14px;
      background: #f5f8fb;
      border-radius: 20px;
      border: 1px solid #e0e0e0;
      border-bottom: 1px solid #e0e0e0;
      margin-bottom: 0;
    }

    .explorekl-sub-tabs .map-category-tab.active {
      background: var(--color-primary, #0ea2bd);
      color: white;
      border-color: var(--color-primary, #0ea2bd);
    }

    /* Map frame */
    .map-frame-wrap {
      position: relative;
      width: 100%;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 8px 40px rgba(0,0,0,.14);
      background: #e8edf2;
      margin-bottom: 20px;
      animation: fadeInUp .6s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .map-frame-wrap iframe,
    .map-canvas {
      width: 100%;
      height: 520px;
      border: 0;
      display: block;
    }

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

    /* Landmark selector chips */
    .landmark-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 20px 0 6px;
      justify-content: center;
      animation: fadeInUp .6s ease .1s backwards;
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
      transition: all .2s ease;
      white-space: nowrap;
    }

    .landmark-chip:hover {
      background: var(--color-primary, #0ea2bd);
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(14, 162, 189, 0.3);
    }

    .landmark-chip.is-active {
      background: var(--color-primary, #0ea2bd);
      color: #fff;
    }

    .landmark-chip i { font-size: 14px; }

    .map-section-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: #aaa;
      text-align: center;
      margin: 34px 0 18px;
    }

    /* Content carousel for each section */
    .section-content {
      margin-top: 40px;
      animation: fadeInUp .6s ease .2s backwards;
    }

    .section-content h3 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.8rem;
      color: var(--color-secondary, #012970);
    }

    .place-carousel {
      margin-bottom: 40px;
    }

    .place-card {
      display: flex;
      flex-direction: column;
      height: 100%;
      border-radius: 12px;
      border: 1px solid #e6eaf0;
      overflow: hidden;
      background: #fff;
      transition: all .3s ease;
    }

    .place-card:hover {
      box-shadow: 0 8px 28px rgba(0,0,0,.12);
      transform: translateY(-5px);
    }

    .place-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .place-card__body {
      padding: 16px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .place-card__title {
      font-size: 15px;
      font-weight: 700;
      color: #1a2535;
      margin: 0 0 8px;
      line-height: 1.2;
    }

    .place-card__desc {
      font-size: 12.5px;
      color: #666;
      line-height: 1.55;
      flex: 1;
      margin: 0 0 12px;
    }

    .place-info {
      font-size: 12px;
      color: #555;
      margin: 4px 0;
    }

    .place-info i {
      color: var(--color-primary, #0ea2bd);
      margin-right: 6px;
      width: 14px;
    }

    .place-info a {
      color: var(--color-primary, #0ea2bd);
      text-decoration: none;
      font-weight: 600;
    }

    .place-info a:hover {
      text-decoration: underline;
    }

    /* Tab panes */
    .map-tab-pane {
      display: none;
      animation: fadeInUp .6s ease;
    }

    .map-tab-pane.active {
      display: block;
    }

    .explorekl-subtab {
      display: none;
      animation: fadeInUp .6s ease;
    }

    .explorekl-subtab.active {
      display: block;
    }

    /* Responsive */
    @media (max-width: 767px) {
      .map-frame-wrap #map { height: 360px; }
      .landmark-chip { font-size: 12px; padding: 6px 13px; }
      .map-category-tabs { gap: 6px; }
      .map-category-tab { padding: 8px 14px; font-size: 13px; }
      .explorekl-sub-tabs.active { max-height: 150px; }
    }

  </style>
</head>

<body>
  <?php include 'nav.php'; ?>

  <main id="map">

    <!-- Page header -->
    <div class="container">
      <div class="map-header" data-aos="fade-up">
        <h1>Explore Kuala Lumpur</h1>
        <p>Navigate KL like a local. Use the interactive map below to discover top landmarks, plan
           your route, and find exactly where everything is in the city.</p>
      </div>
    </div>

    <!-- Category tabs -->
    <div class="container">
      <div class="map-category-tabs" data-aos="fade-up" data-aos-delay="50">
        <button class="map-category-tab active" data-tab="overview">
          <i class="bi bi-map me-2"></i> Map Overview
        </button>
        <button class="map-category-tab" data-tab="explorekl">
          <i class="bi bi-compass me-2"></i> Explore KL
        </button>
        <button class="map-category-tab" data-tab="beyondkl">
          <i class="bi bi-geo me-2"></i> Beyond KL
        </button>
        <button class="map-category-tab" data-tab="medicaltourism">
          <i class="bi bi-hospital me-2"></i> Medical Tourism
        </button>
        <button class="map-category-tab" data-tab="shopping">
          <i class="bi bi-bag-heart me-2"></i> Shop Like Local
        </button>
        <button class="map-category-tab" data-tab="spa">
          <i class="bi bi-person-check me-2"></i> Spa Time
        </button>
        <button class="map-category-tab" data-tab="accommodation">
          <i class="bi bi-building me-2"></i> Places to Stay
        </button>
      </div>
    </div>

    <!-- MAP OVERVIEW TAB -->
    <div class="map-tab-pane active" data-tab="overview">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="map-frame-wrap" id="mapWrap">
          <iframe id="klMap" src="https://maps.google.com/maps?q=Kuala+Lumpur+City+Centre+Malaysia&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="landmark-chips" id="chipBar">
          <button class="landmark-chip is-active" data-query="Kuala Lumpur City Centre Malaysia" data-zoom="13">
            <i class="bi bi-geo-alt-fill"></i> KL Center
          </button>
          <button class="landmark-chip" data-query="Petronas Twin Towers Kuala Lumpur" data-zoom="16">
            <i class="bi bi-buildings"></i> Petronas Towers
          </button>
          <button class="landmark-chip" data-query="KL Tower Menara Kuala Lumpur" data-zoom="16">
            <i class="bi bi-broadcast-pin"></i> KL Tower
          </button>
          <button class="landmark-chip" data-query="Batu Caves Selangor Malaysia" data-zoom="15">
            <i class="bi bi-triangle"></i> Batu Caves
          </button>
          <button class="landmark-chip" data-query="Bukit Bintang Kuala Lumpur" data-zoom="16">
            <i class="bi bi-stars"></i> Bukit Bintang
          </button>
          <button class="landmark-chip" data-query="KL Sentral Station Kuala Lumpur" data-zoom="16">
            <i class="bi bi-train-front-fill"></i> KL Sentral
          </button>
        </div>
      </div>

      <!-- Landmark cards -->
      <div class="container" data-aos="fade-up" data-aos-delay="150">
        <p class="map-section-label">Key Landmarks</p>
        <div class="row g-3">
          <div class="col-6 col-md-4 col-lg-3">
            <div class="place-card">
              <img src="asset-backups/opt/map/twintower.jpg" alt="Petronas Twin Towers" loading="lazy">
              <div class="place-card__body">
                <h5 class="place-card__title">Petronas Twin Towers</h5>
                <p class="place-card__desc">World-famous twin skyscrapers with sky bridge.</p>
                <div class="place-info">
                  <i class="bi bi-geo-alt-fill"></i>
                  <a href="https://www.google.com/maps/search/Petronas+Twin+Towers" target="_blank">Show on Map</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-4 col-lg-3">
            <div class="place-card">
              <img src="assets/img/recommendation/ExploringKL.jpg" alt="KL Tower" loading="lazy">
              <div class="place-card__body">
                <h5 class="place-card__title">KL Tower</h5>
                <p class="place-card__desc">421m tower with 360° observation deck.</p>
                <div class="place-info">
                  <i class="bi bi-geo-alt-fill"></i>
                  <a href="https://www.google.com/maps/search/KL+Tower" target="_blank">Show on Map</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-4 col-lg-3">
            <div class="place-card">
              <img src="asset-backups/opt/map/batu_caves.jpg" alt="Batu Caves" loading="lazy">
              <div class="place-card__body">
                <h5 class="place-card__title">Batu Caves</h5>
                <p class="place-card__desc">Sacred Hindu shrine in limestone caves.</p>
                <div class="place-info">
                  <i class="bi bi-geo-alt-fill"></i>
                  <a href="https://www.google.com/maps/search/Batu+Caves" target="_blank">Show on Map</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-4 col-lg-3">
            <div class="place-card">
              <img src="asset-backups/opt/map/bb_pic.jpg" alt="Bukit Bintang" loading="lazy">
              <div class="place-card__body">
                <h5 class="place-card__title">Bukit Bintang</h5>
                <p class="place-card__desc">Entertainment hub with shopping and nightlife.</p>
                <div class="place-info">
                  <i class="bi bi-geo-alt-fill"></i>
                  <a href="https://www.google.com/maps/search/Bukit+Bintang" target="_blank">Show on Map</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Overview Tab -->

    <!-- EXPLORE KL TAB -->
    <div class="map-tab-pane" data-tab="explorekl">
      <!-- Sub-tabs for Explore KL with animation -->
      <div class="container">
        <div class="explorekl-sub-tabs" id="exploreKLSubTabs" data-tab-bar="explorekl">
          <button class="map-category-tab active" data-subtab="wtd">
            <i class="bi bi-compass me-2"></i> What To Do
          </button>
          <button class="map-category-tab" data-subtab="historical">
            <i class="bi bi-book me-2"></i> Historical Sites
          </button>
          <button class="map-category-tab" data-subtab="worship">
            <i class="bi bi-moon-stars me-2"></i> Places of Worship
          </button>
          <button class="map-category-tab" data-subtab="food">
            <i class="bi bi-cup-hot me-2"></i> Food & Dining
          </button>
          <button class="map-category-tab" data-subtab="nightlife">
            <i class="bi bi-stars me-2"></i> Night Life
          </button>
          <button class="map-category-tab" data-subtab="kids">
            <i class="bi bi-balloon me-2"></i> KL 4 Kids
          </button>
        </div>
      </div>

      <!-- WHAT TO DO Subtab -->
      <div class="explorekl-subtab active" data-subtab="wtd">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="map-frame-wrap" id="mapWrap-wtd">
            <iframe id="klMap-wtd" src="https://maps.google.com/maps?q=attractions+Kuala+Lumpur&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
          <div class="landmark-chips" id="chipBar-wtd">
            <button class="landmark-chip is-active" data-query="things to do Kuala Lumpur" data-zoom="13">
              <i class="bi bi-compass"></i> View All
            </button>
          </div>
        </div>
        <div class="container">
          <div class="section-content">
            <h3>What To Do In KL</h3>
            <div id="wtdCarousel" class="carousel slide place-carousel" data-bs-ride="false" data-bs-interval="false">
              <div class="carousel-indicators">
                <?php
                $query_count = "SELECT COUNT(*) as total FROM explorekl_wtd";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 3;
                $total_slides = ceil($total_items / $items_per_slide);
                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#wtdCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '"></button>';
                }
                ?>
              </div>
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM explorekl_wtd LIMIT 9";
                $result = mysqli_query($db, $query);
                $counter = 1;
                $items_per_slide = 3;
                $slide_count = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                  if ($counter % $items_per_slide == 1) {
                    $active_class = ($slide_count == 0) ? 'active' : '';
                    echo '<div class="carousel-item ' . $active_class . '"><div class="row g-4">';
                    $slide_count++;
                  }
                ?>
                  <div class="col-md-4">
                    <div class="place-card">
                      <img src="assets/img/explorekl/wtd/<?php echo urldecode($row['explorekl_wtd_image']) ?>"
                        alt="<?php echo urldecode($row['explorekl_wtd_title']) ?>" loading="lazy">
                      <div class="place-card__body">
                        <h5 class="place-card__title"><?php echo urldecode($row['explorekl_wtd_title']) ?></h5>
                        <p class="place-card__desc"><?php echo urldecode($row['explorekl_wtd_content']) ?></p>
                        <?php if ($row['explorekl_wtd_location']) { ?>
                          <div class="place-info">
                            <i class="bi bi-geo-alt-fill"></i>
                            <a href="<?php echo $row['explorekl_wtd_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_wtd_location']) ?>
                            </a>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php
                  if ($counter % $items_per_slide == 0 || $counter == $total_items) {
                    echo '</div></div>';
                  }
                  $counter++;
                }
                ?>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#wtdCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#wtdCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- HISTORICAL SITES Subtab -->
      <div class="explorekl-subtab" data-subtab="historical">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="map-frame-wrap" id="mapWrap-historical">
            <iframe id="klMap-historical" src="https://maps.google.com/maps?q=historical+sites+Kuala+Lumpur&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
          <div class="landmark-chips" id="chipBar-historical">
            <button class="landmark-chip is-active" data-query="historical sites Kuala Lumpur" data-zoom="13">
              <i class="bi bi-book"></i> View All
            </button>
          </div>
        </div>
        <div class="container">
          <div class="section-content">
            <h3>Historical Sites</h3>
            <div id="hsCarousel" class="carousel slide place-carousel" data-bs-ride="false" data-bs-interval="false">
              <div class="carousel-indicators">
                <?php
                $query_count = "SELECT COUNT(*) as total FROM explorekl_hs";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 3;
                $total_slides = ceil($total_items / $items_per_slide);
                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#hsCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '"></button>';
                }
                ?>
              </div>
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM explorekl_hs LIMIT 9";
                $result = mysqli_query($db, $query);
                $counter = 1;
                $items_per_slide = 3;
                $slide_count = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                  if ($counter % $items_per_slide == 1) {
                    $active_class = ($slide_count == 0) ? 'active' : '';
                    echo '<div class="carousel-item ' . $active_class . '"><div class="row g-4">';
                    $slide_count++;
                  }
                ?>
                  <div class="col-md-4">
                    <div class="place-card">
                      <img src="assets/img/explorekl/hs/<?php echo $row['explorekl_hs_image'] ?>"
                        alt="<?php echo urldecode($row['explorekl_hs_title']) ?>" loading="lazy">
                      <div class="place-card__body">
                        <h5 class="place-card__title"><?php echo urldecode($row['explorekl_hs_title']) ?></h5>
                        <p class="place-card__desc"><?php echo urldecode($row['explorekl_hs_content']) ?></p>
                        <?php if ($row['explorekl_hs_location']) { ?>
                          <div class="place-info">
                            <i class="bi bi-geo-alt-fill"></i>
                            <a href="<?php echo $row['explorekl_hs_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_hs_location']) ?>
                            </a>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php
                  if ($counter % $items_per_slide == 0 || $counter == $total_items) {
                    echo '</div></div>';
                  }
                  $counter++;
                }
                ?>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#hsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#hsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- PLACES OF WORSHIP / FOOD / NIGHT LIFE / KL 4 KIDS Subtabs -->
      <?php
      mapExploreSubtab($db, [
        'key' => 'worship', 'table' => 'explorekl_pwor', 'prefix' => 'explorekl_pwor',
        'imgPath' => 'assets/img/explorekl/pwor/', 'heading' => 'Places of Worship',
        'mapQuery' => 'places of worship Kuala Lumpur', 'icon' => 'bi-moon-stars', 'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'food', 'table' => 'explorekl_wte_r', 'prefix' => 'explorekl_wte_r',
        'imgPath' => 'assets/img/explorekl/wte/r/', 'heading' => 'Food & Dining',
        'mapQuery' => 'restaurants Kuala Lumpur', 'icon' => 'bi-cup-hot', 'decode' => false,
      ]);
      mapExploreSubtab($db, [
        'key' => 'nightlife', 'table' => 'explorekl_nl', 'prefix' => 'explorekl_nl',
        'imgPath' => 'assets/img/explorekl/nl/', 'heading' => 'Night Life',
        'mapQuery' => 'nightlife bars Kuala Lumpur', 'icon' => 'bi-stars', 'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'kids', 'table' => 'explorekl_kl4k', 'prefix' => 'explorekl_kl4k',
        'imgPath' => 'assets/img/explorekl/kl4k/', 'heading' => 'KL 4 Kids',
        'mapQuery' => 'family attractions Kuala Lumpur', 'icon' => 'bi-balloon', 'decode' => true,
      ]);
      ?>
    </div><!-- End Explore KL Tab -->

    <!-- SPA TAB -->
    <div class="map-tab-pane" data-tab="spa">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="map-frame-wrap" id="mapWrap-spa">
          <iframe id="klMap-spa" src="https://maps.google.com/maps?q=spas+Kuala+Lumpur&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="landmark-chips" id="chipBar-spa">
          <button class="landmark-chip is-active" data-query="spas and wellness Kuala Lumpur" data-zoom="13">
            <i class="bi bi-person-check"></i> All Spas
          </button>
        </div>
      </div>

      <div class="container">
        <div class="section-content">
          <h3>Spa & Wellness</h3>
          <div id="spaCarousel" class="carousel slide place-carousel" data-bs-ride="false" data-bs-interval="false">
            <div class="carousel-indicators">
              <?php
              $query_count = "SELECT COUNT(*) as total FROM spa";
              $result_count = mysqli_query($db, $query_count);
              $total_items = mysqli_fetch_assoc($result_count)['total'];
              $items_per_slide = 3;
              $total_slides = ceil($total_items / $items_per_slide);
              for ($i = 0; $i < $total_slides; $i++) {
                $active_class = ($i == 0) ? 'active' : '';
                echo '<button type="button" data-bs-target="#spaCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '"></button>';
              }
              ?>
            </div>

            <div class="carousel-inner">
              <?php
              $query = "SELECT * FROM spa ORDER BY spa_order DESC LIMIT 9";
              $result = mysqli_query($db, $query);
              $counter = 1;
              $items_per_slide = 3;
              $slide_count = 0;

              while ($row = mysqli_fetch_assoc($result)) {
                if ($counter % $items_per_slide == 1) {
                  $active_class = ($slide_count == 0) ? 'active' : '';
                  echo '<div class="carousel-item ' . $active_class . '"><div class="row g-4">';
                  $slide_count++;
                }
              ?>
                <div class="col-md-4">
                  <div class="place-card">
                    <img src="assets/img/spa/<?php echo $row['spa_image'] ?>"
                      alt="<?php echo urldecode($row['spa_title']) ?>" loading="lazy">
                    <div class="place-card__body">
                      <h5 class="place-card__title"><?php echo urldecode($row['spa_title']) ?></h5>
                      <p class="place-card__desc"><?php echo urldecode($row['spa_content']) ?></p>
                      <?php if ($row['spa_location']) { ?>
                        <div class="place-info">
                          <i class="bi bi-geo-alt-fill"></i>
                          <a href="<?php echo $row['spa_locationurl'] ?>" target="_blank">
                            <?php echo urldecode($row['spa_location']) ?>
                          </a>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php
                if ($counter % $items_per_slide == 0 || $counter == $total_items) {
                  echo '</div></div>';
                }
                $counter++;
              }
              ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#spaCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#spaCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
        </div>
      </div>
    </div><!-- End Spa Tab -->

    <!-- BEYOND KL TAB -->
    <div class="map-tab-pane" data-tab="beyondkl">
      <div class="container">
        <div class="explorekl-sub-tabs" id="beyondKLSubTabs" data-tab-bar="beyondkl">
          <button class="map-category-tab active" data-subtab="islands"><i class="bi bi-water me-2"></i> Islands</button>
          <button class="map-category-tab" data-subtab="hillstation"><i class="bi bi-triangle me-2"></i> Hill Station</button>
          <button class="map-category-tab" data-subtab="waterfall"><i class="bi bi-droplet-half me-2"></i> Waterfall</button>
          <button class="map-category-tab" data-subtab="hiking"><i class="bi bi-signpost-2 me-2"></i> Hiking</button>
          <button class="map-category-tab" data-subtab="extreme"><i class="bi bi-lightning-charge me-2"></i> Extreme Sports</button>
        </div>
      </div>
      <?php
      mapExploreSubtab($db, [
        'key' => 'islands', 'table' => 'beyondkl_i', 'prefix' => 'beyondkl_i',
        'imgPath' => 'assets/img/beyondkl/i/', 'heading' => 'Islands',
        'mapQuery' => 'islands near Kuala Lumpur Malaysia', 'icon' => 'bi-water',
        'decode' => true, 'active' => true, 'linkLabelText' => 'View on Google Maps',
      ]);
      mapExploreSubtab($db, [
        'key' => 'hillstation', 'table' => 'beyondkl_hs', 'prefix' => 'beyondkl_hs',
        'imgPath' => 'assets/img/beyondkl/hs/', 'heading' => 'Hill Station',
        'mapQuery' => 'hill stations near Kuala Lumpur Malaysia', 'icon' => 'bi-triangle',
        'decode' => true, 'linkLabelText' => 'View on Google Maps',
      ]);
      mapExploreSubtab($db, [
        'key' => 'waterfall', 'table' => 'beyondkl_w', 'prefix' => 'beyondkl_w',
        'imgPath' => 'assets/img/beyondkl/w/', 'heading' => 'Waterfall',
        'mapQuery' => 'waterfalls near Kuala Lumpur Malaysia', 'icon' => 'bi-droplet-half',
        'decode' => true, 'linkLabelText' => 'View on Google Maps',
      ]);
      mapExploreSubtab($db, [
        'key' => 'hiking', 'table' => 'beyondkl_h', 'prefix' => 'beyondkl_h',
        'imgPath' => 'assets/img/beyondkl/h/', 'heading' => 'Hiking',
        'mapQuery' => 'hiking trails near Kuala Lumpur Malaysia', 'icon' => 'bi-signpost-2',
        'decode' => true, 'linkLabelText' => 'View on Google Maps',
      ]);
      mapExploreSubtab($db, [
        'key' => 'extreme', 'table' => 'beyondkl_es', 'prefix' => 'beyondkl_es',
        'imgPath' => 'assets/img/beyondkl/es/', 'heading' => 'Extreme Sports',
        'mapQuery' => 'extreme sports Selangor Malaysia', 'icon' => 'bi-lightning-charge',
        'decode' => true, 'linkLabelText' => 'View on Google Maps',
      ]);
      ?>
    </div><!-- End Beyond KL Tab -->

    <!-- MEDICAL TOURISM TAB -->
    <div class="map-tab-pane" data-tab="medicaltourism">
      <div class="container">
        <div class="explorekl-sub-tabs" id="medicalSubTabs" data-tab-bar="medicaltourism">
          <button class="map-category-tab active" data-subtab="healthcare"><i class="bi bi-hospital me-2"></i> Healthcare</button>
          <button class="map-category-tab" data-subtab="dental"><i class="bi bi-emoji-smile me-2"></i> Dental</button>
          <button class="map-category-tab" data-subtab="dermatologist"><i class="bi bi-flower1 me-2"></i> Dermatologist</button>
          <button class="map-category-tab" data-subtab="ophthalmologist"><i class="bi bi-eye-fill me-2"></i> Ophthalmologist</button>
          <button class="map-category-tab" data-subtab="plasticsurgery"><i class="bi bi-bandaid me-2"></i> Plastic Surgery</button>
        </div>
      </div>
      <?php
      mapExploreSubtab($db, [
        'key' => 'healthcare', 'table' => 'medical_tourism_hc', 'prefix' => 'medical_tourism_hc',
        'imgPath' => 'assets/img/medical_tourism/hc/', 'heading' => 'Healthcare',
        'mapQuery' => 'hospitals Kuala Lumpur', 'icon' => 'bi-hospital',
        'decode' => true, 'active' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'dental', 'table' => 'medical_tourism_dtl', 'prefix' => 'medical_tourism_dtl',
        'imgPath' => 'assets/img/medical_tourism/dtl/', 'heading' => 'Dental',
        'mapQuery' => 'dental clinics Kuala Lumpur', 'icon' => 'bi-emoji-smile',
        'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'dermatologist', 'table' => 'medical_tourism_der', 'prefix' => 'medical_tourism_der',
        'imgPath' => 'assets/img/medical_tourism/der/', 'heading' => 'Dermatologist',
        'mapQuery' => 'dermatology clinics Kuala Lumpur', 'icon' => 'bi-flower1',
        'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'ophthalmologist', 'table' => 'medical_tourism_oph', 'prefix' => 'medical_tourism_oph',
        'imgPath' => 'assets/img/medical_tourism/oph/', 'heading' => 'Ophthalmologist',
        'mapQuery' => 'eye specialist Kuala Lumpur', 'icon' => 'bi-eye-fill',
        'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'plasticsurgery', 'table' => 'medical_tourism_ps', 'prefix' => 'medical_tourism_ps',
        'imgPath' => 'assets/img/medical_tourism/ps/', 'heading' => 'Plastic Surgery',
        'mapQuery' => 'plastic surgery clinics Kuala Lumpur', 'icon' => 'bi-bandaid',
        'decode' => true,
      ]);
      ?>
    </div><!-- End Medical Tourism Tab -->

    <!-- SHOP LIKE LOCAL TAB (single-section; no sub-tab bar) -->
    <div class="map-tab-pane" data-tab="shopping">
      <?php
      mapExploreSubtab($db, [
        'key' => 'shop', 'table' => 'place_shop', 'prefix' => 'place_shop',
        'imgPath' => 'assets/img/place_to_shop/', 'heading' => 'Shop Like Local',
        'mapQuery' => 'shopping malls Kuala Lumpur', 'icon' => 'bi-bag-heart',
        'decode' => true, 'active' => true,
      ]);
      ?>
    </div><!-- End Shop Tab -->

    <!-- PLACES TO STAY TAB -->
    <div class="map-tab-pane" data-tab="accommodation">
      <div class="container">
        <div class="explorekl-sub-tabs" id="accommodationSubTabs" data-tab-bar="accommodation">
          <button class="map-category-tab active" data-subtab="topstay"><i class="bi bi-geo-alt me-2"></i> Top Places To Stay</button>
          <button class="map-category-tab" data-subtab="hotels"><i class="bi bi-building me-2"></i> Hotels</button>
          <button class="map-category-tab" data-subtab="budgethotels"><i class="bi bi-house-door me-2"></i> Budget Hotels</button>
          <button class="map-category-tab" data-subtab="backpackers"><i class="bi bi-backpack me-2"></i> Backpackers Lodge</button>
        </div>
      </div>
      <?php
      mapExploreSubtab($db, [
        'key' => 'topstay', 'table' => 'accommodation_top', 'prefix' => 'accommodation_top',
        'imgPath' => 'assets/img/accommodation/top/', 'heading' => 'Top Places To Stay',
        'mapQuery' => 'tourist neighbourhoods Kuala Lumpur', 'icon' => 'bi-geo-alt',
        'decode' => true, 'active' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'hotels', 'table' => 'accommodation_h', 'prefix' => 'accommodation_h',
        'imgPath' => 'assets/img/accommodation/h/', 'heading' => 'Hotels',
        'mapQuery' => 'hotels Kuala Lumpur', 'icon' => 'bi-building',
        'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'budgethotels', 'table' => 'accommodation_bh', 'prefix' => 'accommodation_bh',
        'imgPath' => 'assets/img/accommodation/bh/', 'heading' => 'Budget Hotels',
        'mapQuery' => 'budget hotels Kuala Lumpur', 'icon' => 'bi-house-door',
        'decode' => true,
      ]);
      mapExploreSubtab($db, [
        'key' => 'backpackers', 'table' => 'accommodation_bks', 'prefix' => 'accommodation_bks',
        'imgPath' => 'assets/img/accommodation/bks/', 'heading' => 'Backpackers Lodge',
        'mapQuery' => 'backpacker hostels Kuala Lumpur', 'icon' => 'bi-backpack',
        'decode' => true,
      ]);
      ?>
    </div><!-- End Places to Stay Tab -->


  </main>

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
    (function() {
      'use strict';

      // Get URL parameter
      function getUrlParam(param) {
        const searchParams = new URLSearchParams(window.location.search);
        return searchParams.get(param);
      }

      // Build Google Maps URL
      function buildMapUrl(query, zoom = 13) {
        return `https://maps.google.com/maps?q=${encodeURIComponent(query)}&t=&z=${zoom}&ie=UTF8&iwloc=&output=embed`;
      }

      // Check if a location was passed via URL
      const locationParam = getUrlParam('q');

      // Setup map interactivity
      function setupMapChips(mapId, chipBarId) {
        const mapFrame = document.getElementById(mapId);
        const chipBar = document.getElementById(chipBarId);

        if (!mapFrame || !chipBar) return;

        const chips = chipBar.querySelectorAll('.landmark-chip');

        chips.forEach(chip => {
          chip.addEventListener('click', function() {
            chips.forEach(c => c.classList.remove('is-active'));
            this.classList.add('is-active');

            const query = this.dataset.query || this.textContent.trim();
            const zoom = this.dataset.zoom || 13;

            mapFrame.src = buildMapUrl(query, zoom);
          });
        });

        // If location passed via URL, load it immediately
        if (locationParam && mapId === 'klMap') {
          mapFrame.src = buildMapUrl(locationParam, 15);
        }
      }

      // Setup all map interactions
      setupMapChips('klMap', 'chipBar');
      setupMapChips('klMap-wtd', 'chipBar-wtd');
      setupMapChips('klMap-historical', 'chipBar-historical');
      setupMapChips('klMap-worship', 'chipBar-worship');
      setupMapChips('klMap-food', 'chipBar-food');
      setupMapChips('klMap-nightlife', 'chipBar-nightlife');
      setupMapChips('klMap-kids', 'chipBar-kids');
      setupMapChips('klMap-islands', 'chipBar-islands');
      setupMapChips('klMap-hillstation', 'chipBar-hillstation');
      setupMapChips('klMap-waterfall', 'chipBar-waterfall');
      setupMapChips('klMap-hiking', 'chipBar-hiking');
      setupMapChips('klMap-extreme', 'chipBar-extreme');
      setupMapChips('klMap-healthcare', 'chipBar-healthcare');
      setupMapChips('klMap-dental', 'chipBar-dental');
      setupMapChips('klMap-dermatologist', 'chipBar-dermatologist');
      setupMapChips('klMap-ophthalmologist', 'chipBar-ophthalmologist');
      setupMapChips('klMap-plasticsurgery', 'chipBar-plasticsurgery');
      setupMapChips('klMap-shop', 'chipBar-shop');
      setupMapChips('klMap-topstay', 'chipBar-topstay');
      setupMapChips('klMap-hotels', 'chipBar-hotels');
      setupMapChips('klMap-budgethotels', 'chipBar-budgethotels');
      setupMapChips('klMap-backpackers', 'chipBar-backpackers');
      setupMapChips('klMap-spa', 'chipBar-spa');

      // Tab switching with animation
      const tabButtons = document.querySelectorAll('.map-category-tab[data-tab]');
      const tabPanes = document.querySelectorAll('.map-tab-pane');

      tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          const tabName = this.dataset.tab;

          tabButtons.forEach(b => b.classList.remove('active'));
          tabPanes.forEach(p => p.classList.remove('active'));

          this.classList.add('active');
          const pane = document.querySelector(`.map-tab-pane[data-tab="${tabName}"]`);
          pane.classList.add('active');

          // Show the sub-tab bar that belongs to the active top tab (if any)
          document.querySelectorAll('.explorekl-sub-tabs').forEach(bar => bar.classList.remove('active'));
          const subBar = document.querySelector('.explorekl-sub-tabs[data-tab-bar="' + tabName + '"]');
          if (subBar) subBar.classList.add('active');

          if (window.AOS) AOS.refresh();
        });
      });

      // Sub-tab switching, scoped to the current category pane so Explore KL and
      // Beyond KL (which share the .explorekl-subtab markup) don't affect each other
      const subTabButtons = document.querySelectorAll('.map-category-tab[data-subtab]');

      subTabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          const subtabName = this.dataset.subtab;
          const scope = this.closest('.map-tab-pane');
          if (!scope) return;

          scope.querySelectorAll('.map-category-tab[data-subtab]').forEach(b => b.classList.remove('active'));
          scope.querySelectorAll('.explorekl-subtab').forEach(p => p.classList.remove('active'));

          this.classList.add('active');
          const pane = scope.querySelector(`.explorekl-subtab[data-subtab="${subtabName}"]`);
          if (pane) pane.classList.add('active');

          if (window.AOS) AOS.refresh();
        });
      });

      // Landmark chip interactions
      function setupChipbar(chipBarId) {
        const chipBar = document.getElementById(chipBarId);
        if (!chipBar) return;

        const chips = chipBar.querySelectorAll('.landmark-chip');
        chips.forEach(chip => {
          chip.addEventListener('click', function() {
            chips.forEach(c => c.classList.remove('is-active'));
            this.classList.add('is-active');
          });
        });
      }

      setupChipbar('chipBar');
      setupChipbar('chipBar-wtd');
      setupChipbar('chipBar-historical');
      setupChipbar('chipBar-worship');
      setupChipbar('chipBar-food');
      setupChipbar('chipBar-nightlife');
      setupChipbar('chipBar-kids');
      setupChipbar('chipBar-islands');
      setupChipbar('chipBar-hillstation');
      setupChipbar('chipBar-waterfall');
      setupChipbar('chipBar-hiking');
      setupChipbar('chipBar-extreme');
      setupChipbar('chipBar-healthcare');
      setupChipbar('chipBar-dental');
      setupChipbar('chipBar-dermatologist');
      setupChipbar('chipBar-ophthalmologist');
      setupChipbar('chipBar-plasticsurgery');
      setupChipbar('chipBar-shop');
      setupChipbar('chipBar-topstay');
      setupChipbar('chipBar-hotels');
      setupChipbar('chipBar-budgethotels');
      setupChipbar('chipBar-backpackers');
      setupChipbar('chipBar-spa');

    })();
  </script>

</body>
</html>
