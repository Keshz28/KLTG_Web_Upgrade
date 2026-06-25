<?php
// Guard: this file is included by functions.php which runs on public pages too.
// Skip all handlers unless an admin session is active.
if (!isset($_SESSION['username'])) return;

// Add new advertisement
if (isset($_POST['add_advertisement'])) {
    $link_url = mysqli_real_escape_string($db, $_POST['link_url']);
    $filename = uploadimage($_FILES['ad_image'], 'advertisement', '');

    if ($filename) {
        $query = "INSERT INTO advertisement (image, link_url, is_active) VALUES ('$filename', '$link_url', 0)";
        mysqli_query($db, $query);
        array_push($errors2, "Advertisement added successfully.");
    } else {
        array_push($errors, "Image upload failed. Use JPG/PNG/GIF/WEBP under 5 MB.");
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
