<?php include('functions.php');


if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>


    <meta name="description" content="">
    <meta name="author" content="">

    <title>KLTG ADMIN - Edit Index</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include('nav.php'); ?>
        <?php

        $query = "SELECT * FROM indexpage ";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $hero_title = $row['hero_title'];
            $hero_title2 = $row['hero_title2'];
            $hero_subtitle = $row['hero_subtitle'];
            $tile1_title = $row['tile1_title'];
            $tile1_subtitle = $row['tile1_subtitle'];
            $tile1_photo1 = $row['tile1_photo1'];
            $tile1_photo2 = $row['tile1_photo2'];
            $tile1_photo3 = $row['tile1_photo3'];
            $tile1_photo4 = $row['tile1_photo4'];
            $tile1_title1 = $row['tile1_title1'];
            $tile1_title2 = $row['tile1_title2'];
            $tile1_title3= $row['tile1_title3'];
            $tile1_title4 = $row['tile1_title4'];
            $tile2_title = $row['tile2_title'];
            $tile2_subtitle = $row['tile2_subtitle'];

            $tile2_photo1 = $row['tile2_photo1'];
            $tile2_photo2 = $row['tile2_photo2'];
            $tile2_photo3 = $row['tile2_photo3'];
            $tile2_photo4 = $row['tile2_photo4'];
            $tile2_photo5 = $row['tile2_photo5'];
            $tile2_photo6 = $row['tile2_photo6'];

            $tile2_title1 = $row['tile2_title1'];
            $tile2_title2 = $row['tile2_title2'];
            $tile2_title3 = $row['tile2_title3'];
            $tile2_title4 = $row['tile2_title4'];
            $tile2_title5 = $row['tile2_title5'];
            $tile2_title6 = $row['tile2_title6'];

            $tile3_title = $row['tile3_title'];
            $tile3_subtitle = $row['tile3_subtitle'];
            $tile4_title = $row['tile4_title'];
            $tile4_subtitle = $row['tile4_subtitle'];
        }
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topnav.php'); ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Index Page</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Banner <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table1" aria-expanded="true"
                                    aria-controls="table1" id="table1a">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table1">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col">File Name 2</th>
                                                <th scope="col">URL</th>
                                                <th scope="col">Click Count</th>

                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#exampleModal2">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>
                                                </th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM banner ORDER BY banner_order ASC";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                if (!$row['banner_order']) {

                                                    echo '<tr>';
                                                    echo '<th id="order-' . $row['banner_id'] . '" scope="row">' . $row['banner_order'] . '</th>';
                                                    echo '<td id="name-' . $row['banner_id'] . '">' . $row['banner_name'] . '</td>';
                                                    echo '<td id="filename-' . $row['banner_id'] . '">' . $row['banner_filename'] . '</td>';
                                                    echo '<td id="filename2-' . $row['banner_id'] . '">' . $row['banner_filename2'] . '</td>';
                                                    echo '<td id="url-' . $row['banner_id'] . '">' . $row['banner_url'] . '</td>';
                                                    echo '<td id="clickcount-' . $row['banner_id'] . '">' . $row['click_count'] . '</td>';
                                                    echo '<td><a href="#" class="" onclick="newmodal(' . $row['banner_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a></td>';

                                                    echo '</tr>';
                                                } else {
                                                    echo '<tr>';
                                                    echo '<th id="order-' . $row['banner_id'] . '" scope="row">' . $row['banner_order'] . '</th>';
                                                    echo '<td id="name-' . $row['banner_id'] . '">' . $row['banner_name'] . '</td>';
                                                    echo '<td id="filename-' . $row['banner_id'] . '">' . $row['banner_filename'] . '</td>';
                                                    echo '<td id="filename2-' . $row['banner_id'] . '">' . $row['banner_filename2'] . '</td>';
                                                    echo '<td id="url-' . $row['banner_id'] . '">' . $row['banner_url'] . '</td>';
                                                    echo '<td id="clickcount-' . $row['banner_id'] . '">' . $row['click_count'] . '</td>';
                                                    echo '<td>
                                                    <a href="#" class="" onclick="newmodal(' . $row['banner_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a>
                                                    <a href="?orderup=' . $row['banner_order'] . '&banner_id=' . $row['banner_id'] . '"><i class="fas fa-chevron-up"></i></a>
                                                    <a href="?orderdown=' . $row['banner_order'] . '&banner_id=' . $row['banner_id'] . '"><i class="fas fa-chevron-down"></i></a>
                                                    </td>';

                                                    echo '</tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- hero/ -->
                    <div class="card shadow mb-4" id="heroedit">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hero / Title <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table2" aria-expanded="true"
                                    aria-controls="table2" id="table2a">
                                    <i class="fas fa-chevron-down"></i>
                                </button></h6>
                        </div>
                        <div class="collapse show" id="table2">

                            <form action="?edit#heroedit" method="post" enctype="multipart/form-data">
                                <div class="card-body" id="hero">
                                    <p>Title</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $hero_title ?>" name="hero_title">
                                        </div>

                                    </div>
                                    <p>Title 2</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $hero_title2 ?>" name="hero_title2">
                                        </div>

                                    </div>
                                    <p>Subtitle</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $hero_subtitle ?>" placeholder="hero_subtitle"
                                                name="hero_subtitle"></textarea>
                                        </div>

                                    </div>
                                    <button type="submit" name="heroedit" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-flag"></i>
                                        </span>
                                        <span class="text">Save</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- kl highlights -->
                    <div class="card shadow mb-4" id="tile1">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">KL Highlights <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table3" aria-expanded="true"
                                    aria-controls="table3" id="table3a">
                                    <i class="fas fa-chevron-down"></i>
                                </button></h6>
                        </div>
                        <div class="collapse show" id="table3">

                            <form action="?edit#tile1" method="post" enctype="multipart/form-data">

                                <div class="card-body" id="highlights">
                                    <p>Title</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile1_title ?>" name="tile1_title">
                                        </div>
                                    </div>
                                    <p>Subtitle</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile1_title ?>"
                                                name="tile1_subtitle"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 1</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile1_title1 ?>"
                                                name="tile1_title1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile1_photo1a" name="tile1_photo1"
                                                placeholder="<?php echo $tile1_photo1 ?>" readonly></input>
                                            <input type="file" name="tile1_photo1" id="tile1_photo1">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 2</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile1_title2 ?>"
                                                name="tile1_title2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile1_photo2a" name="tile1_photo2"
                                                placeholder="<?php echo $tile1_photo2 ?>" readonly></input>
                                            <input type="file" name="tile1_photo2" id="tile1_photo2">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 3</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile1_title3 ?>"
                                                name="tile1_title3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile1_photo3a" name="tile1_photo3"
                                                placeholder="<?php echo $tile1_photo3 ?>" readonly></input>
                                            <input type="file" name="tile1_photo3" id="tile1_photo3">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 4</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile1_title4 ?>"
                                                name="tile1_title4"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile1_photo4a" name="tile1_photo4"
                                                placeholder="<?php echo $tile1_photo4 ?>" readonly></input>
                                            <input type="file" name="tile1_photo4" id="tile1_photo4">
                                        </div>
                                    </div>

                                    <button type="submit" name="tile1" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-flag"></i>
                                        </span>
                                        <span class="text">Save</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>



                    <!-- Recommendations -->
                    <div class="card shadow mb-4" id="tile2">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recommendations <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table4" aria-expanded="true"
                                    aria-controls="table4" id="table4a">
                                    <i class="fas fa-chevron-down"></i>
                                </button></h6>
                        </div>
                        <div class="collapse show" id="table4">

                            <form action="?edit#tile2" method="post" enctype="multipart/form-data">

                                <div class="card-body" id="exreco">
                                    <p>Title</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title ?>" name="tile2_title">
                                        </div>

                                    </div>
                                    <p>Subtitle</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_subtitle ?>" placeholder="tile2_subtitle"
                                                name="tile2_subtitle"></textarea>
                                        </div>

                                    </div>

                                    
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 1</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title1 ?>"
                                                name="tile2_title1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile2_photo1a" name="tile2_photo1"
                                                placeholder="<?php echo $tile2_photo1 ?>" readonly></input>
                                            <input type="file" name="tile2_photo1" id="tile2_photo1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 2</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title2 ?>"
                                                name="tile2_title2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile2_photo2a" name="tile2_photo2"
                                                placeholder="<?php echo $tile2_photo2 ?>" readonly></input>
                                            <input type="file" name="tile2_photo2" id="tile2_photo2">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 3</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title3 ?>"
                                                name="tile2_title3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile2_photo3a" name="tile2_photo3"
                                                placeholder="<?php echo $tile2_photo3 ?>" readonly></input>
                                            <input type="file" name="tile2_photo3" id="tile2_photo3">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 4</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title4 ?>"
                                                name="tile2_title4"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile2_photo4a" name="tile2_photo4"
                                                placeholder="<?php echo $tile2_photo4 ?>" readonly></input>
                                            <input type="file" name="tile2_photo4" id="tile2_photo4">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 5</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title5 ?>"
                                                name="tile2_title5"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile2_photo5a" name="tile2_photo5"
                                                placeholder="<?php echo $tile2_photo5 ?>" readonly></input>
                                            <input type="file" name="tile2_photo5" id="tile2_photo5">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label  class="form-label">Title 6</label><br>
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile2_title6 ?>"
                                                name="tile2_title6"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input class="form-control" id="tile2_photo6a" name="tile2_photo6"
                                                placeholder="<?php echo $tile2_photo6 ?>" readonly></input>
                                            <input type="file" name="tile2_photo6" id="tile2_photo6">
                                        </div>
                                    </div>

                                    <button type="submit" name="tile2" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-flag"></i>
                                        </span>
                                        <span class="text">Save</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4" id="recommendcard">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Insider <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table5" aria-expanded="true"
                                    aria-controls="table5" id="table5a">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse show" id="table5">

                            <div class="card-body">

                                <form action="?edit#recommendcard" method="post" enctype="multipart/form-data">

                                    <p>Title</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile3_title ?>" name="tile3_title">
                                        </div>

                                    </div>
                                    <p>Subtitle</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile3_subtitle ?>" placeholder="tile3_subtitle"
                                                name="tile3_subtitle"></textarea>
                                        </div>

                                    </div>

                                    <button type="submit" name="tile3" class="btn btn-primary btn-icon-split mb-3">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-flag"></i>
                                        </span>
                                        <span class="text">Save</span>
                                    </button>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Post ID</th>
                                                <th scope="col"><a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#newrecommendation">
                                                        <i class="fas fa-plus"></i>
                                                        New
                                                    </a>

                                                </th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $query = "SELECT * FROM recommendation ";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {


                                                echo '<tr>';
                                                echo '<th id="order-' . $row['recommendation_id'] . '" scope="row">' . $row['recommendation_id'] . '</th>';
                                                echo '<td id="name-' . $row['recommendation_id'] . '">' . urldecode($row['recommendation_name']) . '</td>';
                                                echo '<td id="category-' . $row['recommendation_id'] . '">' . $row['recommendation_category'] . '</td>';
                                                echo '<td id="postid-' . $row['recommendation_id'] . '">' . $row['recommendation_postid'] . '</td>';

                                                echo '<td>
                                                    <a href="#" class="" onclick="editmodal(' . $row['recommendation_id'] . ',\'' . $row['recommendation_name'] . '\');" id="modaledit"><i class="fas fa-pen"></i></a>

                                                    </td>';

                                                echo '</tr>';

                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- hero/ -->
                    <div class="card shadow mb-4" id="tile4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Blog <button class="btn btn-link"
                                    data-toggle="collapse" data-target="#table6" aria-expanded="true"
                                    aria-controls="table6" id="table6a">
                                    <i class="fas fa-chevron-down"></i>
                                </button></h6>
                        </div>
                        <div class="collapse show" id="table6">

                            <div class="card-body" id="blog">
                                <form action="?edit#tile4" method="post" enctype="multipart/form-data">

                                    <p>Title</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile4_title ?>" name="tile4_title">
                                        </div>

                                    </div>
                                    <p>Subtitle</p>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <textarea type="text" class="form-control form-control-user"
                                                placeholder="<?php echo $tile4_subtitle ?>" placeholder="tile4_subtitle"
                                                name="tile4_subtitle"></textarea>
                                        </div>

                                    </div>

                                    <button type="submit" name="tile4" class="btn btn-primary btn-icon-split mb-3">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-flag"></i>
                                        </span>
                                        <span class="text">Save</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>


        <!-- End of Content Wrapper -->



    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>



    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a>
                </div>
            </div>
        </div>
    </div>



    <!-- Addnew Modal-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Banner</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="edit-index.php" method="post" enctype="multipart/form-data">

                    <div class="modal-body" id="tagdiv2">

                        Select image banner to upload:
                        <!-- <input type="file" name="fileToUpload"  id="fileToUpload"> -->
                        <!-- <input type="submit" value="Upload Image" name="upload_banner"> -->

                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <!-- <label class="custom-file-label" for="fileToUpload">Choose file</label> -->
                    </div>
                    <div class="modal-footer">
                        <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                        <button class="btn btn-primary" type="submit" value="Upload Image"
                            name="upload_banner">Create</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal-->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Banner</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="?edit" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="exampleFormControlTextarea8" name="id" hidden></input>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">File name Desktop</label>
                            <input class="form-control" id="exampleFormControlTextarea3" name="filename"
                                readonly></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">File name Mobile</label><br>
                            <input class="form-control" id="exampleFormControlTextarea99" name="filename2"
                                readonly></input>
                            <input type="file" name="fileToUpload2" id="fileToUpload2">

                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Name</label>
                            <input class="form-control" id="exampleFormControlTextarea1" rows="3" name="name"></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea10" id="exampleFormControlTextarea40"
                                class="form-label">URL</label>
                            <input class="form-control" id="exampleFormControlTextarea10" rows="3" name="url"></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Order</label>
                            <input class="form-control" id="exampleFormControlTextarea2" rows="3" name="order"></input>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                        <button type="submit" class="btn btn-danger " name="deletebanner">Delete</button>
                        <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="editbanner"></input> -->
                        <button class="btn btn-primary" type="submit" value="Upload Image" name="editbanner">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add New Recommendation Modal-->
    <div class="modal fade" id="newrecommendation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <!-- <form action="edit-index.php" method="post" enctype="multipart/form-data"> -->

                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Post ID</th>
                                    <th scope="col">

                                    </th>

                                </tr>
                            </thead>

                            <tbody id="recommendationtable">

                            </tbody>
                        </table>
                    </div>

                    <!-- <input class="form-control" id="exampleFormControlTextarea8" rows="3" name="id" hidden></input>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">File name</label>
                            <input class="form-control" id="exampleFormControlTextarea3" rows="3"
                                name="filename"></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Name</label>
                            <input class="form-control" id="exampleFormControlTextarea1" rows="3" name="name"></input>
                        </div>


                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Order</label>
                            <input class="form-control" id="exampleFormControlTextarea2" rows="3" name="order"></input>
                        </div> -->

                </div>
                <div class="modal-footer">
                    <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                    <!-- <button type="button" class="btn btn-danger " disabled>Delete</button> -->
                    <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="editbanner"></input> -->
                    <!-- <button class="btn btn-primary" type="submit" value="Upload Image" name="editbanner">Save
                            Changes</button> -->

                </div>
                <!-- </form> -->
            </div>
        </div>
    </div>



    <!-- edit recommendation modal  -->
    <div class="modal fade" id="editrecommend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Recommendation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="edit-index.php?editrecommend" method="post" enctype="multipart/form-data">

                    <div class="modal-body">

                        <input class="form-control" id="hiddenid" name="hiddenid" hidden></input>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Name</label>
                            <input class="form-control" id="recommendname" rows="3" name="recommendname"
                                readonly></input>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" id="exampleFormControlTextarea4"
                                class="form-label">Category</label>
                            <input class="form-control" id="recommendcategory" rows="3"
                                name="recommendcategory"></input>
                        </div>


                        <!-- <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Order</label>
                            <input class="form-control" id="exampleFormControlTextarea2" rows="3" name="order"></input>
                        </div> -->

                    </div>
                    <div class="modal-footer">
                        <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?logout=1">Logout</a> -->
                        <button class="btn btn-danger" type="submit" name="deleterecommend">Delete</button>
                        <!-- <input type="submit" class="btn btn-primary" value="Save Changes" name="editbanner"></input> -->
                        <button class="btn btn-primary" type="submit" name="editrecommend">Save
                            Changes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Toast -->
    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="liveToast" class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
            <div class="toast-header">
                <img src="../assets/img/favicon-32x32.png" class="rounded mr-2" alt="...">
                <strong class="mr-auto">Bluedale</strong>
                <!-- <small>11 mins ago</small> -->
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body" id="toast-body">
                Test
            </div>
        </div>
    </div>
    <!-- End Toast -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/editindex.js"></script>
    <script>document.getElementById("editnav").classList.add('active');</script>
    <!-- <script src="js/banner.js"></script> -->
    <?php include('errors2.php'); ?>




</body>

</html>