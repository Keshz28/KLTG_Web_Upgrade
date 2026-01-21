<?php

// beyondkl hs

// --- UPLOAD (Create) ---
if (isset($_POST['upload_bklhs'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');

    $uploaded_file = $_FILES["fileToUploadbklhs"]; // Get the file array

    // Validate file upload
    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        error_log("File upload error for beyondkl_hs: " . $uploaded_file['error']);
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
                error_log("File is not an image for beyondkl_hs: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "File is not an image.";
            } else {
                // Check file size (e.g., 5MB limit)
                if ($uploaded_file["size"] > 5000000) { // 5MB in bytes
                    error_log("File too large for beyondkl_hs: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, your file is too large.";
                } else {
                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        error_log("Invalid file type for beyondkl_hs: " . $uploaded_file['name']);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    } else {
                        // Generate unique filename
                        $newfilename = time() . '_' . basename($uploaded_file["name"]);
                        $target_dir = "../assets/img/beyondkl/hs/"; // Adjust path if necessary
                        $target_file = $target_dir . $newfilename;

                        // Attempt to move uploaded file
                        if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                            error_log("Could not move uploaded file for beyondkl_hs: " . $target_file);
                            $_SESSION['alert_type'] = 'error';
                            $_SESSION['alert_msg'] = "Sorry, there was an error uploading your file.";
                        } else {
                            // Calculate the new order (assuming it's the highest + 1)
                            $order_query = "SELECT MAX(beyondkl_hs_order) AS max_order FROM beyondkl_hs";
                            $order_result = mysqli_query($db, $order_query);
                            if (!$order_result) {
                                error_log("Database query failed for max order in beyondkl_hs: " . mysqli_error($db));
                                $_SESSION['alert_type'] = 'error';
                                $_SESSION['alert_msg'] = "Database error fetching max order.";
                            } else {
                                $order_row = mysqli_fetch_assoc($order_result);
                                $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                                // Insert data into DB
                                $query = "INSERT INTO beyondkl_hs (beyondkl_hs_title,beyondkl_hs_content,beyondkl_hs_location,beyondkl_hs_locationurl,beyondkl_hs_image,beyondkl_hs_order)
                                        VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt = mysqli_prepare($db, $query);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "sssssi", $name, $content, $locationurl, $locationurl, $newfilename, $new_order);
                                    $insert_result = mysqli_stmt_execute($stmt);

                                    if ($insert_result) {
                                        // Success: Store success message in session
                                        $_SESSION['alert_type'] = 'success';
                                        $_SESSION['alert_msg'] = "Added New beyondkl_hs";
                                    } else {
                                        error_log("Database insertion failed for beyondkl_hs: " . mysqli_error($db));
                                        $_SESSION['alert_type'] = 'error';
                                        $_SESSION['alert_msg'] = "Database insertion failed: " . mysqli_error($db);
                                    }
                                    mysqli_stmt_close($stmt);
                                } else {
                                    error_log("Database prepare failed for beyondkl_hs: " . mysqli_error($db));
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
    $redirect_url = $_SERVER['PHP_SELF']; // Redirects back to the main page
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- ORDER UP ---
if (isset($_GET['orderupbklhs'])) {
    $order = (int)$_GET['orderupbklhs'];
    $beyondkl_hs_id = (int)$_GET['beyondkl_hs_id'];
    $order2 = $order + 1;

    $query = "UPDATE beyondkl_hs SET beyondkl_hs_order= ? WHERE beyondkl_hs_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $beyondkl_hs_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order up failed for beyondkl_hs ID $beyondkl_hs_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order beyondkl_hs: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- ORDER DOWN ---
if (isset($_GET['orderdownbklhs'])) {
    $order = (int)$_GET['orderdownbklhs'];
    $beyondkl_hs_id = (int)$_GET['beyondkl_hs_id'];
    $order2 = $order - 1;

    $query = "UPDATE beyondkl_hs SET beyondkl_hs_order= ? WHERE beyondkl_hs_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $beyondkl_hs_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order down failed for beyondkl_hs ID $beyondkl_hs_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order beyondkl_hs: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- DELETE ---
if (isset($_POST['deletebklhs'])) {
    $id = (int)$_POST['bklhsid']; // Cast to integer for security
    $filename = $_POST['imagenamebklhs'];

    $query = "DELETE FROM beyondkl_hs WHERE beyondkl_hs_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // DB deletion successful, now try to delete the file
                $file_path = "../assets/img/beyondkl/hs/" . $filename; // Adjust path if necessary
                if (file_exists($file_path) && !unlink($file_path)) {
                    error_log("Could not delete file for beyondkl_hs: " . $file_path);
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
            error_log("Database deletion failed for beyondkl_hs ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database deletion failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for delete beyondkl_hs: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing delete query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- EDIT ---
if (isset($_POST['editbklhs'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');

    $order = (int)$_POST['order']; // Cast to integer
    $id = (int)$_POST['bklhsid']; // Cast to integer
    $filename = $_POST['imagenamebklhs'];

    // Check if a new file was uploaded
    $new_filename = $filename; // Default to existing filename
    $uploaded_file = $_FILES["fileToUploadbklhs"];
    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        // Validate and handle new file upload (similar validation as in upload)
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("New file is not an image for beyondkl_hs ID $id: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "New file is not an image.";
        } else {
            if ($uploaded_file["size"] > 5000000) {
                error_log("New file too large for beyondkl_hs ID $id: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your new file is too large.";
            } else {
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid new file type for beyondkl_hs ID $id: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the new image.";
                } else {
                    // Generate new filename and move file
                    $new_filename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/beyondkl/hs/"; // Adjust path if necessary
                    $target_file = $target_dir . $new_filename;
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move new uploaded file for beyondkl_hs ID $id: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your new file.";
                    } else {
                        // Delete old file if it's different from the new one
                        if ($filename && $filename !== $new_filename) {
                            $old_file_path = "../assets/img/beyondkl/hs/" . $filename; // Adjust path if necessary
                            if (file_exists($old_file_path) && !unlink($old_file_path)) {
                                error_log("Could not delete old file for beyondkl_hs ID $id: " . $old_file_path);
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
    $query = "UPDATE beyondkl_hs SET
              beyondkl_hs_title=?,
              beyondkl_hs_order=?,
              beyondkl_hs_location=?,
              beyondkl_hs_locationurl=?,
              beyondkl_hs_content=?
              WHERE beyondkl_hs_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisssi", $name, $order, $locationurl, $locationurl, $content, $id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Edit Saved";
        } else {
            error_log("Database update failed for beyondkl_hs ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update beyondkl_hs ID $id: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// beyondkl hs
