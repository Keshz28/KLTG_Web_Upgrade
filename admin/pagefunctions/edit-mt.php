<?php
// medical tourism healthcare 
if (isset($_POST['upload_mthc'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $filename = uploadimage($_FILES["fileToUploadhc"], "medical_tourism", "hc/");
    // echo "  asd" . $filename;

    $query = "INSERT INTO medical_tourism_hc (medical_tourism_hc_title,medical_tourism_hc_content,medical_tourism_hc_location,medical_tourism_hc_locationurl,medical_tourism_hc_image,medical_tourism_hc_hours,medical_tourism_hc_phone,medical_tourism_hc_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Medical Tourism Healthcare");


}
if (isset($_GET['orderupMTH'])) {

    // debug_to_console("test");

    $order = $_GET['orderupMTH'];
    $medical_tourism_hc_id = $_GET['medical_tourism_hc_id'];
    $order2 = $order + 1;

    $query = "UPDATE medical_tourism_hc SET medical_tourism_hc_order= $order2 WHERE medical_tourism_hc_id=$medical_tourism_hc_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownMTH'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownMTH'];
    $medical_tourism_hc_id = $_GET['medical_tourism_hc_id'];
    $order2 = $order - 1;

    $query = "UPDATE medical_tourism_hc SET medical_tourism_hc_order= $order2 WHERE medical_tourism_hc_id=$medical_tourism_hc_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}

if (isset($_POST['deletemthc'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['mthcid'];
    $filename = $_POST['imagenamemthc'];
    $query = "DELETE FROM medical_tourism_hc WHERE medical_tourism_hc_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/medical_tourism/hc/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editmthc'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['mthcid'];
    $filename = $_POST['imagenamemthc'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE medical_tourism_hc SET 
    medical_tourism_hc_title='$name',
    medical_tourism_hc_order='$order',
    medical_tourism_hc_location='$location',
    medical_tourism_hc_locationurl='$locationurl',
    medical_tourism_hc_content='$content',
    medical_tourism_hc_hours='$hours',
    medical_tourism_hc_phone='$phone'
    
    WHERE medical_tourism_hc_id=$id ";
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
// end medical tourism healthcare


// medical tourism dtl
if (isset($_POST['upload_mtdtl'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];

    $filename = uploadimage($_FILES["fileToUploaddtl"], "medical_tourism", "dtl/");
    // echo "  asd" . $filename;


    $query = "INSERT INTO medical_tourism_dtl (medical_tourism_dtl_title,medical_tourism_dtl_content,medical_tourism_dtl_location,medical_tourism_dtl_locationurl,medical_tourism_dtl_image,medical_tourism_dtl_hours,medical_tourism_dtl_phone,medical_tourism_dtl_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone', '0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Medical Tourism Dental");


}
if (isset($_GET['orderupMTDTL'])) {

    // debug_to_console("test");

    $order = $_GET['orderupMTDTL'];
    $medical_tourism_dtl_id = $_GET['medical_tourism_dtl_id'];
    $order2 = $order + 1;

    $query = "UPDATE medical_tourism_dtl SET medical_tourism_dtl_order= $order2 WHERE medical_tourism_dtl_id=$medical_tourism_dtl_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownMTDTL'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownMTDTL'];
    $medical_tourism_dtl_id = $_GET['medical_tourism_dtl_id'];
    $order2 = $order - 1;

    $query = "UPDATE medical_tourism_dtl SET medical_tourism_dtl_order= $order2 WHERE medical_tourism_dtl_id=$medical_tourism_dtl_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deletemtDTL'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['mtdtlid'];
    $filename = $_POST['imagenamemtdtl'];
    $query = "DELETE FROM medical_tourism_dtl WHERE medical_tourism_dtl_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/medical_tourism/dtl/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editmtDTL'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['mtdtlid'];
    $filename = $_POST['imagenamemtdtl'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE medical_tourism_dtl SET 
    medical_tourism_dtl_title='$name',
    medical_tourism_dtl_order='$order',
    medical_tourism_dtl_location='$location',
    medical_tourism_dtl_locationurl='$locationurl',
    medical_tourism_dtl_content='$content',
    medical_tourism_dtl_hours='$hours',
    medical_tourism_dtl_phone='$phone'

    WHERE medical_tourism_dtl_id=$id ";
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
// end medical tourism dtl


// medical tourism der
if (isset($_POST['upload_mtder'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];

    $filename = uploadimage($_FILES["fileToUploadder"], "medical_tourism", "der/");
    // echo "  asd" . $filename;


    $query = "INSERT INTO medical_tourism_der (medical_tourism_der_title,medical_tourism_der_content,medical_tourism_der_location,medical_tourism_der_locationurl,medical_tourism_der_image,medical_tourism_der_hours,medical_tourism_der_phone,medical_tourism_der_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone', '0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Medical Tourism Dental");


}
if (isset($_GET['orderupMTDER'])) {

    // debug_to_console("test");

    $order = $_GET['orderupMTDER'];
    $medical_tourism_der_id = $_GET['medical_tourism_der_id'];
    $order2 = $order + 1;

    $query = "UPDATE medical_tourism_der SET medical_tourism_der_order= $order2 WHERE medical_tourism_der_id=$medical_tourism_der_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownMTDER'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownMTDER'];
    $medical_tourism_der_id = $_GET['medical_tourism_der_id'];
    $order2 = $order - 1;

    $query = "UPDATE medical_tourism_der SET medical_tourism_der_order= $order2 WHERE medical_tourism_der_id=$medical_tourism_der_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deletemtDER'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['mtderid'];
    $filename = $_POST['imagenamemtder'];
    $query = "DELETE FROM medical_tourism_der WHERE medical_tourism_der_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/medical_tourism/der/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editmtDER'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['mtderid'];
    $filename = $_POST['imagenamemtder'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE medical_tourism_der SET 
    medical_tourism_der_title='$name',
    medical_tourism_der_order='$order',
    medical_tourism_der_location='$location',
    medical_tourism_der_locationurl='$locationurl',
    medical_tourism_der_content='$content',
    medical_tourism_der_hours='$hours',
    medical_tourism_der_phone='$phone'

    WHERE medical_tourism_der_id=$id ";
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


// end medical tourism der


// medical tourism oph
if (isset($_POST['upload_mtoph'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];

    $filename = uploadimage($_FILES["fileToUploadoph"], "medical_tourism", "oph/");
    // echo "  asd" . $filename;


    $query = "INSERT INTO medical_tourism_oph (medical_tourism_oph_title,medical_tourism_oph_content,medical_tourism_oph_location,medical_tourism_oph_locationurl,medical_tourism_oph_image,medical_tourism_oph_hours,medical_tourism_oph_phone,medical_tourism_oph_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone', '0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Medical Tourism Oph");


}
if (isset($_GET['orderupMTOPH'])) {

    // debug_to_console("test");

    $order = $_GET['orderupMTOPH'];
    $medical_tourism_oph_id = $_GET['medical_tourism_oph_id'];
    $order2 = $order + 1;

    $query = "UPDATE medical_tourism_oph SET medical_tourism_oph_order= $order2 WHERE medical_tourism_oph_id=$medical_tourism_oph_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownMTOPH'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownMTOPH'];
    $medical_tourism_oph_id = $_GET['medical_tourism_oph_id'];
    $order2 = $order - 1;

    $query = "UPDATE medical_tourism_oph SET medical_tourism_oph_order= $order2 WHERE medical_tourism_oph_id=$medical_tourism_oph_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deletemtOPH'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['mtophid'];
    $filename = $_POST['imagenamemtoph'];
    $query = "DELETE FROM medical_tourism_oph WHERE medical_tourism_oph_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/medical_tourism/oph/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editmtOPH'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $content = urlencode($_POST['content']);
    $id = $_POST['mtophid'];
    $filename = $_POST['imagenamemtoph'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE medical_tourism_oph SET 
    medical_tourism_oph_title='$name',
    medical_tourism_oph_order='$order',
    medical_tourism_oph_location='$location',
    medical_tourism_oph_locationurl='$locationurl',
    medical_tourism_oph_content='$content',
    medical_tourism_oph_hours='$hours',
    medical_tourism_oph_phone='$phone'

    WHERE medical_tourism_oph_id=$id ";
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

// medical tourism ps
if (isset($_POST['upload_mtps'])) {

    $name = urlencode($_POST['name']);
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $content = urlencode($_POST['content']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $article = $_POST['article'];

    $filename = uploadimage($_FILES["fileToUploadps"], "medical_tourism", "ps/");
    // echo "  asd" . $filename;


    $query = "INSERT INTO medical_tourism_ps (medical_tourism_ps_title,medical_tourism_ps_content,medical_tourism_ps_location,medical_tourism_ps_locationurl,medical_tourism_ps_image,medical_tourism_ps_hours,medical_tourism_ps_phone,medical_tourism_ps_websiteurl,medical_tourism_ps_article,medical_tourism_ps_order) 
                                            VALUES('$name','$content','$location','$locationurl','$filename','$hours','$phone','$website','$article', '0')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Medical Tourism Ps");


}
if (isset($_GET['orderupMTPS'])) {

    // debug_to_console("test");

    $order = $_GET['orderupMTPS'];
    $medical_tourism_ps_id = $_GET['medical_tourism_ps_id'];
    $order2 = $order + 1;

    $query = "UPDATE medical_tourism_ps SET medical_tourism_ps_order= $order2 WHERE medical_tourism_ps_id=$medical_tourism_ps_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_GET['orderdownMTPS'])) {

    // debug_to_console("test");

    $order = $_GET['orderdownMTOPS'];
    $medical_tourism_ps_id = $_GET['medical_tourism_ps_id'];
    $order2 = $order - 1;

    $query = "UPDATE medical_tourism_ps SET medical_tourism_ps_order= $order2 WHERE medical_tourism_ps_id=$medical_tourism_ps_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        array_push($errors2, "Order Changed");

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['deletemtPS'])) {


    $name = $_POST['name'];
    $order = $_POST['order'];
    $location = $_POST['location'];
    $locationurl = $_POST['locationurl'];
    $content = $_POST['content'];
    $id = $_POST['mtpsid'];
    $filename = $_POST['imagenamemtps'];
    $query = "DELETE FROM medical_tourism_ps WHERE medical_tourism_ps_id='$id' ";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {


        $status = unlink('../assets/img/medical_tourism/ps/' . $filename);
        if ($status) {
            array_push($errors2, "Removed");
        } else {
            array_push($errors2, "Failed to remove");
        }

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}
if (isset($_POST['editmtPS'])) {


    $name = urlencode($_POST['name']);
    $order = $_POST['order'];
    $location = urlencode($_POST['location']);
    $locationurl = urlencode($_POST['locationurl']);
    $hours = urlencode($_POST['hours']);
    $phone = $_POST['phone'];
    $website = urlencode($_POST['website']);
    $article = urlencode($_POST['article']);
    $content = urlencode($_POST['content']);
    $id = $_POST['mtpsid'];
    $filename = $_POST['imagenamemtps'];

    // $filename = $_POST['filename'];
    // $id = $_POST['id'];

    $query = "UPDATE medical_tourism_ps SET 
    medical_tourism_ps_title='$name',
    medical_tourism_ps_order='$order',
    medical_tourism_ps_location='$location',
    medical_tourism_ps_locationurl='$locationurl',
    medical_tourism_ps_content='$content',
    medical_tourism_ps_hours='$hours',
    medical_tourism_ps_phone='$phone',
    medical_tourism_ps_websiteurl='$website',
    medical_tourism_ps_article='$article'

    WHERE medical_tourism_ps_id=$id ";
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


if (isset($_POST['addnewnavmt'])) {

    $name = urlencode($_POST['name']);
    $orderof = urlencode($_POST['orderof']);
    $display = urlencode($_POST['display']);


    $filename = uploadimage($_FILES["fileToUploaddtl"], "medical_tourism", "");
    // echo "  asd" . $filename;


    $query = "INSERT INTO medical_tourism_nav (name,orderof,display,filename) 
                                            VALUES('$name','$orderof','$display','$filename')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Medical Tourism Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();

}



if (isset($_POST['editnavmt'])) {
    // foreach ($_POST as $key => $value) {
    //     // echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
    //     debug_to_console($key . " : " . $value);
    // }
    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/medical_tourism/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "medical_tourism", "");
    }
    // echo "  asd" . $filename;


    $query = "UPDATE medical_tourism_nav SET name='$name' , orderof='$orderof', display='$display', medical_tourism_nav.filename='$filename' WHERE id='$id'";
    mysqli_query($db, $query);
    array_push($errors2, "Edit Medical Tourism Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
// end medical tourism dtl
if (isset($_POST['deletenavmt'])) {

    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/medical_tourism/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "medical_tourism", "");
    }

    $query = "DELETE FROM medical_tourism_nav WHERE id='$id' ";
    // debug_to_console($query);
    $update = mysqli_query($db, $query);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}


if (isset($_POST['addnewnavaccommodation'])) {

    $name = urlencode($_POST['name']);
    $orderof = urlencode($_POST['orderof']);
    $display = urlencode($_POST['display']);


    $filename = uploadimage($_FILES["fileToUploaddtl"], "accommodation", "");
    // echo "  asd" . $filename;


    $query = "INSERT INTO accommodation_nav (name,orderof,display,filename) 
                                            VALUES('$name','$orderof','$display','$filename')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New Accommodation Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();

}



if (isset($_POST['editnavaccommodation'])) {
    // foreach ($_POST as $key => $value) {
    //     // echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
    //     debug_to_console($key . " : " . $value);
    // }
    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/accommodation/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "accommodation", "");
    }
    // echo "  asd" . $filename;


    $query = "UPDATE accommodation_nav SET name='$name' , orderof='$orderof', display='$display', accommodation_nav.filename='$filename' WHERE id='$id'";
    mysqli_query($db, $query);
    array_push($errors2, "Edit accommodation Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
// end medical tourism dtl
if (isset($_POST['deletenavaccommodation'])) {

    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/accommodation/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "accommodation", "");
    }

    $query = "DELETE FROM accommodation_nav WHERE id='$id' ";
    // debug_to_console($query);
    $update = mysqli_query($db, $query);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}



if (isset($_POST['addnewnavbeyondkl'])) {

    $name = urlencode($_POST['name']);
    $orderof = urlencode($_POST['orderof']);
    $display = urlencode($_POST['display']);


    $filename = uploadimage($_FILES["fileToUploaddtl"], "beyondkl", "");
    // echo "  asd" . $filename;


    $query = "INSERT INTO beyondkl_nav (name,orderof,display,filename) 
                                            VALUES('$name','$orderof','$display','$filename')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New beyondkl Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();

}



if (isset($_POST['editnavbeyondkl'])) {
    // foreach ($_POST as $key => $value) {
    //     // echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
    //     debug_to_console($key . " : " . $value);
    // }
    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/beyondkl/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "beyondkl", "");
    }
    // echo "  asd" . $filename;


    $query = "UPDATE beyondkl_nav SET name='$name' , orderof='$orderof', display='$display', beyondkl_nav.filename='$filename' WHERE id='$id'";
    mysqli_query($db, $query);
    array_push($errors2, "Edit beyondkl Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
// end medical tourism dtl
if (isset($_POST['deletenavbeyondkl'])) {

    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/beyondkl/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "beyondkl", "");
    }

    $query = "DELETE FROM beyondkl_nav WHERE id='$id' ";
    // debug_to_console($query);
    $update = mysqli_query($db, $query);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}




if (isset($_POST['addnewnavexplorekl'])) {

    $name = urlencode($_POST['name']);
    $orderof = urlencode($_POST['orderof']);
    $display = urlencode($_POST['display']);


    $filename = uploadimage($_FILES["fileToUploaddtl"], "explorekl", "");
    // echo "  asd" . $filename;


    $query = "INSERT INTO explorekl_nav (name,orderof,display,filename) 
                                            VALUES('$name','$orderof','$display','$filename')";
    mysqli_query($db, $query);
    array_push($errors2, "Added New explorekl Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();

}



if (isset($_POST['editnavexplorekl'])) {
    // foreach ($_POST as $key => $value) {
    //     // echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";
    //     debug_to_console($key . " : " . $value);
    // }
    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/explorekl/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "explorekl", "");
    }
    // echo "  asd" . $filename;


    $query = "UPDATE explorekl_nav SET name='$name' , orderof='$orderof', display='$display', explorekl_nav.filename='$filename' WHERE id='$id'";
    mysqli_query($db, $query);
    array_push($errors2, "Edit explorekl Navigation");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}
// end medical tourism dtl
if (isset($_POST['deletenavexplorekl'])) {

    $id = ($_POST['id']);
    $name = ($_POST['name']);
    $orderof = ($_POST['orderof']);
    $display = ($_POST['display']);
    $filename = ($_POST['filename']);

    if (($_FILES["fileToUploaddtl"]["tmp_name"] != "")) {
        if ($filename = "") {
            unlink('../assets/img/explorekl/' . $filename);
        }
        $filename = uploadimage($_FILES["fileToUploaddtl"], "explorekl", "");
    }

    $query = "DELETE FROM explorekl_nav WHERE id='$id' ";
    // debug_to_console($query);
    $update = mysqli_query($db, $query);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}


?>