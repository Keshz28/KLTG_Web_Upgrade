<?php include('admin/functions.php'); ?>
<?php
$ebook_id = $_GET['id'];
$query = "SELECT * FROM ebook WHERE ebook_id='$ebook_id' LIMIT 1";
$ebookdata = mysqli_query($db, $query);
while ($row = $ebookdata->fetch_array()) {

  $ebook_name = $row['ebook_name'];
  $ebook_category = $row['ebook_category'];
  $ebook_filename = $row['ebook_filename'];
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

  <meta content="<?php echo $ebook_name ?>" name="description">
  <meta content="" name="keywords">


  <?php include 'header.php'; ?>

  <link href="assets/vendor/dearflip-jquery-flipbook/dflip/css/dflip.min.css" rel="stylesheet" type="text/css">

  <link href="assets/vendor/dearflip-jquery-flipbook/dflip/css/themify-icons.min.css" rel="stylesheet" type="text/css">

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'nav.php'; ?>

  <main id="ebook2">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>E-book</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li><a href="ebook.php#ebook">E-Book</a></li>
            <li>
              <?php echo $ebook_name ?>
            </li>

          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->
    <!-- ======= Team Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header" id="tes5">
          <h2>
            <?php echo $ebook_name ?>
          </h2>


          <div class="row  d-block " style="height:70vh;" id="tes5b">
            <div class="_df_book ebookdetails"
              source="assets/pdf/ebook/<?php echo $ebook_category ?>/<?php echo $ebook_filename ?>"></div>

          </div>
          <br>



    </section><!-- End Team Section -->

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




    <!-- ======= Pricing Section ======= -->
    <!-- <section id="pricing" class="pricing">
        <div class="container" data-aos="fade-up">

          <div class="section-header">
            <h2>Our Pricing</h2>
            <p>Architecto nobis eos vel nam quidem vitae temporibus voluptates qui hic deserunt iusto omnis nam voluptas
              asperiores sequi tenetur dolores incidunt enim voluptatem magnam cumque fuga.</p>
          </div>

          <div class="row gy-4"> -->

    <!-- <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="pricing-item">

              <div class="pricing-header">
                <h3>Free Plan</h3>
                <h4><sup>$</sup>0<span> / month</span></h4>
              </div>

              <ul>
                <li><i class="bi bi-dot"></i> <span>Quam adipiscing vitae proin</span></li>
                <li><i class="bi bi-dot"></i> <span>Nec feugiat nisl pretium</span></li>
                <li><i class="bi bi-dot"></i> <span>Nulla at volutpat diam uteera</span></li>
                <li class="na"><i class="bi bi-x"></i> <span>Pharetra massa massa ultricies</span></li>
                <li class="na"><i class="bi bi-x"></i> <span>Massa ultricies mi quis hendrerit</span></li>
              </ul>

              <div class="text-center mt-auto">
                <a href="#" class="buy-btn">Buy Now</a>
              </div>

            </div>
          </div> -->
    <!-- End Pricing Item -->

    <!-- <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="400">
            <div class="pricing-item featured">

              <div class="pricing-header">
                <h3>Business Plan</h3>
                <h4><sup>$</sup>29<span> / month</span></h4>
              </div>

              <ul>
                <li><i class="bi bi-dot"></i> <span>Quam adipiscing vitae proin</span></li>
                <li><i class="bi bi-dot"></i> <span>Nec feugiat nisl pretium</span></li>
                <li><i class="bi bi-dot"></i> <span>Nulla at volutpat diam uteera</spa>
                </li>
                <li><i class="bi bi-dot"></i> <span>Pharetra massa massa ultricies</spa>
                </li>
                <li><i class="bi bi-dot"></i> <span>Massa ultricies mi quis hendrerit</span></li>
              </ul>

              <div class="text-center mt-auto">
                <a href="#" class="buy-btn">Buy Now</a>
              </div>

            </div>
          </div> -->
    <!-- End Pricing Item -->

    <!-- <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="600">
            <div class="pricing-item">

              <div class="pricing-header">
                <h3>Developer Plan</h3>
                <h4><sup>$</sup>49<span> / month</span></h4>
              </div>

              <ul>
                <li><i class="bi bi-dot"></i> <span>Quam adipiscing vitae proin</span></li>
                <li><i class="bi bi-dot"></i> <span>Nec feugiat nisl pretium</span></li>
                <li><i class="bi bi-dot"></i> <span>Nulla at volutpat diam uteera</span></li>
                <li><i class="bi bi-dot"></i> <span>Pharetra massa massa ultricies</span></li>
                <li><i class="bi bi-dot"></i> <span>Massa ultricies mi quis hendrerit</span></li>
              </ul>

              <div class="text-center mt-auto">
                <a href="#" class="buy-btn">Buy Now</a>
              </div>

            </div>
          </div> -->
    <!-- End Pricing Item -->

    <!-- </div>

      </div>
    </section> -->
    <!-- End Pricing Section -->


    <!-- ======= Team Section ======= -->
    <!-- <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Our Team</h2>
          <p>Architecto nobis eos vel nam quidem vitae temporibus voluptates qui hic deserunt iusto omnis nam voluptas
            asperiores sequi tenetur dolores incidunt enim voluptatem magnam cumque fuga.</p>
        </div>

        <div class="row gy-5"> -->

    <!-- <div class="col-xl-4 col-md-6 d-flex" data-aos="zoom-in" data-aos-delay="200">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/team-1.jpg" class="img-fluid" alt="">
              </div>
              <div class="member-info">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
                <h4>Walter White</h4>
                <span>Chief Executive Officer</span>
              </div>
            </div>
          </div> -->
    <!-- End Team Member -->
    <!-- 

        </div>

      </div>
    </section> -->
    <!-- End Team Section -->


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
});
  </script>
</body>

</html>