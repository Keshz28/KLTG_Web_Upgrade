<!-- ============================================================================
                                 header.php

     Shared <head> include: SEO/OpenGraph meta, Google Analytics + AdSense,
     fonts, vendor CSS, main.css, PWA manifest link.
     Include AFTER the page sets its own <title>/meta.

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<?php
/* DevPanel ad gate: when the visitor's IP is in devpanel_ad_block, skip the
   AdSense loader entirely AND hide every ad slot site-wide via CSS (covers the
   per-page <ins class="adsbygoogle"> blocks that load their own loader). For
   everyone else, ads load exactly as before. $db comes from functions.php. */
$kltg_hide_ads = (isset($db) && $db instanceof mysqli && function_exists('kltg_ads_hidden'))
    ? kltg_ads_hidden($db)
    : false;
if (!$kltg_hide_ads):
?>
<script data-ad-client="ca-pub-3696733888071014" async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php else: ?>
<!-- Ads suppressed for this visitor (DevPanel ad block) -->
<style>.adsbygoogle,ins.adsbygoogle{display:none!important;}</style>
<?php endif; ?>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E96H7RDVLW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-E96H7RDVLW');
</script>

<!-- Favicons -->
<link href="assets/img/favicon.ico" rel="icon">
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
    rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

<!-- Variables CSS Files. Uncomment your preferred color scheme -->
<link href="assets/css/variables.css" rel="stylesheet">


<!-- Template Main CSS File -->
<link href="assets/css/main.css" rel="stylesheet">

<!-- Polished category-tile menus (overrides main.css) -->
<link href="assets/css/tiles.css" rel="stylesheet">

<!-- Web Manifest -->
<link rel="manifest" href="manifest.json">

<!-- Load font awesome icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <div class="icon-bar">
  <a href="https://www.facebook.com/kltheguide/" class="facebook"><i class="fab fa-facebook"></i></a>
  <a href="https://www.instagram.com/kltheguide/" class="instagram"><i class="fab fa-instagram"></i></a>
  <a href="https://www.tiktok.com/@kltheguide" class="tiktok"><i class="fab fa-tiktok"></i></a>
  <a href="https://twitter.com/kltheguide" class="twitter"><i class="fa-brands fa-x-twitter"></i></a>
  <a href="https://www.youtube.com/@kltheguide" class="youtube"><i class="fab fa-youtube"></i></a>

  </div> 


<?php
/*$urlpageview = $_SERVER['REQUEST_URI'];


$newurl = substr( $urlpageview,  strpos($urlpageview,'.php') + 4);

$diff = strlen($urlpageview)-  strpos($urlpageview,'.php') + 4;


$newurl2 = substr( $urlpageview, 0, strpos($urlpageview,'.php') + 4 );


if ($_SERVER['QUERY_STRING'] ){
    $newurl3 = $newurl2 ."?". $_SERVER['QUERY_STRING'];

}
else{
    $newurl3 = $newurl2;

}

$query = "INSERT INTO pageview (url, views) 
  VALUES('$newurl3', '1')";


if (str_contains($newurl,'/')){
}
else{
    $update = mysqli_query($db, $query);
}*/

?>

