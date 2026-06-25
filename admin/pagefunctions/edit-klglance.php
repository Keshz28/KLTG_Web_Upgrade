<?php

// KL @ A Glance — landmark slides (klglance table)

$klglance_dir = "../assets/img/kl_glance/";

// --- UPLOAD (Create) ---
if (isset($_POST['upload_klglance'])) {
    $name    = trim($_POST['name']);
    $content = trim($_POST['content']);

    $uploaded_file = $_FILES["fileToUploadklglance"];

    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg']  = "Please choose an image to upload.";
    } else {
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "File is not an image.";
        } elseif ($uploaded_file["size"] > 5000000) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "Sorry, your file is too large (max 5MB).";
        } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            $newfilename = time() . '_' . basename($uploaded_file["name"]);
            $target_file = $klglance_dir . $newfilename;

            if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                error_log("Could not move uploaded file for klglance: " . $target_file);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg']  = "Sorry, there was an error uploading your file.";
            } else {
                $order_result = mysqli_query($db, "SELECT MAX(klglance_order) AS max_order FROM klglance");
                $order_row = mysqli_fetch_assoc($order_result);
                $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                $query = "INSERT INTO klglance (klglance_title, klglance_content, klglance_image, klglance_order) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($db, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $name, $content, $newfilename, $new_order);
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['alert_type'] = 'success';
                        $_SESSION['alert_msg']  = "Added new landmark";
                    } else {
                        error_log("DB insert failed for klglance: " . mysqli_error($db));
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg']  = "Database insertion failed.";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?addnew#klglanceTable");
    exit();
}

// --- ORDER UP ---
if (isset($_GET['orderupklglance'])) {
    $order2 = (int)$_GET['orderupklglance'] + 1;
    $id     = (int)$_GET['klglance_id'];

    $stmt = mysqli_prepare($db, "UPDATE klglance SET klglance_order = ? WHERE klglance_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg']  = "Order changed";
        }
        mysqli_stmt_close($stmt);
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#klglanceTable");
    exit();
}

// --- ORDER DOWN ---
if (isset($_GET['orderdownklglance'])) {
    $order2 = (int)$_GET['orderdownklglance'] - 1;
    $id     = (int)$_GET['klglance_id'];

    $stmt = mysqli_prepare($db, "UPDATE klglance SET klglance_order = ? WHERE klglance_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg']  = "Order changed";
        }
        mysqli_stmt_close($stmt);
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#klglanceTable");
    exit();
}

// --- DELETE ---
if (isset($_POST['deleteklglance'])) {
    $id       = (int)$_POST['klglanceid'];
    $filename = basename($_POST['imagenameklglance']);

    $stmt = mysqli_prepare($db, "DELETE FROM klglance WHERE klglance_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $file_path = $klglance_dir . $filename;
                if ($filename && file_exists($file_path)) {
                    @unlink($file_path);
                }
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_msg']  = "Removed";
            } else {
                $_SESSION['alert_type'] = 'warning';
                $_SESSION['alert_msg']  = "No record found to delete.";
            }
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "Database deletion failed.";
        }
        mysqli_stmt_close($stmt);
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#klglanceTable");
    exit();
}

// --- EDIT ---
if (isset($_POST['editklglance'])) {
    $name     = trim($_POST['name']);
    $content  = trim($_POST['content']);
    $id       = (int)$_POST['klglanceid'];
    $filename = basename($_POST['imagenameklglance']);

    $new_filename = $filename; // keep existing image unless a new one is uploaded
    $uploaded_file = $_FILES["fileToUploadklglance"];

    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "New file is not an image.";
        } elseif ($uploaded_file["size"] > 5000000) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "Sorry, your new file is too large (max 5MB).";
        } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            $new_filename = time() . '_' . basename($uploaded_file["name"]);
            $target_file  = $klglance_dir . $new_filename;
            if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg']  = "There was an error uploading your new file.";
                $new_filename = $filename; // fall back, do not lose the row's image
            } elseif ($filename && $filename !== $new_filename) {
                $old_file_path = $klglance_dir . $filename;
                if (file_exists($old_file_path)) {
                    @unlink($old_file_path);
                }
            }
        }
    }

    if (isset($_SESSION['alert_type']) && $_SESSION['alert_type'] === 'error') {
        header("Location: " . $_SERVER['PHP_SELF'] . "#klglanceTable");
        exit();
    }

    $query = "UPDATE klglance SET klglance_title = ?, klglance_content = ?, klglance_image = ? WHERE klglance_id = ?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $name, $content, $new_filename, $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg']  = "Edit saved";
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg']  = "Database update failed.";
        }
        mysqli_stmt_close($stmt);
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "#klglanceTable");
    exit();
}
