<?php
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

if (isset($_GET['postidviews'])) {

    $postid = $_GET['postidviews'];

    $query = "SELECT * FROM blog WHERE blog_postid='$postid'";
    // debug_to_console($query);
    $update = mysqli_query($db, $query);
    while ($row = mysqli_fetch_assoc($update)) {
        if ($row['blog_viewsettings'] == 1) {
            echo $row['blog_view'];

        } else {
            echo 0;
        }
    }
    ;

}

if (isset($_GET['updatepageview'])) {

    $urlpageview = $_GET['updatepageview'];


    $query4 = "INSERT INTO pageview (url, views) 
        VALUES('$urlpageview', '1')";
    mysqli_query($db, $query4);

}

if (isset($_GET['postidviewsupdate'])) {

    $postid = $_GET['postidviewsupdate'];

    $query = "UPDATE blog SET blog_view=blog_view + 1 WHERE blog_postid='$postid'";
    // debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        echo "success";
    } else {
        echo "fail";

    }

}
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