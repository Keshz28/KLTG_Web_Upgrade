<?php

//banner
if (isset($_POST["upload_banner"])) {

    $target_dir = "../assets/img/banner/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    // echo $target_file;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        // array_push($errors, "Wrongasdasdsan");

        array_push($errors2, "File is an image - " . $check["mime"] . ".");


        $uploadOk = 1;
    } else {
        // echo "File is not an image.";
        array_push($errors2, "File is not an image");

        $uploadOk = 0;
    }



    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        array_push($errors2, "File already exists");

        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 50000000) {
        // echo "Sorry, your file is too large.";
        array_push($errors2, "Your file is too large");

        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        array_push($errors2, "only JPG, JPEG, PNG & GIF files are allowed");

        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // array_push($errors2, "File is not uploaded");
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            array_push($errors2, "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.");

            $filename = basename($_FILES["fileToUpload"]["name"]);

            // New banners are shown by default (status 1), appended to the end of
            // the carousel order, and named after the uploaded file so they are
            // identifiable in the table. Prepared statement avoids SQL injection
            // via the uploaded filename.
            $nameGuess = pathinfo($filename, PATHINFO_FILENAME);
            $ordRes    = mysqli_query($db, "SELECT COALESCE(MAX(banner_order), 0) + 1 AS nextord FROM banner");
            $nextOrd   = ($ordRes && ($r = mysqli_fetch_assoc($ordRes))) ? (int) $r['nextord'] : 1;

            $stmt = mysqli_prepare($db, "INSERT INTO banner (banner_filename, banner_name, banner_order, status) VALUES (?, ?, ?, '1')");
            mysqli_stmt_bind_param($stmt, 'ssi', $filename, $nameGuess, $nextOrd);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // NOTE: a synchronous "content updated" email blast to every subscriber
            // used to run here. It made every banner image upload hang (on hosts that
            // throttle/block outbound SMTP it stalls until the send times out), and
            // notifying all subscribers on an image upload was unintended. Removed.
            // If subscriber notifications are wanted, trigger them deliberately and
            // drain them via the existing mailqueue + cron, not inline on upload.

        } else {
            array_push($errors2, "Error While Uploading");
        }
    }
}

/*
 * Banner reorder — move a banner up or down one position.
 *
 * The old version just did `banner_order ± 1` on the single banner, which left
 * the neighbour untouched and produced DUPLICATE order values. With ties, the
 * public page's `ORDER BY banner_order` breaks the tie arbitrarily, so the
 * rearrangement appeared to do nothing. We now swap the banner with its
 * neighbour and re-write the whole list as a clean, unique 0..n sequence.
 */
if (isset($_GET['orderup']) || isset($_GET['orderdown'])) {

    $banner_id = (int) $_GET['banner_id'];
    $direction = isset($_GET['orderup']) ? 'up' : 'down';

    // Current order of every banner (stable tie-break on banner_id)
    $ids = [];
    $res = mysqli_query($db, "SELECT banner_id FROM banner ORDER BY banner_order ASC, banner_id ASC");
    while ($r = mysqli_fetch_assoc($res)) {
        $ids[] = (int) $r['banner_id'];
    }

    $pos = array_search($banner_id, $ids, true);
    if ($pos !== false) {
        $swapWith = ($direction === 'up') ? $pos - 1 : $pos + 1;
        if ($swapWith >= 0 && $swapWith < count($ids)) {
            $tmp            = $ids[$pos];
            $ids[$pos]      = $ids[$swapWith];
            $ids[$swapWith] = $tmp;
        }
    }

    // Persist as a clean, gap-free, unique sequence
    foreach ($ids as $i => $bid) {
        mysqli_query($db, "UPDATE banner SET banner_order = $i WHERE banner_id = $bid");
    }

    array_push($errors2, "Banner Position Saved");
}





if (isset($_POST['editbanner'])) {


    if ($_FILES["fileToUpload2"]["name"]!=""){

    $target_dir = "../assets/img/banner/";
    $target_file = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
    // echo $target_file;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
    if ($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        // array_push($errors, "Wrongasdasdsan");

        array_push($errors2, "File is an image - " . $check["mime"] . ".");


        $uploadOk = 1;
    } else {
        // echo "File is not an image.";
        array_push($errors2, "File is not an image");

        $uploadOk = 0;
    }



    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        array_push($errors2, "File already exists");

        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload2"]["size"] > 50000000) {
        // echo "Sorry, your file is too large.";
        array_push($errors2, "Your file is too large");

        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        array_push($errors2, "only JPG, JPEG, PNG & GIF files are allowed");

        $uploadOk = 0;
    }
    }
    else{
        $uploadOk = 1;

    }

    // debug_to_console("test");
    if ($uploadOk == 0) {
        // array_push($errors2, "File is not uploaded");
        // if everything is ok, try to upload file
    } else {
        $name = $_POST['name'];
        $order = $_POST['order'];
     
        $id = $_POST['id'];
        $url = $_POST['url'];
        if ($_FILES["fileToUpload2"]["name"]!=""){
            $filename = basename($_FILES["fileToUpload2"]["name"]);
            $query = "UPDATE banner SET banner_name='$name', banner_order= '$order',banner_filename2='$filename', banner_url='$url' WHERE banner_id='$id'";

        }
        else {
            $query = "UPDATE banner SET banner_name='$name', banner_order= '$order', banner_url='$url' WHERE banner_id='$id'";

        }

        //debug_to_console($query);
        $update = mysqli_query($db, $query);
        if ($update) {

            array_push($errors2, "Banner Edit Saved");


        } else {
            echo "Error updating record: " . mysqli_error($db);
        }
        echo '<script type="text/JavaScript"> 
    $("#exampleModal2").modal("hide");
    </script>';

    }


}
if (isset($_POST['deletebanner'])) {

    // debug_to_console("test");

    $name = $_POST['name'];
    $order = $_POST['order'];
    $filename = $_POST['filename'];
    $id = $_POST['id'];

    $query = "DELETE FROM banner WHERE banner_id='$id' ";
    $update = mysqli_query($db, $query);
    if ($update) {



        $status = unlink('../assets/img/banner/' . $filename);
        if ($status) {
            array_push($errors2, "Banner Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
    // echo '<script type="text/JavaScript"> 
    // $("#exampleModal2").modal("hide");
    // </script>';

}

//recommend
/**
 * Resolves a pasted blog-post link or raw numeric ID to the Blogger post's
 * numeric ID. Accepts e.g. "blog-details.php?postid=123456" or just "123456".
 */
function extractBloggerPostId($input)
{
    $input = trim((string) $input);
    if (preg_match('/postid=(\d+)/', $input, $m)) {
        return $m[1];
    }
    if (preg_match('/^\d+$/', $input)) {
        return $input;
    }
    return '';
}

/**
 * Fetches a single post from the KLTG Blogger blog (same blog that powers
 * blog.php / blog-details.php) and returns [name, image] or null on failure.
 */
function fetchBloggerPost($postId)
{
    $apiKey = envv('BLOGGER_API_KEY');
    if (!$apiKey) {
        return null;
    }
    $blogId = '1732826187557117921';
    $url = "https://www.googleapis.com/blogger/v3/blogs/$blogId/posts/$postId?key=" . urlencode($apiKey) . "&fetchImages=true";

    $response = @file_get_contents($url);
    $post = $response ? json_decode($response, true) : null;
    if (!$post || isset($post['error']) || empty($post['title'])) {
        return null;
    }

    $image = '';
    if (!empty($post['images'][0]['url'])) {
        $image = preg_replace('#^https?://#', '', $post['images'][0]['url']);
    }

    return ['name' => $post['title'], 'image' => $image];
}

if (isset($_POST['saverecommendation'])) {

    $postId = extractBloggerPostId($_POST['posturl'] ?? '');
    $category = mysqli_real_escape_string($db, trim($_POST['category'] ?? ''));

    if ($postId === '') {
        array_push($errors2, "Could not find a valid Post ID in that link.");
    } else {
        $post = fetchBloggerPost($postId);
        if (!$post) {
            array_push($errors2, "Couldn't find that blog post. Double-check the link/ID.");
        } else {
            $name = mysqli_real_escape_string($db, urlencode($post['name']));
            $image = mysqli_real_escape_string($db, urlencode($post['image']));
            $postIdEsc = mysqli_real_escape_string($db, $postId);

            $query = "INSERT INTO recommendation (recommendation_name, recommendation_image, recommendation_postid, recommendation_category)
            VALUES('$name', '$image', '$postIdEsc', '$category')";

            $update = mysqli_query($db, $query);
            if ($update) {
                array_push($errors2, "Recommendation Added");
                sendpushnotification($db, "New Recommendation", "KL The Guide Just updated their recommendation");
            } else {
                array_push($errors2, "Error adding record: " . mysqli_error($db));
            }
        }
    }
}


if (isset($_POST['editrecommend'])) {

    $hidid = mysqli_real_escape_string($db, $_POST['hiddenid']);
    $category = mysqli_real_escape_string($db, trim($_POST['recommendcategory'] ?? ''));
    $postInput = trim($_POST['posturl'] ?? '');

    if ($postInput === '') {
        // No new post specified — just update the category.
        $query = "UPDATE recommendation SET recommendation_category='$category' WHERE recommendation_id='$hidid'";
        $update = mysqli_query($db, $query);
        if ($update) {
            array_push($errors2, "Recommendation Edit Saved");
        } else {
            array_push($errors2, "Error updating record: " . mysqli_error($db));
        }
    } else {
        $postId = extractBloggerPostId($postInput);
        if ($postId === '') {
            array_push($errors2, "Could not find a valid Post ID in that link.");
        } else {
            $post = fetchBloggerPost($postId);
            if (!$post) {
                array_push($errors2, "Couldn't find that blog post. Double-check the link/ID.");
            } else {
                $name = mysqli_real_escape_string($db, urlencode($post['name']));
                $image = mysqli_real_escape_string($db, urlencode($post['image']));
                $postIdEsc = mysqli_real_escape_string($db, $postId);

                $query = "UPDATE recommendation SET recommendation_name='$name', recommendation_image='$image', recommendation_postid='$postIdEsc', recommendation_category='$category' WHERE recommendation_id='$hidid'";
                $update = mysqli_query($db, $query);
                if ($update) {
                    array_push($errors2, "Recommendation Edit Saved");
                } else {
                    array_push($errors2, "Error updating record: " . mysqli_error($db));
                }
            }
        }
    }
}
if (isset($_POST['deleterecommend'])) {

    $hidid = mysqli_real_escape_string($db, $_POST['hiddenid']);

    $query = "DELETE FROM recommendation WHERE recommendation_id='$hidid'";
    //debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        // debug_to_console("test");

        array_push($errors2, "Recommendation Deleted");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
    // echo '<script type="text/JavaScript"> 
    // $("#editrecommend").modal("hide");
    // </script>';




}





if (isset($_POST['heroedit'])) {
    $querycontent = "";
    if ($_POST['hero_title']) {

        $hero_title = $_POST['hero_title'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "hero_title='$hero_title'";

    }
    if ($_POST['hero_title2']) {
        $hero_title2 = $_POST['hero_title2'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "hero_title2='$hero_title2'";



    }
    if ($_POST['hero_subtitle']) {
        $hero_subtitle = $_POST['hero_subtitle'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "hero_subtitle='$hero_subtitle'";


    }

    if ($querycontent) {
        // debug_to_console($querycontent);

        $query = "UPDATE indexpage SET " . $querycontent . "WHERE id='1' ";
        $update = mysqli_query($db, $query);
        // echo $query;
        if ($update) {
            // echo "Record updated successfully";
            //debug_to_console("test");

            array_push($errors2, "Edit Saved");

        } else {
            echo "Error updating record: " . mysqli_error($db);
        }

    }

    // $filename = $_POST['filename'];
// $id = $_POST['id'];

    //     $query = "UPDATE indexpage SET 
// hero_title='$title',
// hero_title='$title2',
// hero_title='$subtitle'

    // WHERE id='1' ";
    // debug_to_console($query);
    // $update = mysqli_query($db, $query);
    // if ($update) {
    // echo "Record updated successfully";
    // debug_to_console("test");

    // array_push($errors2, "Edit Saved");

    // } else {
    //     echo "Error updating record: " . mysqli_error($db);
    // }

}


if (isset($_POST['tile1'])) {
    $querycontent = "";
    if ($_POST['tile1_title']) {

        $tile1_title = $_POST['tile1_title'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile1_title='$tile1_title'";

    }

    if ($_POST['tile1_subtitle']) {
        $tile1_subtitle = $_POST['tile1_subtitle'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile1_subtitle='$tile1_subtitle'";


    }
    if ($_POST['tile1_title1'] != "") {
        // array_push($errors2, "photo 1");

        $tile1_title1 = $_POST['tile1_title1'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile1_title1='$tile1_title1'";
    }


    if ($_FILES["tile1_photo1"]["name"] != "") {
        // array_push($errors2, "photo 1");


        $tile1_photo1 = urlencode(uploadimage($_FILES["tile1_photo1"], "highlights/", ""));
        if ($tile1_photo1) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile1_photo1'];
                $status = unlink('../assets/img/highlights/' . $row['tile1_photo1']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile1_photo1='$tile1_photo1'";
            // echo $tile1_photo1;
        }
    }

    if ($_POST['tile1_title2'] != "") {
        // array_push($errors2, "photo 1");

        $tile1_title2 = $_POST['tile1_title2'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile1_title2='$tile1_title2'";
    }
    if ($_FILES["tile1_photo2"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile1_photo2 = urlencode(uploadimage($_FILES["tile1_photo2"], "highlights/", ""));
        if ($tile1_photo2) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile1_photo2'];
                $status = unlink('../assets/img/highlights/' . $row['tile1_photo2']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile1_photo2='$tile1_photo2'";
            // echo $tile1_photo2;
        }
    }

    if ($_POST['tile1_title3'] != "") {
        // array_push($errors2, "photo 1");

        $tile1_title3 = $_POST['tile1_title3'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile1_title3='$tile1_title3'";
    }
    if ($_FILES["tile1_photo3"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile1_photo3 = urlencode(uploadimage($_FILES["tile1_photo3"], "highlights/", ""));
        if ($tile1_photo3) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile1_photo3'];
                $status = unlink('../assets/img/highlights/' . $row['tile1_photo3']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile1_photo3='$tile1_photo3'";
            // echo $tile1_photo3;
        }
    }

    if ($_POST['tile1_title4'] != "") {
        // array_push($errors2, "photo 1");

        $tile1_title4 = $_POST['tile1_title4'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile1_title4='$tile1_title4'";
    }
    if ($_FILES["tile1_photo4"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile1_photo4 = urlencode(uploadimage($_FILES["tile1_photo4"], "highlights/", ""));
        if ($tile1_photo4) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile1_photo3'];
                if ($row['tile1_photo4'] != "") {
                    $status = unlink('../assets/img/highlights/' . $row['tile1_photo4']);

                }

            }       
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile1_photo4='$tile1_photo4'";
            // echo $tile1_photo3;
        }
    }


    if ($querycontent) {
        // debug_to_console($querycontent);

        $query = "UPDATE indexpage SET " . $querycontent . "WHERE id='1' ";
        $update = mysqli_query($db, $query);
        // echo $query;
        if ($update) {
            // echo "Record updated successfully";
            // debug_to_console("test");

            array_push($errors2, "Edit Saved");

        } else {
            array_push($errors2, "Error updating record: " . mysqli_error($db));

        }

    }


}



if (isset($_POST['tile2'])) {
    $querycontent = "";
    if ($_POST['tile2_title']) {

        $tile2_title = $_POST['tile2_title'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title='$tile2_title'";

    }

    if ($_POST['tile2_subtitle']) {
        $tile2_subtitle = $_POST['tile2_subtitle'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_subtitle='$tile2_subtitle'";


    }


    if ($_POST['tile2_title1'] != "") {
        // array_push($errors2, "photo 1");

        $tile2_title1 = $_POST['tile2_title1'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title1='$tile2_title1'";
    }
    if ($_FILES["tile2_photo1"]["name"] != "") {
        // array_push($errors2, "photo 1");


        $tile2_photo1 = urlencode(uploadimage($_FILES["tile2_photo1"], "recommendation/", ""));
        if ($tile2_photo1) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile2_photo1'];
                $status = unlink('../assets/img/recommendation/' . $row['tile2_photo1']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile2_photo1='$tile2_photo1'";
            // echo $tile2_photo1;
        }
    }

    if ($_POST['tile2_title2'] != "") {
        // array_push($errors2, "photo 1");

        $tile2_title2 = $_POST['tile2_title2'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title2='$tile2_title2'";
    }
    if ($_FILES["tile2_photo2"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile2_photo2 = urlencode(uploadimage($_FILES["tile2_photo2"], "recommendation/", ""));
        if ($tile2_photo2) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile2_photo2'];
                $status = unlink('../assets/img/recommendation/' . $row['tile2_photo2']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile2_photo2='$tile2_photo2'";
            // echo $tile2_photo2;
        }
    }

    if ($_POST['tile2_title3'] != "") {
        // array_push($errors2, "photo 1");

        $tile2_title3 = $_POST['tile2_title3'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title3='$tile2_title3'";
    }
    if ($_FILES["tile2_photo3"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile2_photo3 = urlencode(uploadimage($_FILES["tile2_photo3"], "recommendation/", ""));
        if ($tile2_photo3) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile2_photo3'];
                $status = unlink('../assets/img/recommendation/' . $row['tile2_photo3']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile2_photo3='$tile2_photo3'";
            // echo $tile2_photo3;
        }
    }
    if ($_POST['tile2_title4'] != "") {
        // array_push($errors2, "photo 1");

        $tile2_title4 = $_POST['tile2_title4'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title4='$tile2_title4'";
    }
    if ($_FILES["tile2_photo4"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile2_photo4 = urlencode(uploadimage($_FILES["tile2_photo4"], "recommendation/", ""));
        if ($tile2_photo4) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile2_photo4'];
                $status = unlink('../assets/img/recommendation/' . $row['tile2_photo4']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile2_photo4='$tile2_photo4'";
            // echo $tile2_photo4;
        }
    }
    if ($_POST['tile2_title5'] != "") {
        // array_push($errors2, "photo 1");

        $tile2_title5 = $_POST['tile2_title5'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title5='$tile2_title5'";
    }
    if ($_FILES["tile2_photo5"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile2_photo5 = urlencode(uploadimage($_FILES["tile2_photo5"], "recommendation/", ""));
        if ($tile2_photo5) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile2_photo5'];
                $status = unlink('../assets/img/recommendation/' . $row['tile2_photo5']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile2_photo5='$tile2_photo5'";
            // echo $tile2_photo5;
        }
    }

    if ($_POST['tile2_title6'] != "") {
        // array_push($errors2, "photo 1");

        $tile2_title6 = $_POST['tile2_title6'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile2_title6='$tile2_title6'";
    }
    if ($_FILES["tile2_photo6"]["name"] != "") {
        // array_push($errors2, "photo 1");



        $tile2_photo6 = urlencode(uploadimage($_FILES["tile2_photo6"], "recommendation/", ""));
        if ($tile2_photo6) {
            $queryselect = "SELECT * FROM indexpage where id='1'";
            $removefile = mysqli_query($db, $queryselect);
            // echo $query;
            while ($row = mysqli_fetch_assoc($removefile)) {
                //  echo $row['tile2_photo6'];
                $status = unlink('../assets/img/recommendation/' . $row['tile2_photo6']);

            }
            if ($querycontent) {
                $querycontent .= ",";
            }
            $querycontent .= "tile2_photo6='$tile2_photo6'";
            // echo $tile2_photo6;
        }
    }


    if ($querycontent) {
        // debug_to_console($querycontent);

        $query = "UPDATE indexpage SET " . $querycontent . "WHERE id='1' ";
        $update = mysqli_query($db, $query);
        // echo $query;
        if ($update) {
            // echo "Record updated successfully";
            // debug_to_console("test");

            array_push($errors2, "Edit Saved");

        } else {
            array_push($errors2, "Error updating record: " . mysqli_error($db));

        }

    }


}


if (isset($_POST['tile3'])) {
    $querycontent = "";
    if ($_POST['tile3_title']) {

        $tile3_title = $_POST['tile3_title'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile3_title='$tile3_title'";

    }

    if ($_POST['tile3_subtitle']) {
        $tile3_subtitle = $_POST['tile3_subtitle'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile3_subtitle='$tile3_subtitle'";


    }

    if ($querycontent) {
        // debug_to_console($querycontent);

        $query = "UPDATE indexpage SET " . $querycontent . "WHERE id='1' ";
        $update = mysqli_query($db, $query);
        // echo $query;
        if ($update) {
            // echo "Record updated successfully";
            // debug_to_console("test");

            array_push($errors2, "Edit Saved");

        } else {
            array_push($errors2, "Error updating record: " . mysqli_error($db));

        }

    }


}


if (isset($_POST['tile4'])) {
    $querycontent = "";
    if ($_POST['tile4_title']) {

        $tile4_title = $_POST['tile4_title'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile4_title='$tile4_title'";

    }

    if ($_POST['tile4_subtitle']) {
        $tile4_subtitle = $_POST['tile4_subtitle'];
        if ($querycontent) {
            $querycontent .= ",";
        }
        $querycontent .= "tile4_subtitle='$tile4_subtitle'";


    }

    if ($querycontent) {
        // debug_to_console($querycontent);

        $query = "UPDATE indexpage SET " . $querycontent . "WHERE id='1' ";
        $update = mysqli_query($db, $query);
        // echo $query;
        if ($update) {
            // echo "Record updated successfully";
            // debug_to_console("test");

            array_push($errors2, "Edit Saved");

        } else {
            array_push($errors2, "Error updating record: " . mysqli_error($db));

        }

    }


}

?>