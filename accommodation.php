<?php include('admin/functions.php');

$query = "SELECT * FROM indexpage ";
$result = mysqli_query($db, $query);

while ($row = mysqli_fetch_assoc($result)) {


  $hero_title = $row['hero_title'];
  $hero_title2 = $row['hero_title2'];
  $hero_subtitle = $row['hero_subtitle'];
  $tile1_title = $row['tile1_title'];
  $tile1_subtitle = $row['tile1_subtitle'];
  $tile1_photo1 = $row['tile1_photo1'];
  $tile1_photo2 = $row['tile1_photo2'];
  $tile1_photo3 = $row['tile1_photo3'];
  $tile1_photo4 = $row['tile1_photo4'];
  $tile1_title1 = $row['tile1_title1'];
  $tile1_title2 = $row['tile1_title2'];
  $tile1_title3 = $row['tile1_title3'];
  $tile1_title4 = $row['tile1_title4'];
  $tile2_title = $row['tile2_title'];
  $tile2_subtitle = $row['tile2_subtitle'];

  $tile2_photo1 = $row['tile2_photo1'];
  $tile2_photo2 = $row['tile2_photo2'];
  $tile2_photo3 = $row['tile2_photo3'];
  $tile2_photo4 = $row['tile2_photo4'];
  $tile2_photo5 = $row['tile2_photo5'];
  $tile2_photo6 = $row['tile2_photo6'];

  $tile2_title1 = $row['tile2_title1'];
  $tile2_title2 = $row['tile2_title2'];
  $tile2_title3 = $row['tile2_title3'];
  $tile2_title4 = $row['tile2_title4'];
  $tile2_title5 = $row['tile2_title5'];
  $tile2_title6 = $row['tile2_title6'];

  $tile3_title = $row['tile3_title'];
  $tile3_subtitle = $row['tile3_subtitle'];
  $tile4_title = $row['tile4_title'];
  $tile4_subtitle = $row['tile4_subtitle'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - <?php echo $tile2_title3 ?></title>

  <meta name="description"
    content="The perfect guide for finding places to stay in Kuala Lumpur, a city that never sleeps. Browse listings of the best hotels, resorts, hostels and apartments in the capital of Malaysia.">

  <meta content="" name="keywords">


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/accommodation.php" />
  <meta property="og:title" content="KL The Guide - <?php echo $tile2_title3 ?>" />
  <meta property="og:description" content="The perfect guide for finding places to stay in Kuala Lumpur, a city that never sleeps. Browse listings of the best hotels, resorts, hostels and apartments in the capital of Malaysia." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/accommodation.php" />
  <meta property="twitter:title" content="KL The Guide - <?php echo $tile2_title3 ?>" />
  <meta property="twitter:description" content="The perfect guide for finding places to stay in Kuala Lumpur, a city that never sleeps. Browse listings of the best hotels, resorts, hostels and apartments in the capital of Malaysia." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />


  <?php include 'header.php'; ?>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="placetostay">



    <br>


    <!-- ======= Features Section ======= -->
    <section id="features" class="features">

      <div class="container" data-aos="fade-up">

        <ul class="nav nav-tabs row gy-4 my-5 d-flex">

          <?php
          $query = "SELECT *  FROM accommodation_nav WHERE display='1' ORDER BY orderof ASC ";
          $result = mysqli_query($db, $query);

          while ($row = mysqli_fetch_assoc($result)) {
            if ($row['id'] <= 5) {


          ?>
              <li class="nav-item col-6 col-md-3 col-lg-3">
                <a class="nav-link " id="tab-<?php echo $row['id'] ?>-link" href="#tab-<?php echo $row['id'] ?>"
                  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $row['id'] ?>" style="">
                  <img src="assets/img/accommodation/<?php echo $row['filename'] ?>" class="img-fluid" alt="">
                  <h4 class="text-center align-middle">
                    <?php echo $row['name'] ?>
                  </h4>
                </a>
              </li><!-- End Tab 1 Nav -->

            <?php } else {
            ?>
              <li class="nav-item col-6 col-md-3 col-lg-3 text-center text-break">
                <a class="nav-link" id="tab-<?php echo $row['id'] ?>-link" href="<?php echo $row['name'] ?>">
                  <img src="assets/img/accommodation/<?php echo $row['filename'] ?>" class="img-fluid" alt="">
                  <!-- <h4><?php echo $row['name'] ?></h4> -->
                </a>
              </li>
          <?php
            }
          } ?>

        </ul>

        <div class="tab-content">

          <div class="tab-pane" id="tab-1">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Top Places To Stay In KL</h3>
                </p>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="topCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM accommodation_top";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#topCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM accommodation_top ";
                $result = mysqli_query($db, $query);
                $counter = 1;
                $items_per_slide = 2; // Number of cards per slide
                $slide_count = 0;

                while ($row = mysqli_fetch_assoc($result)) {
                  // Start a new slide every 'items_per_slide' items
                  if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                    $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                    echo '<div class="carousel-item ' . $active_class . '">';
                    echo '<div class="row g-4">'; // Start row for cards in this slide
                    $slide_count++;
                  }
                ?>
                  <!-- Single Card -->
                  <div class="col-md-6"> <!-- Each card takes half the width on medium screens and up -->
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                      <img src="assets/img/accommodation/top/<?php echo $row['accommodation_top_image'] ?>"
                        alt="<?php echo urldecode($row['accommodation_top_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['accommodation_top_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['accommodation_top_content']) ?></p>

                        <?php if ($row['accommodation_top_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['accommodation_top_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['accommodation_top_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['accommodation_top_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['accommodation_top_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['accommodation_top_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['accommodation_top_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['accommodation_top_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['accommodation_top_website'] ?>" target="_blank">
                              <?php echo urldecode($row['accommodation_top_website']) ?>
                            </a>
                          </p>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php
                  // End the slide row and div every 'items_per_slide' items or if it's the last item
                  if ($counter % $items_per_slide == 0 || $counter == $total_items) {
                    echo '</div>'; // Close row
                    echo '</div>'; // Close carousel-item
                  }
                  $counter++;
                }
                ?>
              </div>
              <!-- Carousel Controls -->
              <button class="carousel-control-prev" type="button" data-bs-target="#topCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#topCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 2 -->


          <div class="tab-pane" id="tab-2">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Hotels</h3>
                </p>
              </div>
            </div>
            <div class="row">
              <?php
              $query = "SELECT * FROM accommodation_h";
              $result = mysqli_query($db, $query);
              $counter = 1;
              while ($row = mysqli_fetch_assoc($result)) {

                echo '<div class="col-12 col-lg-6 mb-3  ">';
                echo '  <div class="card h-100 rounded-4">';
                echo '    <div class="row h-100">';
                echo '      <div class="col-6">';
                echo '        <img class="card-img rounded-4" src="assets/img/accommodation/h/' . $row['accommodation_h_image'] . '" alt="' . urldecode($row['accommodation_h_title']) . '" loading="lazy">';
                echo '      </div>';
                echo '      <div class="col-6 " >';
                echo '        <div class="card-body ">';
                echo '          <h5 class="card-title">' . urldecode($row['accommodation_h_title']) . '</h5>';
                if ($row['accommodation_h_location']) {
                  echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['accommodation_h_locationurl'] . '">' . urldecode($row['accommodation_h_location']) . '</a></p>';
                }
                if ($row['accommodation_h_hours']) {
                  echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . $row['accommodation_h_hours'] . '</p>';
                }
                if ($row['accommodation_h_phone']) {
                  echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['accommodation_h_phone'] . '</p>';
                }
                if ($row['accommodation_h_website']) {
                  echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['accommodation_h_websiteurl'] . '">' . urldecode($row['accommodation_h_website']) . '</a></p>';
                }

                echo '        </div>';
                echo '      </div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
              }
              ?>
            </div>
          </div><!-- End Tab Content 2 -->




          <div class="tab-pane" id="tab-3">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Budget Hotels</h3>
                </p>
              </div>
            </div>
            <div class="row">
              <?php
              $query = "SELECT * FROM accommodation_bh";
              $result = mysqli_query($db, $query);
              $counter = 1;
              while ($row = mysqli_fetch_assoc($result)) {

                echo '<div class="col-12 col-lg-6 mb-3  ">';
                echo '  <div class="card h-100 rounded-4">';
                echo '    <div class="row h-100">';
                echo '      <div class="col-6">';
                echo '        <img class="card-img rounded-4" src="assets/img/accommodation/bh/' . $row['accommodation_bh_image'] . '" alt="' . urldecode($row['accommodation_bh_title']) . '" loading="lazy">';
                echo '      </div>';
                echo '      <div class="col-6 " >';
                echo '        <div class="card-body ">';
                echo '          <h4 class="card-title"><strong>' . urldecode($row['accommodation_bh_title']) . '</strong></h4>';
                if ($row['accommodation_bh_location']) {
                  echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['accommodation_bh_locationurl'] . '">' . urldecode($row['accommodation_bh_location']) . '</a></p>';
                }
                if ($row['accommodation_bh_hours']) {
                  echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . $row['accommodation_bh_hours'] . '</p>';
                }
                if ($row['accommodation_bh_phone']) {
                  echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['accommodation_bh_phone'] . '</p>';
                }
                if ($row['accommodation_bh_website']) {
                  echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['accommodation_bh_websiteurl'] . '">' . urldecode($row['accommodation_bh_website']) . '</a></p>';
                }

                echo '        </div>';
                echo '      </div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
              }
              ?>
            </div>
          </div><!-- End Tab Content 3 -->














          <div class="tab-pane" id="tab-4">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Backpackers Lodge </h3>
                </p>
              </div>
            </div>
            <div class="row">
              <?php
              $query = "SELECT * FROM accommodation_bks";
              $result = mysqli_query($db, $query);
              $counter = 1;
              while ($row = mysqli_fetch_assoc($result)) {

                echo '<div class="col-12 col-lg-6 mb-3  ">';
                echo '  <div class="card h-100 rounded-4">';
                echo '    <div class="row h-100">';
                echo '      <div class="col-6">';
                echo '        <img class="card-img rounded-4" src="assets/img/accommodation/bks/' . $row['accommodation_bks_image'] . '" alt="' . urldecode($row['accommodation_bks_title']) . '" loading="lazy">';
                echo '      </div>';
                echo '      <div class="col-6 " >';
                echo '        <div class="card-body ">';
                echo '          <h4 class="card-title"><strong>' . urldecode($row['accommodation_bks_title']) . '</strong></h4>';
                if ($row['accommodation_bks_location']) {
                  echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['accommodation_bks_locationurl'] . '">' . urldecode($row['accommodation_bks_location']) . '</a></p>';
                }
                if ($row['accommodation_bks_hours']) {
                  echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . $row['accommodation_bks_hours'] . '</p>';
                }
                if ($row['accommodation_bks_phone']) {
                  echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['accommodation_bks_phone'] . '</p>';
                }
                if ($row['accommodation_bh_website']) {
                  echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['accommodation_bh_websiteurl'] . '">' . urldecode($row['accommodation_bh_website']) . '</a></p>';
                }

                echo '        </div>';
                echo '      </div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
              }
              ?>
            </div>
          </div><!-- End Tab Content 4 -->
        </div>
    </section><!-- End Features Section -->
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'footer.php'; ?>
  <!-- End Footer -->

  <a href="#placetostay" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    $(document).ready(function() {

      $(window.location.hash).addClass('active show');

      $(window.location.hash + "-link").addClass('active show');

      document.addEventListener('DOMContentLoaded', function() {
        const carouselElement = document.getElementById('topCarousel');
        const carousel = new bootstrap.Carousel(carouselElement, {
          interval: false,
          wrap: false // Optional: prevents wrapping from last to first slide
        });
      });


    });
    window.addEventListener(
      "hashchange",
      () => {
        let text = window.location.hash;
        let result = text.includes("tab");
        if (result) {

          location.reload()

          //  block of code to be executed if the condition is true
        }
      },
      false
    );
  </script>
</body>

</html>