<?php include('admin/functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
<style>
  .country-list {
    z-index: 9999 !important;
  }

  .carousel-item img {
    max-height: 300px;
    /* adjust as needed */
    object-fit: cover;
    width: 100%;
  }

  .hero-animated h1 {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    max-width: 100%;
  }

  .hero-animated {
    z-index: 10;
  }

  .hero-animated h1 {
    z-index: 11;
    font-size: clamp(1.5rem, 5vw, 3rem);
    line-height: 1.2;
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

  <link rel="canonical" href="https://kltheguide.com.my/">

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



</head>

<body>
  <?php include 'nav.php'; ?>
  <div id="fb-root"></div>
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v20.0&appId=1469540920331510" nonce="7D6fqBsd"></script>



  <main id="index">

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="hero carousel lazy carousel-fade" data-bs-ride="carousel" data-bs-interval="5000"
      touch="true">

      <div class="carousel-inner">
        <?php
        $query = "SELECT * FROM banner WHERE status='1' OR status='2' ORDER BY banner_order ASC ";
        $result = mysqli_query($db, $query);
        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          if ($counter == 1) {
            echo '<div class="carousel-item active" data-filename="' . $row['banner_filename'] . '" data-name="' . $row['banner_name'] . '">';
          } else {
            echo '<div class="carousel-item" data-filename="' . $row['banner_filename'] . '" data-name="' . $row['banner_name'] . '">';
          }

          if ($row['banner_url']) {
            // Pass the banner data directly in the onclick function
            echo '<a href="' . $row['banner_url'] . '" onclick="banner_clicks(\'' . addslashes($row['banner_filename']) . '\', \'' . addslashes($row['banner_name']) . '\'); return true;">';
          }

          echo '<div class="container">';
          echo '<div class="row justify-content-center gy-6">';
          echo '<img src="assets/img/banner/' . $row['banner_filename'] . '" alt="' . $row['banner_name'] . '" class="img-fluid">';
          echo '</div>';
          echo '</div>';

          if ($row['banner_url']) {
            echo '</a>';
          }

          echo '</div>';
          $counter++;
        }
        ?>

        <a class="carousel-control-prev" href="#hero" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>
      </div>
      <!-- Indicators BELOW -->
      <ol class="carousel-indicators"></ol>
    </section>
    <!-- End Hero Section -->


    <section id="hero-animated" class="hero-animated d-flex  justify-content-center w-100">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <div class="text-center" data-aos="zoom-out">
              <h1>
                <?php echo $hero_title ?><br>
                <span><?php echo $hero_title2 ?></span>
              </h1>
              <p>
                <?php echo $hero_subtitle ?>
              </p><br>

              <div class="d-flex justify-content-center">
                <form id="subscribeForm" method="post" class="row g-2">
                  <div class="col-12 col-md">
                    <input type="email" name="email" id="emailsubscribe" placeholder="Your email address" class="inputemailsub me-2" required>
                  </div>
                  <div class="col-12 col-md">
                    <input type="text" id="country_selector" name="country" class="location me-2">
                  </div>
                  <div class="col-12 col-md-auto">
                    <input type="submit" value="Subscribe" name="subscribe" class="inputemailsubbtn">
                  </div>
              </div>
              <div class="form-check mt-3 justify-content-center">
                <input class="form-check-input me-2" type="checkbox" value="1" id="monthlyUpdates" name="consent">
                <label class="form-check-label" for="monthlyUpdates">
                  I want to receive monthly updates from KL The Guide
                </label>
              </div>
              </form>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ======= Featured Services Section ======= -->
    <section id="featured-services" class="featured-services">
      <div class="container justify-content-center">
        <div class="section-header">
          <h2>
            <?php echo urldecode($tile1_title) ?>
          </h2>
          <p>
            <?php echo urldecode($tile1_subtitle) ?>
          </p>
        </div>
        <div class="row gy-4 justify-content-center">

          <div class="col  d-flex bg-image" data-aos="zoom-out">
            <div class="service-item  position-relative d-flex align-items-center justify-content-center  ">
              <div class="icon"> <img src="assets/img/highlights/<?php echo urldecode($tile1_photo1) ?>" class=""
                  alt="Kuala Lumpur Guide - Kuala Lumpur At A Glance"></div>
              <a class="stretched-link" href="highlights.php#tab-1"></a>
              <h4 class="text-center text"><?php echo $tile1_title1 ?></h4>
              <!-- <p>KL @ A Glance</p> -->
            </div>
          </div><!-- End Service Item -->
          <div class="col  d-flex" data-aos="zoom-out">
            <div class="service-item  position-relative d-flex align-items-center justify-content-center">

              <div class="icon"> <img src="assets/img/highlights/<?php echo urldecode($tile1_photo2) ?>" class=""
                  alt="Kuala Lumpur Guide - Getting Around Kuala Lumpur"></div>
              <a class="stretched-link" href="highlights.php#tab-2"></a>
              <h4 class="text-center text"><?php echo $tile1_title2 ?></h4>
              <!-- <p>Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi</p> -->
            </div>
          </div><!-- End Service Item -->
          <div class="col d-flex" data-aos="zoom-out">
            <div class="service-item  position-relative d-flex align-items-center justify-content-center">
              <div class="icon"> <img src="assets/img/highlights/<?php echo urldecode($tile1_photo3) ?>" class=""
                  alt="Kuala Lumpur Guide - Travel Tips"></div>
              <a href="highlights.php#tab-3" class="stretched-link"></a>
              <h4 class="text-center text"><?php echo $tile1_title3 ?></h4>

            </div>
          </div>
          <!-- End Service Item -->

          <?php

          if ($travelnews) {
          ?>

            <div class="col  d-flex" data-aos="zoom-out">
              <div class="service-item  position-relative d-flex align-items-center justify-content-center">
                <div class="icon"> <img src="assets/img/highlights/<?php echo urldecode($tile1_photo4) ?>" class=""
                    alt=""></div>
                <a href="highlights.php#tab-4" class="stretched-link"></a>
                <h4 class="text-center text"><?php echo $tile1_title4 ?></h4>

              </div>
            </div>
            <!-- End Service Item -->
          <?php
          }
          ?>


        </div>

      </div>
    </section><!-- End Featured Services Section -->




    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2 class="text-break">
            <?php echo urldecode($tile2_title) ?>
          </h2>
          <p>
            <?php echo urldecode($tile2_subtitle) ?>
          </p>
        </div>

        <div class="row gy-5 d-flex justify-content-center ">

          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="explorekl.php#explorekl" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo1 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Explore KL">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title1 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>

                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="where-to-shop.php" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo2 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Shop Like Locals">
                </div>
                <div class="details position-relative">

                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title2 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="accommodation.php#placetostay" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo3 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Place To Stay">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title3 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="spa.php" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo4 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Spa Time">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title4 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="medical-tourism.php#medicaltourism" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo5 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Medical Tourism">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title5 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- </a> -->
                  <!-- <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure
                      perferendis.</p> -->
                </div>
              </a>

            </div>
          </div><!-- End Service Item -->
          <div class="d-flex col-xl-4 col-md-6 justify-content-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <a href="beyondkl.php#beyondkl" class="stretched-link">

                <div class="img">
                  <img src="assets/img/recommendation/<?php echo $tile2_photo6 ?>" class="img-fluid object-fit-cover"
                    alt="Kuala Lumpur Guide - Beyond KL">
                </div>
                <div class="details position-relative">
                  <div class="d-flex align-items-center justify-content-center">
                    <h3><?php echo $tile2_title6 ?></h3>
                    <div class="icon2">
                      <i class="bi bi-box-arrow-right"></i>
                    </div>
                  </div>
                  <!-- </a> -->

                </div>
              </a>

            </div>
          </div><!-- End Service Item -->


        </div>

      </div>
    </section><!-- End Services Section -->






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

    <!-- Start Instagram Section -->
    <section class="insta-feed">

      <div class="section-header">
        <h2>Instagram</h2>
      </div>

      <div class="profile-info">
        <img src="assets/img/kltginstapp.png" alt="Profile Picture" class="profile-pic">
        <div class="profile-details">
          <h2 class="profile-name">@kltheguide</h2>
          <p class="profile-bio">Your ultimate travel guide to Kuala Lumpur, Malaysia 🇲🇾. Food, Sightseeing, Shopping
            <br> #KLTheGuide in your postings to get featured!
          </p>
          <div class="profile-actions">
            <a href="https://www.instagram.com/kltheguide" target="_blank" class="follow-btn">Follow</a>
          </div>
        </div>
      </div>

      <div class="insta-container" id="instafeed-container"></div>
      <div class="profile-info" id="profile-info"></div>
    </section>

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
  <script src="https://cdn.jsdelivr.net/gh/stevenschobert/instafeed.js@2.0.0rc1/src/instafeed.min.js"></script>

  <script>
    var myCarousel = document.getElementById('hero');

    myCarousel.addEventListener('slide.bs.carousel', event => {
      // do something...
      var activeslide = document.getElementsByClassName('carousel-item active');

      let banner_filename = activeslide[0].dataset.filename;
      let banner_name = activeslide[0].dataset.name;
      var xhttp = new XMLHttpRequest();


      // xhttp.onreadystatechange = function () {
      //   if (this.readyState == 4 && this.status == 200) {
      //    console.log(this.responseText);
      //   }
      // };
      xhttp.open("POST", "banner.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("banner=banner&banner_filename=" + banner_filename + "&banner_name=" + banner_name + "&clicks=0");
    })


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



    var userFeed = new Instafeed({
      get: 'user',
      userId: '1951282339',
      target: "instafeed-container",
      limit: 8,
      resolution: 'standard_resolution', // Change to standard_resolution for higher quality images
      accessToken: 'IGQWROTENqTVd2OExabl9hajhFQmlxQ0pyazZAydmNBdWEzaGdZAVXh3bUdJR0ktU0U2NlgxRlNjYUdtakZAzWFRienVudlUwbFg2YkFLUk0zbk9ISDNJSzJsb1hIa3hGVGpUX0lEdVh2RU1TRzlEYzIxaGdqWVhyZADAZD', // Replace with your Instagram access token
      template: '<a class="instafeed-item" href="{{link}}" target="_blank"><img src="{{image}}" alt="{{caption}}"/><div class="caption"><p>{{caption}}</p><p>❤️ {{likes}}</p></div></a>',
      filter: function(image) {
        // Check if the post is not a video
        return image.type !== 'video';
      }
    });

    userFeed.run();

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
            alert('🎉 Subscription successful!');
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