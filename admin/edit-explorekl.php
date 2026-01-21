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

    <title>KLTG ADMIN - Explore KL</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Explore KL Page</h1>
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
                                                    data-target="#addnewnavexplorekl">
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

                                        $query = "SELECT * FROM explorekl_nav ORDER BY orderof ASC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) { ?>

                                            <tr>
                                                <td>
                                                    <a href="#" class="" id="">
                                                        <form method="post">
                                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                            <button class="dropdown-item text-center text-primary" href="#"
                                                                name="modalshowaddnavexplorekl2">
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


                    <!-- DataTales wtd -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">What To Do <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table2" aria-expanded="true"
                                    aria-controls="table2">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                </a>
                            </h6>

                        </div>

                        <div class="collapse show" id="table2">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
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
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_wtd ORDER BY explorekl_wtd_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr >';
                                                echo '<th id="ordereklwtd-' . $row['explorekl_wtd_id'] . '" scope="row">' . $row['explorekl_wtd_order'] . '</th>';
                                                echo '<td class="text-center">
                                            <a href="#" class="" onclick="editmodalwtd(' . $row['explorekl_wtd_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                            <a href="?orderupeklwtd=' . $row['explorekl_wtd_order'] . '&explorekl_wtd_id=' . $row['explorekl_wtd_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                            <a href="?orderdowneklwtd=' . $row['explorekl_wtd_order'] . '&explorekl_wtd_id=' . $row['explorekl_wtd_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                            </td>';
                                                echo '<td id="nameeklwtd-' . $row['explorekl_wtd_id'] . '">' . urldecode($row['explorekl_wtd_title']) . '</td>';
                                                echo '<td id="filenameeklwtd-' . $row['explorekl_wtd_id'] . '">' . $row['explorekl_wtd_image'] . '</td>';
                                                echo '<td id="locationurleklwtd-' . $row['explorekl_wtd_id'] . '">' . urldecode($row['explorekl_wtd_locationurl']) . '</td>';
                                                echo '<td id="contenteklwtd-' . $row['explorekl_wtd_id'] . '">' . urldecode($row['explorekl_wtd_content']) . '</td>';



                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales hs -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Historical Sites <button class="btn btn-link"
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
                                                        data-target="#exampleModal5">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col">Hours</th>
                                                <th scope="col">Phone</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_hs ORDER BY explorekl_hs_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklhs-' . $row['explorekl_hs_id'] . '" scope="row">' . $row['explorekl_hs_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklhs(' . $row['explorekl_hs_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklhs=' . $row['explorekl_hs_order'] . '&explorekl_hs_id=' . $row['explorekl_hs_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklhs=' . $row['explorekl_hs_order'] . '&explorekl_hs_id=' . $row['explorekl_hs_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklhs-' . $row['explorekl_hs_id'] . '">' . urldecode($row['explorekl_hs_title']) . '</td>';
                                                echo '<td id="filenameeklhs-' . $row['explorekl_hs_id'] . '">' . urldecode($row['explorekl_hs_image']) . '</td>';
                                                echo '<td id="locationeklhs-' . $row['explorekl_hs_id'] . '">' . urldecode($row['explorekl_hs_location']) . '</td>';
                                                echo '<td id="locationurleklhs-' . $row['explorekl_hs_id'] . '">' . urldecode($row['explorekl_hs_locationurl']) . '</td>';
                                                echo '<td id="contenteklhs-' . $row['explorekl_hs_id'] . '">' . urldecode($row['explorekl_hs_content']) . '</td>';
                                                echo '<td id="hourseklhs-' . $row['explorekl_hs_id'] . '">' . urldecode($row['explorekl_hs_hours']) . '</td>';
                                                echo '<td id="phoneeklhs-' . $row['explorekl_hs_id'] . '">' . $row['explorekl_hs_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- DataTales kl4k -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">KL 4 Kids <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table4" aria-expanded="true"
                                    aria-controls="table4">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table4">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
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
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_kl4k ORDER BY explorekl_kl4k_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklkl4k-' . $row['explorekl_kl4k_id'] . '" scope="row">' . $row['explorekl_kl4k_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklkl4k(' . $row['explorekl_kl4k_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklkl4k=' . $row['explorekl_kl4k_order'] . '&explorekl_kl4k_id=' . $row['explorekl_kl4k_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklkl4k=' . $row['explorekl_kl4k_order'] . '&explorekl_kl4k_id=' . $row['explorekl_kl4k_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklkl4k-' . $row['explorekl_kl4k_id'] . '">' . urldecode($row['explorekl_kl4k_title']) . '</td>';
                                                echo '<td id="filenameeklkl4k-' . $row['explorekl_kl4k_id'] . '">' . urldecode($row['explorekl_kl4k_image']) . '</td>';
                                                echo '<td id="locationeklkl4k-' . $row['explorekl_kl4k_id'] . '">' . urldecode($row['explorekl_kl4k_location']) . '</td>';
                                                echo '<td id="locationurleklkl4k-' . $row['explorekl_kl4k_id'] . '">' . urldecode($row['explorekl_kl4k_locationurl']) . '</td>';
                                                echo '<td id="contenteklkl4k-' . $row['explorekl_kl4k_id'] . '">' . urldecode($row['explorekl_kl4k_content']) . '</td>';
                                                echo '<td id="hourseklkl4k-' . $row['explorekl_kl4k_id'] . '">' . urldecode($row['explorekl_kl4k_hours']) . '</td>';
                                                echo '<td id="phoneeklkl4k-' . $row['explorekl_kl4k_id'] . '">' . $row['explorekl_kl4k_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- DataTales parks -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Parks <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table5" aria-expanded="true"
                                    aria-controls="table5">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table5">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable4" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal7">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_p ORDER BY explorekl_p_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklp-' . $row['explorekl_p_id'] . '" scope="row">' . $row['explorekl_p_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklp(' . $row['explorekl_p_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklp=' . $row['explorekl_p_order'] . '&explorekl_p_id=' . $row['explorekl_p_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklp=' . $row['explorekl_p_order'] . '&explorekl_p_id=' . $row['explorekl_p_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklp-' . $row['explorekl_p_id'] . '">' . urldecode($row['explorekl_p_title']) . '</td>';
                                                echo '<td id="filenameeklp-' . $row['explorekl_p_id'] . '">' . urldecode($row['explorekl_p_image']) . '</td>';
                                                echo '<td id="locationeklp-' . $row['explorekl_p_id'] . '">' . urldecode($row['explorekl_p_location']) . '</td>';
                                                echo '<td id="locationurleklp-' . $row['explorekl_p_id'] . '">' . urldecode($row['explorekl_p_locationurl']) . '</td>';
                                                echo '<td id="contenteklp-' . $row['explorekl_p_id'] . '">' . urldecode($row['explorekl_p_content']) . '</td>';
                                                echo '<td id="hourseklp-' . $row['explorekl_p_id'] . '">' . urldecode($row['explorekl_p_hours']) . '</td>';
                                                echo '<td id="phoneeklp-' . $row['explorekl_p_id'] . '">' . $row['explorekl_p_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales pwor -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Place Of Worship <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table6" aria-expanded="true"
                                    aria-controls="table6">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table6">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable5" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal8">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_pwor ORDER BY explorekl_pwor_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklpwor-' . $row['explorekl_pwor_id'] . '" scope="row">' . $row['explorekl_pwor_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklpwor(' . $row['explorekl_pwor_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklpwor=' . $row['explorekl_pwor_order'] . '&explorekl_pwor_id=' . $row['explorekl_pwor_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklpwor=' . $row['explorekl_pwor_order'] . '&explorekl_pwor_id=' . $row['explorekl_pwor_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_title']) . '</td>';
                                                echo '<td id="categoryeklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_category']) . '</td>';
                                                echo '<td id="filenameeklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_image']) . '</td>';
                                                echo '<td id="locationeklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_location']) . '</td>';
                                                echo '<td id="locationurleklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_locationurl']) . '</td>';
                                                echo '<td id="contenteklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_content']) . '</td>';
                                                echo '<td id="hourseklpwor-' . $row['explorekl_pwor_id'] . '">' . urldecode($row['explorekl_pwor_hours']) . '</td>';
                                                echo '<td id="phoneeklpwor-' . $row['explorekl_pwor_id'] . '">' . $row['explorekl_pwor_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales nl -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Night Life <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table7" aria-expanded="true"
                                    aria-controls="table7">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table7">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable6" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal9">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_nl ORDER BY explorekl_nl_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklnl-' . $row['explorekl_nl_id'] . '" scope="row">' . $row['explorekl_nl_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklnl(' . $row['explorekl_nl_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklnl=' . $row['explorekl_nl_order'] . '&explorekl_nl_id=' . $row['explorekl_nl_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklnl=' . $row['explorekl_nl_order'] . '&explorekl_nl_id=' . $row['explorekl_nl_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_title']) . '</td>';
                                                echo '<td id="categoryeklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_category']) . '</td>';
                                                echo '<td id="filenameeklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_image']) . '</td>';
                                                echo '<td id="locationeklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_location']) . '</td>';
                                                echo '<td id="locationurleklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_locationurl']) . '</td>';
                                                echo '<td id="contenteklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_content']) . '</td>';
                                                echo '<td id="hourseklnl-' . $row['explorekl_nl_id'] . '">' . urldecode($row['explorekl_nl_hours']) . '</td>';
                                                echo '<td id="phoneeklnl-' . $row['explorekl_nl_id'] . '">' . $row['explorekl_nl_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- DataTales ss -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Sightseeing <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table8" aria-expanded="true"
                                    aria-controls="table8">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table8">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable7" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal10">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_ss ORDER BY explorekl_ss_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklss-' . $row['explorekl_ss_id'] . '" scope="row">' . $row['explorekl_ss_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklss(' . $row['explorekl_ss_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklss=' . $row['explorekl_ss_order'] . '&explorekl_ss_id=' . $row['explorekl_ss_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklss=' . $row['explorekl_ss_order'] . '&explorekl_ss_id=' . $row['explorekl_ss_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_title']) . '</td>';
                                                echo '<td id="categoryeklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_category']) . '</td>';
                                                echo '<td id="filenameeklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_image']) . '</td>';
                                                echo '<td id="locationeklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_location']) . '</td>';
                                                echo '<td id="locationurleklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_locationurl']) . '</td>';
                                                echo '<td id="contenteklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_content']) . '</td>';
                                                echo '<td id="hourseklss-' . $row['explorekl_ss_id'] . '">' . urldecode($row['explorekl_ss_hours']) . '</td>';
                                                echo '<td id="phoneeklss-' . $row['explorekl_ss_id'] . '">' . $row['explorekl_ss_phone'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales wtesf -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">What To Eat Street Food <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table9" aria-expanded="true"
                                    aria-controls="table9">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table9">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable8" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal11">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Website</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_wte_sf ORDER BY explorekl_wte_sf_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklwtesf-' . $row['explorekl_wte_sf_id'] . '" scope="row">' . $row['explorekl_wte_sf_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklwtesf(' . $row['explorekl_wte_sf_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklwtesf=' . $row['explorekl_wte_sf_order'] . '&explorekl_wte_sf_id=' . $row['explorekl_wte_sf_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklwtesf=' . $row['explorekl_wte_sf_order'] . '&explorekl_wte_sf_id=' . $row['explorekl_wte_sf_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . urldecode($row['explorekl_wte_sf_title']) . '</td>';
                                                echo '<td id="filenameeklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . urldecode($row['explorekl_wte_sf_image']) . '</td>';
                                                echo '<td id="locationeklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . urldecode($row['explorekl_wte_sf_location']) . '</td>';
                                                echo '<td id="locationurleklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . urldecode($row['explorekl_wte_sf_locationurl']) . '</td>';
                                                echo '<td id="contenteklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . urldecode($row['explorekl_wte_sf_content']) . '</td>';
                                                echo '<td id="hourseklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . urldecode($row['explorekl_wte_sf_hours']) . '</td>';
                                                echo '<td id="phoneeklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . $row['explorekl_wte_sf_phone'] . '</td>';
                                                echo '<td id="websiteeklwtesf-' . $row['explorekl_wte_sf_id'] . '">' . $row['explorekl_wte_sf_website'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales wtec -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">What To Eat Cafes <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table10" aria-expanded="true"
                                    aria-controls="table10">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table10">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable9" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal12">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Website</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_wte_c ORDER BY explorekl_wte_c_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklwtec-' . $row['explorekl_wte_c_id'] . '" scope="row">' . $row['explorekl_wte_c_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklwtec(' . $row['explorekl_wte_c_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklwtec=' . $row['explorekl_wte_c_order'] . '&explorekl_wte_c_id=' . $row['explorekl_wte_c_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklwtec=' . $row['explorekl_wte_c_order'] . '&explorekl_wte_c_id=' . $row['explorekl_wte_c_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklwtec-' . $row['explorekl_wte_c_id'] . '">' . urldecode($row['explorekl_wte_c_title']) . '</td>';
                                                echo '<td id="filenameeklwtec-' . $row['explorekl_wte_c_id'] . '">' . urldecode($row['explorekl_wte_c_image']) . '</td>';
                                                echo '<td id="locationeklwtec-' . $row['explorekl_wte_c_id'] . '">' . urldecode($row['explorekl_wte_c_location']) . '</td>';
                                                echo '<td id="locationurleklwtec-' . $row['explorekl_wte_c_id'] . '">' . urldecode($row['explorekl_wte_c_locationurl']) . '</td>';
                                                echo '<td id="contenteklwtec-' . $row['explorekl_wte_c_id'] . '">' . urldecode($row['explorekl_wte_c_content']) . '</td>';
                                                echo '<td id="hourseklwtec-' . $row['explorekl_wte_c_id'] . '">' . urldecode($row['explorekl_wte_c_hours']) . '</td>';
                                                echo '<td id="phoneeklwtec-' . $row['explorekl_wte_c_id'] . '">' . $row['explorekl_wte_c_phone'] . '</td>';
                                                echo '<td id="websiteeklwtec-' . $row['explorekl_wte_c_id'] . '">' . $row['explorekl_wte_c_website'] . '</td>';

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales wtec -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">What To Eat Restaurant <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table11" aria-expanded="true"
                                    aria-controls="table11">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table11">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable10" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal13">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>
                                                <th scope="col">Name</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col" style="min-width:300px">Location</th>
                                                <th scope="col">Location URL</th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                                <th scope="col" style="min-width:300px">Hours</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Website</th>
                                                <th scope="col">Category</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM explorekl_wte_r ORDER BY explorekl_wte_r_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr>';
                                                echo '<th id="ordereklwter-' . $row['explorekl_wte_r_id'] . '" scope="row">' . $row['explorekl_wte_r_order'] . '</th>';

                                                echo '<td class="text-center">
                                                 <a href="#" class="" onclick="editmodaleklwter(' . $row['explorekl_wte_r_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    <a href="?orderupeklwter=' . $row['explorekl_wte_r_order'] . '&explorekl_wte_r_id=' . $row['explorekl_wte_r_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                    <a href="?orderdowneklwter=' . $row['explorekl_wte_r_order'] . '&explorekl_wte_r_id=' . $row['explorekl_wte_r_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                            </td>';
                                                echo '<td id="nameeklwter-' . $row['explorekl_wte_r_id'] . '">' . urldecode($row['explorekl_wte_r_title']) . '</td>';
                                                echo '<td id="filenameeklwter-' . $row['explorekl_wte_r_id'] . '">' . urldecode($row['explorekl_wte_r_image']) . '</td>';
                                                echo '<td id="locationeklwter-' . $row['explorekl_wte_r_id'] . '">' . urldecode($row['explorekl_wte_r_location']) . '</td>';
                                                echo '<td id="locationurleklwter-' . $row['explorekl_wte_r_id'] . '">' . urldecode($row['explorekl_wte_r_locationurl']) . '</td>';
                                                echo '<td id="contenteklwter-' . $row['explorekl_wte_r_id'] . '">' . urldecode($row['explorekl_wte_r_content']) . '</td>';
                                                echo '<td id="hourseklwter-' . $row['explorekl_wte_r_id'] . '">' . urldecode($row['explorekl_wte_r_hours']) . '</td>';
                                                echo '<td id="phoneeklwter-' . $row['explorekl_wte_r_id'] . '">' . $row['explorekl_wte_r_phone'] . '</td>';
                                                echo '<td id="websiteeklwter-' . $row['explorekl_wte_r_id'] . '">' . $row['explorekl_wte_r_website'] . '</td>';
                                                echo '<td id="categoryeklwter-' . $row['explorekl_wte_r_id'] . '">' . $row['explorekl_wte_r_category'] . '</td>';

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



    <!-- Add New wtd-->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL What To Do </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table2" method="post" enctype="multipart/form-data" id="eklwtd">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
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
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadeklwtd"
                                name="fileToUploadeklwtd">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklwtd">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal wtd-->
    <div class="modal fade" id="editwtdmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - What To Do</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table2" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklwtd">Name</label>
                            <input type="text" class="form-control" id="nameeklwtd" name="name">
                        </div>

                        <div class="form-group">
                            <label for="locationurleklwtd">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklwtd" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklwtd">Content</label>
                            <textarea class="form-control" id="contenteklwtd" name="content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="ordereklwtd">Order</label>
                            <input type="text" class="form-control" id="ordereklwtd" name="order">
                        </div>
                        <input class="form-control" id="eklwtdid" name="eklwtdid" hidden></input>
                        <input class="form-control" id="imagenameeklwtd" name="imagenameeklwtd" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklwtd">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklwtd">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New hs Modal-->
    <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - Historical Sites</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table3" method="post" enctype="multipart/form-data" id="eklhs">

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
                            <input type="file" class="form-control-file" id="fileToUploadeklhs"
                                name="fileToUploadeklhs">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklhs">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal hs-->
    <div class="modal fade" id="editeklhsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - Historical Sites</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table3" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklhs">Name</label>
                            <input type="text" class="form-control" id="nameeklhs" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationeklhs">Location</label>
                            <textarea type="text" class="form-control" id="locationeklhs" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklhs">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklhs" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklhs">Content</label>
                            <textarea class="form-control" id="contenteklhs" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklhs">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklhs" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklhs">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklhs" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklhs">Order</label>
                            <input type="text" class="form-control" id="ordereklhs" name="order">
                        </div>
                        <input class="form-control" id="eklhsid" name="eklhsid" hidden></input>
                        <input class="form-control" id="imagenameeklhs" name="imagenameeklhs" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklhs">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklhs">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New kl4k Modal-->
    <div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - KL 4 Kids</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table4" method="post" enctype="multipart/form-data" id="eklkl4k">

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
                            <input type="file" class="form-control-file" id="fileToUploadeklkl4k"
                                name="fileToUploadeklkl4k">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklkl4k">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal kl4k-->
    <div class="modal fade" id="editeklkl4kmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - KL 4 Kids</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table4" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklkl4k">Name</label>
                            <input type="text" class="form-control" id="nameeklkl4k" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationeklkl4k">Location</label>
                            <textarea type="text" class="form-control" id="locationeklkl4k" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklkl4k">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklkl4k" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklkl4k">Content</label>
                            <textarea class="form-control" id="contenteklkl4k" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklkl4k">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklkl4k" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklkl4k">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklkl4k" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklkl4k">Order</label>
                            <input type="text" class="form-control" id="ordereklkl4k" name="order">
                        </div>
                        <input class="form-control" id="eklkl4kid" name="eklkl4kid" hidden></input>
                        <input class="form-control" id="imagenameeklkl4k" name="imagenameeklkl4k" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklkl4k">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklkl4k">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New parks Modal-->
    <div class="modal fade" id="exampleModal7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - Parks</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table5" method="post" enctype="multipart/form-data" id="eklp">

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
                            <input type="file" class="form-control-file" id="fileToUploadeklp" name="fileToUploadeklp">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklp">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal parks-->
    <div class="modal fade" id="editeklpmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - Parks</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table5" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklp">Name</label>
                            <input type="text" class="form-control" id="nameeklp" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationeklp">Location</label>
                            <textarea type="text" class="form-control" id="locationeklp" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklp">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklp" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklp">Content</label>
                            <textarea class="form-control" id="contenteklp" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklp">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklp" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklp">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklp" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklp">Order</label>
                            <input type="text" class="form-control" id="ordereklp" name="order">
                        </div>
                        <input class="form-control" id="eklpid" name="eklpid" hidden></input>
                        <input class="form-control" id="imagenameeklp" name="imagenameeklp" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklp">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklp">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Add New pwor Modal-->
    <div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - Places of Worship</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table6" method="post" enctype="multipart/form-data" id="eklpwor">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="category"
                                placeholder="Category" name="category">
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
                            <input type="file" class="form-control-file" id="fileToUploadeklpwor"
                                name="fileToUploadeklpwor">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklpwor">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal pwor-->
    <div class="modal fade" id="editeklpwormodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - Places of Worship</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table6" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklpwor">Name</label>
                            <input type="text" class="form-control" id="nameeklpwor" name="name">
                        </div>
                        <div class="form-group">
                            <label for="categoryeklpwor">Category</label>
                            <input type="text" class="form-control" id="categoryeklpwor" name="category">
                        </div>
                        <div class="form-group">
                            <label for="locationeklpwor">Location</label>
                            <textarea type="text" class="form-control" id="locationeklpwor" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklpwor">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklpwor" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklpwor">Content</label>
                            <textarea class="form-control" id="contenteklpwor" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklpwor">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklpwor" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklpwor">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklpwor" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklpwor">Order</label>
                            <input type="text" class="form-control" id="ordereklpwor" name="order">
                        </div>
                        <input class="form-control" id="eklpworid" name="eklpworid" hidden></input>
                        <input class="form-control" id="imagenameeklpwor" name="imagenameeklpwor" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklpwor">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklpwor">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New nl Modal-->
    <div class="modal fade" id="exampleModal9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - Night Life</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table7" method="post" enctype="multipart/form-data" id="eklnl">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="category"
                                placeholder="Category" name="category">
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
                            <input type="file" class="form-control-file" id="fileToUploadeklnl"
                                name="fileToUploadeklnl">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklnl">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal nl-->
    <div class="modal fade" id="editeklnlmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - Night Life</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table7" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklnl">Name</label>
                            <input type="text" class="form-control" id="nameeklnl" name="name">
                        </div>
                        <div class="form-group">
                            <label for="categoryeklnl">Category</label>
                            <input type="text" class="form-control" id="categoryeklnl" name="category">
                        </div>
                        <div class="form-group">
                            <label for="locationeklnl">Location</label>
                            <textarea type="text" class="form-control" id="locationeklnl" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklnl">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklnl" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklnl">Content</label>
                            <textarea class="form-control" id="contenteklnl" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklnl">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklnl" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklnl">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklnl" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklnl">Order</label>
                            <input type="text" class="form-control" id="ordereklnl" name="order">
                        </div>
                        <input class="form-control" id="eklnlid" name="eklnlid" hidden></input>
                        <input class="form-control" id="imagenameeklnl" name="imagenameeklnl" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklnl">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklnl">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New ss Modal-->
    <div class="modal fade" id="exampleModal10" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - Sightseeing</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table8" method="post" enctype="multipart/form-data" id="eklss">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="category"
                                placeholder="Category" name="category">
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
                            <input type="file" class="form-control-file" id="fileToUploadeklss"
                                name="fileToUploadeklss">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklss">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal ss-->
    <div class="modal fade" id="editeklssmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - Sightseeing</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table8" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklss">Name</label>
                            <input type="text" class="form-control" id="nameeklss" name="name">
                        </div>
                        <div class="form-group">
                            <label for="categoryeklss">Category</label>
                            <input type="text" class="form-control" id="categoryeklss" name="category">
                        </div>
                        <div class="form-group">
                            <label for="locationeklss">Location</label>
                            <textarea type="text" class="form-control" id="locationeklss" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklss">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklss" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklss">Content</label>
                            <textarea class="form-control" id="contenteklss" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklss">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklss" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklss">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklss" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklss">Order</label>
                            <input type="text" class="form-control" id="ordereklss" name="order">
                        </div>
                        <input class="form-control" id="eklssid" name="eklssid" hidden></input>
                        <input class="form-control" id="imagenameeklss" name="imagenameeklss" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklss">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklss">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Add New wte sf Modal-->
    <div class="modal fade" id="exampleModal11" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - What to Eat (Street Food)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table9" method="post" enctype="multipart/form-data" id="eklwtesf">

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
                            <textarea type="text" class="form-control form-control-user" id="website"
                                placeholder="Website" name="website" rows="3"></textarea>
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
                            <input type="file" class="form-control-file" id="fileToUploadeklwtesf" name="fileToUploadeklwtesf">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklwtesf">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal wte sf-->
    <div class="modal fade" id="editeklwtesfmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - What To Eat (Street Food)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table9" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklwtesf">Name</label>
                            <input type="text" class="form-control" id="nameeklwtesf" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationeklwtesf">Location</label>
                            <textarea type="text" class="form-control" id="locationeklwtesf" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklwtesf">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklwtesf" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklwtesf">Content</label>
                            <textarea class="form-control" id="contenteklwtesf" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="websiteeklwtesf">Website</label>
                            <textarea class="form-control" id="websiteeklwtesf" name="website"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklwtesf">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklwtesf" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklwtesf">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklwtesf" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklwtesf">Order</label>
                            <input type="text" class="form-control" id="ordereklwtesf" name="order">
                        </div>
                        <input class="form-control" id="eklwtesfid" name="eklwtesfid" hidden></input>
                        <input class="form-control" id="imagenameeklwtesf" name="imagenameeklwtesf" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklwtesf">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklwtesf">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New wte c Modal-->
    <div class="modal fade" id="exampleModal12" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - What to Eat (Cafes)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table10" method="post" enctype="multipart/form-data" id="eklwtec">

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
                            <textarea type="text" class="form-control form-control-user" id="website"
                                placeholder="Website" name="website" rows="3"></textarea>
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
                            <input type="file" class="form-control-file" id="fileToUploadeklwtec" name="fileToUploadeklwtec">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklwtec">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal wte c-->
    <div class="modal fade" id="editeklwtecmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - What To Eat (Cafes)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table10" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklwtec">Name</label>
                            <input type="text" class="form-control" id="nameeklwtec" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationeklwtec">Location</label>
                            <textarea type="text" class="form-control" id="locationeklwtec" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklwtec">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklwtec" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklwtec">Content</label>
                            <textarea class="form-control" id="contenteklwtec" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="websiteeklwtec">Website</label>
                            <textarea class="form-control" id="websiteeklwtec" name="website"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklwtec">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklwtec" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklwtec">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklwtec" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklwtec">Order</label>
                            <input type="text" class="form-control" id="ordereklwtec" name="order">
                        </div>
                        <input class="form-control" id="eklwtecid" name="eklwtecid" hidden></input>
                        <input class="form-control" id="imagenameeklwtec" name="imagenameeklwtec" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklwtec">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklwtec">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New wte c Modal-->
    <div class="modal fade" id="exampleModal13" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Explore KL - What to Eat (Restaurant)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table11" method="post" enctype="multipart/form-data" id="eklwter">

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
                            <textarea type="text" class="form-control form-control-user" id="category"
                                placeholder="Category" name="category" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="website"
                                placeholder="Website" name="website" rows="3"></textarea>
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
                            <input type="file" class="form-control-file" id="fileToUploadeklwter" name="fileToUploadeklwter">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_eklwter">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal wte c-->
    <div class="modal fade" id="editeklwtermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - What To Eat (Restaurant)</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table11" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nameeklwter">Name</label>
                            <input type="text" class="form-control" id="nameeklwter" name="name">
                        </div>
                        <div class="form-group">
                            <label for="locationeklwter">Location</label>
                            <textarea type="text" class="form-control" id="locationeklwter" name="location"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="locationurleklwter">Location URL</label>
                            <input type="text" class="form-control" id="locationurleklwter" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenteklwter">Content</label>
                            <textarea class="form-control" id="contenteklwter" name="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="categoryeklwter">Category</label>
                            <textarea class="form-control" id="categoryeklwter" name="category"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="websiteeklwter">Website</label>
                            <textarea class="form-control" id="websiteeklwter" name="website"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hourseklwter">Operating Hours (Put "/" for new line )</label>
                            <textarea class="form-control" id="hourseklwter" name="hours"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phoneeklwter">Phone (Put "/" for new line )</label>
                            <textarea class="form-control" id="phoneeklwter" name="phone"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ordereklwter">Order</label>
                            <input type="text" class="form-control" id="ordereklwter" name="order">
                        </div>
                        <input class="form-control" id="eklwterid" name="eklwterid" hidden></input>
                        <input class="form-control" id="imagenameeklwter" name="imagenameeklwter" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteeklwter">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editeklwter">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addnewnavexplorekl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Explore KL Navigation</h5>
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
                            name="addnewnavexplorekl">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['modalshowaddnavexplorekl2'])) {

        $id = $_POST['id'];
        $query = "SELECT * FROM explorekl_nav WHERE id='$id'";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $rid = $row['id'];
            $rorderof = $row['orderof'];
            $rname = $row['name'];
            $rfilename = $row['filename'];
            $rdisplay = $row['display'];
    ?>

            <div class="modal fade" id="modalshowaddnavexplorekl2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Explore KL - Navigation</h5>
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
                                    name="deletenavexplorekl">Delete</button>
                                <button class="btn btn-primary" type="submit" value="Upload Image"
                                    name="editnavexplorekl">Edit</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

    <?php

        }
    }
    ?>

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
    <script src="js/editexplorekl.js"></script>
    <script>
        document.getElementById("editnav").classList.add('active');
    </script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>
    <?php
    if (isset($_POST['modalshowaddnavexplorekl2'])) {

        echo '<script>$("#modalshowaddnavexplorekl2").modal("show");</script>';
    }

    ?>


    <!-- ... (other HTML code in edit-explorekl.php) ... -->

    <!-- Toast -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="liveToast" class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000"> <!-- Increased delay -->
            <div class="toast-header">
                <img src="../assets/img/favicon-32x32.png" class="rounded mr-2" alt="...">
                <strong class="mr-auto" id="toastTitle">Message</strong> <!-- Made title dynamic -->
                <!-- <small>11 mins ago</small> -->
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body" id="toast-body">Default Message</div> <!-- Made body dynamic -->
        </div>
    </div>

    <!-- ... (other HTML code in edit-explorekl.php) ... -->

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

    <!-- ... (other HTML code in edit-explorekl.php) ... -->

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

    <!-- ... (other scripts) ... -->

    <script>
        // Show the edit navigation modal if the trigger post variable is set
        <?php if (isset($_POST['modalshowaddnavexplorekl2'])): ?>
            $(document).ready(function() {
                $("#modalshowaddnavexplorekl2").modal("show");
            });
        <?php endif; ?>

        // Check URL for status parameter and show toast
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const msg = urlParams.get('msg') || 'An action occurred.'; // Get message from URL, default if not present
            // const hash = urlParams.get('hash'); // Get target hash from URL if needed later

            let toastTitle = 'Message';
            let toastBody = msg; // Use the message from the URL
            let toastClass = 'bg-info text-white'; // Default class

            if (status === 'success') {
                toastTitle = 'Success!';
                toastClass = 'bg-success text-white';
            } else if (status === 'warning') {
                toastTitle = 'Warning!';
                toastClass = 'bg-warning text-white';
            } else if (status === 'upload_error' || status === 'db_error') {
                toastTitle = 'Error!';
                toastClass = 'bg-danger text-white';
            }

            if (status) {
                const toastElement = document.getElementById('liveToast');
                const titleElement = document.getElementById('toastTitle');
                const bodyElement = document.getElementById('toast-body');

                titleElement.textContent = toastTitle;
                bodyElement.textContent = toastBody;
                // Remove any previous classes and add the new one
                toastElement.className = toastElement.className.replace(/bg-\w+\s+text-\w+/g, '');
                toastElement.classList.add(toastClass);

                // Show the toast
                $('#liveToast').toast('show');

                // Optional: Remove the status and msg parameters from the URL after showing the toast
                // This prevents the toast from showing again if the user navigates back to this URL
                // and avoids cluttering the URL
                if (window.history.replaceState) {
                    // Build the new search string without status, msg, hash
                    const newSearchParams = new URLSearchParams(window.location.search);
                    newSearchParams.delete('status');
                    newSearchParams.delete('msg');
                    // newSearchParams.delete('hash'); // Delete hash if you don't need it after redirect
                    // Reconstruct the URL
                    let newUrl = window.location.pathname;
                    if (newSearchParams.toString()) {
                        newUrl += '?' + newSearchParams.toString();
                    }
                    // Optionally append the original hash if it existed and was *not* the status hash
                    // This might be tricky if the status hash was just for redirection target.
                    // For now, let's not append the hash parameter used for redirection.
                    // if (hash) {
                    //    newUrl += hash; // Append the hash if it was present in the original URL
                    // }
                    window.history.replaceState({}, document.title, newUrl);
                }
            }
        });
    </script>

    <!-- ... (other HTML code in edit-explorekl.php) ... -->

    <!-- ... (other scripts) ... -->

    <script>
        // Show the edit navigation modal if the trigger post variable is set
        <?php if (isset($_POST['modalshowaddnavexplorekl2'])): ?>
            $(document).ready(function() {
                $("#modalshowaddnavexplorekl2").modal("show");
            });
        <?php endif; ?>

        // Check for alert message stored in session via PHP and show alert
        <?php if (isset($_SESSION['alert_msg']) && isset($_SESSION['alert_type'])): ?>
            $(document).ready(function() {
                const alertType = "<?php echo $_SESSION['alert_type']; ?>";
                const alertMsg = "<?php echo addslashes($_SESSION['alert_msg']); ?>"; // Escape quotes for JS

                // Display the alert with the message
                alert(alertMsg);

                // Optional: Clear the session variables after showing the alert
                // This prevents the alert from showing again if the user navigates back to this URL
                // or refreshes the page after the redirect.
                // You could do this with another small AJAX call or by reloading without the alert.
                // A simple way is to redirect again, removing the alert from the session on the server side.
                // Since the alert is shown *after* the redirect, clearing it here in JS is tricky.
                // The best way is to clear it on the server side *after* it's been shown once.
                // However, the most reliable way is to clear it on the *next* page load *if* it exists.
                // We can do this by checking for the existence of the variable and unsetting it here
                // before the page finishes loading, but this requires a small PHP snippet run via JS,
                // which is not ideal. The cleanest way is often just to let the session variable
                // be overwritten by the next action or to clear it implicitly by not checking for it
                // again after this point on the *same* page load where it was set.
                // For now, we'll just show it once per page load where the session var exists.

                // Unset the session variable on the server side using PHP via a small AJAX call
                // (This is the cleanest way to ensure it's cleared after showing).
                // Create a small PHP script (e.g., clear_alert.php) containing:
                // <?php session_start();
                    unset($_SESSION['alert_msg']);
                    unset($_SESSION['alert_type']); ?>
                // Then call it from JS:
                // $.post('clear_alert.php', function() { /* Optionally handle success */ });
                // For simplicity in this context, we'll just rely on the fact that the next
                // POST/GET action will overwrite the session variable, or it will expire.
                // The key is that the alert is shown only *after* the redirect.
            });
            <?php
            // Clear the session variables immediately after outputting the JS alert code
            // This ensures it only shows once.
            unset($_SESSION['alert_msg']);
            unset($_SESSION['alert_type']);
            ?>
        <?php endif; ?>
    </script>

    <!-- ... (rest of the HTML code) ... -->


</body>

</html>