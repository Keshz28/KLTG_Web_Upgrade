<?php
require_once __DIR__ . '/admin/functions.php';

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// ── Helpers ────────────────────────────────────────────────────────────────

function verifyPage(string $title, string $heading, string $body, string $color = '#2c3e50'): void
{
    $brand = htmlspecialchars(brand_name(), ENT_QUOTES, 'UTF-8');
    $siteUrl = htmlspecialchars(app_base_url(), ENT_QUOTES, 'UTF-8');
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$title} – {$brand}</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
    .card { background: #fff; border-radius: 10px; padding: 48px 40px; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
    .icon { font-size: 56px; margin-bottom: 20px; }
    h1 { font-size: 24px; color: {$color}; margin-bottom: 12px; }
    p  { font-size: 15px; color: #555; line-height: 1.6; margin-bottom: 16px; }
    a.btn { display: inline-block; margin-top: 12px; padding: 12px 28px; background: #c8a96e; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 15px; }
    a.btn:hover { background: #b5934f; }
    .brand { margin-top: 32px; font-size: 12px; color: #aaa; }
  </style>
</head>
<body>
  <div class="card">
    {$body}
    <div class="brand">{$brand}</div>
  </div>
</body>
</html>
HTML;
    exit;
}

// ── Token validation ────────────────────────────────────────────────────────

$token = trim($_GET['token'] ?? '');

if ($token === '' || strlen($token) !== 64 || !ctype_xdigit($token)) {
    verifyPage(
        'Invalid link',
        'Invalid link',
        '<div class="icon">⚠️</div>
         <h1>Invalid link</h1>
         <p>This verification link is not valid. Please check the email and try again, or re-subscribe to get a new link.</p>
         <a class="btn" href="' . htmlspecialchars(app_base_url(), ENT_QUOTES, 'UTF-8') . '">Go to homepage</a>'
    );
}

// Look up the token
$stmt = mysqli_prepare($db, "
    SELECT emailsub_id, emailsub_email, verified, verify_expires
    FROM emailsub
    WHERE verify_token = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, 's', $token);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

$baseUrl = htmlspecialchars(app_base_url(), ENT_QUOTES, 'UTF-8');

if (!$row) {
    verifyPage(
        'Link not found',
        'Link not found',
        '<div class="icon">🔍</div>
         <h1>Link not found</h1>
         <p>This verification link has already been used or does not exist. If you are already subscribed, no further action is needed.</p>
         <a class="btn" href="' . $baseUrl . '">Go to homepage</a>'
    );
}

if ((int)$row['verified'] === 1) {
    verifyPage(
        'Already verified',
        'Already verified',
        '<div class="icon">✅</div>
         <h1>Already verified!</h1>
         <p>Your email <strong>' . htmlspecialchars($row['emailsub_email'], ENT_QUOTES, 'UTF-8') . '</strong> is already confirmed. You\'re all set to receive our newsletter.</p>
         <a class="btn" href="' . $baseUrl . '">Visit ' . htmlspecialchars(brand_name(), ENT_QUOTES, 'UTF-8') . '</a>',
        '#27ae60'
    );
}

if (strtotime($row['verify_expires']) < time()) {
    verifyPage(
        'Link expired',
        'Link expired',
        '<div class="icon">⏰</div>
         <h1>Link expired</h1>
         <p>This verification link has expired. Please re-enter your email on the website to receive a new confirmation link.</p>
         <a class="btn" href="' . $baseUrl . '">Re-subscribe</a>'
    );
}

// ── Mark verified ────────────────────────────────────────────────────────────

$upd = mysqli_prepare($db, "
    UPDATE emailsub
    SET verified = 1, verify_token = NULL, verify_expires = NULL
    WHERE emailsub_id = ?
");
mysqli_stmt_bind_param($upd, 'i', $row['emailsub_id']);
$ok = mysqli_stmt_execute($upd);
mysqli_stmt_close($upd);

if (!$ok) {
    verifyPage(
        'Error',
        'Something went wrong',
        '<div class="icon">❌</div>
         <h1>Something went wrong</h1>
         <p>We could not confirm your email right now. Please try again in a few minutes.</p>
         <a class="btn" href="' . $baseUrl . '">Go to homepage</a>',
        '#c0392b'
    );
}

$email     = $row['emailsub_email'];
$brandName = brand_name();

// ── Send welcome email ────────────────────────────────────────────────────────

// Try to load the active welcome template
$tplRes = mysqli_query($db, "SELECT * FROM email_templates WHERE is_active_subscribe = 1 LIMIT 1");
$tpl    = ($tplRes && mysqli_num_rows($tplRes) > 0) ? mysqli_fetch_assoc($tplRes) : null;

if ($tpl) {
    $placeholders = [
        'email'           => $email,
        'date'            => date('d M Y'),
        'site_name'       => $brandName,
        'unsubscribe_url' => app_base_url() . '/unsubscribe.php?e=' . urlencode($email),
    ];
    $welcomeHtml    = render_email_html($tpl['subject'], $tpl['preheader'] ?? '', $tpl['from_name'] ?? $brandName, $tpl['body_html'], $tpl['footer_html'] ?? '', $placeholders);
    $welcomeSubject = $tpl['subject'];
    $welcomeFrom    = $tpl['from_name'] ?? $brandName;
} else {
    $welcomeSubject = 'Welcome to ' . $brandName . '!';
    $welcomeFrom    = $brandName;
    $brandEsc       = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');
    $unsubUrl       = htmlspecialchars(app_base_url() . '/unsubscribe.php?e=' . urlencode($email), ENT_QUOTES, 'UTF-8');
    $welcomeHtml    = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#333;background:#f5f5f5;margin:0;padding:0;">
  <div style="max-width:600px;margin:40px auto;background:#fff;padding:36px;border-radius:8px;">
    <h2 style="color:#2c3e50;margin-top:0;">You\'re subscribed!</h2>
    <p>Welcome to <strong>' . $brandEsc . '</strong>. We\'re glad to have you with us.</p>
    <p>You\'ll be the first to know about our latest articles, events, and updates from around Kuala Lumpur.</p>
    <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
    <p style="font-size:12px;color:#aaa;"><a href="' . $unsubUrl . '" style="color:#aaa;">Unsubscribe</a></p>
  </div>
</body>
</html>';
}

send_email_html($email, $welcomeSubject, $welcomeHtml, $welcomeFrom);

// ── Show success page ─────────────────────────────────────────────────────────

$emailEsc = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$brandBtn = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');

verifyPage(
    'Subscription confirmed',
    'Subscription confirmed',
    '<div class="icon">🎉</div>
     <h1>You\'re subscribed!</h1>
     <p>Your email <strong>' . $emailEsc . '</strong> has been confirmed.</p>
     <p>Welcome aboard! A welcome email is on its way to your inbox.</p>
     <a class="btn" href="' . $baseUrl . '">Visit ' . $brandBtn . '</a>',
    '#27ae60'
);
