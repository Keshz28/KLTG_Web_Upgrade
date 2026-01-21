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

    <title>KLTG ADMIN - Edit Highlights</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Highlights Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>


                    <!-- DataTales i -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Travel News<button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table1" aria-expanded="true"
                                    aria-controls="table1" id="table1a">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>

                        </div>

                        <div class="collapse show" id="table1">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatable1" width="100%" cellspacing="0">
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
                                                <th scope="col">Website</th>
                                                <th scope="col" style="min-width:600px">Content</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM highlights WHERE highlights_category='travelnews' ORDER BY highlights_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr>
                                                    <th id="orderbkli-<?php echo $row['highlights_id'] ?>" scope="row">
                                                        <?php echo $row['highlights_order'] ?>
                                                    </th>
                                                    <td class="text-center">
                                                        <a href="#" class=""
                                                            onclick="editmodali('<?php echo $row['highlights_id'] ?>');"
                                                            id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                        <a href="?orderupBKLI='<?php echo $row['highlights_order'] ?>'&highlights_id='<?php echo $row['highlights_id'] ?>'"><i
                                                                class="fas fa-chevron-up"></i></a><br>
                                                        <a href="?orderdownBKLI='<?php echo $row['highlights_order'] ?>'&highlights_id='<?php echo $row['highlights_id'] ?>'"><i
                                                                class="fas fa-chevron-down"></i></a><br>
                                                    </td>
                                                    <td id="namebkli-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_title']) ?>
                                                    </td>
                                                    <td id="filenamebkli-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo $row['highlights_image'] ?>
                                                    </td>
                                                    <td id="locationurlbkli-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_locationurl']) ?>
                                                    </td>
                                                    <td id="websitebkli-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_website']) ?>
                                                    </td>
                                                    <td id="contentbkli-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_content']) ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- DataTales i -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Travel Tips<button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table2" aria-expanded="true"
                                    aria-controls="table2" id="table2a">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>

                        </div>

                        <div class="collapse show" id="table2">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatable2" width="100%" cellspacing="0">
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
                                                <th scope="col">Website</th>
                                                <th scope="col" style="min-width:600px">Content</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM highlights WHERE highlights_category='traveltips' ORDER BY highlights_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                //     echo '<tr >';
                                                //     echo '<th id="orderbklhs-' . $row['beyondkl_i_id'] . '" scope="row">' . $row['beyondkl_i_order'] . '</th>';
                                                //     echo '<td class="text-center">
                                                // <a href="#" class="" onclick="editmodali(' . $row['beyondkl_i_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                // <a href="?orderupbklhs=' . $row['beyondkl_i_order'] . '&beyondkl_i_id=' . $row['beyondkl_i_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                // <a href="?orderdownbklhs=' . $row['beyondkl_i_order'] . '&beyondkl_i_id=' . $row['beyondkl_i_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                // </td>';
                                                //     echo '<td id="namebklhs-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_title']) . '</td>';
                                                //     echo '<td id="filenamebklhs-' . $row['beyondkl_i_id'] . '">' . $row['beyondkl_i_image'] . '</td>';
                                                //     echo '<td id="locationurlbklhs-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_locationurl']) . '</td>';
                                                //     echo '<td id="contentbklhs-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_content']) . '</td>';



                                                //     echo '</tr>';
                                            ?>


                                                <tr>
                                                    <th id="orderbklhs-<?php echo $row['highlights_id'] ?>" scope="row">
                                                        <?php echo $row['highlights_order'] ?>
                                                    </th>
                                                    <td class="text-center">
                                                        <a href="#" class=""
                                                            onclick="editmodali('<?php echo $row['highlights_id'] ?>');"
                                                            id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                        <a
                                                            href="?orderupbklhs='<?php echo $row['highlights_order'] ?>'&highlights_id='<?php echo $row['highlights_id'] ?>'"><i
                                                                class="fas fa-chevron-up"></i></a><br>
                                                        <a
                                                            href="?orderdownbklhs='<?php echo $row['highlights_order'] ?>'&highlights_id='<?php echo $row['highlights_id'] ?>'"><i
                                                                class="fas fa-chevron-down"></i></a><br>
                                                    </td>
                                                    <td id="namebklhs-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_title']) ?>
                                                    </td>
                                                    <td id="filenamebklhs-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo $row['highlights_image'] ?>
                                                    </td>
                                                    <td id="locationurlbklhs-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_website']) ?>
                                                    </td>
                                                    <td id="contentbklhs-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_content']) ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- DataTales i -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Getting Around KL<button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table3" aria-expanded="true"
                                    aria-controls="table3" id="table3a">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>

                        </div>

                        <div class="collapse show" id="table3">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatable3" width="100%" cellspacing="0">
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
                                                <th scope="col">Website</th>
                                                <th scope="col" style="min-width:600px">Content</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM highlights WHERE highlights_category='gettingaround' ORDER BY highlights_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                //     echo '<tr >';
                                                //     echo '<th id="orderbklw-' . $row['beyondkl_i_id'] . '" scope="row">' . $row['beyondkl_i_order'] . '</th>';
                                                //     echo '<td class="text-center">
                                                // <a href="#" class="" onclick="editmodali(' . $row['beyondkl_i_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                // <a href="?orderupbklw=' . $row['beyondkl_i_order'] . '&beyondkl_i_id=' . $row['beyondkl_i_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                // <a href="?orderdownbklw=' . $row['beyondkl_i_order'] . '&beyondkl_i_id=' . $row['beyondkl_i_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                // </td>';
                                                //     echo '<td id="namebklw-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_title']) . '</td>';
                                                //     echo '<td id="filenamebklw-' . $row['beyondkl_i_id'] . '">' . $row['beyondkl_i_image'] . '</td>';
                                                //     echo '<td id="locationurlbklw-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_locationurl']) . '</td>';
                                                //     echo '<td id="contentbklw-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_content']) . '</td>';



                                                //     echo '</tr>';
                                            ?>


                                                <tr>
                                                    <th id="orderbklw-<?php echo $row['highlights_id'] ?>" scope="row">
                                                        <?php echo $row['highlights_order'] ?>
                                                    </th>
                                                    <td class="text-center">
                                                        <a href="#" class=""
                                                            onclick="editmodali('<?php echo $row['highlights_id'] ?>');"
                                                            id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                        <a
                                                            href="?orderupbklw='<?php echo $row['highlights_order'] ?>'&highlights_id='<?php echo $row['highlights_id'] ?>'"><i
                                                                class="fas fa-chevron-up"></i></a><br>
                                                        <a
                                                            href="?orderdownbklw='<?php echo $row['highlights_order'] ?>'&highlights_id='<?php echo $row['highlights_id'] ?>'"><i
                                                                class="fas fa-chevron-down"></i></a><br>
                                                    </td>
                                                    <td id="namebklw-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_title']) ?>
                                                    </td>
                                                    <td id="filenamebklw-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo $row['highlights_image'] ?>
                                                    </td>
                                                    <td id="locationurlbklw-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_website']) ?>
                                                    </td>
                                                    <td id="contentbklw-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_content']) ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales i -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">KL @ A Glance<button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table4" aria-expanded="true"
                                    aria-controls="table4" id="table4a">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>

                        </div>

                        <div class="collapse show" id="table4">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatable4" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Action
                                                </th>
                                                <th scope="col" style="min-width:600px">Content</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM highlights WHERE highlights_category='klglance' ORDER BY highlights_order DESC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                //     echo '<tr >';
                                                //     echo '<th id="orderbklh-' . $row['beyondkl_i_id'] . '" scope="row">' . $row['beyondkl_i_order'] . '</th>';
                                                //     echo '<td class="text-center">
                                                // <a href="#" class="" onclick="editmodali(' . $row['beyondkl_i_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                // <a href="?orderupbklh=' . $row['beyondkl_i_order'] . '&beyondkl_i_id=' . $row['beyondkl_i_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                                // <a href="?orderdownbklh=' . $row['beyondkl_i_order'] . '&beyondkl_i_id=' . $row['beyondkl_i_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                // </td>';
                                                //     echo '<td id="namebklh-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_title']) . '</td>';
                                                //     echo '<td id="filenamebklh-' . $row['beyondkl_i_id'] . '">' . $row['beyondkl_i_image'] . '</td>';
                                                //     echo '<td id="locationurlbklh-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_locationurl']) . '</td>';
                                                //     echo '<td id="contentbklh-' . $row['beyondkl_i_id'] . '">' . urldecode($row['beyondkl_i_content']) . '</td>';



                                                //     echo '</tr>';
                                            ?>


                                                <tr>
                                                    <th id="orderbklh-<?php echo $row['highlights_id'] ?>" scope="row">
                                                        <?php echo $row['highlights_order'] ?>
                                                    </th>
                                                    <td class="text-center">
                                                        <a href="#" class=""
                                                            onclick="editmodali('<?php echo $row['highlights_id'] ?>');"
                                                            id="modaledit"><i class="fas fa-pen"></i></a><br>
                                                    </td>

                                                    <td id="contentbklh-<?php echo $row['highlights_id'] ?>">
                                                        <?php echo ($row['highlights_content']) ?>
                                                    </td>
                                                </tr>
                                            <?php
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



    <!-- Add New i-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Highlights 1</h5>
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
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="website"
                                placeholder="Website" name="website" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadbkli" name="fileToUploadbkli">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_h1">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal i-->
    <div class="modal fade" id="editimodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit beyond kl i</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table1" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namebkli">Name</label>
                            <input type="text" class="form-control" id="namebkli" name="name">
                        </div>

                        <div class="form-group">
                            <label for="locationurlbkli">Location URL</label>
                            <input type="text" class="form-control" id="locationurlbkli" name="locationurl">
                        </div>

                        <div class="form-group">
                            <label for="websitebkli">Website</label>
                            <input type="text" class="form-control" id="websitebkli" name="website">
                        </div>
                        <div class="form-group">
                            <label for="contentbkli">Content</label>
                            <textarea class="form-control" id="contentbkli" name="content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="orderbkli">Order</label>
                            <input type="text" class="form-control" id="orderbkli" name="order">
                        </div>
                        <input class="form-control" id="bkliid" name="bkliid" hidden></input>
                        <input class="form-control" id="imagenamebkli" name="imagenamebkli" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteh1">Delete</button>
                        <button class="btn btn-primary" type="submit" name="edith1">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New hs-->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Highlights 2</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table2" method="post" enctype="multipart/form-data" id="bklhs">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>

                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Website" name="locationurl" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadbklhs"
                                name="fileToUploadbklhs">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_h2">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal hs-->
    <div class="modal fade" id="edithsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit beyondkl hsL</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table2" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namebklhs">Name</label>
                            <input type="text" class="form-control" id="namebklhs" name="name">
                        </div>

                        <div class="form-group">
                            <label for="locationurlbklhs">Website</label>
                            <input type="text" class="form-control" id="locationurlbklhs" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentbklhs">Content</label>
                            <textarea class="form-control" id="contentbklhs" name="content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="orderbklhs">Order</label>
                            <input type="text" class="form-control" id="orderbklhs" name="order">
                        </div>
                        <input class="form-control" id="bklhsid" name="bklhsid" hidden></input>
                        <input class="form-control" id="imagenamebklhs" name="imagenamebklhs" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteh2">Delete</button>
                        <button class="btn btn-primary" type="submit" name="edith2">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Add New w-->
    <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Highlights 3</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table3" method="post" enctype="multipart/form-data" id="bklhs">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>

                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Website" name="locationurl" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadbklw" name="fileToUploadbklw">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_h3">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal w-->
    <div class="modal fade" id="editwmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit beyondkl w</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table3" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namebklw">Name</label>
                            <input type="text" class="form-control" id="namebklw" name="name">
                        </div>

                        <div class="form-group">
                            <label for="locationurlbklw">Website</label>
                            <input type="text" class="form-control" id="locationurlbklw" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentbklw">Content</label>
                            <textarea class="form-control" id="contentbklw" name="content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="orderbklw">Order</label>
                            <input type="text" class="form-control" id="orderbklw" name="order">
                        </div>
                        <input class="form-control" id="bklwid" name="bklwid" hidden></input>
                        <input class="form-control" id="imagenamebklw" name="imagenamebklw" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteh3">Delete</button>
                        <button class="btn btn-primary" type="submit" name="edith3">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Add New h-->
    <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Highlights 4</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table4" method="post" enctype="multipart/form-data" id="bklhs">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>

                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Website" name="locationurl" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadbklh" name="fileToUploadbklh">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_h4">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal h-->
    <div class="modal fade" id="edithmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit BKLH</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table4" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namebklh">Name</label>
                            <input type="text" class="form-control" id="namebklh" name="name">
                        </div>

                        <div class="form-group">
                            <label for="locationurlbklh">Website</label>
                            <input type="text" class="form-control" id="locationurlbklh" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentbklh">Content</label>
                            <textarea class="form-control" id="contentbklh" name="content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="orderbklh">Order</label>
                            <input type="text" class="form-control" id="orderbklh" name="order">
                        </div>
                        <input class="form-control" id="bklhid" name="bklhid" hidden></input>
                        <input class="form-control" id="imagenamebklh" name="imagenamebklh" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deleteh4">Delete</button>
                        <button class="btn btn-primary" type="submit" name="edith4">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Add New es-->
    <div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Beyond KL es</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#table5" method="post" enctype="multipart/form-data" id="bklhs">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Name"
                                name="name">
                        </div>

                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Website" name="locationurl" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>



                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadbkles"
                                name="fileToUploadbkles">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_bkles">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal es-->
    <div class="modal fade" id="editesmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit beyondkl es</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#table5" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namebkles">Name</label>
                            <input type="text" class="form-control" id="namebkles" name="name">
                        </div>

                        <div class="form-group">
                            <label for="locationurlbkles">Website</label>
                            <input type="text" class="form-control" id="locationurlbkles" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contentbkles">Content</label>
                            <textarea class="form-control" id="contentbkles" name="content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="orderbkles">Order</label>
                            <input type="text" class="form-control" id="orderbkles" name="order">
                        </div>
                        <input class="form-control" id="bklesid" name="bklesid" hidden></input>
                        <input class="form-control" id="imagenamebkles" name="imagenamebkles" hidden></input>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-danger " name="deletebkles">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editbkles">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal KL @ A GLANCE-->
    <div class="modal fade" id="editimodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit KL @ A GLANCE</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#klglance" method="post" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="contentbkli">Content</label>
                            <textarea class="form-control" id="contentbkli" name="content"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" name="editklglance">Save Changes</button>
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
    <script src="js/editbeyondkl.js"></script>
    <script>
        document.getElementById("editnav").classList.add('active');
    </script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>