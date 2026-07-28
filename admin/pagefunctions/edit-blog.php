<?php
// Guard: included by functions.php which runs on public pages too.
if (!isset($_SESSION['username'])) return;

if (isset($_POST['editblogview2'])) {
    $id = $_POST['hiddenid'];
    $number = $_POST['valueupdate'];
    

    $query = "UPDATE blog SET blog_view2= '$number' WHERE blog_id='$id' LIMIT 1";
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Updated Views");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}

//blog
if (isset($_POST['refreshblogitem'])) {

    $url = 'https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts?key=AIzaSyC7NA9vDhkVtk4lWisJxGW--fYXLIeM__w&maxResults=500&sortOption=ascending';
    $json_data = file_get_contents($url);
    $response_data = json_decode($json_data);

    $blogposts = json_decode($json_data)->items;

    $query = "SELECT * FROM blog";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {

        $rowcount = mysqli_num_rows($update);

        if ($rowcount != count($blogposts)) {

            foreach ($blogposts as $blog) {
                $title = urlencode($blog->title);
                $postid = $blog->id;
                // var_dump($blog->id);
                // debug_to_console($postid);
                // echo $postid;
                // echo '<br/>';
                $view = 0;
                $viewsettings = 0;

                $query = "INSERT INTO blog (blog_postid, blog_title, blog_view, blog_viewsettings) 
                VALUES('$postid', '$title', '$view', '$viewsettings') ON DUPLICATE KEY UPDATE blog_postid='$postid', blog_title='$title'  ";
                $update = mysqli_query($db, $query);
                if ($update) {
                    // debug_to_console("test");

                    array_push($errors2, "Success");

                } else {
                    array_push($errors2, "No Update");
                }
                // echo $query;
                // echo "<br>";
            }
        } else {

        }
    } else {
        echo "Error updating record: " . mysqli_error($db);
    }



}

// NOTE: the three public blog/page view-counter actions that used to live here
// (postidviews, postidviewsupdate, updatepageview) moved to
// pagefunctions/public-viewcount.php. They are hit by anonymous site visitors
// via assets/js/blog-details.js and blog2.js, so they must stay OUTSIDE the
// admin-session gate that now wraps the rest of the pagefunctions includes.

if (isset($_GET['enableview'])) {

    $postid = $_GET['enableview'];

    // echo "adasdasda";

    // echo $postid;
    $query = "UPDATE blog SET blog_viewsettings= 1 WHERE blog_postid='$postid'";
    // debug_to_console($postid);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Views Enabled");

    } else {
        // echo "fail";

    }

}
if (isset($_GET['disableview'])) {

    $postid = $_GET['disableview'];

    // echo "adasdasda";

    // echo $postid;
    $query = "UPDATE blog SET blog_viewsettings= 0 WHERE blog_postid='$postid'";
    // debug_to_console($postid);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Views Disabled");

    } else {
        // echo "fail";

    }

}
if (isset($_GET['initblog'])) {
    $output = "";
    if (isset($_GET['page'])) {
        $pagination = $_GET['page'];

    } else {
        $pagination = 0;
    }

    $url = "https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts?key=AIzaSyC7NA9vDhkVtk4lWisJxGW--fYXLIeM__w&fetchImages=true&maxResults=200";
    if (isset($_GET['tags'])) {
        $tags = urlencode($_GET['tags']);
        $url .= "&labels=" . $tags;
    }
    $json = file_get_contents($url);
    $json_data = json_decode($json);
    $itemsinarray = $json_data->items;

    $array = [];
    array_push($array, $json_data->items);
    $output = json_encode($array);
    echo $output;

}

?>