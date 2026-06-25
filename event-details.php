<?php
/* ============================================================================
 *                            event-details.php
 *
 *   Public page — single event detail (CMS-driven).
 *   Reached from event.php cards as event-details.php?id=<event_id>.
 *
 *   Layout: poster + script-font title on the left, description and
 *   labelled Date / Hours / Phone / Website / Location on the right.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include('admin/functions.php');

// ---- Fetch the requested event -------------------------------------------
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$event = null;

if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM `event` WHERE `event_id` = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $event = $res ? $res->fetch_assoc() : null;
        $stmt->close();
    }
}

// ---- Helpers --------------------------------------------------------------
function ev_monthName($index)
{
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    return $months[(int) $index] ?? '';
}

// Add a scheme to bare URLs so links work from any page.
function ev_url($raw)
{
    $raw = trim(urldecode($raw));
    if ($raw === '') return '';
    return preg_match('#^https?://#i', $raw) ? $raw : 'https://' . $raw;
}

// ---- Build the view-model (only when an event was found) ------------------
if ($event) {
    $title    = urldecode($event['event_title']);
    $category = $event['event_category'];
    $imgFile  = $event['event_image']
        ? 'assets/img/event/' . $event['event_image']
        : 'assets/img/event/comingsoon.png';

    // Date: "<day> <Month> <Year>"
    $dayPart = trim(str_replace('/', ', ', urldecode($event['event_day'])));
    $dateStr = trim($dayPart . ' ' . ev_monthName($event['event_month']) . ' ' . $event['event_year']);

    $hoursStr = trim(str_replace('/', ', ', urldecode($event['event_hours'])));
    $phoneStr = trim(str_replace('/', ', ', $event['event_phone']));
    $locStr   = trim(str_replace('/', ', ', urldecode($event['event_location'])));
    $locUrl   = trim(urldecode($event['event_locationurl']));
    $webRaw   = trim(urldecode($event['event_website']));

    // Description (content + content2). "/" is the editors' line-break marker.
    $descParts = [];
    if (trim($event['event_content']) !== '') {
        $descParts[] = str_replace('/', '<br>', htmlspecialchars(urldecode($event['event_content'])));
    }
    if (trim($event['event_content2']) !== '') {
        $descParts[] = str_replace('/', '<br>', htmlspecialchars(urldecode($event['event_content2'])));
    }
    $descHtml = implode('<br><br>', $descParts);

    // Meta description for SEO/social (plain text, trimmed).
    $metaDesc = trim(preg_replace('/\s+/', ' ', strip_tags(urldecode($event['event_content']))));
    if ($metaDesc === '') {
        $metaDesc = 'Event details on KL The Guide.';
    } elseif (function_exists('mb_substr') && mb_strlen($metaDesc) > 160) {
        $metaDesc = mb_substr($metaDesc, 0, 157) . '…';
    }

    $ogImage  = 'https://www.kltheguide.com.my/' . $imgFile;
    $pageUrl  = 'https://www.kltheguide.com.my/event-details.php?id=' . $id;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php if ($event): ?>
    <title>KL The Guide - <?= htmlspecialchars($title) ?></title>

    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($pageUrl) ?>" />
    <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?= htmlspecialchars($pageUrl) ?>" />
    <meta property="og:title" content="<?= htmlspecialchars($title) ?> | KL The Guide" />
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc) ?>" />
    <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="<?= htmlspecialchars($pageUrl) ?>" />
    <meta property="twitter:title" content="<?= htmlspecialchars($title) ?> | KL The Guide" />
    <meta property="twitter:description" content="<?= htmlspecialchars($metaDesc) ?>" />
    <meta property="twitter:image" content="<?= htmlspecialchars($ogImage) ?>" />
<?php else: ?>
    <title>KL The Guide - Event Not Found</title>
    <meta name="robots" content="noindex, follow">
<?php endif; ?>

    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

    <?php include 'header.php'; ?>

    <!-- Event detail styles (scoped under #event-detail) -->
    <link rel="stylesheet" href="assets/css/event-details.css">
</head>

<body id="event-detail-page">
    <!-- Header / nav -->
    <?php include 'nav.php'; ?>

    <main id="event-detail">
        <div class="evd-wrap">

            <a href="event.php" class="evd-back"><i class="bi bi-arrow-left"></i> Back to events</a>

            <?php if (!$event): ?>
                <div class="evd-missing">
                    <i class="bi bi-calendar-x"></i>
                    <h2>Event not found</h2>
                    <p>The event you're looking for doesn't exist or may have been removed.</p>
                    <a href="event.php" class="btn">Browse all events</a>
                </div>
            <?php else: ?>
                <div class="evd-grid">

                    <!-- Left: poster + title -->
                    <div class="evd-left">
                        <div class="evd-poster">
                            <?php if ($category): ?>
                                <span class="evd-chip"><?= htmlspecialchars($category) ?></span>
                            <?php endif; ?>
                            <img src="<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($title) ?>"
                                onerror="this.src='assets/img/event/comingsoon.png';">
                        </div>
                        <h1 class="evd-title"><?= htmlspecialchars($title) ?></h1>
                    </div>

                    <!-- Right: details -->
                    <div class="evd-right">
                        <div class="evd-card">

                            <?php if ($descHtml !== ''): ?>
                                <div class="evd-desc"><?= $descHtml ?></div>
                            <?php endif; ?>

                            <div class="evd-meta">
                                <?php if ($dateStr !== ''): ?>
                                    <div class="evd-row"><span class="evd-label">Date :</span> <?= htmlspecialchars($dateStr) ?></div>
                                <?php endif; ?>

                                <?php if ($hoursStr !== ''): ?>
                                    <div class="evd-row"><span class="evd-label">Hours :</span> <?= htmlspecialchars($hoursStr) ?></div>
                                <?php endif; ?>

                                <?php if ($phoneStr !== ''): ?>
                                    <div class="evd-row"><span class="evd-label">Phone :</span> <?= htmlspecialchars($phoneStr) ?></div>
                                <?php endif; ?>

                                <?php if ($webRaw !== ''): ?>
                                    <div class="evd-row"><span class="evd-label">Website :</span>
                                        <a href="<?= htmlspecialchars(ev_url($webRaw)) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($webRaw) ?></a>
                                    </div>
                                <?php endif; ?>

                                <?php if ($locStr !== ''): ?>
                                    <div class="evd-row"><span class="evd-label">Location :</span>
                                        <?php if ($locUrl !== ''): ?>
                                            <a href="<?= htmlspecialchars(ev_url($locUrl)) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($locStr) ?></a>
                                        <?php else: ?>
                                            <?= htmlspecialchars($locStr) ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($event['event_facebook'] || $event['event_instagram'] || $event['event_tiktok'] || $event['event_youtube'] || $event['event_twitter']): ?>
                                <div class="evd-socials">
                                    <?php if ($event['event_facebook']): ?>
                                        <a href="<?= htmlspecialchars(ev_url($event['event_facebook'])) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                                    <?php endif; ?>
                                    <?php if ($event['event_instagram']): ?>
                                        <a href="<?= htmlspecialchars(ev_url($event['event_instagram'])) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if ($event['event_tiktok']): ?>
                                        <a href="<?= htmlspecialchars(ev_url($event['event_tiktok'])) ?>" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                                    <?php endif; ?>
                                    <?php if ($event['event_youtube']): ?>
                                        <a href="<?= htmlspecialchars(ev_url($event['event_youtube'])) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                                    <?php endif; ?>
                                    <?php if ($event['event_twitter']): ?>
                                        <a href="<?= htmlspecialchars(ev_url($event['event_twitter'])) ?>" target="_blank" rel="noopener" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            <?php endif; ?>

        </div>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Scroll Top Button -->
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/vendor/aos/aos.js" defer></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js" defer></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js" defer></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js" defer></script>
</body>

</html>
