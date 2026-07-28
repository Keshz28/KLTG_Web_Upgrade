<?php
// Guard: this file is included by functions.php which runs on public pages too.
// Skip all handlers unless an admin session is active.
if (!isset($_SESSION['username'])) return;

// Add new advertisement
if (isset($_POST['add_advertisement'])) {
    $link_url = mysqli_real_escape_string($db, $_POST['link_url']);
    $filename = uploadimage($_FILES['ad_image'], 'advertisement', '');

    if ($filename) {
        // NOTE: functions.php sets mysqli_report(MYSQLI_REPORT_OFF), so a failed
        // query returns false silently. Check the return so a missing table or
        // permission problem surfaces instead of showing a false "success".
        $query = "INSERT INTO advertisement (image, link_url, is_active) VALUES ('$filename', '$link_url', 0)";
        if (mysqli_query($db, $query)) {
            array_push($errors2, "Advertisement added successfully.");
        } else {
            // Roll back the uploaded file so it doesn't orphan on disk.
            $orphan = '../assets/img/advertisement/' . $filename;
            if (is_file($orphan)) unlink($orphan);
            array_push($errors, "Database error saving the advertisement: " . mysqli_error($db) .
                " (if this says the 'advertisement' table is missing, run admin/advertisement_migration.sql on the live database).");
        }
    } else {
        array_push($errors, "Image upload failed. Use JPG/PNG/GIF/WEBP under 5 MB, and make sure a file with the same name wasn't already uploaded.");
    }
}

// Toggle active/inactive (only one ad active at a time)
if (isset($_GET['toggle_ad'])) {
    $id = (int)$_GET['toggle_ad'];
    $current = (int)$_GET['status'];
    $new_status = $current ? 0 : 1;

    if ($new_status === 1) {
        mysqli_query($db, "UPDATE advertisement SET is_active = 0");
    }

    $query = "UPDATE advertisement SET is_active = $new_status WHERE id = $id";
    mysqli_query($db, $query);
    array_push($errors2, $new_status ? "Advertisement activated." : "Advertisement deactivated.");
}

// Update link URL only
if (isset($_POST['update_ad_link'])) {
    $id = (int)$_POST['ad_id'];
    $link_url = mysqli_real_escape_string($db, $_POST['link_url']);
    $query = "UPDATE advertisement SET link_url = '$link_url' WHERE id = $id";
    mysqli_query($db, $query);
    array_push($errors2, "Link updated.");
}

// Delete advertisement
if (isset($_POST['delete_advertisement'])) {
    $id = (int)$_POST['ad_id'];
    $image = mysqli_real_escape_string($db, $_POST['ad_image_name']);

    $fetch = mysqli_query($db, "SELECT image FROM advertisement WHERE id = $id");
    if ($fetch && $row = mysqli_fetch_assoc($fetch)) {
        $img_path = '../assets/img/advertisement/' . $row['image'];
        if (file_exists($img_path) && $row['image'] !== '') {
            unlink($img_path);
        }
    }

    $query = "DELETE FROM advertisement WHERE id = $id";
    mysqli_query($db, $query);
    array_push($errors2, "Advertisement deleted.");
}
