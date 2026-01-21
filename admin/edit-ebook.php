<?php include('functions.php');


if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
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

    <title>KLTG ADMIN - Edit E-book</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

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
                    <h1 class="h3 mb-2 text-gray-800">E-book Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4" id="editebook">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">E-book
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable55" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Filename</th>
                                            <th scope="col">Cover</th>
                                            <th scope="col">URL</th>
                                            <th scope="col">Views</th>
                                            <th scope="col">Base Views</th>
                                            <th scope="col">Edited Views</th>

                                            <th scope="col">

                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#addebook">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                        </button>
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT *, ebook_view+ebook_view2 FROM ebook  ORDER BY ebook_id DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            // if (!$row['banner_order']) {
                                        
                                            echo '<tr>';
                                            echo '<th scope="row">' . $row['ebook_id'] . '</th>';
                                            echo '<td id="name-' . $row['ebook_id'] . '">' . $row['ebook_name'] . '</td>';
                                            echo '<td id="category-' . $row['ebook_id'] . '">' . $row['ebook_category'] . '</td>';
                                            echo '<td id="filename-' . $row['ebook_id'] . '">' . urldecode($row['ebook_filename']) . '</td>';
                                            echo '<td id="image-' . $row['ebook_id'] . '">' . $row['ebook_image'] . '</td>';
                                            echo '<td id="url-' . $row['ebook_id'] . '">' . $row['ebook_url'] . '</td>';
                                            echo '<td>' . $row['ebook_view'] . '</td>';
                                            echo '<form action="?editebook" method="post" enctype="multipart/form-data"><td>' . $row['ebook_view2'] . '
                                             
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" value="" name="valueupdate" >  
                                                    <input name="hiddenid" value="' . $row['ebook_id'] . '"  hidden>
                                                    <div class="input-group-append" >
                                                        <button class="btn btn-primary" type="submit" name="editebookview2"><i class=\'fas fa-plus\'></i></button>
                                                    </div>
                                                </div>

                                            </td></form>';
                                            echo '<td>' . $row['ebook_view+ebook_view2'] . '</td>';



                                            if ($row['ebook_viewsettings'] == 0) {
                                                echo '<td><a class="btn btn-danger" href="edit-ebook.php?enableviewebook=' . $row['ebook_id'] . '" name="enableviewebook">Disabled</a>
                                                <a href="#" class="" onclick="editmodalebook(' . $row['ebook_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a>
                                                </td>';

                                            } else {
                                                echo '<td><a class="btn btn-success" href="edit-ebook.php?disableviewebook=' . $row['ebook_id'] . '" name="disableviewebook">Enabled</a>
                                                <a href="#" class="" onclick="editmodal(' . $row['ebook_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a></td>';

                                            }
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





    <!-- add new book modal  -->
    <div class="modal fade" id="addebook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Add new book</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addebook" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="hiddenid" name="hiddenid" hidden></input>
                        <div class="mb-3">
                            <label for="ebook_name" class="form-label">Name</label>
                            <input class="form-control" id="ebook_name" rows="3" name="ebook_name"></input>
                        </div>

                        <div class="form-group">
                            <label for="ebook_category">Category</label>
                            <select class="form-control" id="ebook_category" name="ebook_category">
                                <option value="kltg">KL The Guide</option>
                                <option value="kltgs">KL The Guide Specials</option>
                                <option value="kv4l">Klang Valley 4 Locals</option>
                                <option value="mktg">Melaka The Guide</option>
                                <option value="tptg">Taiping The Guide</option>
                                <option value="uztg">Uzbekistan The Guide</option>
                                <option value="kntg">Keningau The Guide</option>
                                <option value="twtg">Tawau The Guide</option>
                                <option value="tbtg">Tambunan The Guide</option>
                                <option value="hstg">Hulu Selangor The Guide</option>
                                <option value="prtg">Perak The Guide</option>
                                <option value="sbtg">Seremban The Guide</option>
                                <option value="kstg">Kuala Selangor The Guide</option>
                                <option value="klgt">Kuala Langat The Guide</option>
                                <option value="kztg">Kazakhstan The Guide</option>
                                <option value="amspa">AMSPA</option>
<option value="kgtg">Klang The Guide</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload2" class="form-label">PDF File</label><br />
                            <input type="file" name="fileToUpload2" id="fileToUpload2">
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload3" class="form-label">Cover</label><br />
                            <input type="file" name="fileToUpload3" id="fileToUpload3">
                        </div>




                    </div>
                    <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" name="addebook2">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- edit book modal  -->
    <div class="modal fade" id="editebook2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit E-book</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?editebook" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="hiddenid2" name="hiddenid2" hidden></input>
                        <div class="mb-3">
                            <label for="ebook_name2" class="form-label">Name</label>
                            <input class="form-control" id="ebook_name2" name="ebook_name"></input>
                        </div>

                        <div class="mb-3">
                            <label for="ebook_url" class="form-label">Bit.ly Link</label>
                            <input class="form-control" id="ebook_url" rows="3" name="ebook_url"></input>
                        </div>

                        <div class="form-group">
                            <label for="ebook_category2">Category</label>
                            <select class="form-control" id="ebook_category2" name="ebook_category">
                                <option value="kltg">KL The Guide</option>
                                <option value="kv4l">Klang Valley 4 Locals</option>
                                <option value="mktg">Melaka The Guide</option>
                                <option value="tptg">Taiping The Guide</option>
                                <option value="uztg">Uzbekistan The Guide</option>
                                <option value="kntg">Keningau The Guide</option>
                                <option value="twtg">Tawau The Guide</option>
                                <option value="tbtg">Tambunan The Guide</option>
                                <option value="hstg">Hulu Selangor The Guide</option>
                                <option value="prtg">Perak The Guide</option>
                                <option value="sbtg">Seremban The Guide</option>
                                <option value="kstg">Kuala Selangor The Guide</option>
                                <option value="klgt">Kuala Langat The Guide</option>
                                <option value="kztg">Kazakhstan The Guide</option>
                                <option value="amspa">AMSPA</option>


                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload2" class="form-label">PDF File</label><br />
                            <input type="text" name="fileToUpload2a" id="fileToUpload2a" class="form-control" readonly>
                            <input type="file" name="fileToUpload2b" id="fileToUpload2b">
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload3" class="form-label">Cover</label><br />
                            <input type="text" name="fileToUpload3a" id="fileToUpload3a" class="form-control" readonly>
                            <input type="file" name="fileToUpload3b" id="fileToUpload3b">
                        </div>




                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit" name="deleteebook2">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editebook2">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Toast -->
    <div class="toast-container position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;"
        id="toast11">
        <div id="liveToast" class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
            <div class="toast-header">
                <img src="../assets/img/favicon-32x32.png" class="rounded mr-2" alt="...">
                <strong class="mr-auto">Bluedale</strong>
                <!-- <small>11 mins ago</small> -->
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body" id="toast-body">

            </div>
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
    <script src="js/editebook.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>