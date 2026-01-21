<?php
include('admin/functions.php');

$query = "SELECT * FROM event ";
$result = mysqli_query($db, $query);

// Get the selected year and month
$currentYear = date('Y'); // Current year
$currentMonth = date('n'); // Current month (1-12)
$currentCategory = 'All'; // Default category

if (isset($_GET['year'])) {
    $currentYear = $_GET['year'];
}

if (isset($_GET['month'])) {
    $currentMonth = $_GET['month'];
}

if (isset($_GET['category'])) {
    $currentCategory = $_GET['category'];
}

// Fetch events for the selected year, month, and category
$query = "SELECT * FROM `event` WHERE `event_year` = $currentYear AND `event_month` = $currentMonth";

if ($currentCategory !== 'All') {
    $query .= " AND `event_category` = '$currentCategory'";
}

$result = $db->query($query);

// Initialize event list array
$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

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

    return $months[$index];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>KL The Guide - Kuala Lumpur's Upcoming Highlights</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="https://kltheguide.com.my/event.php">

    <meta name="description" content="Bluedale Publishing is dedicated to helping people make the most of their open-ended travel experiences, so we feel a deep sense of responsibility and privilege when we help someone create their own stories.">
    <meta name="keywords" content="Events, events, event, Bluedale Publishing, Bluedale, BGOC, travel, tourism, Malaysia, 
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

    <!-- Fonts (These are render-blocking if not preloaded, but often necessary early) -->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/espoir-serif-free" rel="stylesheet">

    <!-- Preload Non-Critical CSS (style.css, aos.css if used here) -->
    <link rel="preload" href="assets/css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="assets/css/style.css"></noscript>
    <!-- Preload other non-critical CSS files if they exist in the head and are not critical -->
    <!-- Example for aos.css if used on this page: -->
    <!-- <link rel="preload" href="assets/vendor/aos/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->
    <!-- <noscript><link rel="stylesheet" href="assets/vendor/aos/aos.css"></noscript> -->

    <style>
        /* --- INLINE YOUR CRITICAL CSS HERE --- */
        /* Example placeholder - Replace this with the actual critical CSS for this page */
        body { font-family: "Muli", sans-serif; margin: 0; padding: 0; color: #333; } /* Adjust based on your actual critical font/css */
        /* Add other critical rules needed for the initial render */
        /* Add critical styles for .top-section, .pagetitle, .sub-title, .container-nav, .btn-nav, etc. */
        .top-section { background-size: cover; padding: 60px 0; text-align: center; } /* Example critical style */
        .pagetitle { font-size: 2.5rem; margin-bottom: 10px; } /* Example critical style */
        .sub-title { font-size: 1.2rem; margin-bottom: 20px; } /* Example critical style */
        /* Add more critical styles as needed */
    </style>


    <!-- CSS Links (These are render-blocking, so ensure they are preloaded above or are critical) -->
    <!-- Remove the direct stylesheet link for style.css and any other non-critical CSS loaded here -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/style.css"> -->

    <!-- Third-party CSS that might be non-critical -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <?php include 'header.php'; ?>
    <?php include 'assets/css/highlights.php'; ?> <!-- Assuming this outputs inline styles or a critical CSS block, keep it if critical -->

</head>

<body id="event">
    <!-- Header -->
    <?php include 'nav.php'; ?>

    <main id="main-event">
        <div class="top-section top-bg">
            <h1 class="pagetitle">Monthly Highlights</h1>
            <h2 class="sub-title">Discover upcoming events in Kuala Lumpur</h2>

            <!-- Date Navigation -->
            <div class="container-nav">
                <div class="btn-nav">
                    <div>
                        <div class="month-nav-container">
                            <button class="monthbtn-left" onclick="changeMonth(-1)"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></button>
                            <div class="month"><?= getMonthName($currentMonth) ?></div>
                            <button class="monthbtn-right" onclick="changeMonth(1)"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>

                    </div>
                    <div>
                        <div class="year-nav-container">
                            <!-- <button class="yearbtn-left" onclick="changeYear(-1)"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></button> -->
                            <div class="year"><?= $currentYear ?></div>
                            <!-- <button class="yearbtn-right" onclick="changeYear(1)"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Date Navigation -->

        <!-- Scrolling Ticker -->
        <div class="event-ticker-container">
            <a href="http://www.wasap.my/60122200622">
                <div class="ticker-title">
                    Call Us!
                </div>
            </a>
            <ul>

                <li class="ticker">
                    Call or Whatsapp Us : <a href="http://www.wasap.my/60122200622">012-220-0622</a> For Advertisement OR Event Features.
                </li>
                <li class="ticker">
                    Call or Whatsapp Us : <a href="http://www.wasap.my/60122200622">012-220-0622</a> For Advertisement OR Event Features.
                </li>
                <li class="ticker">
                    Call or Whatsapp Us : <a href="http://www.wasap.my/60122200622">012-220-0622</a> For Advertisement OR Event Features.
                </li>
                <li class="ticker">
                    Call or Whatsapp Us : <a href="http://www.wasap.my/60122200622">012-220-0622</a> For Advertisement OR Event Features.
                </li>

            </ul>
        </div>

        <!-- Navigation Container -->
        <div class="nav-card">


            <div class="nav-event">
                <ul class="custom-nav-pills" id="custom-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'All') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=All">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'Holiday') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=Holiday">Holiday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'Exhibition') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=Exhibition">Exhibition</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'Nightlife') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=Nightlife">Nightlife</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'Food') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=Food">Food</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'Happening') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=Happening">Happening</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentCategory === 'Entertainment') echo 'active'; ?>" href="?year=<?= $currentYear ?>&month=<?= $currentMonth ?>&category=Entertainment">Entertainment</a>
                    </li>
                </ul>
            </div>

            <div class="social-links" style="margin-left: auto; padding-right: 40px; font-size: 30px">
                <a href="mailto:?subject=Check Out This Article&body=https://www.kltheguide.com.my/event.php" class="instagram">
                    <i class="bi bi-share-fill" style="color:var(--color-primary)"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.kltheguide.com.my/event.php" target="_blank" class="facebook-share">
                    <i class="bi bi-facebook" style="color:#4267B2"></i>
                </a>
                <a href="https://twitter.com/share?text=Check Out This Article&url=https://www.kltheguide.com.my/event.php" class="twitter">
                    <i class="fa-brands fa-x-twitter" style="color:#1DA1F2"></i>
                </a>
                <a href="https://api.whatsapp.com/send?text=https://www.kltheguide.com.my/event.php" target="_blank" class="whatsapp-share-button">
                    <i class="bi bi-whatsapp" style="color:#25D366"></i>
                </a>
            </div>

        </div>
        <!-- End Navigation Container -->



        <!-- Content Container -->
        <div class="col-md-12">
            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-1">
                    <div class="row">
                        <section class="event-section">
                            <?php if (empty($events)): ?>
                                <div class="event-container">
                                    <div class="event-content">
                                        <h2 class="section-titlex">No Events</h2>
                                        <p>There are currently no events for this month and category.</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php
                                // Sort events by 'event_order' in descending order
                                usort($events, function ($a, $b) {
                                    return $b['event_order'] <=> $a['event_order'];
                                });

                                $index = 0;
                                ?>
                                <?php foreach ($events as $event): ?>
                                    <?php
                                    // Check if it's an odd event
                                    $isOdd = $index % 2 === 0;
                                    ?>
                                    <div class="event-container <?php echo $isOdd ? 'odd-event-container' : 'even-event-container'; ?>">
                                        <div class="event-background" style="background-image: url('assets/img/event/event-bg8.jpeg');"></div>
                                        <div class="event-contents">
                                            <?php if ($isOdd): ?>
                                                <h2 class="odd-section-title"><?= urldecode($event['event_title']) ?></h2>
                                                <div class="odd-event-content">
                                                    <div class="odd-event-image position-relative">
                                                        <!-- Image -->
                                                        <?php if ($event['event_image']): ?>
                                                            <img class="odd-event-img" src="assets/img/event/<?= $event['event_image'] ?>" alt="">
                                                        <?php else: ?>
                                                            <img class="odd-event-img" src="assets/img/event/comingsoon.png" alt="">
                                                        <?php endif; ?>
                                                        <div class="odd-event-img-caption"><?= urldecode($event['event_title']) ?></div>
                                                    </div>
                                                    <div class="odd-text-box">
                                                        <p><?= str_replace("/", "<br>", urldecode($event['event_content'])); ?></p>
                                                        <?php if ($event['event_content2']): ?>
                                                            <p class="event-content2" style="display: none;"><?= str_replace("/", "<br>", urldecode($event['event_content2'])); ?></p>
                                                            <button class="read-more-btn">Read More</button>
                                                        <?php endif; ?>
                                                        <p class="spacer"></p>
                                                        <?php if ($event['event_day']): ?>
                                                            <p>Date : <?= str_replace("/", "<br>", urldecode($event['event_day'])); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_hours']): ?>
                                                            <p>Operation Hours : <?= str_replace("/", "<br>", urldecode($event['event_hours'])); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_phone']): ?>
                                                            <p>Phone : <?= str_replace("/", "<br>", $event['event_phone']); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_website']): ?>
                                                            <p>Website : <a href="<?= urldecode($event['event_website']); ?>" target="_blank"><?= urldecode($event['event_website']); ?></a></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_location']): ?>
                                                            <p>Location :
                                                                <a href="<?= urldecode($event['event_locationurl']) ?>"><?= str_replace("/", "<br>", urldecode($event['event_location'])) ?></a>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="media-icons">
                                                    <?php if ($event['event_facebook']): ?>
                                                        <a href="<?= ($event['event_facebook']) ?>" class="event-media-icon"><i class="fab fa-facebook"></i></a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_instagram']): ?>
                                                        <a href="<?= ($event['event_instagram']) ?>" class="event-media-icon"><i class="fab fa-instagram"></i></a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_tiktok']): ?>
                                                        <a href="<?= ($event['event_tiktok']) ?>" class="event-media-icon">
                                                            <div class="tiktok-icon">
                                                                <i class="fab fa-tiktok blue"></i>
                                                                <i class="fab fa-tiktok red"></i>
                                                                <i class="fab fa-tiktok black"></i>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_youtube']): ?>
                                                        <a href="<?= ($event['event_youtube']) ?>" class="event-media-icon"><i class="fab fa-youtube"></i></a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_twitter']): ?>
                                                        <a href="<?= ($event['event_twitter']) ?>" class="event-media-icon"><i class="fab fa-twitter"></i></a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <h2 class="even-section-title"><?= urldecode($event['event_title']) ?></h2>
                                                <div class="even-event-content">
                                                    <div class="even-text-box">
                                                        <p><?= str_replace("/", "<br>", urldecode($event['event_content'])); ?></p>
                                                        <?php if ($event['event_content2']): ?>
                                                            <p class="event-content2" style="display: none;"><?= str_replace("/", "<br>", urldecode($event['event_content2'])); ?></p>
                                                            <button class="read-more-btn">Read More</button>
                                                        <?php endif; ?>
                                                        <p class="spacer"></p>
                                                        <?php if ($event['event_day']): ?>
                                                            <p>Date : <?= str_replace("/", "<br>", urldecode($event['event_day'])); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_hours']): ?>
                                                            <p>Operation Hours : <?= str_replace("/", "<br>", urldecode($event['event_hours'])); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_phone']): ?>
                                                            <p>Phone : <?= str_replace("/", "<br>", $event['event_phone']); ?></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_website']): ?>
                                                            <p>Website : <a href="<?= urldecode($event['event_website']); ?>" target="_blank"><?= urldecode($event['event_website']); ?></a></p>
                                                        <?php endif; ?>
                                                        <?php if ($event['event_location']): ?>
                                                            <p>Location : <a href="<?= urldecode($event['event_locationurl']) ?>"><?= urldecode($event['event_location']) ?></a></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="even-event-image position-relative">
                                                        <!-- Image -->
                                                        <?php if ($event['event_image']): ?>
                                                            <img class="odd-event-img" src="assets/img/event/<?= $event['event_image'] ?>" alt="">
                                                        <?php else: ?>
                                                            <img class="odd-event-img" src="assets/img/event/comingsoon.png" alt="">
                                                        <?php endif; ?>
                                                        <div class="even-event-img-caption"><?= urldecode($event['event_title']) ?></div>
                                                    </div>
                                                </div>
                                                <div class="media-icons">
                                                    <?php if ($event['event_facebook']): ?>
                                                        <a href="<?= ($event['event_facebook']) ?>" class="event-media-icon"><i class="fab fa-facebook"></i></a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_instagram']): ?>
                                                        <a href="<?= ($event['event_instagram']) ?>" class="event-media-icon"><i class="fab fa-instagram"></i></a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_tiktok']): ?>
                                                        <a href="<?= ($event['event_tiktok']) ?>" class="event-media-icon">
                                                            <div class="tiktok-icon">
                                                                <i class="fab fa-tiktok blue"></i>
                                                                <i class="fab fa-tiktok red"></i>
                                                                <i class="fab fa-tiktok black"></i>
                                                            </div>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_youtube']): ?>
                                                        <a href="<?= ($event['event_youtube']) ?>" class="event-media-icon"><i class="fab fa-youtube"></i></a>
                                                    <?php endif; ?>
                                                    <?php if ($event['event_twitter']): ?>
                                                        <a href="<?= ($event['event_twitter']) ?>" class="event-media-icon"><i class="fab fa-twitter"></i></a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php $index++; ?>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </section>
                    </div>
                </div><!-- End Tab 4 Content -->
            </div>
        </div><!-- End Container -->
        <!-- End Event Nav Bar -->

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

    <!-- Vendor JS Files (Add 'defer') -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/vendor/aos/aos.js" defer></script> <!-- Add defer if used here -->
    <script src="assets/vendor/glightbox/js/glightbox.min.js" defer></script> <!-- Add defer if used here -->
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js" defer></script> <!-- Add defer if used here -->
    <script src="assets/vendor/swiper/swiper-bundle.min.js" defer></script> <!-- Add defer if used here -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"
        integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous" defer></script> <!-- Add defer -->

    <!-- ScrollReveal JS (Add 'defer' or move to bottom) -->
    <script src="https://unpkg.com/scrollreveal" defer></script>


    <!-- Template Main JS File (Add 'defer') -->
    <script src="assets/js/main.js" defer></script> <!-- Add defer -->

    <!-- Inline Scripts (Place these at the end of the body) -->
    <script>
        function changeYear(offset) {
            const currentYearElement = document.querySelector('.year');
            let currentYear = parseInt(currentYearElement.textContent);
            currentYear += offset;
            currentYearElement.textContent = currentYear;

            // Reload page with updated year and category
            const category = getCurrentCategory();
            window.location.href = `?year=${currentYear}&month=<?= $currentMonth ?>&category=${category}`;
        }

        function changeMonth(offset) {
            let currentYear = <?= $currentYear ?>;
            let currentMonth = <?= $currentMonth ?> + offset;

            // Handle month overflow
            if (currentMonth < 1) {
                currentMonth = 12; // December
                currentYear -= 1;
            } else if (currentMonth > 12) {
                currentMonth = 1; // January
                currentYear += 1;
            }

            const targetDate = new Date(currentYear, currentMonth - 1); // JavaScript months are 0-11
            const currentDate = new Date();
            const threeMonthsAgo = new Date(currentDate.getFullYear(), currentDate.getMonth() - 3);

            // Prevent navigating to more than 3 months old
            if (targetDate < threeMonthsAgo) {
                alert("You cannot go to a month more than 3 months older than the current date.");
                return;
            }

            // Reload page with updated month and category
            const category = getCurrentCategory();
            window.location.href = `?year=${currentYear}&month=${currentMonth}&category=${category}`;
        }

        function getCurrentCategory() {
            const currentCategoryElement = document.querySelector('.nav-link.active');
            if (currentCategoryElement) {
                return currentCategoryElement.textContent.trim();
            }
            return 'All'; // Default to All if no category is selected
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const readMoreButtons = document.querySelectorAll('.read-more-btn');

            readMoreButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const eventContent2 = this.previousElementSibling;

                    if (eventContent2.style.display === 'none' || eventContent2.style.display === '') {
                        eventContent2.style.display = 'block';
                        this.textContent = 'Read Less';
                    } else {
                        eventContent2.style.display = 'none';
                        this.textContent = 'Read Less'; // Fixed: should be 'Read Less' when showing less
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ticker = document.querySelector('.event-ticker-container');
            var header = document.querySelector('.header');
            var headerHeight = header ? header.offsetHeight : 0; // Get the height of the header

            var stickyOffset = ticker.offsetTop; // Adjust offset considering the header height

            window.addEventListener('scroll', function() {
                if (window.scrollY >= stickyOffset) {
                    ticker.classList.add('sticky');
                } else {
                    ticker.classList.remove('sticky');
                }
            });
        });
    </script>
</body>

</html>