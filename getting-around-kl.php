<?php include('admin/functions.php');
$query = "SELECT tile1_title1, tile1_title2, tile1_title3 FROM indexpage";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_assoc($result);
$tile1_title1 = $row['tile1_title1'];
$tile1_title2 = $row['tile1_title2'];
$tile1_title3 = $row['tile1_title3'];

$transports = [
  ['name' => 'Light Rail Transit (LRT)', 'img' => 'asset-backups/LRT_Logo.jpg'],
  ['name' => 'Mass Rapid Transit (MRT)', 'img' => 'asset-backups/MRT_Logo.jpg'],
  ['name' => 'KTM Komuter',              'img' => 'asset-backups/KTM_Logo.jpg'],
  ['name' => 'KL Monorail',              'img' => 'asset-backups/Monorai_Logo.jpg'],
  ['name' => 'KLIA Express',             'img' => 'asset-backups/KLIA_Exp_Logo.png'],
  ['name' => 'MRT Bus',                  'img' => 'asset-backups/MRTBus_Logo.jpg'],
  ['name' => 'Rapid KL Bus',             'img' => 'asset-backups/RapidKL_Logo.jpg'],
  ['name' => 'GOKL Bus',                 'img' => 'asset-backups/GoKL_Logo.jpg'],
  ['name' => 'KL Hop on Hop off Bus',    'img' => 'asset-backups/KLHopOn_Logo.jpg'],
  ['name' => 'Grab Car',                 'img' => 'asset-backups/Grab_Logo.jpg'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Getting Around KL</title>

  <link rel="canonical" href="https://kltheguide.com.my/getting-around-kl.php/" />
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
      transition: border-color .25s;
    }

    .klag-tab + .klag-tab { border-left: 1px solid rgba(255,255,255,.12); }

    .klag-tab.is-active { border-bottom-color: #0ea2bd; }

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
      background-image: url('asset-backups/KLGlanceBackground.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      filter: blur(7px) brightness(0.55) saturate(1.05);
      transform: scale(1.08);
      z-index: 0;
    }

    #gak-section .gak-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(6, 12, 28, 0.55) 0%, rgba(8, 18, 48, 0.45) 100%);
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

    <a class="klag-tab is-active" href="getting-around-kl.php" aria-current="page">
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

  <main id="getting-around-kl-page">

    <section id="gak-section">
      <div class="gak-bg"></div>
      <div class="gak-overlay"></div>

      <div class="gak-wrap">
        <h2 class="gak-heading">Getting Around KL</h2>

        <div class="gak-grid">
          <?php foreach ($transports as $t): ?>
            <a class="gak-card" href="#">
              <img src="<?php echo htmlspecialchars($t['img'], ENT_QUOTES); ?>"
                   alt="<?php echo htmlspecialchars($t['name'], ENT_QUOTES); ?>"
                   loading="lazy">
              <span class="gak-card-label"><?php echo htmlspecialchars($t['name'], ENT_QUOTES); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

  </main><!-- /#getting-around-kl-page -->

  <?php include 'footer.php'; ?>

</body>
</html>
