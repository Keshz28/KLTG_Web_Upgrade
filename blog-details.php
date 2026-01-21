<?php include('admin/functions.php'); ?>

<?php

$postid = $_GET['postid'];

// echo "a is " . is_numeric($postid) . "<br>";

$postid = $_GET['postid'] ?? '';

if (is_numeric($postid)) {
//   $url = "fetch_blogger.php?postid=" . urlencode($postid);
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
  $url = rtrim($baseUrl, '/') . '/fetch_blogger.php?postid=' . urlencode($postid);
  $json_data = file_get_contents($url);
  $response_data = json_decode($json_data);

  if ($response_data && !isset($response_data->error)) {
      // Use response safely
      $title   = $response_data->title ?? 'Untitled Post';
      $image   = $response_data->images[0]->url ?? 'assets/img/default.jpg';
      $urlmeta = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
      $datepub = $response_data->published ?? date('c');

      $schema = [
          '@context' => 'http://schema.org',
          '@type'    => 'BlogPosting',
          'headline' => $title,
          'description' => $title,
          'image'    => $image,
          'datePublished' => $datepub,
          'dateModified'  => $datepub,
          'author'   => [
              '@type' => 'Organization',
              'name'  => 'Bluedale',
              'url'   => 'https://www.bluedale.com.my'
          ],
      ];

      // Insert blog post if not exists
      $title2 = $title; // store raw title
      $stmt = $db->prepare("SELECT blog_postid FROM blog WHERE blog_postid = ?");
      $stmt->bind_param("i", $postid);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows === 0) {
          $view = 0;
          $viewsettings = 0;
          $stmt = $db->prepare("INSERT INTO blog (blog_postid, blog_title, blog_view, blog_viewsettings) VALUES (?, ?, ?, ?)");
          $stmt->bind_param("isii", $postid, $title2, $view, $viewsettings);
          $stmt->execute();
      }
  } else {
      // API error
      $title = "Post not found";
      $image = "assets/img/default.jpg";
      $datepub = date('c');
      $urlmeta = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
      $schema = [];
  }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
  <title>KL The Guide - <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>

  <meta content="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" name="description">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?php echo htmlspecialchars($urlmeta, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:title" content="KL The Guide - <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:description" content="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="og:image" content="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" />

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="<?php echo htmlspecialchars($urlmeta, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="twitter:title" content="KL The Guide - <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="twitter:description" content="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" />
  <meta property="twitter:image" content="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" />

  <link rel="alternate" type="application/rss+xml" href="https://www.kltheguide.com.my/blog-list.xml" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <?php echo '<script type="application/ld+json">' . json_encode($schema) . '</script>'; ?>

  <?php include 'header.php'; ?>
</head>


<body>

  <!-- ======= Header ======= -->
  <?php include 'nav.php'; ?>

  <main>

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Blog Details</h2>
          <ol>
            <li><a href="index.php#index">Home</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li id="blogbreadcrumbs"></li>
            <li style="display:none" id="hidid"><?php echo htmlspecialchars($postid, ENT_QUOTES, 'UTF-8'); ?></li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Blog Details Section ======= -->
    <section id="blog-details" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row g-5">


          <div class="col-lg-12">



            <article class="blog-details" id="article">



            </article><!-- End blog post -->

            <!-- <div class="post-author d-flex align-items-center">
                        <img src="assets/img/blog/blog-author.jpg" class="rounded-circle flex-shrink-0" alt="">
                        <div>
                          <h4>Jane Smith</h4>
                          <div class="social-links">
                            <a href="https://twitters.com/#"><i class="bi bi-twitter"></i></a>
                            <a href="https://facebook.com/#"><i class="bi bi-facebook"></i></a>
                            <a href="https://instagram.com/#"><i class="biu bi-instagram"></i></a>
                          </div>
                          <p>
                            Itaque quidem optio quia voluptatibus dolorem dolor. Modi eum sed possimus accusantium. Quas repellat voluptatem officia numquam sint aspernatur voluptas. Esse et accusantium ut unde voluptas.
                          </p>
                        </div>
                      </div> -->
            <!-- End post author -->

          </div>

          <!-- <div class="col-lg-4"> -->

            <!-- <div class="sidebar"> -->

              <!--              <div class="sidebar-item search-form">
                <h3 class="sidebar-title">Search</h3>
                <form action="" class="mt-3">
                  <input type="text">
                  <button type="submit"><i class="bi bi-search"></i></button>
                </form>
              </div> -->
              <!-- End sidebar search formn-->
              <!-- 
              <div class="sidebar-item categories">
                <h3 class="sidebar-title">Categories</h3>
                <ul class="mt-3">
                  <li><a href="#">General <span>(25)</span></a></li>
                  <li><a href="#">Lifestyle <span>(12)</span></a></li>
                  <li><a href="#">Travel <span>(5)</span></a></li>
                  <li><a href="#">Design <span>(22)</span></a></li>
                  <li><a href="#">Creative <span>(8)</span></a></li>
                  <li><a href="#">Educaion <span>(14)</span></a></li>
                </ul>
              </div> -->
              <!-- End sidebar categories-->

              <!-- <div class="sidebar-item recent-posts">
                <h3 class="sidebar-title">Recent Posts</h3>

                <div class="mt-3" id="recentpost">


                </div>

              </div> -->
              <!-- End sidebar recent posts-->

              <!-- <div class="sidebar-item tags">
                <h3 class="sidebar-title">Tags</h3>
                <ul class="mt-3">
                  <li><a href="#">App</a></li>
                  <li><a href="#">IT</a></li>
                  <li><a href="#">Business</a></li>
                  <li><a href="#">Mac</a></li>
                  <li><a href="#">Design</a></li>
                  <li><a href="#">Office</a></li>
                  <li><a href="#">Creative</a></li>
                  <li><a href="#">Studio</a></li>
                  <li><a href="#">Smart</a></li>
                  <li><a href="#">Tips</a></li>
                  <li><a href="#">Marketing</a></li>
                </ul>
              </div> -->
              <!-- End sidebar tags-->

            <!-- </div> -->
            <!-- End Blog Sidebar -->

          <!-- </div> -->
        </div>

      </div>
    </section><!-- End Blog Details Section -->

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
  <script src="assets/js/blog-details.js"></script>


</body>

</html>