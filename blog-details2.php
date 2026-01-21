<?php include('admin/functions.php'); ?>

<?php

$postid = $_GET['postid'];
$url = 'https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts/' . $postid . '?key=AIzaSyC7NA9vDhkVtk4lWisJxGW--fYXLIeM__w&fetchImages=true';
$json_data = file_get_contents($url);
$response_data = json_decode($json_data);
$title = json_decode($json_data)->title;
$image = json_decode($json_data)->images[0]->url;
$urlmeta = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$datepub = json_decode($json_data)->published;
// echo $title;

$schema = array(
  '@context'  => 'http://schema.org',
  '@type'     => 'BlogPosting',
  'headline'      => $title,
  'description' => $description,
  'image' => $image,
  'datePublished'=> $datepub,
  'dateModified'=> $datepub,
  'author'   => array(
      '@type'           => 'Organization',
      'name'            => 'Bludate',
      'url'          => 'https://www.bluedale.com.my'

  ),
);



// var_dump($image);
$title2 = urlencode($title);
$query = "SELECT blog_postid FROM blog WHERE blog_postid='$postid'";
// debug_to_console($query);
$update = mysqli_query($db, $query);
if ($update) {
  $rowcount = mysqli_num_rows($update);
  if ($rowcount == 1) {

    // echo "exist";

  } else {
    $view = 0;
    $viewsettings = 0;
    // echo "not yet";


    $query = "INSERT INTO blog (blog_postid, blog_title, blog_view, blog_viewsettings) 
    VALUES('$postid', '$title2', '$view', '$viewsettings')";
    $update = mysqli_query($db, $query);
    if ($update) {
      // debug_to_console("test");
      // echo "sucess";
      // array_push($errors2, "Success");

    } else {
      // echo mysqli_error($db);

      // array_push($errors2, "No Update");
    }
  }
} else {


}
?>

<!DOCTYPE html>
<html lang="en">


<head>
  <title>KL The Guide - <?php echo $title; ?></title>

  <meta content="<?php echo $title; ?>" name="description">

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
            <li><a href="blog.php#blog">Blog</a></li>
            <li id="blogbreadcrumbs"></li>
            <li style="display:none" id="hidid"><?php echo $postid; ?></li>

          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Blog Details Section ======= -->
    <section id="blog-details" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row g-5">


          <div class="col-lg-8">



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

          <div class="col-lg-4">

            <div class="sidebar">

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

              <div class="sidebar-item recent-posts">
                <h3 class="sidebar-title">Recent Posts</h3>

                <div class="mt-3" id="recentpost">


                </div>

              </div><!-- End sidebar recent posts-->

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

            </div><!-- End Blog Sidebar -->

          </div>
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
  <script src="assets/js/blog-details2.js"></script>


</body>

</html>