<?php
/* ============================================================================
 *                              contribute.php
 *
 *   Public page — 'Contribute An Article' (invites user submissions).
 *   Submissions are emailed straight to enquiry@bluedale.com.my.
 *   The POST handler lives in admin/functions.php (guarded by $_POST['contribute']).
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('admin/functions.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Contribute An Article</title>

  <meta name="description"
    content="Share your Kuala Lumpur travel stories, hidden gems and local tips with KL The Guide. Contribute an article and get featured on our platform.">
  <meta name="keywords"
    content="Contribute, write for us, KL The Guide, Bluedale Publishing, Bluedale, BGOC, travel, tourism, Malaysia,
  KL The Guide E-Book, KLTG ebook, travel guidebook, Malaysia's capital city, Kuala Lumpur, KL,
  Dataran Merdeka, Petaling Street, travel guide, KLCC, KL Tower, Batu Caves, Kuala Lumpur city">


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/contribute.php" />
  <meta property="og:title" content="KL The Guide - Contribute An Article" />
  <meta property="og:description"
    content="Share your Kuala Lumpur travel stories, hidden gems and local tips with KL The Guide. Contribute an article and get featured on our platform." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/contribute.php" />
  <meta property="twitter:title" content="KL The Guide - Contribute An Article" />
  <meta property="twitter:description"
    content="Share your Kuala Lumpur travel stories, hidden gems and local tips with KL The Guide. Contribute an article and get featured on our platform." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

  <?php include 'header.php'; ?>

  <style>
    /* Match the site's unified blue section titles (Carter One / #1520A6) */
    #aboutus .section-header h2 {
      font-family: 'Carter One', cursive;
      color: #1520A6;
      font-weight: 700;
      font-size: clamp(2.6rem, 5.5vw, 4rem);
      line-height: 1.15;
    }

    /* Floating "pop-up" form fields — each box lifts off the page with a shadow */
    #aboutus .php-email-form .form-control {
      border: 1px solid #eef0f3;
      border-radius: 12px;
      padding: 16px 18px;
      background: #fff;
      box-shadow: 0 10px 25px rgba(21, 32, 166, 0.08);
      transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    }

    #aboutus .php-email-form .form-control:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 34px rgba(21, 32, 166, 0.14);
    }

    #aboutus .php-email-form .form-control:focus {
      border-color: var(--color-primary);
      box-shadow: 0 16px 36px rgba(14, 162, 189, 0.22);
      outline: none;
    }

    /* Elevated submit button with the same floating treatment */
    #aboutus .php-email-form button[type="submit"] {
      border: 0;
      border-radius: 12px;
      padding: 14px 44px;
      color: #fff;
      font-weight: 600;
      background: var(--color-primary);
      box-shadow: 0 12px 26px rgba(14, 162, 189, 0.35);
      transition: box-shadow .25s ease, transform .25s ease, background-color .25s ease;
    }

    #aboutus .php-email-form button[type="submit"]:hover {
      transform: translateY(-3px);
      background: var(--color-primary-dark);
      box-shadow: 0 18px 34px rgba(14, 162, 189, 0.45);
    }

    #aboutus .php-email-form button[type="submit"]:active {
      transform: translateY(-1px);
      box-shadow: 0 8px 18px rgba(14, 162, 189, 0.35);
    }
  </style>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="aboutus">

    <!-- ======= Contribute Section ======= -->
    <section id="contact" class="contact mt-5">
      <div class="container">

        <div class="section-header">
          <h2>Contribute An Article</h2>
        </div>

      </div>


      <div class="container">

        <?php if (isset($_GET['sent'])): ?>
          <?php if ($_GET['sent'] === '1'): ?>
            <div class="alert alert-success text-center" role="alert">
              Thank you! Your article has been submitted. Our editorial team will review it shortly.
            </div>
          <?php else: ?>
            <div class="alert alert-danger text-center" role="alert">
              Sorry, something went wrong and your article could not be sent. Please try again later.
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <div class="row gy-5 gx-lg-5">

          <div class="col-lg-4">

            <div class="info">
              <h3>Share your story</h3>
              <p>Have a Kuala Lumpur travel experience, hidden gem or local tip worth sharing? Send it to our
                editorial team and it could be featured on KL The Guide.</p>

              <div class="info-item d-flex">
                <i class="bi bi-pencil-square flex-shrink-0"></i>
                <div>
                  <h4>Write freely:</h4>
                  <p>Guides, reviews, food trails, itineraries — anything that helps travellers explore KL.</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex">
                <i class="bi bi-envelope flex-shrink-0"></i>
                <div>
                  <h4>Email:</h4>
                  <p>enquiry@bluedale.com.my</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex">
                <i class="bi bi-check2-circle flex-shrink-0"></i>
                <div>
                  <h4>Get featured:</h4>
                  <p>Approved articles are published with full credit to you.</p>
                </div>
              </div><!-- End Info Item -->

            </div>

          </div>

          <div class="col-lg-8">
            <form action="" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="title" id="title" placeholder="Article Title" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="article" id="article" rows="12"
                  placeholder="Write your article here..." required></textarea>
              </div>

              <div class="text-center"><button type="submit" name="contribute">Send Article</button></div>
            </form>
          </div><!-- End Contribute Form -->

        </div>

      </div>
    </section><!-- End Contribute Section -->



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
</body>

</html>
