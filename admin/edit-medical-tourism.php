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

    <title>KLTG ADMIN - Edit Medical Tourism</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Medical Tourism Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Navigation
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">

                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#addnewnavmt">
                                                    <i class="fas fa-plus"></i>
                                                    New
                                                </a>
                                            </th>
                                            <th scope="col">Order</th>
                                            <th scope="col">Filename</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Display</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM medical_tourism_nav ORDER BY orderof ASC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) { ?>

                                            <tr>
                                                <td>
                                                    <a href="#" class="" id="">
                                                        <form method="post">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <button class="dropdown-item text-center text-primary" href="#" name="modalshowaddnavmt">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                        </form>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo $row['orderof'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['filename'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['name'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['display'] == "1" ? "Yes" : "No" ?>
                                                </td>
                                            </tr>

                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales HC -->
                    <div class="card shadow mb-4" id="hcdt">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Healthcare
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
                                            <th scope="col">Content</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM medical_tourism_hc ORDER BY medical_tourism_hc_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="orderhc-' . $row['medical_tourism_hc_id'] . '" scope="row">' . $row['medical_tourism_hc_order'] . '</th>';
                                            echo '<td class="text-center">
                                            <a href="#" class="" onclick="editmodalhc(' . $row['medical_tourism_hc_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                            <a href="?orderupMTH=' . $row['medical_tourism_hc_order'] . '&medical_tourism_hc_id=' . $row['medical_tourism_hc_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                            <a href="?orderdownMTH=' . $row['medical_tourism_hc_order'] . '&medical_tourism_hc_id=' . $row['medical_tourism_hc_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                            </td>';
                                            echo '<td id="namehc-' . $row['medical_tourism_hc_id'] . '">' . urldecode($row['medical_tourism_hc_title']) . '</td>';
                                            echo '<td id="filenamehc-' . $row['medical_tourism_hc_id'] . '">' . $row['medical_tourism_hc_image'] . '</td>';
                                            echo '<td id="locationhc-' . $row['medical_tourism_hc_id'] . '">' . urldecode($row['medical_tourism_hc_location']) . '</td>';
                                            echo '<td id="locationurlhc-' . $row['medical_tourism_hc_id'] . '">' . urldecode($row['medical_tourism_hc_locationurl']) . '</td>';
                                            echo '<td id="contenthc-' . $row['medical_tourism_hc_id'] . '">' . urldecode($row['medical_tourism_hc_content']) . '</td>';
                                            echo '<td id="hourshc-' . $row['medical_tourism_hc_id'] . '">' . urldecode($row['medical_tourism_hc_hours']) . '</td>';
                                            echo '<td id="phonehc-' . $row['medical_tourism_hc_id'] . '">' . $row['medical_tourism_hc_phone'] . '</td>';



                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <!-- DataTales DTL -->
                    <div class="card shadow mb-4" id="dtldt">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Dental
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#exampleModal3">
                                                    <i class="fas fa-plus"></i>
                                                    New
                                                </a>
                                            </th>
                                            <th scope="col">Name</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Location URL</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM medical_tourism_dtl ORDER BY medical_tourism_dtl_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="orderdtl-' . $row['medical_tourism_dtl_id'] . '" scope="row">' . $row['medical_tourism_dtl_order'] . '</th>';

                                            echo '<td class="text-center">
                                                <a href="#" class="" onclick="editmodaldtl(' . $row['medical_tourism_dtl_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                <a href="?orderupMTDTL=' . $row['medical_tourism_dtl_order'] . '&medical_tourism_dtl_id=' . $row['medical_tourism_dtl_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                <a href="?orderdownMTDTL=' . $row['medical_tourism_dtl_order'] . '&medical_tourism_dtl_id=' . $row['medical_tourism_dtl_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                  </td>';
                                            echo '<td id="namedtl-' . $row['medical_tourism_dtl_id'] . '">' . urldecode($row['medical_tourism_dtl_title']) . '</td>';
                                            echo '<td id="filenamedtl-' . $row['medical_tourism_dtl_id'] . '">' . urldecode($row['medical_tourism_dtl_image']) . '</td>';
                                            echo '<td id="locationdtl-' . $row['medical_tourism_dtl_id'] . '">' . urldecode($row['medical_tourism_dtl_location']) . '</td>';
                                            echo '<td id="locationurldtl-' . $row['medical_tourism_dtl_id'] . '">' . urldecode($row['medical_tourism_dtl_locationurl']) . '</td>';
                                            echo '<td id="contentdtl-' . $row['medical_tourism_dtl_id'] . '">' . urldecode($row['medical_tourism_dtl_content']) . '</td>';
                                            echo '<td id="hoursdtl-' . $row['medical_tourism_dtl_id'] . '">' . urldecode($row['medical_tourism_dtl_hours']) . '</td>';
                                            echo '<td id="phonedtl-' . $row['medical_tourism_dtl_id'] . '">' . $row['medical_tourism_dtl_phone'] . '</td>';

                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales DER -->
                    <div class="card shadow mb-4" id="derdt">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Dermatologist
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#exampleModal4">
                                                    <i class="fas fa-plus"></i>
                                                    New
                                                </a>
                                            </th>
                                            <th scope="col">Name</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Location URL</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM medical_tourism_der ORDER BY medical_tourism_der_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="orderder-' . $row['medical_tourism_der_id'] . '" scope="row">' . $row['medical_tourism_der_order'] . '</th>';

                                            echo '<td class="text-center">
                                                <a href="#" class="" onclick="editmodalder(' . $row['medical_tourism_der_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                <a href="?orderupMTDER=' . $row['medical_tourism_der_order'] . '&medical_tourism_der_id=' . $row['medical_tourism_der_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                <a href="?orderdownMTDER=' . $row['medical_tourism_der_order'] . '&medical_tourism_der_id=' . $row['medical_tourism_der_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                  </td>';
                                            echo '<td id="nameder-' . $row['medical_tourism_der_id'] . '">' . urldecode($row['medical_tourism_der_title']) . '</td>';
                                            echo '<td id="filenameder-' . $row['medical_tourism_der_id'] . '">' . urldecode($row['medical_tourism_der_image']) . '</td>';
                                            echo '<td id="locationder-' . $row['medical_tourism_der_id'] . '">' . urldecode($row['medical_tourism_der_location']) . '</td>';
                                            echo '<td id="locationurlder-' . $row['medical_tourism_der_id'] . '">' . urldecode($row['medical_tourism_der_locationurl']) . '</td>';
                                            echo '<td id="contentder-' . $row['medical_tourism_der_id'] . '">' . urldecode($row['medical_tourism_der_content']) . '</td>';
                                            echo '<td id="hoursder-' . $row['medical_tourism_der_id'] . '">' . urldecode($row['medical_tourism_der_hours']) . '</td>';
                                            echo '<td id="phoneder-' . $row['medical_tourism_der_id'] . '">' . $row['medical_tourism_der_phone'] . '</td>';

                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales OPH -->
                    <div class="card shadow mb-4" id="ophdt">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Ophthalmologist
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#exampleModal5">
                                                    <i class="fas fa-plus"></i>
                                                    New
                                                </a>
                                            </th>
                                            <th scope="col">Name</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Location URL</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM medical_tourism_oph ORDER BY medical_tourism_oph_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="orderoph-' . $row['medical_tourism_oph_id'] . '" scope="row">' . $row['medical_tourism_oph_order'] . '</th>';

                                            echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaloph(' . $row['medical_tourism_oph_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupMToph=' . $row['medical_tourism_oph_order'] . '&medical_tourism_oph_id=' . $row['medical_tourism_oph_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdownMToph=' . $row['medical_tourism_oph_order'] . '&medical_tourism_oph_id=' . $row['medical_tourism_oph_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                            echo '<td id="nameoph-' . $row['medical_tourism_oph_id'] . '">' . urldecode($row['medical_tourism_oph_title']) . '</td>';
                                            echo '<td id="filenameoph-' . $row['medical_tourism_oph_id'] . '">' . urldecode($row['medical_tourism_oph_image']) . '</td>';
                                            echo '<td id="locationoph-' . $row['medical_tourism_oph_id'] . '">' . urldecode($row['medical_tourism_oph_location']) . '</td>';
                                            echo '<td id="locationurloph-' . $row['medical_tourism_oph_id'] . '">' . urldecode($row['medical_tourism_oph_locationurl']) . '</td>';
                                            echo '<td id="contentoph-' . $row['medical_tourism_oph_id'] . '">' . urldecode($row['medical_tourism_oph_content']) . '</td>';
                                            echo '<td id="hoursoph-' . $row['medical_tourism_oph_id'] . '">' . urldecode($row['medical_tourism_oph_hours']) . '</td>';
                                            echo '<td id="phoneoph-' . $row['medical_tourism_oph_id'] . '">' . $row['medical_tourism_oph_phone'] . '</td>';

                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales PS -->
                    <div class="card shadow mb-4" id="psdt">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Plastic Surgery
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#exampleModal6">
                                                    <i class="fas fa-plus"></i>
                                                    New
                                                </a>
                                            </th>
                                            <th scope="col">Name</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Location URL</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Website</th>
                                            <th scope="col">Article</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM medical_tourism_ps ORDER BY medical_tourism_ps_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="orderps-' . $row['medical_tourism_ps_id'] . '" scope="row">' . $row['medical_tourism_ps_order'] . '</th>';

                                            echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodalps(' . $row['medical_tourism_ps_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupMTps=' . $row['medical_tourism_ps_order'] . '&medical_tourism_ps_id=' . $row['medical_tourism_ps_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdownMTps=' . $row['medical_tourism_ps_order'] . '&medical_tourism_ps_id=' . $row['medical_tourism_ps_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                            echo '<td id="nameps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_title']) . '</td>';
                                            echo '<td id="filenameps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_image']) . '</td>';
                                            echo '<td id="locationps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_location']) . '</td>';
                                            echo '<td id="locationurlps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_locationurl']) . '</td>';
                                            echo '<td id="contentps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_content']) . '</td>';
                                            echo '<td id="hoursps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_hours']) . '</td>';
                                            echo '<td id="phoneps-' . $row['medical_tourism_ps_id'] . '">' . $row['medical_tourism_ps_phone'] . '</td>';
                                            echo '<td id="websiteps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_websiteurl']) . '</td>';
                                            echo '<td id="articleps-' . $row['medical_tourism_ps_id'] . '">' . urldecode($row['medical_tourism_ps_article']) . '</td>';

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



    <!-- Add New Healthcare Modal-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Healthcare</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#hcdt" method="post" enctype="multipart/form-data" id="mthc">

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
                            <input type="file" class="form-control-file" id="fileToUploadhc" name="fileToUploadhc">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_mthc">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal HC-->
    <div class="modal fade" id="edithcmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Healthcare</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namehc">Name</label>
                            <input type="text" class="form-control" id="namehc" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationhc">Location</label>
                            <textarea type="text" class="form-control" id="locationhc" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlhc">Location URL</label>
                            <input type="text" class="form-control" id="locationurlhc" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenthc">Content</label>
                            <textarea class="form-control" id="contenthc" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourshc">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourshc" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phonehc">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phonehc" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderhc">Order</label>
                            <input type="text" class="form-control" id="orderhc" name="order">
                        </div>
                        <input class="form-control" id="mthcid" name="mthcid" hidden></input>
                        <input class="form-control" id="imagenamemthc" name="imagenamemthc" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deletemthc">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editmthc">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New Dental Modal-->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Dental</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#dtldt" method="post" enctype="multipart/form-data" id="mtdtl">

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
                            <input type="file" class="form-control-file" id="fileToUploaddtl" name="fileToUploaddtl">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_mtdtl">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal DTL-->
    <div class="modal fade" id="editdtlmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Dental</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namedtl">Name</label>
                            <input type="text" class="form-control" id="namedtl" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationdtl">Location</label>
                            <textarea type="text" class="form-control" id="locationdtl" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurldtl">Location URL</label>
                            <input type="text" class="form-control" id="locationurldtl" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentdtl">Content</label>
                            <textarea class="form-control" id="contentdtl" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursdtl">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursdtl" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phonedtl">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phonedtl" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderdtl">Order</label>
                            <input type="text" class="form-control" id="orderdtl" name="order">
                        </div>
                        <input class="form-control" id="mtdtlid" name="mtdtlid" hidden></input>
                        <input class="form-control" id="imagenamemtdtl" name="imagenamemtdtl" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deletemtDTL">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editmtDTL">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New Der Modal-->
    <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Dermatologist</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <form action="?addnew#derdt" method="post" enctype="multipart/form-data" id="mtder">

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
                            <input type="file" class="form-control-file" id="fileToUploadder" name="fileToUploadder">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_mtder">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal Der-->
    <div class="modal fade" id="editdermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Dermatologist</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameder">Name</label>
                            <input type="text" class="form-control" id="nameder" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationder">Location</label>
                            <textarea type="text" class="form-control" id="locationder" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlder">Location URL</label>
                            <input type="text" class="form-control" id="locationurlder" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentder">Content</label>
                            <textarea class="form-control" id="contentder" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursder">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursder" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneder">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneder" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderder">Order</label>
                            <input type="text" class="form-control" id="orderder" name="order">
                        </div>
                        <input class="form-control" id="mtderid" name="mtderid" hidden></input>
                        <input class="form-control" id="imagenamemtder" name="imagenamemtder" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deletemtDER">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editmtDER">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New Oph Modal-->
    <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Ophthalmologist</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#ophdt" method="post" enctype="multipart/form-data" id="mtdtl">

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
                            <input type="file" class="form-control-file" id="fileToUploadoph" name="fileToUploadoph">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_mtoph">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal OPH-->
    <div class="modal fade" id="editophmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Ophthalmologist</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#ophdt" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameoph">Name</label>
                            <input type="text" class="form-control" id="nameoph" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationoph">Location</label>
                            <textarea type="text" class="form-control" id="locationoph" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurloph">Location URL</label>
                            <input type="text" class="form-control" id="locationurloph" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentoph">Content</label>
                            <textarea class="form-control" id="contentoph" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursoph">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursoph" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneoph">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneoph" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderoph">Order</label>
                            <input type="text" class="form-control" id="orderoph" name="order">
                        </div>
                        <input class="form-control" id="mtophid" name="mtophid" hidden></input>
                        <input class="form-control" id="imagenamemtoph" name="imagenamemtoph" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deletemtOPH">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editmtOPH">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- Add New Ps Modal-->
        <div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Plastic Surgery</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#psdt" method="post" enctype="multipart/form-data" id="mtps">

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
                            <textarea type="text" class="form-control form-control-user" id="website" placeholder="WebsiteUrl"
                                name="website" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="article" placeholder="ArticleUrl"
                                name="article" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadps" name="fileToUploadps">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_mtps">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal PS-->
    <div class="modal fade" id="editpsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Plastic Surgery</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#psdt" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameps">Name</label>
                            <input type="text" class="form-control" id="nameps" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationps">Location</label>
                            <textarea type="text" class="form-control" id="locationps" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlps">Location URL</label>
                            <input type="text" class="form-control" id="locationurlps" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentps">Content</label>
                            <textarea class="form-control" id="contentps" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursps">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursps" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneps">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneps" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="websiteps">Website Url</label>
                            <textarea class="form-control" id="websiteps" name="website"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="articleps">Article Url</label>
                            <textarea class="form-control" id="articleps" name="article"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderps">Order</label>
                            <input type="text" class="form-control" id="orderps" name="order">
                        </div>
                        <input class="form-control" id="mtpsid" name="mtpsid" hidden></input>
                        <input class="form-control" id="imagenamemtps" name="imagenamemtps" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deletemtPS">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editmtPS">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addnewnavmt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Navigation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#dtldt" method="post" enctype="multipart/form-data" id="mtdtl">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name"
                                placeholder="Website/Name" name="name">
                        </div>

                        <div class="form-group">
                            <input type="number" class="form-control form-control-user" id="orderof" placeholder="Order"
                                name="orderof">
                        </div>

                        <div class="form-group mt-1">
                            <label for="">Display</label>
                            <!-- <textarea class="form-control" id="phoneatop2" name="phone"></textarea> -->
                            <select name="display" class="form-control">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>

                            </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploaddtl" name="fileToUploaddtl">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="addnewnavmt">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Toast -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
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
    <script src="js/editmt.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>

    <?php
    if (isset($_POST['modalshowaddnavmt'])) {

        $id = $_POST['id'];
        $query = "SELECT * FROM medical_tourism_nav WHERE id='$id'";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $rid = $row['id'];
            $rorderof = $row['orderof'];
            $rname = $row['name'];
            $rfilename = $row['filename'];
            $rdisplay = $row['display'];
            ?>

            <div class="modal fade" id="modalshowaddnavmt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Medical Tourism Navigation</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" id="mtdtl">

                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $rid ?>">
                                <input type="hidden" name="filename" value="<?php echo $rfilename ?>">
                                <div class="form-group">
                                    <label for="">Website/Name</label>
                                    <input type="text" class="form-control form-control-user" id="name"
                                        placeholder="Website/Name" name="name" value="<?php echo $rname ?>">
                                </div>

                                <div class="form-group">
                                    <label for="">Order</label>
                                    <input type="number" class="form-control form-control-user" id="orderof" placeholder="Order"
                                        name="orderof" value="<?php echo $rorderof ?>">
                                </div>

                                <div class="form-group mt-1">
                                    <label for="">Display</label>
                                    <!-- <textarea class="form-control" id="phoneatop2" name="phone"></textarea> -->
                                    <select name="display" class="form-control">
                                        <option value="0" <?php echo $rdisplay == "0" ? "selected" : " " ?>>No</option>
                                        <option value="1" <?php echo $rdisplay == "1" ? "selected" : " " ?>>Yes</option>

                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Select image to upload :</label>
                                    <input type="file" class="form-control-file" id="fileToUploaddtl" name="fileToUploaddtl">
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button class="btn btn-danger" type="submit" value="Upload Image"
                                    name="deletenavmt">Delete</button>
                                <button class="btn btn-primary" type="submit" value="Upload Image"
                                    name="editnavmt">Edit</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php

        }
        ?>

        <?php
        echo '<script>$("#modalshowaddnavmt").modal("show");</script>';
    }
    ?>
</body>

</html>