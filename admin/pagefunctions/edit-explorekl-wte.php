<?php
// ekl wte sf

// --- UPLOAD (Create) wte_sf ---
if (isset($_POST['upload_eklwtesf'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

    $uploaded_file = $_FILES["fileToUploadeklwtesf"]; // Get the file array

    // Validate file upload
    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        error_log("File upload error for explorekl_wte_sf: " . $uploaded_file['error']);
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
                error_log("File is not an image for explorekl_wte_sf: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "File is not an image.";
            } else {
                // Check file size (e.g., 5MB limit)
                if ($uploaded_file["size"] > 5000000) { // 5MB in bytes
                    error_log("File too large for explorekl_wte_sf: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, your file is too large.";
                } else {
                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        error_log("Invalid file type for explorekl_wte_sf: " . $uploaded_file['name']);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    } else {
                        // Generate unique filename
                        $newfilename = time() . '_' . basename($uploaded_file["name"]);
                        $target_dir = "../assets/img/explorekl/wte/sf/"; // Adjust path if necessary
                        $target_file = $target_dir . $newfilename;

                        // Attempt to move uploaded file
                        if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                            error_log("Could not move uploaded file for explorekl_wte_sf: " . $target_file);
                            $_SESSION['alert_type'] = 'error';
                            $_SESSION['alert_msg'] = "Sorry, there was an error uploading your file.";
                        } else {
                            // Calculate the new order (assuming it's the highest + 1)
                            $order_query = "SELECT MAX(explorekl_wte_sf_order) AS max_order FROM explorekl_wte_sf";
                            $order_result = mysqli_query($db, $order_query);
                            if (!$order_result) {
                                error_log("Database query failed for max order in explorekl_wte_sf: " . mysqli_error($db));
                                $_SESSION['alert_type'] = 'error';
                                $_SESSION['alert_msg'] = "Database error fetching max order.";
                            } else {
                                $order_row = mysqli_fetch_assoc($order_result);
                                $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                                // Insert data into DB
                                $query = "INSERT INTO explorekl_wte_sf (explorekl_wte_sf_title,explorekl_wte_sf_content,explorekl_wte_sf_website,explorekl_wte_sf_location,explorekl_wte_sf_locationurl,explorekl_wte_sf_image,explorekl_wte_sf_hours,explorekl_wte_sf_phone,explorekl_wte_sf_category,explorekl_wte_sf_order)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt = mysqli_prepare($db, $query);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "sssssssssi", $name, $content, $website, $location, $locationurl, $newfilename, $hours, $phone, $category, $new_order);
                                    $insert_result = mysqli_stmt_execute($stmt);

                                    if ($insert_result) {
                                        // Success: Store success message in session
                                        $_SESSION['alert_type'] = 'success';
                                        $_SESSION['alert_msg'] = "Added New explorekl_wte_sf";
                                    } else {
                                        error_log("Database insertion failed for explorekl_wte_sf: " . mysqli_error($db));
                                        $_SESSION['alert_type'] = 'error';
                                        $_SESSION['alert_msg'] = "Database insertion failed: " . mysqli_error($db);
                                    }
                                    mysqli_stmt_close($stmt);
                                } else {
                                    error_log("Database prepare failed for explorekl_wte_sf: " . mysqli_error($db));
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
    $redirect_url = $_SERVER['PHP_SELF'] . "?addnew#table10"; // Adjust hash if necessary for WTE_SF table
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- ORDER UP wte_sf ---
if (isset($_GET['orderupeklwtesf'])) {
    $order = (int)$_GET['orderupeklwtesf'];
    $explorekl_wte_sf_id = (int)$_GET['explorekl_wte_sf_id'];
    $order2 = $order + 1;

    $query = "UPDATE explorekl_wte_sf SET explorekl_wte_sf_order= ? WHERE explorekl_wte_sf_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_wte_sf_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order up failed for explorekl_wte_sf ID $explorekl_wte_sf_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_wte_sf: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- ORDER DOWN wte_sf ---
if (isset($_GET['orderdowneklwtesf'])) {
    $order = (int)$_GET['orderdowneklwtesf'];
    $explorekl_wte_sf_id = (int)$_GET['explorekl_wte_sf_id'];
    $order2 = $order - 1;

    $query = "UPDATE explorekl_wte_sf SET explorekl_wte_sf_order= ? WHERE explorekl_wte_sf_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_wte_sf_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order down failed for explorekl_wte_sf ID $explorekl_wte_sf_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_wte_sf: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- DELETE wte_sf ---
if (isset($_POST['deleteeklwtesf'])) {
    $id = (int)$_POST['eklwtesfid']; // Cast to integer for security
    $filename = $_POST['imagenameeklwtesf'];

    $query = "DELETE FROM explorekl_wte_sf WHERE explorekl_wte_sf_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // DB deletion successful, now try to delete the file
                $file_path = "../assets/img/explorekl/wte/sf/" . $filename; // Adjust path if necessary
                if (file_exists($file_path) && !unlink($file_path)) {
                    error_log("Could not delete file for explorekl_wte_sf: " . $file_path);
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
            error_log("Database deletion failed for explorekl_wte_sf ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database deletion failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for delete explorekl_wte_sf: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing delete query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- EDIT wte_sf ---
if (isset($_POST['editeklwtesf'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

    $order = (int)$_POST['order']; // Cast to integer
    $id = (int)$_POST['eklwtesfid']; // Cast to integer
    $filename = $_POST['imagenameeklwtesf'];


    // Check if a new file was uploaded
    $new_filename = $filename; // Default to existing filename
    $uploaded_file = $_FILES["fileToUploadeklwtesf"];
    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        // Validate and handle new file upload (similar validation as in upload)
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("New file is not an image for explorekl_wte_sf ID $id: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "New file is not an image.";
        } else {
            if ($uploaded_file["size"] > 5000000) {
                error_log("New file too large for explorekl_wte_sf ID $id: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your new file is too large.";
            } else {
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid new file type for explorekl_wte_sf ID $id: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the new image.";
                } else {
                    // Generate new filename and move file
                    $new_filename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/explorekl/wte/sf/"; // Adjust path if necessary
                    $target_file = $target_dir . $new_filename;
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move new uploaded file for explorekl_wte_sf ID $id: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your new file.";
                    } else {
                        // Delete old file if it's different from the new one
                        if ($filename && $filename !== $new_filename) {
                            $old_file_path = "../assets/img/explorekl/wte/sf/" . $filename; // Adjust path if necessary
                            if (file_exists($old_file_path) && !unlink($old_file_path)) {
                                error_log("Could not delete old file for explorekl_wte_sf ID $id: " . $old_file_path);
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
    $query = "UPDATE explorekl_wte_sf SET
              explorekl_wte_sf_title=?,
              explorekl_wte_sf_order=?,
              explorekl_wte_sf_location=?,
              explorekl_wte_sf_locationurl=?,
              explorekl_wte_sf_content=?,
              explorekl_wte_sf_website=?,
              explorekl_wte_sf_hours=?,
              explorekl_wte_sf_phone=?,
              explorekl_wte_sf_category=?
              WHERE explorekl_wte_sf_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisssssssi", $name, $order, $location, $locationurl, $content, $website, $hours, $phone, $category, $id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Edit Saved";
        } else {
            error_log("Database update failed for explorekl_wte_sf ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update explorekl_wte_sf ID $id: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// ekl wte_sf

// ekl wte c

// --- UPLOAD (Create) wte_c ---
if (isset($_POST['upload_eklwtec'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

    $uploaded_file = $_FILES["fileToUploadeklwtec"]; // Get the file array

    // Validate file upload
    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        error_log("File upload error for explorekl_wte_c: " . $uploaded_file['error']);
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
            // Check file size (e.g., 5MB limit)
            if ($uploaded_file["size"] > 5000000) { // 5MB in bytes
                error_log("File too large for explorekl_wte_c: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid file type for explorekl_wte_c: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Generate unique filename
                    $newfilename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/explorekl/wte/c/"; // Adjust path if necessary
                    $target_file = $target_dir . $newfilename;

                    // Attempt to move uploaded file
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move uploaded file for explorekl_wte_c: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your file.";
                    } else {
                        // Calculate the new order (assuming it's the highest + 1)
                        $order_query = "SELECT MAX(explorekl_wte_c_order) AS max_order FROM explorekl_wte_c";
                        $order_result = mysqli_query($db, $order_query);
                        if (!$order_result) {
                            error_log("Database query failed for max order in explorekl_wte_c: " . mysqli_error($db));
                            $_SESSION['alert_type'] = 'error';
                            $_SESSION['alert_msg'] = "Database error fetching max order.";
                        } else {
                            $order_row = mysqli_fetch_assoc($order_result);
                            $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                            // Insert data into DB
                            $query = "INSERT INTO explorekl_wte_c (explorekl_wte_c_title,explorekl_wte_c_content,explorekl_wte_c_website,explorekl_wte_c_location,explorekl_wte_c_locationurl,explorekl_wte_c_image,explorekl_wte_c_hours,explorekl_wte_c_phone,explorekl_wte_c_category,explorekl_wte_c_order)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($db, $query);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "sssssssssi", $name, $content, $website, $location, $locationurl, $newfilename, $hours, $phone, $category, $new_order);
                                $insert_result = mysqli_stmt_execute($stmt);

                                if ($insert_result) {
                                    // Success: Store success message in session
                                    $_SESSION['alert_type'] = 'success';
                                    $_SESSION['alert_msg'] = "Added New explorekl_wte_c";
                                } else {
                                    error_log("Database insertion failed for explorekl_wte_c: " . mysqli_error($db));
                                    $_SESSION['alert_type'] = 'error';
                                    $_SESSION['alert_msg'] = "Database insertion failed: " . mysqli_error($db);
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                error_log("Database prepare failed for explorekl_wte_c: " . mysqli_error($db));
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
    $redirect_url = $_SERVER['PHP_SELF'] . "?addnew#table11"; // Adjust hash if necessary for WTE_C table
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- ORDER UP wte_c ---
if (isset($_GET['orderupeklwtec'])) {
    $order = (int)$_GET['orderupeklwtec'];
    $explorekl_wte_c_id = (int)$_GET['explorekl_wte_c_id'];
    $order2 = $order + 1;

    $query = "UPDATE explorekl_wte_c SET explorekl_wte_c_order= ? WHERE explorekl_wte_c_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_wte_c_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order up failed for explorekl_wte_c ID $explorekl_wte_c_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_wte_c: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- ORDER DOWN wte_c ---
if (isset($_GET['orderdowneklwtec'])) {
    $order = (int)$_GET['orderdowneklwtec'];
    $explorekl_wte_c_id = (int)$_GET['explorekl_wte_c_id'];
    $order2 = $order - 1;

    $query = "UPDATE explorekl_wte_c SET explorekl_wte_c_order= ? WHERE explorekl_wte_c_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_wte_c_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order down failed for explorekl_wte_c ID $explorekl_wte_c_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_wte_c: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- DELETE wte_c ---
if (isset($_POST['deleteeklwtec'])) {
    $id = (int)$_POST['eklwtecid']; // Cast to integer for security
    $filename = $_POST['imagenameeklwtec'];

    $query = "DELETE FROM explorekl_wte_c WHERE explorekl_wte_c_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // DB deletion successful, now try to delete the file
                $file_path = "../assets/img/explorekl/wte/c/" . $filename; // Adjust path if necessary
                if (file_exists($file_path) && !unlink($file_path)) {
                    error_log("Could not delete file for explorekl_wte_c: " . $file_path);
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
            error_log("Database deletion failed for explorekl_wte_c ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database deletion failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for delete explorekl_wte_c: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing delete query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- EDIT wte_c ---
if (isset($_POST['editeklwtec'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

    $order = (int)$_POST['order']; // Cast to integer
    $id = (int)$_POST['eklwtecid']; // Cast to integer
    $filename = $_POST['imagenameeklwtec'];

    // Check if a new file was uploaded
    $new_filename = $filename; // Default to existing filename
    $uploaded_file = $_FILES["fileToUploadeklwtec"];
    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        // Validate and handle new file upload (similar validation as in upload)
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("New file is not an image for explorekl_wte_c ID $id: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "New file is not an image.";
        } else {
            if ($uploaded_file["size"] > 5000000) {
                error_log("New file too large for explorekl_wte_c ID $id: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your new file is too large.";
            } else {
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid new file type for explorekl_wte_c ID $id: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the new image.";
                } else {
                    // Generate new filename and move file
                    $new_filename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/explorekl/wte/c/"; // Adjust path if necessary
                    $target_file = $target_dir . $new_filename;
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move new uploaded file for explorekl_wte_c ID $id: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your new file.";
                    } else {
                        // Delete old file if it's different from the new one
                        if ($filename && $filename !== $new_filename) {
                            $old_file_path = "../assets/img/explorekl/wte/c/" . $filename; // Adjust path if necessary
                            if (file_exists($old_file_path) && !unlink($old_file_path)) {
                                error_log("Could not delete old file for explorekl_wte_c ID $id: " . $old_file_path);
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
    $query = "UPDATE explorekl_wte_c SET
              explorekl_wte_c_title=?,
              explorekl_wte_c_order=?,
              explorekl_wte_c_location=?,
              explorekl_wte_c_locationurl=?,
              explorekl_wte_c_content=?,
              explorekl_wte_c_website=?,
              explorekl_wte_c_hours=?,
              explorekl_wte_c_phone=?,
              explorekl_wte_c_category=?
              WHERE explorekl_wte_c_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisssssssi", $name, $order, $location, $locationurl, $content, $website, $hours, $phone, $category, $id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Edit Saved";
        } else {
            error_log("Database update failed for explorekl_wte_c ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update explorekl_wte_c ID $id: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// ekl wte c


// ekl wte r

// --- UPLOAD (Create) wte_r ---
if (isset($_POST['upload_eklwter'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

    $uploaded_file = $_FILES["fileToUploadeklwter"]; // Get the file array

    // Validate file upload
    if (!isset($uploaded_file['error']) || $uploaded_file['error'] !== UPLOAD_ERR_OK) {
        error_log("File upload error for explorekl_wte_r: " . $uploaded_file['error']);
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "File upload failed: " . $uploaded_file['error'];
    } else {
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("File is not an image for explorekl_wte_r: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "File is not an image.";
        } else {
            // Check file size (e.g., 5MB limit)
            if ($uploaded_file["size"] > 5000000) { // 5MB in bytes
                error_log("File too large for explorekl_wte_r: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid file type for explorekl_wte_r: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Generate unique filename
                    $newfilename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/explorekl/wte/r/"; // Adjust path if necessary
                    $target_file = $target_dir . $newfilename;

                    // Attempt to move uploaded file
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move uploaded file for explorekl_wte_r: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your file.";
                    } else {
                        // Calculate the new order (assuming it's the highest + 1)
                        $order_query = "SELECT MAX(explorekl_wte_r_order) AS max_order FROM explorekl_wte_r";
                        $order_result = mysqli_query($db, $order_query);
                        if (!$order_result) {
                            error_log("Database query failed for max order in explorekl_wte_r: " . mysqli_error($db));
                            $_SESSION['alert_type'] = 'error';
                            $_SESSION['alert_msg'] = "Database error fetching max order.";
                        } else {
                            $order_row = mysqli_fetch_assoc($order_result);
                            $new_order = isset($order_row['max_order']) ? $order_row['max_order'] + 1 : 1;

                            // Insert data into DB
                            $query = "INSERT INTO explorekl_wte_r (explorekl_wte_r_title,explorekl_wte_r_content,explorekl_wte_r_category,explorekl_wte_r_website,explorekl_wte_r_location,explorekl_wte_r_locationurl,explorekl_wte_r_image,explorekl_wte_r_hours,explorekl_wte_r_phone,explorekl_wte_r_order)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($db, $query);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "sssssssssi", $name, $content, $category, $website, $location, $locationurl, $newfilename, $hours, $phone, $new_order);
                                $insert_result = mysqli_stmt_execute($stmt);

                                if ($insert_result) {
                                    // Success: Store success message in session
                                    $_SESSION['alert_type'] = 'success';
                                    $_SESSION['alert_msg'] = "Added New explorekl_wte_r";
                                } else {
                                    error_log("Database insertion failed for explorekl_wte_r: " . mysqli_error($db));
                                    $_SESSION['alert_type'] = 'error';
                                    $_SESSION['alert_msg'] = "Database insertion failed: " . mysqli_error($db);
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                error_log("Database prepare failed for explorekl_wte_r: " . mysqli_error($db));
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
    $redirect_url = $_SERVER['PHP_SELF'] . "?addnew#table12"; // Adjust hash if necessary for WTE_R table
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- ORDER UP wte_r ---
if (isset($_GET['orderupeklwter'])) {
    $order = (int)$_GET['orderupeklwter'];
    $explorekl_wte_r_id = (int)$_GET['explorekl_wte_r_id'];
    $order2 = $order + 1;

    $query = "UPDATE explorekl_wte_r SET explorekl_wte_r_order= ? WHERE explorekl_wte_r_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_wte_r_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order up failed for explorekl_wte_r ID $explorekl_wte_r_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_wte_r: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- ORDER DOWN wte_r ---
if (isset($_GET['orderdowneklwter'])) {
    $order = (int)$_GET['orderdowneklwter'];
    $explorekl_wte_r_id = (int)$_GET['explorekl_wte_r_id'];
    $order2 = $order - 1;

    $query = "UPDATE explorekl_wte_r SET explorekl_wte_r_order= ? WHERE explorekl_wte_r_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $order2, $explorekl_wte_r_id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Order Changed";
        } else {
            error_log("Database update order down failed for explorekl_wte_r ID $explorekl_wte_r_id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update order explorekl_wte_r: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the GET parameters (prevents accidental repeat on refresh)
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit();
}

// --- DELETE wte_r ---
if (isset($_POST['deleteeklwter'])) {
    $id = (int)$_POST['eklwterid']; // Cast to integer for security
    $filename = $_POST['imagenameeklwter'];

    $query = "DELETE FROM explorekl_wte_r WHERE explorekl_wte_r_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // DB deletion successful, now try to delete the file
                $file_path = "../assets/img/explorekl/wte/r/" . $filename; // Adjust path if necessary
                if (file_exists($file_path) && !unlink($file_path)) {
                    error_log("Could not delete file for explorekl_wte_r: " . $file_path);
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
            error_log("Database deletion failed for explorekl_wte_r ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database deletion failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for delete explorekl_wte_r: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing delete query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// --- EDIT wte_r ---
if (isset($_POST['editeklwter'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
    $locationurl = htmlspecialchars($_POST['locationurl'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $hours = htmlspecialchars($_POST['hours'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

    $order = (int)$_POST['order']; // Cast to integer
    $id = (int)$_POST['eklwterid']; // Cast to integer
    $filename = $_POST['imagenameeklwter'];

    // Check if a new file was uploaded
    $new_filename = $filename; // Default to existing filename
    $uploaded_file = $_FILES["fileToUploadeklwter"];
    if (isset($uploaded_file) && $uploaded_file['error'] == UPLOAD_ERR_OK) {
        // Validate and handle new file upload (similar validation as in upload)
        $imageFileType = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $check = getimagesize($uploaded_file["tmp_name"]);
        if ($check === false) {
            error_log("New file is not an image for explorekl_wte_r ID $id: " . $uploaded_file['name']);
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "New file is not an image.";
        } else {
            if ($uploaded_file["size"] > 5000000) {
                error_log("New file too large for explorekl_wte_r ID $id: " . $uploaded_file['name']);
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_msg'] = "Sorry, your new file is too large.";
            } else {
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    error_log("Invalid new file type for explorekl_wte_r ID $id: " . $uploaded_file['name']);
                    $_SESSION['alert_type'] = 'error';
                    $_SESSION['alert_msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the new image.";
                } else {
                    // Generate new filename and move file
                    $new_filename = time() . '_' . basename($uploaded_file["name"]);
                    $target_dir = "../assets/img/explorekl/wte/r/"; // Adjust path if necessary
                    $target_file = $target_dir . $new_filename;
                    if (!move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                        error_log("Could not move new uploaded file for explorekl_wte_r ID $id: " . $target_file);
                        $_SESSION['alert_type'] = 'error';
                        $_SESSION['alert_msg'] = "Sorry, there was an error uploading your new file.";
                    } else {
                        // Delete old file if it's different from the new one
                        if ($filename && $filename !== $new_filename) {
                            $old_file_path = "../assets/img/explorekl/wte/r/" . $filename; // Adjust path if necessary
                            if (file_exists($old_file_path) && !unlink($old_file_path)) {
                                error_log("Could not delete old file for explorekl_wte_r ID $id: " . $old_file_path);
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
    $query = "UPDATE explorekl_wte_r SET
              explorekl_wte_r_title=?,
              explorekl_wte_r_order=?,
              explorekl_wte_r_location=?,
              explorekl_wte_r_locationurl=?,
              explorekl_wte_r_content=?,
              explorekl_wte_r_category=?,
              explorekl_wte_r_website=?,
              explorekl_wte_r_hours=?,
              explorekl_wte_r_phone=?,
              explorekl_wte_r_category=?
              WHERE explorekl_wte_r_id=?";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sissssssssi", $name, $order, $location, $locationurl, $content, $category, $website, $hours, $phone, $category, $id);
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_msg'] = "Edit Saved";
        } else {
            error_log("Database update failed for explorekl_wte_r ID $id: " . mysqli_error($db));
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_msg'] = "Database update failed: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Database prepare failed for update explorekl_wte_r ID $id: " . mysqli_error($db));
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_msg'] = "Database error preparing update query.";
    }

    // After processing, redirect to clear the POST data
    $redirect_url = $_SERVER['PHP_SELF'];
    header("Location: " . $redirect_url);
    exit(); // Crucial: Stop execution after redirect
}

// ekl wte r
