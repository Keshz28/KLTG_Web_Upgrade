<?php
// event/Upcoming Highlights
if (isset($_POST['upload_ev'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $location = mysqli_real_escape_string($db, $_POST['location']);
    $locationurl = mysqli_real_escape_string($db, $_POST['locationurl']);
    $website = mysqli_real_escape_string($db, $_POST['website']);
    $content = mysqli_real_escape_string($db, $_POST['content']);
    $content2 = mysqli_real_escape_string($db, $_POST['content2']);
    $imagename = ['imagename'];
    $hours = mysqli_real_escape_string($db, $_POST['hours']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $category = $_POST['category'];
    $facebook = mysqli_real_escape_string($db, $_POST['facebook']);
    $instagram = mysqli_real_escape_string($db, $_POST['instagram']);
    $tiktok = mysqli_real_escape_string($db, $_POST['tiktok']);
    $youtube = mysqli_real_escape_string($db, $_POST['youtube']);
    $twitter = mysqli_real_escape_string($db, $_POST['twitter']);
    $filename = uploadimage($_FILES["fileToUploadev"], "event", "");

    $query = "INSERT INTO `event` (`event_title`, `event_content`, `event_content2`, `event_location`, `event_locationurl`, `event_website`, `event_image`, `event_hours`, `event_phone`, `event_category`, `event_day`, `event_month`, `event_year`, `event_order`, `event_facebook`, `event_instagram`, `event_tiktok`, `event_youtube`, `event_twitter`) 
              VALUES ('$name', '$content', '$content2', '$location', '$locationurl', '$website', '$filename', '$hours', '$phone', '$category', '$day', '$month', '$year', '0', '$facebook', '$instagram', '$tiktok', '$youtube', '$twitter')";

    if (mysqli_query($db, $query)) {
        array_push($errors2, "Added New Event");
    } else {
        array_push($errors2, "Error adding event: " . mysqli_error($db));
    }
}


if (isset($_GET['orderupEV'])) {

    // debug_to_console("test");

    $order = $_GET['orderupEV'];
    $event_id = $_GET['event_id'];
    $order2 = $order + 1;

    $query = "UPDATE event SET event_order= $order2 WHERE event_id=$event_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownEV'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownEV'];
    $event_id = $_GET['event_id'];
    $order2 = $order - 1;

    $query = "UPDATE event SET event_order= $order2 WHERE event_id=$event_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}

if (isset($_POST['deleteev'])) {
    $id = $_POST['event_id'];
    $filename = $_POST['imageev'];
    $query = "DELETE FROM event WHERE event_id='$id' ";
    $update = mysqli_query($db, $query);

    if ($update) {
        $status = unlink('../assets/img/event/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }
    } else {
        echo "Error deleting record: " . mysqli_error($db);
    }
}

if (isset($_POST['editev'])) {
    $id = $_POST['event_id'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $content2 = $_POST['content2'];
    $hours = $_POST['hours'];
    $phone = $_POST['phone'];
    $order = $_POST['order'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $website = $_POST['website'];
    $category = $_POST['category'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $tiktok = $_POST['tiktok'];
    $youtube = $_POST['youtube'];
    $twitter = $_POST['twitter'];
    $imagename = ['imagename'];

    // Handle file upload if a new file is provided
    if (isset($_FILES['fileToUploadev']) && $_FILES['fileToUploadev']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/event/';
        $uploadedFile = $uploadDir . basename($_FILES['fileToUploadev']['name']);
        
        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES['fileToUploadev']['tmp_name'], $uploadedFile)) {
            $filename = basename($_FILES['fileToUploadev']['name']);
        } else {
            echo "Error uploading file.";
            return;
        }
    } else {
        // Use the existing filename if no new file is uploaded
        $filename = $_POST['imagename'];
    }

    // Update query
    $query = "UPDATE event SET 
              event_title='$title', 
              event_content='$content', 
              event_content2='$content2', 
              event_location='$location', 
              event_locationurl='$locationurl', 
              event_website='$website', 
              event_image='$filename', 
              event_hours='$hours', 
              event_phone='$phone', 
              event_category='$category', 
              event_order='$order', 
              event_year='$year', 
              event_month='$month', 
              event_day='$day',
              `event_facebook` = '$facebook',
              `event_instagram` = '$instagram',
              `event_tiktok` = '$tiktok',
              `event_youtube` = '$youtube',
              `event_twitter` = '$twitter'
              WHERE `event_id` = '$id'";

    $update = mysqli_query($db, $query);

    if ($update) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}



?>