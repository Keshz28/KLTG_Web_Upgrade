<?php

// ekl kl4k

// --- UPLOAD (Create) ---
if (isset($_POST['upload_eklkl4k'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');

    $uploaded_file = $_FILES["fileToUploadeklkl4k"]; // Get the file array

    // Validate file upload
    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        error_log("File upload error for explorekl_kl4k: " . $uploaded_file['error']);
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "File upload failed: " . $uploaded_file['error'];
    } else {
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("File is not an image for explorekl_wte_c: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "File is not an image.";
        } else {

            // Check if image file is an actual image
            $check = getimagesize($uploaded_file["tmp_name"]);
            if ($check === false) {
                error_log("File is not an image for explorekl_kl4k: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "File is not an image.";
            } else {
                // Check file size (e.g., 5MB limit)
                if ($uploaded_file["size"] > 5000000) { // 5MB in bytes
                    error_log("File too large for explorekl_kl4k: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, your file is too large.";
                } else {
                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        error_log("Invalid file type for explorekl_kl4k: " . $uploaded_file['name']);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    } else {
                        // Generate unique filename
                        $newfilename = time() . '_' . basename($uploaded_file["name"]);
                        $target_dir = "../assets/img/explorekl/kl4k/"; // Adjust path if necessary
                        $target_file = $target_dir . $newfilename;

                        // Attempt to move uploaded file
                        if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                            error_log("Could not move uploaded file for explorekl_kl4k: " . $target_file);
                            $_SESSION['alert_type'] = 'error';
                            $_SESSION['alert_msg'] = "Sorry, there was an error uploading your file.";
                        } else {
                            // Calculate the new order (assuming it's the highest + 1)
                            $order_query = "SELECT MAX(explorekl_kl4k_order) AS max_order FROM explorekl_kl4k";
                            $order_result = mysqli_query($db, $order_query);
                            if (!$order_result) {
                                error_log("Database query failed for max order in explorekl_kl4k: " . mysqli_error($db));
                                $_SESSION['alert_type'] = 'error';
                                $_SESSION['alert_msg'] = "Database error fetching max order.";
                            } else {
                                $order_row = mysqli_fetch_assoc($order_result);
                                $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                                // Insert data into DB
                                $query = "INSERT INTO explorekl_kl4k (explorekl_kl4k_title,explorekl_kl4k_content,explorekl_kl4k_location,explorekl_kl4k_locationurl,explorekl_kl4k_image,explorekl_kl4k_hours,explorekl_kl4k_phone,explorekl_kl4k_order)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt = mysqli_prepare($db, $query);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "sssssssi", $name, $content, $location, $locationurl, $newfilename, $hours, $phone, $new_order);
                                    $insert_result = mysqli_stmt_execute($stmt);

                                    if ($insert_result) {
                                        // Success: Store success message in session
                                        $_SESSION['alert_type'] = 'success';
                                        $_SESSION['alert_msg'] = "Added New explorekl_kl4k";
                                    } else {
                                        error_log("Database insertion failed for explorekl_kl4k: " . mysqli_error($db));
                                        $_SESSION['alert_type'] = 'error';
                                        $_SESSION['alert_msg'] = "Database insertion failed: " . mysqli_error($db);
                                    }
                                    mysqli_stmt_close($stmt);
                                } else {
                                    error_log("Database prepare failed for explorekl_kl4k: " . mysqli_error($db));
                                    $_SESSION['alert_type'] = 'error';
                                    $_SESSION['alert_msg'] = "Database error preparing query.";
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'] . "?addnew#table4"; // Adjust hash if necessary for KL4K table
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- ORDER UP ---
if (isset($_GET['orderupeklkl4k'])) {
    $order = (int)$_GET['orderupeklkl4k'];
    $explorekl_kl4k_id = (int)$_GET['explorekl_kl4k_id'];
    $order2 = $order + 1;

    $query = "UPDATE explorekl_kl4k SET explorekl_kl4k_order= ? WHERE explorekl_kl4k_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_kl4k_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order up failed for explorekl_kl4k ID $explorekl_kl4k_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_kl4k: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- ORDER DOWN ---
if (isset($_GET['orderdowneklkl4k'])) {
    $order = (int)$_GET['orderdowneklkl4k'];
    $explorekl_kl4k_id = (int)$_GET['explorekl_kl4k_id'];
    $order2 = $order - 1;

    $query = "UPDATE explorekl_kl4k SET explorekl_kl4k_order= ? WHERE explorekl_kl4k_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_kl4k_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order down failed for explorekl_kl4k ID $explorekl_kl4k_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_kl4k: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- DELETE ---
if (isset($_POST['deleteeklkl4k'])) {
    $id = (int)$_POST['eklkl4kid']; // Cast to integer for security
    $filename = $_POST['imagenameeklkl4k'];

    $query = "DELETE FROM explorekl_kl4k WHERE explorekl_kl4k_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // DB deletion successful, now try to delete the file
                $file_path = "../assets/img/explorekl/kl4k/" . $filename; // Adjust path if necessary
                if (file_exists($file_path) && !unlink($file_path)) {
                    error_log("Could not delete file for explorekl_kl4k: " . $file_path);
                    // File deletion failed, but DB entry was removed
                    $_SESSION['alert_type'] = 'warning';
                    $_SESSION['alert_msg'] = "Database entry deleted, but file removal failed.";
                } else {
                    // Both DB and file deletion successful
                    $_SESSION['alert_type'] = 'success';
                    $_SESSION['alert_msg'] = "Removed";
                }
            } else {
                // No rows were affected, ID might not exist
                $_SESSION['alert_type'] = 'warning';
                $_SESSION['alert_msg'] = "No record found to delete.";
            }
        } else {
            // DB deletion failed
            error_log("Database deletion failed for explorekl_kl4k ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database deletion failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for delete explorekl_kl4k: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing delete query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- EDIT ---
if (isset($_POST['editeklkl4k'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');

    $order = (int)$_POST['order']; // Cast to integer
    $id = (int)$_POST['eklkl4kid']; // Cast to integer
    $filename = $_POST['imagenameeklkl4k'];

    // Check if a new file was uploaded
    $new_filename = $filename; // Default to existing filename
    $uploaded_file = $_FILES["fileToUploadeklkl4k"];
    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        // Validate and handle new file upload (similar validation as in upload)
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("New file is not an image for explorekl_kl4k ID $id: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "New file is not an image.";
        } else {
            if ($uploaded_file["size"] > 5000000) {
                error_log("New file too large for explorekl_kl4k ID $id: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your new file is too large.";
            } else {
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid new file type for explorekl_kl4k ID $id: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the new image.";
                } else {
                    // Generate new filename and move file
                    $new_filename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/explorekl/kl4k/"; // Adjust path if necessary
                    $target_file = $target_dir . $new_filename;
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move new uploaded file for explorekl_kl4k ID $id: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your new file.";
                    } else {
                        // Delete old file if it's different from the new one
                        if ($filename && $filename !== $new_filename) {
                            $old_file_path = "../assets/img/explorekl/kl4k/" . $filename; // Adjust path if necessary
                            if (file_exists($old_file_path) && !unlink($old_file_path)) {
                                error_log("Could not delete old file for explorekl_kl4k ID $id: " . $old_file_path);
                                // Log error but continue with update
                            }
                        }
                    }
                }
            }
        }
    }

    // If an error occurred during file handling, redirect now
    if (isset($_SESSION['alert_type']) && $_SESSION['alert_type'] === 'error') {
        $redirect_url = $_SERVER['PHP_SELF'];
        header("Location: " . $redirect_url);
        exit();
    }

    // Update database with new data (and potentially new filename)
    $query = "UPDATE explorekl_kl4k SET
              explorekl_kl4k_title=?,
              explorekl_kl4k_order=?,
              explorekl_kl4k_location=?,
              explorekl_kl4k_locationurl=?,
              explorekl_kl4k_content=?,
              explorekl_kl4k_hours=?,
              explorekl_kl4k_phone=?
              WHERE explorekl_kl4k_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisssssi", $name, $order, $location, $locationurl, $content, $hours, $phone, $id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Edit Saved";
        } else {
            error_log("Database update failed for explorekl_kl4k ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update explorekl_kl4k ID $id: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// ekl kl4k
