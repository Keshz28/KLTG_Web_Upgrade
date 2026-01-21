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


$travelnews_check_query = "SELECT * FROM highlights WHERE highlights_category='travelnews' LIMIT 1";
$result = mysqli_query($db, $travelnews_check_query);
$travelnews = mysqli_fetch_assoc($result);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - KL Highlights</title>

  <meta
    content="Immerse yourself in the best of Kuala Lumpur with our curated selection of KL Highlights. Start planning your trip today!"
    name="description">
  <meta content="" name="keywords">



  <meta itemprop="name" content="KL The Guide - KL Highlights">
  <meta itemprop="description"
    content="Immerse yourself in the best of Kuala Lumpur with our curated selection of KL Highlights. Start planning your trip today!">
  <meta itemprop="image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/highlights.php" />
  <meta property="og:title" content="KL The Guide - KL Highlights" />
  <meta property="og:description"
    content="Immerse yourself in the best of Kuala Lumpur with our curated selection of KL Highlights. Start planning your trip today!" />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/highlights.php" />
  <meta property="twitter:title" content="KL The Guide - KL Highlights" />
  <meta property="twitter:description"
    content="Immerse yourself in the best of Kuala Lumpur with our curated selection of KL Highlights. Start planning your trip today!" />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <?php include 'header.php'; ?>
  <?php include 'assets/css/highlights.php'; ?>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="highlights">



    <br>


    <!-- ======= Features Section ======= -->
    <section id="features gy-4" class="features">

      <div class="container" data-aos="fade-up">

        <ul class="nav nav-tabs row gy-4 my-5 d-flex justify-content-center">

          <li class="nav-item col d-flex justify-content-center">
            <a class="nav-link active show" id="tab-1-link" href="#tab-1" data-bs-toggle="tab" data-bs-target="#tab-1">
              <img src="assets/img/highlights/<?php echo $tile1_photo1 ?>" class="" alt="Kuala Lumpur Guide - Kuala Lumpur At A Glance">
              <h4 class="text-center align-middle  text-break"><?php echo $tile1_title1 ?></h4>
            </a>
          </li><!-- End Tab 1 Nav -->

          <li class="nav-item col d-flex justify-content-center">
            <a class="nav-link " id="tab-2-link" data-bs-toggle="tab" href="#tab-2" data-bs-target="#tab-2">
              <img src="assets/img/highlights/<?php echo $tile1_photo2 ?>" class="" alt="Kuala Lumpur Guide - Getting Around Kuala Lumpur">

              <h4 class="text-center align-middle  text-break"><?php echo $tile1_title2 ?></h4>
            </a>
          </li><!-- End Tab 2 Nav -->

          <li class="nav-item col d-flex justify-content-center">
            <a class="nav-link " id="tab-3-link" data-bs-toggle="tab" href="#tab-3" data-bs-target="#tab-3">
              <img src="assets/img/highlights/<?php echo $tile1_photo3 ?>" class="" alt="Kuala Lumpur Guide - Travel Tips">

              <h4 class="text-center align-middle text-break"><?php echo $tile1_title3 ?></h4>
            </a>
          </li><!-- End Tab 3 Nav -->

          <!-- Tab 4 nav item is added conditionally further down only when there is travel-news data -->
          <?php

          if ($travelnews) {

          ?>

            <li class="nav-item col d-flex justify-content-center">
              <a class="nav-link " id="tab-4-link" data-bs-toggle="tab" href="#tab-4" data-bs-target="#tab-4">
                <img src="assets/img/highlights/<?php echo $tile1_photo4 ?>" class="" alt="">

                <h4 class="text-center align-middle  text-break"><?php echo $tile1_title4 ?></h4>
              </a>
            </li><!-- End Tab 3 Nav -->
          <?php

          }

          ?>


        </ul>

        <div class="tab-content">

          <!-- KL @ GLANCE TAB CONTENT   -->
          <div class="tab-pane active show" id="tab-1">
            <div class="row gy-4 ">
              <div class="col-lg-12 order-2 order-lg-1  text-center" data-aos="fade-up" data-aos-delay="100">
                <h3>KL @ A Glance</h3>
                <p>
                  Kuala Lumpur city center (KL) is renowned for its tall, futuristic skyscrapers and modern structures.
                  Yet, to experience KL, you 're going to have to walk through its streets and roads to appreciate KL at
                  its best. That's how you're going to be able to smell the food from the hawker stalls, appreciate the
                  murals on the some of the older buildings. The more you walk, the more you will come to realise that
                  KL is not just about concrete skyscrapers, but is a work of architectural art. Sauntering along the
                  streets, you would be able to take in the sights of KL better.
                </p>
                <p>
                  KL's establishment was almost an accident. In 1857, 87 Chinese prospectors, looking for tin, arrived
                  at the meeting point of the Klang and Gombak rivers and set up camp, naming the place Kuala Lumpur,
                  meaning 'muddy confluence'.
                </p>
              </div>
            </div>
          </div><!-- End Tab Content 1 -->

          <!-- GETTING AROUND KL TAB CONTENT -->
          <div class="tab-pane" id="tab-2">
            <div class="row gy-4 mb-5">
              <div class="col-12 text-center">
                <h3>Getting Around KL</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="gaklCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (5 slides for 10 items, 2 per slide) -->
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#gaklCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#gaklCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#gaklCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#gaklCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#gaklCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
              </div>
              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <!-- Slide 1: LRT + MRT -->
                <div class="carousel-item active">
                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/kl_lrt.jpg"
                          alt="Kuala Lumpur Guide - Rapid KL LRT train at Kuala Lumpur, Malaysia station."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Light Rail Transit (LRT)</h4>
                          <p class="card-text flex-grow-1">
                            The Light Rail Transit (LRT) is one of the most commonly used public rail transport. The LRT is
                            divided into two lines; Ampang/Sri Petaling and Kelana Jaya. This is the best means of transport if
                            you are thinking of visiting places that aren't within walking distance of Kuala Lumpur city centre.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 03-7885 2585
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/mrt.png"
                          alt="Kuala Lumpur Guide - MRT train on the Sungai Buloh-Kajang line in Kuala Lumpur."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Mass Rapid Transit (MRT)</h4>
                          <p class="card-text flex-grow-1">
                            The Mass Rapid Transit (MRT) is divided into two lines; the first is the Sungai Buloh-Kajang line that
                            is 51km-long with 31 stations along its route. All the stations start operating at 6.00 am daily.
                            Closing times varies based on respective stations. A single journey is priced at RM1.20 and upwards.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 1800-82-6868
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 2: KTM + Monorail -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/kl_ktm.jpg"
                          alt="Kuala Lumpur Guide - KTM Komuter train with a crowded of passengers in Malaysia."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">KTM Komuter</h4>
                          <p class="card-text flex-grow-1">
                            The oldest rail system in Malaysia, the KTM is catered more for the locals rather than tourists. It
                            acts as a cheaper travel alternative to longer destinations. The lines are divided into two; Rawang -
                            Pelabuhan Klang and Batu Caves - Seremban.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 03-2267 1200
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/monorail.png"
                          alt="Kuala Lumpur Guide - KL Monorail train traveling between KL Sentral and Titiwangsa."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">KL Monorail</h4>
                          <p class="card-text flex-grow-1">
                            The KL Monorail is a simple yet well-connected train system which runs between Kuala Lumpur's
                            transport hub KL Sentral and Titiwangsa in the heart of KL. It acts as a bridge between Kuala Lumpur
                            city centre and the inner areas of Kuala Lumpur. The KL Monorail starts operations at 6.00 am and
                            ceases at 12.00 am.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 03-2267 9888
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 3: RapidKL + Go KL -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/rapid_bus.png"
                          alt="Kuala Lumpur Guide - RapidKL Bus at a station with passengers boarding."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">RapidKL Bus</h4>
                          <p class="card-text flex-grow-1">
                            The RapidKL Bus is commonly seen at most train stations and usually acts as a transit-based mode of
                            transportation for passengers to directly get to their preferred destinations. Each trip on the bus
                            can cost up to RM5.00 per trip which can be paid by using exact change or through tapping your Touch
                            n' Go card upon entry and exit.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 03-7885 2585
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/gokl_bus.jpg"
                          alt="Kuala Lumpur Guide - Go KL City Bus with lilac color in Kuala Lumpur."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Go KL City Bus</h4>
                          <p class="card-text flex-grow-1">
                            Easily recognised due to its lilac-coloured exterior, the Go KL City Bus is a city bus service in
                            Kuala Lumpur that takes you to the most famous districts of the city, train stations, shopping malls
                            and landmarks-free of charge. The bus operates on four main routes, the Green, Purple, Blue and Red
                            Line, which run every day at five-minute intervals during peak hours and 15-minute intervals during
                            off-peak hours.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 03-2603 6600
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 4: Hop-On Hop-Off + ERL -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/kl_hopon_hopoff.jpg"
                          alt="Kuala Lumpur Guide - KL Hop-On Hop-Off double-decker bus for city tours in Kuala Lumpur."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">KL Hop-On Hop-Off</h4>
                          <p class="card-text flex-grow-1">
                            With double-decker and sky-roofed busses carrying sight-seeing tourists, the KL Hop-On Hop-Off Bus
                            provides a quick and easy city tour with 23 stops covering more than 40 attractions in Kuala Lumpur
                            areas. The KL Hop On-Hop Off City Tour runs daily from 9 am to 8 pm at 30-minute intervals. The tour
                            follows the "hop-on, hop-off" concept, which allows ticket holders to hop on and off the bus whenever
                            and wherever they want at all 23 stops.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 1800-88-5546
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/klia_transit.jpg"
                          alt="Kuala Lumpur Guide - ERL express train connecting KLIA/KLIA2 to KL Sentral."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">From KLIA/KLIA2 to Kuala Lumpur</h4>
                          <p class="card-text flex-grow-1">
                            The express train or better known as ERL travels between international airports (KLIA and KLIA2) to KL
                            Sentral. This is one of the fastest forms of transport to travel to and from the airport
                            (approximately 25-35 minutes). However, the tickets could be more on the pricier side (RM55/person;
                            on-way). Tickets can be purchased online or at the main station in KL Sentral. The train service
                            provides high-speed WiFi onboard.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-telephone-fill" style="color: black;"></i>
                            Helpline: 03-2267 1200
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 5: Bus + E-hailing -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/normal_bus.jpg"
                          alt="Kuala Lumpur Guide - Bus for airport to city transport, offering affordable and comfortable travel."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Bus</h4>
                          <p class="card-text flex-grow-1">
                            Buses are just about the cheapest mode of transportation to get from the airport to the city and vice
                            versa. They are relatively safe and fairly comfortable. The fair is also quite affordable (RM12-RM15
                            per pax; depending on your pick up station). The standard travel time is 1 hour to 1 hour 15 minutes.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/getaround/e-hailing.png"
                          alt="Kuala Lumpur Guide - E-hailing car with visible branding, offering reliable and insured transport."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">E-hailing service</h4>
                          <p class="card-text flex-grow-1">
                            E-hailing has become one of the most popular modes of transport modes in Malaysia by locals and
                            foreigners alike due to its high reliability and access due to its user-friendly mobile app. One of
                            the best reasons to use E-hailing service are transparent and fixed fares made clear even before a
                            booking is made. Passengers are also covered with accident insurance the moment they enter a car
                            provided through the E-hailing service provider.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-ticket-detailed" style="color: black;"></i>
                            Fare: RM65 (including toll fares)
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-stopwatch-fill" style="color: black;"></i>
                            Duration: 1-2 hours (depending on traffic and destination)
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <!-- Carousel Controls -->
              <button class="carousel-control-prev" type="button" data-bs-target="#gaklCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#gaklCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>

          <!-- TRAVEL TIPS TAB CONTENT -->
          <div class="tab-pane" id="tab-3">
            <div class="row gy-4">
              <div class="col-12 text-center">
                <h3>Travel Tips</h3>
              </div>
            </div>

            <!-- Travel Tips Content -->
            <!-- Carousel Wrapper -->
            <div id="tipsCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators (3 cards per slide, 6 slides for 17 items) -->
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#tipsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#tipsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#tipsCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#tipsCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#tipsCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
                <button type="button" data-bs-target="#tipsCarousel" data-bs-slide-to="5" aria-label="Slide 6"></button>
              </div>
              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <!-- Slide 1: Weather + Time Zone + Currency -->
                <div class="carousel-item active">
                  <div class="row g-4">
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/weather.png"
                          alt="Kuala Lumpur Guide - Weather information for Kuala Lumpur, Malaysia."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Weather</h4>
                          <p class="card-text flex-grow-1">
                            The climate in KL is quite humid all year-round. Anyone travelling to KL must always be ready for rains at any time of the year. Regardless, the best time to visit KL is from May-July or December-February.
                          </p>
                          <p class="card-text flex-grow-1">
                            The weather can be pretty hot between March - April. Due to the forest fires from Sumatera which typically happens between June - August, the dust particles may cast a haze over KL thus making it not an ideal time to visit the city.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.ventusky.com/">https://www.ventusky.com/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/timezone.png"
                          alt="Kuala Lumpur Guide - Time Zone information for Kuala Lumpur, Malaysia."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Time Zone</h4>
                          <p class="card-text flex-grow-1">
                            Standard Malaysian time is 8 hours ahead of GMT (GMT +8).
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.timeanddate.com/">https://www.timeanddate.com/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/currency-code.png"
                          alt="Kuala Lumpur Guide - Ringgit Malaysia (RM) currency notes and coins."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Currency</h4>
                          <p class="card-text flex-grow-1">
                            Malaysia’s currency unit is divided into two. The term Ringgit Malaysia (RM) is used to refer to paper money. The denominations are RM1, RM5, RM10, RM 20, RM 50 and RM100. Whereas the coins are referred to as sen (cents). The denominations are 5 sen, 10 sen, 20 sen and 50 sen.
                          </p>
                          <p class="card-text flex-grow-1">
                            Samples of Ringgit Malaysia Notes:<br>
                            1. Current Version of Notes and Coins<br>
                            https://www.bnm.gov.my/ (Sample of Notes)<br>
                            https://www.bnm.gov.my/ (Sample of Coins)<br>
                            2. Old Version of Notes and Coins<br>
                            https://www.bnm.gov.my/
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.xe.com/">https://www.xe.com/</a>
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.bnm.gov.my/">https://www.bnm.gov.my/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 2: Exchanging Money + Visa and Passport + Hotel -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/currency.png"
                          alt="Kuala Lumpur Guide - Money Currency"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Exchanging Money</h4>
                          <p class="card-text flex-grow-1">
                            Exchanging money in Malaysia is relatively easy as you can find money changers easily. If you want to withdraw money, make sure your home bank ATM card is supported by banks in Malaysia. Also, bear in mind that your home bank can charge an overseas withdrawal fee. You may also realise that the Malaysian ATM adds its own fee. Ask your home bank before you leave what charges will be added-and check the ATM notices to understand any extra charges. Most places in Kuala Lumpur accept debit/credit cards, just be sure to notify your home bank about your trip to avoid your transactions to be barred and your card getting blocked.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://mtradeasia.com/">https://mtradeasia.com/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/visa-and-passports.png"
                          alt="Kuala Lumpur Guide - Visa and Passport"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Visa and Passport</h4>
                          <p class="card-text flex-grow-1">
                            Passports must be valid for at least 6 months at the time of entry. Visa requirements vary from time to time, so it is best to check with the Malaysian embassy or consulate or the Immigration Department website.
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.imi.gov.my/">https://www.imi.gov.my/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/hotel.png"
                          alt="Kuala Lumpur Guide - Hotel Accommodation"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Hotel</h4>
                          <p class="card-text flex-grow-1">
                            Be sure to have your hotel bookings printed. Booking a hotel in Kuala Lumpur is easy, but if you are travelling during peak periods, you may want to book in advance to avoid disappointments. When booking a stay, be sure to check how far it is from the places you want to visit. Check if your hotel provides free Wi-Fi. Most hotels provide free Wi-Fi for their guests. But in case they don’t, you will need to purchase a prepaid phone line with a data plan. There’s a myriad of budget to 5-star hotels to choose from in Kuala Lumpur.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 3: Internet + Clothing + Comfortable Footwear -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/wifi-wifi-rental.png"
                          alt="Kuala Lumpur Guide - Wi-Fi and Internet Access"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Internet</h4>
                          <p class="card-text flex-grow-1">
                            If you do not plan to switch on your roaming while you are travelling to Kuala Lumpur, fret not as most hotels give you access to their Wi-Fi. Whilst most cafés in Kuala Lumpur provide free Wi-Fi with the purchase of a drink or food. There are also many free Wi-Fi hotspot areas in Kuala Lumpur so make sure to check out the area for free Wi-Fi. It is also advisable to buy a prepaid SIM card with a data plan to make things easier when you are on the go.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/clothing.png"
                          alt="Kuala Lumpur Guide - Clothes for hot and humid weather."
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Clothing</h4>
                          <p class="card-text flex-grow-1">
                            The heat and humidity in Kuala Lumpur can be intense. Therefore, be sure to pack light and breathable clothes that will help you stay cool and avoid heatstroke. Cotton is a good choice, as it is designed to absorb moisture. If your hotel comes with a swimming pool or you are planning to head to the beach, don’t forget to pack your swimsuit.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/comfortable-wear.png"
                          alt="Kuala Lumpur Guide - Footwear"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Comfortable Footwear</h4>
                          <p class="card-text flex-grow-1">
                            Since you will be doing a LOT of walking so you should consider wearing sensible shoes that are very comfortable. We recommend packing a pair of sneakers as well as flats and sandals.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 4: Sunscreen + Raingear + Medicine -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/sunscreen.png"
                          alt="Kuala Lumpur Guide - Sunscreen"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Sunscreen</h4>
                          <p class="card-text flex-grow-1">
                            Make sure to pack in your sunscreen, especially if you don’t want to be sunburnt. The sun can be scorching hot at times.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/raingear.png"
                          alt="Kuala Lumpur Guide - Raingear"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Raingear</h4>
                          <p class="card-text flex-grow-1">
                            Since the rain can be pretty unpredictable in Kuala Lumpur, don’t forget to pack your raincoat or a travel-sized umbrella. You don’t want to be soaked to your skin if it rains suddenly.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/medicine.png"
                          alt="Kuala Lumpur Guide - Medicine"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Medicine</h4>
                          <p class="card-text flex-grow-1">
                            DO NOT forget to pack your prescription tablets and any other medicines that you think you might need in case you fall sick.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Slide 5: Prepaid Sim Card + Dialing Prefixes + Emergency Number -->
                <div class="carousel-item">
                  <div class="row g-4">
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/simcardanddiallingprefixes.png"
                          alt="Kuala Lumpur Guide - Prepaid Sim Card"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Prepaid Sim Card</h4>
                          <p class="card-text flex-grow-1">
                            Malaysia mobile phones use the GSM network, if your phone has a roaming facility, it will automatically hook-up to a local network. Otherwise, prepaid sim cards can be purchased starting at RM20 for registration and talk time. The three main phone providers in Malaysia are Maxis, Celcom and DiGi. Buying prepaid sim cards is easy since you can find kiosks selling prepaid sim cards almost everywhere.
                          </p>
                          <p class="card-text flex-grow-1">
                            Three Main Phone Providers:<br>
                            1. Maxis / Hotlink<br>
                            2. Celcom<br>
                            3. Digi<br>
                            Other Phone Providers:<br>
                            1. U Mobile<br>
                            2. Tune Talk<br>
                            3. Unifi<br>
                            4. Yes<br>
                            5. redONE<br>
                            6. MCalls<br>
                            7. Yoodo<br>
                            8. XOX
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/dialling.png"
                          alt="Kuala Lumpur Guide - Phone Call Dialing Prefixes"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Dialing Prefixes</h4>
                          <p class="card-text flex-grow-1">
                            Each city has its unique area code for landlines. 03 is Kuala Lumpur’s area code followed by the eight-digit number when you call from your mobile phone. Example: 03-12345678 Whereas, every call to a mobile phone requires a three-digit prefix (Maxis = 012, Celcom = 019, DiGi = 016), followed by the seven-digit or eight-digit subscriber number. Example: 012-123 4567
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.imi.gov.my/">https://www.imi.gov.my/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm rounded-3">
                        <img src="assets/img/highlights/traveltips/emergency.png"
                          alt="Kuala Lumpur Guide - SOS Emergency Number"
                          class="card-img-top img-fluid rounded-top-4" loading="lazy">
                        <div class="card-body d-flex flex-column">
                          <h4 class="card-title mb-3">Emergency Number</h4>
                          <p class="card-text flex-grow-1">
                            Police/Ambulance: 999 (112 from a mobile phone)
                            Fire and Rescue Department (Bomba): 994 (112 from a mobile phone)
                            Civil Defence Tel: 991
                            Tourist Police Hotline Tel: 03-2149 6590
                          </p>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="https://www.mm2h.com/">https://www.mm2h.com/</a>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <!-- Carousel Controls -->
              <button class="carousel-control-prev" type="button" data-bs-target="#tipsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#tipsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 3 -->



          <!-- TRAVEL NEWS TAB -->
          <div class="tab-pane" id="tab-4">
            <div class="row gy-10 mb-5">
              <div class="col-12 text-center">
                <h3>Travel News</h3>
              </div>
            </div>

            <!-- Carousel Wrapper -->
            <div id="travelNewsCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
              <!-- Carousel Indicators -->
              <div class="carousel-indicators">
                <?php
                $query_count = "SELECT COUNT(*) as total FROM highlights WHERE highlights_category = 'travelnews'";
                $result_count = mysqli_query($db, $query_count);
                $total_items = mysqli_fetch_assoc($result_count)['total'];
                $items_per_slide = 2;
                $total_slides = ceil($total_items / $items_per_slide);
                for ($i = 0; $i < $total_slides; $i++) {
                  $active_class = ($i == 0) ? 'active' : '';
                  echo '<button type="button" data-bs-target="#travelNewsCarousel" data-bs-slide-to="' . $i . '" class="' . $active_class . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                }
                ?>
              </div>

              <!-- Carousel Inner (Slides) -->
              <div class="carousel-inner">
                <?php
                $query = "SELECT * FROM highlights WHERE highlights_category = 'travelnews' ";
                $result = mysqli_query($db, $query);
                $counter = 1;
                $slide_count = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                  if ($counter % $items_per_slide == 1) {
                    $active_class = ($slide_count == 0) ? 'active' : '';
                    echo '<div class="carousel-item ' . $active_class . '"><div class="row g-4">';
                    $slide_count++;
                  }
                ?>
                  <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                      <img src="assets/img/highlights/travelnews/<?php echo urldecode($row['highlights_image']) ?>"
                        alt="<?php echo urldecode($row['highlights_title']) ?>"
                        class="card-img-top img-fluid rounded-top-4" loading="lazy">
                      <div class="card-body d-flex flex-column">
                        <h4 class="card-title mb-3"><?php echo urldecode($row['highlights_title']) ?></h4>
                        <p class="card-text flex-grow-1"><?php echo urldecode($row['highlights_content']) ?></p>
                        <?php if ($row['highlights_location']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-geo-alt-fill" style="color: black;"></i>
                            <a href="<?php echo urldecode($row['highlights_location']) ?>" target="_blank">
                              <?php echo urldecode($row['highlights_location']) ?>
                            </a>
                          </p>
                        <?php } ?>

                        <?php if ($row['highlights_website']) { ?>
                          <p class="mb-2">
                            <i class="bi bi-globe" style="color: black;"></i>
                            <a href="<?php echo urldecode($row['highlights_website']) ?>" target="_blank">
                              <?php echo urldecode($row['highlights_website']) ?>
                            </a>
                          </p>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php
                  if ($counter % $items_per_slide == 0 || $counter == $total_items) {
                    echo '</div></div>';
                  }
                  $counter++;
                }
                ?>
              </div>
              <!-- Carousel Controls -->
              <button class="carousel-control-prev" type="button" data-bs-target="#travelNewsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#travelNewsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div><!-- End Tab Content 4 -->
        </div>
    </section><!-- End Features Section -->


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
      // If no hash, default to Tab-1
      if (!window.location.hash || !window.location.hash.includes('tab')) {
        $('#tab-1').addClass('active show');
        $('#tab-1-link').addClass('active show');
      } else {
        $(window.location.hash).addClass('active show');
        $(window.location.hash + "-link").addClass('active show');
      }

      document.addEventListener('DOMContentLoaded', function() {
        const carouselElement = document.getElementById('tipsCarousel');
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