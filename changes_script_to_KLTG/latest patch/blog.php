<?php
include('admin/functions.php');

$query = "SELECT SUM(blog_view) FROM blog ";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {


  $_SESSION['totalviews'] = $row['SUM(blog_view)'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Blog</title>
  <link rel="canonical" href="https://kltheguide.com.my/blog.php/" />

  <meta
    content="Stay ahead of the curve and be up to date with the freshest content form our blog. Discover insightful articles, helpful guides and many more to keep you informed and inspired. Don’t miss out on the latest happenings – explore our blog and stay connected!"
    name="description">
  <meta content="" name="keywords">

  <meta itemprop="name" content="KL The Guide - Blog">
  <meta itemprop="description"
    content="Stay ahead of the curve and be up to date with the freshest content form our blog. Discover insightful articles, helpful guides and many more to keep you informed and inspired. Don’t miss out on the latest happenings – explore our blog and stay connected!">
  <meta itemprop="image"  content="https://www.kltheguide.com.my/assets/img/kltgseoblog.jpeg">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/blog.php" />
  <meta property="og:title" content="KL The Guide - Blog" />
  <meta property="og:description"
    content="Stay ahead of the curve and be up to date with the freshest content form our blog. Discover insightful articles, helpful guides and many more to keep you informed and inspired. Don’t miss out on the latest happenings – explore our blog and stay connected!" />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseoblog.jpeg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/blog.php" />
  <meta property="twitter:title" content="KL The Guide - Blog" />
  <meta property="twitter:description"
    content="Stay ahead of the curve and be up to date with the freshest content form our blog. Discover insightful articles, helpful guides and many more to keep you informed and inspired. Don’t miss out on the latest happenings – explore our blog and stay connected!" />
  <meta property="twitter:image" content="assets/img/kltgseoblog.jpeg" />
  <link rel="alternate" type="application/rss+xml" href="https://www.kltheguide.com.my/blog-list.xml" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <?php include 'header.php'; ?>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'nav.php'; ?>

  <main id="blog">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs"> 
      <div class="container">
      <!--//200126-->
        <div class="d-flex justify-content-end align-items-center">
          <div class="p-1 row rounded position-relative w-100">   
            <h2 class="col start-0 d-none d-sm-inline">Blog</h2>
            <input placeholder="search content or title" type="text" id="search_blog" class = "px-2 d-inline rounded border-primary" style = "width:200px;"/>
            <span id="clearBtn" class="position-relative btn-close" style = "margin-left:-30px; margin-top: 12px; font-size:13px;width:20px;"></span>
            <button type="button" id="submit_search_blog" class = "d-flex text-light btn-primary justify-content-center" style = "width:50px;">
              <i class=" bi bi-search text-light rounded-start"></i>
            </button>
          </div>
          <div class="mx-2"></div>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Blog</li>
          </ol>
        </div>
        <!--end-->
      </div>
    </div><!-- End Breadcrumbs -->
    <div id = "spin2progress" class="ms-2 text-dark"></div>
    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
      <div class="container">

        <div class="row g-5">

          <div class="col-lg-8">

            <div class="row gy-4 posts-list" id="postlist">


            </div><!-- End blog posts list -->

            <div class="blog-pagination">
              <ul class="justify-content-center " id="blog-paginationid">


                <!-- <li><a href="#">3</a></li> -->
              </ul>
            </div><!-- End blog pagination -->

          </div>

          <div class="col-lg-4 d-none d-lg-block">

            <div class="sidebar">

              <div class="sidebar-item">
                <h3 class="sidebar-title">Total Views</h3>
                <p class="text fw-bold fs-1 text-center">
                  <?php echo $_SESSION['totalviews']; ?>
                </p>
                <p class=" text-center">views</p>
              </div>

              <div class="sidebar-item tags">
                <div class="row">
                  <div class="col">
                    <h3 class="sidebar-title">Tags </h3>

                  </div>
                  <div class="col d-flex align-items-end justify-content-end">
                    <h3 class="sidebar-title "><a id="resetTags" href="#" class="">Reset <i class="bi bi-x-square-fill"></i></a></h3>

                  </div>
                </div>

                <ul class="mt-3" id="tagdiv">
                  <div class="spinner-border text-primary text-center d-none" role="status" id="tagspinner">
                    <span class="sr-only"></span>
                  </div>
                </ul>
                
              </div>


              <div class="sidebar-item recent-posts">
                <h3 class="sidebar-title">Recent Posts</h3>

                <div class="mt-3" id="recentpost">



                  <!-- End recent post item-->


                </div>

              </div><!-- End sidebar recent posts-->



            </div><!-- End Blog Sidebar -->

          </div>

        </div>

      </div>
      <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <div class="row">
                <div class="col">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tags</h1>

                </div>
                <div class="col-auto  ms-5">
                <a class="fs-5 " href="#">Reset <i class="bi bi-x-square-fill"></i></a>
                </div>
              </div>
              <button type="button" class="btn-close d-flex" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="tagdiv2">

            </div>
            <div class="modal-footer">

            </div>
          </div>
        </div>
      </div>
    </section><!-- End Blog Section -->


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'footer.php'; ?>
  <!-- End Footer -->
  <a href="#" class="search-button d-flex align-items-center justify-content-center d-block d-lg-none" data-bs-toggle="modal"
    data-bs-target="#exampleModal"><i class="bi bi-filter"></i></a>
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!--<div id="preloader"></div>-->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>
  <script src="assets/js/blog2.js?v=210126n01"></script>

  <script>
    <?php
    if (isset($_GET['tags'])) {
      $tags = $_GET['tags'];

    } else {
      $tags = "";
    }
    if (isset($_GET['page'])) {
      $page = $_GET['page'];

    } else {
      $page = 1;
    }
    ?>
    window.addEventListener("load", (event) => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      })
    });


    //200126
    document.getElementById("resetTags").addEventListener('click',(e) => {
      window.addEventListener("scroll", handleInfiniteScroll);
    })

    document.getElementById("submit_search_blog").addEventListener('click',(e) =>
    { 
      if(document.getElementById("search_blog").value) {
        if(document.getElementById('search_text_progress')) {
            return;
        }
       
        var searchingBlogSpinner =  
        '<div id="search_text_progress" class="spinner-border text-secondary position-absolute mx-auto" role="status" id="spin-search-1">' +
        '<span class="sr-only"></span>' +
        "</div>";
        const searchbutton = document.getElementById("submit_search_blog");
        searchbutton.insertAdjacentHTML("afterbegin", searchingBlogSpinner); 
      
        const result = searchbyText(document.getElementById("search_blog").value);
      
      } else {
        alert('searchbox is empty')
      }
    });


    document.getElementById("clearBtn").addEventListener('click',(e) =>
    { 
      document.getElementById("search_blog").value= '';
    });




    //end
    initblog3();
    gettags();
    recentpost();



    window.addEventListener(
      "hashchange",
      () => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        })
        // const blogposthtml = document.getElementById("postlist");

        // blogposthtml.innerHTML = "";
        initblog3();
      },
      false,
    );
    const handleInfiniteScroll = () => {
      if (document.getElementById("spin2")) {

        var spinspinaround = document.getElementById("spin2").offsetTop;
      }
      throttle(() => {
  
        const endOfPage = window.innerHeight + window.pageYOffset >= spinspinaround;
        if (endOfPage) {
          nextpageblog3();

        }
      }, 1000);
    };

    window.addEventListener("scroll", handleInfiniteScroll);
    document.getElementById("blognavlink").classList.add("active");
   


  
  </script>
</body>

</html>