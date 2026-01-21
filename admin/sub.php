<?php include('functions.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



$flash_message = '';
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // clear after showing once
}

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

    <title>KLTG ADMIN - Subscription</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Summernote LITE CSS (no Bootstrap dependency) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        .et-row{display:grid;grid-template-columns:280px 1fr;gap:16px}
        .et-field{margin-bottom:10px}
        .chip{display:inline-block;padding:4px 8px;border:1px solid #ddd;border-radius:12px;margin-right:6px;cursor:pointer}
        .preview-wrap{border:1px solid #ddd;border-radius:8px;overflow:hidden}
        .preview-toolbar{padding:8px;background:#fafafa;border-bottom:1px solid #eee;display:flex;gap:8px;align-items:center}
        .preview-frame{width:100%;height:640px;border:0}
    </style>

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
                    <h1 class="h3 mb-2 text-gray-800">Subscribers</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Email
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                                $countQuery = "SELECT COUNT(*) AS total FROM emailsub";
                                $countResult = mysqli_query($db, $countQuery);
                                $countRow = mysqli_fetch_assoc($countResult);
                                $total = $countRow['total'];

                                

                                // Flash message for delete data
                                // if (isset($_GET['message']) && $_GET['message'] == 'deleted') {
                                //     echo '<div class="alert alert-success" id="flash-message">Selected records deleted successfully.</div>';
                                // }
                            if (!empty($flash_message)) : ?>
                                <div class="alert alert-success" id="flash-message"><?php echo $flash_message; ?></div>
                            <?php endif; ?>

                            <div class="mt-3">
                                <strong>Total Subscribers:</strong> <?php echo $total; ?>
                            </div>
                            <div class="table-responsive">
                                <br>
                                <!-- filter by date -->
                                <form id="filterForm">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date" required>
                                    <label for="end_date">End Date:</label>
                                    <input type="date" id="end_date" name="end_date" required>
                                    <button type="submit" class="btn btn-info">Filter</button>
                                </form>
                                <!-- export data -->
                                <form action="sub_handler.php" method="post" id="exportForm">
                                    <input type="hidden" name="search" id="export_search">
                                    <input type="hidden" name="start_date" id="export_start_date">
                                    <input type="hidden" name="end_date" id="export_end_date">
                                    <button type="submit" name="export_subs" class="btn btn-primary">Export Data</button>
                                </form>
                                <form action="sub_handler.php" method="post" onsubmit="return confirm('Are you sure you want to delete selected records?');">
                                <button type="submit" name="delete_subs" class="btn btn-danger mb-3">Delete Selected</button>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;"><input type="checkbox" id="selectAll"></th>
                                            <th scope="col" style="width:5%;">#</th>
                                            <th scope="col" style="width:20%;">Email</th>
                                            <th scope="col" style="width:25%;">Country</th>
                                            <th scope="col" style="width:20%;">Monthly Subs</th>
                                            <th scope="col" style="width:30%;">Date</th>

                                        </tr>
                                    </thead>

                                    
                                </table>
                                </form>
                            </div>
                            
                        </div>
                    </div>


                    <!-- Approach -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Send E-mail</h6>
                        </div>
                        
                        <form action="?queuemail" method="post" enctype="multipart/form-data">

                            <div class="card-body">

                                <div class="form-group">
                                    <label for="emailtitle">Email Title</label>
                                    <textarea type="text" class="form-control" id="emailtitle"
                                        name="emailtitle"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="emailcontent">Query Name ( For Tracking )</label>
                                    <textarea type="text" class="form-control" id="emailcontent"
                                        name="emailcontent"></textarea>
                                </div>
                                <div class="dropdown">
                                    <div class="form-row ">
                                        <div class="col-6 ">
                                            <div class="input-group mb-3">
                                                <select class="form-control" id="exampleFormControlSelect1" name="file"
                                                    onchange="">

                                                    <?php
                                                    $query8 = "SELECT * FROM emailfile";

                                                    $result8 = mysqli_query($db, $query8);
                                                    while ($row8 = mysqli_fetch_assoc($result8)) {
                                                        ?>
                                                        <option value="email/<?php echo $row8['filename'] ?>">
                                                            <?php echo $row8['name'] ?>
                                                        </option>


                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="input-group-prepend">
                                                    <button  type="button" onclick=" trytologall()" class="btn btn-primary ">

                                                        <span class="text">Preview</span>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-6 ">

                                            <button type="submit" name="queuemail"
                                                class="btn btn-primary btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-flag"></i>
                                                </span>
                                                <span class="text">Send Mail</span>
                                            </button>   
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $subscriberemail = "(Subscriber Name)";
                                $content = "(Content)";

                                ?>
                                <div id="test2">
                                </div>


                            </div>
                        </form>
                    </div>

                    <form id="subscribe-form">
  <input type="email" id="email" name="email" required placeholder="Enter your email" />
  <button type="submit">Subscribe</button>
</form>

<script>
document.getElementById('subscribe-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const email = document.getElementById('email').value.trim();
  const body  = new URLSearchParams({ email });

  const res  = await fetch('sub_handler.php?action=subscribe', {
    method: 'POST',
    headers: { 'Accept': 'application/json' },
    body
  });

  const data = await safeJson(res);
  if (data.ok) {
    alert(data.sent
      ? 'Subscribed! Check your inbox for the welcome email.'
      : 'Subscribed! We’ll send your welcome email shortly.');
  } else {
    alert(data.error || 'Subscription failed.');
  }
});
</script>


                    <!-- Email Templates (NEW) -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Email Templates</h6>
                        </div>
                        <div class="card-body">

                            <div class="et-row">
                                <div>
                                    <div class="et-field">
    <label class="form-label fw-bold">Template</label>
    <div class="input-group">
        <select id="tplSlug" class="form-control">
            <!-- Populated by JS -->
        </select>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="btnNewTemplate">New</button>
        </div>
    </div>
    <div class="form-text mt-1" id="draftStatus"></div>
</div>

                                    <div class="et-field">
                                        <label class="form-label fw-bold">Subject</label>
                                        <input type="text" id="etSubject" class="form-control" placeholder="Subject">
                                    </div>

                                    <div class="et-field">
                                        <label class="form-label fw-bold">Preheader</label>
                                        <input type="text" id="etPreheader" class="form-control" placeholder="Short preview line">
                                    </div>

                                    <div class="et-field">
                                        <label class="form-label fw-bold">From Name</label>
                                        <input type="text" id="etFromName" class="form-control" placeholder="KL The Guide">
                                        <div class="form-text">From email stays in .env</div>
                                    </div>

                                    <div class="et-field">
                                        <label class="form-label fw-bold">Footer (optional)</label>
                                        <textarea id="etFooter" class="form-control" rows="4" placeholder="Team KL The Guide"></textarea>
                                    </div>

                                    <div class="et-field">
                                        <label class="form-label fw-bold">Placeholders</label><br>
                                        <span class="chip" data-ph="{{email}}">email</span>
                                        <span class="chip" data-ph="{{date}}">date</span>
                                        <span class="chip" data-ph="{{site_name}}">site_name</span>
                                    </div>

                                    <div class="et-field">
                                        <label class="form-label fw-bold d-block">Assets</label>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnUpload">Upload Image</button>
                                        <input type="file" id="assetFile" accept="image/*" hidden>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLibrary">Open Library</button>
                                        <div id="assetList" class="small mt-2"></div>
                                    </div>
<div class="et-field">
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-secondary" id="btnSaveDraft">Save as Draft</button>
        <button class="btn btn-primary" id="btnPublish">Publish</button>
        <button class="btn btn-outline-primary" id="btnPreview">Preview</button>
        <button class="btn btn-outline-success" id="btnSendTest">Send Test</button>
        <button class="btn btn-success" id="btnUseTemplate">Use This Template</button>
    </div>
    <div class="form-text mt-2">
        Click "Use This Template" to set it as the welcome email for new subscribers.
    </div>
</div>
                                </div>

                                <div>
                                    <div class="et-field">
                                        <label class="form-label fw-bold">Body (WYSIWYG)</label>
                                        <textarea id="etBody"></textarea>
                                    </div>

                                    <div class="preview-wrap">
                                        <div class="preview-toolbar">
                                            <strong class="me-2">Live Preview</strong>
                                            <button class="btn btn-sm btn-outline-secondary" data-w="600" id="pvDesktop">Desktop 600px</button>
                                            <button class="btn btn-sm btn-outline-secondary" data-w="375" id="pvMobile">Mobile 375px</button>
                                        </div>
                                        <iframe id="previewFrame" class="preview-frame"></iframe>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Approach -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Send Push Notification</h6>
                        </div>
                        <form action="?sendpushnotification" method="post" enctype="multipart/form-data">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="pushtitle">Title</label>
                                    <textarea type="text" class="form-control" id="pushtitle"
                                        name="pushtitle"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="pushcontent">Content</label>
                                    <textarea type="text" class="form-control" id="pushcontent"
                                        name="pushcontent"></textarea>
                                </div>
                                <button type="submit" name="sendpushnotification"
                                    class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-flag"></i>
                                    </span>
                                    <span class="text">Send</span>
                                </button>
                                <!-- <p class="mb-0">Before working with this theme, you should become familiar with the
                                Bootstrap framework, especially the utility classes.</p> -->
                            </div>
                        </form>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Email Queue
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Send To</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Init Time</th>
                                            <th scope="col">Send Time</th>
                                            <th scope="col">Status</th>

                                        </tr>
                                    </thead>
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
                <form action="?addnew" method="post" enctype="multipart/form-data" id="mthc">

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
                            <label for="exampleFormControlFile1">Select image to upload :</label>
                            <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
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

    <!-- Edit Modal-->
    <div class="modal fade" id="edithcmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
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
                            <input type="text" class="form-control" id="locationhc" name="location">
                        </div>
                        <div class="form-group">
                            <label for="locationurlhc">Location URL</label>
                            <input type="text" class="form-control" id="locationurlhc" name="locationurl">
                        </div>
                        <div class="form-group">
                            <label for="contenthc">Content</label>
                            <input type="text" class="form-control" id="contenthc" name="content">
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
                        <button class="btn btn-primary" type="submit" value="Upload Image" name="editmthc">Save
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
    
    <!-- Summernote LITE JS (loaded right after jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
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
    <script>document.getElementById("subnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>


    <script>
  
    $(document).ready(function () {

        async function safeJson(response) {
        const text = await response.text();
        try { return JSON.parse(text); }
        catch {
            return { ok: false, status: response.status, error: text || response.statusText };
        }
        }

        function isEmail(v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v || '');
        }
        window.trytologall = function () {
        const selectBox = document.getElementById("exampleFormControlSelect1");
        const selectedValue = selectBox.options[selectBox.selectedIndex].value;
        window.open(selectedValue, '_blank');
        };

        const startDateInput = document.getElementById("start_date");
        const endDateInput = document.getElementById("end_date");
        const filterForm = document.getElementById("filterForm");
        const tableElement = $('#dataTable');
        
        // Get today's date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];

        // Restrict both date inputs to today or earlier
        startDateInput.max = today;
        endDateInput.max = today;

        // Set minimum end date when start date changes
        startDateInput.addEventListener("change", function () {
            endDateInput.min = startDateInput.value;
            endDateInput.max = today;
        });

        // Set maximum start date when end date changes
        endDateInput.addEventListener("change", function () {
            startDateInput.max = endDateInput.value;
        });

        // Sync export form with filters
        document.getElementById("filterForm").addEventListener("submit", function () {
            document.getElementById("export_start_date").value = document.getElementById("start_date").value;
            document.getElementById("export_end_date").value = document.getElementById("end_date").value;

            // Get search box value from DataTable
            const searchValue = $('.dataTables_filter input').val();
            document.getElementById("export_search").value = searchValue;
        });

        // Validate on form submission
        filterForm.addEventListener("submit", function (e) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const now = new Date();

            if (startDate > endDate) {
                e.preventDefault();
                alert("Start date cannot be after end date.");
                return;
            }

            if (startDate > now || endDate > now) {
                e.preventDefault();
                alert("Dates cannot be in the future.");
            }
        });

        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().clear().destroy();
        }

        if ($.fn.DataTable.isDataTable('#dataTable2')) {
            $('#dataTable2').DataTable().clear().destroy();
        }

        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            order: [[0, "desc"]],
            ajax: {
                url: "sub_handler.php",
                type: "GET",
                data: function (d) {
                    d.table = "emailsub";
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: "checkbox", orderable: false },
                { data: "id" },
                { data: "email" },
                { data: "country" },
                { data: "consent" },
                { data: "date" }
            ]
        });

        let table2 = $('#dataTable2').DataTable({
            processing: true,
            serverSide: true,
            order: [[0, "desc"]],
            ajax: {
                url: "sub_handler.php",
                type: "GET",
                data: function (d) {
                    d.table = "mailqueue";
                }
            },
            columns: [
                { data: "id" },
                { data: "sendto" },
                { data: "sendtitle" },
                { data: "init_time" },
                { data: "send_time" },
                { data: "sendstatus" },
            ]
        });

        // Handle filter form submit
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            // Set export form values too
            $('#export_start_date').val($('#start_date').val());
            $('#export_end_date').val($('#end_date').val());
            table.ajax.reload();
        });
        
        // handle "select-all" checkbox
        document.getElementById("selectAll").addEventListener("change", function () {
            const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // When user submits filter form (and clicks Export)
        document.querySelector("form[action='sub_handler.php']").addEventListener("submit", function () {
            const searchValue = $('.dataTables_filter input').val(); // get value from search box
            document.getElementById("export_search").value = searchValue; // set to export form
        });

        // Auto-hide flash message after 3 seconds
        setTimeout(function () {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.transition = "opacity 0.5s ease-out";
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 3000);

        // Email Templates functionality
(function() {
    // Check if jQuery and Summernote are loaded
    if (!window.jQuery) {
        console.error('jQuery not loaded');
        return;
    }
    if (typeof $.fn.summernote !== 'function') {
        console.error('Summernote not loaded');
        return;
    }

    const $slug = document.getElementById('tplSlug');
    const $subject = document.getElementById('etSubject');
    const $preheader = document.getElementById('etPreheader');
    const $fromName = document.getElementById('etFromName');
    const $footer = document.getElementById('etFooter');
    const $body = $('#etBody');
    const $frame = document.getElementById('previewFrame');
    const $draftStatus = document.getElementById('draftStatus');

    let currentDraft = 1; // Track current template's draft status
    let allTemplates = []; // Store all templates for reference

    // Initialize Summernote
    $body.summernote({
        height: 320,
        dialogsInBody: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['codeview']]
        ]
    });

    // Load templates list
   // Load templates list
function loadTemplatesList() {
    fetch('sub_handler.php?action=templates.list')
        .then(safeJson)
        .then(j => {
            if (!j.data) return;
            
            allTemplates = j.data; // Store for later use
            $slug.innerHTML = '';
            
            // Group by draft status
            const drafts = j.data.filter(t => t.draft === 1);
            const published = j.data.filter(t => t.draft === 0);
            
            // Add published templates
            if (published.length > 0) {
                const pubGroup = document.createElement('optgroup');
                pubGroup.label = 'Published Templates';
                published.forEach(row => {
                    const opt = document.createElement('option');
                    opt.value = row.slug;
                    const activeLabel = row.is_active === 1 ? ' ⭐ [ACTIVE]' : '';
                    opt.textContent = row.slug + ' — ' + row.subject + activeLabel;
                    opt.setAttribute('data-draft', '0');
                    opt.setAttribute('data-active', row.is_active || '0');
                    pubGroup.appendChild(opt);
                });
                $slug.appendChild(pubGroup);
            }
            
            // Add draft templates
            if (drafts.length > 0) {
                const draftGroup = document.createElement('optgroup');
                draftGroup.label = 'Draft Templates';
                drafts.forEach(row => {
                    const opt = document.createElement('option');
                    opt.value = row.slug;
                    const activeLabel = row.is_active === 1 ? ' ⭐ [ACTIVE]' : '';
                    opt.textContent = row.slug + ' — ' + row.subject + ' [DRAFT]' + activeLabel;
                    opt.setAttribute('data-draft', '1');
                    opt.setAttribute('data-active', row.is_active || '0');
                    draftGroup.appendChild(opt);
                });
                $slug.appendChild(draftGroup);
            }
            
            // Load first available template
            if ($slug.options.length > 0) {
                // Try to select active template first
                const activeOpt = Array.from($slug.options).find(o => o.getAttribute('data-active') === '1');
                if (activeOpt) {
                    $slug.value = activeOpt.value;
                } else {
                    const w = Array.from($slug.options).find(o => o.value === 'welcome_subscribe');
                    if (w) {
                        $slug.value = 'welcome_subscribe';
                    } else {
                        $slug.selectedIndex = 0;
                    }
                }
                loadTemplate($slug.value);
            }
        })
        .catch(err => {
            console.error('Error loading templates:', err);
        });
}

    // Initial load
    loadTemplatesList();

    // Handle template change
    $slug.addEventListener('change', function() {
        console.log('Template changed to:', $slug.value);
        loadTemplate($slug.value);
    });

    // New template button
    document.getElementById('btnNewTemplate').addEventListener('click', function() {
        const newSlug = prompt('Enter new template name (slug):', 'my_new_template');
        if (!newSlug) return;
        
        // Check if slug already exists
        const exists = allTemplates.some(t => t.slug === newSlug.trim());
        if (exists) {
            alert('Template with this name already exists!');
            return;
        }
        
        // Clear form for new template
        $subject.value = '';
        $preheader.value = '';
        $fromName.value = 'KL The Guide';
        $footer.value = 'Team KL The Guide';
        $body.summernote('code', '<p>Start writing your email here...</p>');
        
        // Add new option to dropdown
        const opt = document.createElement('option');
        opt.value = newSlug.trim();
        opt.textContent = newSlug.trim() + ' [NEW]';
        opt.setAttribute('data-draft', '1');
        opt.selected = true;
        
        // Add to Draft Templates group or create it
        let draftGroup = Array.from($slug.children).find(g => g.label === 'Draft Templates');
        if (!draftGroup) {
            draftGroup = document.createElement('optgroup');
            draftGroup.label = 'Draft Templates';
            $slug.appendChild(draftGroup);
        }
        draftGroup.appendChild(opt);
        
        currentDraft = 1;
        updateDraftStatus();
    });

    function loadTemplate(slugValue) {
        const chosen = (typeof slugValue === 'string' && slugValue.length) ? slugValue : $slug.value;
        if (!chosen) return;
        
        console.log('Loading template:', chosen);
        
        fetch('sub_handler.php?action=templates.get&slug=' + encodeURIComponent(chosen))
            .then(safeJson)
            .then(j => {
                if (!j.ok || !j.data) {
                    console.error('Template not found:', j);
                    return;
                }
                const t = j.data;
                $subject.value = t.subject || '';
                $preheader.value = t.preheader || '';
                $fromName.value = t.from_name || 'KL The Guide';
                $footer.value = t.footer_html || '';
                $body.summernote('code', t.body_html || '');
                
                // Update current draft status
                currentDraft = t.draft || 0;
                updateDraftStatus();
                previewNow();
            })
            .catch(err => {
                console.error('Error loading template:', err);
            });
    }

    function updateDraftStatus() {
    const selected = $slug.options[$slug.selectedIndex];
    const isDraft = selected?.getAttribute('data-draft') === '1' || currentDraft === 1;
    const isActive = selected?.getAttribute('data-active') === '1';
    
    let badges = '';
    if (isActive) {
        badges += '<span class="badge badge-success mr-1">⭐ ACTIVE</span>';
    }
    if (isDraft) {
        badges += '<span class="badge badge-warning">Draft</span>';
    } else if (!isActive) {
        badges += '<span class="badge badge-info">Published</span>';
    }
    
    $draftStatus.innerHTML = badges;
}

    // Placeholder chips
    document.querySelectorAll('.chip').forEach(ch => {
        ch.addEventListener('click', () => {
            const ph = ch.getAttribute('data-ph');
            $body.summernote('pasteHTML', ph);
        });
    });

    // Assets
    document.getElementById('btnUpload').addEventListener('click', () => {
        document.getElementById('assetFile').click();
    });

    document.getElementById('assetFile').addEventListener('change', (e) => {
        const f = e.target.files[0];
        if (!f) return;
        const fd = new FormData();
        fd.append('action', 'assets.upload');
        fd.append('file', f);
        fetch('sub_handler.php?action=assets.upload', { method:'POST', body: fd })
            .then(safeJson)
            .then(j => {
                if (j.ok) {
                    const url = j.data.url;
                    const a = document.createElement('div');
                    a.innerHTML = `<a href="${url}" target="_blank">${url}</a>`;
                    document.getElementById('assetList').prepend(a);
                    $body.summernote('insertImage', url);
                } else {
                    alert(j.error || 'Upload failed');
                }
            });
    });

    document.getElementById('btnLibrary').addEventListener('click', () => {
        fetch('sub_handler.php?action=assets.list')
            .then(safeJson)
            .then(j => {
                const list = document.getElementById('assetList');
                list.innerHTML = '';
                (j.data || []).forEach(it => {
                    const d = document.createElement('div');
                    d.innerHTML = `<a href="${it.url}" target="_blank">${it.filename}</a> (${(it.size/1024).toFixed(1)} KB)`;
                    d.style.cursor = 'pointer';
                    d.addEventListener('click', () => $body.summernote('insertImage', it.url));
                    list.appendChild(d);
                });
            });
    });

    // Save as Draft
    document.getElementById('btnSaveDraft').addEventListener('click', async () => {
        await saveTemplate(1); // 1 = draft
    });

    // Publish
    document.getElementById('btnPublish').addEventListener('click', async () => {
        if (!$subject.value.trim()) { 
            alert('Subject is required for published templates.'); 
            return; 
        }
        if (!confirm('Publish this template? It will be available for sending emails.')) return;
        await saveTemplate(0); // 0 = published
    });

    async function saveTemplate(draftStatus) {
        const slugValue = $slug.value.trim();
        if (!slugValue) { 
            alert('Please enter a template name.'); 
            return; 
        }

        const payload = new URLSearchParams();
        payload.append('action', 'templates.save');
        payload.append('slug', slugValue);
        payload.append('subject', $subject.value);
        payload.append('preheader', $preheader.value);
        payload.append('from_name', $fromName.value);
        payload.append('body_html', $body.summernote('code'));
        payload.append('footer_html', $footer.value);
        payload.append('draft', draftStatus);

        const j = await fetch('sub_handler.php?action=templates.save', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: payload.toString()
        }).then(safeJson);

        if (j.ok) {
            alert(draftStatus === 1 ? 'Saved as draft' : 'Published successfully');
            currentDraft = draftStatus;
            // Reload template list to update UI
            loadTemplatesList();
        } else {
            alert(j.error || 'Save failed');
        }
    }

    function previewNow(sampleEmail) {
        const payload = new URLSearchParams();
        payload.append('action', 'templates.preview');
        payload.append('subject', $subject.value);
        payload.append('preheader', $preheader.value);
        payload.append('from_name', $fromName.value);
        payload.append('body_html', $body.summernote('code'));
        payload.append('footer_html', $footer.value);
        if (sampleEmail) payload.append('sample_email', sampleEmail);

        fetch('sub_handler.php?action=templates.preview', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: payload.toString()
        })
        .then(r => r.text())
        .then(html => {
            const doc = $frame.contentDocument || $frame.contentWindow.document;
            doc.open(); doc.write(html); doc.close();
        });
    }

    document.getElementById('btnPreview').addEventListener('click', () => previewNow());
    
    document.getElementById('btnSendTest').addEventListener('click', async () => {
        const email = prompt('Send test to which email?', '<?php echo htmlspecialchars($_SESSION["username"] ?? "test@example.com"); ?>');
        if (!email) return;
        if (!isEmail(email)) { alert('Enter a valid email'); return; }
        if (!$subject.value.trim()) { alert('Missing subject. Please fill the Subject field first.'); return; }

        const payload = new URLSearchParams();
        payload.append('action', 'templates.sendtest');
        payload.append('to_email', email.trim());
        payload.append('subject', $subject.value);
        payload.append('preheader', $preheader.value);
        payload.append('from_name', $fromName.value);
        payload.append('body_html', $body.summernote('code'));
        payload.append('footer_html', $footer.value);

        const j = await fetch('sub_handler.php?action=templates.sendtest', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: payload.toString()
        }).then(safeJson);

        if (j.ok) alert('Test email sent to ' + email);
        else alert(j.error || 'Send failed');
    });

   // Use this template for new subscriptions
document.getElementById('btnUseTemplate').addEventListener('click', async () => {
    const slugValue = $slug.value.trim();
    if (!slugValue) {
        alert('Please select a template first.');
        return;
    }

    if (!$subject.value.trim()) {
        alert('Subject is required. Please fill the Subject field first.');
        return;
    }

    const confirmed = confirm(
        `Set "${slugValue}" as the active welcome template?\n\n` +
        `This template will be sent to all new subscribers when they sign up.\n\n` +
        `Current subject: ${$subject.value}`
    );

    if (!confirmed) return;

    // Show loading
    const btn = document.getElementById('btnUseTemplate');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Setting...';

    try {
        const payload = new URLSearchParams();
        payload.append('action', 'templates.setactive');
        payload.append('slug', slugValue);

        const j = await fetch('sub_handler.php?action=templates.setactive', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: payload.toString()
        }).then(safeJson);

        btn.disabled = false;
        btn.textContent = originalText;

        if (j.ok) {
            alert('✅ Template activated!\n\nNew subscribers will receive this email when they sign up.');
            // Reload template list to show active indicator
            loadTemplatesList();
        } else {
            alert('❌ Error: ' + (j.error || 'Failed to set template'));
        }
    } catch (err) {
        btn.disabled = false;
        btn.textContent = originalText;
        alert('❌ Error: ' + err.message);
    }
});
    // Preview width toggles
    document.getElementById('pvDesktop').addEventListener('click', (e) => {
        e.preventDefault();
        $frame.style.width = '600px';
    });
    document.getElementById('pvMobile').addEventListener('click', (e) => {
        e.preventDefault();
        $frame.style.width = '375px';
    });
})();
    })

    </script>

</body>

</html>