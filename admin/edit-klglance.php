<?php
/* ============================================================================
 *                            edit-klglance.php
 *
 *   Admin editor — KL at a Glance slides (klglance table).
 *   NOTE: stores RAW text (not htmlspecialchars'd), unlike other editors.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */ include('functions.php');

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit;
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

    <title>KLTG ADMIN - KL @ A Glance</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">

        <?php include('nav.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include('topnav.php'); ?>

                <div class="container-fluid">

                    <h1 class="h3 mb-2 text-gray-800">KL @ A Glance — Landmarks</h1>
                    <p class="mb-4">Add, edit, reorder and remove the landmark slides shown on the
                        <a href="../kl-glance.php" target="_blank">KL @ A Glance</a> page. Each landmark needs an image,
                        a title and a short description.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landmarks</h6>
                        </div>
                        <div class="card-header py-2 d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-grip-vertical"></i>
                                Drag a row by the handle to reorder. The new order saves automatically.
                            </small>
                            <a class="btn btn-sm btn-primary" href="#" data-toggle="modal"
                                data-target="#addKlglanceModal">
                                <i class="fas fa-plus"></i> New
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="klglance-scroll" style="max-height:62vh; overflow:auto;">
                                <table class="table table-bordered mb-0" id="klglanceTable" width="100%" cellspacing="0">
                                    <thead class="thead-light" style="position:sticky; top:0; z-index:2;">
                                        <tr>
                                            <th scope="col" style="width:44px"></th>
                                            <th scope="col" style="width:48px">Edit</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Title</th>
                                            <th scope="col" style="min-width:480px">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody id="klglanceSortable">
                                        <?php
                                        $query = "SELECT * FROM klglance ORDER BY klglance_order ASC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $id      = (int)$row['klglance_id'];
                                            $title   = $row['klglance_title'];
                                            $content = $row['klglance_content'];
                                            $img     = $row['klglance_image'];
                                            ?>
                                            <tr data-id="<?php echo $id; ?>">
                                                <td class="text-center align-middle klglance-handle" style="cursor:grab" title="Drag to reorder">
                                                    <i class="fas fa-grip-vertical text-secondary"></i>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a href="#" class="klglance-edit" data-toggle="modal"
                                                        data-target="#editKlglanceModal"
                                                        data-id="<?php echo $id; ?>"
                                                        data-title="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>"
                                                        data-content="<?php echo htmlspecialchars($content, ENT_QUOTES); ?>"
                                                        data-image="<?php echo htmlspecialchars($img, ENT_QUOTES); ?>"
                                                        title="Edit"><i class="fas fa-pen"></i></a>
                                                </td>
                                                <td>
                                                    <?php if ($img): ?>
                                                        <img src="../assets/img/kl_glance/<?php echo htmlspecialchars($img, ENT_QUOTES); ?>"
                                                            alt="" style="max-width:120px;height:auto;border-radius:4px"><br>
                                                    <?php endif; ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars($img, ENT_QUOTES); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($title, ENT_QUOTES); ?></td>
                                                <td><?php echo htmlspecialchars($content, ENT_QUOTES); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div id="klglanceSaveStatus" class="small mt-2" style="min-height:18px;"></div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; KL The Guide</span>
                    </div>
                </div>
            </footer>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

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

    <!-- Add New Landmark Modal -->
    <div class="modal fade" id="addKlglanceModal" tabindex="-1" role="dialog" aria-labelledby="addKlglanceLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKlglanceLabel">Add New Landmark</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addnew#klglanceTable" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addName">Title</label>
                            <input type="text" class="form-control" id="addName" name="name"
                                placeholder="e.g. KL Tower" required>
                        </div>
                        <div class="form-group">
                            <label for="addContent">Description</label>
                            <textarea class="form-control" id="addContent" name="content" rows="5"
                                placeholder="Short description shown on the slide" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fileToUploadklglanceAdd">Image (JPG, PNG, GIF — max 5MB)</label>
                            <input type="file" class="form-control-file" id="fileToUploadklglanceAdd"
                                name="fileToUploadklglance" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" name="upload_klglance">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Landmark Modal -->
    <div class="modal fade" id="editKlglanceModal" tabindex="-1" role="dialog" aria-labelledby="editKlglanceLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKlglanceLabel">Edit Landmark</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit#klglanceTable" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Title</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editContent">Description</label>
                            <textarea class="form-control" id="editContent" name="content" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Current image</label><br>
                            <img id="editImagePreview" src="" alt="" style="max-width:160px;height:auto;border-radius:4px">
                        </div>
                        <div class="form-group">
                            <label for="fileToUploadklglanceEdit">Replace image (optional)</label>
                            <input type="file" class="form-control-file" id="fileToUploadklglanceEdit"
                                name="fileToUploadklglance" accept="image/*">
                        </div>
                        <input type="hidden" id="editId" name="klglanceid">
                        <input type="hidden" id="editImageName" name="imagenameklglance">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" name="deleteklglance"
                            onclick="return confirm('Delete this landmark? This cannot be undone.');">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editklglance">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        $(document).ready(function () {
            // Populate the edit modal from the clicked row's data attributes
            $('.klglance-edit').on('click', function () {
                var d = this.dataset;
                $('#editId').val(d.id);
                $('#editName').val(d.title);
                $('#editContent').val(d.content);
                $('#editImageName').val(d.image);
                $('#editImagePreview').attr('src', '../assets/img/kl_glance/' + d.image);
            });

            // Drag-and-drop reordering — saves via AJAX, no page reload.
            var tbody = document.getElementById('klglanceSortable');
            var status = document.getElementById('klglanceSaveStatus');

            function setStatus(msg, cls) {
                status.textContent = msg;
                status.className = 'small mt-2 ' + (cls || 'text-muted');
            }

            if (tbody && window.Sortable) {
                Sortable.create(tbody, {
                    handle: '.klglance-handle',
                    animation: 150,
                    ghostClass: 'table-active',
                    onEnd: function () {
                        var ids = Array.prototype.map.call(
                            tbody.querySelectorAll('tr'),
                            function (tr) { return tr.getAttribute('data-id'); }
                        );
                        setStatus('Saving order…', 'text-muted');
                        var params = new URLSearchParams();
                        params.append('table', 'klglance');
                        ids.forEach(function (id) { params.append('ids[]', id); });

                        fetch('reorder.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: params.toString()
                        })
                        .then(function (r) { return r.json(); })
                        .then(function (res) {
                            if (res && res.success) {
                                setStatus('✓ Order saved', 'text-success');
                            } else {
                                setStatus('Could not save order: ' + ((res && res.error) || 'unknown error'), 'text-danger');
                            }
                        })
                        .catch(function () {
                            setStatus('Could not save order (network error).', 'text-danger');
                        });
                    }
                });
            }
        });
    </script>

    <script>
        // Show alert message stored in session by the handler
        <?php if (isset($_SESSION['alert_msg'])): ?>
            alert("<?php echo addslashes($_SESSION['alert_msg']); ?>");
            <?php unset($_SESSION['alert_msg']); unset($_SESSION['alert_type']); ?>
        <?php endif; ?>
    </script>

</body>

</html>
