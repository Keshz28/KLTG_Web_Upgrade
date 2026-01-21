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

    <title>KLTG ADMIN - Highlights</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Upcoming Highlights Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>


                    <!-- DataTales wtd -->
                    <div class="card shadow mb-4" id="eve">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Upcoming Highlights
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
                                            <th scope="col">Title</th>
                                            <th scope="col">Image File Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Location URL</th>
                                            <th scope="col">Content</th>
                                            <th scope="col">Content2</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Day</th>
                                            <th scope="col">Month</th>
                                            <th scope="col">Year</th>
                                            <th scope="col">Website</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Facebook</th>
                                            <th scope="col">Instagram</th>
                                            <th scope="col">Tiktok</th>
                                            <th scope="col">Youtube</th>
                                            <th scope="col">Twitter</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT * FROM event ORDER BY event_order DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<th id="orderev-' . $row['event_id'] . '" scope="row">' . $row['event_order'] . '</th>';
                                            echo '<td class="text-center">
                                            <a href="#" class="" onclick="editmodalev(' . $row['event_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a><br>
                                            <a href="?orderupEV=' . $row['event_order'] . '&event_id=' . $row['event_id'] . '"><i class="fas fa-chevron-up"></i></a><br>
                                            <a href="?orderdownEV=' . $row['event_order'] . '&event_id=' . $row['event_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                            </td>';
                                            echo '<td id="nameev-' . $row['event_id'] . '">' . urldecode($row['event_title']) . '</td>';
                                            echo '<td id="filenameev-' . $row['event_id'] . '">' . $row['event_image'] . '</td>';
                                            echo '<td id="locationev-' . $row['event_id'] . '">' . urldecode($row['event_location']) . '</td>';
                                            echo '<td id="locationurlev-' . $row['event_id'] . '">' . urldecode($row['event_locationurl']) . '</td>';
                                            echo '<td id="contentev-' . $row['event_id'] . '">' . urldecode($row['event_content']) . '</td>';
                                            echo '<td id="content2ev-' . $row['event_id'] . '">' . urldecode($row['event_content2']) . '</td>';
                                            echo '<td id="hoursev-' . $row['event_id'] . '">' . urldecode($row['event_hours']) . '</td>';
                                            echo '<td id="phoneev-' . $row['event_id'] . '">' . $row['event_phone'] . '</td>';
                                            echo '<td id="dayev-' . $row['event_id'] . '">' . $row['event_day'] . '</td>';
                                            echo '<td id="monthev-' . $row['event_id'] . '">' . $row['event_month'] . '</td>';
                                            echo '<td id="yearev-' . $row['event_id'] . '">' . $row['event_year'] . '</td>';
                                            echo '<td id="websiteev-' . $row['event_id'] . '">' . $row['event_website'] . '</td>';
                                            echo '<td id="categoryev-' . $row['event_id'] . '">' . $row['event_category'] . '</td>';
                                            echo '<td id="facebookev-' . $row['event_id'] . '">' . $row['event_facebook'] . '</td>';
                                            echo '<td id="instagramev-' . $row['event_id'] . '">' . $row['event_instagram'] . '</td>';
                                            echo '<td id="tiktokev-' . $row['event_id'] . '">' . $row['event_tiktok'] . '</td>';
                                            echo '<td id="youtubeev-' . $row['event_id'] . '">' . $row['event_youtube'] . '</td>';
                                            echo '<td id="twitterev-' . $row['event_id'] . '">' . $row['event_twitter'] . '</td>';

                                        
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


    <!-- Add New Event-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Event</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#ev" method="post" enctype="multipart/form-data" id="ev">

                    <div class="modal-body" id="tagdiv2">

                        <div class="form-group">
                            <label for="nameev">Title</label>
                            <input type="text" class="form-control form-control-user" id="name" placeholder="Title (Do Not Use " ' " (single quotes) instead use " ` " (backtick))"
                                name="name">
                        </div>
                        <div class="form-group">
                        <label for="locationev">Location</label>
                            <textarea type="text" class="form-control form-control-user" id="location"
                                placeholder="Location" name="location" rows="1"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="locationurlev">Location URL</label>
                            <textarea type="text" class="form-control form-control-user" id="locationurl"
                                placeholder="Location URL" name="locationurl" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                        <label for="contentev">Content</label>
                            <textarea type="text" class="form-control form-control-user" id="content"
                                placeholder="Content" name="content" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="content2ev">Content2</label>
                            <textarea type="text" class="form-control form-control-user" id="content2"
                                placeholder="Content2" name="content2" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="hoursev">Operating Hours (Put "/" for new line )</label>
                            <textarea type="text" class="form-control form-control-user" id="hours"
                                placeholder="Operating Hours" name="hours" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="phoneev">Phone (Put "/" for new line )</label>
                            <textarea type="text" class="form-control form-control-user" id="phone" placeholder="Phone"
                                name="phone" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="dayev">Date</label>
                            <textarea type="text" class="form-control form-control-user" id="day" placeholder="Date"
                                name="day" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="monthev">Month</label>
                            <textarea type="text" class="form-control form-control-user" id="month" placeholder="Month (set Month as Digits (Exp: 1, 2, 3... etc))"
                                name="month" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                        <label for="yearev">Year</label>
                            <textarea type="text" class="form-control form-control-user" id="year" placeholder="Year (set Year is Numerical form (Exp: 2020, 2021, 2022... etc))"
                                name="year" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="categoryev">Category</label>
                            <textarea type="text" class="form-control form-control-user" id="category" placeholder="Category ( Holiday, Exhibition, Nightlife, Food, Happening, Entertainment )"
                                name="category" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="websiteev">Website</label>
                            <textarea type="text" class="form-control form-control-user" id="website" placeholder="Website Url"
                                name="website" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="facebookev">Facebook</label>
                            <textarea type="text" class="form-control form-control-user" id="facebook" placeholder="Facebook Url"
                                name="facebook" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="instagramev">Instagram</label>
                            <textarea type="text" class="form-control form-control-user" id="instagram" placeholder="Instagram Url"
                                name="instagram" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="tiktokev">Tiktok</label>
                            <textarea type="text" class="form-control form-control-user" id="tiktok" placeholder="Tiktok Url"
                                name="tiktok" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="youtubeev">Youtube</label>
                            <textarea type="text" class="form-control form-control-user" id="youtube" placeholder="Youtube Url"
                                name="youtube" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                        <label for="twitterev">Twitter</label>
                            <textarea type="text" class="form-control form-control-user" id="twitter" placeholder="Twitter Url"
                                name="twitter" rows="3"></textarea>
                        </div>
                        

                        <div class="form-group">
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUploadev" name="fileToUploadev">
                        </div>
                    </div>
                    <input class="form-control" id="eventid" name="eventid" hidden></input>
                    <input class="form-control" id="imagenameev" name="imagenameev" hidden></input>
                    <div class="modal-footer">
                    
                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_ev">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <!-- Edit Modal Event -->
<div class="modal fade" id="editevmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="?edit" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="tagdiv2">
                    <div class="form-group">
                        <label for="event_id">Id</label>
                        <input type="text" class="form-control" id="event_id" name="event_id" readonly>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" id="titleev" placeholder="Title" name="title">
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="locationev" placeholder="Location" name="location" rows="1"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="locationurlev" placeholder="Location URL" name="locationurl" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="contentev" placeholder="Content" name="content" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="content2ev" placeholder="Content2" name="content2" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="hoursev" placeholder="Operating Hours" name="hours" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="phoneev" placeholder="Phone" name="phone" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="number" class="form-control form-control-user" id="orderev" placeholder="Order" name="order" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="dayev" placeholder="Day" name="day" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="monthev" placeholder="Month" name="month" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="yearev" placeholder="Year" name="year" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="categoryev" placeholder="Category" name="category" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="websiteev" placeholder="Website" name="website" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="facebookev" placeholder="Facebook" name="facebook" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="instagramev" placeholder="Instagram" name="instagram" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="tiktokev" placeholder="Tiktok" name="tiktok" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="youtubeev" placeholder="Youtube" name="youtube" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-user" id="twitterev" placeholder="Twitter" name="twitter" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Select image to upload :</label>
                        <input type="file" class="form-control-file" id="fileToUploadev" name="fileToUploadev">
                    </div>
                    <input type="hidden" id="imagenameev" name="imagename">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="deleteev">Delete</button>
                    <button class="btn btn-primary" type="submit" name="editev">Save Changes</button>
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
    <script src="js/editevent.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>