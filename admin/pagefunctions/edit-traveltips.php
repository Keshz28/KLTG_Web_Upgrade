<?php
/* ============================================================================
 *                     pagefunctions/edit-traveltips.php
 *
 *   Handler for the Travel Tips editor (admin/edit-traveltips.php).
 *   Manages the `traveltips` table — the Q&A "tip" items shown on the public
 *   travel-tips.php page. The 5 sections (a–e) and their icons are fixed
 *   scaffolding in the public page; only the items are edited here.
 *
 *   Included by functions.php after the central CSRF guard, so POST actions
 *   are CSRF-protected automatically (nav.php injects the token).
 * ============================================================================ */

// Guard: functions.php runs on public pages too — skip unless an admin is in.
if (!isset($_SESSION['username'])) return;

$tt_valid_sections = ['a', 'b', 'c', 'd', 'e'];
$tt_valid_cta      = ['none', 'link', 'map'];

// --- ADD ---------------------------------------------------------------
if (isset($_POST['add_traveltip'])) {
    $section = in_array($_POST['tt_section'] ?? '', $tt_valid_sections, true) ? $_POST['tt_section'] : 'a';
    $header  = trim($_POST['tt_header'] ?? '');
    $q       = trim($_POST['tt_question'] ?? '');
    $a       = trim($_POST['tt_answer'] ?? '');
    $extra   = trim($_POST['tt_extra'] ?? '');
    $ctaType = in_array($_POST['tt_cta_type'] ?? 'none', $tt_valid_cta, true) ? $_POST['tt_cta_type'] : 'none';
    $ctaLbl  = trim($_POST['tt_cta_label'] ?? '');
    $ctaVal  = trim($_POST['tt_cta_value'] ?? '');
    if ($ctaType === 'none') { $ctaLbl = ''; $ctaVal = ''; }

    if ($header === '') {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg']  = 'A title is required.';
    } else {
        $order_res = mysqli_query($db, "SELECT MAX(tt_order) AS m FROM traveltips WHERE tt_section = '" . $section . "'");
        $order_row = $order_res ? mysqli_fetch_assoc($order_res) : null;
        $new_order = ($order_row && $order_row['m'] !== null) ? ((int)$order_row['m'] + 1) : 1;

        $stmt = mysqli_prepare($db, "INSERT INTO traveltips (tt_section, tt_order, tt_header, tt_question, tt_answer, tt_extra, tt_cta_type, tt_cta_label, tt_cta_value) VALUES (?,?,?,?,?,?,?,?,?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sisssssss", $section, $new_order, $header, $q, $a, $extra, $ctaType, $ctaLbl, $ctaVal);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_msg']  = 'Tip added.';
            } else {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg']  = 'Database insert failed: ' . mysqli_error($db);
            }
            mysqli_stmt_close($stmt);
        }
    }
    header("Location: edit-traveltips.php#ttTable");
    exit();
}

// --- EDIT --------------------------------------------------------------
if (isset($_POST['edit_traveltip'])) {
    $id      = (int)($_POST['tt_id'] ?? 0);
    $section = in_array($_POST['tt_section'] ?? '', $tt_valid_sections, true) ? $_POST['tt_section'] : 'a';
    $header  = trim($_POST['tt_header'] ?? '');
    $q       = trim($_POST['tt_question'] ?? '');
    $a       = trim($_POST['tt_answer'] ?? '');
    $extra   = trim($_POST['tt_extra'] ?? '');
    $ctaType = in_array($_POST['tt_cta_type'] ?? 'none', $tt_valid_cta, true) ? $_POST['tt_cta_type'] : 'none';
    $ctaLbl  = trim($_POST['tt_cta_label'] ?? '');
    $ctaVal  = trim($_POST['tt_cta_value'] ?? '');
    if ($ctaType === 'none') { $ctaLbl = ''; $ctaVal = ''; }

    if ($id <= 0 || $header === '') {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg']  = 'A title is required.';
    } else {
        $stmt = mysqli_prepare($db, "UPDATE traveltips SET tt_section=?, tt_header=?, tt_question=?, tt_answer=?, tt_extra=?, tt_cta_type=?, tt_cta_label=?, tt_cta_value=? WHERE tt_id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssssi", $section, $header, $q, $a, $extra, $ctaType, $ctaLbl, $ctaVal, $id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_msg']  = 'Tip updated.';
            } else {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg']  = 'Database update failed: ' . mysqli_error($db);
            }
            mysqli_stmt_close($stmt);
        }
    }
    header("Location: edit-traveltips.php#ttTable");
    exit();
}

// --- DELETE ------------------------------------------------------------
if (isset($_POST['delete_traveltip'])) {
    $id = (int)($_POST['tt_id'] ?? 0);
    if ($id > 0) {
        $stmt = mysqli_prepare($db, "DELETE FROM traveltips WHERE tt_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_msg']  = 'Tip removed.';
    }
    header("Location: edit-traveltips.php#ttTable");
    exit();
}

// --- REORDER (swap with neighbour within the same section) -------------
if (isset($_GET['tt_up']) || isset($_GET['tt_down'])) {
    $dir = isset($_GET['tt_up']) ? 'up' : 'down';
    $id  = (int)($dir === 'up' ? $_GET['tt_up'] : $_GET['tt_down']);

    $cur = null;
    $res = mysqli_query($db, "SELECT tt_id, tt_section, tt_order FROM traveltips WHERE tt_id = " . $id);
    if ($res) $cur = mysqli_fetch_assoc($res);

    if ($cur) {
        $section = mysqli_real_escape_string($db, $cur['tt_section']);
        $order   = (int)$cur['tt_order'];
        // Neighbour = closest row on the chosen side, same section.
        if ($dir === 'up') {
            $nsql = "SELECT tt_id, tt_order FROM traveltips WHERE tt_section='$section' AND tt_order < $order ORDER BY tt_order DESC LIMIT 1";
        } else {
            $nsql = "SELECT tt_id, tt_order FROM traveltips WHERE tt_section='$section' AND tt_order > $order ORDER BY tt_order ASC LIMIT 1";
        }
        $nres = mysqli_query($db, $nsql);
        $nb   = $nres ? mysqli_fetch_assoc($nres) : null;

        if ($nb) {
            $stmt = mysqli_prepare($db, "UPDATE traveltips SET tt_order = ? WHERE tt_id = ?");
            if ($stmt) {
                $nbId = (int)$nb['tt_id']; $nbOrder = (int)$nb['tt_order'];
                mysqli_stmt_bind_param($stmt, "ii", $nbOrder, $id);   // current takes neighbour's order
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_param($stmt, "ii", $order, $nbId);   // neighbour takes current's order
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_msg']  = 'Order changed.';
            }
        }
    }
    header("Location: edit-traveltips.php#ttTable");
    exit();
}
