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

    <title>KLTG ADMIN - Landing Page</title>

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
                    <h1 class="h3 mb-2 text-gray-800">QR Code Landing Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">QR Code Landing Page
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <form action="export.php" method="post">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" required>
                                <br><br>
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date" required>
                                <br><br>
                                <button type="submit" name="export" class="btn btn-primary">Export Data</button>
                            </form>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Country</th>
                                            <th scope="col">State</th>
                                            <th scope="col">City</th>
                                            <th scope="col">Submission_Date</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM contact_forms ORDER BY submission_date DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) { ?>

                                            <tr>
                                                <td>
                                                    <?php echo $row['id'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['email'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['phone'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['country'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['state'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['city'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['submission_date'] ?>
                                                </td>
                                            </tr>

                                        <?php }
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
                        <span aria-hidden="true">×</span>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add New Medical Tourism Dental</h5>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Dental</h5>
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