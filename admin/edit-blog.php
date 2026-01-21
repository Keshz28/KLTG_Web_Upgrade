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

    <title>KLTG ADMIN - Edit Blog</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Blog Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Blog
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable99" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name </th>
                                            <th scope="col">Views</th>
                                            <th scope="col">Base Views</th>
                                            <th scope="col">Edited Views</th>
                                            <th scope="col">
                                                <form action="?refreshblogitem" method="post">

                                                    <button class="dropdown-item" name="refreshblogitem" type="submit">
                                                        <i class="fas fa-plus"></i>
                                                        Refresh
                                                    </button>
                                                </form>
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * , blog_view+blog_view2 FROM blog  ORDER BY blog_id DESC";
                                        $result = mysqli_query($db, $query);

                                        $var_counter = mysqli_num_rows($result);

                                        while ($row = mysqli_fetch_assoc($result)) {

                                            // if (!$row['banner_order']) {s

                                            echo '<tr>';
                                            echo '<th scope="row">' . $var_counter . '</th>';
                                            echo '<td>' . urldecode($row['blog_title']) . '</td>';
                                            echo '<td>' . $row['blog_view'] . '</td>';
                                            echo '<td class="text-center"> ' . $row['blog_view2'] . '
                                            <form action="edit-blog.php#editblog" method="post" enctype="multipart/form-data">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" value="" name="valueupdate" >  
                                                    <input name="hiddenid" value="' . $row['blog_id'] . '"  hidden>
                                                    <div class="input-group-append" >
                                                    <button class="btn btn-primary" type="submit" name="editblogview2"><i class=\'fas fa-plus\'></i></button>
                                                    </div>
                                                </div>
                                            </form
                                                    </td>';
                                            echo '<td>' . $row['blog_view+blog_view2'] . '</td>';
                                            if ($row['blog_viewsettings'] == 0) {
                                                echo '<td><a class="btn btn-danger" href="?enableview=' . $row['blog_postid'] . '" name="enableview">Disabled</a>
                                                </td>';
                                            } else {
                                                echo '<td><a class="btn btn-success" href="?disableview=' . $row['blog_postid'] . '" name="disableview">Enabled</a></td>';
                                            }

                                            echo '</tr>';

                                            $var_counter--;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Blog SITEMAP
                            </h6>
                        </div>
                        <div class="card-body">

                            <?php

                            $query = "SELECT * , blog_view+blog_view2 FROM blog  ORDER BY blog_id DESC";
                            $result = mysqli_query($db, $query);

                            $var_counter = mysqli_num_rows($result);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo htmlspecialchars("<url>");

                                echo htmlspecialchars("<loc>https://www.kltheguide.com.my/blog-details.php?postid=" . $row['blog_postid'] . "</loc>");
                                echo htmlspecialchars("<lastmod>2023-07-20T13:12:39+00:00</lastmod><priority>0.80</priority></url>");
                                echo "<br/>";
                                // if (!$row['banner_order']) {s
                            }
                            ?>

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



    <!-- Addnew Modal-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="edit-index.php" method="post" enctype="multipart/form-data">

                    <div class="modal-body" id="tagdiv2">

                        Select image banner to upload:
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <!-- <input type="submit" value="Upload Image" name="upload_banner"> -->

                    </div>
                    <div class="modal-footer">
                        <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_banner">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal-->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="edit-index.php" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="exampleFormControlTextarea8" rows="3" name="id" hidden></input>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">File name</label>
                            <input class="form-control" id="exampleFormControlTextarea3" rows="3"
                                name="filename"></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Name</label>
                            <input class="form-control" id="exampleFormControlTextarea1" rows="3" name="name"></input>
                        </div>


                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Order</label>
                            <input class="form-control" id="exampleFormControlTextarea2" rows="3" name="order"></input>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                        <button type="button" class="btn btn-danger " disabled>Delete</button>
                        <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="editbanner"></input> -->
                        <button class="btn btn-primary" type="submit" value="Upload Image" name="editbanner">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New Recommendation Modal-->
    <div class="modal fade" id="newrecommendation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <!-- <form action="edit-index.php" method="post" enctype="multipart/form-data"> -->

                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Post ID</th>
                                    <th scope="col">

                                    </th>

                                </tr>
                            </thead>

                            <tbody id="recommendationtable">

                            </tbody>
                        </table>
                    </div>

                    <!-- <input class="form-control" id="exampleFormControlTextarea8" rows="3" name="id" hidden></input>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">File name</label>
                            <input class="form-control" id="exampleFormControlTextarea3" rows="3"
                                name="filename"></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Name</label>
                            <input class="form-control" id="exampleFormControlTextarea1" rows="3" name="name"></input>
                        </div>


                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Order</label>
                            <input class="form-control" id="exampleFormControlTextarea2" rows="3" name="order"></input>
                        </div> -->

                </div>
                <div class="modal-footer">
                    <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                    <!-- <button type="button" class="btn btn-danger " disabled>Delete</button> -->
                    <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="editbanner"></input> -->
                    <!-- <button class="btn btn-primary" type="submit" value="Upload Image" name="editbanner">Save
                            Changes</button> -->

                </div>
                <!-- </form> -->
            </div>
        </div>
    </div>



    <!-- edit recommendation modal  -->
    <div class="modal fade" id="editrecommend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Recommendation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="edit-index.php?editrecommend" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="hiddenid" name="hiddenid" hidden></input>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Name</label>
                            <input class="form-control" id="recommendname" rows="3" name="recommendname"
                                readonly></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Category</label>
                            <input class="form-control" id="recommendcategory" rows="3"
                                name="recommendcategory"></input>
                        </div>


                        <!-- <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Order</label>
                            <input class="form-control" id="exampleFormControlTextarea2" rows="3" name="order"></input>
                        </div> -->

                    </div>
                    <div class="modal-footer">
                        <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                        <button class="btn btn-danger" type="submit" name="deleterecommend">Delete</button>
                        <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="editbanner"></input> -->
                        <button class="btn btn-primary" type="submit" name="editrecommend">Save
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
                Test
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
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/editblog.js"></script>
    <script>
        document.getElementById("editnav").classList.add('active');
    </script>

    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>