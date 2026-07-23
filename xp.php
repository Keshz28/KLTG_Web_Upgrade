<?php
/* ============================================================================
 *                                  xp.php
 *
 *   HIDDEN DEV PANEL (System Console) for KL The Guide.
 *
 *   A standalone operator console that lives OUTSIDE the normal admin/login.php
 *   CMS. It has its own URL (/xp.php), its own auth (a single master key, not a
 *   user login), its own API, and its own dark theme.
 *
 *   Security model in one line: anyone who knows the URL still sees nothing
 *   unless they also hold the DEV_MASTER_KEY. Without a valid key, every API
 *   call returns 404 — the panel is invisible, not just locked.
 *
 *   The key is read from .env as DEV_MASTER_KEY (via envv()). If it is empty the
 *   whole panel is inaccessible (every API call 404s).
 *
 *   Headline feature for this site: "Ads" tab hides ALL advertisements for a
 *   specific visitor IP (everyone else still sees ads). See header.php +
 *   kltg_ads_hidden() in admin/functions.php.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md; longer write-up
 *   in DEVPANEL.md.
 * ============================================================================ */

require __DIR__ . '/admin/functions.php'; // gives $db, envv(), session, helpers

/* -------------------------------------------------------------------------- */
/*  Auth / response helpers                                                    */
/* -------------------------------------------------------------------------- */

function dp_master_key(): string
{
    return (string) envv('DEV_MASTER_KEY', '');
}

/** Constant-time compare of the supplied key against the configured key.
 *  Accepts the key from the X-Dev-K header OR, as a fallback, the `dp_k` POST
 *  field — some shared/free hosts strip non-standard request headers. */
function dp_key_valid(): bool
{
    $key = dp_master_key();
    if ($key === '') {
        return false; // not configured -> panel completely inaccessible
    }
    $given = $_SERVER['HTTP_X_DEV_K'] ?? '';
    if (!is_string($given) || $given === '') {
        $given = $_POST['dp_k'] ?? '';
    }
    return is_string($given) && $given !== '' && hash_equals($key, $given);
}

/** Emit a plausible Apache 404 and stop. Used for every unauthorised access. */
function dp_404(): void
{
    http_response_code(404);
    header('Content-Type: text/html; charset=iso-8859-1');
    echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n"
       . "<html><head><title>404 Not Found</title></head><body>\n"
       . "<h1>Not Found</h1>\n"
       . "<p>The requested URL was not found on this server.</p>\n"
       . "</body></html>";
    exit;
}

function dp_json($data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data);
    exit;
}

/* ----- brute-force throttle on invalid-key attempts (per IP) -------------- */
function dp_throttle_file(): string
{
    return sys_get_temp_dir() . '/kltg_dp_' . md5($_SERVER['REMOTE_ADDR'] ?? '0') . '.json';
}
function dp_throttle_check(): void
{
    $f = dp_throttle_file();
    if (!is_file($f)) return;
    $d = json_decode(@file_get_contents($f), true);
    if (!$d) return;
    if (time() >= ($d['reset_at'] ?? 0)) { @unlink($f); return; }
    if (($d['fails'] ?? 0) >= 10) { dp_404(); } // looks identical to "no route"
}
function dp_throttle_fail(): void
{
    $f = dp_throttle_file();
    $d = ['fails' => 1, 'reset_at' => time() + 600];
    if (is_file($f)) {
        $p = json_decode(@file_get_contents($f), true);
        if ($p && time() < ($p['reset_at'] ?? 0)) {
            $d = ['fails' => ($p['fails'] ?? 0) + 1, 'reset_at' => $p['reset_at']];
        }
    }
    @file_put_contents($f, json_encode($d), LOCK_EX);
}

/* -------------------------------------------------------------------------- */
/*  API dispatch  (POST dp_action=...; auth via X-Dev-K header)               */
/* -------------------------------------------------------------------------- */

$dpAction = $_POST['dp_action'] ?? '';

if ($dpAction !== '') {
    dp_throttle_check();
    if (!dp_key_valid()) {
        dp_throttle_fail();
        dp_404();
    }

    if ($dpAction !== 'ping') {
        $dpDetail = $_POST['username'] ?? $_POST['table'] ?? $_POST['ip']
            ?? (isset($_POST['id']) ? ('id=' . $_POST['id']) : null);
        dp_audit_log($dpAction, $dpDetail !== null ? (string) $dpDetail : null);
    }

    switch ($dpAction) {

        /* lightweight handshake used by the lock screen */
        case 'ping':
            dp_json(['ok' => true]);
            break;

        /* ---- Overview ---------------------------------------------------- */
        case 'info': {
            global $db, $db_name, $db_host;
            $root = realpath(__DIR__) ?: __DIR__;
            $free = @disk_free_space($root);
            $total = @disk_total_space($root);
            $srvVer = '';
            if ($res = @mysqli_query($db, 'SELECT VERSION() AS v')) {
                $srvVer = (mysqli_fetch_assoc($res)['v'] ?? '');
            }
            dp_json([
                'ok' => true,
                'php_version'  => PHP_VERSION,
                'server'       => $_SERVER['SERVER_SOFTWARE'] ?? 'n/a',
                'host'         => $_SERVER['HTTP_HOST'] ?? 'n/a',
                'doc_root'     => $root,
                'server_time'  => date('Y-m-d H:i:s'),
                'db_status'    => $db ? 'connected' : 'down',
                'db_name'      => $db_name ?? 'n/a',
                'db_host'      => $db_host ?? 'n/a',
                'db_version'   => $srvVer,
                'your_ip'      => kltg_client_ip(),
                'ads_hidden_for_you' => kltg_ads_hidden($db),
                'disk_free'    => $free ? round($free / 1073741824, 2) : null,
                'disk_total'   => $total ? round($total / 1073741824, 2) : null,
                'security_flags' => dp_security_flags($db),
            ]);
            break;
        }

        /* ---- Database inspector ------------------------------------------ */
        case 'db_tables': {
            global $db;
            $tables = [];
            if ($res = @mysqli_query($db, 'SHOW TABLES')) {
                while ($row = mysqli_fetch_row($res)) {
                    $t = $row[0];
                    $count = null;
                    if ($c = @mysqli_query($db, 'SELECT COUNT(*) AS n FROM `' . $t . '`')) {
                        $count = (int) (mysqli_fetch_assoc($c)['n'] ?? 0);
                    }
                    $tables[] = ['name' => $t, 'rows' => $count];
                }
            }
            usort($tables, fn($a, $b) => ($b['rows'] ?? 0) <=> ($a['rows'] ?? 0));
            dp_json(['ok' => true, 'tables' => $tables]);
            break;
        }

        /* ---- Ads: hide advertisements for specific IPs ------------------- */
        case 'ads_list': {
            global $db;
            dp_ensure_ads_table($db);
            $rows = [];
            if ($res = @mysqli_query($db, 'SELECT id, ip_address, note, created_at FROM devpanel_ad_block ORDER BY created_at DESC')) {
                while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            }
            dp_json(['ok' => true, 'ips' => $rows, 'your_ip' => kltg_client_ip()]);
            break;
        }

        case 'ads_add': {
            global $db;
            dp_ensure_ads_table($db);
            $ip   = trim($_POST['ip'] ?? '');
            $note = trim($_POST['note'] ?? '');
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                dp_json(['ok' => false, 'error' => 'Not a valid IPv4/IPv6 address.'], 422);
            }
            if (mb_strlen($note) > 255) $note = mb_substr($note, 0, 255);
            $stmt = mysqli_prepare(
                $db,
                'INSERT INTO devpanel_ad_block (ip_address, note) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE note = VALUES(note)'
            );
            mysqli_stmt_bind_param($stmt, 'ss', $ip, $note);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok, 'error' => $ok ? null : mysqli_error($db)]);
            break;
        }

        case 'ads_delete': {
            global $db;
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = mysqli_prepare($db, 'DELETE FROM devpanel_ad_block WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        /* ---- Admin users (the login.php CMS accounts) -------------------- */
        case 'users_list': {
            global $db;
            $rows = [];
            if ($res = @mysqli_query($db, 'SELECT username, email, password FROM users ORDER BY username ASC')) {
                while ($r = mysqli_fetch_assoc($res)) {
                    // The stored value is a one-way hash — it is never sent to the
                    // browser. Only report which scheme it uses so the UI can show
                    // whether the account is on modern bcrypt or a legacy MD5 hash.
                    $rows[] = [
                        'username' => $r['username'],
                        'email'    => $r['email'],
                        'hash'     => dp_hash_scheme((string) $r['password']),
                    ];
                }
            }
            dp_json(['ok' => true, 'users' => $rows]);
            break;
        }

        case 'users_create': {
            global $db;
            $u = trim($_POST['username'] ?? '');
            $e = trim($_POST['email'] ?? '');
            $p = (string) ($_POST['password'] ?? '');
            if ($u === '' || $p === '') {
                dp_json(['ok' => false, 'error' => 'Username and password are required.'], 422);
            }
            $chk = mysqli_prepare($db, 'SELECT 1 FROM users WHERE username = ? LIMIT 1');
            mysqli_stmt_bind_param($chk, 's', $u);
            mysqli_stmt_execute($chk);
            mysqli_stmt_store_result($chk);
            if (mysqli_stmt_num_rows($chk) > 0) {
                mysqli_stmt_close($chk);
                dp_json(['ok' => false, 'error' => 'That username already exists.'], 409);
            }
            mysqli_stmt_close($chk);
            $hash = password_hash($p, PASSWORD_DEFAULT); // functions.php verifies bcrypt first, MD5 as legacy fallback
            $stmt = mysqli_prepare($db, 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sss', $u, $e, $hash);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok, 'error' => $ok ? null : mysqli_error($db)]);
            break;
        }

        /* Change an admin password. The current password must be supplied and
         * is checked against the stored hash (same bcrypt-then-legacy-MD5 order
         * as the login handler in functions.php) — the panel can never show the
         * existing password because only its one-way hash is stored.
         *
         * `force=1` skips that check: the break-glass path for an account whose
         * password nobody remembers. Audited as a distinct action either way. */
        case 'users_password': {
            global $db;
            $u     = trim($_POST['username'] ?? '');
            $cur   = (string) ($_POST['current'] ?? '');
            $p     = (string) ($_POST['password'] ?? '');
            $force = ($_POST['force'] ?? '') === '1';
            if ($u === '' || $p === '') {
                dp_json(['ok' => false, 'error' => 'Username and new password are required.'], 422);
            }
            if (strlen($p) < 8) {
                dp_json(['ok' => false, 'error' => 'New password must be at least 8 characters.'], 422);
            }

            $sel = mysqli_prepare($db, 'SELECT password FROM users WHERE username = ? LIMIT 1');
            mysqli_stmt_bind_param($sel, 's', $u);
            mysqli_stmt_execute($sel);
            $stored = null;
            mysqli_stmt_bind_result($sel, $stored);
            $found = mysqli_stmt_fetch($sel);
            mysqli_stmt_close($sel);
            if (!$found) {
                dp_json(['ok' => false, 'error' => 'No such admin user.'], 404);
            }

            if (!$force) {
                if ($cur === '') {
                    dp_json(['ok' => false, 'error' => 'Current password is required.'], 422);
                }
                if (!password_verify($cur, (string) $stored) && !hash_equals((string) $stored, md5($cur))) {
                    dp_audit_log('users_password_denied', $u);
                    dp_json(['ok' => false, 'error' => 'Current password is incorrect.'], 403);
                }
                if (password_verify($p, (string) $stored) || hash_equals((string) $stored, md5($p))) {
                    dp_json(['ok' => false, 'error' => 'New password must differ from the current one.'], 422);
                }
            } else {
                dp_audit_log('users_password_forced', $u);
            }

            $hash = password_hash($p, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($db, 'UPDATE users SET password = ? WHERE username = ?');
            mysqli_stmt_bind_param($stmt, 'ss', $hash, $u);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok, 'error' => $ok ? null : mysqli_error($db)]);
            break;
        }

        /* Check a password against an account's stored hash without changing
         * anything — lets you confirm "is *this* still the password?" which is
         * the closest safe equivalent to reading it back. */
        case 'users_password_check': {
            global $db;
            $u = trim($_POST['username'] ?? '');
            $p = (string) ($_POST['password'] ?? '');
            if ($u === '' || $p === '') {
                dp_json(['ok' => false, 'error' => 'Username and password are required.'], 422);
            }
            $sel = mysqli_prepare($db, 'SELECT password FROM users WHERE username = ? LIMIT 1');
            mysqli_stmt_bind_param($sel, 's', $u);
            mysqli_stmt_execute($sel);
            $stored = null;
            mysqli_stmt_bind_result($sel, $stored);
            $found = mysqli_stmt_fetch($sel);
            mysqli_stmt_close($sel);
            if (!$found) {
                dp_json(['ok' => false, 'error' => 'No such admin user.'], 404);
            }
            $match = password_verify($p, (string) $stored) || hash_equals((string) $stored, md5($p));
            dp_json(['ok' => true, 'match' => $match]);
            break;
        }

        case 'users_delete': {
            global $db;
            $u = trim($_POST['username'] ?? '');
            // Refuse to delete the last remaining admin so you can't lock yourself out.
            $n = 0;
            if ($res = @mysqli_query($db, 'SELECT COUNT(*) AS n FROM users')) {
                $n = (int) (mysqli_fetch_assoc($res)['n'] ?? 0);
            }
            if ($n <= 1) {
                dp_json(['ok' => false, 'error' => 'Refusing to delete the last admin account.'], 409);
            }
            $stmt = mysqli_prepare($db, 'DELETE FROM users WHERE username = ?');
            mysqli_stmt_bind_param($stmt, 's', $u);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        /* Plant a live admin/login.php session for this account, bypassing
         * the password entirely — mirrors the normal login flow in
         * functions.php's login_user handler (session_regenerate_id +
         * the same $_SESSION keys it sets) so admin/*.php sees a real
         * login. Works regardless of what the account's password is or
         * how many times it has been changed. */
        case 'users_impersonate': {
            global $db;
            $u = trim($_POST['username'] ?? '');
            $chk = mysqli_prepare($db, 'SELECT 1 FROM users WHERE username = ? LIMIT 1');
            mysqli_stmt_bind_param($chk, 's', $u);
            mysqli_stmt_execute($chk);
            mysqli_stmt_store_result($chk);
            $exists = mysqli_stmt_num_rows($chk) > 0;
            mysqli_stmt_close($chk);
            if (!$exists) {
                dp_json(['ok' => false, 'error' => 'No such admin user.'], 404);
            }
            session_regenerate_id(true);
            $_SESSION['username'] = $u;
            $_SESSION['success'] = 'You are now logged in';
            $_SESSION['last_activity'] = time();
            dp_json(['ok' => true]);
            break;
        }

        /* ---- Generic content / table row editor ------------------------- */
        case 'table_meta': {
            global $db;
            $t = $_POST['table'] ?? '';
            if (!dp_valid_table($db, $t)) dp_404();
            $cols = [];
            foreach (dp_columns($db, $t) as $name => $c) {
                $cols[] = [
                    'name'  => $name,
                    'type'  => $c['Type'],
                    'null'  => $c['Null'],
                    'key'   => $c['Key'],
                    'extra' => $c['Extra'],
                ];
            }
            dp_json(['ok' => true, 'columns' => $cols, 'pk' => dp_pk($db, $t)]);
            break;
        }

        case 'table_rows': {
            global $db;
            $t = $_POST['table'] ?? '';
            if (!dp_valid_table($db, $t)) dp_404();
            $page = max(1, (int) ($_POST['page'] ?? 1));
            $per  = 50;
            $off  = ($page - 1) * $per;
            $total = 0;
            if ($c = @mysqli_query($db, 'SELECT COUNT(*) AS n FROM ' . dp_qid($t))) {
                $total = (int) (mysqli_fetch_assoc($c)['n'] ?? 0);
            }
            $pk = dp_pk($db, $t);
            $order = $pk ? (' ORDER BY ' . dp_qid($pk) . ' DESC') : '';
            $rows = [];
            if ($r = @mysqli_query($db, 'SELECT * FROM ' . dp_qid($t) . $order . " LIMIT $per OFFSET $off")) {
                while ($row = mysqli_fetch_assoc($r)) $rows[] = $row;
            }
            dp_json(['ok' => true, 'rows' => $rows, 'total' => $total, 'page' => $page, 'per' => $per, 'pk' => $pk]);
            break;
        }

        case 'row_insert': {
            global $db;
            $t = $_POST['table'] ?? '';
            if (!dp_valid_table($db, $t)) dp_404();
            $data = json_decode($_POST['data'] ?? '[]', true);
            if (!is_array($data)) dp_json(['ok' => false, 'error' => 'Bad payload'], 422);
            $cols = dp_columns($db, $t);
            $names = []; $place = []; $vals = []; $types = '';
            foreach ($data as $k => $v) {
                if (!isset($cols[$k])) continue;
                // let auto-increment / empty PK be assigned by MySQL
                if (strpos($cols[$k]['Extra'], 'auto_increment') !== false && ($v === '' || $v === null)) continue;
                $names[] = dp_qid($k); $place[] = '?'; $vals[] = $v; $types .= 's';
            }
            if (!$names) dp_json(['ok' => false, 'error' => 'No valid columns'], 422);
            $sql = 'INSERT INTO ' . dp_qid($t) . ' (' . implode(',', $names) . ') VALUES (' . implode(',', $place) . ')';
            $stmt = mysqli_prepare($db, $sql);
            if (!$stmt) dp_json(['ok' => false, 'error' => mysqli_error($db)], 500);
            mysqli_stmt_bind_param($stmt, $types, ...$vals);
            $ok = mysqli_stmt_execute($stmt);
            $err = $ok ? null : mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok, 'error' => $err]);
            break;
        }

        case 'row_update': {
            global $db;
            $t = $_POST['table'] ?? '';
            if (!dp_valid_table($db, $t)) dp_404();
            $pk = dp_pk($db, $t);
            if (!$pk) dp_json(['ok' => false, 'error' => 'This table has no single-column primary key, so rows cannot be edited safely.'], 422);
            $pkval = $_POST['pk_val'] ?? '';
            $data = json_decode($_POST['data'] ?? '[]', true);
            if (!is_array($data)) dp_json(['ok' => false, 'error' => 'Bad payload'], 422);
            $cols = dp_columns($db, $t);
            $set = []; $vals = []; $types = '';
            foreach ($data as $k => $v) {
                if (!isset($cols[$k]) || $k === $pk) continue;
                $set[] = dp_qid($k) . ' = ?'; $vals[] = $v; $types .= 's';
            }
            if (!$set) dp_json(['ok' => false, 'error' => 'No editable columns'], 422);
            $vals[] = $pkval; $types .= 's';
            $sql = 'UPDATE ' . dp_qid($t) . ' SET ' . implode(', ', $set) . ' WHERE ' . dp_qid($pk) . ' = ? LIMIT 1';
            $stmt = mysqli_prepare($db, $sql);
            if (!$stmt) dp_json(['ok' => false, 'error' => mysqli_error($db)], 500);
            mysqli_stmt_bind_param($stmt, $types, ...$vals);
            $ok = mysqli_stmt_execute($stmt);
            $err = $ok ? null : mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok, 'error' => $err]);
            break;
        }

        case 'row_delete': {
            global $db;
            $t = $_POST['table'] ?? '';
            if (!dp_valid_table($db, $t)) dp_404();
            $pk = dp_pk($db, $t);
            if (!$pk) dp_json(['ok' => false, 'error' => 'This table has no single-column primary key, so rows cannot be deleted safely.'], 422);
            $pkval = $_POST['pk_val'] ?? '';
            $stmt = mysqli_prepare($db, 'DELETE FROM ' . dp_qid($t) . ' WHERE ' . dp_qid($pk) . ' = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $pkval);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        /* ---- House ads (image banners, homepage) ------------------------ */
        case 'houseads_list': {
            global $db;
            dp_ensure_houseads_table($db);
            $rows = [];
            if ($r = @mysqli_query($db, 'SELECT id, image, link_url, note, active, created_at FROM devpanel_house_ad ORDER BY id DESC')) {
                while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
            }
            dp_json(['ok' => true, 'ads' => $rows, 'base' => 'assets/img/houseads/']);
            break;
        }

        case 'housead_add': {
            global $db;
            dp_ensure_houseads_table($db);
            $link = trim($_POST['link'] ?? '');
            $note = trim($_POST['note'] ?? '');
            if (empty($_FILES['image']) || ($_FILES['image']['error'] ?? 1) !== UPLOAD_ERR_OK) {
                dp_json(['ok' => false, 'error' => 'Please choose an image file.'], 422);
            }
            $f = $_FILES['image'];
            if (!@getimagesize($f['tmp_name'])) {
                dp_json(['ok' => false, 'error' => 'That file is not a valid image.'], 422);
            }
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                dp_json(['ok' => false, 'error' => 'Allowed types: jpg, png, webp, gif.'], 422);
            }
            if ($f['size'] > 3 * 1024 * 1024) {
                dp_json(['ok' => false, 'error' => 'Image must be 3 MB or smaller.'], 422);
            }
            if ($link !== '' && !preg_match('#^https?://#i', $link)) $link = 'https://' . $link;
            $dir = __DIR__ . '/assets/img/houseads/';
            if (!is_dir($dir)) @mkdir($dir, 0775, true);
            $name = bin2hex(random_bytes(8)) . '.' . $ext;
            if (!move_uploaded_file($f['tmp_name'], $dir . $name)) {
                dp_json(['ok' => false, 'error' => 'Could not save the uploaded image.'], 500);
            }
            $stmt = mysqli_prepare($db, 'INSERT INTO devpanel_house_ad (image, link_url, note) VALUES (?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sss', $name, $link, $note);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        case 'housead_toggle': {
            global $db;
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = mysqli_prepare($db, 'UPDATE devpanel_house_ad SET active = 1 - active WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        case 'housead_delete': {
            global $db;
            $id = (int) ($_POST['id'] ?? 0);
            $img = null;
            $s = mysqli_prepare($db, 'SELECT image FROM devpanel_house_ad WHERE id = ?');
            mysqli_stmt_bind_param($s, 'i', $id);
            mysqli_stmt_execute($s);
            mysqli_stmt_bind_result($s, $img);
            mysqli_stmt_fetch($s);
            mysqli_stmt_close($s);
            $stmt = mysqli_prepare($db, 'DELETE FROM devpanel_house_ad WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            if ($ok && $img) @unlink(__DIR__ . '/assets/img/houseads/' . basename($img));
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        /* ---- Mail queue (admin/cron2-4.php drain this via functions.php) - */
        case 'queue_stats': {
            global $db;
            $stats = ['pending' => 0, 'sent' => 0, 'other' => 0, 'total' => 0];
            if ($res = @mysqli_query($db, "SELECT sendstatus, COUNT(*) AS n FROM mailqueue GROUP BY sendstatus")) {
                while ($r = mysqli_fetch_assoc($res)) {
                    $n = (int) $r['n'];
                    $stats['total'] += $n;
                    if ($r['sendstatus'] === '0' || $r['sendstatus'] === null || $r['sendstatus'] === '') $stats['pending'] += $n;
                    elseif ($r['sendstatus'] === '1') $stats['sent'] += $n;
                    else $stats['other'] += $n;
                }
            }
            dp_json(['ok' => true, 'stats' => $stats]);
            break;
        }

        case 'queue_list': {
            global $db;
            $rows = [];
            if ($res = @mysqli_query($db, "SELECT id, sendstatus, sendto, sendtitle, init_time, send_time FROM mailqueue ORDER BY init_time DESC LIMIT 50")) {
                while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            }
            dp_json(['ok' => true, 'rows' => $rows]);
            break;
        }

        /* Manual drain — ignores the automatic 9am-7pm send window in
         * functions.php's testqueue handler; this is an explicit operator action. */
        case 'queue_run': {
            global $db;
            $limit = 50;
            $sent = 0; $failed = 0;
            if ($res = @mysqli_query($db, "SELECT id, sendto, sendtitle, sendbody FROM mailqueue WHERE sendstatus='0' OR sendstatus IS NULL OR sendstatus='' LIMIT $limit")) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = (int) $row['id'];
                    $body = is_file($row['sendbody']) ? file_get_contents($row['sendbody']) : $row['sendbody'];
                    $ok = send_email_html($row['sendto'], $row['sendtitle'], $body, 'KL The Guide');
                    $ok ? $sent++ : $failed++;
                    $status = $ok ? '1' : '0';
                    $upd = mysqli_prepare($db, "UPDATE mailqueue SET sendstatus = ?, send_time = ? WHERE id = ?");
                    $now = date('Y-m-d H:i:s');
                    mysqli_stmt_bind_param($upd, 'ssi', $status, $now, $id);
                    mysqli_stmt_execute($upd);
                    mysqli_stmt_close($upd);
                }
            }
            dp_json(['ok' => true, 'sent' => $sent, 'failed' => $failed]);
            break;
        }

        case 'queue_clear': {
            global $db;
            $ok = @mysqli_query($db, "DELETE FROM mailqueue WHERE sendstatus='1'");
            dp_json(['ok' => (bool) $ok]);
            break;
        }

        /* ---- Error log tail ------------------------------------------------ */
        case 'logs_tail': {
            $path = ini_get('error_log');
            if (!$path || !is_file($path) || !is_readable($path)) {
                dp_json(['ok' => true, 'path' => $path ?: null, 'available' => false, 'lines' => []]);
            }
            $lines = dp_tail_file($path, 200);
            dp_json(['ok' => true, 'path' => $path, 'available' => true, 'size' => filesize($path), 'lines' => $lines]);
            break;
        }

        /* ---- Audit trail ---------------------------------------------------- */
        case 'audit_list': {
            global $db;
            dp_ensure_audit_table($db);
            $rows = [];
            if ($res = @mysqli_query($db, 'SELECT action, detail, ip_address, created_at FROM devpanel_audit ORDER BY id DESC LIMIT 200')) {
                while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            }
            dp_json(['ok' => true, 'rows' => $rows]);
            break;
        }

        default:
            dp_404();
    }
    exit;
}

/* ----- generic table-editor helpers --------------------------------------- */

/** Quote an identifier (table/column) safely. */
function dp_qid(string $id): string
{
    return '`' . str_replace('`', '``', $id) . '`';
}

/** Whitelist of real table names (cached). */
function dp_tables(mysqli $db): array
{
    static $t = null;
    if ($t !== null) return $t;
    $t = [];
    if ($r = @mysqli_query($db, 'SHOW TABLES')) {
        while ($row = mysqli_fetch_row($r)) $t[] = $row[0];
    }
    return $t;
}

function dp_valid_table(mysqli $db, string $t): bool
{
    return $t !== '' && in_array($t, dp_tables($db), true);
}

/** Column metadata keyed by column name. */
function dp_columns(mysqli $db, string $t): array
{
    $cols = [];
    if ($r = @mysqli_query($db, 'SHOW COLUMNS FROM ' . dp_qid($t))) {
        while ($c = mysqli_fetch_assoc($r)) $cols[$c['Field']] = $c;
    }
    return $cols;
}

/** Single-column primary key name, or null (composite/none -> view only). */
function dp_pk(mysqli $db, string $t): ?string
{
    $pk = [];
    if ($r = @mysqli_query($db, 'SHOW KEYS FROM ' . dp_qid($t) . " WHERE Key_name = 'PRIMARY'")) {
        while ($k = mysqli_fetch_assoc($r)) $pk[] = $k['Column_name'];
    }
    return count($pk) === 1 ? $pk[0] : null;
}

/** Create the house-ads table on demand. */
function dp_ensure_houseads_table(mysqli $db): void
{
    @mysqli_query($db, "CREATE TABLE IF NOT EXISTS `devpanel_house_ad` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `image` VARCHAR(255) NOT NULL,
        `link_url` VARCHAR(500) NULL,
        `note` VARCHAR(255) NULL,
        `active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

/** Create the ad-block table on demand so no manual migration step is needed. */
function dp_ensure_ads_table(mysqli $db): void
{
    @mysqli_query($db, "CREATE TABLE IF NOT EXISTS `devpanel_ad_block` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `ip_address` VARCHAR(45) NOT NULL,
        `note` VARCHAR(255) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uq_ip` (`ip_address`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

/** Create the audit-log table on demand (and migrate in the `detail` column
 *  for installs that auto-created this table before that column existed). */
function dp_ensure_audit_table(mysqli $db): void
{
    @mysqli_query($db, "CREATE TABLE IF NOT EXISTS `devpanel_audit` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `action` VARCHAR(64) NOT NULL,
        `detail` VARCHAR(255) NULL,
        `ip_address` VARCHAR(45) NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $has = false;
    if ($r = @mysqli_query($db, "SHOW COLUMNS FROM `devpanel_audit` LIKE 'detail'")) {
        $has = mysqli_num_rows($r) > 0;
    }
    if (!$has) {
        @mysqli_query($db, "ALTER TABLE `devpanel_audit` ADD COLUMN `detail` VARCHAR(255) NULL AFTER `action`");
    }
}

/** Name the hashing scheme behind a stored password, for display only. */
function dp_hash_scheme(string $stored): string
{
    if (preg_match('/^\$2[aby]\$/', $stored))  return 'bcrypt';
    if (strncmp($stored, '$argon2', 7) === 0)  return 'argon2';
    if (preg_match('/^[a-f0-9]{32}$/i', $stored)) return 'md5 (legacy)';
    return 'unknown';
}

/** Record who-did-what. Best-effort — never blocks the action it logs. */
function dp_audit_log(string $action, ?string $detail = null): void
{
    global $db;
    if (!$db) return;
    dp_ensure_audit_table($db);
    $ip = kltg_client_ip();
    $stmt = @mysqli_prepare($db, 'INSERT INTO devpanel_audit (action, detail, ip_address) VALUES (?, ?, ?)');
    if (!$stmt) return;
    mysqli_stmt_bind_param($stmt, 'sss', $action, $detail, $ip);
    @mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/** Live checks for the known pre-launch security hazards, so the Overview
 *  banner never goes stale — it reflects whatever is actually on disk now. */
function dp_security_flags(?mysqli $db = null): array
{
    $root = __DIR__;
    $checks = [
        ['file' => 'info.php',            'label' => 'info.php exposes phpinfo() publicly'],
        ['file' => 'admin/vapid.php',     'label' => 'admin/vapid.php prints the web-push private key'],
        ['file' => 'admin/test.php',      'label' => 'admin/test.php ungated scratch page'],
        ['file' => 'admin/contest.php',   'label' => 'admin/contest.php ungated scratch page'],
        ['file' => 'admin/test2',         'label' => 'admin/test2/ ungated scratch folder'],
    ];
    $flags = [];
    foreach ($checks as $c) {
        if (file_exists($root . '/' . $c['file'])) $flags[] = $c['label'];
    }

    // fetch_blogger.php: only flag while the API key is still hard-coded in the file
    // (moving it to .env clears this automatically).
    $fb = $root . '/fetch_blogger.php';
    if (is_file($fb) && strpos((string) @file_get_contents($fb), 'AIzaSy') !== false) {
        $flags[] = 'fetch_blogger.php has a hard-coded Blogger API key';
    }

    // Passwords: only flag while legacy MD5 hashes remain — functions.php upgrades
    // each account to bcrypt automatically the next time it logs in.
    if ($db) {
        $legacy = 0;
        if ($r = @mysqli_query($db, 'SELECT password FROM users')) {
            while ($row = mysqli_fetch_assoc($r)) {
                if (!preg_match('/^\$2[aby]\$|^\$argon2/', $row['password'])) $legacy++;
            }
        }
        if ($legacy > 0) {
            $flags[] = "$legacy admin account(s) still on legacy MD5 password hashes (auto-upgrades the next time each logs in)";
        }
    }

    return $flags;
}

/** Read the last $n lines of a (potentially large) file without loading it
 *  all into memory — seeks backward from the end in chunks. */
function dp_tail_file(string $path, int $n): array
{
    $fh = @fopen($path, 'r');
    if (!$fh) return [];
    $chunk = 8192;
    $size = filesize($path);
    $pos = $size;
    $data = '';
    $lineCount = 0;
    while ($pos > 0 && $lineCount <= $n) {
        $read = min($chunk, $pos);
        $pos -= $read;
        fseek($fh, $pos);
        $data = fread($fh, $read) . $data;
        $lineCount = substr_count($data, "\n");
    }
    fclose($fh);
    $lines = explode("\n", rtrim($data, "\n"));
    return array_slice($lines, -$n);
}

/* -------------------------------------------------------------------------- */
/*  Otherwise: render the standalone panel shell (generic lock screen).        */
/* -------------------------------------------------------------------------- */
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>System Access</title>
<style>
  :root{
    --bg:#0d1117; --bg2:#161b22; --bg3:#21262d; --line:#30363d;
    --ink:#e6edf3; --muted:#8b949e; --accent:#2f81f7; --accent2:#238636;
    --danger:#f85149; --ok:#3fb950; --warn:#d29922;
  }
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    font-size:14px;line-height:1.5}
  a{color:var(--accent);text-decoration:none}
  button{font-family:inherit;cursor:pointer}
  input,textarea{font-family:inherit}

  /* ---- Lock screen ---- */
  #lock{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;
    background:radial-gradient(circle at 50% 30%,#161b22 0%,#0d1117 70%);z-index:50}
  .lock-card{width:340px;background:var(--bg2);border:1px solid var(--line);
    border-radius:12px;padding:32px 28px;box-shadow:0 16px 50px rgba(0,0,0,.5)}
  .lock-card h1{font-size:18px;margin:0 0 4px;display:flex;align-items:center;gap:8px}
  .lock-card p{color:var(--muted);margin:0 0 20px;font-size:13px}
  .lock-card input{width:100%;height:42px;padding:0 12px;background:var(--bg);
    border:1px solid var(--line);border-radius:8px;color:var(--ink);outline:none}
  .lock-card input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(47,129,247,.25)}
  .lock-card button{width:100%;height:42px;margin-top:14px;border:0;border-radius:8px;
    background:var(--accent2);color:#fff;font-weight:600}
  .lock-card button:hover{background:#2ea043}
  .lock-err{color:var(--danger);font-size:13px;margin-top:12px;min-height:18px}

  /* ---- Shell ---- */
  #shell{display:none;min-height:100vh}
  header.topbar{display:flex;align-items:center;justify-content:space-between;
    padding:12px 20px;background:var(--bg2);border-bottom:1px solid var(--line);
    position:sticky;top:0;z-index:10}
  .topbar .brand{font-weight:600;letter-spacing:.3px;display:flex;align-items:center;gap:8px}
  .dot{width:9px;height:9px;border-radius:50%;background:var(--ok);box-shadow:0 0 8px var(--ok)}
  .topbar button.lockbtn{background:var(--bg3);border:1px solid var(--line);color:var(--ink);
    border-radius:8px;padding:7px 12px;font-size:13px}
  nav.tabs{display:flex;gap:2px;padding:0 14px;background:var(--bg2);
    border-bottom:1px solid var(--line);overflow-x:auto}
  nav.tabs button{background:none;border:0;color:var(--muted);padding:12px 16px;
    font-size:13px;border-bottom:2px solid transparent;white-space:nowrap}
  nav.tabs button.active{color:var(--ink);border-bottom-color:var(--accent)}
  nav.tabs button:hover{color:var(--ink)}
  main{padding:24px;max-width:1100px;margin:0 auto}
  h2.section{font-size:16px;margin:0 0 16px;font-weight:600}

  .card{background:var(--bg2);border:1px solid var(--line);border-radius:10px;
    padding:18px;margin-bottom:18px}
  .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px}
  .kv{background:var(--bg);border:1px solid var(--line);border-radius:8px;padding:12px 14px}
  .kv .k{color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.4px}
  .kv .v{font-size:15px;margin-top:4px;word-break:break-all}

  table{width:100%;border-collapse:collapse}
  th,td{text-align:left;padding:10px 12px;border-bottom:1px solid var(--line);font-size:13px}
  th{color:var(--muted);font-weight:500;text-transform:uppercase;font-size:11px;letter-spacing:.5px}
  tbody tr:hover{background:var(--bg3)}

  .field{margin-bottom:12px}
  .field label{display:block;color:var(--muted);font-size:12px;margin-bottom:6px}
  .field input,.field textarea{width:100%;padding:9px 12px;background:var(--bg);
    border:1px solid var(--line);border-radius:8px;color:var(--ink);outline:none}
  .field input:focus,.field textarea:focus{border-color:var(--accent)}
  .row{display:flex;gap:12px;flex-wrap:wrap}
  .row .field{flex:1;min-width:160px}

  .btn{background:var(--accent2);border:0;color:#fff;border-radius:8px;padding:9px 16px;font-weight:600}
  .btn:hover{background:#2ea043}
  .btn.sm{padding:6px 10px;font-size:12px}
  .btn.danger{background:var(--danger)}
  .btn.danger:hover{background:#da3633}
  .btn.ghost{background:var(--bg3);border:1px solid var(--line);color:var(--ink)}

  .pill{display:inline-block;padding:2px 8px;border-radius:20px;font-size:12px;
    background:var(--bg3);border:1px solid var(--line)}
  .pill.you{background:rgba(210,153,34,.15);border-color:var(--warn);color:var(--warn)}
  .muted{color:var(--muted)}
  .toast{position:fixed;bottom:20px;right:20px;background:var(--bg3);border:1px solid var(--line);
    border-left:3px solid var(--ok);padding:12px 16px;border-radius:8px;opacity:0;
    transform:translateY(10px);transition:.2s;z-index:60;max-width:340px}
  .toast.show{opacity:1;transform:none}
  .toast.err{border-left-color:var(--danger)}
  .hint{background:rgba(47,129,247,.08);border:1px solid rgba(47,129,247,.3);
    border-radius:8px;padding:12px 14px;color:#c9d1d9;font-size:13px;margin-bottom:16px}
  .spin{color:var(--muted);padding:20px;text-align:center}
  code{background:var(--bg);border:1px solid var(--line);border-radius:5px;padding:1px 5px;font-size:12px}

  /* scrollable data grid */
  .tablewrap{overflow:auto;max-width:100%}
  .tablewrap table{min-width:100%}
  .tablewrap td{max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .crumb{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap}
  .crumb .btn.sm{padding:5px 10px}
  .pager{display:flex;gap:8px;align-items:center;margin-top:14px}

  /* modal */
  .modal-bg{display:none;position:fixed;inset:0;background:rgba(1,4,9,.7);z-index:80;
    align-items:flex-start;justify-content:center;padding:40px 16px;overflow:auto}
  .modal-bg.show{display:flex}
  .modal-card{width:640px;max-width:100%;background:var(--bg2);border:1px solid var(--line);
    border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.6)}
  .modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;
    border-bottom:1px solid var(--line);font-weight:600}
  .modal-x{background:none;border:0;color:var(--muted);font-size:22px;line-height:1}
  .modal-body{padding:18px 20px;max-height:62vh;overflow:auto}
  .modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--line)}
  .mf{margin-bottom:14px}
  .mf label{display:block;font-size:12px;color:var(--muted);margin-bottom:5px}
  .mf label .meta{color:#6e7681;font-weight:400}
  .mf input,.mf textarea{width:100%;padding:9px 12px;background:var(--bg);border:1px solid var(--line);
    border-radius:8px;color:var(--ink);outline:none;font-size:13px}
  .mf input:focus,.mf textarea:focus{border-color:var(--accent)}
  .mf input[readonly]{opacity:.6}
  .mf textarea{min-height:90px;resize:vertical;font-family:ui-monospace,Consolas,monospace}
  .adprev img{max-height:60px;border:1px solid var(--line);border-radius:6px;background:#fff}
</style>
</head>
<body>

<!-- ===================== Lock screen ===================== -->
<div id="lock">
  <div class="lock-card">
    <h1>&#128274; System Access</h1>
    <p>Restricted console. Enter the developer key to continue.</p>
    <input id="keyInput" type="password" placeholder="Developer key" autocomplete="off" autofocus>
    <button id="unlockBtn">Unlock</button>
    <div class="lock-err" id="lockErr"></div>
  </div>
</div>

<!-- ===================== Panel shell ===================== -->
<div id="shell">
  <header class="topbar">
    <span class="brand"><span class="dot"></span> System Console</span>
    <button class="lockbtn" onclick="lockSession()">Lock session</button>
  </header>
  <nav class="tabs" id="tabbar">
    <button data-tab="overview" class="active">Overview</button>
    <button data-tab="ads">Ads</button>
    <button data-tab="content">Content</button>
    <button data-tab="queue">Queue</button>
    <button data-tab="logs">Logs</button>
    <button data-tab="users">Users</button>
    <button data-tab="audit">Audit</button>
  </nav>
  <main id="view"><div class="spin">Loading…</div></main>
</div>

<div class="toast" id="toast"></div>

<!-- ===================== Modal (row editor) ===================== -->
<div id="modal" class="modal-bg">
  <div class="modal-card">
    <div class="modal-head">
      <span id="modalTitle">Edit row</span>
      <button class="modal-x" onclick="closeModal()">&times;</button>
    </div>
    <div class="modal-body" id="modalBody"></div>
    <div class="modal-foot">
      <button class="btn ghost" onclick="closeModal()">Cancel</button>
      <button class="btn" id="modalSave">Save</button>
    </div>
  </div>
</div>

<script>
const KEY_STORE = '_dmk';
let DK = sessionStorage.getItem(KEY_STORE) || '';

function toast(msg, isErr){
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = 'toast show' + (isErr ? ' err' : '');
  setTimeout(()=>{ t.className = 'toast' + (isErr?' err':''); }, 2600);
}

/* All API calls go through here. A 404 means "bad/no key" -> bounce to lock. */
async function api(action, params){
  const body = new URLSearchParams(Object.assign({dp_action: action, dp_k: DK}, params || {}));
  const res = await fetch('xp.php', {
    method:'POST',
    headers:{'X-Dev-K': DK, 'Content-Type':'application/x-www-form-urlencoded'},
    body
  });
  if (res.status === 404){ lockSession(); throw new Error('Unauthorized'); }
  let data = {};
  try { data = await res.json(); } catch(e){ data = {ok:false, error:'Bad response'}; }
  return data;
}

function esc(s){ return String(s==null?'':s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

/* ---- Unlock flow ---- */
async function tryUnlock(key){
  DK = key;
  const res = await fetch('xp.php', {
    method:'POST',
    headers:{'X-Dev-K': DK, 'Content-Type':'application/x-www-form-urlencoded'},
    body: new URLSearchParams({dp_action:'ping', dp_k: DK})
  });
  if (res.ok){
    sessionStorage.setItem(KEY_STORE, DK);
    document.getElementById('lock').style.display='none';
    document.getElementById('shell').style.display='block';
    loadTab('overview');
    return true;
  }
  DK=''; sessionStorage.removeItem(KEY_STORE);
  return false;
}

function lockSession(){
  DK=''; sessionStorage.removeItem(KEY_STORE);
  document.getElementById('shell').style.display='none';
  document.getElementById('lock').style.display='flex';
  document.getElementById('keyInput').value='';
  document.getElementById('keyInput').focus();
}

document.getElementById('unlockBtn').onclick = async ()=>{
  const k = document.getElementById('keyInput').value.trim();
  const err = document.getElementById('lockErr');
  err.textContent='';
  if (!k){ err.textContent='Enter a key.'; return; }
  const ok = await tryUnlock(k);
  if (!ok) err.textContent='Invalid key.';
};
document.getElementById('keyInput').addEventListener('keydown', e=>{ if(e.key==='Enter') document.getElementById('unlockBtn').click(); });

/* ---- Tabs ---- */
document.querySelectorAll('#tabbar button').forEach(b=>{
  b.onclick=()=>{
    document.querySelectorAll('#tabbar button').forEach(x=>x.classList.remove('active'));
    b.classList.add('active');
    loadTab(b.dataset.tab);
  };
});

const view = ()=>document.getElementById('view');
function loadTab(tab){
  view().innerHTML='<div class="spin">Loading…</div>';
  ({overview:tabOverview, ads:tabAds, content:tabContent, queue:tabQueue, logs:tabLogs, users:tabUsers, audit:tabAudit}[tab] || tabOverview)();
}

/* Multipart upload helper (for image-banner ads). */
async function apiUpload(action, formData){
  formData.append('dp_action', action);
  formData.append('dp_k', DK);
  const res = await fetch('xp.php', {method:'POST', headers:{'X-Dev-K':DK}, body:formData});
  if (res.status === 404){ lockSession(); throw new Error('Unauthorized'); }
  try { return await res.json(); } catch(e){ return {ok:false, error:'Bad response'}; }
}

/* Modal helpers */
function openModal(title){ document.getElementById('modalTitle').textContent=title; document.getElementById('modal').classList.add('show'); }
function closeModal(){ document.getElementById('modal').classList.remove('show'); }
document.getElementById('modal').addEventListener('click', e=>{ if(e.target.id==='modal') closeModal(); });

/* ---- Overview ---- */
async function tabOverview(){
  const d = await api('info');
  const kv = (k,v)=>`<div class="kv"><div class="k">${k}</div><div class="v">${esc(v)}</div></div>`;
  const adsState = d.ads_hidden_for_you
    ? '<span class="pill you">Hidden for your IP</span>'
    : '<span class="pill">Visible to you</span>';
  const flags = d.security_flags || [];
  const secBanner = flags.length ? `
    <div class="hint" style="background:rgba(248,81,73,.08);border-color:rgba(248,81,73,.35)">
      <b style="color:var(--danger)">Security items still open before go-live (${flags.length}):</b>
      <ul style="margin:8px 0 0;padding-left:18px">
        ${flags.map(f=>`<li>${esc(f)}</li>`).join('')}
      </ul>
    </div>` : `
    <div class="hint" style="background:rgba(63,185,80,.08);border-color:rgba(63,185,80,.35)">
      <b style="color:var(--ok)">No known scratch/diagnostic files detected on disk.</b> (MD5 password hashing is a separate, larger migration — not checked here.)
    </div>`;
  view().innerHTML = `
    <h2 class="section">Environment</h2>
    <div class="grid">
      ${kv('PHP', d.php_version)}
      ${kv('Server', d.server)}
      ${kv('Host', d.host)}
      ${kv('Server time', d.server_time)}
      ${kv('DB status', d.db_status)}
      ${kv('DB name', d.db_name)}
      ${kv('DB host', d.db_host)}
      ${kv('MySQL', d.db_version)}
      ${kv('Disk free (GB)', d.disk_free)}
      ${kv('Disk total (GB)', d.disk_total)}
      <div class="kv"><div class="k">Your IP</div><div class="v">${esc(d.your_ip)} ${adsState}</div></div>
    </div>
    <h2 class="section" style="margin-top:24px">Security</h2>
    ${secBanner}`;
}

/* ---- Ads ---- */
async function tabAds(){
  const d = await api('ads_list');
  const rows = (d.ips||[]).map(r=>`
    <tr>
      <td><code>${esc(r.ip_address)}</code>${r.ip_address===d.your_ip?' <span class="pill you">you</span>':''}</td>
      <td>${esc(r.note||'')}</td>
      <td class="muted">${esc(r.created_at||'')}</td>
      <td><button class="btn sm danger" onclick="adsDelete(${r.id})">Remove</button></td>
    </tr>`).join('');
  view().innerHTML = `
    <h2 class="section">Advertisement control</h2>
    <div class="hint">IPs listed here will <b>not</b> see any advertisements anywhere on the site.
      Everyone else sees ads normally. Your current IP is
      <b>${esc(d.your_ip||'unknown')}</b> — <a href="#" onclick="fillMyIp('${esc(d.your_ip)}');return false;">use it</a>.</div>
    <div class="card">
      <div class="row">
        <div class="field"><label>IP address (IPv4 or IPv6)</label><input id="adIp" placeholder="e.g. 203.0.113.45"></div>
        <div class="field"><label>Note (optional)</label><input id="adNote" placeholder="e.g. office router"></div>
        <div class="field" style="flex:0;min-width:auto;display:flex;align-items:flex-end">
          <button class="btn" onclick="adsAdd()">Hide ads for this IP</button>
        </div>
      </div>
    </div>
    <div class="card" style="padding:0;overflow:hidden">
      <table>
        <thead><tr><th>IP address</th><th>Note</th><th>Added</th><th></th></tr></thead>
        <tbody>${rows || '<tr><td colspan="4" class="muted" style="padding:18px">No IPs blocked yet — ads are visible to everyone.</td></tr>'}</tbody>
      </table>
    </div>

    <h2 class="section" style="margin-top:28px">Your advertisements (homepage banners)</h2>
    <div class="hint">Upload image banners to show on the <b>homepage</b>. They are shown to everyone
      <b>except</b> the blocked IPs above. Recommended a wide banner image (e.g. 970&times;250). Max 3&nbsp;MB.</div>
    <div class="card">
      <div class="row">
        <div class="field"><label>Banner image (jpg / png / webp / gif)</label><input id="haImg" type="file" accept="image/*"></div>
        <div class="field"><label>Click-through link (optional)</label><input id="haLink" placeholder="https://example.com"></div>
        <div class="field"><label>Note (optional)</label><input id="haNote" placeholder="e.g. Ramadan promo"></div>
        <div class="field" style="flex:0;min-width:auto;display:flex;align-items:flex-end">
          <button class="btn" onclick="houseAdd()">Upload ad</button>
        </div>
      </div>
    </div>
    <div id="houseWrap"><div class="spin">Loading ads…</div></div>`;
  loadHouseAds();
}

async function loadHouseAds(){
  const d = await api('houseads_list');
  const base = d.base || 'assets/img/houseads/';
  const rows = (d.ads||[]).map(a=>`
    <tr>
      <td class="adprev"><img src="${esc(base)}${esc(a.image)}" alt=""></td>
      <td>${a.link_url?`<a href="${esc(a.link_url)}" target="_blank">${esc(a.link_url)}</a>`:'<span class="muted">no link</span>'}</td>
      <td>${esc(a.note||'')}</td>
      <td>${Number(a.active)?'<span class="pill" style="border-color:var(--ok);color:var(--ok)">live</span>':'<span class="pill muted">hidden</span>'}</td>
      <td style="white-space:nowrap;text-align:right">
        <button class="btn sm ghost" onclick="houseToggle(${a.id})">${Number(a.active)?'Hide':'Show'}</button>
        <button class="btn sm danger" onclick="houseDelete(${a.id})">Delete</button>
      </td>
    </tr>`).join('');
  document.getElementById('houseWrap').innerHTML = `
    <div class="card" style="padding:0;overflow:hidden"><div class="tablewrap">
      <table><thead><tr><th>Preview</th><th>Link</th><th>Note</th><th>Status</th><th></th></tr></thead>
      <tbody>${rows||'<tr><td colspan="5" class="muted" style="padding:18px">No banner ads yet.</td></tr>'}</tbody></table>
    </div></div>`;
}

async function houseAdd(){
  const file = document.getElementById('haImg').files[0];
  if(!file){ toast('Choose an image first', true); return; }
  const fd = new FormData();
  fd.append('image', file);
  fd.append('link', document.getElementById('haLink').value.trim());
  fd.append('note', document.getElementById('haNote').value.trim());
  const d = await apiUpload('housead_add', fd);
  if(d.ok){ toast('Ad uploaded'); tabAds(); } else toast(d.error||'Upload failed', true);
}
async function houseToggle(id){
  const d = await api('housead_toggle',{id});
  if(d.ok) loadHouseAds(); else toast('Failed', true);
}
async function houseDelete(id){
  if(!confirm('Delete this banner ad? The image file is removed too.')) return;
  const d = await api('housead_delete',{id});
  if(d.ok){ toast('Ad deleted'); loadHouseAds(); } else toast('Failed', true);
}
function fillMyIp(ip){ const el=document.getElementById('adIp'); if(el) el.value=ip; }
async function adsAdd(){
  const ip=document.getElementById('adIp').value.trim();
  const note=document.getElementById('adNote').value.trim();
  const d=await api('ads_add',{ip,note});
  if(d.ok){ toast('Ads will now be hidden for '+ip); tabAds(); }
  else toast(d.error||'Failed to add IP', true);
}
async function adsDelete(id){
  if(!confirm('Remove this IP? Ads will be shown to it again.')) return;
  const d=await api('ads_delete',{id});
  if(d.ok){ toast('Removed — ads restored for that IP'); tabAds(); }
  else toast('Failed to remove', true);
}

/* ---- Content (generic table/row editor) ---- */
const CONTENT_HINTS = {
  indexpage:'Homepage', ebook:'E-books', ebook_category:'E-book categories',
  blog:'Blog posts', post:'Blog posts', event:'Events', events:'Events',
  klglance:'KL @ A Glance', map:'Map locations', location:'Map locations'
};
let dbCur = null; // {table, meta, rows, page, total, per, pk}

async function tabContent(){
  const d = await api('db_tables');
  const rows=(d.tables||[]).map(t=>{
    const hint = CONTENT_HINTS[t.name] ? ` <span class="muted">— ${esc(CONTENT_HINTS[t.name])}</span>` : '';
    return `<tr>
      <td><code>${esc(t.name)}</code>${hint}</td>
      <td>${t.rows==null?'—':t.rows.toLocaleString()}</td>
      <td style="text-align:right"><button class="btn sm" onclick="dbOpen('${esc(t.name)}',1)">Browse / edit</button></td>
    </tr>`;
  }).join('');
  view().innerHTML=`
    <h2 class="section">Content tables <span class="muted">(${(d.tables||[]).length})</span></h2>
    <div class="hint">Pick a table to add, edit or delete its rows. Page content lives here —
      e.g. <code>indexpage</code> (homepage), <code>ebook</code>, blog/event tables, map locations.
      Rich-text fields are stored as Quill JSON; edit the raw value carefully.</div>
    <div class="card" style="padding:0;overflow:hidden"><div class="tablewrap">
      <table><thead><tr><th>Table</th><th>Rows</th><th></th></tr></thead>
      <tbody>${rows||'<tr><td colspan="3" class="muted" style="padding:18px">No tables.</td></tr>'}</tbody></table>
    </div></div>`;
}

async function dbOpen(table, page){
  view().innerHTML='<div class="spin">Loading…</div>';
  const meta = await api('table_meta',{table});
  const data = await api('table_rows',{table, page});
  dbCur = {table, meta:meta.columns||[], pk:data.pk, rows:data.rows||[], page:data.page, total:data.total, per:data.per};
  renderTable();
}

function renderTable(){
  const c = dbCur;
  const cols = c.meta.map(m=>m.name);
  const head = cols.map(n=>`<th>${esc(n)}${n===c.pk?' <span class="muted">(PK)</span>':''}</th>`).join('');
  const body = c.rows.map((r,i)=>{
    const tds = cols.map(n=>{
      let v = r[n]; v = (v==null)?'<span class="muted">NULL</span>':esc(String(v).slice(0,200));
      return `<td title="${esc(r[n]==null?'':String(r[n]))}">${v}</td>`;
    }).join('');
    const actions = c.pk
      ? `<button class="btn sm ghost" onclick="dbEdit(${i})">Edit</button>
         <button class="btn sm danger" onclick="dbDelete(${i})">Del</button>`
      : '<span class="muted">no PK</span>';
    return `<tr>${tds}<td style="white-space:nowrap;text-align:right">${actions}</td></tr>`;
  }).join('');
  const pages = Math.max(1, Math.ceil(c.total / c.per));
  view().innerHTML=`
    <div class="crumb">
      <button class="btn sm ghost" onclick="tabContent()">&larr; Tables</button>
      <h2 class="section" style="margin:0">${esc(c.table)} <span class="muted">(${c.total.toLocaleString()} rows)</span></h2>
      <span style="flex:1"></span>
      <button class="btn sm" onclick="dbAdd()">+ Add row</button>
    </div>
    ${c.pk?'':'<div class="hint">This table has no single-column primary key, so rows are read-only here.</div>'}
    <div class="card" style="padding:0;overflow:hidden"><div class="tablewrap">
      <table><thead><tr>${head}<th></th></tr></thead>
      <tbody>${body||`<tr><td colspan="${cols.length+1}" class="muted" style="padding:18px">No rows.</td></tr>`}</tbody></table>
    </div></div>
    <div class="pager">
      <button class="btn sm ghost" ${c.page<=1?'disabled':''} onclick="dbOpen('${esc(c.table)}',${c.page-1})">Prev</button>
      <span class="muted">Page ${c.page} / ${pages}</span>
      <button class="btn sm ghost" ${c.page>=pages?'disabled':''} onclick="dbOpen('${esc(c.table)}',${c.page+1})">Next</button>
    </div>`;
}

function rowFieldsHtml(meta, row){
  return meta.map(m=>{
    const isPk = m.key==='PRI';
    const auto = (m.extra||'').includes('auto_increment');
    const val = row && row[m.name]!=null ? row[m.name] : '';
    const long = /text|blob|json|longtext|mediumtext/i.test(m.type);
    const ro = (row && isPk) ? 'readonly' : '';
    const note = `<span class="meta">${esc(m.type)}${auto?' · auto':''}${m.null==='NO'?' · required':''}</span>`;
    const input = long
      ? `<textarea data-col="${esc(m.name)}" ${ro}>${esc(val)}</textarea>`
      : `<input data-col="${esc(m.name)}" value="${esc(val)}" ${ro}>`;
    return `<div class="mf"><label>${esc(m.name)} ${note}</label>${input}</div>`;
  }).join('');
}

function collectRowData(){
  const out={};
  document.querySelectorAll('#modalBody [data-col]').forEach(el=>{ out[el.getAttribute('data-col')] = el.value; });
  return out;
}

function dbEdit(i){
  const c=dbCur, row=c.rows[i];
  document.getElementById('modalBody').innerHTML = rowFieldsHtml(c.meta, row);
  openModal('Edit row');
  document.getElementById('modalSave').onclick = async ()=>{
    const data = collectRowData();
    const res = await api('row_update',{table:c.table, pk_val:String(row[c.pk]), data:JSON.stringify(data)});
    if(res.ok){ toast('Saved'); closeModal(); dbOpen(c.table,c.page); } else toast(res.error||'Save failed', true);
  };
}

function dbAdd(){
  const c=dbCur;
  document.getElementById('modalBody').innerHTML = rowFieldsHtml(c.meta, null);
  openModal('Add row to '+c.table);
  document.getElementById('modalSave').onclick = async ()=>{
    const data = collectRowData();
    const res = await api('row_insert',{table:c.table, data:JSON.stringify(data)});
    if(res.ok){ toast('Row added'); closeModal(); dbOpen(c.table,1); } else toast(res.error||'Insert failed', true);
  };
}

async function dbDelete(i){
  const c=dbCur, row=c.rows[i];
  if(!confirm('Delete this row from "'+c.table+'"? This cannot be undone.')) return;
  const res = await api('row_delete',{table:c.table, pk_val:String(row[c.pk])});
  if(res.ok){ toast('Row deleted'); dbOpen(c.table,c.page); } else toast(res.error||'Delete failed', true);
}

/* ---- Users ---- */
async function tabUsers(){
  const d = await api('users_list');
  const rows=(d.users||[]).map(u=>`
    <tr>
      <td><b>${esc(u.username)}</b></td>
      <td class="muted">${esc(u.email||'')}</td>
      <td><span class="pill"${u.hash&&u.hash.indexOf('md5')===0?' style="border-color:var(--warn);color:var(--warn)"':''}>${esc(u.hash||'?')}</span></td>
      <td style="white-space:nowrap;text-align:right">
        <button class="btn sm" onclick="userImpersonate('${esc(u.username)}')">Access dashboard</button>
        <button class="btn sm ghost" onclick="userPwd('${esc(u.username)}')">Change password</button>
        <button class="btn sm danger" onclick="userDel('${esc(u.username)}')">Delete</button>
      </td>
    </tr>`).join('');
  view().innerHTML=`
    <h2 class="section">Admin accounts <span class="muted">(login.php / CMS)</span></h2>
    <div class="hint"><b>Access dashboard</b> logs this browser into <code>admin/index.php</code> as that
      account &mdash; no password needed, and their real password is left untouched. It replaces whichever
      admin session this browser currently holds, and is recorded in the Audit tab.</div>
    <div class="hint"><b>Why no password is shown:</b> only a one-way hash of each password is stored
      (see the Hash column), so nothing &mdash; not this panel, not the database &mdash; can read the
      original back. <b>Change password</b> instead asks for the current password and verifies it against
      that hash before setting a new one, and lets you test a guess without changing anything.</div>
    <div class="card">
      <div class="row">
        <div class="field"><label>Username</label><input id="nuUser"></div>
        <div class="field"><label>Email</label><input id="nuEmail"></div>
        <div class="field"><label>Password</label><input id="nuPass" type="text"></div>
        <div class="field" style="flex:0;min-width:auto;display:flex;align-items:flex-end">
          <button class="btn" onclick="userCreate()">Create</button>
        </div>
      </div>
    </div>
    <div class="card" style="padding:0;overflow:hidden">
      <table><thead><tr><th>Username</th><th>Email</th><th>Hash</th><th></th></tr></thead>
      <tbody>${rows||'<tr><td colspan="4" class="muted" style="padding:18px">No users.</td></tr>'}</tbody></table>
    </div>`;
}
async function userCreate(){
  const username=document.getElementById('nuUser').value.trim();
  const email=document.getElementById('nuEmail').value.trim();
  const password=document.getElementById('nuPass').value;
  const d=await api('users_create',{username,email,password});
  if(d.ok){ toast('User created'); tabUsers(); } else toast(d.error||'Failed', true);
}
function userPwd(username){
  openModal('Change password — '+username);
  document.getElementById('modalBody').innerHTML=`
    <div class="hint">The current password can't be displayed &mdash; only its one-way hash is stored.
      Enter it below and it will be checked against that hash before the new one is saved.
      <b>Verify</b> tells you whether a password you have in mind is still the live one.</div>
    <div class="field">
      <label>Current password</label>
      <div class="row" style="gap:8px">
        <input id="pwCur" type="password" autocomplete="off" style="flex:1">
        <button class="btn sm ghost" type="button" onclick="pwVerify('${esc(username)}')">Verify</button>
      </div>
      <div id="pwCurMsg" class="muted" style="margin-top:6px"></div>
    </div>
    <div class="field"><label>New password <span class="muted">(min 8 characters)</span></label>
      <input id="pwNew" type="password" autocomplete="new-password"></div>
    <div class="field"><label>Confirm new password</label>
      <input id="pwNew2" type="password" autocomplete="new-password"></div>
    <label class="muted" style="display:block;margin-top:10px">
      <input id="pwShow" type="checkbox" onchange="pwToggle(this.checked)"> Show passwords
    </label>
    <label class="muted" style="display:block;margin-top:6px">
      <input id="pwForce" type="checkbox" onchange="pwForceToggle(this.checked)">
      I don't know the current password &mdash; force reset (recorded in the Audit tab)
    </label>`;
  document.getElementById('modalSave').textContent='Update password';
  document.getElementById('modalSave').onclick=()=>userPwdSave(username);
}
function pwToggle(on){
  ['pwCur','pwNew','pwNew2'].forEach(id=>{ document.getElementById(id).type = on ? 'text' : 'password'; });
}
function pwForceToggle(on){
  const cur=document.getElementById('pwCur');
  cur.disabled=on; if(on) cur.value='';
  document.getElementById('pwCurMsg').textContent = on ? 'Current password will not be checked.' : '';
}
async function pwVerify(username){
  const msg=document.getElementById('pwCurMsg');
  const password=document.getElementById('pwCur').value;
  if(!password){ msg.textContent='Enter a password to verify.'; return; }
  const d=await api('users_password_check',{username,password});
  if(!d.ok){ msg.textContent=d.error||'Check failed'; return; }
  msg.innerHTML = d.match
    ? '<span style="color:var(--ok)">Correct &mdash; this is the current password.</span>'
    : '<span style="color:var(--danger)">Not the current password.</span>';
}
async function userPwdSave(username){
  const force=document.getElementById('pwForce').checked;
  const current=document.getElementById('pwCur').value;
  const password=document.getElementById('pwNew').value;
  const confirm2=document.getElementById('pwNew2').value;
  if(password.length<8) return toast('New password must be at least 8 characters', true);
  if(password!==confirm2) return toast('New passwords do not match', true);
  if(!force && !current) return toast('Enter the current password, or tick force reset', true);
  if(force && !window.confirm('Force-reset "'+username+'" without verifying the current password?')) return;
  const d=await api('users_password',{username,current,password,force:force?'1':'0'});
  if(d.ok){ toast('Password updated'); closeModal(); tabUsers(); } else toast(d.error||'Failed', true);
}
async function userDel(username){
  if(!confirm('Delete admin user "'+username+'"? They will lose CMS access.')) return;
  const d=await api('users_delete',{username});
  if(d.ok){ toast('User deleted'); tabUsers(); } else toast(d.error||'Failed', true);
}
async function userImpersonate(username){
  if(!confirm('Log this browser into the admin dashboard as "'+username+'"? This replaces any admin session currently open here.')) return;
  const d=await api('users_impersonate',{username});
  if(d.ok){ toast('Logged in as '+username); window.open('admin/index.php','_blank'); }
  else toast(d.error||'Failed', true);
}

/* ---- Queue (mailqueue drain, monitored) ---- */
async function tabQueue(){
  const [s, l] = await Promise.all([api('queue_stats'), api('queue_list')]);
  const st = s.stats || {pending:0, sent:0, other:0, total:0};
  const rows = (l.rows||[]).map(r=>{
    const status = r.sendstatus==='1'
      ? '<span class="pill" style="border-color:var(--ok);color:var(--ok)">sent</span>'
      : (r.sendstatus==='0'||!r.sendstatus ? '<span class="pill" style="border-color:var(--warn);color:var(--warn)">pending</span>' : `<span class="pill">${esc(r.sendstatus)}</span>`);
    return `<tr>
      <td>${esc(r.id)}</td>
      <td>${status}</td>
      <td>${esc(r.sendto||'')}</td>
      <td>${esc((r.sendtitle||'').slice(0,60))}</td>
      <td class="muted">${esc(r.init_time||'')}</td>
      <td class="muted">${esc(r.send_time||'')}</td>
    </tr>`;
  }).join('');
  view().innerHTML = `
    <h2 class="section">Mail queue</h2>
    <div class="hint">This is <code>mailqueue</code>, the table <code>admin/cron2-4.php</code> drain automatically
      (outside the 9am&ndash;7pm window). <b>Run now</b> sends pending mail immediately, ignoring that time window
      &mdash; use it to clear a stuck queue.</div>
    <div class="grid" style="margin-bottom:18px">
      ${['pending','sent','other','total'].map(k=>`<div class="kv"><div class="k">${k}</div><div class="v">${(st[k]||0).toLocaleString()}</div></div>`).join('')}
    </div>
    <div class="row" style="margin-bottom:16px">
      <button class="btn" onclick="queueRun()">Run now</button>
      <button class="btn ghost" onclick="queueClear()">Clear sent rows</button>
    </div>
    <div class="card" style="padding:0;overflow:hidden"><div class="tablewrap">
      <table><thead><tr><th>ID</th><th>Status</th><th>To</th><th>Subject</th><th>Queued</th><th>Sent</th></tr></thead>
      <tbody>${rows || '<tr><td colspan="6" class="muted" style="padding:18px">Queue is empty.</td></tr>'}</tbody></table>
    </div></div>`;
}
async function queueRun(){
  const d = await api('queue_run');
  if(d.ok){ toast(`Sent ${d.sent}, failed ${d.failed}`); tabQueue(); } else toast('Failed', true);
}
async function queueClear(){
  if(!confirm('Delete all sent rows from the mail queue? This cannot be undone.')) return;
  const d = await api('queue_clear');
  if(d.ok){ toast('Sent rows cleared'); tabQueue(); } else toast('Failed', true);
}

/* ---- Logs (tail the PHP error log) ---- */
async function tabLogs(){
  const d = await api('logs_tail');
  if(!d.available){
    view().innerHTML = `
      <h2 class="section">Error log</h2>
      <div class="hint">No readable PHP error log found${d.path?` at <code>${esc(d.path)}</code>`:' (no <code>error_log</code> configured in php.ini)'}.
      </div>`;
    return;
  }
  const body = (d.lines||[]).map(esc).join('\n');
  view().innerHTML = `
    <div class="crumb">
      <h2 class="section" style="margin:0">Error log</h2>
      <span class="muted">${esc(d.path)} &middot; ${(d.size/1024).toFixed(1)} KB</span>
      <span style="flex:1"></span>
      <button class="btn sm ghost" onclick="tabLogs()">Refresh</button>
    </div>
    <div class="card"><pre style="margin:0;white-space:pre-wrap;word-break:break-all;font-family:ui-monospace,Consolas,monospace;font-size:12px;max-height:65vh;overflow:auto">${body || '<span class="muted">(log is empty)</span>'}</pre></div>`;
}

/* ---- Audit trail ---- */
async function tabAudit(){
  const d = await api('audit_list');
  const rows = (d.rows||[]).map(r=>`
    <tr><td><code>${esc(r.action)}</code></td><td>${esc(r.detail||'')}</td><td>${esc(r.ip_address||'')}</td><td class="muted">${esc(r.created_at||'')}</td></tr>`).join('');
  view().innerHTML = `
    <h2 class="section">Audit trail</h2>
    <div class="hint">Every action taken through this console (except the unlock handshake), most recent first. Anyone with the key can act; this is a record, not an access control.</div>
    <div class="card" style="padding:0;overflow:hidden"><div class="tablewrap">
      <table><thead><tr><th>Action</th><th>Detail</th><th>IP</th><th>When</th></tr></thead>
      <tbody>${rows || '<tr><td colspan="4" class="muted" style="padding:18px">No actions logged yet.</td></tr>'}</tbody></table>
    </div></div>`;
}

/* ---- Boot: if a key is already stored, validate it silently ---- */
(async ()=>{
  if (DK){
    const ok = await tryUnlock(DK);
    if (!ok) lockSession();
  }
})();
</script>
</body>
</html>
