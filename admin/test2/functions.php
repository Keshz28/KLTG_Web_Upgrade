<?php
session_start();

// initializing variables
$username = "";
$email = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'kltgtest');


//edbug fucntion
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "email already exists");
        }
    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypt the password before saving in the database

        $query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}


// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}



// Check if image file is a actual image or fake image
if (isset($_POST["upload_banner"])) {

    $target_dir = "../assets/img/banner/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    echo $target_file;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        array_push($errors, "Wrongasdasdsan");

        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";

            $filename = basename($_FILES["fileToUpload"]["name"]);

            $query = "INSERT INTO banner (banner_filename) VALUES('$filename')";
            mysqli_query($db, $query);


        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}


if (isset($_GET['orderup'])) {

    // debug_to_console("test");

    $order = $_GET['orderup'];
    $banner_id = $_GET['banner_id'];
    $order2 = $order - 1;
    // $query = "UPDATE banner SET banner_order= $order WHERE banner_id=$banner_id-1";
    // debug_to_console($query);
    // $update = mysqli_query($db, $query);
    // if ($update) {
    //     echo "Record updated successfully";

    // } else {
    //     echo "Error updating record: " . mysqli_error($db);
    // }
    $query = "UPDATE banner SET banner_order= $order2 WHERE banner_id=$banner_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        echo "Record updated successfully";

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}

if (isset($_GET['orderdown'])) {

    // debug_to_console("test");

    $order = $_GET['orderdown'];
    $banner_id = $_GET['banner_id'];
    $order2 = $order + 1;
    // $query = "UPDATE banner SET banner_order= $order WHERE banner_id=$banner_id-1";
    // debug_to_console($query);
    // $update = mysqli_query($db, $query);
    // if ($update) {
    //     echo "Record updated successfully";

    // } else {
    //     echo "Error updating record: " . mysqli_error($db);
    // }
    $query = "UPDATE banner SET banner_order= $order2 WHERE banner_id=$banner_id";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        echo "Record updated successfully";

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
}





if (isset($_POST['editbanner'])) {

    // debug_to_console("test");

    $name = $_POST['name'];
    $order = $_POST['order'];
    $filename = $_POST['filename'];
    $id = $_POST['id'];

    $query = "UPDATE banner SET banner_name='$name', banner_order= '$order' WHERE banner_id='$id'";
    debug_to_console($query);
    $update = mysqli_query($db, $query);
    if ($update) {
        echo "Record updated successfully";

    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
    echo '<script type="text/JavaScript"> 
    $("#exampleModal3").modal("hide");
    </script>';




}




?>