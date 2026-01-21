<?php
// place to shop

// --- UPLOAD (Create) ---
if (isset($_POST['upload_pts'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');

    $uploaded_file = $_FILES["fileToUpload"]; // Get the file array

    // Validate file upload
    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        error_log("File upload error for place_shop: " . $uploaded_file['error']);
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "File upload failed: " . $uploaded_file['error'];
    } else {
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("File is not an image for place_shop: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "File is not an image.";
        } else {
            // Check file size (e.g., 5MB limit)
            if ($uploaded_file["size"] > 5000000) { // 5MB in bytes
                error_log("File too large for place_shop: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid file type for place_shop: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Generate unique filename
                    $newfilename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/place_to_shop/"; // Adjust path if necessary
                    $target_file = $target_dir . $newfilename;

                    // Attempt to move uploaded file
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move uploaded file for place_shop: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your file.";
                    } else {
                        // Calculate the new order (assuming it's the highest + 1)
                        $order_query = "SELECT MAX(place_shop_order) AS max_order FROM place_shop";
                        $order_result = mysqli_query($db, $order_query);
                        if (!$order_result) {
                            error_log("Database query failed for max order in place_shop: " . mysqli_error($db));
                            $_SESSION['alert_type'] = 'error';
                            $_SESSION['alert_msg'] = "Database error fetching max order.";
                        } else {
                            $order_row = mysqli_fetch_assoc($order_result);
                            $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                            // Insert data into DB
                            $query = "INSERT INTO place_shop (place_shop_title,place_shop_content,place_shop_location,place_shop_locationurl,place_shop_image,place_shop_hours,place_shop_phone,place_shop_website,place_shop_order)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($db, $query);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $content, $location, $locationurl, $newfilename, $hours, $phone, $website, $new_order);
                                $insert_result = mysqli_stmt_execute($stmt);

                                if ($insert_result) {
                                    // Success: Store success message in session
                                    $_SESSION['alert_type'] = 'success';
                                    $_SESSION['alert_msg'] = "Added New place_shop";
                                } else {
                                    error_log("Database insertion failed for place_shop: " . mysqli_error($db));
                                    $_SESSION['alert_type'] = 'error';
                                    $_SESSION['alert_msg'] = "Database insertion failed: " . mysqli_error($db);
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                error_log("Database prepare failed for place_shop: " . mysqli_error($db));
                                $_SESSION['alert_type'] = 'error';
                                $_SESSION['alert_msg'] = "Database error preparing query.";
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
if (isset($_GET['orderupPTS'])) {
    $order = (int)$_GET['orderupPTS'];
    $place_shop_id = (int)$_GET['place_shop_id'];
    $order2 = $order + 1;

    $query = "UPDATE place_shop SET place_shop_order= ? WHERE place_shop_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $place_shop_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order up failed for place_shop ID $place_shop_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order place_shop: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- ORDER DOWN ---
if (isset($_GET['orderdownPTS'])) {
    $order = (int)$_GET['orderdownPTS'];
    $place_shop_id = (int)$_GET['place_shop_id'];
    $order2 = $order - 1;

    $query = "UPDATE place_shop SET place_shop_order= ? WHERE place_shop_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $place_shop_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order down failed for place_shop ID $place_shop_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order place_shop: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- DELETE ---
if (isset($_POST['deletepts'])) {
    $id = (int)$_POST['ptsid']; // Cast to integer for security
    $filename = $_POST['imagenamepts'];

    $query = "DELETE FROM place_shop WHERE place_shop_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // DB deletion successful, now try to delete the file
                $file_path = "../assets/img/place_to_shop/" . $filename; // Adjust path if necessary
                if (file_exists($file_path) && !unlink($file_path)) {
                    error_log("Could not delete file for place_shop: " . $file_path);
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
            error_log("Database deletion failed for place_shop ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database deletion failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for delete place_shop: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing delete query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- EDIT ---
if (isset($_POST['editpts'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');

    $order = (int)$_POST['order']; // Cast to integer
    $id = (int)$_POST['ptsid']; // Cast to integer
    $filename = $_POST['imagenamepts'];

    // Check if a new file was uploaded
    $new_filename = $filename; // Default to existing filename
    $uploaded_file = $_FILES["fileToUpload"];
    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        // Validate and handle new file upload (similar validation as in upload)
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("New file is not an image for place_shop ID $id: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "New file is not an image.";
        } else {
            if ($uploaded_file["size"] > 5000000) {
                error_log("New file too large for place_shop ID $id: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your new file is too large.";
            } else {
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid new file type for place_shop ID $id: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the new image.";
                } else {
                    // Generate new filename and move file
                    $new_filename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/place_to_shop/"; // Adjust path if necessary
                    $target_file = $target_dir . $new_filename;
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move new uploaded file for place_shop ID $id: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your new file.";
                    } else {
                        // Delete old file if it's different from the new one
                        if ($filename && $filename !== $new_filename) {
                            $old_file_path = "../assets/img/place_to_shop/" . $filename; // Adjust path if necessary
                            if (file_exists($old_file_path) && !unlink($old_file_path)) {
                                error_log("Could not delete old file for place_shop ID $id: " . $old_file_path);
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
    $query = "UPDATE place_shop SET
              place_shop_title=?,
              place_shop_order=?,
              place_shop_location=?,
              place_shop_locationurl=?,
              place_shop_content=?,
              place_shop_hours=?,
              place_shop_phone=?,
              place_shop_website=?
              WHERE place_shop_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sissssssi", $name, $order, $location, $locationurl, $content, $hours, $phone, $website, $id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Edit Saved";
        } else {
            error_log("Database update failed for place_shop ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update place_shop ID $id: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// end place to shop
