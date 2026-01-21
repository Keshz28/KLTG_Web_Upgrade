<?php
include('functions.php');

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit(); // Stop execution after redirect
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit(); // Stop execution after redirect
}

// --- PROCESSING LOGIC (Moved from external file) ---

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
    $redirect_url = $_SERVER['PHP_SELF'] . "?addnew"; // Redirects back to the main page, potentially with an anchor
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>KLTG ADMIN - Edit Places To Shop</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include('nav.php'); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include('topnav.php'); ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Places To Shop Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales pts -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Places To Shop
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#exampleModal2">
                                                    <i class="fas fa-plus"></i>
                                                    New
                                                </a>
                                            </th>
                                            <th scope="col">Name</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Location URL</th>
                                            <th scope="col">Website</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM place_shop ORDER BY place_shop_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="order-' . $row['place_shop_id'] . '" scope="row">' . $row['place_shop_order'] . '</th>';
                                            echo '<td class="text-center">
                                            <a href="#" class="" onclick="editmodalpts(' . $row['place_shop_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                            <a href="?orderupPTS=' . $row['place_shop_order'] . '&place_shop_id=' . $row['place_shop_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                            <a href="?orderdownPTS=' . $row['place_shop_order'] . '&place_shop_id=' . $row['place_shop_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                            </td>';
                                            echo '<td id="name-' . $row['place_shop_id'] . '">' . htmlspecialchars(urldecode($row['place_shop_title']), ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="filename-' . $row['place_shop_id'] . '">' . htmlspecialchars($row['place_shop_image'], ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="location-' . $row['place_shop_id'] . '">' . htmlspecialchars(urldecode($row['place_shop_location']), ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="locationurl-' . $row['place_shop_id'] . '">' . htmlspecialchars(urldecode($row['place_shop_locationurl']), ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="website-' . $row['place_shop_id'] . '">' . htmlspecialchars(urldecode($row['place_shop_website']), ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="content-' . $row['place_shop_id'] . '">' . htmlspecialchars(urldecode($row['place_shop_content']), ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="hours-' . $row['place_shop_id'] . '">' . htmlspecialchars(urldecode($row['place_shop_hours']), ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '<td id="phone-' . $row['place_shop_id'] . '">' . htmlspecialchars($row['place_shop_phone'], ENT_QUOTES, 'UTF-8') . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New PTS Modal-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Places To Shop</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew" method="post" enctype="multipart/form-data" id="mthc">
                    <div class="modal-body" id="tagdiv2">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="location"
                                placeholder="Location" name="location" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="website"
                                placeholder="Website" name="website" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="hours"
                                placeholder="Operating Hours" name="hours" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="phone" placeholder="Phone"
                                name="phone" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_pts">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal PTS-->
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Places To Shop</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="namepts" name="name">
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <textarea type="text" class="form-control" id="locationpts" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurl">Location URL</label>
                            <input type="text" class="form-control" id="locationurlpts" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="locationurl">Website</label>
                            <input type="text" class="form-control" id="websitepts" name="website">
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="contentpts" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hours">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourspts" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phonepts" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="order">Order</label>
                            <input type="text" class="form-control" id="orderpts" name="order">
                        </div>
                        <input class="form-control" id="ptsid" name="ptsid" hidden></input>
                        <input class="form-control" id="imagenamepts" name="imagenamepts" hidden></input>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger " name="deletepts">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editpts">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="liveToast" class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header">
                <img src="../assets/img/favicon-32x32.png" class="rounded mr-2" alt="...">
                <strong class="mr-auto" id="toastTitle">Message</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body" id="toast-body">Default Message</div>
        </div>
    </div>
    <!-- End Toast -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/editpts.js"></script>
    <script>
        document.getElementById("editnav").classList.add('active');
    </script>
    <?php include('errors2.php'); ?>

    <script>
        // Check for alert message stored in session via PHP and show alert (Toast)
        <?php if (isset($_SESSION['alert_msg']) && isset($_SESSION['alert_type'])): ?>
            $(document).ready(function() {
                const alertType = "<?php echo $_SESSION['alert_type']; ?>";
                const alertMsg = "<?php echo addslashes($_SESSION['alert_msg']); ?>"; // Escape quotes for JS

                let toastTitle = 'Message';
                let toastBody = alertMsg; // Use the message from the session
                let toastClass = 'bg-info text-white'; // Default class

                if (alertType === 'success') {
                    toastTitle = 'Success!';
                    toastClass = 'bg-success text-white';
                } else if (alertType === 'warning') {
                    toastTitle = 'Warning!';
                    toastClass = 'bg-warning text-white';
                } else if (alertType === 'error') {
                    toastTitle = 'Error!';
                    toastClass = 'bg-danger text-white';
                }

                // Get the toast element
                const toastElement = document.getElementById('liveToast');
                const titleElement = document.getElementById('toastTitle');
                const bodyElement = document.getElementById('toast-body');

                // Set the title and body
                titleElement.textContent = toastTitle;
                bodyElement.textContent = toastBody;
                // Remove any previous classes and add the new one
                toastElement.className = toastElement.className.replace(/bg-\w+\s+text-\w+/g, '');
                toastElement.classList.add(toastClass);

                // Show the toast using Bootstrap's toast method
                $('#liveToast').toast('show');

            });
            <?php
            // Clear the session variables immediately after outputting the JS alert code
            // This ensures it only shows once.
            unset($_SESSION['alert_msg']);
            unset($_SESSION['alert_type']);
            ?>
        <?php endif; ?>
    </script>

</body>

</html>