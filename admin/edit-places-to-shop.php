<?php
/* ============================================================================
 *                         edit-places-to-shop.php
 *
 *   Admin editor — Where to Shop page.
 *   Pairs with pagefunctions/edit-places-to-shop.php.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
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

// NOTE: this file used to carry its own copy of the add/edit/delete handler
// here. It was dead code: include('functions.php') above already pulls in
// pagefunctions/edit-pts.php, whose branches all end in header()+exit(), so
// the copy could never run. Removed so there is one place to fix.
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
                                            <th scope="col">Map Coords</th>
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
                                            echo '<td id="mapcoords-' . $row['place_shop_id'] . '">' . htmlspecialchars((string) $row['place_shop_mapcoords'], ENT_QUOTES, 'UTF-8') . '</td>';
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
                            <input type="text" class="form-control form-control-user" name="mapcoords"
                                placeholder="Map Coordinates — e.g. 3.1580207,101.7116671 (optional)">
                            <small class="form-text text-muted">Sets the exact &quot;View on Map&quot; pin. Right-click the place in Google Maps &rarr; click the coordinates to copy, or just paste the whole map link.</small>
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
                        <div class="form-group">
                            <label for="mapcoordspts">Map Coordinates (lat,lng)</label>
                            <input type="text" class="form-control" id="mapcoordspts" name="mapcoords"
                                placeholder="e.g. 3.1580207,101.7116671">
                            <small class="form-text text-muted">Sets the exact &quot;View on Map&quot; pin. Right-click the place in Google Maps &rarr; click the coordinates to copy, or just paste the whole map link.</small>
                        </div>
                        <div class="form-group">
                            <label>Current image</label><br>
                            <img id="previewpts" src="" alt="Current image"
                                class="img-thumbnail d-none" style="max-height:120px">
                            <label for="fileToUploadptsedit" class="mt-2 mb-1 d-block">Replace image <span class="text-muted">(leave empty to keep the current one)</span></label>
                            <input type="file" class="form-control-file" id="fileToUploadptsedit"
                                name="fileToUpload" accept="image/*">
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

    <!-- Drag-and-drop row reordering -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script src="js/dragreorder.js"></script>
</body>

</html>