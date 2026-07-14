<?php
/* ============================================================================
 *                                event.php
 *
 *   Public page — events listing (CMS-driven).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include('admin/functions.php');

// Get the selected year and month
$currentYear = date('Y'); // Current year
$currentMonth = date('n'); // Current month (1-12)
$currentCategory = 'All'; // Default category

if (isset($_GET['year'])) {
    $currentYear = (int) $_GET['year'];
}

if (isset($_GET['month'])) {
    $currentMonth = (int) $_GET['month'];
}

if (isset($_GET['category'])) {
    $currentCategory = $_GET['category'];
}

// Fetch events for the selected year, month, and category
$query = "SELECT * FROM `event` WHERE `event_year` = $currentYear AND `event_month` = $currentMonth";

if ($currentCategory !== 'All') {
    $safeCategory = mysqli_real_escape_string($db, $currentCategory);
    $query .= " AND `event_category` = '$safeCategory'";
}

$result = $db->query($query);

// Initialize event list array
$events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Sort events by 'event_order' descending
usort($events, function ($a, $b) {
    return $b['event_order'] <=> $a['event_order'];
});

// Function to format month names
function getMonthName($index)
{
    $months = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December"
    ];

    return $months[$index] ?? '';
}

// Category list for the filter bar
$eventCategories = ['All', 'Holiday', 'Exhibition', 'Nightlife', 'Food', 'Happening', 'Entertainment'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>KL The Guide - Kuala Lumpur's Upcoming Highlights</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="https://www.kltheguide.com.my/event.php" />

    <meta name="description" content="Discover upcoming events, exhibitions, concerts and happenings in Kuala Lumpur. KL The Guide keeps you updated on what's on across the city every month.">
    <meta name="keywords" content="Events, events, event, KL events, Kuala Lumpur events, what's on KL, Bluedale Publishing, Bluedale, BGOC, travel, tourism, Malaysia,
	KL The Guide E-Book, KLTG ebook, KL The Guide, travel guidebook, Malaysia's capital city, e-book, Kuala Lumpur, KL,
	Dataran Merdeka, Petaling Street, travel guide app, travel guide, KLCC, KL Tower, Batu Caves, Google Play Store, Apple App Store, KL The Guide, Kuala Lumpur city">

    <meta itemprop="name" content="KL The Guide - Events">
    <meta itemprop="description" content="Your go-to guide for exploring Kuala Lumpur! Stay updated with the latest events, top attractions, and exciting highlights happening in KL. Discover everything you need to know about upcoming activities and must-visit spots, all in one place.">
    <meta itemprop="image" content="https://www.kltheguide.com.my/assets/img/eventthumbnail.png">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.kltheguide.com.my/event.php" />
    <meta property="og:title" content="KL The Guide - Events" />
    <meta property="og:description" content="Your go-to guide for exploring Kuala Lumpur! Stay updated with the latest events, top attractions, and exciting highlights happening in KL. Discover everything you need to know about upcoming activities and must-visit spots, all in one place." />
    <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/eventthumbnail.png" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://www.kltheguide.com.my/event.php" />
    <meta property="twitter:title" content="KL The Guide - Events" />
    <meta property="twitter:description" content="Your go-to guide for exploring Kuala Lumpur! Stay updated with the latest events, top attractions, and exciting highlights happening in KL. Discover everything you need to know about upcoming activities and must-visit spots, all in one place." />
    <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/eventthumbnail.png" />

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "KL The Guide - Upcoming Highlights",
            "description": "Your go-to guide for exploring Kuala Lumpur! Stay updated with the latest events, top attractions, and exciting highlights happening in KL. Discover everything you need to know about upcoming activities and must-visit spots, all in one place.",
            "url": "https://www.kltheguide.com.my/event.php",
            "image": "https://www.kltheguide.com.my/assets/img/eventthumbnail.png"
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

    <?php include 'header.php'; ?>

    <!-- Events page styles (scoped under #main-event) -->
    <link rel="stylesheet" href="assets/css/event.css">

</head>

<body id="event" class="has-video-hero">
    <!-- Header -->
    <?php include 'nav.php'; ?>

    <main id="main-event">

        <!-- ======= Hero (full-bleed evening KL video) ======= -->
        <section class="ev-hero">
            <video class="ev-hero__video" autoplay muted loop playsinline
                poster="assets/img/kltgseohp.jpeg" aria-hidden="true">
                <source src="asset-backups/EveningKL.mp4" type="video/mp4">
            </video>

            <div class="ev-hero__content">
                <span class="ev-hero__eyebrow">What&rsquo;s On</span>
                <h1 class="ev-hero__title">Monthly Highlights</h1>
                <p class="ev-hero__sub">Discover the events, exhibitions and happenings lighting up Kuala Lumpur</p>

                <!-- Month navigator -->
                <div class="ev-monthnav">
                    <button class="ev-monthnav__btn" onclick="changeMonth(-1)" aria-label="Previous month">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="ev-monthnav__label">
                        <span class="ev-monthnav__month"><?= getMonthName($currentMonth) ?></span>
                        <span class="ev-monthnav__year"><?= $currentYear ?></span>
                    </div>
                    <button class="ev-monthnav__btn" onclick="changeMonth(1)" aria-label="Next month">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>
        <!-- End Hero -->

        <!-- ======= Toolbar: category filters + share ======= -->
        <div class="ev-toolbar">
            <nav class="ev-filters" aria-label="Event categories">
                <?php foreach ($eventCategories as $cat): ?>
                    <a class="ev-filter <?php if ($currentCategory === $cat) echo 'is-active'; ?>"
                        href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=<?= urlencode($cat) ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="ev-share">
                <a href="mailto:?subject=Check out KL events&body=https://www.kltheguide.com.my/event.php" aria-label="Share by email">
                    <i class="bi bi-share-fill" style="color:var(--color-primary)"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.kltheguide.com.my/event.php" target="_blank" aria-label="Share on Facebook">
                    <i class="bi bi-facebook" style="color:#4267B2"></i>
                </a>
                <a href="https://twitter.com/share?text=Check out KL events&url=https://www.kltheguide.com.my/event.php" target="_blank" aria-label="Share on X">
                    <i class="fa-brands fa-x-twitter" style="color:#000"></i>
                </a>
                <a href="https://api.whatsapp.com/send?text=https://www.kltheguide.com.my/event.php" target="_blank" aria-label="Share on WhatsApp">
                    <i class="bi bi-whatsapp" style="color:#25D366"></i>
                </a>
            </div>
        </div>

        <!-- Slim advertise bar -->
        <div class="ev-advert">
            <i class="bi bi-megaphone-fill"></i>
            Want your event featured here?
            <a href="http://www.wasap.my/60122200622" target="_blank">WhatsApp us &middot; 012-220-0622</a>
        </div>

        <!-- ======= Events ======= -->
        <section class="ev-section">
            <h2 class="ev-section__title">What&rsquo;s happening in <?= getMonthName($currentMonth) ?></h2>

            <?php if (empty($events)): ?>
                <div class="ev-empty">
                    <i class="bi bi-calendar-x"></i>
                    <h3>No events just yet</h3>
                    <p>There are no events listed for <?= getMonthName($currentMonth) ?> <?= $currentYear ?><?php if ($currentCategory !== 'All') echo ' in ' . htmlspecialchars($currentCategory); ?>. Try another month or category.</p>
                </div>
            <?php else: ?>
                <div class="ev-masonry">
                    <?php foreach ($events as $event): ?>
                        <?php
                        $title = urldecode($event['event_title']);
                        $category = $event['event_category'];
                        $imgFile = $event['event_image'] ? 'assets/img/event/' . $event['event_image'] : 'assets/img/event/comingsoon.png';
                        ?>
                        <a href="event-details.php?id=<?= (int) $event['event_id'] ?>" class="ev-card" aria-label="View details for <?= htmlspecialchars($title) ?>">
                            <img class="ev-card__img" src="<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($title) ?>" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='assets/img/event/comingsoon.png';">
                            <span class="ev-card__shade"></span>
                            <?php if ($category): ?>
                                <span class="ev-card__chip"><?= htmlspecialchars($category) ?></span>
                            <?php endif; ?>
                            <span class="ev-card__caption">
                                <span class="ev-card__title"><?= htmlspecialchars($title) ?></span>
                                <?php if ($event['event_day']): ?>
                                    <span class="ev-card__date"><i class="bi bi-calendar-event"></i><?= htmlspecialchars(str_replace('/', ' ', urldecode($event['event_day']))) ?></span>
                                <?php endif; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <!-- End Events -->

        <!-- Advertisement Section -->
        <div class="row d-flex justify-content-center btmbanner mt-4">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3696733888071014"
                crossorigin="anonymous"></script>
            <!-- Index Hero KLTG -->
            <ins class="adsbygoogle" align="center" data-ad-client="ca-pub-3696733888071014" data-ad-slot="5212427798"
                data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div><!-- End Advertisement Section -->
    </main><!-- End Main Content -->

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <!-- End Footer -->

    <!-- Scroll Top Button -->
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/vendor/aos/aos.js" defer></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js" defer></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js" defer></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js" defer></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js" defer></script>

    <!-- Month navigation -->
    <script>
        var EV_YEAR = <?= $currentYear ?>;
        var EV_MONTH = <?= $currentMonth ?>;
        var EV_CATEGORY = <?= json_encode($currentCategory) ?>;

        function changeMonth(offset) {
            var year = EV_YEAR;
            var month = EV_MONTH + offset;

            if (month < 1) { month = 12; year -= 1; }
            else if (month > 12) { month = 1; year += 1; }

            var targetDate = new Date(year, month - 1);
            var now = new Date();
            var threeMonthsAgo = new Date(now.getFullYear(), now.getMonth() - 3);

            if (targetDate < threeMonthsAgo) {
                alert("You cannot go to a month more than 3 months older than the current date.");
                return;
            }

            window.location.href = '?year=' + year + '&month=' + month + '&category=' + encodeURIComponent(EV_CATEGORY);
        }
    </script>
</body>

</html>
