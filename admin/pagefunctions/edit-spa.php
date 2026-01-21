<?php
// spa
if (isset($_POST['upload_spa'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $filename = uploadimage($_FILES["fileToUpload"], "spa/", "");
    // echo "  asd" . $filename;

    $query = "INSERT INTO spa (spa_title,spa_content,spa_location,spa_locationurl,spa_image,spa_hours,spa_phone,spa_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','0')";
    mysqli_query($db, $query);
    $redirect_url = $_SERVER['PHP_SELF']; // Adjust hash if necessary for WTE_SF table
    header("Location: " . $redirect_url);

}
if (isset($_GET['orderupSPA'])) {

    $order = $_GET['orderupSPA'];
    $spa_id = $_GET['spa_id'];
    $order2 = $order + 1;

    $query = "UPDATE spa SET spa_order= $order2 WHERE spa_id=$spa_id";
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownSPA'])) {

    $order = $_GET['orderdownSPA'];
    $spa_id = $_GET['spa_id'];
    $order2 = $order - 1;

    $query = "UPDATE spa SET spa_order= $order2 WHERE spa_id=$spa_id";
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deletespa'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['spaid'];
    $filename = $_POST['imagenamespa'];
    $query = "DELETE FROM spa WHERE spa_id='$id' ";
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/spa/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editspa'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['spaid'];
    $filename = $_POST['imagenamespa'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE spa SET 
    spa_title='$name',
    spa_order='$order',
    spa_location='$location',
    spa_locationurl='$locationurl',
    spa_content='$content',
    spa_hours='$hours',
    spa_phone='$phone'
    
    WHERE spa_id=$id ";
    $update = mysqli_query($db, $query);
    if ($update) {
        // echo "Record updated successfully";

        array_push($errors2, "Edit Saved");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }

}
// spa

?>