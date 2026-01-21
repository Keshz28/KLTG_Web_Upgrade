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
  <title>KL The Guide - <?php echo $tile2_title5 ?></title>

  <meta name="description"
    content="This page contains the medical tourism locations located throughout Kuala Lumpur, Malaysia">
  <meta content="" name="keywords">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/medical-tourism.php" />
  <meta property="og:title" content="KL The Guide - <?php echo $tile2_title5 ?>" />
  <meta property="og:description"
    content="This page contains the medical tourism locations located throughout Kuala Lumpur, Malaysia" />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/medical-tourism.php" />
  <meta property="twitter:title" content="KL The Guide - <?php echo $tile2_title5 ?>" />
  <meta property="twitter:description"
    content="This page contains the medical tourism locations located throughout Kuala Lumpur, Malaysia" />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />



  <?php include 'header.php'; ?>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="medicaltourism">



    <br>

    <!-- ======= Features Section ======= -->
    <section id="features" class="features">

      <div class="container" data-aos="fade-up">

        <ul class="nav nav-tabs row gy-4  my-5 d-flex">
          <li class="nav-item col text-center text-break">
            <a class="nav-link " id="tab-1-link" href="#tab-1" data-bs-toggle="tab" data-bs-target="#tab-1">
              <img src="assets/img/medical_tourism/1.jpg" class="img-fluid" alt="">
              <h4>Healthcare</h4>
            </a>
          </li>

          <li class="nav-item col text-center text-break">
            <a class="nav-link " id="tab-2-link" data-bs-toggle="tab" href="#tab-2" data-bs-target="#tab-2">
              <img src="assets/img/medical_tourism/DENTAL-3-01.jpg" class="img-fluid" alt="">
              <h4>Dental</h4>
            </a>
          </li>

          <li class="nav-item col text-center text-break">
            <a class="nav-link " id="tab-3-link" data-bs-toggle="tab" href="#tab-3" data-bs-target="#tab-3">
              <img src="assets/img/medical_tourism/DERMATOLOGIST-01.jpg" class="img-fluid" alt="">
              <h4>Dermatologist</h4>
            </a>
          </li>

          <li class="nav-item col text-center text-break">
            <a class="nav-link " id="tab-4-link" data-bs-toggle="tab" href="#tab-4" data-bs-target="#tab-4">
              <img src="assets/img/medical_tourism/OPHTHALMOLOGIST-2-01.jpg" class="img-fluid" alt="">
              <h4>Ophthalmologist</h4>
            </a>
          </li>

          <li class="nav-item col text-center text-break">
            <a class="nav-link " id="tab-5-link" data-bs-toggle="tab" href="#tab-5" data-bs-target="#tab-5">
              <img src="assets/img/medical_tourism/PLASTISURGERY-02.jpeg" class="img-fluid" alt="">
              <h4>Plastic Surgery</h4>
            </a>
          </li>
        </ul>

        <div class="tab-content">

          <div class="tab-pane" id="tab-1">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Healthcare</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="mthcCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM medical_tourism_hc";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#mthcCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM medical_tourism_hc ORDER BY medical_tourism_hc_order DESC ";
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
                      <img src="assets/img/medical_tourism/hc/<?php echo $row['medical_tourism_hc_image'] ?>"
                        alt="<?php echo urldecode($row['medical_tourism_hc_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['medical_tourism_hc_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['medical_tourism_hc_content']) ?></p>

                        <?php if ($row['medical_tourism_hc_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_hc_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_hc_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_hc_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['medical_tourism_hc_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_hc_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['medical_tourism_hc_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_hc_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_hc_website'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_hc_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#mthcCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#mthcCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 1 -->


          <div class="tab-pane" id="tab-2">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Dental</h3>
              </div>
            </div>
            <!-- Carousel Wrapper -->
            <div id="mtdtlCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM medical_tourism_dtl";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#mtdtlCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM medical_tourism_dtl ORDER BY medical_tourism_dtl_order DESC  ";
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
                      <img src="assets/img/medical_tourism/dtl/<?php echo $row['medical_tourism_dtl_image'] ?>"
                        alt="<?php echo urldecode($row['medical_tourism_dtl_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['medical_tourism_dtl_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['medical_tourism_dtl_content']) ?></p>

                        <?php if ($row['medical_tourism_dtl_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_dtl_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_dtl_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_dtl_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['medical_tourism_dtl_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_dtl_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['medical_tourism_dtl_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_dtl_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_dtl_website'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_dtl_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#mtdtlCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#mtdtlCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 2 -->


          <div class="tab-pane" id="tab-3">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Dermatologist</h3>
              </div>
            </div>
            <!-- Carousel Wrapper -->
            <div id="mtderCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM medical_tourism_der";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#mtderCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM medical_tourism_der ORDER BY medical_tourism_der_order DESC  ";
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
                      <img src="assets/img/medical_tourism/der/<?php echo $row['medical_tourism_der_image'] ?>"
                        alt="<?php echo urldecode($row['medical_tourism_der_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['medical_tourism_der_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['medical_tourism_der_content']) ?></p>

                        <?php if ($row['medical_tourism_der_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_der_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_der_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_der_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['medical_tourism_der_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_der_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['medical_tourism_der_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_der_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_der_website'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_der_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#mtderCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#mtderCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 3 -->


          <div class="tab-pane" id="tab-4">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Ophthalmologist</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="mtophCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM medical_tourism_oph";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#mtophCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM medical_tourism_oph ORDER BY medical_tourism_oph_order DESC ";
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
                      <img src="assets/img/medical_tourism/oph/<?php echo $row['medical_tourism_oph_image'] ?>"
                        alt="<?php echo urldecode($row['medical_tourism_oph_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['medical_tourism_oph_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['medical_tourism_oph_content']) ?></p>

                        <?php if ($row['medical_tourism_oph_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_oph_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_oph_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_oph_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['medical_tourism_oph_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_oph_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['medical_tourism_oph_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_oph_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_oph_website'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_oph_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#mtophCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#mtophCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 4 -->


          <div class="tab-pane" id="tab-5">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Plastic Surgery</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="mtpsCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM medical_tourism_ps";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#mtpsCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM medical_tourism_ps ORDER BY medical_tourism_ps_order DESC ";
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
                      <img src="assets/img/medical_tourism/ps/<?php echo $row['medical_tourism_ps_image'] ?>"
                        alt="<?php echo urldecode($row['medical_tourism_ps_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['medical_tourism_ps_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['medical_tourism_ps_content']) ?></p>

                        <?php if ($row['medical_tourism_ps_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_ps_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_ps_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_ps_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['medical_tourism_ps_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_ps_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['medical_tourism_ps_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['medical_tourism_ps_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['medical_tourism_ps_website'] ?>" target="_blank">
                              <?php echo urldecode($row['medical_tourism_ps_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#mtpsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#mtpsCarousel" data-bs-slide="next">
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

      document.addEventListener('DOMContentLoaded', function() {
        const carouselElement = document.getElementById('mthcCarousel');
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