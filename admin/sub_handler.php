<?php
// sub_handler.php - Fixed with guards & error handling

// Include your DB connection
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hide notices/warnings for production
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// ============================================
// OUTPUT HELPERS (must be defined first)
// ============================================

function _flush_buffers()
{
    while (ob_get_level()) ob_end_clean();
}

function json_out($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
}
function html_out($html, $code = 200)
{
    http_response_code($code);
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

// ============================================
// DB CONNECTION GUARD
// ============================================
if (!isset($db) || !$db) {
    json_out(['ok' => false, 'error' => 'Database connection not available'], 500);
}

// Test connection
if (mysqli_connect_errno()) {
    json_out(['ok' => false, 'error' => 'Database connection failed'], 500);
}

// ============================================
// DB ERROR HANDLER
// ============================================
function db_or_500($res, $db, $context = '')
{
    if ($res === false) {
        $err = mysqli_error($db);
        error_log("DB error @{$context}: {$err}");
        json_out(['ok' => false, 'error' => "Database error @{$context}"], 500);
    }
    return $res;
}

// ============================================
// HELPER FUNCTIONS
// ============================================

function redirect_get(string $fallback = 'sub.php'): void
{
    $to = $fallback;
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $to = strtok($_SERVER['HTTP_REFERER'], '#');
        $to = strtok($to, '?');
    }
    header('Location: ' . $to);
    exit;
}

ob_start();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ============================================
// EMAIL TEMPLATE ENDPOINTS
// ============================================

if ($action === 'templates.list') {
    require_admin();

    // Check if table exists
    $check = db_or_500(
        mysqli_query($db, "SHOW TABLES LIKE 'email_templates'"),
        $db,
        'check email_templates table'
    );

    if (mysqli_num_rows($check) === 0) {
        json_out([
            'ok' => true,
            'data' => [],
            'message' => 'Table email_templates does not exist yet'
        ]);
    }

    $sql = "SELECT id, slug, subject, preheader, from_name, draft, is_active_subscribe, updated_at 
            FROM email_templates 
            ORDER BY is_active_subscribe DESC, draft DESC, updated_at DESC";
    $res = db_or_500(mysqli_query($db, $sql), $db, 'templates.list');

    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $rows[] = [
            'id'         => (int)$r['id'],
            'slug'       => $r['slug'],
            'subject'    => $r['subject'],
            'preheader'  => $r['preheader'],
            'from_name'  => $r['from_name'],
            'draft'      => (int)$r['draft'],
            'is_active'  => (int)$r['is_active_subscribe'],
            'updated_at' => $r['updated_at'],
        ];
    }

    json_out(['ok' => true, 'data' => $rows]);
}

if ($action === 'templates.get') {
    require_admin();
    $slug = $_GET['slug'] ?? '';

    if ($slug === '') {
        json_out(['ok' => false, 'error' => 'Missing slug parameter'], 422);
    }

    $tpl = get_template_by_slug($db, $slug);
    if (!$tpl) {
        json_out(['ok' => false, 'error' => 'Template not found'], 404);
    }

    json_out(['ok' => true, 'data' => $tpl]);
}

if ($action === 'templates.save') {
    require_admin();

    $slug       = trim($_POST['slug'] ?? '');
    $subject    = trim($_POST['subject'] ?? '');
    $preheader  = trim($_POST['preheader'] ?? '');
    $from_name  = trim($_POST['from_name'] ?? '');
    $body_html  = $_POST['body_html'] ?? '';
    $footer_html = $_POST['footer_html'] ?? '';
    $draft      = isset($_POST['draft']) ? (int)$_POST['draft'] : 1; // Default to draft
    $user       = $_SESSION['username'] ?? 'admin';

    if ($slug === '') {
        json_out(['ok' => false, 'error' => 'Missing slug'], 422);
    }

    $stmt = mysqli_prepare($db, "
        INSERT INTO email_templates (slug, subject, preheader, from_name, body_html, footer_html, draft, updated_by)
        VALUES (?,?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE
          subject=VALUES(subject),
          preheader=VALUES(preheader),
          from_name=VALUES(from_name),
          body_html=VALUES(body_html),
          footer_html=VALUES(footer_html),
          draft=VALUES(draft),
          updated_by=VALUES(updated_by),
          updated_at=CURRENT_TIMESTAMP
    ");

    if (!$stmt) {
        db_or_500(false, $db, 'templates.save prepare');
    }

    mysqli_stmt_bind_param($stmt, 'ssssssis', $slug, $subject, $preheader, $from_name, $body_html, $footer_html, $draft, $user);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        db_or_500(false, $db, 'templates.save execute');
    }

    mysqli_stmt_close($stmt);

    if ($tpl = get_template_by_slug($db, $slug)) {
        save_template_version($db, (int)$tpl['id'], $tpl, $user);
    }

    json_out(['ok' => true, 'message' => 'Template saved successfully']);
}

if ($action === 'templates.preview') {
    require_admin();

    $subject     = $_POST['subject'] ?? 'Preview';
    $preheader   = $_POST['preheader'] ?? null;
    $from_name   = $_POST['from_name'] ?? brand_name();
    $body_html   = $_POST['body_html'] ?? '<p>Empty</p>';
    $footer_html = $_POST['footer_html'] ?? null;
    $sample_email = $_POST['sample_email'] ?? 'subscriber@example.com';

    $context = [
        'email' => $sample_email,
        'date' => date('d M Y'),
        'site_name' => brand_name(),
        'unsubscribe_url' => app_base_url() . '/unsubscribe.php?e=' . urlencode($sample_email),
    ];

    $html = render_email_html($subject, $preheader, $from_name, $body_html, $footer_html, $context);
    html_out($html);
}

if ($action === 'templates.sendtest') {
    require_admin();

    $to_email    = trim($_POST['to_email'] ?? '');
    $subject     = trim($_POST['subject'] ?? '');
    $preheader   = $_POST['preheader'] ?? null;
    $from_name   = $_POST['from_name'] ?? brand_name();
    $body_html   = $_POST['body_html'] ?? '';
    $footer_html = $_POST['footer_html'] ?? null;

    if ($to_email === '' || $subject === '') {
        json_out(['ok' => false, 'error' => 'Missing to_email or subject'], 422);
    }

    $context = [
        'email' => $to_email,
        'date' => date('d M Y'),
        'site_name' => brand_name(),
        'unsubscribe_url' => app_base_url() . '/unsubscribe.php?e=' . urlencode($to_email),
    ];

    $html = render_email_html($subject, $preheader, $from_name, $body_html, $footer_html, $context);

    // === Send directly WITHOUT queue ===
    $sent = send_email_html($to_email, $subject, $html, $from_name);

    // Record result in mailqueue for tracking
    $status = $sent ? 'sent' : 'smtp_failed';
    if ($sent) {
        $stmt = mysqli_prepare($db, "
            INSERT INTO mailqueue (sendstatus, sendto, sendtitle, sendbody, init_time, send_time)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        mysqli_stmt_bind_param($stmt, 'ssss', $status, $to_email, $subject, $html);
    } else {
        $stmt = mysqli_prepare($db, "
            INSERT INTO mailqueue (sendstatus, sendto, sendtitle, sendbody, init_time, send_time)
            VALUES (?, ?, ?, ?, NOW(), NULL)
        ");
        mysqli_stmt_bind_param($stmt, 'ssss', $status, $to_email, $subject, $html);
    }

    if ($stmt) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    json_out(['ok' => $sent, 'sent' => $sent, 'status' => $sent ? 'sent' : 'smtp_failed'], $sent ? 200 : 500);
}

if ($action === 'assets.upload') {
    require_admin();

    if (!isset($_FILES['file'])) {
        json_out(['ok' => false, 'error' => 'No file uploaded'], 422);
    }

    try {
        $info = upload_email_asset($_FILES['file'], 'email', ($_SESSION['username'] ?? null), $db);
        json_out(['ok' => true, 'data' => $info]);
    } catch (Throwable $e) {
        json_out(['ok' => false, 'error' => $e->getMessage()], 400);
    }
}

if ($action === 'assets.list') {
    require_admin();

    $sql = "SELECT id, url, filename, size, width, height, uploaded_at 
            FROM email_assets 
            WHERE is_deleted=0 
            ORDER BY id DESC 
            LIMIT 100";
    $res = db_or_500(mysqli_query($db, $sql), $db, 'assets.list');

    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $rows[] = $r;
    }

    json_out(['ok' => true, 'data' => $rows]);
}

if ($action === 'subscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_out(['ok' => false, 'error' => 'Invalid email address'], 422);
    }

    $email   = strtolower($email);
    $country = trim($_POST['country'] ?? '');
    $consent = isset($_POST['consent']) ? 1 : 0;

    // Check if this email already exists
    $chk = mysqli_prepare($db, "SELECT emailsub_id, verified FROM emailsub WHERE emailsub_email = ? LIMIT 1");
    mysqli_stmt_bind_param($chk, 's', $email);
    mysqli_stmt_execute($chk);
    $chkRes  = mysqli_stmt_get_result($chk);
    $existing = mysqli_fetch_assoc($chkRes);
    mysqli_stmt_close($chk);

    // Already verified — nothing more to do
    if ($existing && (int)$existing['verified'] === 1) {
        json_out(['ok' => true, 'inserted' => false, 'status' => 'duplicate']);
    }

    // Generate a fresh token (64 hex chars) valid for 48 hours
    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+48 hours'));

    if ($existing) {
        // Pending verification — refresh token so a new email can be sent
        $upd = mysqli_prepare($db, "UPDATE emailsub SET verify_token = ?, verify_expires = ? WHERE emailsub_email = ?");
        mysqli_stmt_bind_param($upd, 'sss', $token, $expires, $email);
        mysqli_stmt_execute($upd);
        mysqli_stmt_close($upd);
    } else {
        // New subscriber — insert as unverified
        $ins = mysqli_prepare($db, "
            INSERT INTO emailsub (emailsub_email, emailsub_country, emailsub_consent, emailsub_date, verified, verify_token, verify_expires)
            VALUES (?, ?, ?, NOW(), 0, ?, ?)
        ");
        if (!$ins) {
            db_or_500(false, $db, 'subscribe insert prepare');
        }
        mysqli_stmt_bind_param($ins, 'ssiss', $email, $country, $consent, $token, $expires);
        mysqli_stmt_execute($ins);
        if (mysqli_stmt_affected_rows($ins) < 1) {
            mysqli_stmt_close($ins);
            json_out(['ok' => false, 'error' => 'Could not save subscription'], 500);
        }
        mysqli_stmt_close($ins);
    }

    // Build the verification email
    $verifyUrl  = app_base_url() . '/verify-email.php?token=' . urlencode($token);
    $brandName  = brand_name();
    $subject    = 'Confirm your subscription – ' . $brandName;
    $fromName   = $brandName;
    $urlEsc     = htmlspecialchars($verifyUrl, ENT_QUOTES, 'UTF-8');
    $brandEsc   = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');

    $html = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Confirm your subscription</title></head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#333;background:#f5f5f5;margin:0;padding:0;">
  <div style="max-width:600px;margin:40px auto;background:#fff;padding:36px;border-radius:8px;">
    <h2 style="color:#2c3e50;margin-top:0;">One more step!</h2>
    <p>Thanks for subscribing to <strong>' . $brandEsc . '</strong>.</p>
    <p>Click the button below to confirm your email address and activate your subscription:</p>
    <p style="text-align:center;margin:32px 0;">
      <a href="' . $urlEsc . '" style="background:#c8a96e;color:#fff;padding:14px 32px;text-decoration:none;border-radius:4px;font-weight:bold;font-size:16px;display:inline-block;">Confirm my subscription</a>
    </p>
    <p style="color:#666;font-size:13px;">Or paste this link into your browser:<br>
      <a href="' . $urlEsc . '" style="color:#c8a96e;word-break:break-all;">' . $urlEsc . '</a>
    </p>
    <p style="color:#888;font-size:12px;margin-top:24px;">This link expires in 48 hours. If you did not subscribe, you can safely ignore this email.</p>
    <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
    <p style="font-size:12px;color:#aaa;">' . $brandEsc . '</p>
  </div>
</body>
</html>';

    $sent = send_email_html($email, $subject, $html, $fromName);

    // Log in mailqueue for tracking
    $qStatus   = $sent ? 'sent' : 'smtp_failed';
    $qSendTime = $sent ? date('Y-m-d H:i:s') : null;
    $qStmt = mysqli_prepare($db, "
        INSERT INTO mailqueue (sendstatus, sendto, sendtitle, sendbody, init_time, send_time)
        VALUES (?, ?, ?, ?, NOW(), ?)
    ");
    if ($qStmt) {
        mysqli_stmt_bind_param($qStmt, 'sssss', $qStatus, $email, $subject, $html, $qSendTime);
        mysqli_stmt_execute($qStmt);
        mysqli_stmt_close($qStmt);
    }

    json_out(['ok' => true, 'inserted' => true, 'sent' => $sent, 'status' => 'verification_sent']);
}

// ============================================
// DATATABLES HANDLERS
// ============================================

$tableName = $_GET['table'] ?? '';

if ($tableName === 'emailsub') {
    require_admin();

    $draw   = (int)($_GET['draw'] ?? 1);
    $start  = (int)($_GET['start'] ?? 0);
    $length = (int)($_GET['length'] ?? 10);
    $search = trim($_GET['search']['value'] ?? '');
    $start_date = $_GET['start_date'] ?? '';
    $end_date   = $_GET['end_date'] ?? '';

    // Check if table exists
    $check = db_or_500(
        mysqli_query($db, "SHOW TABLES LIKE 'emailsub'"),
        $db,
        'check emailsub table'
    );

    if (mysqli_num_rows($check) === 0) {
        json_out([
            'draw' => $draw,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }

    // Total records
    $totalRes = db_or_500(
        mysqli_query($db, "SELECT COUNT(*) AS c FROM emailsub"),
        $db,
        'emailsub total'
    );
    $recordsTotal = (int)mysqli_fetch_assoc($totalRes)['c'];

    // Build filter
    $filters = [];
    if ($search !== '') {
        $esc = mysqli_real_escape_string($db, $search);
        $filters[] = "(emailsub_email LIKE '%$esc%' OR emailsub_country LIKE '%$esc%')";
    }
    if ($start_date !== '' && $end_date !== '') {
        $sd = mysqli_real_escape_string($db, $start_date);
        $ed = mysqli_real_escape_string($db, $end_date);
        $filters[] = "(DATE(emailsub_date) BETWEEN '$sd' AND '$ed')";
    }

    $where = $filters ? ('WHERE ' . implode(' AND ', $filters)) : '';

    // Filtered count
    $cntRes = db_or_500(
        mysqli_query($db, "SELECT COUNT(*) AS c FROM emailsub $where"),
        $db,
        'emailsub filtered count'
    );
    $recordsFiltered = (int)mysqli_fetch_assoc($cntRes)['c'];

    // Get data
    $sql = "SELECT emailsub_id, emailsub_email, emailsub_country, emailsub_consent, emailsub_date
            FROM emailsub
            $where
            ORDER BY emailsub_id DESC
            LIMIT $start, $length";
    $res = db_or_500(mysqli_query($db, $sql), $db, 'emailsub data');

    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $rows[] = [
            'checkbox' => '<input type="checkbox" name="delete_ids[]" value="' . (int)$r['emailsub_id'] . '">',
            'id'       => (int)$r['emailsub_id'],
            'email'    => $r['emailsub_email'],
            'country'  => $r['emailsub_country'] ?? '',
            'consent'  => ((int)$r['emailsub_consent'] === 1 ? 'Yes' : 'No'),
            'date'     => substr($r['emailsub_date'], 0, 19),
        ];
    }

    json_out([
        'draw'            => $draw,
        'recordsTotal'    => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data'            => $rows,
    ]);
}

if ($tableName === 'mailqueue') {
    require_admin();

    $draw   = (int)($_GET['draw'] ?? 1);
    $start  = (int)($_GET['start'] ?? 0);
    $length = (int)($_GET['length'] ?? 10);
    $search = trim($_GET['search']['value'] ?? '');

    $orderColumnIndex = (int)($_GET['order'][0]['column'] ?? 0);
    $columns = ['id', 'sendto', 'sendtitle', 'init_time', 'send_time', 'sendstatus'];
    $orderColumn = $columns[$orderColumnIndex] ?? 'id';
    $orderDir = (($_GET['order'][0]['dir'] ?? 'desc') === 'asc') ? 'asc' : 'desc';

    // Total records
    $totalRes = db_or_500(
        mysqli_query($db, "SELECT COUNT(*) AS c FROM mailqueue"),
        $db,
        'mailqueue total'
    );
    $recordsTotal = (int)mysqli_fetch_assoc($totalRes)['c'];

    // Build filter
    $where = '';
    if ($search !== '') {
        $esc = mysqli_real_escape_string($db, $search);
        $where = "WHERE (sendto LIKE '%$esc%' OR sendtitle LIKE '%$esc%')";
    }

    // Filtered count
    $cntRes = db_or_500(
        mysqli_query($db, "SELECT COUNT(*) AS c FROM mailqueue $where"),
        $db,
        'mailqueue filtered count'
    );
    $recordsFiltered = (int)mysqli_fetch_assoc($cntRes)['c'];

    // Get data
    $sql = "SELECT id, sendto, sendtitle, init_time, send_time, sendstatus
            FROM mailqueue
            $where
            ORDER BY $orderColumn $orderDir
            LIMIT $start, $length";
    $res = db_or_500(mysqli_query($db, $sql), $db, 'mailqueue data');

    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $rows[] = [
            'id'         => (int)$r['id'],
            'sendto'     => $r['sendto'],
            'sendtitle'  => $r['sendtitle'],
            'init_time'  => $r['init_time'],
            'send_time'  => $r['send_time'],
            'sendstatus' => $r['sendstatus'],
        ];
    }

    json_out([
        'draw'            => $draw,
        'recordsTotal'    => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data'            => $rows,
    ]);
}

if ($action === 'templates.setactive') {
    require_admin();

    $slug = trim($_POST['slug'] ?? '');
    
    if ($slug === '') {
        json_out(['ok' => false, 'error' => 'Missing template slug'], 422);
    }

    // Check if template exists
    $tpl = get_template_by_slug($db, $slug);
    if (!$tpl) {
        json_out(['ok' => false, 'error' => 'Template not found'], 404);
    }

    // First, deactivate all templates
    $stmt1 = mysqli_prepare($db, "UPDATE email_templates SET is_active_subscribe = 0");
    if (!$stmt1) {
        db_or_500(false, $db, 'templates.setactive deactivate all');
    }
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // Then activate the selected template
    $stmt2 = mysqli_prepare($db, "UPDATE email_templates SET is_active_subscribe = 1 WHERE slug = ?");
    if (!$stmt2) {
        db_or_500(false, $db, 'templates.setactive activate');
    }
    mysqli_stmt_bind_param($stmt2, 's', $slug);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    json_out(['ok' => true, 'message' => 'Template set as active for new subscriptions']);
}
// No action matched - exit silently
exit;
