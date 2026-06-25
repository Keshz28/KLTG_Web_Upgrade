<?php
/* ============================================================================
 *                                ebook.php
 *
 *   Public page — e-book listing. Links to ebook-details.php.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('admin/functions.php');

// Categories are managed from the admin dashboard (ebook_category table).
// ebook_categories() falls back to the original hard-coded list if the table
// hasn't been created yet, so this page never breaks.
$categories = ebook_categories($db, true);

// kltg shows only the two most recent published years
// (publication year is derived from ebook_datetime — there is no ebook_year_published column)
$kltgQuery = "SELECT e.* FROM ebook e
              JOIN (
                  SELECT DISTINCT YEAR(ebook_datetime) AS yr FROM ebook
                  WHERE ebook_category = 'kltg'
                  ORDER BY yr DESC LIMIT 2
              ) AS y ON YEAR(e.ebook_datetime) = y.yr
              WHERE e.ebook_category = 'kltg'
              ORDER BY e.ebook_id DESC";

$ebooks = [];
foreach ($categories as $cat => $info) {
    $q = ($cat === 'kltg')
        ? $kltgQuery
        : "SELECT * FROM ebook WHERE ebook_category='$cat' ORDER BY ebook_id DESC";
    $result = mysqli_query($db, $q);
    $ebooks[$cat] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ebooks[$cat][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - E-book</title>
  <link rel="canonical" href="https://www.kltheguide.com.my/ebook.php" />

  <meta name="description"
    content="The fastest guide to Kuala Lumpur. A guide to the essentials of Kuala Lumpur that helps you hit the ground running on your trip. A practical e-book of things to do and see in Kuala Lumpur, Malaysia.">
  <meta name="keywords" content="Ebook, KL The Guide Tawau Uzbekistan Kazakhstan QR Code Ebook">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/ebook.php" />
  <meta property="og:title" content="KL The Guide - E-book" />
  <meta property="og:description"
    content="The fastest guide to Kuala Lumpur. A guide to the essentials of Kuala Lumpur that helps you hit the ground running on your trip. A practical e-book of things to do and see in Kuala Lumpur, Malaysia." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseoebook.jpeg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/ebook.php" />
  <meta property="twitter:title" content="KL The Guide - E-book" />
  <meta property="twitter:description"
    content="The fastest guide to Kuala Lumpur. A guide to the essentials of Kuala Lumpur that helps you hit the ground running on your trip. A practical e-book of things to do and see in Kuala Lumpur, Malaysia." />
  <meta property="twitter:image" content="assets/img/kltgseoebook.jpeg" />

  <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

  <?php include 'header.php'; ?>

  <style>
    #ebook .section-header h2 {
      font-family: 'Carter One', cursive;
      color: #1520A6;
      font-weight: 700;
      font-size: clamp(2.6rem, 5.5vw, 4rem);
      line-height: 1.15;
    }

    /* ── Tab navigation ── */
    #ebookTabs {
      flex-wrap: wrap;
      justify-content: center;
      gap: 8px;
      padding-bottom: 6px;
    }
    #ebookTabs .nav-link {
      white-space: nowrap;
      border-radius: 50px;
      padding: 7px 20px;
      font-size: 0.85rem;
      font-weight: 500;
      color: #555;
      background: #f1f3f5;
      border: 1.5px solid transparent;
      transition: all .2s;
    }
    #ebookTabs .nav-link:hover:not(.active) {
      border-color: var(--color-primary);
      color: var(--color-primary);
      background: #fff;
    }
    #ebookTabs .nav-link.active {
      background: var(--color-primary);
      color: #fff;
    }

    /* ── Ebook card ── */
    .ebook-card {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 2px 14px rgba(0, 0, 0, 0.08);
      transition: transform .25s, box-shadow .25s;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .ebook-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.14);
    }
    .ebook-card img {
      width: 100%;
      height: auto;
      display: block;
    }
    /* PDF cover: render the first page in the same slot as an image cover */
    .ebook-cover-pdf {
      width: 100%;
      aspect-ratio: 3 / 4;
      background: #f1f3f5;
      overflow: hidden;
    }
    .ebook-cover-pdf embed {
      width: 100%;
      height: 100%;
      border: 0;
      display: block;
      pointer-events: none; /* let clicks fall through to the card */
    }
    .ebook-card-body {
      padding: 14px 16px 16px;
      text-align: center;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 10px;
    }
    .ebook-card-body h5 {
      font-size: 0.88rem;
      font-weight: 600;
      line-height: 1.4;
      margin: 0;
      color: #222;
    }
    .ebook-actions {
      display: flex;
      gap: 8px;
      justify-content: center;
    }
    .ebook-actions a {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 16px;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 500;
      text-decoration: none;
      transition: all .2s;
    }
    .btn-read {
      background: var(--color-primary);
      color: #fff !important;
    }
    .btn-read:hover { opacity: .85; }
    .btn-dl {
      border: 1.5px solid var(--color-primary);
      color: var(--color-primary) !important;
      padding: 5px 12px;
    }
    .btn-dl:hover {
      background: var(--color-primary);
      color: #fff !important;
    }

    /* ── Category description ── */
    .category-desc {
      color: #666;
      max-width: 700px;
      margin: 0 auto 28px;
      text-align: center;
      line-height: 1.75;
      font-size: 0.95rem;
    }

    /* ── Empty state ── */
    .ebook-empty {
      text-align: center;
      padding: 48px 0;
      color: #aaa;
    }
    .ebook-empty i { font-size: 2.5rem; display: block; margin-bottom: 12px; }
  </style>
</head>

<body>

  <?php include 'nav.php'; ?>

  <main id="ebook">

    <section class="team" style="margin-top:76px;">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>KL The Guide</h2>
          <p>Browse our growing library of travel eBooks. Pick a destination below to get started.</p>
        </div>

        <!-- Category pill tabs -->
        <div class="mb-4">
          <ul class="nav nav-pills" id="ebookTabs" role="tablist">
            <?php $first = true; foreach ($categories as $cat => $info): ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link <?= $first ? 'active' : '' ?>"
                      id="tab-<?= $cat ?>"
                      data-bs-toggle="pill"
                      data-bs-target="#pane-<?= $cat ?>"
                      type="button" role="tab"
                      aria-controls="pane-<?= $cat ?>"
                      aria-selected="<?= $first ? 'true' : 'false' ?>">
                <?= htmlspecialchars($info['title']) ?>
              </button>
            </li>
            <?php $first = false; endforeach; ?>
          </ul>
        </div>

        <!-- Tab panels -->
        <div class="tab-content" id="ebookTabsContent">
          <?php $first = true; foreach ($categories as $cat => $info): ?>
          <div class="tab-pane fade <?= $first ? 'show active' : '' ?>"
               id="pane-<?= $cat ?>"
               role="tabpanel"
               aria-labelledby="tab-<?= $cat ?>">

            <p class="category-desc"><?= htmlspecialchars($info['desc']) ?></p>

            <?php if (empty($ebooks[$cat])): ?>
              <div class="ebook-empty">
                <i class="bi bi-book"></i>
                No eBooks available yet — check back soon!
              </div>
            <?php else: ?>
              <div class="row gy-4 gx-3 justify-content-center">
                <?php foreach ($ebooks[$cat] as $row): ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                  <div class="ebook-card">
                    <?php
                    $coverPath = 'assets/img/ebook/' . rawurlencode($row['ebook_category']) . '/' . rawurlencode($row['ebook_image']);
                    $coverIsPdf = strtolower(pathinfo($row['ebook_image'], PATHINFO_EXTENSION)) === 'pdf';
                    ?>
                    <?php if ($coverIsPdf): ?>
                      <div class="ebook-cover-pdf">
                        <embed src="<?= htmlspecialchars($coverPath) ?>#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                               type="application/pdf">
                      </div>
                    <?php else: ?>
                      <img src="<?= htmlspecialchars($coverPath) ?>"
                           alt="<?= htmlspecialchars($row['ebook_name']) ?>"
                           loading="lazy">
                    <?php endif; ?>
                    <div class="ebook-card-body">
                      <h5><?= htmlspecialchars($row['ebook_name']) ?></h5>
                      <div class="ebook-actions">
                        <?php if (!$row['ebook_url']): ?>
                          <?php if ($row['ebook_filename']): ?>
                            <a href="ebook-details.php?id=<?= $row['ebook_id'] ?>" class="btn-read">
                              <i class="bi bi-book"></i> Read
                            </a>
                          <?php endif; ?>
                        <?php else: ?>
                          <a href="<?= htmlspecialchars($row['ebook_url']) ?>" class="btn-read">
                            <i class="bi bi-book"></i> Read
                          </a>
                        <?php endif; ?>
                        <?php if ($row['ebook_filename']): ?>
                          <a href="assets/pdf/ebook/<?= htmlspecialchars($row['ebook_category']) ?>/<?= htmlspecialchars($row['ebook_filename']) ?>"
                             download class="btn-dl" title="Download"
                             onclick="trackEbookDownload(<?= (int)$row['ebook_id'] ?>)">
                            <i class="bi bi-download"></i>
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

          </div>
          <?php $first = false; endforeach; ?>
        </div>

      </div>
    </section>

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
    // Records an e-book download without delaying the file download itself.
    // sendBeacon fires reliably even as the browser starts the download.
    function trackEbookDownload(id) {
      try {
        var data = new FormData();
        data.append('id', id);
        navigator.sendBeacon('ebook-track-download.php', data);
      } catch (e) { /* tracking is best-effort; never block the download */ }
    }
  </script>

</body>

</html>
