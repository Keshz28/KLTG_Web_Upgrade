<?php
/* ============================================================================
 *                            edit-merchandise.php
 *
 *   Admin editor — Merchandise page (merchandise / merchandise_category tables).
 *   Pairs with pagefunctions/edit-merchandise.php.
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

// Categories (for the dropdowns + management list)
$categories = [];
$catRes = mysqli_query($db, "SELECT merchandise_category_id AS id, name FROM merchandise_category ORDER BY merchandise_category_order ASC, merchandise_category_id ASC");
if ($catRes) {
    while ($c = mysqli_fetch_assoc($catRes)) { $categories[] = $c; }
}
$cat_map = [];
foreach ($categories as $c) { $cat_map[(int)$c['id']] = $c['name']; }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description" content="">
    <meta name="author" content="">
    <title>KLTG ADMIN - Merchandise</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

                    <h1 class="h3 mb-2 text-gray-800">Merchandise</h1>
                    <p class="mb-4">Add, edit and remove the products shown on the public Merchandise page.</p>

                    <!-- ===================== Categories ===================== -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
                            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal"
                                data-target="#addCategoryModal"><i class="fas fa-plus fa-sm"></i> Add Category</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th style="width:90px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $c): ?>
                                        <tr>
                                            <td><?php echo (int)$c['id']; ?></td>
                                            <td><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></td>
                                            <td>
                                                <form action="edit-merchandise.php" method="post"
                                                    onsubmit="return confirm('Delete this category? Products in it will become uncategorised.');"
                                                    style="margin:0;">
                                                    <input type="hidden" name="category_id"
                                                        value="<?php echo (int)$c['id']; ?>">
                                                    <button type="submit" name="delete_merch_category"
                                                        class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($categories)): ?>
                                        <tr><td colspan="3" class="text-muted">No categories yet.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ===================== Products ===================== -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Products</h6>
                            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal"
                                data-target="#addProductModal"><i class="fas fa-plus fa-sm"></i> Add New Product</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="merchTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Buy link</th>
                                            <th style="width:90px;">Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $prodRes = mysqli_query($db, "SELECT * FROM merchandise ORDER BY merchandise_order ASC, merchandise_id ASC");
                                        if ($prodRes) {
                                            while ($p = mysqli_fetch_assoc($prodRes)):
                                                $pid   = (int)$p['merchandise_id'];
                                                $pname = htmlspecialchars($p['name'], ENT_QUOTES);
                                                $pimg  = htmlspecialchars($p['image'], ENT_QUOTES);
                                                $pcat  = (int)$p['category_id'];
                                                $pprice= htmlspecialchars($p['price'], ENT_QUOTES);
                                                $purl  = htmlspecialchars($p['buy_url'], ENT_QUOTES);
                                                $catName = htmlspecialchars($cat_map[$pcat] ?? '—', ENT_QUOTES);
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if ($p['image'] !== ''): ?>
                                                <img src="../assets/img/merchandise/<?php echo rawurlencode($p['image']); ?>"
                                                    alt="" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                                <?php else: ?>
                                                <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $pname; ?></td>
                                            <td><?php echo $catName; ?></td>
                                            <td><?php echo $pprice; ?></td>
                                            <td><?php echo $purl ? '<a href="' . $purl . '" target="_blank" rel="noopener">link</a>' : '<span class="text-muted">—</span>'; ?></td>
                                            <td>
                                                <a href="#" onclick="editMerch(this); return false;"
                                                    data-id="<?php echo $pid; ?>"
                                                    data-name="<?php echo $pname; ?>"
                                                    data-image="<?php echo $pimg; ?>"
                                                    data-cat="<?php echo $pcat; ?>"
                                                    data-price="<?php echo $pprice; ?>"
                                                    data-url="<?php echo $purl; ?>"><i class="fas fa-pen"></i></a>
                                            </td>
                                        </tr>
                                        <?php endwhile; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
    </div>

    <!-- ===================== Add Category Modal ===================== -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="edit-merchandise.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category name</label>
                            <input type="text" class="form-control" name="category_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_merch_category" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== Add Product Modal ===================== -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="edit-merchandise.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Product</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product image</label>
                            <input type="file" name="fileToUpload" class="form-control-file" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="category_id">
                                <?php foreach ($categories as $c): ?>
                                <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price <small class="text-muted">(e.g. RM 49.90)</small></label>
                            <input type="text" class="form-control" name="price" placeholder="RM 0.00">
                        </div>
                        <div class="form-group">
                            <label>Buy / product link <small class="text-muted">(optional)</small></label>
                            <input type="url" class="form-control" name="buy_url" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_merch" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== Edit Product Modal ===================== -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="edit-merchandise.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="merchandise_id" id="em_id">
                        <input type="hidden" name="current_image" id="em_current_image">
                        <div class="form-group">
                            <label>Current image</label><br>
                            <img id="em_preview" src="" alt="" style="max-width:120px;border-radius:8px;display:none;">
                            <span id="em_noimg" class="text-muted">No image</span>
                        </div>
                        <div class="form-group">
                            <label>Replace image <small class="text-muted">(leave empty to keep current)</small></label>
                            <input type="file" name="fileToUpload" class="form-control-file" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" id="em_name" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="category_id" id="em_cat">
                                <?php foreach ($categories as $c): ?>
                                <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" name="price" id="em_price">
                        </div>
                        <div class="form-group">
                            <label>Buy / product link</label>
                            <input type="url" class="form-control" name="buy_url" id="em_url">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_merch" class="btn btn-danger"
                            onclick="return confirm('Delete this product?');">Delete</button>
                        <button type="submit" name="edit_merch" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () { $('#merchTable').DataTable(); });

        function editMerch(el) {
            document.getElementById('em_id').value = el.getAttribute('data-id');
            document.getElementById('em_name').value = el.getAttribute('data-name');
            document.getElementById('em_price').value = el.getAttribute('data-price');
            document.getElementById('em_url').value = el.getAttribute('data-url');
            document.getElementById('em_cat').value = el.getAttribute('data-cat');
            var img = el.getAttribute('data-image');
            document.getElementById('em_current_image').value = img;
            var preview = document.getElementById('em_preview');
            var noimg = document.getElementById('em_noimg');
            if (img) {
                preview.src = '../assets/img/merchandise/' + encodeURIComponent(img);
                preview.style.display = 'inline-block';
                noimg.style.display = 'none';
            } else {
                preview.style.display = 'none';
                noimg.style.display = 'inline';
            }
            $('#editProductModal').modal('show');
        }
    </script>
    <?php include('errors2.php'); ?>

</body>

</html>
