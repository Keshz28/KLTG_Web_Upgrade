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
  <title>KL The Guide - <?php echo $tile2_title6 ?></title>

  <meta name="description"
    content="Beyond KL is the best KL guide for discovering the city beyond KL's everyday destinations.">
  <meta content="" name="keywords">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/beyondkl.php" />
  <meta property="og:title" content="KL The Guide - <?php echo $tile2_title6 ?>" />
  <meta property="og:description"
    content="<?php echo $tile2_title6 ?> is the best KL guide for discovering the city beyond KL's everyday destinations." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my" />
  <meta property="twitter:title" content="KL The Guide - <?php echo $tile2_title6 ?>" />
  <meta property="twitter:description"
    content="<?php echo $tile2_title6 ?> is the best KL guide for discovering the city beyond KL's everyday destinations." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />



  <?php include 'header.php'; ?>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="beyondkl">



    <br>


    <!-- ======= Features Section ======= -->
    <section id="features" class="features">

      <div class="container" data-aos="fade-up">

        <ul class="nav nav-tabs row gy-4  my-5 d-flex justify-content-center">
          <?php
          $query = "SELECT *  FROM beyondkl_nav WHERE display='1' ORDER BY orderof ASC ";
          $result = mysqli_query($db, $query);

          while ($row = mysqli_fetch_assoc($result)) {
            if ($row['id'] <= 5) {


          ?>
              <li class="nav-item col-4 col-lg">
                <a class="nav-link " id="tab-<?php echo $row['id'] ?>-link" href="#tab-<?php echo $row['id'] ?>"
                  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $row['id'] ?>" style="">
                  <img src="assets/img/beyondkl/<?php echo $row['filename'] ?>" class="img-fluid" alt="">
                  <h4 class="text-center align-middle">
                    <?php echo $row['name'] ?>
                  </h4>
                </a>
              </li><!-- End Tab 1 Nav -->

            <?php } else {
            ?>
              <li class="nav-item col text-center text-break">
                <a class="nav-link" id="tab-<?php echo $row['id'] ?>-link" href="<?php echo $row['name'] ?>">
                  <img src="assets/img/beyondkl/<?php echo $row['filename'] ?>" class="img-fluid" alt="">
                  <!-- <h4><?php echo $row['name'] ?></h4> -->
                </a>
              </li>
          <?php
            }
          } ?>
        </ul>

        <div class="tab-content">

          <div class="tab-pane" id="tab-1">

            <div class="row gy-10 mb-3">
              <div class="col-12 text-center">
                <h3 class="">Islands</h3>
              </div>
              <br>
              <hr>
            </div>

            <!-- Carousel Wrapper -->
            <div id="bkliCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM beyondkl_i";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#bkliCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM beyondkl_i ";
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
                      <img src="assets/img/beyondkl/i/<?php echo $row['beyondkl_i_image'] ?>"
                        alt="<?php echo urldecode($row['beyondkl_i_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['beyondkl_i_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['beyondkl_i_content']) ?></p>

                        <?php if ($row['beyondkl_i_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_i_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_i_title']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_i_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_wtd_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_i_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['beyondkl_i_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_i_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_i_website'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_i_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#bkliCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#bkliCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 1 -->



          <div class="tab-pane" id="tab-2">

            <div class="row gy-10 mb-3">
              <div class="col-12 text-center">
                <h3>Hill Station</h3>
              </div>
              <br>
              <hr>
            </div>


            <!-- Carousel Wrapper -->
            <div id="bklhsCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM beyondkl_hs";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#bklhsCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM beyondkl_hs ";
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
                      <img src="assets/img/beyondkl/hs/<?php echo $row['beyondkl_hs_image'] ?>"
                        alt="<?php echo urldecode($row['beyondkl_hs_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['beyondkl_hs_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['beyondkl_hs_content']) ?></p>

                        <?php if ($row['beyondkl_hs_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_hs_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_hs_title']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_hs_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_wtd_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_hs_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['beyondkl_hs_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_hs_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_hs_website'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_hs_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#bklhsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#bklhsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 2 -->


          <div class="tab-pane" id="tab-3">
            <div class="row gy-10 mb-3">
              <div class="col-12 text-center">
                <h3>Waterfall</h3>
              </div>
              <br>
              <hr>
            </div>


            <!-- Carousel Wrapper -->
            <div id="bklwCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM beyondkl_w";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#bklwCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM beyondkl_w ";
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
                      <img src="assets/img/beyondkl/w/<?php echo urldecode($row['beyondkl_w_image']) ?>"
                        alt="<?php echo urldecode($row['beyondkl_w_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['beyondkl_w_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['beyondkl_w_content']) ?></p>

                        <?php if ($row['beyondkl_w_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_w_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_w_title']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_w_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_wtd_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_w_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['beyondkl_w_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_w_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_w_website'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_w_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#bklwCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#bklwCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 3 -->



          <div class="tab-pane" id="tab-4">

            <div class="row gy-10 mb-3">
              <div class="col-12 text-center">
                <h3>Hiking</h3>
              </div>
              <br>
              <hr>
            </div>


            <!-- Carousel Wrapper -->
            <div id="bklhCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM beyondkl_h";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#bklhCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM beyondkl_h ";
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
                      <img src="assets/img/beyondkl/h/<?php echo $row['beyondkl_h_image'] ?>"
                        alt="<?php echo urldecode($row['beyondkl_h_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['beyondkl_h_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['beyondkl_h_content']) ?></p>

                        <?php if ($row['beyondkl_h_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_h_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_h_title']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_h_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['beyondkl_h_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_h_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['beyondkl_h_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_h_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_h_website'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_h_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#bklhCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#bklhCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 4 -->


          <div class="tab-pane" id="tab-5">

            <div class="row gy-10 mb-3">
              <div class="col-12 text-center">
                <h3>Extreme Sports</h3>
              </div>
              <br>
              <hr>
            </div>

            <!-- Carousel Wrapper -->
            <div id="bklesCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM beyondkl_es";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#bklesCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM beyondkl_es ";
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
                      <img src="assets/img/beyondkl/es/<?php echo $row['beyondkl_es_image'] ?>"
                        alt="<?php echo urldecode($row['beyondkl_es_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['beyondkl_es_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['beyondkl_es_content']) ?></p>

                        <?php if ($row['beyondkl_es_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_es_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_es_title']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_es_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['beyondkl_es_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_es_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['beyondkl_es_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['beyondkl_es_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['beyondkl_es_website'] ?>" target="_blank">
                              <?php echo urldecode($row['beyondkl_es_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#bklesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#bklesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 5 -->
        </div>
      </div>
    </section><!-- End Features Section -->





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
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    $(document).ready(function() {

      $(window.location.hash).addClass('active show');

      $(window.location.hash + "-link").addClass('active show');


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