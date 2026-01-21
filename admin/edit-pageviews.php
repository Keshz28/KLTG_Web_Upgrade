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

$query = "SELECT  url, sum(views) ,dateent FROM `pageview` GROUP BY url ";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
    // echo $row['url'];
    $url = $row['url'];
    $views = $row['sum(views)'];
    $views2 = 0;
    // echo '<br>';

    $query2 = "INSERT INTO pageview2 (url, views, views2) 
    VALUES('$url', '$views', '$views2') ON DUPLICATE KEY UPDATE views2=views2 , views=$views";
    mysqli_query($db, $query2);

    // echo $query2;
    // echo '<br>';


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

    <title>KLTG ADMIN - Edit Page Views</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Edit Page Views Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                        <!-- Pie Chart -->
                        <div class="col-xl-6 col-lg-6 h-100 w-100">
                            <div class="card shadow mb-4 h-100">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Page Views</h6>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body h-100 w-100">
                                    <div class="row h-100 w-100">
                                        <div class="col h-100">
                                            <?php

                                            $query = "SELECT url, views, sum(views)+sum(views2), dateent  FROM `pageview2` WHERE url NOT LIKE '%blog-%' AND  url NOT LIKE '%ebook-%' ORDER BY sum(views)+sum(views2) DESC ";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {

                                            echo'<p class="font-weight-bold text-center" style="font-size:10vh">
                                               '. $row['sum(views)+sum(views2)'].'
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

                    <!-- DataTales i -->
                    <div class="card shadow mb-4" id="editpageviews">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Page View<button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table1" aria-expanded="true"
                                    aria-controls="table1">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                </a>
                            </h6>

                        </div>

                        <div class="collapse show" id="table1">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable5" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>

                                                <th scope="col">URL</th>
                                                <th scope="col">Views</th>
                                                <th scope="col">Base Views</th>
                                                <th scope="col">Views Edited</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $totalpageviews = 0;
                                            $query = "SELECT id,url,views,views2,views+views2,dateent FROM pageview2 WHERE url NOT LIKE '%blog-%' AND  url NOT LIKE '%ebook-%'  ";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<tr >';
                                                echo '<th >' . $row['id'] . '</th>';
                                                echo '<td >' . urldecode($row['url']) . '</td>';
                                                echo '<td class="text-center" >' . $row['views'] . '</td>';
                                                // echo '<td id="locationurlbkli-' . $row['id'] . '">' . $row['sum(views2)']. '                                            <a href="#" class="" onclick="editmodali(' . $row['id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a>
                                                // </td>';
                                                echo '<td class="text-center"> ' . $row['views2'] . '
                                                <form action="edit-pageviews.php#editpageviews" method="post" enctype="multipart/form-data">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" value="" name="valueupdate" >  
                                                        <input name="hiddenid" value="' . $row['id'] . '"  hidden>
                                                        <input name="hiddenurl" value="' . $row['url'] . '"  hidden>
                                                        <input name="hiddenviews" value="' . $row['views'] . '"  hidden>
                                                        <div class="input-group-append" >
                                                        <button class="btn btn-primary" type="submit" name="editviews2"><i class=\'fas fa-plus\'></i></button>
                                                        </div>
                                                    </div>
                                                </form
                                                        </td>';
                                                echo '<td class="text-end" >' . $row['views+views2'] . '</td>';
                                                $totalpageviews += $row['views+views2'];

                                                // echo '</tr>';
                                            }
                                            ?>


                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class=row>

                                        <div class=col-8>
                                        </div>
                                        <div class=col-4>
                                        <p class="font-weight-bold" >Total Views : 
                                              <?php echo $totalpageviews?>
                                                </p>

                                        </div>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add New Beyond KL I</h5>
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
                            name="upload_bkli">Create</button>

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

                        <button type="submit" class="btn btn-danger " name="deletebkli">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editbkli">Save
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
                    <h5 class="modal-title" id="exampleModalLabel">Add New Beyond KL HS</h5>
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
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
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
                            name="upload_bklhs">Create</button>

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
                            <label for="locationurlbklhs">Location URL</label>
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

                        <button type="submit" class="btn btn-danger " name="deletebklhs">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editbklhs">Save
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
                    <h5 class="modal-title" id="exampleModalLabel">Add New Beyond KL W</h5>
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
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
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
                            name="upload_bklw">Create</button>

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
                            <label for="locationurlbklw">Location URL</label>
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

                        <button type="submit" class="btn btn-danger " name="deletebklw">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editbklw">Save
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
                    <h5 class="modal-title" id="exampleModalLabel">Add New Beyond KL H</h5>
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
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
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
                            name="upload_bklh">Create</button>

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
                            <label for="locationurlbklh">Location URL</label>
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

                        <button type="submit" class="btn btn-danger " name="deletebklh">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editbklh">Save
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
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
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
                            <label for="locationurlbkles">Location URL</label>
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
    <script src="js/editpageviews.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>