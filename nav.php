<?php
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



$travelnews_check_query = "SELECT * FROM highlights WHERE highlights_category='travelnews' LIMIT 1";
$result = mysqli_query($db, $travelnews_check_query);
$travelnews = mysqli_fetch_assoc($result);


?>
<!-- ── Transparent-over-video navbar (only active when body has .has-video-hero) ── -->
<style>
  /* Smooth transitions for any state change */
  #header,
  #header .navbar > ul > li > a,
  #header .navbar .dropdown > a,
  #header .logo img,
  #header .btn-getstarted {
    transition: background-color .35s ease, color .3s ease, box-shadow .35s ease, filter .35s ease, border-color .3s ease;
  }

  /* Transparent state: applied only on pages with .has-video-hero, while at top of page and not hovered */
  body.has-video-hero #header.header-transparent {
    background-color: transparent !important;
    box-shadow: none !important;
    backdrop-filter: none !important;
  }

  /* Light/white text + indicators when transparent */
  body.has-video-hero #header.header-transparent .navbar > ul > li > a,
  body.has-video-hero #header.header-transparent .navbar .dropdown > a,
  body.has-video-hero #header.header-transparent .navbar > ul > li > a > span,
  body.has-video-hero #header.header-transparent .navbar .dropdown-indicator {
    color: #fff !important;
  }

  /* Underline accent on active link should still show, but white */
  body.has-video-hero #header.header-transparent .navbar .active,
  body.has-video-hero #header.header-transparent .navbar .active:focus,
  body.has-video-hero #header.header-transparent .navbar li:hover > a {
    color: #fff !important;
  }

  /* Logo: invert to white silhouette over the dark video */
  body.has-video-hero #header.header-transparent .logo img {
    filter: brightness(0) invert(1);
  }

  /* Contact-us pill: glass-style outline so it's readable on video */
  body.has-video-hero #header.header-transparent .btn-getstarted {
    background-color: rgba(255, 255, 255, .14) !important;
    border: 1.5px solid rgba(255, 255, 255, .55) !important;
    color: #fff !important;
    backdrop-filter: blur(6px);
  }

  body.has-video-hero #header.header-transparent .btn-getstarted:hover {
    background-color: rgba(255, 255, 255, .25) !important;
    border-color: #fff !important;
  }

  /* Mobile-nav toggle (burger) becomes white when transparent */
  body.has-video-hero #header.header-transparent .mobile-nav-toggle {
    color: #fff !important;
  }
</style>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top<?php echo in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'travel-tips.php', 'kl-glance.php', 'getting-around-kl.php']) ? ' header-transparent' : ''; ?>" data-scrollto-offset="0">
  <div class="container-fluid d-flex align-items-center justify-content-between">

    <a href="index.php#index" class="logo d-flex align-items-center scrollto me-auto me-lg-0">
      <!-- Uncomment the line below if you also wish to use an image logo -->
      <img src="assets/img/LogoNav.png" alt="Logo KL The Guide">
      <!-- <h1>HeroBiz<span>.</span></h1> -->
    </a>

    <nav id="navbar" class="navbar">
      <ul>

        <li><a class="nav-link scrollto" href="index.php#index">Home</a></li>
        <li><a class="nav-link scrollto" href="aboutus.php#aboutus">About Us</a></li>
        <li class="dropdown"><a href="#"><span>Pages</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
          <ul>
            <!-- <li><a href="highlights.php#tab-1">KL Highlights</a></li> -->
            <li class="dropdown"><a href="highlights.php"><span>
                  <?php echo $tile1_title ?>
                </span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li><a href="highlights.php#tab-1">
                    <?php echo $tile1_title1 ?>
                  </a></li>
                <li><a href="highlights.php#tab-2">
                    <?php echo $tile1_title2 ?>
                  </a></li>
                <li><a href="highlights.php#tab-3">
                    <?php echo $tile1_title3 ?>
                  </a></li>
                <?php

                if ($travelnews) {

                  ?>
                  <li><a href="highlights.php#tab-4">
                      <?php echo $tile1_title4 ?>
                    </a></li>

                  <?php
                }
                ?>

              </ul>
            </li>
            <li class="dropdown"><a href="explorekl.php#explorekl"><span><?php echo $tile2_title1 ?></span> <i
                  class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li><a href="explorekl.php#tab-1">What To Do</a></li>
                <li><a href="explorekl.php#tab-2">Historical Sites</a></li>
                <li><a href="explorekl.php#tab-3">Places Of Worship</a></li>
                <li><a href="explorekl.php#tab-4">What To Eat</a></li>
                <li><a href="explorekl.php#tab-5">Night Life</a></li>
                <li><a href="explorekl.php#tab-6">KL 4 Kids</a></li>
                <li><a href="explorekl.php#tab-7">Sightseeing</a></li>
                <li><a href="explorekl.php#tab-8">Parks</a></li>

              </ul>
            </li>
            <li class="dropdown"><a href="beyondkl.php#beyondkl"><span><?php echo $tile2_title6 ?></span> <i
                  class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li><a href="beyondkl.php#tab-1">Islands</a></li>
                <li><a href="beyondkl.php#tab-2">Hill Station</a></li>
                <li><a href="beyondkl.php#tab-3">Waterfall</a></li>
                <li><a href="beyondkl.php#tab-4">Hiking</a></li>
                <li><a href="beyondkl.php#tab-5">Extreme Sports</a></li>
              </ul>
            </li>
            <!-- <li><a href="medical-tourism.php#tab-1">Medical Tourism</a></li> -->
            <li class="dropdown"><a href="medical-tourism.php#medicaltourism"><span><?php echo $tile2_title5 ?></span> <i
                  class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li><a href="medical-tourism.php#tab-1">Healthcare</a></li>
                <li><a href="medical-tourism.php#tab-2">Dental</a></li>
                <li><a href="medical-tourism.php#tab-3">Dermatologist</a></li>
                <li><a href="medical-tourism.php#tab-4">Ophthalmologist</a></li>
                <li><a href="medical-tourism.php#tab-5">Plastic Surgery</a></li>
              </ul>
            </li>
            <li><a href="where-to-shop.php"><?php echo $tile2_title2 ?></a></li>
            <li><a href="spa.php"><?php echo $tile2_title4 ?></a></li>
            <li class="dropdown"><a href="accommodation.php#placetostay"><span><?php echo $tile2_title3 ?></span> <i
                  class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li><a href="accommodation.php#tab-1">Top Places To Stay</a></li>
                <li><a href="accommodation.php#tab-2">Hotels</a></li>
                <li><a href="accommodation.php#tab-3">Budget Hotels</a></li>
                <li><a href="accommodation.php#tab-4">Backpackers Lodge</a></li>
              </ul>
            </li>
            <!-- <li><a href="#">Place To Stay</a></li> -->
          </ul>
        </li>
        <li><a class="nav-link scrollto" href="ebook.php#ebook">E-Book</a></li>
        <li><a class="nav-link scrollto" href="blog.php" id="blognavlink">Blog</a></li>
        <li><a class="nav-link scrollto" href="map.php#map">Map</a></li>
        <li><a class="nav-link scrollto" href="event.php">Event</a></li>
        <li><a class="nav-link scrollto" href="merchandise.php">Merchandise</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle d-none"></i>
    </nav><!-- .navbar -->

    <!-- <a class="btn-getstarted " href="index.html#about"><i class="bi bi-whatsapp"></i></a> -->
    <a class="btn-getstarted " href="http://www.wasap.my/60122200622" target="blank"><i class="bi bi-whatsapp"></i>
      Contact Us</a>

  </div>
</header><!-- End Header -->

<!-- ── Transparent navbar: scroll + hover toggling (only runs on .has-video-hero pages) ── -->
<script>
  (function () {
    if (!document.body.classList.contains('has-video-hero')) return;
    var header = document.getElementById('header');
    if (!header) return;

    var threshold = 80;       // px scrolled before navbar turns solid
    var hovered = false;

    function update() {
      var atTop = window.scrollY < threshold;
      if (atTop && !hovered) {
        header.classList.add('header-transparent');
      } else {
        header.classList.remove('header-transparent');
      }
    }

    window.addEventListener('scroll', update, { passive: true });
    header.addEventListener('mouseenter', function () { hovered = true; update(); });
    header.addEventListener('mouseleave', function () { hovered = false; update(); });
    update();
  })();
</script>