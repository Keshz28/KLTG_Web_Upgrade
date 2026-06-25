<?php
/* ============================================================================
 *                              edit-ebook.php
 *
 *   Admin editor — e-books.
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

// Categories for the dropdowns (code => ['title','desc']) and the manager table.
$ebookCats = ebook_categories($db, false);
$ebookCatRows = [];
$catRes = @mysqli_query($db, "SELECT * FROM ebook_category ORDER BY cat_order ASC, cat_id ASC");
if ($catRes) {
    while ($cr = mysqli_fetch_assoc($catRes)) {
        $ebookCatRows[] = $cr;
    }
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

    <title>KLTG ADMIN - Edit E-book</title>

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
                    <h1 class="h3 mb-2 text-gray-800">E-book Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- Quick reference: category code = title -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body py-3">
                            <span class="font-weight-bold text-primary mr-2"><i class="fas fa-tags"></i> Category codes:</span>
                            <?php if (empty($ebookCats)): ?>
                                <span class="text-muted small">No categories yet.</span>
                            <?php else: foreach ($ebookCats as $code => $info): ?>
                                <span class="badge badge-light border mr-1 mb-1" style="font-size:.8rem; font-weight:500;">
                                    <code><?= htmlspecialchars($code) ?></code> = <?= htmlspecialchars($info['title']) ?>
                                </span>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>

                    <!-- ===== Manage Categories ===== -->
                    <div class="card shadow mb-4" id="ebookcategories">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">E-book Categories</h6>
                            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#addcategorymodal">
                                <i class="fas fa-plus"></i> Add Category
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Categories become the tabs on the public E-book page. The <strong>code</strong> is also the
                                folder name where that category's PDFs &amp; covers are stored, so it can't be changed after creation.
                                Hidden categories stay selectable here but don't appear on the live site.
                            </p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:70px;">Order</th>
                                            <th scope="col" style="width:90px;">Code</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Description</th>
                                            <th scope="col" style="width:90px;">Visible</th>
                                            <th scope="col" style="width:120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($ebookCatRows)): ?>
                                            <tr><td colspan="6" class="text-center text-muted">
                                                No categories yet. If you just deployed, run <code>db_migration_ebook_category.sql</code> first, then add categories here.
                                            </td></tr>
                                        <?php else: foreach ($ebookCatRows as $cr): ?>
                                            <tr>
                                                <td><?= (int) $cr['cat_order'] ?></td>
                                                <td><code><?= htmlspecialchars($cr['cat_code']) ?></code></td>
                                                <td><?= htmlspecialchars($cr['cat_title']) ?></td>
                                                <td class="small text-muted"><?= htmlspecialchars(mb_strimwidth((string) $cr['cat_desc'], 0, 90, '…')) ?></td>
                                                <td>
                                                    <?php if ((int) $cr['cat_visible'] === 1): ?>
                                                        <span class="badge badge-success">Visible</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Hidden</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary editcatbtn"
                                                        data-id="<?= (int) $cr['cat_id'] ?>"
                                                        data-code="<?= htmlspecialchars($cr['cat_code'], ENT_QUOTES) ?>"
                                                        data-title="<?= htmlspecialchars($cr['cat_title'], ENT_QUOTES) ?>"
                                                        data-desc="<?= htmlspecialchars((string) $cr['cat_desc'], ENT_QUOTES) ?>"
                                                        data-order="<?= (int) $cr['cat_order'] ?>"
                                                        data-visible="<?= (int) $cr['cat_visible'] ?>">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                    <form action="?deletecat" method="post" class="d-inline"
                                                        onsubmit="return confirm('Delete this category? (Only works if it has no e-books.)');">
                                                        <input type="hidden" name="cat_id" value="<?= (int) $cr['cat_id'] ?>">
                                                        <button type="submit" name="deletecategory" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- ===== End Manage Categories ===== -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4" id="editebook">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">E-book
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable55" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Filename</th>
                                            <th scope="col">Cover</th>
                                            <th scope="col">URL</th>
                                            <th scope="col">Views</th>
                                            <th scope="col">Base Views</th>
                                            <th scope="col">Edited Views</th>

                                            <th scope="col">

                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#addebook">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                        </button>
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = "SELECT *, ebook_view+ebook_view2 FROM ebook  ORDER BY ebook_id DESC";
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            // if (!$row['banner_order']) {
                                        
                                            echo '<tr>';
                                            echo '<th scope="row">' . $row['ebook_id'] . '</th>';
                                            echo '<td id="name-' . $row['ebook_id'] . '">' . $row['ebook_name'] . '</td>';
                                            $catTitle = isset($ebookCats[$row['ebook_category']]) ? $ebookCats[$row['ebook_category']]['title'] : '';
                                            // Keep #category-<id> as the bare code (editmodalebook reads its text
                                            // to pre-select the dropdown); show the title in a separate span.
                                            echo '<td><span id="category-' . $row['ebook_id'] . '">' . htmlspecialchars($row['ebook_category']) . '</span>'
                                                . ($catTitle ? ' <span class="text-muted small">&mdash; ' . htmlspecialchars($catTitle) . '</span>' : '')
                                                . '</td>';
                                            echo '<td id="filename-' . $row['ebook_id'] . '">' . urldecode($row['ebook_filename']) . '</td>';
                                            echo '<td id="image-' . $row['ebook_id'] . '">' . $row['ebook_image'] . '</td>';
                                            echo '<td id="url-' . $row['ebook_id'] . '">' . $row['ebook_url'] . '</td>';
                                            echo '<td>' . $row['ebook_view'] . '</td>';
                                            echo '<form action="?editebook" method="post" enctype="multipart/form-data"><td>' . $row['ebook_view2'] . '
                                             
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" value="" name="valueupdate" >  
                                                    <input name="hiddenid" value="' . $row['ebook_id'] . '"  hidden>
                                                    <div class="input-group-append" >
                                                        <button class="btn btn-primary" type="submit" name="editebookview2"><i class=\'fas fa-plus\'></i></button>
                                                    </div>
                                                </div>

                                            </td></form>';
                                            echo '<td>' . $row['ebook_view+ebook_view2'] . '</td>';



                                            if ($row['ebook_viewsettings'] == 0) {
                                                echo '<td><a class="btn btn-danger" href="edit-ebook.php?enableviewebook=' . $row['ebook_id'] . '" name="enableviewebook">Disabled</a>
                                                <a href="#" class="" onclick="editmodalebook(' . $row['ebook_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a>
                                                </td>';

                                            } else {
                                                echo '<td><a class="btn btn-success" href="edit-ebook.php?disableviewebook=' . $row['ebook_id'] . '" name="disableviewebook">Enabled</a>
                                                <a href="#" class="" onclick="editmodal(' . $row['ebook_id'] . ');" id="modaledit"><i class="fas fa-pen"></i></a></td>';

                                            }
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





    <!-- add new book modal  -->
    <div class="modal fade" id="addebook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Add new book</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?addebook" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="hiddenid" name="hiddenid" hidden></input>
                        <div class="mb-3">
                            <label for="ebook_name" class="form-label">Name</label>
                            <input class="form-control" id="ebook_name" rows="3" name="ebook_name"></input>
                        </div>

                        <div class="form-group">
                            <label for="ebook_category">Category</label>
                            <select class="form-control" id="ebook_category" name="ebook_category">
                                <?php foreach ($ebookCats as $code => $info): ?>
                                    <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($info['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload2" class="form-label">PDF File</label><br />
                            <input type="file" name="fileToUpload2" id="fileToUpload2">
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload3" class="form-label">Cover <small class="text-muted">(PDF, PNG, JPG or JPEG)</small></label><br />
                            <input type="file" name="fileToUpload3" id="fileToUpload3" accept=".pdf,.png,.jpg,.jpeg,.webp">
                        </div>




                    </div>
                    <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" name="addebook2">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- edit book modal  -->
    <div class="modal fade" id="editebook2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit E-book</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?editebook" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="hiddenid2" name="hiddenid2" hidden></input>
                        <div class="mb-3">
                            <label for="ebook_name2" class="form-label">Name</label>
                            <input class="form-control" id="ebook_name2" name="ebook_name"></input>
                        </div>

                        <div class="mb-3">
                            <label for="ebook_url" class="form-label">Bit.ly Link</label>
                            <input class="form-control" id="ebook_url" rows="3" name="ebook_url"></input>
                        </div>

                        <div class="form-group">
                            <label for="ebook_category2">Category</label>
                            <select class="form-control" id="ebook_category2" name="ebook_category">
                                <?php foreach ($ebookCats as $code => $info): ?>
                                    <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($info['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload2" class="form-label">PDF File</label><br />
                            <input type="text" name="fileToUpload2a" id="fileToUpload2a" class="form-control" readonly>
                            <input type="file" name="fileToUpload2b" id="fileToUpload2b">
                        </div>
                        <div class="mb-3">
                            <label for="fileToUpload3" class="form-label">Cover <small class="text-muted">(PDF, PNG, JPG or JPEG)</small></label><br />
                            <input type="text" name="fileToUpload3a" id="fileToUpload3a" class="form-control" readonly>
                            <input type="file" name="fileToUpload3b" id="fileToUpload3b" accept=".pdf,.png,.jpg,.jpeg,.webp">
                        </div>




                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="submit" name="deleteebook2">Delete</button>
                        <button class="btn btn-primary" type="submit" name="editebook2">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add Category modal -->
    <div class="modal fade" id="addcategorymodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form action="?addcat" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cat_code" class="form-label">Code <small class="text-muted">(folder name — lowercase letters/numbers, e.g. <code>kltg</code>)</small></label>
                            <input class="form-control" id="cat_code" name="cat_code" maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="cat_title" class="form-label">Title</label>
                            <input class="form-control" id="cat_title" name="cat_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="cat_desc" class="form-label">Description <small class="text-muted">(shown under the tab on the live page)</small></label>
                            <textarea class="form-control" id="cat_desc" name="cat_desc" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="cat_order" class="form-label">Order <small class="text-muted">(lower shows first)</small></label>
                            <input type="number" class="form-control" id="cat_order" name="cat_order" value="0">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="cat_visible" name="cat_visible" value="1" checked>
                            <label class="form-check-label" for="cat_visible">Visible on the live E-book page</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" name="addcategory">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category modal -->
    <div class="modal fade" id="editcategorymodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form action="?editcat" method="post">
                    <input type="hidden" id="edit_cat_id" name="cat_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code <small class="text-muted">(fixed — tied to the file folders)</small></label>
                            <input class="form-control" id="edit_cat_code" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cat_title" class="form-label">Title</label>
                            <input class="form-control" id="edit_cat_title" name="cat_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cat_desc" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_cat_desc" name="cat_desc" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cat_order" class="form-label">Order</label>
                            <input type="number" class="form-control" id="edit_cat_order" name="cat_order" value="0">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="edit_cat_visible" name="cat_visible" value="1">
                            <label class="form-check-label" for="edit_cat_visible">Visible on the live E-book page</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" name="editcategory">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast-container position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;"
        id="toast11">
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
    <script src="js/editebook.js"></script>
    <script>
        function editcategory(id, code, title, desc, order, visible) {
            document.getElementById('edit_cat_id').value = id;
            document.getElementById('edit_cat_code').value = code;
            document.getElementById('edit_cat_title').value = title;
            document.getElementById('edit_cat_desc').value = desc;
            document.getElementById('edit_cat_order').value = order;
            document.getElementById('edit_cat_visible').checked = (parseInt(visible, 10) === 1);
            $('#editcategorymodal').modal('show');
        }
        document.querySelectorAll('.editcatbtn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                editcategory(this.dataset.id, this.dataset.code, this.dataset.title,
                    this.dataset.desc, this.dataset.order, this.dataset.visible);
            });
        });
    </script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>