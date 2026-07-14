<?php
/* ============================================================================
 *                              functions.php
 *
 *   THE CENTRAL HUB for the whole site. Loads Composer autoload + .env, starts
 *   the session, opens the MySQL connection ($db), wires PHPMailer + WebPush, and
 *   handles incoming POST actions (email/push queue drain, banner tracking).
 *   Every public AND admin page depends on this file.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include 'vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\SMTP;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Minifier\TinyMinify;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (class_exists('Dotenv\\Dotenv')) {
    $root   = dirname(__DIR__);
    $envDir = is_file($root . '/.env') ? $root : __DIR__;
    Dotenv::createImmutable($envDir)->safeLoad();
}

session_start();

// Generate CSRF token once per session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Prevent bfcache on admin pages so the session check always runs on navigation
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');

    // Session timeout: 30 minutes of inactivity
    if (isset($_SESSION['username'])) {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_unset();
            session_destroy();
            header('Location: login.php?timeout=1');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }
}

// ---- IMPROVED DB CONNECTION ----
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// Helper function
function envv($k, $d = null)
{
    if (isset($_ENV[$k]) && $_ENV[$k] !== '') return $_ENV[$k];
    $v = getenv($k);
    return ($v !== false && $v !== '') ? $v : $d;
}

/**
 * Map a category-tile label to a Bootstrap Icons class. Keyword-based and
 * case-insensitive so small CMS label tweaks still match; falls back to a
 * neutral compass. Used by the polished tile menus on explorekl / beyondkl /
 * accommodation / medical-tourism. Returns just the icon class (e.g. "bi-tree").
 */
function tileIcon($label)
{
    $l = strtolower(trim((string) $label));
    // Order matters: more specific keywords first (e.g. "budget" before "hotel").
    $map = [
        'what to do'  => 'bi-stars',
        'historical'  => 'bi-bank',
        'worship'     => 'bi-building',
        'eat'         => 'bi-egg-fried',
        'night'       => 'bi-moon-stars',
        'kids'        => 'bi-balloon-heart',
        'sightsee'    => 'bi-binoculars',
        'park'        => 'bi-tree',
        'island'      => 'bi-water',
        'hill'        => 'bi-thermometer-sun',
        'waterfall'   => 'bi-droplet',
        'hik'         => 'bi-signpost-2',
        'extreme'     => 'bi-bicycle',
        'sport'       => 'bi-bicycle',
        'budget'      => 'bi-cash-coin',
        'backpack'    => 'bi-house-door',
        'lodge'       => 'bi-house-door',
        'top places'  => 'bi-star-fill',
        'stay'        => 'bi-star-fill',
        'hotel'       => 'bi-building-fill',
        'health'      => 'bi-heart-pulse',
        'dental'      => 'bi-emoji-smile',
        'derma'       => 'bi-bandaid',
        'ophthal'     => 'bi-eye',
        'eye'         => 'bi-eye',
        'plastic'     => 'bi-scissors',
        'surgery'     => 'bi-scissors',
    ];
    foreach ($map as $kw => $icon) {
        if ($l !== '' && strpos($l, $kw) !== false) return $icon;
    }
    return 'bi-compass';
}

// Try to get credentials from environment first
$db_host = envv('DB_HOST', 'localhost');  // default localhost
$db_user = envv('DB_USER', 'kltheguidecom_user');
$db_pass = isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : (getenv('DB_PASS') ?: '');
$db_name = envv('DB_NAME', 'kltheguidecom_bluedale2_kltg');


// Fallback: detect environment if no env vars
if (!$db_host || !$db_user || !$db_name) {
    $is_local = isset($_SERVER['HTTP_HOST']) && (
        stripos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
        stripos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false
    );

    if ($is_local) {
        // LOCAL (XAMPP) SETTINGS
        $db_host = '127.0.0.1';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'kltheguide';  // change if your local DB name differs
    } else {
        // PRODUCTION SETTINGS
        // ⚠️ REPLACE THESE WITH YOUR ACTUAL PRODUCTION CREDENTIALS ⚠️
        $db_host = 'localhost';
        $db_user = 'YOUR_PRODUCTION_DB_USER';      // ← CHANGE THIS
        $db_pass = 'YOUR_PRODUCTION_DB_PASSWORD';  // ← CHANGE THIS
        $db_name = 'YOUR_PRODUCTION_DB_NAME';      // ← CHANGE THIS
    }
}

// Legacy debug helper used by ~124 calls in the edit/delete handlers. The only
// real definition lives in test2/ (not loaded here), so without this stub those
// handler branches fatal with "Call to undefined function". A no-op is correct:
// the original echoed a <script> tag, which would also break the header()
// redirects that follow these calls.
if (!function_exists('debug_to_console')) {
    function debug_to_console($data) { /* no-op */ }
}

// Attempt connection
$db = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Handle connection failure gracefully
if (!$db) {
    error_log('DB Connection Failed: ' . mysqli_connect_error() . ' [Host: ' . $db_host . ', User: ' . $db_user . ']');

    // For AJAX/API requests, return JSON
    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($is_ajax || isset($_POST['fetch_events']) || isset($_POST['appExploreKL_WTD'])) {
        http_response_code(503);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['error' => 'Database unavailable. Please try again later.']);
    } else {
        http_response_code(503);
        echo '<!DOCTYPE html><html><head><title>Maintenance</title></head><body>';
        echo '<h2>We\'re experiencing technical difficulties</h2>';
        echo '<p>Please try again in a few moments.</p></body></html>';
    }
    exit;
}

// Set charset
mysqli_set_charset($db, 'utf8mb4');

// initializing variables
$username = "";
$email = "";
$errors = array();
$errors2 = array();

// Rest of your functions.php code continues here...


function smtp_config_from_env()
{
    $get = function ($k, $d = null) {
        if (isset($_ENV[$k]) && $_ENV[$k] !== '') return $_ENV[$k];
        $v = getenv($k);
        return ($v !== false && $v !== '') ? $v : $d;
    };
    return [
        'host' => $get('MAIL_HOST4') ?: $get('MAIL_HOST1'),
        'user' => $get('MAIL_USER4') ?: $get('MAIL_USER1'),
        'pass' => $get('MAIL_PASS4') ?: $get('MAIL_PASS1'),
        'from' => $get('MAIL_FROM_ADDRESS') ?: ($get('MAIL_USER4') ?: $get('MAIL_USER1')),
        'name' => $get('MAIL_FROM_NAME', 'KL The Guide'),
        'port' => (int)($get('MAIL_PORT1', 465)),
    ];
}

function mailer_from_config()
{
    $cfg = smtp_config_from_env();
    $m = new PHPMailer(true);
    $m->isSMTP();
    $m->Host = $cfg['host'];
    $m->SMTPAuth = true;
    $m->Username = $cfg['user'];
    $m->Password = $cfg['pass'];
    $m->Port = $cfg['port'];
    $m->SMTPSecure = ($m->Port === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $m->setFrom($cfg['from'], $cfg['name']);
    $m->Sender = $cfg['from']; // DMARC
    $m->CharSet = 'UTF-8';
    return $m;
}




function smtp_can_connect(string $host, int $port, int $timeout = 8)
{
    $errno = 0;
    $errstr = '';
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$fp) {
        error_log("SMTP port check failed: $host:$port [$errno] $errstr");
        return false;
    }
    fclose($fp);
    return true;
}

function send_email_html(string $to, string $subject, string $html, ?string $fromName = null): bool
{
    error_log("send_email_html to=$to subject=$subject fromName=" . ($fromName ?: 'default'));
    $mail = new PHPMailer(true);
    try {
        // Prioritas pakai akun website (it@bluedale.com.my)
        
        //$host = 'mail.bluedale.com.my' ?? 'mail.bluedale.com.my';
        //$user = 'marketing@bluedale.com.my' ?? 'marketing@bluedale.com.my';
        //$pass = 'BluedaleMarketing#001' ?? 'BluedaleMarketing#001';
        
        $host = $_ENV['MAIL_HOST4'] ?? '';
        $user = $_ENV['MAIL_USER4'] ?? '';
        $pass = $_ENV['MAIL_PASS4'] ?? '';
        $port = (int)($_ENV['MAIL_PORT1'] ?? 465); // 465=SSL, 587=TLS

        if (!$host || !$user || !$pass) {
            error_log("PHPMailer: missing SMTP config (host=$host, user=$user, pass_set=" . (strlen($pass) ? 'yes' : 'no') . ")");
            return false;
        }

        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $user;
        $mail->Password   = $pass;
        $mail->Port       = $port;
        $mail->SMTPSecure = ($port === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;

        // From/Reply-To konsisten dengan akun SMTP
        $fromEmail = getenv('MAIL_FROM_ADDRESS') ?: $user;
        $fromName  = $fromName ?: (getenv('MAIL_FROM_NAME') ?: 'KL The Guide');
        $mail->setFrom($fromEmail, $fromName);
        $mail->Sender = $fromEmail;
        $mail->addReplyTo($fromEmail, $fromName);
        $mail->addCC('it@bluedale.com.my', 'KLTG New Subscriber Notification');

        // Debug optional
        if ((getenv('MAIL_DEBUG') ?: '0') === '1') {
            $mail->SMTPDebug   = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = static function ($str, $level) {
                error_log("PHPMailer[$level]: $str");
            };
        }

        // Message
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = trim(strip_tags($html));
        $mail->addAddress($to);

        $ok = $mail->send();
        error_log("PHPMailer accepted: " . ($ok ? 'true' : 'false') . "; msg-id=" . $mail->getLastMessageID());
        return $ok;
    } catch (\Throwable $e) {
        error_log('PHPMailer send exception: ' . $e->getMessage());
        return false;
    }
}

// ============================================================================
//  Newsletter subscription helpers (shared by sub_handler.php)
// ============================================================================

/**
 * Canonicalise an email so bot-generated variants collapse to one identity.
 * Gmail ignores dots and everything after a "+", so "f.a.n+x@gmail.com" and
 * "fan@gmail.com" are the same mailbox. Storing the normalised form lets the
 * duplicate check reject the endless dotted variants spammers submit.
 */
function normalize_subscribe_email(string $email): string
{
    $email = strtolower(trim($email));
    $at = strrpos($email, '@');
    if ($at === false) {
        return $email;
    }
    $local  = substr($email, 0, $at);
    $domain = substr($email, $at + 1);

    // Drop "+tag" sub-addressing (delivers to the base mailbox anyway).
    $plus = strpos($local, '+');
    if ($plus !== false) {
        $local = substr($local, 0, $plus);
    }

    // Gmail / Googlemail ignore dots in the local part.
    if ($domain === 'gmail.com' || $domain === 'googlemail.com') {
        $local  = str_replace('.', '', $local);
        $domain = 'gmail.com';
    }

    return $local . '@' . $domain;
}

/** Best-effort real client IP, honouring common proxy headers. */
function subscribe_client_ip(): string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = trim(explode(',', $_SERVER[$k])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '';
}

/**
 * Per-IP rate limiter for the subscribe endpoint. Logs every attempt (even
 * rejected ones) in a self-creating throttle table so a single IP cannot flood
 * the list. Returns true if the attempt is allowed.
 */
function subscribe_throttle_ok(mysqli $db, string $ip, int $maxPerHour = 6): bool
{
    if ($ip === '') {
        return true; // can't identify — don't hard-block real users
    }

    @mysqli_query($db, "CREATE TABLE IF NOT EXISTS subscribe_throttle (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip VARCHAR(45) NOT NULL,
        created_at DATETIME NOT NULL,
        INDEX idx_ip_time (ip, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Housekeeping: drop entries older than a day so the table stays small.
    @mysqli_query($db, "DELETE FROM subscribe_throttle WHERE created_at < (NOW() - INTERVAL 1 DAY)");

    $count = 0;
    if ($st = mysqli_prepare($db, "SELECT COUNT(*) AS c FROM subscribe_throttle WHERE ip = ? AND created_at > (NOW() - INTERVAL 1 HOUR)")) {
        mysqli_stmt_bind_param($st, 's', $ip);
        mysqli_stmt_execute($st);
        $count = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($st))['c'] ?? 0);
        mysqli_stmt_close($st);
    }

    // Record this attempt regardless of the outcome.
    if ($ins = mysqli_prepare($db, "INSERT INTO subscribe_throttle (ip, created_at) VALUES (?, NOW())")) {
        mysqli_stmt_bind_param($ins, 's', $ip);
        mysqli_stmt_execute($ins);
        mysqli_stmt_close($ins);
    }

    return $count < $maxPerHour;
}

/**
 * Send the "welcome / you're subscribed" email. Uses the admin's active
 * template (email_templates.is_active_subscribe = 1) when present, otherwise a
 * built-in default. Best-effort: never throws, returns whether it was accepted.
 */
function send_subscriber_welcome_email(mysqli $db, string $email): bool
{
    $brandName = brand_name();
    $tplRes = @mysqli_query($db, "SELECT * FROM email_templates WHERE is_active_subscribe = 1 LIMIT 1");
    $tpl    = ($tplRes && mysqli_num_rows($tplRes) > 0) ? mysqli_fetch_assoc($tplRes) : null;

    if ($tpl && function_exists('render_email_html')) {
        $placeholders = [
            'email'           => $email,
            'date'            => date('d M Y'),
            'site_name'       => $brandName,
            'unsubscribe_url' => app_base_url() . '/unsubscribe.php?e=' . urlencode($email),
        ];
        $html    = render_email_html($tpl['subject'], $tpl['preheader'] ?? '', $tpl['from_name'] ?? $brandName, $tpl['body_html'], $tpl['footer_html'] ?? '', $placeholders);
        $subject = $tpl['subject'];
        $from    = $tpl['from_name'] ?? $brandName;
    } else {
        $brandEsc = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');
        $unsubUrl = htmlspecialchars(app_base_url() . '/unsubscribe.php?e=' . urlencode($email), ENT_QUOTES, 'UTF-8');
        $subject  = 'Welcome to ' . $brandName . '!';
        $from     = $brandName;
        $html     = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#333;background:#f5f5f5;margin:0;padding:0;">
  <div style="max-width:600px;margin:40px auto;background:#fff;padding:36px;border-radius:8px;">
    <h2 style="color:#2c3e50;margin-top:0;">You\'re subscribed!</h2>
    <p>Welcome to <strong>' . $brandEsc . '</strong>. We\'re glad to have you with us.</p>
    <p>You\'ll be the first to know about our latest articles, events, and updates from around Kuala Lumpur.</p>
    <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
    <p style="font-size:12px;color:#aaa;"><a href="' . $unsubUrl . '" style="color:#aaa;">Unsubscribe</a></p>
  </div>
</body></html>';
    }

    return send_email_html($email, $subject, $html, $from);
}


function sendmail($subscriberemail, $content, $title)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST4'] ?? 'mail.bluedale.com.my';
        $mail->SMTPAuth   = false;
        $mail->Username   = $_ENV['MAIL_USER4'] ?? 'it@bluedale.com.my';
        $mail->Password   = $_ENV['MAIL_PASS4'] ?? '';
        $mail->Port       = (int)($_ENV['MAIL_PORT1'] ?? 465);
        $mail->SMTPSecure = ($mail->Port === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;

        $from = $_ENV['MAIL_FROM_ADDRESS'] ?: $mail->Username;
        $name = $_ENV['MAIL_FROM_NAME'] ?: 'KL The Guide';
        $mail->setFrom($from, $name);
        $mail->addReplyTo($from, $name);

        $mail->addAddress($subscriberemail, 'Subscriber');
        $mail->isHTML(true);
        $mail->Subject = $title;

        // TERPENTING: dukung path file ATAU html string
        $mail->Body = (is_file($content) ? file_get_contents($content) : $content);
        $mail->AltBody = strip_tags($title);

        $mail->send();
        $mail->clearAddresses();
        $mail->clearAttachments();
        return '1';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function sendmail3($subscriberemail, $content, $title)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST4'] ?? 'mail.kltheguide.com.my';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER4'] ?? 'it@bluedale.com.my';
        $mail->Password   = $_ENV['MAIL_PASS4'] ?? '';
        $mail->Port       = (int)($_ENV['MAIL_PORT1'] ?? 465);
        $mail->SMTPSecure = ($mail->Port === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;

        $from = $_ENV['MAIL_FROM_ADDRESS'] ?: $mail->Username;
        $name = $_ENV['MAIL_FROM_NAME'] ?: 'KL The Guide';
        $mail->setFrom($from, $name);
        $mail->addReplyTo($from, $name);
        $mail->addAddress($subscriberemail, 'Subscriber');

        // optional embedded
        if (is_file('../assets/img/LogoNav.png'))      $mail->addEmbeddedImage('../assets/img/LogoNav.png', 'logoimg');
        if (is_file('../assets/img/email/6.jpg'))      $mail->addEmbeddedImage('../assets/img/email/6.jpg', 'footerimg', 'Sign');

        $mail->isHTML(true);
        $mail->Subject = $title;

        // dukung file template atau html langsung
        if (is_file($content)) {
            $mail->Body = file_get_contents($content);
        } else {
            // fallback: render via sendemail.php jika memang itu cara lama
            if (is_file('sendemail.php')) {
                ob_start();
                require 'sendemail.php';
                $output = ob_get_clean();
                $mail->Body = $output ?: $content;
            } else {
                $mail->Body = $content;
            }
        }
        $mail->AltBody = strip_tags($title);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}


// Attempts to send immediately; if it fails, enqueue in mailqueue.
// Returns true if sent now, false if queued (or if sending failed but queue succeeded).
function queue_or_send_email(string $to_email, string $subject, string $html, ?string $from_name = null): bool
{
    try {
        $sent = send_email_html($to_email, $subject, $html, $from_name ?: brand_name());
        if ($sent) return true;

        // kalau gagal → masukkan ke mailqueue
        global $db;
        $status = '0';
        $stmt = mysqli_prepare($db, "INSERT INTO mailqueue (sendstatus, sendto, sendtitle, sendbody) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $status, $to_email, $subject, $html);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return false;
    } catch (\Throwable $e) {
        error_log('queue_or_send_email error: ' . $e->getMessage());
        return false;
    }
}


function sendmail2(string $subscriberemail, string $content, string $title, ?string $fromName = 'KL The Guide'): bool
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST4'] ?? 'mail.kltheguide.com.my';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER4'] ?? 'it@bluedale.com.my';
        $mail->Password   = $_ENV['MAIL_PASS4'] ?? '';
        $mail->Port       = (int)($_ENV['MAIL_PORT1'] ?? 465);
        $mail->SMTPSecure = ($mail->Port === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;

        $fromEmail = $_ENV['MAIL_FROM_ADDRESS'] ?: $mail->Username;
        $mail->setFrom($fromEmail, $fromName ?: 'KL The Guide');
        $mail->addReplyTo($fromEmail, $fromName ?: 'KL The Guide');
        $mail->addAddress($subscriberemail, 'Subscriber');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $title ?: 'Welcome';

        // embedded optional
        $logo   = __DIR__ . '/assets/img/LogoNav.png';
        $footer = __DIR__ . '/assets/img/email/6.jpg';
        if (is_file($logo))   $mail->addEmbeddedImage($logo, 'logoimg');
        if (is_file($footer)) $mail->addEmbeddedImage($footer, 'footerimg', 'Sign');

        // dukung file/HTML
        if (trim($content) !== '') {
            $mail->Body = is_file($content) ? file_get_contents($content) : $content;
        } else {
            ob_start();
            @require __DIR__ . '/welcomeemail.php';
            $out = ob_get_clean();
            $mail->Body = $out ?: '<p>Welcome!</p>';
        }
        $mail->AltBody = strip_tags($title) . "\n\n" . strip_tags($mail->Body);

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        error_log('sendmail2 exception: ' . $e->getMessage());
        return false;
    }
}


function ensure_dir($dir)
{
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

function uploadimage($formname, $folder, $category)
{
    $dir = rtrim("../assets/img/$folder/$category", '/') . '/';
    ensure_dir($dir);

    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Sanitize filename
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($formname['name']));
    $target = $dir . $name;

    // 1️⃣ Validate extension
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        return false;
    }

    // 2️⃣ Validate actual image (NO fileinfo)
    if (!getimagesize($formname['tmp_name'])) {
        return false;
    }

    // 3️⃣ Check file size (5MB)
    if ($formname['size'] > 5 * 1024 * 1024) {
        return false;
    }

    // 4️⃣ Prevent overwrite
    if (file_exists($target)) {
        return false;
    }

    // 5️⃣ Move file
    return move_uploaded_file($formname['tmp_name'], $target) ? $name : false;
}


function uploadpdf($formname, $folder, $category)
{
    $dir = rtrim("../assets/pdf/$folder/$category", '/') . '/';
    ensure_dir($dir);

    // Sanitize filename
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($formname['name']));
    $target = $dir . $name;

    // 1️⃣ Validate extension
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        return false;
    }

    // 2️⃣ Validate real PDF (magic header)
    $fh = fopen($formname['tmp_name'], 'rb');
    if (!$fh) return false;
    $header = fread($fh, 4);
    fclose($fh);

    if ($header !== '%PDF') {
        return false;
    }

    // 3️⃣ Prevent overwrite
    if (file_exists($target)) {
        return false;
    }

    // 4️⃣ File size limit (300MB) — keep <= upload_max_filesize in .user.ini
    if ($formname['size'] > 300 * 1024 * 1024) {
        return false;
    }

    // 5️⃣ Move file
    return move_uploaded_file($formname['tmp_name'], $target) ? $name : false;
}


/**
 * Upload an e-book cover into assets/img/ebook/<category>/.
 * Unlike uploadimage(), this also accepts a PDF cover (validated by its
 * %PDF magic header) so a cover can be a pdf, jpg, jpeg, png or webp.
 * Returns the sanitized stored filename, or false on any failure.
 */
function uploadcover($formname, $category)
{
    $dir = rtrim("../assets/img/ebook/$category", '/') . '/';
    ensure_dir($dir);

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    // Sanitize filename
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($formname['name']));
    $target = $dir . $name;

    // 1️⃣ Validate extension
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        return false;
    }

    // 2️⃣ Validate the actual file contents
    if ($ext === 'pdf') {
        $fh = fopen($formname['tmp_name'], 'rb');
        if (!$fh) return false;
        $header = fread($fh, 4);
        fclose($fh);
        if ($header !== '%PDF') return false;
    } else {
        if (!getimagesize($formname['tmp_name'])) return false;
    }

    // 3️⃣ File size limit (20MB)
    if ($formname['size'] > 20 * 1024 * 1024) {
        return false;
    }

    // 4️⃣ Prevent overwrite
    if (file_exists($target)) {
        return false;
    }

    // 5️⃣ Move file
    return move_uploaded_file($formname['tmp_name'], $target) ? $name : false;
}


/**
 * Return the e-book categories as [cat_code => ['title'=>..., 'desc'=>...]].
 * Reads the ebook_category table (managed from the admin dashboard). If that
 * table is missing or empty (e.g. the migration hasn't been applied yet) it
 * falls back to the original hard-coded list so the public page never breaks.
 *
 * @param mysqli $db
 * @param bool   $onlyVisible  When true, hidden categories are skipped.
 */
function ebook_categories($db, $onlyVisible = false)
{
    $cats = [];
    $sql = "SELECT cat_code, cat_title, cat_desc, cat_visible
            FROM ebook_category
            ORDER BY cat_order ASC, cat_id ASC";
    $res = @mysqli_query($db, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        while ($r = mysqli_fetch_assoc($res)) {
            if ($onlyVisible && (int) $r['cat_visible'] !== 1) continue;
            $cats[$r['cat_code']] = [
                'title' => $r['cat_title'],
                'desc'  => $r['cat_desc'] ?? '',
            ];
        }
        if (!empty($cats)) return $cats;
    }

    // Fallback: original hard-coded list (used only if the table is absent).
    return [
        'kltg' => ['title' => 'KL The Guide', 'desc' => "Unlock the ultimate trip to Kuala Lumpur. Our exclusive KL The Guide eBooks pack everything you need into one place. Detailed itineraries, local secrets and cultural deep dives."],
        'kv4l' => ['title' => 'Klang Valley 4 Locals', 'desc' => "The Klang Valley 4 Locals eBooks are exclusive guides specially crafted for locals, helping them to uncover the hidden gems and unique experiences within the vibrant Klang Valley region."],
        'mktg' => ['title' => 'Melaka The Guide', 'desc' => 'Experience the enchanting city of Melaka like never before with Melaka The Guide eBooks!'],
        'tptg' => ['title' => 'Taiping The Guide', 'desc' => "Discover the charming town of Taiping with Taiping The Guide eBooks."],
        'uztg' => ['title' => 'Uzbekistan The Guide', 'desc' => 'Embark on a remarkable journey to the enchanting country of Uzbekistan with Uzbekistan The Guide eBook.'],
        'kntg' => ['title' => 'Keningau The Guide', 'desc' => 'Discover the ultimate travel companion for your upcoming journey to Keningau!'],
        'twtg' => ['title' => 'Tawau The Guide', 'desc' => "Unlock a treasure trove of invaluable information and expert recommendations with Tawau The Guide eBook!"],
        'tbtg' => ['title' => 'Tambunan The Guide', 'desc' => 'Immerse yourself in the rich culture, vibrant traditions, and breathtaking landscapes that Tambunan has to offer.'],
        'hstg' => ['title' => 'Hulu Selangor The Guide', 'desc' => "Immerse yourself in the natural beauty, historical landmarks, and cultural richness of this hidden gem."],
        'prtg' => ['title' => 'Perak The Guide', 'desc' => "Embark on an extraordinary journey through Perak's wonders with Perak The Guide eBooks."],
        'sbtg' => ['title' => 'Seremban The Guide', 'desc' => 'Unlock the wonders of Seremban with Seremban The Guide eBook.'],
        'kstg' => ['title' => 'Kuala Selangor The Guide', 'desc' => 'Discover Kuala Selangor like never before with our comprehensive Kuala Selangor The Guide eBook!'],
        'klgt' => ['title' => 'Kuala Langat The Guide', 'desc' => "Explore the enchanting town of Kuala Langat with Kuala Langat The Guide."],
    ];
}


// === INSERT HERE (near your other helpers) ===
function app_base_url(): string
{
    // Adjust if you already have a base URL helper.
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'example.com';
    return $scheme . '://' . $host;
}

function brand_name(): string
{
    return 'KL The Guide'; // or read from env/config if you have it
}

// === DevPanel: ad visibility by IP ==========================================
// The hidden DevPanel (xp.php) lets the operator hide all advertisements for a
// specific visitor IP. The visitor's IP is matched against the
// `devpanel_ad_block` table; header.php uses kltg_ads_hidden() to suppress the
// AdSense slots for those IPs only. See db_migration_devpanel.sql.

/**
 * Best-effort real client IP. Honours common proxy/CDN headers (Cloudflare,
 * standard reverse proxies) before falling back to REMOTE_ADDR, and validates
 * the result so a spoofed header can't inject garbage. Returns '' if unknown.
 */
function kltg_client_ip(): string
{
    $candidates = [];
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $candidates[] = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // First hop in the list is the original client.
        $candidates[] = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $candidates[] = $_SERVER['REMOTE_ADDR'];
    }
    foreach ($candidates as $ip) {
        $ip = trim((string) $ip);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }
    return '';
}

/**
 * True when ads should be hidden for the current visitor (their IP is in the
 * devpanel_ad_block list). Computed once per request and fails safe: any DB
 * error or a missing table simply means "show ads" so the public site is never
 * broken by this feature.
 */
function kltg_ads_hidden(mysqli $db): bool
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }
    $cached = false;

    $ip = kltg_client_ip();
    if ($ip === '') {
        return $cached;
    }

    $stmt = @mysqli_prepare($db, "SELECT 1 FROM devpanel_ad_block WHERE ip_address = ? LIMIT 1");
    if (!$stmt) {
        return $cached; // table missing or DB error -> show ads
    }
    mysqli_stmt_bind_param($stmt, 's', $ip);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        $cached = mysqli_stmt_num_rows($stmt) > 0;
    }
    mysqli_stmt_close($stmt);
    return $cached;
}

/**
 * Active "house ads" (image banners) managed from the DevPanel (xp.php → Ads).
 * Returns [] for blocked IPs so they never see house ads either. Fails safe:
 * a missing devpanel_house_ad table just yields no ads.
 */
function kltg_house_ads(mysqli $db): array
{
    if (kltg_ads_hidden($db)) {
        return [];
    }
    $ads = [];
    $res = @mysqli_query($db, "SELECT image, link_url FROM devpanel_house_ad WHERE active = 1 ORDER BY id DESC");
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $ads[] = $r;
        }
    }
    return $ads;
}

/** Render the active house ads as a centered banner block (or '' if none). */
function kltg_render_house_ads(mysqli $db): string
{
    $ads = kltg_house_ads($db);
    if (!$ads) {
        return '';
    }
    $out = '<div class="house-ads d-flex flex-column align-items-center my-4">';
    foreach ($ads as $a) {
        $src = 'assets/img/houseads/' . rawurlencode($a['image']);
        $img = '<img src="' . htmlspecialchars($src, ENT_QUOTES) . '" alt="Advertisement" '
             . 'style="max-width:100%;height:auto;display:block;margin:0 auto;">';
        if (!empty($a['link_url'])) {
            $out .= '<a href="' . htmlspecialchars($a['link_url'], ENT_QUOTES) . '" target="_blank" '
                  . 'rel="noopener sponsored" class="house-ad mb-3">' . $img . '</a>';
        } else {
            $out .= '<div class="house-ad mb-3">' . $img . '</div>';
        }
    }
    $out .= '</div>';
    return $out;
}

// === Login rate limiting (file-based, per IP, max 5 attempts / 15 min) ===
function login_rate_limit_check(): ?string {
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $file = sys_get_temp_dir() . '/kltg_login_' . md5($ip) . '.json';
    if (!file_exists($file)) return null;
    $data = @json_decode(file_get_contents($file), true);
    if (!$data) return null;
    if (time() >= $data['reset_at']) { @unlink($file); return null; }
    if ($data['attempts'] >= 5) {
        $wait = max(1, (int) ceil(($data['reset_at'] - time()) / 60));
        return "Too many failed login attempts. Try again in {$wait} minute(s).";
    }
    return null;
}

function login_rate_limit_fail(): void {
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $file = sys_get_temp_dir() . '/kltg_login_' . md5($ip) . '.json';
    $data = ['attempts' => 1, 'reset_at' => time() + 900];
    if (file_exists($file)) {
        $prev = @json_decode(file_get_contents($file), true);
        if ($prev && time() < $prev['reset_at']) {
            $data = ['attempts' => $prev['attempts'] + 1, 'reset_at' => $prev['reset_at']];
        }
    }
    @file_put_contents($file, json_encode($data), LOCK_EX);
}

function login_rate_limit_clear(): void {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    @unlink(sys_get_temp_dir() . '/kltg_login_' . md5($ip) . '.json');
}

// === CSRF helpers ===
function csrf_token(): string {
    return $_SESSION['csrf_token'] ?? '';
}

function csrf_field(): string {
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $t . '">';
}

function csrf_check(): void {
    $token     = $_SESSION['csrf_token'] ?? '';
    $submitted = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($token, $submitted)) {
        http_response_code(403);
        error_log('CSRF check failed: ' . ($_SERVER['REQUEST_URI'] ?? '') . ' from ' . ($_SERVER['REMOTE_ADDR'] ?? ''));
        die('Request validation failed. Please go back and try again.');
    }
}

// === INSERT HERE (security helper) ===
function require_admin()
{
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    // If you have roles/levels, check here. For now, any logged-in user is "admin".
}


// === INSERT HERE (DB helpers) ===
function get_template_by_slug(mysqli $db, string $slug): ?array
{
    $stmt = $db->prepare("SELECT * FROM email_templates WHERE slug = ? LIMIT 1");
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

function save_template_version(mysqli $db, int $templateId, array $tpl, ?string $user)
{
    $stmt = $db->prepare("
        INSERT INTO email_template_versions
        (template_id, subject, preheader, from_name, body_html, footer_html, saved_by)
        VALUES (?,?,?,?,?,?,?)
    ");
    $subject = $tpl['subject'] ?? '';
    $preheader = $tpl['preheader'] ?? null;
    $from_name = $tpl['from_name'] ?? null;
    $body_html = $tpl['body_html'] ?? '';
    $footer_html = $tpl['footer_html'] ?? null;
    $stmt->bind_param('issssss', $templateId, $subject, $preheader, $from_name, $body_html, $footer_html, $user);
    $stmt->execute();
    $stmt->close();
}


// === INSERT HERE (renderer) ===
function replace_placeholders(string $html, array $context): string
{
    $map = [
        '{{email}}' => htmlspecialchars($context['email'] ?? '', ENT_QUOTES, 'UTF-8'),
        '{{date}}' => htmlspecialchars($context['date'] ?? date('d M Y'), ENT_QUOTES, 'UTF-8'),
        '{{site_name}}' => htmlspecialchars($context['site_name'] ?? brand_name(), ENT_QUOTES, 'UTF-8'),
        '{{unsubscribe_url}}' => htmlspecialchars($context['unsubscribe_url'] ?? '#', ENT_QUOTES, 'UTF-8'),
    ];
    return strtr($html, $map);
}

function absoluteize_urls(string $html): string
{
    $base = rtrim(app_base_url(), '/');
    // Convert src/href that start with /assets or relative to absolute
    $html = preg_replace_callback(
        '/\b(src|href)=(["\'])(?!https?:\/\/|data:|mailto:)([^"\']+)\2/i',
        function ($m) use ($base) {
            $attr = $m[1];
            $q = $m[2];
            $path = $m[3];
            if (strpos($path, '/') === 0) {
                return $attr . '=' . $q . $base . $path . $q;
            } else {
                return $attr . '=' . $q . $base . '/' . ltrim($path, '/') . $q;
            }
        },
        $html
    );
    return $html;
}

function render_email_html(
    string $subject,
    ?string $preheader,
    ?string $from_name,
    string $body_html,
    ?string $footer_html,
    array $context = []
): string {

    $body = replace_placeholders($body_html, $context);
    $footer = $footer_html ? replace_placeholders($footer_html, $context) : '';

    $preheader_text = $preheader ? htmlspecialchars($preheader, ENT_QUOTES, 'UTF-8') : '';
    $brand = htmlspecialchars($from_name ?: brand_name(), ENT_QUOTES, 'UTF-8');

    $shell = '
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</title>
  <style>
    body{margin:0;background:#f4f4f7}
    .wrapper{width:100%;padding:24px 0}
    .container{width:100%;max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden}
    .brand{padding:20px 24px;font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:700;background:#fafafa;border-bottom:1px solid #eee}
    .content{padding:24px;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.6;color:#111}
    .footer{padding:20px 24px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#666;background:#fafafa;border-top:1px solid #eee}
    .preheader{display:none!important;visibility:hidden;opacity:0;color:transparent;height:0;width:0;max-height:0;max-width:0;overflow:hidden;mso-hide:all}
    img{max-width:100%;height:auto;border:0}
  </style>
</head>
<body>
  <span class="preheader">' . $preheader_text . '</span>
  <div class="wrapper">
    <div class="container">
      <div class="brand">' . $brand . '</div>
      <div class="content">' . $body . '</div>
      ' . ($footer ? '<div class="footer">' . $footer . '</div>' : '') . '
    </div>
  </div>
</body>
</html>';
    return absoluteize_urls($shell);
}






// === INSERT HERE (upload helper) ===
function upload_email_asset(array $file, string $subdir, ?string $username, mysqli $db)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload error');
    }

    // 1️⃣ Validate real image (NO fileinfo)
    $dim = @getimagesize($file['tmp_name']);
    if ($dim === false) {
        throw new RuntimeException('Invalid image file');
    }

    // 2️⃣ Validate extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed_ext, true)) {
        throw new RuntimeException('Invalid file type');
    }

    // Normalize extension
    $extMap = [
        'jpg'  => '.jpg',
        'jpeg' => '.jpg',
        'png'  => '.png',
        'gif'  => '.gif',
        'webp' => '.webp',
    ];
    $ext = $extMap[$ext] ?? '.bin';

    // 3️⃣ Directory structure
    $y = date('Y');
    $m = date('m');
    $relDir = "assets/email/$y/$m";
    $absDir = realpath(__DIR__ . '/../') . "/$relDir";
    if (!is_dir($absDir)) {
        mkdir($absDir, 0775, true);
    }

    // 4️⃣ Generate safe filename
    $name = bin2hex(random_bytes(8)) . $ext;
    $absPath = "$absDir/$name";

    // 5️⃣ Store file
    if (!move_uploaded_file($file['tmp_name'], $absPath)) {
        throw new RuntimeException('Failed to store file');
    }

    // 6️⃣ Optional: strip EXIF for JPEG
    if ($ext === '.jpg' && function_exists('exif_read_data')) {
        $img = @imagecreatefromjpeg($absPath);
        if ($img) {
            imagejpeg($img, $absPath, 88);
            imagedestroy($img);
        }
    }

    $url  = app_base_url() . "/$relDir/$name";
    $size = filesize($absPath);

    // Width & height already known
    $w = $dim[0] ?? null;
    $h = $dim[1] ?? null;

    // 7️⃣ Save to DB
    $stmt = $db->prepare("
        INSERT INTO email_assets
        (path, url, filename, size, width, height, uploaded_by)
        VALUES (?,?,?,?,?,?,?)
    ");
    $stmt->bind_param('ssssiis', $relDir, $url, $name, $size, $w, $h, $username);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();

    return [
        'id'       => $id,
        'url'      => $url,
        'filename' => $name,
        'width'    => $w,
        'height'   => $h,
        'size'     => $size
    ];
}




function mail_from_email(): string
{
    // From email must stay in .env per your requirement
    return getenv('MAIL_FROM_ADDRESS') ?: 'it@bluedale.com.my';
}


function sendpushnotification($db, $pushtitle, $pushcontent)
{


    $notifications = [];

    $query = "SELECT * FROM pushsub";

    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_assoc($result)) {

        $endpoint = $row['endpoint'];
        $p256dh = $row['p256dh'];
        $auth = $row['auth'];

        $sub = [
            'subscription' => Subscription::create([
                'endpoint' => $endpoint,
                // Firefox 43+,
                'publicKey' => $p256dh,
                // base 64 encoded, should be 88 chars
                'authToken' => $auth, // base 64 encoded, should be 24 chars
            ])
        ];
        array_push($notifications, $sub);
        // var_dump($notifications);

    }
    $push = new WebPush([
        "VAPID" => [
            "subject" => "mailto: <izmeera2000@gmail.com>",
            "publicKey" => $_ENV['VAPID_PUBLIC_KEY'],
            "privateKey" => $_ENV['VAPID_PRIVATE_KEY']
        ]
    ]);
    foreach ($notifications as $notification) {

        $push->queueNotification($notification['subscription'], json_encode([
            "title" => $pushtitle,
            "body" => $pushcontent,
            "icon" => "../assets/img/android-chrome-192x192.png",
            "image" => "../assets/img/sign.jpg"
        ]));
    }

    foreach ($push->flush() as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();

        if ($report->isSuccess()) {
            echo "<br>[v] Message sent successfully for subscription {$endpoint}.";
            // array_push($errors2, "Message sent successfully for subscription");

        } else {
            echo "<br>[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
            // array_push($errors2, "{$endpoint}: {$report->getReason()}");

        }
    }
}


// REGISTER USER
if (isset($_POST['reg_user'])) {
    // SECURITY: account creation is admin-only. This handler is reachable from
    // any page that includes functions.php, so the gate must live here (not just
    // in register.php's view guard). Reject anyone who is not already logged in.
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
        exit;
    }
    // receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    $passbd = mysqli_real_escape_string($db, $_POST['passbd']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    if ($passbd != $_ENV['PASSBD']) {
        array_push($errors, "Password BD is wrong");
    }


    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "email already exists");
        }
    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = password_hash($password_1, PASSWORD_DEFAULT); // bcrypt (was: md5())

        $query = "INSERT INTO users (username, email, password)
  			  VALUES('$username', '$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
        exit;
    }
}


// LOGIN USER
if (isset($_POST['login_user'])) {
    $rl_error = login_rate_limit_check();
    if ($rl_error) {
        array_push($errors, $rl_error);
    } else {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }

        if (count($errors) == 0) {
            $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
            $results = mysqli_query($db, $query);
            $user = $results ? mysqli_fetch_assoc($results) : null;
            $ok = false;
            if ($user) {
                $stored = $user['password'];
                if (password_verify($password, $stored)) {
                    $ok = true; // modern bcrypt/argon2 hash
                } elseif (hash_equals($stored, md5($password))) {
                    // legacy MD5 hash — verify, then transparently upgrade to bcrypt
                    $ok = true;
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $upd = mysqli_prepare($db, "UPDATE users SET password = ? WHERE username = ?");
                    mysqli_stmt_bind_param($upd, 'ss', $newHash, $username);
                    mysqli_stmt_execute($upd);
                    mysqli_stmt_close($upd);
                }
            }
            if ($ok) {
                login_rate_limit_clear();
                session_regenerate_id(true);
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                $_SESSION['last_activity'] = time();
                header('location: index.php');
                exit;
            } else {
                login_rate_limit_fail();
                array_push($errors, "Wrong username/password combination");
            }
        }
    }
}



// NOTE: The legacy `$_POST['subscribe']` newsletter handler that used to live
// here has been REMOVED (2026-07). It ran on every page that includes this file
// and inserted subscribers directly — with no honeypot, no rate limit and no
// bot protection — which let spam bots flood the `emailsub` table simply by
// POSTing `subscribe`/`emailsubscribe` to any page. All newsletter sign-ups now
// go exclusively through admin/sub_handler.php (?action=subscribe), which
// validates, de-dupes (Gmail-normalised), honeypots and rate-limits the input.

//email send
if (isset($_POST['sendmail'])) {
    if (!isset($_SESSION['username'])) { http_response_code(403); exit; }
    csrf_check();

    $emailcontent = $_POST['emailcontent'];
    $emailtitle = $_POST['emailtitle'];

    $query = "SELECT * FROM emailsub WHERE verified = 1";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {



        sendmail3($row['emailsub_email'], $emailcontent, $emailtitle);
    }
}
if (isset($_POST['sendinternal'])) {
    if (!isset($_SESSION['username'])) { http_response_code(403); exit; }
    csrf_check();

    $allowed_email_templates = [
        'article-061123.php', 'article-120124.php', 'article-271223.php',
        'article-311023.php', 'article-coco011223.php', 'article-glemarie-140923.php',
        'article-goldenlounge-171123.php', 'article-kv4l.php', 'article-madamkwan-221123.php',
        'article.php', 'article151223.php', 'article190124.php', 'article81223.php',
        'billboard.php',
    ];
    $requested = basename($_POST['file'] ?? '');
    if (!in_array($requested, $allowed_email_templates, true)) {
        error_log('sendinternal: blocked disallowed template: ' . $requested);
        http_response_code(400);
        exit;
    }
    $file = __DIR__ . '/email/' . $requested;
    ob_start();
    require_once $file;

    $output = ob_get_clean();

    $output = TinyMinify::html($output);
    $output = urlencode($output);
    $output = preg_replace('/[\x00-\x1F\x7F]/u', '', $output);
    $output2 = urldecode($output);
    // echo $output2;

    $query = "SELECT * FROM internal";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $sendto = $row['internal_email'];
        if (isset($_POST['checkbox_name'])) {
            $emailtitle = $sendto . " - " . $_POST['emailtitle'];
        } else {
            $emailtitle = $_POST['emailtitle'];
        }
        $query4 = "INSERT INTO mailqueue (sendstatus, sendto, sendtitle,sendbody) 
        VALUES('0' , '$sendto' , '$emailtitle', '$output')";
        $result4 = mysqli_query($db, $query4);
        if (!empty($result4)) {
            // echo "ok";
        } else {
            // throw new Exception("Value must be 1 or below");
            echo ("Error description: " . mysqli_error($db));
        }
    }
}


if (isset($_POST['queuemail'])) {
    if (!isset($_SESSION['username'])) { http_response_code(403); exit; }
    csrf_check();

    $allowed_email_templates = [
        'article-061123.php', 'article-120124.php', 'article-271223.php',
        'article-311023.php', 'article-coco011223.php', 'article-glemarie-140923.php',
        'article-goldenlounge-171123.php', 'article-kv4l.php', 'article-madamkwan-221123.php',
        'article.php', 'article151223.php', 'article190124.php', 'article81223.php',
        'billboard.php',
    ];
    $requested = basename($_POST['file'] ?? '');
    if (!in_array($requested, $allowed_email_templates, true)) {
        error_log('queuemail: blocked disallowed template: ' . $requested);
        http_response_code(400);
        exit;
    }
    $file = $requested;
    // ob_start();
    // require_once $file;

    // $output = ob_get_clean();
    $emailtitle = $_POST['emailtitle'];


    $emailcontent = $_POST['emailcontent'];

    $query4 = "INSERT INTO emailcampaign (name, file,title) 
    VALUES('$emailcontent' , '$file','$emailtitle')";
    $result4 = mysqli_query($db, $query4);
    if (!empty($result4)) {
        // echo "ok";

    } else {
        // throw new Exception("Value must be 1 or below");
        echo ("Error description: " . mysqli_error($db));
    }


    $query = "SELECT * FROM emailsub WHERE verified = 1";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $sendto = $row['emailsub_email'];
        if (isset($_POST['checkbox_name'])) {
            $emailtitle = $sendto . " - " . $_POST['emailtitle'];
        } else {
            $emailtitle = $_POST['emailtitle'];
        }
        $query4 = "INSERT INTO mailqueue (sendstatus, sendto, sendtitle,sendbody) 
        VALUES('0' , '$sendto' , '$emailtitle', '$file')";
        $result4 = mysqli_query($db, $query4);
        if (!empty($result4)) {
            // echo "ok";
        } else {
            // throw new Exception("Value must be 1 or below");
            echo ("Error description: " . mysqli_error($db));
        }
    }
}

//pushnotification
if (isset($_POST['sendpushnotification'])) {
    if (!isset($_SESSION['username'])) { http_response_code(403); exit; }
    csrf_check();

    $pushtitle = $_POST['pushtitle'];
    $pushcontent = $_POST['pushcontent'];

    $notifications = [];

    $query = "SELECT * FROM pushsub";

    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_assoc($result)) {

        $endpoint = $row['endpoint'];
        $p256dh = $row['p256dh'];
        $auth = $row['auth'];

        $sub = [
            'subscription' => Subscription::create([
                'endpoint' => $endpoint,
                // Firefox 43+,
                'publicKey' => $p256dh,
                // base 64 encoded, should be 88 chars
                'authToken' => $auth, // base 64 encoded, should be 24 chars
            ])
        ];
        array_push($notifications, $sub);
        // var_dump($notifications);

    }

    $defaultOptions = [
        "TTL" => 0,
        // defaults to 4 weeks
        "urgency" => "normal",
        // protocol defaults to "normal". (very-low, low, normal, or high)
        "topic" => "newEvent",
        // not defined by default. Max. 32 characters from the URL or filename-safe Base64 characters sets
        "batchSize" => 200, // defaults to 1000
    ];
    $push = new WebPush([
        "VAPID" => [
            "subject" => "mailto: <izmeera2000@gmail.com>",
            "publicKey" => $_ENV['VAPID_PUBLIC_KEY'],
            "privateKey" => $_ENV['VAPID_PRIVATE_KEY']
        ]
    ], $defaultOptions);
    $push->setDefaultOptions($defaultOptions);

    foreach ($notifications as $notification) {

        $push->queueNotification($notification['subscription'], json_encode([
            "title" => $pushtitle,
            "body" => $pushcontent,
            "icon" => "../assets/img/android-chrome-192x192.png",
            "image" => "../assets/img/sign.jpg",
            "badge" => "../assets/img/android-chrome-192x192.png",
            "data" => ["url" => "ebook.php#ebook"],
        ]));
    }

    foreach ($push->flush() as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();

        if ($report->isSuccess()) {
            // echo "<br>[v] Message sent successfully for subscription {$endpoint}.";
            array_push($errors2, "Message sent successfully for subscription");
        } else {
            // echo $report->getReason();
            // echo $endpoint;

            $query = "DELETE FROM pushsub WHERE endpoint='$endpoint'";
            mysqli_query($db, $query);

            // array_push($errors2, "{$endpoint}: {$report->getReason()}");


        }
    }
}
//post user sub data to db
if (isset($_POST['sub'])) {

    $subData = json_decode($_POST["sub"]);
    $p256dh = $subData->keys->p256dh;
    $auth = $subData->keys->auth;
    $endpoint = $subData->endpoint;

    $stmt = mysqli_prepare($db, "INSERT INTO pushsub (endpoint, p256dh, auth) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $endpoint, $p256dh, $auth);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


// Central CSRF guard: fires once before all pagefunctions.
// Covers all 26 pagefunctions handlers without touching each file.
// Only enforced on admin pages with an active session. Excludes sub_handler.php's
// public, unauthenticated actions (e.g. newsletter "subscribe") — those are hit by
// anonymous site visitors and never carry a CSRF token, but an admin who is also
// logged into the CMS in the same browser would otherwise get falsely blocked here.
$public_subhandler_actions = ['subscribe'];
$is_public_subhandler_action = isset($_GET['action']) && in_array($_GET['action'], $public_subhandler_actions, true);
if (!empty($_POST) && isset($_SESSION['username']) && strpos($_SERVER['PHP_SELF'], '/admin/') !== false && !$is_public_subhandler_action) {
    csrf_check();
}

include 'pagefunctions/sub.php';

include 'pagefunctions/edit-index.php';
include 'pagefunctions/edit-pageviews.php';
include 'pagefunctions/edit-blog.php';
include 'pagefunctions/edit-ebook.php';
include 'pagefunctions/edit-merchandise.php';


include 'pagefunctions/edit-explorekl-wte.php';
include 'pagefunctions/edit-explorekl-wtd.php';
include 'pagefunctions/edit-explorekl-ss.php';
include 'pagefunctions/edit-explorekl-nl.php';
include 'pagefunctions/edit-explorekl-pwor.php';
include 'pagefunctions/edit-explorekl-p.php';
include 'pagefunctions/edit-explorekl-kl4k.php';
include 'pagefunctions/edit-explorekl-hs.php';

include 'pagefunctions/edit-beyondkl-es.php';
include 'pagefunctions/edit-beyondkl-h.php';
include 'pagefunctions/edit-beyondkl-hs.php';
include 'pagefunctions/edit-beyondkl-w.php';
include 'pagefunctions/edit-beyondkl-i.php';

include 'pagefunctions/edit-highlights-1.php';

include 'pagefunctions/edit-klglance.php';

include 'pagefunctions/edit-accomodation.php';

include 'pagefunctions/edit-pts.php';

include 'pagefunctions/edit-spa.php';

include 'pagefunctions/edit-mt.php';
include 'pagefunctions/edit-ev.php';
include 'pagefunctions/edit-advertisement.php';


if (isset($_POST['fetch_events'])) {
    $myArray = array();
    $currentMonth = date('n'); // Current month as number (1-12)
    $currentYear = date('Y');  // Current year

    // Fetch events for the current month and year or January of the next year
    $query = "
        SELECT event_title, event_content, event_location, event_image, event_day, event_month, event_year 
        FROM event 
        WHERE 
            (event_year = $currentYear AND event_month >= $currentMonth) 
            OR (event_year = $currentYear + 1 AND event_month = 1)
    ";

    $result = mysqli_query($db, $query);

    if (!$result) {
        error_log("Database Query Failed: " . mysqli_error($db));
        echo json_encode(["error" => "Database query failed"]);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $myArray[] = [
            'event_title' => urldecode($row['event_title']),
            'event_content' => urldecode($row['event_content']),
            'event_location' => urldecode($row['event_location']),
            'event_image' => 'https://www.kltheguide.com.my/assets/img/event/' . urldecode($row['event_image']),
            'event_day' => urldecode($row['event_day']),
            'event_month' => urldecode($row['event_month']),
            'event_year' => urldecode($row['event_year'])
        ];
    }

    echo json_encode($myArray);
}

if (isset($_POST['fetch_vouchers'])) {
    $myArray = array();

    // Select all vouchers from the voucher table
    $query = "SELECT voucher, voucher_title, voucher_image, voucher_expiry_date 
              FROM voucher 
              ORDER BY voucher_expiry_date ASC";

    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $myArray[] = [
            'voucher' => urldecode($row['voucher']),
            'voucher_title' => urldecode($row['voucher_title']),
            'voucher_image' => 'https://www.kltheguide.com.my/assets/img/voucher/' . urldecode($row['voucher_image']),
            'voucher_expiry_date' => $row['voucher_expiry_date'],
        ];
    }

    echo json_encode($myArray);
}

if (isset($_POST['appExploreKL_WTD'])) {
    $myArray = array();
    $query = "SELECT explorekl_wtd_title as title,explorekl_wtd_content as content, explorekl_wtd_image as image  FROM explorekl_wtd ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/wtd/' . urldecode($row['image']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appExploreKL_HS'])) {
    $myArray = array();
    $query = "SELECT explorekl_hs_title as title,explorekl_hs_content as content, explorekl_hs_image as image  ,explorekl_hs_location as location , explorekl_hs_locationurl as locationurl, explorekl_hs_hours as hours , explorekl_hs_phone as phone FROM explorekl_hs ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/hs/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),

        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appExploreKL_P'])) {
    $myArray = array();
    $query = "SELECT explorekl_p_title as title,explorekl_p_content as content,explorekl_p_content2 as content2, explorekl_p_image as image , explorekl_p_location as location ,explorekl_p_locationurl as locationurl ,explorekl_p_website as website , explorekl_p_hours as hours , explorekl_p_phone as phone  FROM explorekl_p ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'content2' => urldecode($row['content2']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/p/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appExploreKL_KL4K'])) {
    $myArray = array();
    $query = "SELECT explorekl_kl4k_title as title,explorekl_kl4k_content as content,explorekl_kl4k_content2 as content2, explorekl_kl4k_image as image , explorekl_kl4k_location as location ,explorekl_kl4k_locationurl as locationurl ,explorekl_kl4k_website as website , explorekl_kl4k_hours as hours , explorekl_kl4k_phone as phone  FROM explorekl_kl4k ";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'content2' => urldecode($row['content2']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/kl4k/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appExploreKL_PWOR'])) {
    $myArray = array();
    $category = $_POST['category'];
    $query = "SELECT explorekl_pwor_title as title,explorekl_pwor_content as content,explorekl_pwor_image as image , explorekl_pwor_website as website ,explorekl_pwor_location as location,explorekl_pwor_locationurl as locationurl , explorekl_pwor_hours as hours FROM explorekl_pwor WHERE explorekl_pwor_category='$category'";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/pwor/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appExploreKL_WTE_SF'])) {
    $myArray = array();
    $query = "SELECT explorekl_wte_sf_title as title,explorekl_wte_sf_content as content, explorekl_wte_sf_image as image , explorekl_wte_sf_location as location , explorekl_wte_sf_locationurl as locationurl FROM explorekl_wte_sf ";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/wte/sf/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),

        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appExploreKL_WTE_C'])) {
    $myArray = array();
    $query = "SELECT explorekl_wte_c_title as title,explorekl_wte_c_location as location , explorekl_wte_c_image as image , explorekl_wte_c_content as content ,explorekl_wte_c_locationurl as locationurl , explorekl_wte_c_hours as hours , explorekl_wte_c_hours as hours ,explorekl_wte_c_website as website  FROM explorekl_wte_c ";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [

            'title' => urldecode($row['title']),
            'content' => '',
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/wte/c/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appExploreKL_WTE_R'])) {
    $myArray = array();
    $query = "SELECT explorekl_wte_r_title as title,explorekl_wte_r_location as location , explorekl_wte_r_image as image , explorekl_wte_r_content as content ,explorekl_wte_r_locationurl as locationurl , explorekl_wte_r_hours as hours , explorekl_wte_r_hours as hours,  explorekl_wte_r_website as website  FROM explorekl_wte_r ";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => '',
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/wte/r/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appExploreKL_NL'])) {
    $myArray = array();
    $category = $_POST['category'];
    $query = "SELECT explorekl_nl_title as title,explorekl_nl_location as location , explorekl_nl_image as image , explorekl_nl_content as content ,explorekl_nl_locationurl as locationurl , explorekl_nl_hours as hours , explorekl_nl_hours as hours,  explorekl_nl_website as website  FROM explorekl_nl WHERE explorekl_nl_category='$category'";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/nl/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appExploreKL_SS'])) {
    $myArray = array();
    $category = $_POST['category'];
    $query = "SELECT explorekl_ss_title as title,explorekl_ss_location as location , explorekl_ss_image as image , explorekl_ss_content as content , explorekl_ss_content2 as content2,explorekl_ss_locationurl as locationurl , explorekl_ss_hours as hours , explorekl_ss_hours as hours,  explorekl_ss_website as website  FROM explorekl_ss WHERE explorekl_ss_category='$category'";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'content2' => urldecode($row['content2']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/explorekl/ss/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appShop'])) {
    $myArray = array();
    $query = "SELECT place_shop_title as title,place_shop_location as location , place_shop_image as image , place_shop_content as content ,place_shop_locationurl as locationurl , place_shop_hours as hours , place_shop_hours as hours,  place_shop_website as website  FROM place_shop ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/place_to_shop/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
            'website' => urldecode($row['website']),
        ];
    }
    echo json_encode($myArray);
}

if (isset($_POST['appSpa'])) {
    $myArray = array();
    $query = "SELECT spa_title as title,spa_location as location , spa_image as image , spa_content as content , spa_locationurl as locationurl , spa_hours as hours , spa_hours as hours  FROM spa ORDER BY spa_order DESC";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/spa/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appMedicalT_hc'])) {
    $myArray = array();
    $query = "SELECT medical_tourism_hc_title as title,medical_tourism_hc_location as location , medical_tourism_hc_image as image , medical_tourism_hc_content as content , medical_tourism_hc_locationurl as locationurl , medical_tourism_hc_hours as hours , medical_tourism_hc_hours as hours FROM medical_tourism_hc ORDER BY medical_tourism_hc_order DESC ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/medical_tourism/hc/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appMedicalT_dtl'])) {
    $myArray = array();
    $query = "SELECT medical_tourism_dtl_title as title,medical_tourism_dtl_location as location , medical_tourism_dtl_image as image , medical_tourism_dtl_content as content , medical_tourism_dtl_locationurl as locationurl , medical_tourism_dtl_hours as hours , medical_tourism_dtl_hours as hours FROM medical_tourism_dtl ORDER BY medical_tourism_dtl_order DESC ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/medical_tourism/dtl/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}

if (isset($_POST['appMedicalT_der'])) {
    $myArray = array();
    $query = "SELECT medical_tourism_der_title as title,medical_tourism_der_location as location , medical_tourism_der_image as image , medical_tourism_der_content as content , medical_tourism_der_locationurl as locationurl , medical_tourism_der_hours as hours , medical_tourism_der_hours as hours FROM medical_tourism_der ORDER BY medical_tourism_der_order DESC ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/medical_tourism/der/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}

if (isset($_POST['appMedicalT_oph'])) {
    $myArray = array();
    $query = "SELECT medical_tourism_oph_title as title,medical_tourism_oph_location as location , medical_tourism_oph_image as image , medical_tourism_oph_content as content , medical_tourism_oph_locationurl as locationurl , medical_tourism_oph_hours as hours , medical_tourism_oph_hours as hours FROM medical_tourism_oph ORDER BY medical_tourism_oph_order DESC ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/medical_tourism/oph/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}




if (isset($_POST['appMedicalT_ps'])) {
    $myArray = array();
    $query = "SELECT medical_tourism_ps_title as title,medical_tourism_ps_location as location , medical_tourism_ps_image as image , medical_tourism_ps_content as content , medical_tourism_ps_locationurl as locationurl , medical_tourism_ps_hours as hours , medical_tourism_ps_hours as hours FROM medical_tourism_ps ORDER BY medical_tourism_ps_order DESC ";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/medical_tourism/ps/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appStay_top'])) {
    $myArray = array();
    $query = "SELECT accommodation_top_title as title,accommodation_top_location as location , accommodation_top_image as image , accommodation_top_content as content , accommodation_top_locationurl as locationurl , accommodation_top_hours as hours , accommodation_top_hours as hours  FROM accommodation_top ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/accommodation/top/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}

if (isset($_POST['appStay_h'])) {
    $myArray = array();
    $query = "SELECT accommodation_h_title as title,accommodation_h_location as location , accommodation_h_image as image , accommodation_h_content as content , accommodation_h_locationurl as locationurl , accommodation_h_hours as hours , accommodation_h_hours as hours  FROM accommodation_h ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/accommodation/h/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appStay_bh'])) {
    $myArray = array();
    $query = "SELECT accommodation_bh_title as title,accommodation_bh_location as location , accommodation_bh_image as image , accommodation_bh_content as content , accommodation_bh_locationurl as locationurl , accommodation_bh_hours as hours , accommodation_bh_hours as hours  FROM accommodation_bh ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/accommodation/bh/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appStay_bks'])) {
    $myArray = array();
    $query = "SELECT accommodation_bks_title as title,accommodation_bks_location as location , accommodation_bks_image as image , accommodation_bks_content as content , accommodation_bks_locationurl as locationurl , accommodation_bks_hours as hours , accommodation_bks_hours as hours  FROM accommodation_bks ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/accommodation/bks/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
            'locationurl' => urldecode($row['locationurl']),
            'hours' => urldecode($row['hours']),
            'phone' => urldecode($row['phone']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appBeyondKL_i'])) {
    $myArray = array();
    $query = "SELECT beyondkl_i_title as title,beyondkl_i_content as content, beyondkl_i_image as image , beyondkl_i_location as location FROM beyondkl_i ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/beyondkl/i/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appBeyondKL_hs'])) {
    $myArray = array();
    $query = "SELECT beyondkl_hs_title as title,beyondkl_hs_content as content, beyondkl_hs_image as image , beyondkl_hs_location as location FROM beyondkl_hs ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/beyondkl/hs/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appBeyondKL_w'])) {
    $myArray = array();
    $query = "SELECT beyondkl_w_title as title,beyondkl_w_content as content, beyondkl_w_image as image , beyondkl_w_location as location FROM beyondkl_w ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/beyondkl/w/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST['appBeyondKL_h'])) {
    $myArray = array();
    $query = "SELECT beyondkl_h_title as title,beyondkl_h_content as content, beyondkl_h_image as image , beyondkl_h_location as location FROM beyondkl_h ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/beyondkl/h/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appBeyondKL_es'])) {
    $myArray = array();
    $query = "SELECT beyondkl_es_title as title,beyondkl_es_content as content, beyondkl_es_image as image  , beyondkl_es_location as location FROM beyondkl_es ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => 'https://www.kltheguide.com.my/' . 'assets/img/beyondkl/es/' . urldecode($row['image']),
            'location' => urldecode($row['location']),
        ];
    }
    echo json_encode($myArray);
}


if (isset($_POST['appEbook'])) {
    $myArray = array();
    $category = $_POST['category'];
    $query = "SELECT ebook_name as title,ebook_filename as content, ebook_image as image FROM ebook WHERE ebook_category='$category' ORDER BY ebook_name DESC;";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['content']) {
            $myArray[] = [
                'title' => urldecode($row['title']),
                'content' => 'https://www.kltheguide.com.my/' . 'assets/pdf/ebook/' . $category . '/' . urldecode($row['content']),
                'image' => 'https://www.kltheguide.com.my/' . 'assets/img/ebook/' . $category . '/' . urldecode($row['image']),
            ];
        } else {
            $myArray[] = [
                'title' => urldecode($row['title']),
                'content' => '',
                'image' => 'https://www.kltheguide.com.my/' . 'assets/img/ebook/' . $category . '/' . urldecode($row['image']),
            ];
        }
    }
    echo json_encode($myArray);
}


if (isset($_POST['appAdsSettings'])) {
    $myArray = [];
    $query = "SELECT * FROM adsettings ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $myArray[$row['settingname']] = $row['value'];
    }

    echo json_encode($myArray);
}


if (isset($_POST['appAdsURL'])) {
    $myArray = [];
    $query = "SELECT * FROM banner ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['banner_filename2']) {


            $myArray[] = [
                'imageURL' => 'https://www.kltheguide.com.my/assets/img/banner/' . urldecode($row['banner_filename2']),
                'URL' => urlencode($row['banner_url']),

            ];
        }
    }

    echo json_encode($myArray);
}

if (isset($_POST['appHighlights'])) {
    $myArray = array();
    $category = $_POST['category'];
    $query = "SELECT highlights_title as title,highlights_content as content, highlights_image as image,  highlights_location as location FROM highlights WHERE highlights_category='$category' ORDER BY highlights_id ASC ";
    $result = mysqli_query($db, $query);
    $counter = 0;

    while ($row = mysqli_fetch_assoc($result)) {

        $myArray[] = [
            'title' => urldecode($row['title']),
            'content' => urldecode($row['content']),
            'image' => urldecode($row['image']),
            'location' => urldecode($row['location']),
        ];
    }
    echo json_encode($myArray);
}



if (isset($_POST["testqueue"])) {


    // $emailtitle = $_POST['emailtitle'];
    $datenow = date('H:00:00');
    // echo $datenow;

    $dateworkstart = date('09:00:00');


    $dateworkend = date('19:00:00');

    if (($datenow >= $dateworkstart) & ($datenow <= $dateworkend)) {

        $limitsend = 0;
    } else {
        $limitsend = 80;
    }




    $query4 = "SELECT * FROM mailqueue WHERE sendstatus='0' LIMIT $limitsend ";
    $result4 = mysqli_query($db, $query4);
    $counter = 0;
    while ($row4 = mysqli_fetch_assoc($result4)) {

        $id = $row4['id'];
        $date = date('Y-m-d H:i:s');
        // echo "send";

        $filename = $row4['sendbody'];

        if (($datenow >= $dateworkstart) & ($datenow <= $dateworkend)) {
        } else {
            $html = is_file($filename) ? file_get_contents($filename) : $filename;
            $response = send_email_html($row4['sendto'], $row4['sendtitle'], $html, 'KL The Guide') ? '1' : '0';

            if ($counter == 0) {
                sendmail('annie@bluedale.com.my', $filename, $row4['sendtitle']);
            }
        }

        $query = "UPDATE mailqueue SET sendstatus='$response' , send_time='$date'  WHERE  id=$id";

        $update = mysqli_query($db, $query);


        $counter++;
    }
}

if (isset($_POST["clearqueue"])) {
    // echo "sucess";

    $query4 = "DELETE FROM  mailqueue WHERE sendstatus=1 ";
    $result4 = mysqli_query($db, $query4);
}

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), "", strtolower(trim($purpose)));
    $support = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if ($ipdat != '') {
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
    }
    return $output;
}

if (isset($_POST["banner"]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log the entire POST data
    error_log("Full POST data: " . print_r($_POST, true));

    $filename = isset($_POST['banner_filename']) ? trim(urldecode($_POST['banner_filename'])) : '';
    $name = isset($_POST['banner_name']) ? trim(urldecode($_POST['banner_name'])) : '';
    $clicks = isset($_POST['clicks']) ? (int)$_POST['clicks'] : 0;

    error_log("Parsed data - filename: '{$filename}', name: '{$name}', clicks: {$clicks}");

    if (empty($filename) || empty($name)) {
        error_log("Missing required data");
        exit('Missing data');
    }

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($db, "SELECT banner_name FROM banner WHERE banner_name = ?");
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        error_log("No banner found with name: {$name}");
        mysqli_stmt_close($stmt);
        exit('Banner not found');
    }
    mysqli_stmt_close($stmt);

    // Only increment if clicks = 1
    if ($clicks == 1) {
        $stmt = mysqli_prepare($db, "UPDATE banner SET click_count = click_count + 1 WHERE banner_name = ?");
        mysqli_stmt_bind_param($stmt, "s", $name);

        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            error_log("Update successful - rows affected: {$affected_rows}, banner name: {$name}");
        } else {
            error_log("Update failed: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    }

    exit('OK');
}


if (isset($_POST['contribute'])) {
    // Article contribution form (contribute.php) — emailed straight to the editorial inbox.
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $title   = trim($_POST['title']   ?? '');
    $article = trim($_POST['article'] ?? '');

    $toEmail = 'enquiry@bluedale.com.my';
    $subject = 'Article Contribution' . ($title !== '' ? ' - ' . $title : '');

    $htmlContent =
        '<h2>New Article Contribution</h2>' .
        '<p><b>Name:</b> '          . htmlspecialchars($name)  . '</p>' .
        '<p><b>Email:</b> '         . htmlspecialchars($email) . '</p>' .
        '<p><b>Article Title:</b> ' . htmlspecialchars($title) . '</p>' .
        '<hr><p><b>Article:</b></p>' .
        '<div>' . nl2br(htmlspecialchars($article)) . '</div>';

    try {
        $mail = mailer_from_config();
        $mail->addAddress($toEmail);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($email, $name !== '' ? $name : $email);
        }
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body    = $htmlContent;
        $mail->AltBody = strip_tags($htmlContent);
        $mail->send();

        header('Location: contribute.php?sent=1');
    } catch (Exception $e) {
        error_log('Contribute mail failed: ' . $e->getMessage());
        header('Location: contribute.php?sent=0');
    }
    exit;
}
if (isset($_POST['advertise'])) {
    $email   = $_POST['email']   ?? '';
    $name    = $_POST['name']    ?? '';
    $company = $_POST['company'] ?? '';
    $phone   = $_POST['phone']   ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $toEmail   = 'enquiry@bluedale.com.my';
    $fromName  = 'Advertise With Us - ' . $company;

    $htmlContent =
        '<h2>' . htmlspecialchars($subject) . '</h2>' .
        '<p><b>Name:</b> '    . htmlspecialchars($name)    . '</p>' .
        '<p><b>Email:</b> '   . htmlspecialchars($email)   . '</p>' .
        '<p><b>Company:</b> ' . htmlspecialchars($company) . '</p>' .
        '<p><b>Phone:</b> '   . htmlspecialchars($phone)   . '</p>' .
        '<p><b>Message:</b><br/>' . nl2br(htmlspecialchars($message)) . '</p>';

    try {
        $mail = mailer_from_config();
        $mail->addAddress($toEmail);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($email, $name !== '' ? $name : $email);
        }
        $mail->Subject = $fromName;
        $mail->isHTML(true);
        $mail->Body    = $htmlContent;
        $mail->AltBody = strip_tags($htmlContent);
        $mail->send();

        header('Location: advertisewithus.php?sent=1');
    } catch (Exception $e) {
        error_log('Advertise mail failed: ' . $e->getMessage());
        header('Location: advertisewithus.php?sent=0');
    }
    exit;
}
