<?php
// Guard: included by functions.php which runs on public pages too.
if (!isset($_SESSION['username'])) return;

// --- Upload diagnostics: surface failures that PHP otherwise hides ----------
// When a POST body is larger than post_max_size, PHP discards $_POST and $_FILES
// entirely, so none of the add/edit blocks below run and the page just reloads
// with no message. Detect that case and report it instead of failing silently.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES)
    && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0
) {
    $sentMB = round(((int) $_SERVER['CONTENT_LENGTH']) / 1048576, 1);
    array_push(
        $errors2,
        "Upload rejected before it could be processed: you sent {$sentMB} MB, which is "
        . "larger than the server's post_max_size (" . ini_get('post_max_size') . "). "
        . "Raise post_max_size and upload_max_filesize on the server, then try again."
    );
}

if (isset($_POST['editebookview2'])) {
    $id = $_POST['hiddenid'];
    $number = $_POST['valueupdate'];


    $query = "UPDATE ebook SET ebook_view2= '$number' WHERE ebook_id='$id' LIMIT 1";

    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Updated Views");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['enableviewebook'])) {

    $postid = $_GET['enableviewebook'];

    // echo "adasdasda";

    // echo $postid;
    $query = "UPDATE ebook SET ebook_viewsettings= 1 WHERE ebook_id='$postid'";
    // debug_to_console($postid);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Views Enabled");

    } else {
        // echo "fail";

    }

}
if (isset($_GET['disableviewebook'])) {

    $postid = $_GET['disableviewebook'];

    // echo "adasdasda";

    // echo $postid;
    $query = "UPDATE ebook SET ebook_viewsettings= 0 WHERE ebook_id='$postid'";
    // debug_to_console($postid);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Views Disabled");

    } else {
        // echo "fail";

    }

}

if (isset($_POST['editebook2'])) {
    $querycontent = "";
    $id = $_POST['hiddenid2'];
    if ($_POST['ebook_name']) {

        $ebook_name = $_POST['ebook_name'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "ebook_name='$ebook_name'";

    }

    if ($_POST['ebook_url']) {
        $ebook_url = trim($_POST['ebook_url']);
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "ebook_url='$ebook_url'";


    }
    if ($_POST['ebook_category']) {
        $ebook_category = $_POST['ebook_category'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "ebook_category='$ebook_category'";


    }
    if ($_FILES["fileToUpload2b"]["name"] != "") {
        if ($_FILES["fileToUpload2b"]["error"] !== UPLOAD_ERR_OK) {
            array_push($errors2, "New PDF upload failed (PHP upload error code "
                . $_FILES["fileToUpload2b"]["error"]
                . " — code 1/2 means it exceeds the server's upload size limit).");
        } else {
            $tile1_photo1 = uploadpdf($_FILES["fileToUpload2b"], "ebook", $ebook_category);
            if ($tile1_photo1) {
                $queryselect = "SELECT * FROM ebook where ebook_id='" . $id . "'";
                $removefile = mysqli_query($db, $queryselect);
                while ($row = mysqli_fetch_assoc($removefile)) {
                    $status = @unlink('../assets/pdf/ebook/' . $ebook_category . '/' . $row['ebook_filename']);
                }
                if ($querycontent) {
                    $querycontent .= ",";
                }
                $querycontent .= "ebook_filename='" . urlencode($tile1_photo1) . "'";
            } else {
                array_push($errors2, "New PDF was rejected. It must be a real .pdf under 100 MB, "
                    . "no file with the same name may already exist in assets/pdf/ebook/"
                    . htmlspecialchars($ebook_category) . "/, and that folder must be writable.");
            }
        }
    }


    if ($_FILES["fileToUpload3b"]["name"] != "") {
        if ($_FILES["fileToUpload3b"]["error"] !== UPLOAD_ERR_OK) {
            array_push($errors2, "New cover upload failed (PHP upload error code "
                . $_FILES["fileToUpload3b"]["error"]
                . " — code 1/2 means it exceeds the server's upload size limit).");
        } else {
            $tile1_photo2 = uploadcover($_FILES["fileToUpload3b"], $ebook_category);
            if ($tile1_photo2) {
                $queryselect = "SELECT * FROM ebook where ebook_id='" . $id . "'";
                $removefile = mysqli_query($db, $queryselect);
                while ($row = mysqli_fetch_assoc($removefile)) {
                    // Remove the previous cover file (ebook_image, not the PDF).
                    if (!empty($row['ebook_image'])) {
                        @unlink('../assets/img/ebook/' . $ebook_category . '/' . $row['ebook_image']);
                    }
                }
                if ($querycontent) {
                    $querycontent .= ",";
                }
                $querycontent .= "ebook_image='$tile1_photo2'";
            } else {
                array_push($errors2, "New cover was rejected. It must be a jpg/jpeg/png/webp (or pdf) under 20 MB, "
                    . "no file with the same name may already exist in assets/img/ebook/"
                    . htmlspecialchars($ebook_category) . "/, and that folder must be writable.");
            }
        }
    }




    if ($querycontent) {
        // debug_to_console($querycontent);
// echo $querycontent;
        $query = "UPDATE ebook SET " . $querycontent . " WHERE ebook_id='$id' ";
        $update = mysqli_query($db, $query);
        if ($update) {
            // echo "Record updated successfully";
            // debug_to_console("test");

            array_push($errors2, "Edit Saved");
            // echo $query;

        } else {
            array_push($errors2, "Error updating record: " . mysqli_error($db));
            // echo $query;

        }

    }


}

if (isset($_POST['deleteebook'])){
    $id = $_POST['hiddenid2'];
    $query = "DELETE FROM ebook WHERE ebook_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}

// ebook
if (isset($_POST['addebook2'])) {

    $ebook_name = $_POST['ebook_name'];
    $ebook_category = $_POST['ebook_category'];

    // Report any PHP-level upload errors (size limit, partial, no temp dir, etc.)
    foreach (['fileToUpload2' => 'E-book PDF', 'fileToUpload3' => 'Cover image'] as $field => $label) {
        $code = $_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($code !== UPLOAD_ERR_OK) {
            $reasons = [
                UPLOAD_ERR_INI_SIZE   => 'is larger than the server upload_max_filesize limit',
                UPLOAD_ERR_FORM_SIZE  => 'is larger than the form allows',
                UPLOAD_ERR_PARTIAL    => 'was only partially uploaded (connection interrupted)',
                UPLOAD_ERR_NO_FILE    => 'was not selected',
                UPLOAD_ERR_NO_TMP_DIR => 'has no temporary folder on the server',
                UPLOAD_ERR_CANT_WRITE => 'could not be written to disk (folder permissions?)',
                UPLOAD_ERR_EXTENSION  => 'was blocked by a PHP extension',
            ];
            array_push($errors2, "{$label} " . ($reasons[$code] ?? "failed (error code {$code})") . ".");
        }
    }

    $target_dir2 = "../assets/pdf/ebook/" . $ebook_category . "/";
    $target_file = $target_dir2 . basename($_FILES["fileToUpload2"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // echo $target_file;
    $target_dir3 = "../assets/img/ebook/" . $ebook_category . "/";
    $target_file2 = $target_dir3 . basename($_FILES["fileToUpload3"]["name"]);
    // echo $target_dir2;
    $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

    $uploadOk = 1;


    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        array_push($errors2, "File already exists");

        $uploadOk = 0;
    }
    // Allow certain file formats
    if (
        $imageFileType != "pdf"
    ) {
        array_push($errors2, "only PDF files are allowed");
        // echo "Sorry, not pdf.";

        $uploadOk = 0;
    }
    if (
        !in_array($imageFileType2, ["jpg", "png", "jpeg", "gif", "webp", "pdf"], true)
    ) {
        array_push($errors2, "Cover must be a PDF, JPG, JPEG, PNG, WEBP or GIF file");
        // echo "Sorry, file not allowed";

        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload2"]["size"] > 100 * 1024 * 1024) {   // 100 MB max for the e-book PDF
        // echo "Sorry, your file is too large.";
        array_push($errors2, "your file is too large");

        $uploadOk = 0;
    }
    if ($_FILES["fileToUpload3"]["size"] > 10 * 1024 * 1024) {   // 10 MB max for the cover image (covers are small; largest in use ~1.5 MB)
        array_push($errors2, "your cover image is too large (max 10 MB)");
        $uploadOk = 0;
    }
    // $filename = basename($_FILES["fileToUpload2"]["name"]);
    // $filename2 = basename($_FILES["fileToUpload3"]["name"]);

    // echo $filename;
    // echo $filename2;

    // echo "Sorry, file not uplasdasdasoaded.";
    // echo $uploadOk;
    // // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        array_push($errors2, "File is not uploaded");
        // if everything is ok, try to upload file'
        // echo "Sorry, file not uploaded.";

    } else {
        // Make sure the category folders exist (newly added categories, etc.)
        if (function_exists('ensure_dir')) {
            ensure_dir($target_dir2);
            ensure_dir($target_dir3);
        }
        if (move_uploaded_file(($_FILES["fileToUpload2"]["tmp_name"]), $target_file) && move_uploaded_file($_FILES["fileToUpload3"]["tmp_name"], $target_file2)) {
            array_push($errors2, "The file " . htmlspecialchars(basename($_FILES["fileToUpload2"]["name"])) . " has been uploaded.");
            array_push($errors2, "The file " . htmlspecialchars(basename($_FILES["fileToUpload3"]["name"])) . " has been uploaded.");

            $filename = basename($_FILES["fileToUpload2"]["name"]);
            $filename2 = basename($_FILES["fileToUpload3"]["name"]);


            $query = "INSERT INTO ebook (ebook_filename,ebook_image,ebook_name,ebook_category,ebook_viewsettings) VALUES('$filename','$filename2','$ebook_name','$ebook_category','0')";
            if (mysqli_query($db, $query)) {
                array_push($errors2, "Added New E-Book");
            } else {
                array_push($errors2, "Database error adding e-book: " . mysqli_error($db));
            }


        } else {
            array_push($errors2, "Could not save the uploaded files to "
                . htmlspecialchars($target_dir2) . " / " . htmlspecialchars($target_dir3)
                . " — check the folders exist and are writable on the server.");


        }


    }

}

/* ===========================================================================
 *  E-book CATEGORY management (table: ebook_category)
 *  Each cat_code is also the folder name under assets/pdf/ebook/<code>/ and
 *  assets/img/ebook/<code>/.
 * =========================================================================== */

// --- Add a new category ----------------------------------------------------
if (isset($_POST['addcategory'])) {
    // Codes are used as folder names, so keep them to a safe slug.
    $code = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['cat_code'] ?? ''));
    $title = trim($_POST['cat_title'] ?? '');
    $desc = trim($_POST['cat_desc'] ?? '');
    $order = (int) ($_POST['cat_order'] ?? 0);
    $visible = isset($_POST['cat_visible']) ? 1 : 0;

    if ($code === '' || $title === '') {
        array_push($errors2, "Category needs both a code and a title");
    } else {
        $codeE = mysqli_real_escape_string($db, $code);
        $exists = mysqli_query($db, "SELECT cat_id FROM ebook_category WHERE cat_code='$codeE' LIMIT 1");
        if ($exists && mysqli_num_rows($exists) > 0) {
            array_push($errors2, "A category with code '$code' already exists");
        } else {
            $titleE = mysqli_real_escape_string($db, $title);
            $descE = mysqli_real_escape_string($db, $desc);
            $q = "INSERT INTO ebook_category (cat_code, cat_title, cat_desc, cat_order, cat_visible)
                  VALUES ('$codeE', '$titleE', '$descE', $order, $visible)";
            if (mysqli_query($db, $q)) {
                // Create the matching upload folders so the first e-book upload works.
                if (function_exists('ensure_dir')) {
                    ensure_dir('../assets/pdf/ebook/' . $code . '/');
                    ensure_dir('../assets/img/ebook/' . $code . '/');
                }
                array_push($errors2, "Category '$title' added");
            } else {
                array_push($errors2, "Error adding category: " . mysqli_error($db));
            }
        }
    }
}

// --- Edit an existing category (code is fixed; it maps to folders) ----------
if (isset($_POST['editcategory'])) {
    $id = (int) ($_POST['cat_id'] ?? 0);
    $title = trim($_POST['cat_title'] ?? '');
    $desc = trim($_POST['cat_desc'] ?? '');
    $order = (int) ($_POST['cat_order'] ?? 0);
    $visible = isset($_POST['cat_visible']) ? 1 : 0;

    if ($id <= 0 || $title === '') {
        array_push($errors2, "Category needs a title");
    } else {
        $titleE = mysqli_real_escape_string($db, $title);
        $descE = mysqli_real_escape_string($db, $desc);
        $q = "UPDATE ebook_category
              SET cat_title='$titleE', cat_desc='$descE', cat_order=$order, cat_visible=$visible
              WHERE cat_id=$id LIMIT 1";
        if (mysqli_query($db, $q)) {
            array_push($errors2, "Category updated");
        } else {
            array_push($errors2, "Error updating category: " . mysqli_error($db));
        }
    }
}

// --- Delete a category (only if it has no e-books) -------------------------
if (isset($_POST['deletecategory'])) {
    $id = (int) ($_POST['cat_id'] ?? 0);
    if ($id > 0) {
        // Find its code, then refuse if any e-book still uses it.
        $codeRes = mysqli_query($db, "SELECT cat_code FROM ebook_category WHERE cat_id=$id LIMIT 1");
        $codeRow = $codeRes ? mysqli_fetch_assoc($codeRes) : null;
        if (!$codeRow) {
            array_push($errors2, "Category not found");
        } else {
            $codeE = mysqli_real_escape_string($db, $codeRow['cat_code']);
            $used = mysqli_query($db, "SELECT ebook_id FROM ebook WHERE ebook_category='$codeE' LIMIT 1");
            if ($used && mysqli_num_rows($used) > 0) {
                array_push($errors2, "Cannot delete '" . $codeRow['cat_code'] . "': it still has e-books. Move or remove them first.");
            } else {
                if (mysqli_query($db, "DELETE FROM ebook_category WHERE cat_id=$id LIMIT 1")) {
                    array_push($errors2, "Category deleted");
                } else {
                    array_push($errors2, "Error deleting category: " . mysqli_error($db));
                }
            }
        }
    }
}
?>