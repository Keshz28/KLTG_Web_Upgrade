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


// $query = "SELECT  url, sum(views) ,dateent FROM `pageview` GROUP BY url ";
// $result = mysqli_query($db, $query);
// while ($row = mysqli_fetch_assoc($result)) {
//     // echo $row['url'];
//     $url = $row['url'];
//     $views = $row['sum(views)'];
//     $views2 = 0;
//     // echo '<br>';

//     $query2 = "INSERT INTO pageview2 (url, views, views2) 
//     VALUES('$url', '$views', '$views2') ON DUPLICATE KEY UPDATE views2=views2 , views=$views";
//     mysqli_query($db, $query2);

//     // echo $query2;
// // echo '<br>';


// }

// $query = "INSERT INTO pageview2 SELECT id, url, sum(views), 0 ,dateent from pageview group by url  ON DUPLICATE KEY UPDATE   views2 = views2,views=sum(views)    ;";
// $update = mysqli_query($db, $query);
// echo $query;
// if ($update) {
//     // echo "Record updated successfully";


// } else {
//     array_push($errors2, "Error updating record: " . mysqli_error($db));

// }

$totalpageviews = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KLTG ADMIN - Blog Views</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Blog Views</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>



                    <!-- Content Row -->

                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-xl-6 col-lg-6 mb-4">


                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Blog Views</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $query2 = "SELECT sum(blog_view)+sum(blog_view2) FROM `blog`";
                                    $result = mysqli_query($db, $query2);
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        $totalviewsoverall = $row['sum(blog_view)+sum(blog_view2)'];
                                    }   
                                    ?>

                                    <?php
                                    $query = "SELECT *, blog_view+blog_view2 FROM `blog` ORDER BY blog_view+blog_view2 DESC LIMIT 5";
                                    $result = mysqli_query($db, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<h4 class="small font-weight-bold">' . urldecode($row['blog_title']) . ' <span class="float-right">' . $row['blog_view+blog_view2'] . '</span></h4>';
                                        echo '<div class="progress mb-4">';
                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: ' . $row['blog_view+blog_view2'] / $totalviewsoverall * 100 . '%" aria-valuenow="' . $row['blog_view'] . '" aria-valuemin="0" aria-valuemax="100"></div></div>';



                                    }
                                    ?>
                                </div>
                            </div>

                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-6 col-lg-6 h-100 w-100">
                            <div class="card shadow mb-4 h-100">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Blog Views</h6>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body h-100 w-100">
                                    <div class="row h-100 w-100">
                                        <div class="col h-100">
                                            <?php

                                            $query = "SELECT *, sum(blog_view)+sum(blog_view2) FROM `blog`  ";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {

                                            echo'<p class="font-weight-bold text-center " style="font-size:10vh">
                                               '. $row['sum(blog_view)+sum(blog_view2)'].'
                                                </p>';
                                            }

                                            ?>
                                            <!-- <p class="font-weight-bold" style="font-size:10vh">
                                                <?php echo $totalpageviews; ?>
                                            </p> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Blog View
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Blog Name</th>
                                            <th scope="col">Views</th>
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT *, blog_view+blog_view2   FROM `blog` ORDER BY blog_id DESC  ";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {



                                            echo '<tr>';
                                            echo '<td ">' . urldecode($row['blog_title']) . '</td>';
                                            echo '<td ">' . $row['blog_view+blog_view2'] . '</td>';
                                            // echo '<td><a href="#" class="" onclick="newmodal(' . $row['banner_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a></td>';
                                            echo '</tr>';
                                            $totalpageviews += $row['blog_view+blog_view2'];

                                        }

                                        ?>
                                    </tbody>
                                </table>
                                <div class=row>

                                    <div class=col-8>
                                    </div>
                                    <div class=col-4>
                                        <p class="font-weight-bold">Total Views :
                                            <?php echo $totalpageviews; ?>
                                        </p>

                                    </div>
                                </div>

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
                        <span>Copyright &copy; Your Website 2021</span>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


    <script src="js/pageviews.js"></script>
    <script>document.getElementById("blognav").classList.add('active');</script>

</body>

</html>