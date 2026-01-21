<?php include('admin/functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - E-book</title>
  <link rel="canonical" href="https://kltheguide.com.my/ebook.php/" />

  <meta name="description"
    content="The fastest guide to Kuala Lumpur. A guide to the essentials of Kuala Lumpur that helps you hit the ground running on your trip. A practical e-book of things to do and see in Kuala Lumpur, Malaysia.">
  <meta name="keywords" content="Ebook, KL The Guide Tawau Uzbekistan Kazakhstan QR Code Ebook">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/ebook.php" />
  <meta property="og:title" content="KL The Guide - E-book" />
  <meta property="og:description"
    content="The fastest guide to Kuala Lumpur. A guide to the essentials of Kuala Lumpur that helps you hit the ground running on your trip. A practical e-book of things to do and see in Kuala Lumpur, Malaysia." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseoebook.jpeg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/ebook.php" />
  <meta property="twitter:title" content="KL The Guide - E-book" />
  <meta property="twitter:description"
    content="The fastest guide to Kuala Lumpur. A guide to the essentials of Kuala Lumpur that helps you hit the ground running on your trip. A practical e-book of things to do and see in Kuala Lumpur, Malaysia." />
  <meta property="twitter:image" content="assets/img/kltgseoebook.jpeg" />


  <?php include 'header.php'; ?>



</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'nav.php'; ?>

  <main id="ebook">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>E-book</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>E-Book</li>

          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->




    <!-- ======= KLTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>KL The Guide</h2>
          <p>Check out our extensive library of KL The Guide eBook, exclusively created to plan for your upcoming trip
            to KL. Our eBooks provide everything you need to organize your ideal trip to KL, whether you’re looking for
            detailed itineraries, insider tips, or in-depth cultural insights.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper gy-5">
            <div class="swiper-wrapper align-items-center">

              <?php
              $query = "SELECT e.*
                FROM ebook e
                JOIN (
                    SELECT DISTINCT ebook_year_published
                    FROM ebook
                    WHERE ebook_category = 'kltg'
                    ORDER BY ebook_year_published DESC
                    LIMIT 2
                ) AS y ON e.ebook_year_published = y.ebook_year_published
                WHERE e.ebook_category = 'kltg'
                ORDER BY e.ebook_id DESC; ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member ">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>
                </div>

                <?php
              }
              ?>
            </div>

            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->

    <!-- ======= KV4L Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Klang Valley 4 Locals</h2>
          <p>The Klang Valley 4 Locals eBooks are exclusive guides that are specially crafted for locals, helping them
            to uncover the hidden gems and unique experiences within the vibrant Klang Valley region. Whether you’re a
            local looking to discover the facets of Klang Valley or a visitor seeking an authentic local experience, the
            Klang Valley 4 Locals eBooks are your ultimate guide.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='kv4l' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->


    <!-- ======= MKTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Melaka The Guide</h2>
          <p>Experience the enchanting city of Melaka like never before with Melaka The Guide eBooks! Our meticulously
            crafted guides offer the best recommendations and insights for those seeking to explore the rich history and
            cultural wonders of the beautiful historical city. </p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='mktg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->



    <!-- ======= TPTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Taiping The Guide</h2>
          <p>Discover the charming town of Taiping with Taiping The Guide eBooks. Let Taiping The Guide eBooks be your
            companion as your embark on a remarkable journey through Taiping’s storied past and vibrant present</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='tptg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->


    <!-- ======= UZTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Uzbekistan The Guide</h2>
          <p>Embark on a remarkable journey to the enchanting country of Uzbekistan with Uzbekistan The Guide eBook.
            With just a tap of your finger, our comprehensive eBook allows you to plan and explore this beautiful
            country with ease.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='uztg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->

    <!-- ======= KNTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Keningau The Guide</h2>
          <p>Discover the ultimate travel companion for your upcoming journey to Keningau! Check our Keningau The Guide
            eBook, a comprehensive travel guide to help you plan and make the most of your visit to this captivating
            destination.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='kntg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->

    <!-- ======= TWTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Tawau The Guide</h2>
          <p>With one click, unlock a treasure trove of invaluable information and expert recommendations with Tawau The
            Guide eBook! Whether you’re a first-time visitor or a seasoned traveler, let ‘Tawau The Guide’ be your
            trusted companion, providing you with all the tools you need to create unforgettable memories in the
            mesmerizing Tawau town.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='twtg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->

    <!-- ======= TBTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Tambunan The Guide</h2>
          <p>Immerse yourself in the rich culture, vibrant traditions, and breathtaking landscapes that Tambunan has to
            offer. From must-see attractions and hidden gems to local dining spots and off-the-beaten-path adventures,
            Tambunan The Guide eBook has it all!</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='tbtg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>

            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->

    <!-- ======= HSTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Hulu Selangor The Guide</h2>
          <p>Immerse yourself in the natural beauty, historical landmarks, and cultural richness of this hidden gem. Our
            meticulously curated eBooks provide the best guides and recommendations for those looking to explore Hulu
            Selangor’s unique offerings. </p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='hstg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->


    <!-- ======= PRTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Perak The Guide</h2>
          <p>Embark on an extraordinary journey through Perak’s wonders with Perak The Guide eBooks, your ultimate
            companion for an unforgettable experience at this beautiful state. </p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center  ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='prtg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>
          </div>
        </div>



      </div>
    </section><!-- End Team Section -->

    <!-- ======= SBTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Seremban The Guide</h2>
          <p>Unlock the wonders of Seremban with Seremban The Guide eBook. Discover this charming city and everything
            that Seremban have to offer, right at your fingertips. Experience Seremban like a local with Seremban The
            Guide eBook, your virtual companion to this captivating city.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='sbtg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->


    <!-- ======= KSTG Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Kuala Selangor The Guide</h2>
          <p>Discover Kuala Selangor like never before with our comprehensive Kuala Selangor The Guide eBook! Our eBook
            simplifies your trip planning process, making it a breeze to organize your next unforgettable journey with
            just a click away.</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='kstg' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>

          </div>
        </div>



      </div>
    </section><!-- End Team Section -->


    <!-- ======= KLGT Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Kuala Langat The Guide</h2>
          <p>Explore the enchanting town of Kuala Langat with Kuala Langat The Guide. Inside our eBook, you’ll find
            everything you need to know to make the most of your trip in Kuala Langat!</p>
        </div>

        <div class="row gy-5">
          <div class="clients-slider swiper">
            <div class="swiper-wrapper align-items-center ">

              <?php
              $query = "SELECT * FROM ebook WHERE ebook_category='klgt' ORDER BY ebook_id DESC ";
              $result = mysqli_query($db, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="swiper-slide">
                  <br>

                  <div class="team-member">
                    <div class="member-img mx-auto">
                      <img src="assets/img/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_image'] ?>"
                        class="img-fluid" alt="<?php echo $row['ebook_name'] ?>" loading="lazy">
                    </div>
                    <div class="member-info">

                      <div class="social">
                        <?php if (!$row['ebook_url']) { ?>
                          <?php if ($row['ebook_filename']) { ?>

                            <a href="ebook-details.php?id=<?php echo $row['ebook_id'] ?>"><i class="bi bi-book"></i></a>
                          <?php } else ?>
                        <?php } else { ?>
                          <a href="<?php echo $row['ebook_url'] ?>"><i class="bi bi-book"></i></a>
                        <?php } ?>
                        <?php if ($row['ebook_filename']) { ?>

                          <a href="assets/pdf/ebook/<?php echo $row['ebook_category'] ?>/<?php echo $row['ebook_filename'] ?>"
                            download><i class="bi bi-download"></i></a>
                        <?php } ?>


                      </div>

                      <h4>
                        <?php echo $row['ebook_name'] ?>
                      </h4>
                    </div>
                  </div>
                  <br>

                </div>
                <?php
              }
              ?>
            </div>
            <div class="swiper-scrollbar"></div>
          </div>
        </div>



      </div>
    </section><!-- End Team Section -->


    <!-- End Team Section -->


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

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>