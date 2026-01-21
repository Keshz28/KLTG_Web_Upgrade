<?php
if (isset($_POST['editviews2'])) {
    $id = $_POST['hiddenid'];
    $url = $_POST['hiddenurl'];
    $number = $_POST['valueupdate'];
    $views = $_POST['hiddenviews'];
    

    $query = "UPDATE pageview2 SET views2= '$number' WHERE id='$id' LIMIT 1";

    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Updated Views");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}

?>