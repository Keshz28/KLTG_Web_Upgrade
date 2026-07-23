<!-- ============================================================================
                             ebook-details.php

     Public page — single e-book with DearFlip flipbook viewer.

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->
﻿<?php include('admin/functions.php'); ?>
<?php
$ebook_id = $_GET['id'];
$query = "SELECT * FROM ebook WHERE ebook_id='$ebook_id' LIMIT 1";
$ebookdata = mysqli_query($db, $query);
while ($row = $ebookdata->fetch_array()) {



  $ebook_name = $row['ebook_name'];
  $ebook_category = $row['ebook_category'];
  $ebook_filename = $row['ebook_filename'];
  $ebook_datetime = $row['ebook_datetime'];
  $ebook_image = $row['ebook_image'];

  if ($row['ebook_url']) {
    $ebook_url = $row['ebook_url'];
  } else{
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $ebook_url = "https://";
    } else {
      $ebook_url = "http://";
    }
    // Append the host(domain name, ip) to the URL.   
    $ebook_url .= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL   
    $ebook_url .= $_SERVER['REQUEST_URI'];


    // $schema = array(
    //   '@context'  => 'http://schema.org',
    //   '@type'     => 'BlogPosting',
    //   'headline'      => $title,
    //   'description' => $title,
    //   'image' => $image,
    //   'datePublished'=> $datepub,
    //   'dateModified'=> $datepub,
    //   'author'   => array(
    //       '@type'           => 'Organization',
    //       'name'            => 'Bluedale',
    //       'url'          => 'https://www.bluedale.com.my'
    
    //   ),
    // );

    $title = $ebook_name;
    $image =  "https://". $_SERVER['HTTP_HOST'] ."/assets/img/ebook/". $ebook_category."/" .$ebook_image;
    $urlmeta = $ebook_url;
    $datepub = $ebook_datetime;

  }


}
$query2 = "UPDATE ebook SET ebook_view=ebook_view + 1 WHERE ebook_id='$ebook_id'";
// debug_to_console($query);
$update = mysqli_query($db, $query2);




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide -
    <?php echo $ebook_name ?>
  </title>

  <link rel="canonical" href="<?php echo htmlspecialchars($urlmeta, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta content="<?php echo $ebook_name ?>" name="description">
  <meta content="" name="keywords">

  <!-- <?php echo '<script type="application/ld+json">' . json_encode($schema) . '</script>'; ?> -->


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?php echo $urlmeta ?>" />
  <meta property="og:title" content="KL The Guide - <?php echo $title ?>" />
  <meta property="og:description" content="<?php echo $title ?>" />
  <meta property="og:image" content="<?php echo $image ?>" />

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="<?php echo $urlmeta ?>" />
  <meta property="twitter:title" content="KL The Guide - <?php echo $title ?>" />
  <meta property="twitter:description" content="<?php echo $title ?>" />
  <meta property="twitter:image" content="<?php echo $image ?>" />

  <?php include 'header.php'; ?>

  <link href="assets/vendor/dearflip-jquery-flipbook/dflip/css/dflip.min.css" rel="stylesheet" type="text/css">

  <link href="assets/vendor/dearflip-jquery-flipbook/dflip/css/themify-icons.min.css" rel="stylesheet" type="text/css">

  <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

  <style>
    /* ── E-book reader frame ── */
    #ebook2 .section-header { padding-bottom: 10px; }

    #ebook2 .section-header h2 {
      font-family: 'Carter One', cursive;
      color: #1520A6;
      font-weight: 700;
      font-size: clamp(2.2rem, 4.5vw, 3.2rem);
      line-height: 1.15;
      margin-bottom: 0;
    }

    /* The flipbook scales to fit its frame, so the frame height decides how big
       the pages render. DearFlip also clamps its own container to the window
       height, so the frame must never be taller than the viewport or the excess
       shows up as empty white. sizeEbookReader() below sets the exact px height;
       these values are only the pre-JS fallback. */
    #ebookReader {
      height: calc(100vh - 190px);
      min-height: 360px;
      max-width: 1400px;
      margin: 0 auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 34px rgba(0, 0, 0, 0.13);
    }
    #ebookReader .ebookdetails { height: 100%; }

    @media (max-width: 768px) {
      #ebookReader { border-radius: 8px; }
    }
  </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'nav.php'; ?>

  <main id="ebook2">

    <!-- ======= Reader Section ======= -->
    <section id="team" class="team" style="margin-top:76px; padding-top:14px; padding-bottom:10px;">
      <div class="container" data-aos="fade-up">

        <div class="section-header" id="tes5">
          <h2>
            <?php echo $ebook_name ?>
          </h2>
        </div>

        <div id="ebookReader">
          <div class="_df_book ebookdetails"
            source="assets/pdf/ebook/<?php echo $ebook_category ?>/<?php echo $ebook_filename ?>"
            backgroundcolor="#f2f3f5"
            paddingtop="8" paddingbottom="8" paddingleft="8" paddingright="8"></div>
        </div>

        <script>
          /* Page auto-scrolls to #team on load, so the reader gets the viewport
             minus the title block above it. Must stay under window.innerHeight:
             DearFlip caps its book container at the window height, and anything
             taller than that cap renders as empty white inside the frame. */
          (function () {
            function sizeEbookReader() {
              var reader = document.getElementById('ebookReader');
              if (!reader) return;
              var header = document.querySelector('#ebook2 .section-header');
              var used = (header ? header.offsetHeight : 0) + 14 /* section padding-top */ + 16 /* breathing room */;
              reader.style.height = Math.max(360, window.innerHeight - used) + 'px';
            }
            sizeEbookReader();
            window.addEventListener('resize', sizeEbookReader);
            window.addEventListener('orientationchange', sizeEbookReader);
            window.addEventListener('load', sizeEbookReader);
          })();
        </script>

      </div>
    </section><!-- End Reader Section -->

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


    <!-- End Team Section -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
  <img src="assets/img/phoneHandSwipe.png" class="handswipe" >

  </div>
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
  <!-- <script src="assets/vendor/php-email-form/validate.js"></script> -->

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>

  <!-- <script src="assets/vendor/dearflip-jquery-flipbook/dflip/js/dflip2.php" type="text/javascript"></script> -->
  <?php include 'assets/vendor/dearflip-jquery-flipbook/dflip/js/dflip2.php'; ?>
  <script>

$(document).ready(function () {
    // Handler for .ready() called.
    $('html, body').animate({
        scrollTop: $('#team').offset().top
    }, 'slow');
    $('#exampleModal').modal('show');

});
  </script>
</body>

</html>
