<!-- ============================================================================
                                order.php

     Public checkout page for a single merchandise product.

     Flow: product + payment QR shown → customer fills name/email/phone/address
     and uploads a payment receipt (required) → "Complete Purchase" saves the
     order to `merchandise_orders` and hands off to a wa.me link that opens the
     customer's WhatsApp pre-filled with their order details, addressed to the
     business number set in admin → Store Settings.

     MEMO for the next dev — full file map is in PROJECT_GUIDE.md
============================================================================ -->
<?php
include('admin/functions.php');

// ── Load store settings (WhatsApp number + payment QR) ───────────────────────
$settings = ['whatsapp_number' => '', 'payment_qr' => ''];
$setRes = mysqli_query($db, "SELECT whatsapp_number, payment_qr FROM merchandise_settings WHERE id = 1");
if ($setRes && ($r = mysqli_fetch_assoc($setRes))) { $settings = $r; }

// ── Resolve the product (id from GET on first load, POST on submit) ───────────
$pid = isset($_POST['merchandise_id']) ? (int) $_POST['merchandise_id']
     : (isset($_GET['id']) ? (int) $_GET['id'] : 0);

$product = null;
if ($pid > 0) {
    $stmt = mysqli_prepare($db, "SELECT merchandise_id, name, description, image, price FROM merchandise WHERE merchandise_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $pid);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $product = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
}

$prod_img = ($product && $product['image'] !== '')
    ? 'assets/img/merchandise/' . rawurlencode($product['image'])
    : 'https://placehold.co/600x600/1a2e44/cce8f4?text=KLTG';

// ── Handle submission ────────────────────────────────────────────────────────
$errors   = [];
$success  = false;
$wa_link  = '';
$old      = ['name' => '', 'email' => '', 'phone' => '', 'address' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_purchase'])) {
    // This is a public page, so the admin CSRF guard in functions.php does not run
    // for it — validate the token manually. (csrf_field() is emitted in the form.)
    csrf_check();

    $old['name']    = trim($_POST['customer_name'] ?? '');
    $old['email']   = trim($_POST['customer_email'] ?? '');
    $old['phone']   = trim($_POST['customer_phone'] ?? '');
    $old['address'] = trim($_POST['customer_address'] ?? '');

    if (!$product)                                          $errors[] = "Sorry, that product could not be found.";
    if ($old['name'] === '')                               $errors[] = "Please enter your name.";
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
    if ($old['phone'] === '')                              $errors[] = "Please enter your phone number.";
    if ($old['address'] === '')                            $errors[] = "Please enter your delivery address.";

    // Receipt upload — required.
    $receipt_dir   = "assets/img/receipts/";
    $receipt_allow = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $receipt_name  = '';
    $file          = $_FILES['receipt'] ?? null;

    if (!$file || ($file['name'] ?? '') === '' || ($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
        $errors[] = "Please upload your payment receipt.";
    } else {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $receipt_allow, true)) {
            $errors[] = "Receipt must be a JPG, PNG, GIF or WEBP image.";
        } elseif (getimagesize($file['tmp_name']) === false) {
            $errors[] = "The uploaded receipt is not a valid image.";
        } elseif ($file['size'] > 10 * 1024 * 1024) {
            $errors[] = "Receipt image is too large (max 10 MB).";
        }
    }

    if (empty($errors)) {
        if (!is_dir($receipt_dir)) { @mkdir($receipt_dir, 0775, true); }
        // Unguessable filename — receipts hold personal payment proof.
        $receipt_name = 'receipt_' . date('Ymd') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $receipt_dir . $receipt_name)) {
            $errors[] = "Could not save your receipt. Please try again.";
        }
    }

    if (empty($errors)) {
        $pname  = $product['name'];
        $pprice = $product['price'];
        $stmt = mysqli_prepare($db,
            "INSERT INTO merchandise_orders
              (merchandise_id, product_name, product_price, customer_name, customer_email, customer_phone, customer_address, receipt_image, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        mysqli_stmt_bind_param($stmt, 'isssssss',
            $pid, $pname, $pprice, $old['name'], $old['email'], $old['phone'], $old['address'], $receipt_name);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($db);
        mysqli_stmt_close($stmt);

        // Build the WhatsApp hand-off message (customer → business number).
        $wa_number = preg_replace('/\D+/', '', $settings['whatsapp_number']);
        if ($wa_number !== '') {
            $msg  = "Hi KL The Guide! I'd like to confirm my merchandise order. \xF0\x9F\x9B\x8D\n\n";
            $msg .= "*Order #{$order_id}*\n";
            $msg .= "Product: {$pname}\n";
            $msg .= "Price: " . ($pprice !== '' ? $pprice : 'N/A') . "\n\n";
            $msg .= "*My details*\n";
            $msg .= "Name: {$old['name']}\n";
            $msg .= "Phone: {$old['phone']}\n";
            $msg .= "Email: {$old['email']}\n";
            $msg .= "Address: {$old['address']}\n\n";
            $msg .= "I've made payment via the QR and uploaded my receipt as proof of purchase. "
                  . "Kindly confirm my order. Thank you! \xF0\x9F\x99\x8F";
            $wa_link = 'https://wa.me/' . $wa_number . '?text=' . rawurlencode($msg);
        }

        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>KL The Guide - Checkout</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="description" content="Complete your KL The Guide merchandise purchase.">
  <meta name="robots" content="noindex, nofollow">

  <?php include 'header.php'; ?>

  <style>
    #checkout { background: #f0f5f9; min-height: 100vh; }

    .co-hero {
      background: linear-gradient(135deg, #0a1628 0%, #1a3a5c 55%, #0d5286 100%);
      padding: 140px 24px 60px;
      text-align: center;
      color: #fff;
    }
    @media (max-width: 768px) {
      .co-hero { padding: 110px 20px 50px; }
    }
    .co-hero p.eyebrow {
      font-size: .72rem; font-weight: 800; letter-spacing: 6px;
      color: #00d4ff; text-transform: uppercase; margin: 0 0 12px;
    }
    .co-hero h1 {
      font-size: clamp(1.9rem, 5vw, 3rem); font-weight: 900;
      letter-spacing: 4px; text-transform: uppercase; margin: 0;
    }

    .co-wrap { max-width: 1000px; margin: -34px auto 80px; padding: 0 20px; }

    .co-grid { display: grid; grid-template-columns: 1fr 1.15fr; gap: 26px; align-items: start; }
    @media (max-width: 820px) { .co-grid { grid-template-columns: 1fr; } }

    .co-card {
      background: #fff; border-radius: 18px; padding: 26px;
      box-shadow: 0 6px 26px rgba(0,0,0,.08);
    }
    .co-card h2 {
      font-size: .78rem; font-weight: 800; letter-spacing: 2.5px;
      text-transform: uppercase; color: #0077b6; margin: 0 0 18px;
    }

    /* Product summary */
    .co-prod { display: flex; gap: 16px; align-items: center; }
    .co-prod img {
      width: 96px; height: 96px; object-fit: cover;
      border-radius: 12px; flex-shrink: 0;
      background: linear-gradient(135deg, #e3f0f8, #cce4f2);
    }
    .co-prod-name { font-weight: 800; color: #1a2e44; font-size: 1.05rem; margin: 0 0 6px; }
    .co-prod-price { font-weight: 800; color: #0077b6; font-size: 1.1rem; margin: 0; }
    .co-prod-desc { color: #6b7885; font-size: .85rem; margin: 10px 0 0; line-height: 1.5; }

    /* QR block */
    .co-qr { text-align: center; margin-top: 22px; padding-top: 22px; border-top: 1px solid #eef2f6; }
    .co-qr img { max-width: 220px; width: 100%; border: 1px solid #e3e6f0; border-radius: 12px; }
    .co-qr p { font-size: .82rem; color: #6b7885; margin: 12px 0 0; }
    .co-qr-missing {
      background: #fff6e5; color: #8a6d3b; border: 1px dashed #e0c07a;
      border-radius: 10px; padding: 16px; font-size: .85rem;
    }

    /* Form */
    .co-field { margin-bottom: 16px; }
    .co-field label {
      display: block; font-weight: 700; font-size: .82rem;
      color: #33475b; margin-bottom: 6px;
    }
    .co-field label .req { color: #e76f51; }
    .co-field input, .co-field textarea {
      width: 100%; padding: 12px 14px; font-size: .92rem;
      border: 1.5px solid #dde6ee; border-radius: 10px; outline: none;
      transition: border-color .2s, box-shadow .2s; font-family: inherit;
    }
    .co-field input:focus, .co-field textarea:focus {
      border-color: #0077b6; box-shadow: 0 0 0 3px rgba(0,119,182,.12);
    }
    .co-field textarea { resize: vertical; min-height: 78px; }
    .co-field .hint { font-size: .76rem; color: #9aa7b4; margin-top: 5px; }

    .co-file {
      border: 1.5px dashed #b6c6d4; border-radius: 10px;
      padding: 14px; background: #f7fafc;
    }

    .co-submit {
      width: 100%; margin-top: 8px; padding: 15px;
      background: linear-gradient(135deg, #0077b6, #00b4d8);
      color: #fff; font-weight: 800; font-size: .85rem; letter-spacing: 2px;
      text-transform: uppercase; border: none; border-radius: 50px;
      cursor: pointer; box-shadow: 0 8px 22px rgba(0,119,182,.3);
      transition: filter .2s, transform .2s;
    }
    .co-submit:hover { filter: brightness(1.08); transform: translateY(-2px); }

    .co-alert {
      background: #fdecea; border: 1px solid #f5c2bc; color: #8a2c1e;
      border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; font-size: .88rem;
    }
    .co-alert ul { margin: 6px 0 0; padding-left: 18px; }

    /* Success */
    .co-success { max-width: 620px; margin: -34px auto 80px; padding: 0 20px; }
    .co-success-card {
      background: #fff; border-radius: 18px; padding: 40px 30px; text-align: center;
      box-shadow: 0 6px 26px rgba(0,0,0,.08);
    }
    .co-success-icon { font-size: 3.4rem; }
    .co-success-card h2 { color: #1a2e44; font-weight: 900; margin: 14px 0 8px; }
    .co-success-card p { color: #6b7885; font-size: .95rem; margin: 0 auto 8px; max-width: 460px; line-height: 1.6; }
    .co-wa-btn {
      display: inline-flex; align-items: center; gap: 10px;
      margin-top: 22px; padding: 15px 30px;
      background: #25d366; color: #fff; font-weight: 800; font-size: .9rem;
      letter-spacing: .5px; border-radius: 50px; text-decoration: none;
      box-shadow: 0 8px 22px rgba(37,211,102,.35);
    }
    .co-wa-btn:hover { filter: brightness(1.05); color: #fff; text-decoration: none; }
    .co-back { display: inline-block; margin-top: 20px; color: #0077b6; font-weight: 700; text-decoration: none; }
    .co-back:hover { text-decoration: underline; }
  </style>
</head>

<body>
  <?php include 'nav.php'; ?>

  <main id="checkout">

    <?php if ($success): ?>
      <!-- ── Success ── -->
      <section class="co-hero">
        <p class="eyebrow">Order Received</p>
        <h1>Thank You</h1>
      </section>
      <div class="co-success">
        <div class="co-success-card">
          <div class="co-success-icon">&#9989;</div>
          <h2>Your order has been recorded</h2>
          <p>We've saved your details and receipt as proof of purchase. To finish, please
             send us your order confirmation on WhatsApp so we can verify and process it.</p>
          <?php if ($wa_link !== ''): ?>
            <a class="co-wa-btn" id="waBtn" href="<?php echo htmlspecialchars($wa_link, ENT_QUOTES); ?>"
               target="_blank" rel="noopener">&#128241; Send confirmation on WhatsApp</a>
            <p style="margin-top:16px;font-size:.82rem;">If WhatsApp didn't open automatically, tap the button above.</p>
          <?php else: ?>
            <p style="color:#8a6d3b;">Your order is saved. Our team will contact you to confirm shortly.</p>
          <?php endif; ?>
          <br><a class="co-back" href="merchandise.php">&larr; Back to merchandise</a>
        </div>
      </div>
      <?php if ($wa_link !== ''): ?>
      <script>
        // Auto-open the WhatsApp hand-off shortly after the confirmation renders.
        setTimeout(function () { window.location.href = <?php echo json_encode($wa_link); ?>; }, 1200);
      </script>
      <?php endif; ?>

    <?php elseif (!$product): ?>
      <!-- ── Product not found ── -->
      <section class="co-hero">
        <p class="eyebrow">Checkout</p>
        <h1>Product Not Found</h1>
      </section>
      <div class="co-success">
        <div class="co-success-card">
          <p>Sorry, we couldn't find that product.</p>
          <a class="co-back" href="merchandise.php">&larr; Back to merchandise</a>
        </div>
      </div>

    <?php else: ?>
      <!-- ── Checkout form ── -->
      <section class="co-hero">
        <p class="eyebrow">Checkout</p>
        <h1>Complete Your Purchase</h1>
      </section>

      <div class="co-wrap">
        <div class="co-grid">

          <!-- Left: product + QR -->
          <div class="co-card">
            <h2>Your Order</h2>
            <div class="co-prod">
              <img src="<?php echo htmlspecialchars($prod_img, ENT_QUOTES); ?>"
                   alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>">
              <div>
                <p class="co-prod-name"><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></p>
                <?php if ($product['price'] !== ''): ?>
                  <p class="co-prod-price"><?php echo htmlspecialchars($product['price'], ENT_QUOTES); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <?php if (trim($product['description'] ?? '') !== ''): ?>
              <p class="co-prod-desc"><?php echo nl2br(htmlspecialchars($product['description'], ENT_QUOTES)); ?></p>
            <?php endif; ?>

            <div class="co-qr">
              <?php if ($settings['payment_qr'] !== ''): ?>
                <img src="assets/img/merchandise/<?php echo rawurlencode($settings['payment_qr']); ?>" alt="Payment QR">
                <p><strong>Scan to pay</strong>, then upload your receipt below as proof.</p>
              <?php else: ?>
                <div class="co-qr-missing">Payment QR is not set up yet. Please contact us before paying.</div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Right: customer form -->
          <div class="co-card">
            <h2>Your Details</h2>

            <?php if (!empty($errors)): ?>
              <div class="co-alert">
                Please fix the following:
                <ul>
                  <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e, ENT_QUOTES); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form method="post" action="order.php" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="merchandise_id" value="<?php echo (int)$product['merchandise_id']; ?>">

              <div class="co-field">
                <label>Full name <span class="req">*</span></label>
                <input type="text" name="customer_name" required
                       value="<?php echo htmlspecialchars($old['name'], ENT_QUOTES); ?>">
              </div>
              <div class="co-field">
                <label>Email <span class="req">*</span></label>
                <input type="email" name="customer_email" required
                       value="<?php echo htmlspecialchars($old['email'], ENT_QUOTES); ?>">
              </div>
              <div class="co-field">
                <label>Phone number <span class="req">*</span></label>
                <input type="text" name="customer_phone" required
                       value="<?php echo htmlspecialchars($old['phone'], ENT_QUOTES); ?>">
              </div>
              <div class="co-field">
                <label>Delivery address <span class="req">*</span></label>
                <textarea name="customer_address" required><?php echo htmlspecialchars($old['address'], ENT_QUOTES); ?></textarea>
              </div>
              <div class="co-field">
                <label>Payment receipt <span class="req">*</span></label>
                <div class="co-file">
                  <input type="file" name="receipt" accept="image/*" required>
                </div>
                <p class="hint">Upload a screenshot/photo of your payment as proof (JPG, PNG, GIF or WEBP, max 10 MB).</p>
              </div>

              <button type="submit" name="complete_purchase" class="co-submit">Complete Purchase</button>
            </form>
          </div>

        </div>
      </div>
    <?php endif; ?>

  </main>

  <?php include 'footer.php'; ?>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>
