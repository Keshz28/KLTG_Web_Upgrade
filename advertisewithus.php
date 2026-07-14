<!-- ============================================================================
                            advertisewithus.php

     Public page — 'Advertise With Us' info + enquiry.

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->
﻿<?php include('admin/functions.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Advertise With Us</title>

  <meta name="description"
    content="Bluedale Publishing is dedicated to helping people make the most of their open-ended travel experiences, so we feel a deep sense of responsibility and privilege when we help someone create their own stories.">
  <meta name="keywords"
    content="About Us, Bluedale Publishing, Bluedale, BGOC, travel, tourism, Malaysia, 
  KL The Guide E-Book, KLTG ebook, KL The Guide, travel guidebook, Malaysia's capital city, e-book, Kuala Lumpur, KL,
  Dataran Merdeka, Petaling Street, travel guide app, travel guide, KLCC, KL Tower, Batu Caves, Google Play Store, Apple App Store, KL The Guide, Kuala Lumpur city">


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.kltheguide.com.my/aboutus.php" />
  <meta property="og:title" content="KL The Guide - About Us" />
  <meta property="og:description"
    content="Bluedale Publishing is dedicated to helping people make the most of their open-ended travel experiences, so we feel a deep sense of responsibility and privilege when we help someone create their own stories." />
  <meta property="og:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://www.kltheguide.com.my/aboutus.php" />
  <meta property="twitter:title" content="KL The Guide - About Us" />
  <meta property="twitter:description"
    content="Bluedale Publishing is dedicated to helping people make the most of their open-ended travel experiences, so we feel a deep sense of responsibility and privilege when we help someone create their own stories." />
  <meta property="twitter:image" content="https://www.kltheguide.com.my/assets/img/kltgseo.jpg" />

  <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">

  <?php include 'header.php'; ?>

  <style>
    /* Match the site's unified blue section titles (Carter One / #1520A6) */
    #aboutus .section-header h2 {
      font-family: 'Carter One', cursive;
      color: #1520A6;
      font-weight: 700;
      font-size: clamp(2.6rem, 5.5vw, 4rem);
      line-height: 1.15;
    }

    /* Floating "pop-up" form fields — each box lifts off the page with a shadow */
    #aboutus .php-email-form .form-control {
      border: 1px solid #eef0f3;
      border-radius: 12px;
      padding: 16px 18px;
      background: #fff;
      box-shadow: 0 10px 25px rgba(21, 32, 166, 0.08);
      transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    }

    #aboutus .php-email-form .form-control:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 34px rgba(21, 32, 166, 0.14);
    }

    #aboutus .php-email-form .form-control:focus {
      border-color: var(--color-primary);
      box-shadow: 0 16px 36px rgba(14, 162, 189, 0.22);
      outline: none;
    }

    /* Two elevated action buttons (Email + WhatsApp) with the floating treatment */
    #aboutus .adv-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      justify-content: center;
      margin-top: 8px;
    }

    #aboutus .btn-adv {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      border: 0;
      border-radius: 12px;
      padding: 14px 34px;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: box-shadow .25s ease, transform .25s ease, background-color .25s ease;
    }

    #aboutus .btn-adv i {
      font-size: 1.15em;
    }

    #aboutus .btn-adv:hover {
      transform: translateY(-3px);
    }

    #aboutus .btn-adv:active {
      transform: translateY(-1px);
    }

    #aboutus .btn-adv--email {
      background: var(--color-primary);
      box-shadow: 0 12px 26px rgba(14, 162, 189, 0.35);
    }

    #aboutus .btn-adv--email:hover {
      background: var(--color-primary-dark);
      box-shadow: 0 18px 34px rgba(14, 162, 189, 0.45);
    }

    #aboutus .btn-adv--wa {
      background: #25D366;
      box-shadow: 0 12px 26px rgba(37, 211, 102, 0.35);
    }

    #aboutus .btn-adv--wa:hover {
      background: #1EBE5A;
      box-shadow: 0 18px 34px rgba(37, 211, 102, 0.45);
    }
  </style>

</head>

<body>

  <?php include 'nav.php'; ?>



  <main id="aboutus">



    <!-- ======= About Section ======= -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact mt-5">
      <div class="container">

        <div class="section-header">
          <h2>Advertise With Us</h2>
          <!-- <p>Ea vitae aspernatur deserunt voluptatem impedit deserunt magnam occaecati dssumenda quas ut ad dolores adipisci aliquam.</p> -->
        </div>

      </div>


      <div class="container">

        <?php if (isset($_GET['sent'])): ?>
          <?php if ($_GET['sent'] === '1'): ?>
            <div class="alert alert-success text-center" role="alert">
              Thank you! Your enquiry has been sent. Our team will get back to you shortly.
            </div>
          <?php else: ?>
            <div class="alert alert-danger text-center" role="alert">
              Sorry, something went wrong and your enquiry could not be sent. Please try again later.
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <div class="row gy-5 gx-lg-5">

          <div class="col-lg-4">

            <div class="info">
              <h3>Get in touch</h3>
              <!-- <p>Et id eius voluptates atque nihil voluptatem enim in tempore minima sit ad mollitia commodi minus.</p> -->

              <div class="info-item d-flex">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <div>
                  <h4>Location:</h4>
                  <p>No.31-2, Block F2, Level, 2, Jalan PJU 1/42a, Dataran Prima,
                    47301 Petaling Jaya, Selangor
                  </p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex">
                <i class="bi bi-envelope flex-shrink-0"></i>
                <div>
                  <h4>Email:</h4>
                  <p>enquiry@bluedale.com.my</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex">
                <i class="bi bi-phone flex-shrink-0"></i>
                <div>
                  <h4>Call:</h4>
                  <p>+6012-220 0622</p>
                </div>
              </div><!-- End Info Item -->

            </div>

          </div>

          <div class="col-lg-8">
            <form action="" method="post" role="form" class="php-email-form" id="advertiseForm">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="company" id="company" placeholder="Company Name" required>
              </div>
              <div class="form-group mt-3">
                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone Number" >
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" placeholder="Message" required></textarea>
              </div>

              <div class="adv-actions">
                <button type="submit" name="advertise" class="btn-adv btn-adv--email">
                  <i class="bi bi-envelope"></i> Send via Email
                </button>
                <button type="button" id="btnWhatsApp" class="btn-adv btn-adv--wa">
                  <i class="bi bi-whatsapp"></i> Send via WhatsApp
                </button>
              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>
    </section><!-- End Contact Section -->



    <!-- ======= Testimonials Section ======= -->
    <!-- <section id="testimonials" class="testimonials">
      <div class="container" data-aos="fade-up">

        <div class="testimonials-slider swiper">
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Saul Goodman</h3>
                <h4>Ceo &amp; Founder</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  I recently used the travel guide provided by KL The Guide for my trip to Kuala Lumpur, and I must
                  say it was an invaluable resource.
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div>


          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section> -->
    <!-- End Testimonials Section -->



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
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
    /* Two ways to send an Advertise With Us enquiry:
         1. "Send via Email"    — submits the form (email to enquiry@bluedale.com.my) AND pings
                                   the WhatsApp number with a short heads-up (name, topic, email).
         2. "Send via WhatsApp" — opens a pre-filled WhatsApp chat with the full enquiry (no email). */
    (function () {
      var WHATSAPP_NUMBER = '60122200622';
      var form = document.getElementById('advertiseForm');
      if (!form) return;

      var val = function (n) {
        var el = form.querySelector('[name="' + n + '"]');
        return el ? el.value.trim() : '';
      };

      var openWhatsApp = function (text) {
        window.open('https://wa.me/' + WHATSAPP_NUMBER + '?text=' + encodeURIComponent(text), '_blank');
      };

      // 1. Email path — fires when the form is submitted via the "Send via Email" button.
      form.addEventListener('submit', function () {
        var text = [
          '*Advertise With Us — email enquiry received*',
          'This person has just sent an email to advertise with us through the KL The Guide website.',
          '',
          'Name: ' + val('name'),
          'Topic: ' + val('subject'),
          'Email: ' + val('email')
        ].join('\n');
        openWhatsApp(text);
      });

      // 2. WhatsApp path — full enquiry sent straight through WhatsApp (no email).
      var waBtn = document.getElementById('btnWhatsApp');
      if (waBtn) {
        waBtn.addEventListener('click', function () {
          if (!form.reportValidity()) return; // enforce the required fields first
          var text = [
            '*Advertise With Us — new enquiry*',
            'Name: ' + val('name'),
            'Email: ' + val('email'),
            'Company: ' + val('company'),
            'Phone: ' + val('phone'),
            'Subject: ' + val('subject'),
            'Message: ' + val('message')
          ].join('\n');
          openWhatsApp(text);
        });
      }
    })();
  </script>
</body>

</html>
