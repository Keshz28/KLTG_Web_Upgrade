<?php

// accommodation top
if (isset($_POST['upload_atop'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $filename = uploadimage($_FILES["fileToUploadatop"], "accommodation/", "top/");
    // echo "  asd" . $filename;

    $query = "INSERT INTO accommodation_top (accommodation_top_title,accommodation_top_content,accommodation_top_location,accommodation_top_locationurl,accommodation_top_image,accommodation_top_hours,accommodation_top_phone,accommodation_top_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New accommodation_top");


}
if (isset($_GET['orderupATOP'])) {

    // debug_to_console("test");

    $order = $_GET['orderupATOP'];
    $accommodation_top_id = $_GET['accommodation_top_id'];
    $order2 = $order + 1;

    $query = "UPDATE accommodation_top SET accommodation_top_order= $order2 WHERE accommodation_top_id=$accommodation_top_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownATOP'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownATOP'];
    $accommodation_top_id = $_GET['accommodation_top_id'];
    $order2 = $order - 1;

    $query = "UPDATE accommodation_top SET accommodation_top_order= $order2 WHERE accommodation_top_id=$accommodation_top_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deleteatop'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['atopid'];
    $filename = $_POST['imagenameatop'];
    $query = "DELETE FROM accommodation_top WHERE accommodation_top_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/accommodation/top/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editatop'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['atopid'];
    $filename = $_POST['imagenameatop'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE accommodation_top SET 
    accommodation_top_title='$name',
    accommodation_top_order='$order',
    accommodation_top_location='$location',
    accommodation_top_locationurl='$locationurl',
    accommodation_top_content='$content',
    accommodation_top_hours='$hours',
    accommodation_top_phone='$phone'
    
    WHERE accommodation_top_id=$id ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        // echo "Record updated successfully";
        // debug_to_console("test");

        array_push($errors2, "Edit Saved");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }

}


// accommodation



// accommodation h
if (isset($_POST['upload_ah'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $filename = uploadimage($_FILES["fileToUploadah"], "accommodation/", "h/");
    // echo "  asd" . $filename;

    $query = "INSERT INTO accommodation_h (accommodation_h_title,accommodation_h_content,accommodation_h_location,accommodation_h_locationurl,accommodation_h_image,accommodation_h_hours,accommodation_h_phone,accommodation_h_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New accommodation_h");


}
if (isset($_GET['orderupAH'])) {

    // debug_to_console("test");

    $order = $_GET['orderupAH'];
    $accommodation_h_id = $_GET['accommodation_h_id'];
    $order2 = $order + 1;

    $query = "UPDATE accommodation_h SET accommodation_h_order= $order2 WHERE accommodation_h_id=$accommodation_h_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownAH'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownAH'];
    $accommodation_h_id = $_GET['accommodation_h_id'];
    $order2 = $order - 1;

    $query = "UPDATE accommodation_h SET accommodation_h_order= $order2 WHERE accommodation_h_id=$accommodation_h_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deleteah'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['ahid'];
    $filename = $_POST['imagenameah'];
    $query = "DELETE FROM accommodation_h WHERE accommodation_h_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/accommodation/h/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editah'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['ahid'];
    $filename = $_POST['imagenameah'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE accommodation_h SET 
    accommodation_h_title='$name',
    accommodation_h_order='$order',
    accommodation_h_location='$location',
    accommodation_h_locationurl='$locationurl',
    accommodation_h_content='$content',
    accommodation_h_hours='$hours',
    accommodation_h_phone='$phone'
    
    WHERE accommodation_h_id=$id ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        // echo "Record updated successfully";
        // debug_to_console("test");

        array_push($errors2, "Edit Saved");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }

}
// accommodation




// accommodation bh
if (isset($_POST['upload_abh'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $filename = uploadimage($_FILES["fileToUploadabh"], "accommodation/", "bh/");
    // echo "  asd" . $filename;

    $query = "INSERT INTO accommodation_bh (accommodation_bh_title,accommodation_bh_content,accommodation_bh_location,accommodation_bh_locationurl,accommodation_bh_image,accommodation_bh_hours,accommodation_bh_phone,accommodation_bh_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New accommodation_bh");


}
if (isset($_GET['orderupABH'])) {

    // debug_to_console("test");

    $order = $_GET['orderupABH'];
    $accommodation_bh_id = $_GET['accommodation_bh_id'];
    $order2 = $order + 1;

    $query = "UPDATE accommodation_bh SET accommodation_bh_order= $order2 WHERE accommodation_bh_id=$accommodation_bh_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownABH'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownABH'];
    $accommodation_bh_id = $_GET['accommodation_bh_id'];
    $order2 = $order - 1;

    $query = "UPDATE accommodation_bh SET accommodation_bh_order= $order2 WHERE accommodation_bh_id=$accommodation_bh_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deleteabh'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['abhid'];
    $filename = $_POST['imagenameah'];
    $query = "DELETE FROM accommodation_bh WHERE accommodation_bh_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/accommodation/bh/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editabh'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['abhid'];
    $filename = $_POST['imagenameabh'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE accommodation_bh SET 
    accommodation_bh_title='$name',
    accommodation_bh_order='$order',
    accommodation_bh_location='$location',
    accommodation_bh_locationurl='$locationurl',
    accommodation_bh_content='$content',
    accommodation_bh_hours='$hours',
    accommodation_bh_phone='$phone'
    
    WHERE accommodation_bh_id=$id ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        // echo "Record updated successfully";
        // debug_to_console("test");

        array_push($errors2, "Edit Saved");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }

}
// accommodation




// accommodation bks
if (isset($_POST['upload_abks'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $filename = uploadimage($_FILES["fileToUploadabks"], "accommodation/", "bks/");
    // echo "  asd" . $filename;

    $query = "INSERT INTO accommodation_bks (accommodation_bks_title,accommodation_bks_content,accommodation_bks_location,accommodation_bks_locationurl,accommodation_bks_image,accommodation_bks_hours,accommodation_bks_phone,accommodation_bks_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New accommodation_bks");


}
if (isset($_GET['orderupABKS'])) {

    // debug_to_console("test");

    $order = $_GET['orderupABKS'];
    $accommodation_bks_id = $_GET['accommodation_bks_id'];
    $order2 = $order + 1;

    $query = "UPDATE accommodation_bks SET accommodation_bks_order= $order2 WHERE accommodation_bks_id=$accommodation_bks_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownABKS'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownABKS'];
    $accommodation_bks_id = $_GET['accommodation_bks_id'];
    $order2 = $order - 1;

    $query = "UPDATE accommodation_bks SET accommodation_bks_order= $order2 WHERE accommodation_bks_id=$accommodation_bks_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deleteabks'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['abksid'];
    $filename = $_POST['imagenameabks'];
    $query = "DELETE FROM accommodation_bks WHERE accommodation_bks_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/accommodation/bks/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editabks'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['abksid'];
    $filename = $_POST['imagenameabks'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE accommodation_bks SET 
    accommodation_bks_title='$name',
    accommodation_bks_order='$order',
    accommodation_bks_location='$location',
    accommodation_bks_locationurl='$locationurl',
    accommodation_bks_content='$content',
    accommodation_bks_hours='$hours',
    accommodation_bks_phone='$phone'
    
    WHERE accommodation_bks_id=$id ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        // echo "Record updated successfully";
        // debug_to_console("test");

        array_push($errors2, "Edit Saved");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }

}
// accommodation
?>