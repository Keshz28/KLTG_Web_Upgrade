<?php
/* ============================================================================
 *                          edit-advertisement.php
 *
 *   Admin editor — Advertisements.
 *   Pairs with track_ad_click.php for click tracking.
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

$ads_result = mysqli_query($db, "SELECT * FROM advertisement ORDER BY created_at DESC");
$ads = [];
if ($ads_result) {
    while ($row = mysqli_fetch_assoc($ads_result)) {
        $ads[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KLTG ADMIN - Advertisement</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .ad-thumb {
            max-width: 120px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .badge-active   { background-color: #1cc88a; color: #fff; }
        .badge-inactive { background-color: #e74a3b; color: #fff; }
    </style>
</head>

<body id="page-top">
<div id="wrapper">

    <?php include('nav.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('topnav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-2 text-gray-800">Advertisement Popup</h1>
                <p class="mb-4">Manage the popup advertisement shown to visitors on every page. Only one ad can be active at a time.</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $e) echo htmlspecialchars($e) . '<br>'; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors2)): ?>
                    <div class="alert alert-success">
                        <?php foreach ($errors2 as $e) echo htmlspecialchars($e) . '<br>'; ?>
                    </div>
                <?php endif; ?>

                <!-- Active Ad Preview -->
                <?php
                $active_ad = null;
                foreach ($ads as $a) { if ($a['is_active']) { $active_ad = $a; break; } }
                ?>
                <?php if ($active_ad): ?>
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-check-circle"></i> Currently Active Advertisement</h6>
                    </div>
                    <div class="card-body d-flex align-items-center">
                        <img src="../assets/img/advertisement/<?php echo htmlspecialchars($active_ad['image']); ?>"
                             alt="Active Ad"
                             style="max-height:150px; max-width:250px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6;">
                        <div class="ml-4">
                            <p class="mb-1"><strong>Link:</strong>
                                <a href="<?php echo htmlspecialchars($active_ad['link_url']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($active_ad['link_url']); ?>
                                </a>
                            </p>
                            <p class="mb-0 text-muted">ID #<?php echo (int)$active_ad['id']; ?> &mdash; Added <?php echo htmlspecialchars($active_ad['created_at']); ?></p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No active advertisement. Activate one from the table below.</div>
                <?php endif; ?>

                <!-- Ads Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All Advertisements</h6>
                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAdModal">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Preview</th>
                                        <th>Link URL</th>
                                        <th>Status</th>
                                        <th>Added</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($ads)): ?>
                                    <tr><td colspan="6" class="text-center text-muted">No advertisements yet. Add one above.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($ads as $ad): ?>
                                    <tr>
                                        <td><?php echo (int)$ad['id']; ?></td>
                                        <td>
                                            <img src="../assets/img/advertisement/<?php echo htmlspecialchars($ad['image']); ?>"
                                                 class="ad-thumb" alt="Ad Image">
                                        </td>
                                        <td style="max-width:200px; word-break:break-all;">
                                            <a href="<?php echo htmlspecialchars($ad['link_url']); ?>" target="_blank">
                                                <?php echo htmlspecialchars($ad['link_url']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($ad['is_active']): ?>
                                                <span class="badge badge-active px-2 py-1">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-inactive px-2 py-1">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($ad['created_at']); ?></td>
                                        <td>
                                            <a href="edit-advertisement.php?toggle_ad=<?php echo (int)$ad['id']; ?>&status=<?php echo (int)$ad['is_active']; ?>"
                                               class="btn btn-sm <?php echo $ad['is_active'] ? 'btn-warning' : 'btn-success'; ?>"
                                               title="<?php echo $ad['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                <i class="fas <?php echo $ad['is_active'] ? 'fa-toggle-off' : 'fa-toggle-on'; ?>"></i>
                                                <?php echo $ad['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                            </a>
                                            <button class="btn btn-sm btn-info"
                                                    data-toggle="modal"
                                                    data-target="#editLinkModal"
                                                    data-id="<?php echo (int)$ad['id']; ?>"
                                                    data-link="<?php echo htmlspecialchars($ad['link_url'], ENT_QUOTES); ?>">
                                                <i class="fas fa-link"></i> Edit Link
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#deleteAdModal"
                                                    data-id="<?php echo (int)$ad['id']; ?>"
                                                    data-img="<?php echo htmlspecialchars($ad['image'], ENT_QUOTES); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; KLTG Admin <?php echo date('Y'); ?></span>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Add New Ad Modal -->
<div class="modal fade" id="addAdModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Advertisement</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ad Image <span class="text-danger">*</span></label>
                        <input type="file" name="ad_image" class="form-control-file" accept="image/*" required>
                        <small class="form-text text-muted">JPG, PNG, GIF or WEBP. Max 5 MB. Recommended: 600×400 px.</small>
                    </div>
                    <div class="form-group">
                        <label>Destination URL <span class="text-danger">*</span></label>
                        <input type="url" name="link_url" class="form-control" placeholder="https://example.com" required>
                        <small class="form-text text-muted">Visitors will be taken to this URL when they click the ad.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_advertisement" class="btn btn-primary">Upload &amp; Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="modal fade" id="editLinkModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="ad_id" id="editLinkId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Destination URL</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Destination URL</label>
                        <input type="url" name="link_url" id="editLinkUrl" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_ad_link" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAdModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="ad_id" id="deleteAdId">
                <input type="hidden" name="ad_image_name" id="deleteAdImage">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Delete Advertisement</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this advertisement? The image file will also be removed. This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_advertisement" class="btn btn-danger">Delete</button>
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
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    $('#dataTable').DataTable({ order: [[0, 'desc']] });

    $('#editLinkModal').on('show.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        $('#editLinkId').val(btn.data('id'));
        $('#editLinkUrl').val(btn.data('link'));
    });

    $('#deleteAdModal').on('show.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        $('#deleteAdId').val(btn.data('id'));
        $('#deleteAdImage').val(btn.data('img'));
    });
});
</script>
</body>
</html>
