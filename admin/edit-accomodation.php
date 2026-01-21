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

    <title>KLTG ADMIN - Edit Accomodation</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Accomodation Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales TOP -->

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Acccomodation
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">

                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                    data-target="#addnewnavaccommodation">
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

                                        $query = "SELECT * FROM accommodation_nav ORDER BY orderof ASC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) { ?>

                                            <tr>
                                                <td>
                                                    <a href="#" class="" id="">
                                                        <form method="post">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <button class="dropdown-item text-center text-primary" href="#" name="modalshowaddnavaccommodation">
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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Top Places To Stay In KL <button
                                    class="btn btn-link" data-toggle="collapse" data-target="#table1"
                                    aria-expanded="true" aria-controls="table1">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                </a>
                            </h6>

                        </div>

                        <div class="collapse show" id="table1">

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
                                                <th scope="col" class="th-lg">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" class="th-lg">Content</th>
                                                <th scope="col">Hours</th>
                                                <th scope="col">Phone</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM accommodation_top ORDER BY accommodation_top_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr >';
                                                echo '<th id="orderatop-' . $row['accommodation_top_id'] . '" scope="row">' . $row['accommodation_top_order'] . '</th>';
                                                echo '<td class="text-center">
                                            <a href="#" class="" onclick="editmodaltop(' . $row['accommodation_top_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                            <a href="?orderupATOP=' . $row['accommodation_top_order'] . '&accommodation_top_id=' . $row['accommodation_top_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                            <a href="?orderdownATOP=' . $row['accommodation_top_order'] . '&accommodation_top_id=' . $row['accommodation_top_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                            </td>';
                                                echo '<td id="nameatop-' . $row['accommodation_top_id'] . '">' . urldecode($row['accommodation_top_title']) . '</td>';
                                                echo '<td id="filenameatop-' . $row['accommodation_top_id'] . '">' . $row['accommodation_top_image'] . '</td>';
                                                echo '<td id="locationatop-' . $row['accommodation_top_id'] . '">' . urldecode($row['accommodation_top_location']) . '</td>';
                                                echo '<td id="locationurlatop-' . $row['accommodation_top_id'] . '">' . urldecode($row['accommodation_top_locationurl']) . '</td>';
                                                echo '<td id="contentatop-' . $row['accommodation_top_id'] . '">' . urldecode($row['accommodation_top_content']) . '</td>';
                                                echo '<td id="hoursatop-' . $row['accommodation_top_id'] . '">' . urldecode($row['accommodation_top_hours']) . '</td>';
                                                echo '<td id="phoneatop-' . $row['accommodation_top_id'] . '">' . $row['accommodation_top_phone'] . '</td>';



                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- DataTales H -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hotels <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table2" aria-expanded="true"
                                    aria-controls="table2">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table2">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
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

                                            $query = "SELECT * FROM accommodation_h ORDER BY accommodation_h_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="orderah-' . $row['accommodation_h_id'] . '" scope="row">' . $row['accommodation_h_order'] . '</th>';

                                                echo '<td class="text-center">
                                                <a href="#" class="" onclick="editmodalah(' . $row['accommodation_h_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                <a href="?orderupAH=' . $row['accommodation_h_order'] . '&accommodation_h_id=' . $row['accommodation_h_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                <a href="?orderdownAH=' . $row['accommodation_h_order'] . '&accommodation_h_id=' . $row['accommodation_h_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                  </td>';
                                                echo '<td id="nameah-' . $row['accommodation_h_id'] . '">' . urldecode($row['accommodation_h_title']) . '</td>';
                                                echo '<td id="filenameah-' . $row['accommodation_h_id'] . '">' . urldecode($row['accommodation_h_image']) . '</td>';
                                                echo '<td id="locationah-' . $row['accommodation_h_id'] . '">' . urldecode($row['accommodation_h_location']) . '</td>';
                                                echo '<td id="locationurlah-' . $row['accommodation_h_id'] . '">' . urldecode($row['accommodation_h_locationurl']) . '</td>';
                                                echo '<td id="contentah-' . $row['accommodation_h_id'] . '">' . urldecode($row['accommodation_h_content']) . '</td>';
                                                echo '<td id="hoursah-' . $row['accommodation_h_id'] . '">' . urldecode($row['accommodation_h_hours']) . '</td>';
                                                echo '<td id="phoneah-' . $row['accommodation_h_id'] . '">' . $row['accommodation_h_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales BH -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Budget Hotels <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table3" aria-expanded="true"
                                    aria-controls="table3">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table3">

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

                                            $query = "SELECT * FROM accommodation_bh ORDER BY accommodation_bh_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="orderabh-' . $row['accommodation_bh_id'] . '" scope="row">' . $row['accommodation_bh_order'] . '</th>';

                                                echo '<td class="text-center">
                                                <a href="#" class="" onclick="editmodalabh(' . $row['accommodation_bh_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                <a href="?orderupABH=' . $row['accommodation_bh_order'] . '&accommodation_bh_id=' . $row['accommodation_bh_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                <a href="?orderdownABH=' . $row['accommodation_bh_order'] . '&accommodation_bh_id=' . $row['accommodation_bh_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                  </td>';
                                                echo '<td id="nameabh-' . $row['accommodation_bh_id'] . '">' . urldecode($row['accommodation_bh_title']) . '</td>';
                                                echo '<td id="filenameabh-' . $row['accommodation_bh_id'] . '">' . urldecode($row['accommodation_bh_image']) . '</td>';
                                                echo '<td id="locationabh-' . $row['accommodation_bh_id'] . '">' . urldecode($row['accommodation_bh_location']) . '</td>';
                                                echo '<td id="locationurlabh-' . $row['accommodation_bh_id'] . '">' . urldecode($row['accommodation_bh_locationurl']) . '</td>';
                                                echo '<td id="contentabh-' . $row['accommodation_bh_id'] . '">' . urldecode($row['accommodation_bh_content']) . '</td>';
                                                echo '<td id="hoursabh-' . $row['accommodation_bh_id'] . '">' . urldecode($row['accommodation_bh_hours']) . '</td>';
                                                echo '<td id="phoneabh-' . $row['accommodation_bh_id'] . '">' . $row['accommodation_bh_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales BL -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Backpackers Lodge <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table4" aria-expanded="true"
                                    aria-controls="table4">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table4">

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

                                            $query = "SELECT * FROM accommodation_bks ORDER BY accommodation_bks_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="orderabks-' . $row['accommodation_bks_id'] . '" scope="row">' . $row['accommodation_bks_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodalabks(' . $row['accommodation_bks_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupABKS=' . $row['accommodation_bks_order'] . '&accommodation_bks_id=' . $row['accommodation_bks_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdownABKS=' . $row['accommodation_bks_order'] . '&accommodation_bks_id=' . $row['accommodation_bks_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameabks-' . $row['accommodation_bks_id'] . '">' . urldecode($row['accommodation_bks_title']) . '</td>';
                                                echo '<td id="filenameabks-' . $row['accommodation_bks_id'] . '">' . urldecode($row['accommodation_bks_image']) . '</td>';
                                                echo '<td id="locationabks-' . $row['accommodation_bks_id'] . '">' . urldecode($row['accommodation_bks_location']) . '</td>';
                                                echo '<td id="locationurlabks-' . $row['accommodation_bks_id'] . '">' . urldecode($row['accommodation_bks_locationurl']) . '</td>';
                                                echo '<td id="contentabks-' . $row['accommodation_bks_id'] . '">' . urldecode($row['accommodation_bks_content']) . '</td>';
                                                echo '<td id="hoursabks-' . $row['accommodation_bks_id'] . '">' . urldecode($row['accommodation_bks_hours']) . '</td>';
                                                echo '<td id="phoneabks-' . $row['accommodation_bks_id'] . '">' . $row['accommodation_bks_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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



    <!-- Add New Acco TOP-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Accomodation Top</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table1" method="post" enctype="multipart/form-data" id="atop">

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
                            <input type="file" class="form-control-file" id="fileToUploadatop" name="fileToUploadatop">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_atop">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal Top-->
    <div class="modal fade" id="edittopmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Top Places To Stay In KL</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table1" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameatop">Name</label>
                            <input type="text" class="form-control" id="nameatop" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationatop">Location</label>
                            <textarea type="text" class="form-control" id="locationatop" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlatop">Location URL</label>
                            <input type="text" class="form-control" id="locationurlatop" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentatop">Content</label>
                            <textarea class="form-control" id="contentatop" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursatop">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursatop" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneatop">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneatop" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderatop">Order</label>
                            <input type="text" class="form-control" id="orderatop" name="order">
                        </div>
                        <input class="form-control" id="atopid" name="atopid" hidden></input>
                        <input class="form-control" id="imagenameatop" name="imagenameatop" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteatop">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editatop">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New AH Modal-->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New A Hotel</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table2" method="post" enctype="multipart/form-data" id="ah">

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
                            <input type="file" class="form-control-file" id="fileToUploadah" name="fileToUploadah">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_ah">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal AH-->
    <div class="modal fade" id="editahmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit A H</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table2" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameah">Name</label>
                            <input type="text" class="form-control" id="nameah" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationah">Location</label>
                            <textarea type="text" class="form-control" id="locationah" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlah">Location URL</label>
                            <input type="text" class="form-control" id="locationurlah" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentah">Content</label>
                            <textarea class="form-control" id="contentah" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursah">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursah" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneah">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneah" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderah">Order</label>
                            <input type="text" class="form-control" id="orderah" name="order">
                        </div>
                        <input class="form-control" id="ahid" name="ahid" hidden></input>
                        <input class="form-control" id="imagenameah" name="imagenameah" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteah">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editah">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New BH Modal-->
    <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New A BH</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table3" method="post" enctype="multipart/form-data" id="abh">

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
                            <input type="file" class="form-control-file" id="fileToUploadabh" name="fileToUploadabh">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_abh">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal BH-->
    <div class="modal fade" id="editabhmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit A BH</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table3" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namebh">Name</label>
                            <input type="text" class="form-control" id="nameabh" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationbh">Location</label>
                            <textarea type="text" class="form-control" id="locationabh" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlbh">Location URL</label>
                            <input type="text" class="form-control" id="locationurlabh" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentbh">Content</label>
                            <textarea class="form-control" id="contentabh" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursbh">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursabh" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phonebh">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneabh" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderbh">Order</label>
                            <input type="text" class="form-control" id="orderabh" name="order">
                        </div>
                        <input class="form-control" id="abhid" name="abhid" hidden></input>
                        <input class="form-control" id="imagenameabh" name="imagenameabh" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteabh">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editabh">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New ABKS Modal-->
    <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New A BKS</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table4" method="post" enctype="multipart/form-data" id="abks">

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
                            <input type="file" class="form-control-file" id="fileToUploadabks" name="fileToUploadabks">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_abks">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal ABKS-->
    <div class="modal fade" id="editabksmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit ABKS</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table4" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameabks">Name</label>
                            <input type="text" class="form-control" id="nameabks" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationabks">Location</label>
                            <textarea type="text" class="form-control" id="locationabks" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurlabks">Location URL</label>
                            <input type="text" class="form-control" id="locationurlabks" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentabks">Content</label>
                            <textarea class="form-control" id="contentabks" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hoursabks">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hoursabks" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneabks">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneabks" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="orderabks">Order</label>
                            <input type="text" class="form-control" id="orderabks" name="order">
                        </div>
                        <input class="form-control" id="abksid" name="abksid" hidden></input>
                        <input class="form-control" id="imagenameabks" name="imagenameabks" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteabks">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editabks">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addnewnavaccommodation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Accomodation Navigation</h5>
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
                            name="addnewnavaccommodation">Create</button>

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
    <script src="js/edita.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>

    <?php
    if (isset($_POST['modalshowaddnavaccommodation'])) {

        $id = $_POST['id'];
        $query = "SELECT * FROM accommodation_nav WHERE id='$id'";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $rid = $row['id'];
            $rorderof = $row['orderof'];
            $rname = $row['name'];
            $rfilename = $row['filename'];
            $rdisplay = $row['display'];
            ?>

            <div class="modal fade" id="modalshowaddnavaccommodation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Accomodation Navigation</h5>
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
                                    name="deletenavaccommodation">Delete</button>
                                <button class="btn btn-primary" type="submit" value="Upload Image"
                                    name="editnavaccommodation">Edit</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php

        }
        ?>

        <?php
        echo '<script>$("#modalshowaddnavaccommodation").modal("show");</script>';
    }
    ?>


</body>

</html>