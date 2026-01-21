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
  <title>KL The Guide - <?php echo $tile2_title1 ?></title>

  <meta name="description"
    content="This page contains the menu for navigating to the various sights, activities and locations throughout Kuala Lumpur">
  <meta content="" name="keywords">


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/explorekl.php" />
  <meta property="og:title" content="KL The Guide - <?php echo $tile2_title1 ?>" />
  <meta property="og:description"
    content="This page contains the menu for navigating to the various sights, activities and locations throughout Kuala Lumpur" />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/explorekl.php" />
  <meta property="twitter:title" content="KL The Guide - <?php echo $tile2_title1 ?>" />
  <meta property="twitter:description"
    content="This page contains the menu for navigating to the various sights, activities and locations throughout Kuala Lumpur" />
  <meta property="twitter:image" content="assets/img/kltgseo.jpg" />


  <?php include 'header.php'; ?>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="exploreklbody">



    <br>


    <!-- ======= Features Section ======= -->
    <section id="explorekl" class="features">

      <div class="container" data-aos="fade-up">

        <ul class="nav nav-tabs row gy-4  my-5 d-flex justify-content-center">
          <?php
          $query = "SELECT *  FROM explorekl_nav WHERE display='1' ORDER BY orderof ASC ";
          $result = mysqli_query($db, $query);

          while ($row = mysqli_fetch_assoc($result)) {
            if ($row['id'] <= 8) {


          ?>
              <li class="nav-item col-4 col-md-3 col-lg-3 ">
                <a class="nav-link explorekl" id="tab-<?php echo $row['id'] ?>-link" href="#tab-<?php echo $row['id'] ?>"
                  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $row['id'] ?>" style="">
                  <img src="assets/img/explorekl/<?php echo $row['filename'] ?>" class="img-fluid" alt="">
                  <h4 class="text-center align-middle">
                    <?php echo $row['name'] ?>
                  </h4>
                </a>
              </li><!-- End Tab 1 Nav -->
            <?php } else {
            ?>
              <li class="nav-item col text-center text-break">
                <a class="nav-link" id="tab-<?php echo $row['id'] ?>-link" href="<?php echo $row['name'] ?>">
                  <img src="assets/img/medical_tourism/<?php echo $row['filename'] ?>" class="img-fluid" alt="">
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
                <h3>What To Do In KL</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="wtdCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM explorekl_wtd";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#wtdCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM explorekl_wtd ";
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
                      <img src="assets/img/explorekl/wtd/<?php echo urldecode($row['explorekl_wtd_image']) ?>"
                        alt="<?php echo urldecode($row['explorekl_wtd_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_wtd_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_wtd_content']) ?></p>

                        <?php if ($row['explorekl_wtd_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_wtd_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_wtd_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_wtd_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_wtd_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_wtd_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['explorekl_wtd_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_wtd_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_wtd_website'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_wtd_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#wtdCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#wtdCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

          </div>




          <div class="tab-pane" id="tab-2">

            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Historical Sites</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="hsCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM explorekl_hs";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#hsCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM explorekl_hs ";
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
                      <img src="assets/img/explorekl/hs/<?php echo $row['explorekl_hs_image'] ?>"
                        alt="<?php echo urldecode($row['explorekl_hs_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_hs_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_hs_content']) ?></p>

                        <?php if ($row['explorekl_hs_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_hs_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_hs_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_hs_hours']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_hs_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_hs_phone']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            <?php echo urldecode($row['explorekl_hs_phone']) ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_hs_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_hs_website'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_hs_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#hsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#hsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

          </div><!-- End Tab Content 2 -->


          <div class="tab-pane" id="tab-3">

            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Places Of Worship</h3>

                </p>
              </div>

              <!-- ======= About Section ======= -->
              <div class="container" data-aos="fade-up">


                <div class="row g-4 g-lg-5" data-aos="fade-up" data-aos-delay="200">


                  <div class="col-lg-12">

                    <!-- Tabs -->
                    <ul class="nav d-flex  nav-pills mb-3 justify-content-center">
                      <li><a class="nav-link  d-flex justify-content-center active" data-bs-toggle="pill"
                          href="#tabc1">Muslim</a></li>
                      <li><a class="nav-link  d-flex justify-content-center " data-bs-toggle="pill"
                          href="#tabc2">Buddhist/Tao</a></li>
                      <li><a class="nav-link   d-flex justify-content-center " data-bs-toggle="pill"
                          href="#tabc3">Hindu</a></li>
                      <li><a class="nav-link   d-flex justify-content-center " data-bs-toggle="pill"
                          href="#tabc4">Others</a></li>

                    </ul><!-- End Tabs -->

                    <!-- Tab Content -->
                    <div class="tab-content">

                      <div class="tab-pane fade show active" id="tabc1">
                        <div class="row">



                          <?php

                          $query = "SELECT * FROM explorekl_pwor WHERE explorekl_pwor_category='muslim'";
                          $result = mysqli_query($db, $query);
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {


                            echo '<div class="col-12 col-lg-6 mb-3  ">';
                            echo '  <div class="card h-100 rounded-4">';
                            echo '    <div class="row h-100">';
                            echo '      <div class="col-6">';
                            echo '        <img class="card-img rounded-4" src="assets/img/explorekl/pwor/' . $row['explorekl_pwor_image'] . '" alt="' . $row['explorekl_pwor_title'] . '" loading="lazy" >';
                            echo '      </div>';
                            echo '      <div class="col-6 " >';
                            echo '        <div class="card-body h-100">';
                            echo '          <h5 class="card-title">' . urldecode($row['explorekl_pwor_title']) . '</h5>';
                            if ($row['explorekl_pwor_location']) {
                              echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['explorekl_pwor_locationurl'] . '">' . urldecode($row['explorekl_pwor_location']) . '</a></p>';
                            }
                            if ($row['explorekl_pwor_hours']) {
                              echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . urldecode($row['explorekl_pwor_hours']) . '</p>';
                            }
                            if ($row['explorekl_pwor_phone']) {
                              echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['explorekl_pwor_phone'] . '</p>';
                            }
                            if ($row['explorekl_pwor_website']) {
                              echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['explorekl_pwor_website'] . '">' . $row['explorekl_pwor_website'] . '</a></p>';
                            }
                            echo '        </div>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                          }
                          ?>

                        </div>



                      </div><!-- End Tab 1 Content -->

                      <div class="tab-pane fade show" id="tabc2">

                        <div class="row">



                          <?php

                          $query = "SELECT * FROM explorekl_pwor WHERE explorekl_pwor_category='tao'";
                          $result = mysqli_query($db, $query);
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {



                            echo '<div class="col-12 col-lg-6 mb-3  ">';
                            echo '  <div class="card h-100 rounded-4">';
                            echo '    <div class="row  h-100">';
                            echo '      <div class="col-6">';
                            echo '        <img class="card-img rounded-4 " src="assets/img/explorekl/pwor/' . $row['explorekl_pwor_image'] . '" alt="' . $row['explorekl_pwor_title'] . '" loading="lazy">';
                            echo '      </div>';
                            echo '      <div class="col-6 " >';
                            echo '        <div class="card-body h-100 ">';
                            echo '          <h5 class="card-title">' . urldecode($row['explorekl_pwor_title']) . '</h5>';
                            if ($row['explorekl_pwor_location']) {
                              echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['explorekl_pwor_locationurl'] . '">' . urldecode($row['explorekl_pwor_location']) . '</a></p>';
                            }
                            if ($row['explorekl_pwor_hours']) {
                              echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . urldecode($row['explorekl_pwor_hours']) . '</p>';
                            }
                            if ($row['explorekl_pwor_phone']) {
                              echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['explorekl_pwor_phone'] . '</p>';
                            }
                            if ($row['explorekl_pwor_website']) {
                              echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['explorekl_pwor_website'] . '">' . $row['explorekl_pwor_website'] . '</a></p>';
                            }
                            echo '        </div>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                          }
                          ?>

                        </div>

                      </div><!-- End Tab 2 Content -->

                      <div class="tab-pane fade show" id="tabc3">

                        <div class="row">



                          <?php

                          $query = "SELECT * FROM explorekl_pwor WHERE explorekl_pwor_category='hindu'";
                          $result = mysqli_query($db, $query);
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {



                            echo '<div class="col-12 col-lg-6 mb-3  ">';
                            echo '  <div class="card h-100 rounded-4">';
                            echo '    <div class="row h-100">';
                            echo '      <div class="col-6">';
                            echo '        <img class="card-img rounded-4 " src="assets/img/explorekl/pwor/' . $row['explorekl_pwor_image'] . '" alt="' . $row['explorekl_pwor_title'] . '" loading="lazy">';
                            echo '      </div>';
                            echo '      <div class="col-6 " >';
                            echo '        <div class="card-body h-100 ">';
                            echo '          <h5 class="card-title">' . urldecode($row['explorekl_pwor_title']) . '</h5>';
                            if ($row['explorekl_pwor_location']) {
                              echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['explorekl_pwor_locationurl'] . '">' . urldecode($row['explorekl_pwor_location']) . '</a></p>';
                            }
                            if ($row['explorekl_pwor_hours']) {
                              echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . urldecode($row['explorekl_pwor_hours']) . '</p>';
                            }
                            if ($row['explorekl_pwor_phone']) {
                              echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['explorekl_pwor_phone'] . '</p>';
                            }
                            if ($row['explorekl_pwor_website']) {
                              echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['explorekl_pwor_website'] . '">' . $row['explorekl_pwor_website'] . '</a></p>';
                            }
                            echo '        </div>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                          }
                          ?>

                        </div>

                      </div><!-- End Tab 3 Content -->


                      <div class="tab-pane fade show" id="tabc4">

                        <div class="row">



                          <?php

                          $query = "SELECT * FROM explorekl_pwor WHERE explorekl_pwor_category='other'";
                          $result = mysqli_query($db, $query);
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {



                            echo '<div class="col-12 col-lg-6 mb-3  ">';
                            echo '  <div class="card h-100 rounded-4">';
                            echo '    <div class="row h-100">';
                            echo '      <div class="col-6">';
                            echo '        <img class="card-img rounded-4 " src="assets/img/explorekl/pwor/' . $row['explorekl_pwor_image'] . '" alt="' . $row['explorekl_pwor_title'] . '" loading="lazy">';
                            echo '      </div>';
                            echo '      <div class="col-6" >';
                            echo '        <div class="card-body h-100 ">';
                            echo '          <h5 class="card-title">' . urldecode($row['explorekl_pwor_title']) . '</h5>';
                            if ($row['explorekl_pwor_location']) {
                              echo '          <p class="card-text"><i class="bi bi-geo-alt-fill" style="color: black;"></i> <a href="' . $row['explorekl_pwor_locationurl'] . '">' . urldecode($row['explorekl_pwor_location']) . '</a></p>';
                            }
                            if ($row['explorekl_pwor_hours']) {
                              echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . urldecode($row['explorekl_pwor_hours']) . '</p>';
                            }
                            if ($row['explorekl_pwor_phone']) {
                              echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['explorekl_pwor_phone'] . '</p>';
                            }
                            if ($row['explorekl_pwor_website']) {
                              echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['explorekl_pwor_website'] . '">' . $row['explorekl_pwor_website'] . '</a></p>';
                            }
                            echo '        </div>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                          }
                          ?>
                        </div>
                      </div><!-- End Tab 4 Content -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tab Content 3 places of worship -->



          <div class="tab-pane" id="tab-4">


            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>What To Eat In KL</h3>

                </p>
              </div>

              <!-- ======= About Section ======= -->
              <div class="container" data-aos="fade-up">


                <div class="row g-4 g-lg-5" data-aos="fade-up" data-aos-delay="200">


                  <div class="col-lg-12">

                    <!-- Tabs -->
                    <ul class="nav nav-pills mb-3 justify-content-center">
                      <li><a class="nav-link  d-flex justify-content-center  active" data-bs-toggle="pill"
                          href="#tabd1">Street Food</a></li>
                      <li><a class="nav-link  d-flex justify-content-center" data-bs-toggle="pill"
                          href="#tabd2">Cafes</a></li>
                      <li><a class="nav-link  d-flex justify-content-center" data-bs-toggle="pill" href="#tabd3"
                          id="reinitportfolio" onclick="reinitportfolio()">Restaurants</a></li>

                    </ul><!-- End Tabs -->

                    <!-- Tab Content -->
                    <div class="tab-content">

                      <div class="tab-pane fade show active" id="tabd1">

                        <!-- Carousel Wrapper -->
                        <div id="sfCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_wte_sf";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#sfCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_wte_sf ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/wte/sf/<?php echo $row['explorekl_wte_sf_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_sf_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_wte_sf_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_wte_sf_content']) ?></p>

                                    <?php if ($row['explorekl_wte_sf_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_wte_sf_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_wte_sf_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_wte_sf_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_wte_sf_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_wte_sf_website']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_wte_sf_website'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_wte_sf_website']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#sfCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#sfCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div>

                      <!-- End Tab 1 Content -->

                      <!-- JavaScript to ensure carousel doesn't auto-rotate -->
                      <script>
                        document.addEventListener('DOMContentLoaded', function() {
                          const carouselElement = document.getElementById('sfCarousel');
                          const carousel = new bootstrap.Carousel(carouselElement, {
                            interval: false,
                            wrap: false
                          });
                        });
                      </script>



                      <div class="tab-pane fade show" id="tabd2">
                        <div class="row">
                          <?php
                          $query = "SELECT * FROM explorekl_wte_c ";
                          $result = mysqli_query($db, $query);
                          $counter = 1;
                          while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="col-12 col-lg-6 my-3">';
                            echo '  <div class="card h-100 rounded-4">';
                            echo '    <div class="row  h-100">';
                            echo '      <div class="col-6  h-100">';
                            echo '        <img class="card-img rounded-4" src="assets/img/explorekl/wte/c/' . $row['explorekl_wte_c_image'] . '" alt="Card image cap">';
                            echo '      </div>';
                            echo '      <div class="col-6  h-100">';
                            echo '        <div class="card-body h-100">';
                            echo '          <h5 class="card-title">' . $row['explorekl_wte_c_title'] . '</h5>';
                            if ($row['explorekl_wte_c_location']) {
                              echo '          <p class="card-text"><i class="bi bi-geo-fill" style="color: black;"></i> <a href="' . $row['explorekl_wte_c_locationurl'] . '">' . $row['explorekl_wte_c_location'] . '</a></p>';
                            }
                            if ($row['explorekl_wte_c_hours']) {
                              echo '          <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i> ' . $row['explorekl_wte_c_hours'] . '</p>';
                            }
                            if ($row['explorekl_wte_c_phone']) {
                              echo '          <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i> ' . $row['explorekl_wte_c_phone'] . '</p>';
                            }
                            if ($row['explorekl_wte_c_website']) {
                              echo '          <p class="card-text"><i class="bi bi-globe" style="color: black;"></i> <a href="' . $row['explorekl_wte_c_website'] . '">' . $row['explorekl_wte_c_website'] . '</a></p>';
                            }
                            echo '        </div>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                            $counter++;
                          }
                          ?>
                        </div>
                      </div><!-- End Tab 2 Content -->

                      <div class="tab-pane fade show" id="tabd3">

                        <!-- ======= Portfolio Section ======= -->
                        <section id="portfolio" class="portfolio" data-aos="fade-up">


                          <div class="container">

                            <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry"
                              data-portfolio-sort="original-order">

                              <ul class="portfolio-flters">
                                <li data-filter="*" class="filter-active"><a class="btn  btn-primary"
                                    aria-pressed="true">All</a></li>
                                <li data-filter=".filter-malay"><a class="btn btn-primary" aria-pressed="true">Malay</a>
                                </li>
                                <li data-filter=".filter-chinese"><a class="btn btn-primary"
                                    aria-pressed="true">Chinese</a></li>
                                <li data-filter=".filter-indian"><a class="btn btn-primary"
                                    aria-pressed="true">Indian</a></li>
                                <li data-filter=".filter-arabic"><a class="btn btn-primary"
                                    aria-pressed="true">Arabic</a></li>
                                <li data-filter=".filter-korean"><a class="btn btn-primary"
                                    aria-pressed="true">Korean</a></li>
                                <li data-filter=".filter-japanese"><a class="btn btn-primary"
                                    aria-pressed="true">Japanese</a></li>
                                <li data-filter=".filter-mexican"><a class="btn btn-primary"
                                    aria-pressed="true">Mexican</a></li>
                                <li data-filter=".filter-fastfood"><a class="btn btn-primary" aria-pressed="true">Fast
                                    Food</a></li>
                                <li data-filter=".filter-westernfood"><a class="btn btn-primary"
                                    aria-pressed="true">Western Food</a></li>

                              </ul><!-- End Portfolio Filters -->

                              <div class="row  portfolio-container">


                                <?php

                                $query = "SELECT * FROM explorekl_wte_r ";
                                $result = mysqli_query($db, $query);
                                while ($row = mysqli_fetch_assoc($result)) { ?>


                                  <div
                                    class="col-12 col-lg-6 my-3  portfolio-item2  filter-<?php echo $row['explorekl_wte_r_category'] ?>">
                                    <div class="card h-100 rounded-4">
                                      <div class="row h-100">
                                        <div class="col-6 ">
                                          <img class="card-img rounded-4"
                                            src="assets/img/explorekl/wte/r/<?php echo $row['explorekl_wte_r_image'] ?>"
                                            alt="<?php echo $row['explorekl_wte_r_title'] ?>" loading="lazy">
                                        </div>
                                        <div class="col-6">
                                          <div class="card-body h-100">
                                            <h5 class="card-title"><b>
                                                <?php echo $row['explorekl_wte_r_title'] ?>
                                              </b></h5>
                                            <?php if ($row['explorekl_wte_r_location']) { ?>
                                              <p class="card-text"><i class="bi bi-geo-fill" style="color: black;"></i>
                                                <a href="<?php echo $row['explorekl_wte_r_locationurl'] ?>">
                                                  <?php echo $row['explorekl_wte_r_location'] ?>
                                                </a>
                                              </p>
                                            <?php } ?>

                                            <?php if ($row['explorekl_wte_r_hours']) { ?>
                                              <p class="card-text"><i class="bi bi-clock-fill" style="color: black;"></i>
                                                <?php echo $row['explorekl_wte_r_hours'] ?>
                                              </p>
                                            <?php } ?>
                                            <?php if ($row['explorekl_wte_r_phone']) { ?>

                                              <p class="card-text"><i class="bi bi-telephone-fill" style="color: black;"></i>
                                                <?php echo $row['explorekl_wte_r_phone'] ?>
                                              </p>
                                            <?php } ?>
                                            <?php if ($row['explorekl_wte_r_website']) { ?>
                                              <p class="card-text"><i class="bi bi-globe" style="color: black;"></i>
                                                <a href="<?php echo $row['explorekl_wte_r_website'] ?>">
                                                  <?php echo $row['explorekl_wte_r_website'] ?>
                                                </a>
                                              </p>
                                            <?php } ?>
                                            <?php if ($row['explorekl_wte_r_content']) { ?>

                                              <p class="card-text">Remark : <br>
                                                <?php echo $row['explorekl_wte_r_content'] ?>
                                              </p>
                                            <?php } ?>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>


                                <?php }
                                ?>

                                <!-- End Portfolio Item -->


                              </div><!-- End Portfolio Container -->

                            </div>

                          </div>
                        </section><!-- End Portfolio Section -->
                      </div><!-- End Tab 3 Content -->

                    </div>

                  </div>

                </div>

              </div>


            </div>

          </div><!-- End Tab Content 4 -->


          <div class="tab-pane" id="tab-5">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Night Life</h3>

                </p>
              </div>

              <!-- ======= About Section ======= -->
              <div class="container" data-aos="fade-up">


                <div class="row g-4 g-lg-5" data-aos="fade-up" data-aos-delay="200">


                  <div class="col-lg-12">

                    <!-- Tabs -->
                    <ul class="nav nav-pills mb-3 justify-content-center">
                      <li><a class="nav-link  d-flex justify-content-center active" data-bs-toggle="pill"
                          href="#tabe1">Night-Life</a></li>
                      <li><a class="nav-link d-flex justify-content-center" data-bs-toggle="pill" href="#tabe2">Bars</a>
                      </li>
                      <li><a class="nav-link d-flex justify-content-center" data-bs-toggle="pill" href="#tabe3">Night
                          Market</a></li>

                    </ul><!-- End Tabs -->

                    <!-- Tab Content -->
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="tabe1">
                        <!-- Carousel Wrapper -->
                        <div id="nlCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_nl WHERE explorekl_nl_category='nl' ";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#nlCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_nl WHERE explorekl_nl_category='nl'  ORDER BY explorekl_nl_order DESC  ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/nl/<?php echo $row['explorekl_nl_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_nl_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_nl_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_nl_content']) ?></p>

                                    <?php if ($row['explorekl_nl_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_nl_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_nl_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-telephone-fill" style="color: black;"></i> <?php echo $row['explorekl_nl_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_phone']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <?php echo urldecode($row['explorekl_nl_phone']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#nlCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#nlCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div><!-- End Tab 1 Content -->








































                      <div class="tab-pane fade" id="tabe2">
                        <!-- Carousel Wrapper -->
                        <div id="barCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_nl";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#barCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_nl WHERE explorekl_nl_category='bars'  ORDER BY explorekl_nl_order DESC  ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/nl/<?php echo $row['explorekl_nl_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_nl_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_nl_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_nl_content']) ?></p>

                                    <?php if ($row['explorekl_nl_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_nl_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_nl_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_nl_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_phone']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-telephone-fill" style="color: black;"></i>
                                        <?php echo urldecode($row['explorekl_nl_phone']) ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_website']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_nl_website'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_nl_website']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#barCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#barCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div><!-- End Tab 2 Content -->















                      <div class="tab-pane fade" id="tabe3">
                        <!-- Carousel Wrapper -->
                        <div id="nmCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_nl";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#nmCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_nl WHERE explorekl_nl_category='nm'  ORDER BY explorekl_nl_order DESC  ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/nl/<?php echo $row['explorekl_nl_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_nl_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_nl_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_nl_content']) ?></p>

                                    <?php if ($row['explorekl_nl_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_nl_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_nl_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_nl_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_phone']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-telephone-fill" style="color: black;"></i>
                                        <?php echo urldecode($row['explorekl_nl_phone']) ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_nl_website']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_nl_website'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_nl_website']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#nmCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#nmCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div><!-- End Tab 3 Content -->






                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tab Content 5 -->





          <div class="tab-pane" id="tab-6">

            <div class="row gy-10 mb-4">
              <div class="col-12 text-center">
                <h3>KL 4 Kids</h3>
                </p>
              </div>
            </div>

            <div id="kl4kCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM explorekl_kl4k";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#kl4kCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM explorekl_kl4k ORDER BY explorekl_kl4k_order DESC";
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
                      <img src="assets/img/explorekl/kl4k/<?php echo $row['explorekl_kl4k_image'] ?>"
                        alt="<?php echo urldecode($row['explorekl_kl4k_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_kl4k_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_kl4k_content']) ?></p>
                        <?php if ($row['explorekl_kl4k_content2']) { ?>
                          <div class="collapse" id="collapseKL4K1-<?php echo $counter ?>">
                            <?php echo urldecode($row['explorekl_kl4k_content2']) ?>

                          </div>
                          <p>
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapseKL4K1-<?php echo $counter ?>" aria-expanded="false"
                              aria-controls="collapseExample">
                              Read More
                            </button>

                          </p>

                        <?php } ?>

                        <?php if ($row['explorekl_kl4k_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_kl4k_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_kl4k_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_kl4k_hours']) { ?>
                          <p class="mb-0">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_kl4k_hours'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_kl4k_phone']) { ?>
                          <p class="mb-0">
                            <i class="bi bi-telephone-fill" style="color: black;"></i> <?php echo $row['explorekl_kl4k_phone'] ?>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_kl4k_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_kl4k_websiteurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_kl4k_website']) ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#kl4kCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#kl4kCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

          </div><!-- End Tab Content 6 -->





          <div class="tab-pane" id="tab-7">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Sightseeing</h3>

                </p>
              </div>

              <!-- ======= About Section ======= -->
              <div class="container" data-aos="fade-up">


                <div class="row g-4 g-lg-5" data-aos="fade-up" data-aos-delay="200">


                  <div class="col-lg-12">

                    <!-- Tabs -->
                    <ul class="nav nav-pills mb-3 justify-content-center">
                      <li><a class="nav-link mx-4 active" data-bs-toggle="pill" href="#tabg1">Must Visit</a></li>
                      <li><a class="nav-link mx-4" data-bs-toggle="pill" href="#tabg2">Memorial & Museums</a></li>
                      <li><a class="nav-link mx-4" data-bs-toggle="pill" href="#tabg3">KL Art Scene</a></li>

                    </ul><!-- End Tabs -->

                    <!-- Tab Content -->
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="tabg1">
                        <!-- Carousel Wrapper -->
                        <div id="ssmvCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_ss WHERE explorekl_ss_category='mv' ";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#nlCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_ss WHERE explorekl_ss_category='mv'  ORDER BY explorekl_ss_order DESC  ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/ss/<?php echo $row['explorekl_ss_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_ss_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_ss_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_ss_content']) ?></p>

                                    <?php if ($row['explorekl_ss_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_ss_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_ss_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_ss_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_phone']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-telephone-fill" style="color: black;"></i>
                                        <?php echo urldecode($row['explorekl_ss_phone']) ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_website']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_ss_websiteurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_ss_website']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#ssmvCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#ssmvCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div><!-- End Tab 1 Content -->


                      <div class="tab-pane fade" id="tabg2">
                        <!-- Carousel Wrapper -->
                        <div id="mmCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_ss WHERE explorekl_ss_category='mm'";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#mmCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_ss WHERE explorekl_ss_category='mm' ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/ss/<?php echo $row['explorekl_ss_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_ss_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_ss_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_ss_content']) ?></p>

                                    <?php if ($row['explorekl_ss_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_ss_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_ss_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_ss_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_phone']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-telephone-fill" style="color: black;"></i>
                                        <?php echo urldecode($row['explorekl_ss_phone']) ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_website']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_ss_websiteurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_ss_website']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#mmCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#mmCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div><!-- End Tab 1 Content -->


                      <div class="tab-pane fade" id="tabg3">
                        <!-- Carousel Wrapper -->
                        <div id="artCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                          <!-- Carousel Indicators (Optional) -->
                          <div class="carousel-indicators">
                            <?php
                            // First, get the total number of items to create indicators
                            $query_count = "SELECT COUNT(*) as total FROM explorekl_ss WHERE explorekl_ss_category='art'";
                            $result_count = mysqli_query($db, $query_count);
                            $total_items = mysqli_fetch_assoc($result_count)['total'];
                            $items_per_slide = 2; // Adjust based on how many items you want per slide
                            $total_slides = ceil($total_items / $items_per_slide);

                            for ($i = 0; $i < $total_slides; $i++) {
                              $active_class = ($i == 0) ? 'active' : '';
                              echo '<button type="button" data-bs-target="#artCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                            }
                            ?>
                          </div>

                          <!-- Carousel Inner (Slides) -->
                          <div class="carousel-inner">
                            <?php
                            $query = "SELECT * FROM explorekl_ss WHERE explorekl_ss_category='art'  ORDER BY explorekl_ss_order DESC  ";
                            $result = mysqli_query($db, $query);
                            $counter = 1;
                            $items_per_slide = 2; // Number of items per slide
                            $slide_count = 0;

                            while ($row = mysqli_fetch_assoc($result)) {
                              // Start a new slide every 'items_per_slide' items
                              if ($counter % $items_per_slide == 1) { // e.g., 1, 3, 5, ...
                                $active_class = ($slide_count == 0) ? 'active' : ''; // First slide is active
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row g-4">'; // Start row for items in this slide
                                $slide_count++;
                              }
                            ?>
                              <!-- Single Item -->
                              <div class="col-md-6"> <!-- Each item takes half the width on medium screens and up -->
                                <div class="card h-100 border-0 shadow-sm rounded-3">
                                  <img src="assets/img/explorekl/ss/<?php echo $row['explorekl_ss_image'] ?>"
                                    alt="<?php echo urldecode($row['explorekl_ss_title']) ?>"
                                    class="card-img-top img-fluid rounded-top-4" loading="lazy">




                                  <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_ss_title']) ?></h4>
                                    <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_ss_content']) ?></p>

                                    <?php if ($row['explorekl_ss_location']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_ss_locationurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_ss_location']) ?>
                                        </a>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_hours']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_ss_hours'] ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_phone']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-telephone-fill" style="color: black;"></i>
                                        <?php echo urldecode($row['explorekl_ss_phone']) ?>
                                      </p>
                                    <?php } ?>

                                    <?php if ($row['explorekl_ss_website']) { ?>
                                      <p class="mb-2">
                                        <i class="bi bi-globe" style="color: black;"></i>
                                        <a href="<?php echo $row['explorekl_ss_websiteurl'] ?>" target="_blank">
                                          <?php echo urldecode($row['explorekl_ss_website']) ?>
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
                          <button class="carousel-control-prev" type="button" data-bs-target="#artCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#artCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                      </div><!-- End Tab 1 Content -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tab Content 7 -->


          <div class="tab-pane" id="tab-8">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Parks</h3>
                </p>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="pCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (Optional) -->
              <div class="carousel-indicators">
                <?php
                // First, get the total number of items to create indicators
                $query_count = "SELECT COUNT(*) as total FROM explorekl_p";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2; // Adjust based on how many cards you want per slide
                $total_slides = ceil($total_items / $items_per_slide);

                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#pCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM explorekl_p ORDER BY explorekl_p_order DESC ";
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
                      <img src="assets/img/explorekl/p/<?php echo $row['explorekl_p_image'] ?>"
                        alt="<?php echo urldecode($row['explorekl_p_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['explorekl_p_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['explorekl_p_content']) ?></p>

                        <?php if ($row['explorekl_p_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo $row['explorekl_p_locationurl'] ?>" target="_blank">
                              <?php echo urldecode($row['explorekl_p_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['explorekl_p_hours']) { ?>
                          <p class="mb-0">
                            <i class="bi bi-clock-fill" style="color: black;"></i> <?php echo $row['explorekl_p_hours'] ?>
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
              <button class="carousel-control-prev" type="button" data-bs-target="#pCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#pCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 2 -->
        </div>
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
        const carouselElement = document.getElementById('wtdCarousel');
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