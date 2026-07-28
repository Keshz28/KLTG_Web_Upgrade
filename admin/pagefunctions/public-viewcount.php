<?php
/* ============================================================================
 *                       pagefunctions/public-viewcount.php
 *
 *   The blog / page view counters. Split out of edit-blog.php because these are
 *   the only pagefunctions actions a PUBLIC, logged-out visitor triggers:
 *   assets/js/blog-details.js and assets/js/blog2.js XHR straight to
 *   admin/functions.php?postidviews= / ?postidviewsupdate=.
 *
 *   Everything else under pagefunctions/ is a CMS write and is gated behind an
 *   admin session in functions.php. This file is deliberately included OUTSIDE
 *   that gate, so it must never do anything an anonymous caller shouldn't.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

// Read a blog post's view count (echoed as a bare number for the XHR caller).
if (isset($_GET['postidviews'])) {
    $stmt = mysqli_prepare($db, "SELECT blog_view, blog_viewsettings FROM blog WHERE blog_postid = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $_GET['postidviews']);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) {
            echo $row['blog_viewsettings'] == 1 ? (int) $row['blog_view'] : 0;
        }
        mysqli_stmt_close($stmt);
    }
}

// Record a page view.
if (isset($_GET['updatepageview'])) {
    $stmt = mysqli_prepare($db, "INSERT INTO pageview (url, views) VALUES (?, '1')");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $_GET['updatepageview']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Increment a blog post's view count.
if (isset($_GET['postidviewsupdate'])) {
    $stmt = mysqli_prepare($db, "UPDATE blog SET blog_view = blog_view + 1 WHERE blog_postid = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $_GET['postidviewsupdate']);
        echo mysqli_stmt_execute($stmt) ? "success" : "fail";
        mysqli_stmt_close($stmt);
    } else {
        echo "fail";
    }
}
