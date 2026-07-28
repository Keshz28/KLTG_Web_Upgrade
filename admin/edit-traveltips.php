<?php
/* ============================================================================
 *                            edit-traveltips.php
 *
 *   Admin editor — Travel Tips (traveltips table).
 *   Public page: ../travel-tips.php. Handler: pagefunctions/edit-traveltips.php.
 *
 *   The 5 sections (a–e) and their icons are fixed scaffolding in the public
 *   page; this editor manages the Q&A "tip" items inside each section.
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

// Fixed section metadata (mirrors the tab bar in travel-tips.php)
$tt_sections = [
    'a' => 'Communication & Connectivity',
    'b' => 'Essential Information',
    'c' => 'Finance & Documents',
    'd' => 'Packing & Gear',
    'e' => 'Logistics',
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KLTG ADMIN - Edit Travel Tips</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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

                    <h1 class="h3 mb-2 text-gray-800">Travel Tips</h1>
                    <p class="mb-4">Add, edit, reorder and remove the Q&amp;A tips shown on the
                        <a href="../travel-tips.php" target="_blank">Travel Tips</a> page. The five sections and their
                        icons are fixed; you manage the tip items inside each section here.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tip Items</h6>
                            <a class="btn btn-sm btn-primary" href="#" data-toggle="modal" data-target="#addTtModal">
                                <i class="fas fa-plus"></i> New Tip
                            </a>
                        </div>
                        <div class="card-body" id="ttTable">
                            <?php foreach ($tt_sections as $skey => $slabel):
                                $items = mysqli_query($db, "SELECT * FROM traveltips WHERE tt_section = '" . $skey . "' ORDER BY tt_order ASC, tt_id ASC");
                            ?>
                            <h6 class="font-weight-bold text-gray-700 mt-3 mb-2">
                                Section <?php echo strtoupper($skey); ?> &mdash; <?php echo htmlspecialchars($slabel); ?>
                            </h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm mb-0" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:70px">Order</th>
                                            <th style="width:48px">Edit</th>
                                            <th style="width:180px">Title</th>
                                            <th>Question / Answer</th>
                                            <th style="width:150px">CTA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $has = false; while ($it = mysqli_fetch_assoc($items)): $has = true;
                                            $id = (int)$it['tt_id']; ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <a href="?tt_up=<?php echo $id; ?>" class="btn btn-sm btn-light border" title="Move up"><i class="fas fa-chevron-up"></i></a>
                                                <a href="?tt_down=<?php echo $id; ?>" class="btn btn-sm btn-light border" title="Move down"><i class="fas fa-chevron-down"></i></a>
                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="#" class="tt-edit" data-toggle="modal" data-target="#editTtModal"
                                                    data-id="<?php echo $id; ?>"
                                                    data-section="<?php echo htmlspecialchars($it['tt_section'], ENT_QUOTES); ?>"
                                                    data-header="<?php echo htmlspecialchars($it['tt_header'], ENT_QUOTES); ?>"
                                                    data-question="<?php echo htmlspecialchars($it['tt_question'], ENT_QUOTES); ?>"
                                                    data-answer="<?php echo htmlspecialchars($it['tt_answer'], ENT_QUOTES); ?>"
                                                    data-extra="<?php echo htmlspecialchars($it['tt_extra'], ENT_QUOTES); ?>"
                                                    data-cta-type="<?php echo htmlspecialchars($it['tt_cta_type'], ENT_QUOTES); ?>"
                                                    data-cta-label="<?php echo htmlspecialchars($it['tt_cta_label'], ENT_QUOTES); ?>"
                                                    data-cta-value="<?php echo htmlspecialchars($it['tt_cta_value'], ENT_QUOTES); ?>"
                                                    title="Edit"><i class="fas fa-pen"></i></a>
                                            </td>
                                            <td class="align-middle"><?php echo htmlspecialchars($it['tt_header']); ?></td>
                                            <td class="align-middle">
                                                <div class="text-muted small"><?php echo htmlspecialchars($it['tt_question']); ?></div>
                                                <div><?php echo htmlspecialchars(mb_strimwidth((string)$it['tt_answer'], 0, 180, '…')); ?></div>
                                                <?php if (trim((string)$it['tt_extra']) !== ''): ?>
                                                    <span class="badge badge-info mt-1">+ extra HTML</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle small">
                                                <?php if ($it['tt_cta_type'] === 'link'): ?>
                                                    <span class="badge badge-primary">Link</span> <?php echo htmlspecialchars($it['tt_cta_label']); ?>
                                                <?php elseif ($it['tt_cta_type'] === 'map'): ?>
                                                    <span class="badge badge-success">Map</span> <?php echo htmlspecialchars($it['tt_cta_label']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                        <?php if (!$has): ?>
                                        <tr><td colspan="5" class="text-center text-muted">No tips in this section yet.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; KLTG Admin <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <?php
    // Reusable form fields for both add and edit modals.
    function tt_fields($prefix, $sections) {
        ?>
        <div class="form-group">
            <label>Section</label>
            <select class="form-control" name="tt_section" id="<?php echo $prefix; ?>Section" required>
                <?php foreach ($sections as $k => $label): ?>
                    <option value="<?php echo $k; ?>"><?php echo strtoupper($k) . ' — ' . htmlspecialchars($label); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="tt_header" id="<?php echo $prefix; ?>Header" placeholder="e.g. Mobile Services" required>
        </div>
        <div class="form-group">
            <label>Question</label>
            <input type="text" class="form-control" name="tt_question" id="<?php echo $prefix; ?>Question" placeholder='e.g. "Where can I buy a SIM card?"'>
        </div>
        <div class="form-group">
            <label>Answer</label>
            <textarea class="form-control" name="tt_answer" id="<?php echo $prefix; ?>Answer" rows="5" placeholder="The answer paragraph shown to visitors"></textarea>
        </div>
        <div class="form-group">
            <label>Extra HTML <small class="text-muted">(optional — advanced; e.g. a bullet list)</small></label>
            <textarea class="form-control" name="tt_extra" id="<?php echo $prefix; ?>Extra" rows="3" placeholder="Raw HTML rendered after the answer. Leave blank if not needed."></textarea>
        </div>
        <div class="form-group">
            <label>Call-to-action button</label>
            <select class="form-control tt-cta-type" name="tt_cta_type" id="<?php echo $prefix; ?>CtaType">
                <option value="none">None</option>
                <option value="link">Link (opens a URL)</option>
                <option value="map">Map (searches Google Maps near the visitor)</option>
            </select>
        </div>
        <div class="form-group tt-cta-fields" id="<?php echo $prefix; ?>CtaFields" style="display:none;">
            <label>Button label</label>
            <input type="text" class="form-control mb-2" name="tt_cta_label" id="<?php echo $prefix; ?>CtaLabel" placeholder="e.g. Find SIM Shops">
            <label class="tt-cta-value-label">Destination</label>
            <input type="text" class="form-control" name="tt_cta_value" id="<?php echo $prefix; ?>CtaValue" placeholder="Link: https://…  |  Map: search words e.g. money changer">
        </div>
        <?php
    }
    ?>

    <!-- Add Tip Modal -->
    <div class="modal fade" id="addTtModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tip</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form action="edit-traveltips.php#ttTable" method="post">
                    <div class="modal-body">
                        <?php tt_fields('add', $tt_sections); ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" name="add_traveltip">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Tip Modal -->
    <div class="modal fade" id="editTtModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tip</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form action="edit-traveltips.php#ttTable" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="tt_id" id="editId">
                        <?php tt_fields('edit', $tt_sections); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger mr-auto" name="delete_traveltip"
                            onclick="return confirm('Delete this tip? This cannot be undone.');">Delete</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" name="edit_traveltip">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        // Show/hide the CTA label+value fields based on the selected CTA type.
        function ttToggleCta(selectEl) {
            var fields = selectEl.closest('.modal-body').querySelector('.tt-cta-fields');
            var valLbl = fields.querySelector('.tt-cta-value-label');
            if (selectEl.value === 'none') {
                fields.style.display = 'none';
            } else {
                fields.style.display = '';
                valLbl.textContent = (selectEl.value === 'map') ? 'Map search words' : 'Destination URL';
            }
        }
        document.querySelectorAll('.tt-cta-type').forEach(function (sel) {
            sel.addEventListener('change', function () { ttToggleCta(sel); });
        });

        // Populate the edit modal from the clicked row's data attributes.
        $('.tt-edit').on('click', function () {
            var d = this.dataset;
            $('#editId').val(d.id);
            $('#editSection').val(d.section);
            $('#editHeader').val(d.header);
            $('#editQuestion').val(d.question);
            $('#editAnswer').val(d.answer);
            $('#editExtra').val(d.extra);
            $('#editCtaType').val(d.ctaType || 'none');
            $('#editCtaLabel').val(d.ctaLabel);
            $('#editCtaValue').val(d.ctaValue);
            ttToggleCta(document.getElementById('editCtaType'));
        });

        // Reset the add modal each time it opens.
        $('#addTtModal').on('show.bs.modal', function () {
            this.querySelector('form').reset();
            ttToggleCta(document.getElementById('addCtaType'));
        });
    </script>
    <script>
        <?php if (isset($_SESSION['alert_msg'])): ?>
            alert("<?php echo addslashes($_SESSION['alert_msg']); ?>");
            <?php unset($_SESSION['alert_msg']); unset($_SESSION['alert_type']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
