<?php include('functions.php');

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit;
}

// Define the upload directory for vouchers globally
$upload_dir = __DIR__ . "/../assets/img/voucher/";  // Absolute path to the voucher directory

// Handle Add New Voucher
if (isset($_POST['upload_voucher'])) {
    // Handle file upload
    $voucher_image = $_FILES['fileToUpload']['name'];
    $target = $upload_dir . basename($voucher_image);

    // Get form data
    $voucher_title = mysqli_real_escape_string($db, $_POST['title']);
    $voucher_content = mysqli_real_escape_string($db, $_POST['voucher']);
    $voucher_expiry_date = mysqli_real_escape_string($db, $_POST['expiry_date']);

    // Insert into the database
    $query = "INSERT INTO voucher (voucher_title, voucher, voucher_image, voucher_expiry_date) 
              VALUES ('$voucher_title', '$voucher_content', '$voucher_image', '$voucher_expiry_date')";

    if (mysqli_query($db, $query)) {
        // Move uploaded image to target directory
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target)) {
            echo "<div class='alert alert-success'>Voucher added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to upload image.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($db) . "</div>";
    }
}

// Handle Edit Voucher
if (isset($_POST['edit_voucher'])) {
    $voucher_id = mysqli_real_escape_string($db, $_POST['voucher_id']);
    $voucher_title = mysqli_real_escape_string($db, $_POST['title']);
    $voucher_content = mysqli_real_escape_string($db, $_POST['voucher']);
    $voucher_expiry_date = mysqli_real_escape_string($db, $_POST['expiry_date']);

    // Handle file upload if there's a new file
    if (!empty($_FILES['fileToUploadEdit']['name'])) {
        $voucher_image = $_FILES['fileToUploadEdit']['name'];
        $target = $upload_dir . basename($voucher_image);

        if (move_uploaded_file($_FILES['fileToUploadEdit']['tmp_name'], $target)) {
            // Update the voucher with the new image
            $query = "UPDATE voucher 
                      SET voucher_title = '$voucher_title', voucher = '$voucher_content', voucher_image = '$voucher_image', voucher_expiry_date = '$voucher_expiry_date' 
                      WHERE voucher_id = '$voucher_id'";
        } else {
            echo "<div class='alert alert-danger'>Failed to upload new image.</div>";
            exit; // Stop execution if image upload fails
        }
    } else {
        // No new image uploaded, keep the old one
        $query = "UPDATE voucher 
                  SET voucher_title = '$voucher_title', voucher = '$voucher_content', voucher_expiry_date = '$voucher_expiry_date' 
                  WHERE voucher_id = '$voucher_id'";
    }

    // Execute the update query
    if (mysqli_query($db, $query)) {
        echo "<div class='alert alert-success'>Voucher updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating voucher: " . mysqli_error($db) . "</div>";
    }
}

// Handle Delete Voucher
if (isset($_POST['delete_voucher'])) {
    $voucher_id = mysqli_real_escape_string($db, $_POST['voucher_id']);

    // Delete the voucher entry from the database
    $query = "DELETE FROM voucher WHERE voucher_id = '$voucher_id'";

    if (mysqli_query($db, $query)) {
        echo "<div class='alert alert-success'>Voucher deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting voucher: " . mysqli_error($db) . "</div>";
    }
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

    <title>KLTG ADMIN - Vouchers</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Vouchers Page</h1>
                    <p class="mb-4">Manage vouchers below. Use the buttons to add, edit, or delete vouchers.</p>

                    <!-- DataTales -->
                    <div class="card shadow mb-4" id="voucher">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Voucher Management</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal" data-target="#addVoucherModal">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            </th>
                                            <th scope="col">Voucher Title</th>
                                            <th scope="col">Voucher Description</th>
                                            <th scope="col">Voucher Image File Name</th>
                                            <th scope="col">Voucher Expiry Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM voucher ORDER BY voucher_id DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="order-' . $row['voucher_id'] . '" scope="row">' . $row['voucher_id'] . '</th>';
                                            echo '<td class="text-center">
                                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#editVoucherModal" 
                                                   onclick="editmodal(' . $row['voucher_id'] . ');">Edit</a><br>
                                                <form action="edit-voucher.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="voucher_id" value="' . $row['voucher_id'] . '">
                                                    <button type="submit" name="delete_voucher" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>';
                                            echo '<td id="title-' . $row['voucher_id'] . '">' . urldecode($row['voucher_title']) . '</td>';
                                            echo '<td id="voucher-' . $row['voucher_id'] . '">' . htmlspecialchars($row['voucher']) . '</td>';
                                            echo '<td id="image-' . $row['voucher_id'] . '">' . $row['voucher_image'] . '</td>';
                                            echo '<td id="expiry-' . $row['voucher_id'] . '">' . $row['voucher_expiry_date'] . '</td>';
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
                        <span>Copyright &copy; Your Website 2024</span>
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

    <!-- Add New Voucher Modal-->
    <div class="modal fade" id="addVoucherModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Voucher</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="edit-voucher.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control form-control-user" id="title" placeholder="Voucher Title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="voucher">Voucher Content</label>
                            <textarea class="form-control form-control-user" id="voucher" placeholder="Voucher" name="voucher"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" class="form-control form-control-user" id="expiry_date" name="expiry_date">
                        </div>
                        <div class="form-group">
                            <label for="fileToUpload">Select image to upload:</label>
                            <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" name="upload_voucher">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Voucher Modal-->
    <div class="modal fade" id="editVoucherModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Voucher</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="edit-voucher.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="voucher_id">Id</label>
                            <input type="text" class="form-control" id="voucher_id" name="voucher_id" readonly>
                        </div>
                        <div class="form-group">
                            <label for="titleEdit">Title</label>
                            <input type="text" class="form-control form-control-user" id="titleEdit" placeholder="Title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="voucherEdit">Voucher</label>
                            <textarea class="form-control form-control-user" id="voucherEdit" placeholder="Voucher" name="voucher"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="expiry_dateEdit">Expiry Date</label>
                            <input type="date" class="form-control form-control-user" id="expiry_dateEdit" name="expiry_date">
                        </div>
                        <div class="form-group">
                            <label for="fileToUploadEdit">Select image to upload:</label>
                            <input type="file" class="form-control-file" id="fileToUploadEdit" name="fileToUploadEdit">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit" name="delete_voucher">Delete</button>
                        <button class="btn btn-primary" type="submit" name="edit_voucher">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/editvoucher.js"></script>

    <script>
    // JavaScript to populate the edit modal with voucher data
    function editmodal(voucher_id) {
        var title = document.getElementById('title-' + voucher_id).innerText;
        var voucher = document.getElementById('voucher-' + voucher_id).innerText;
        var expiry_date = document.getElementById('expiry-' + voucher_id).innerText;

        // Set the modal fields with this data
        document.getElementById('voucher_id').value = voucher_id;
        document.getElementById('titleEdit').value = title;
        document.getElementById('voucherEdit').value = voucher;
        document.getElementById('expiry_dateEdit').value = expiry_date;
    }
    </script>
</body>

</html>
