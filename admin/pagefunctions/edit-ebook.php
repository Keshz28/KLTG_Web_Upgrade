<?php
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
        // array_push($errors2, "photo 1");
        echo "test";

        $tile1_photo1 = urlencode(uploadpdf($_FILES["fileToUpload2b"], "pdf/", $ebook_category));
        if ($tile1_photo1) {
            $queryselect = "SELECT * FROM ebook where ebook_id='" . $id . "'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile1_photo1'];
                $status = unlink('../assets/pdf/ebook/' . $ebook_category . '/' . $row['ebook_filename']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "ebook_filename='$tile1_photo1'";
            // echo $tile1_photo1;
        }
    }


    if ($_FILES["fileToUpload3b"]["name"] != "") {
        // array_push($errors2, "photo 1");

        echo "test2";


        $tile1_photo2 = urlencode(uploadimage($_FILES["fileToUpload3b"], "ebook/", $ebook_category));
        if ($tile1_photo2) {
            $queryselect = "SELECT * FROM ebook where ebook_id='" . $id . "'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile1_photo2'];
                $status = unlink('../assets/img/ebook/' . $ebook_category . '/' . $row['ebook_filename']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "ebook_image='$tile1_photo2'";
            // echo $tile1_photo2;
        }
    }




    if ($querycontent) {
        // debug_to_console($querycontent);
// echo $querycontent;
        $query = "UPDATE ebook SET " . $querycontent . " WHERE ebook_id='$id' ";
        echo $query;
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
        $imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg"
        && $imageFileType2 != "gif"
    ) {
        array_push($errors2, "only JPG, JPEG, PNG & GIF files are allowed");
        // echo "Sorry, file not jpg";

        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload2"]["size"] > 20000000) {
        // echo "Sorry, your file is too large.";
        array_push($errors2, "your file is too large");

        $uploadOk = 0;
    }
    if ($_FILES["fileToUpload3"]["size"] > 20000000) {
        array_push($errors2, "your file is too large");
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
        if (move_uploaded_file(($_FILES["fileToUpload2"]["tmp_name"]), $target_file) && move_uploaded_file($_FILES["fileToUpload3"]["tmp_name"], $target_file2)) {
            array_push($errors2, "The file " . htmlspecialchars(basename($_FILES["fileToUpload2"]["name"])) . " has been uploaded.");
            array_push($errors2, "The file " . htmlspecialchars(basename($_FILES["fileToUpload3"]["name"])) . " has been uploaded.");

            $filename = basename($_FILES["fileToUpload2"]["name"]);
            $filename2 = basename($_FILES["fileToUpload3"]["name"]);


            $query = "INSERT INTO ebook (ebook_filename,ebook_image,ebook_name,ebook_category,ebook_viewsettings) VALUES('$filename','$filename2','$ebook_name','$ebook_category','0')";
            mysqli_query($db, $query);
            array_push($errors2, "Added New E-Book");


        } else {
            array_push($errors2, "Error While Uploading PDF");
            echo "Error While Uploading PDF";


        }


    }

}
?>