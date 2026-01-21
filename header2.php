<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<script data-ad-client="ca-pub-3696733888071014" async
  src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E96H7RDVLW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() { dataLayer.push(arguments); }
  gtag('js', new Date());

  gtag('config', 'G-E96H7RDVLW');
</script>

<!-- Favicons -->
<link href="assets/img/favicon-32x32.png" rel="icon">
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

<!-- Web Manifest -->
<link rel="manifest" href="manifest.json">

<?php $urlpageview = $_SERVER['REQUEST_URI'];
$query = "INSERT INTO pageview (url, views) 
    VALUES('$urlpageview', '1')";
$update = mysqli_query($db, $query);
if ($update) {
  debug_to_console("test");
  // echo "sucess";
  // array_push($errors2, "Success");

} else {
  // echo mysqli_error($db);

  // array_push($errors2, "No Update");
} ?>