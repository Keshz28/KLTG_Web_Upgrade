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




// $query = "SELECT  url, sum(views) ,dateent FROM `pageview` GROUP BY url ";
// $result = mysqli_query($db, $query);
// while ($row = mysqli_fetch_assoc($result)) {
//     // echo $row['url'];
//     $url = $row['url'];
//     $views = $row['sum(views)'];
//     $views2 = 0;
//     // echo '<br>';

//     $query2 = "INSERT INTO pageview2 (url, views, views2) 
//     VALUES('$url', '$views', '$views2') ON DUPLICATE KEY UPDATE views2=views2 , views=$views";
//     mysqli_query($db, $query2);

//     // echo $query2;
// // echo '<br>';


// }

// $query = "INSERT INTO pageview2 SELECT id, url, sum(views), 0 ,dateent from pageview group by url  ON DUPLICATE KEY UPDATE   views2 = views2,views=sum(views)    ;";
// $update = mysqli_query($db, $query);
// echo $query;
// if ($update) {
//     // echo "Record updated successfully";


// } else {
//     array_push($errors2, "Error updating record: " . mysqli_error($db));

// }

$totalpageviews = 0;
?>

<?php

if (!isset($_GET["startdate"]) && !isset($_GET["enddate"])) {
    $totalvalue = 0;
    $query = 'SELECT url , sum(views), DATE_FORMAT(dateent, "%Y-%m-%d") as dateent2 FROM `pageview` WHERE url LIKE "%blog-details.php?postid=%"  GROUP BY dateent2  
    ORDER BY `dateent2`  DESC LIMIT 7';
    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_assoc($result)) { ?>

    <?php
        $array1[] = $row['dateent2'];
        $array2[] = intval($row['sum(views)']);
        $totalvalue += intval($row['sum(views)']);
    }
} else {
    $startdate = $_GET["startdate"];
    $enddate = $_GET["enddate"];
    $query = "SELECT url , sum(views), DATE_FORMAT(dateent, '%Y-%m-%d') as dateent2 FROM `pageview` WHERE url LIKE '%blog-details.php?postid=%' AND DATE_FORMAT(dateent, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(dateent, '%Y-%m-%d') <= '$enddate'  GROUP BY dateent2  
    ORDER BY `dateent2`  DESC";
    $result = mysqli_query($db, $query);
    while ($row = mysqli_fetch_assoc($result)) { ?>

<?php
        $array1[] = $row['dateent2'];
        $array2[] = intval($row['sum(views)']);
        $totalvalue += intval($row['sum(views)']);
    }
}

// echo (json_encode($array1));
// echo (json_encode($array2));
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KLTG ADMIN - Blog Views</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include('nav.php'); ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php include('topnav.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Blog Views</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>



                    <!-- Content Row -->

                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-xl-6 col-lg-6 mb-4">


                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Blog Views</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $query2 = "SELECT sum(blog_view)+sum(blog_view2) FROM `blog`";
                                    $result = mysqli_query($db, $query2);
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        $totalviewsoverall = $row['sum(blog_view)+sum(blog_view2)'];
                                    }
                                    ?>

                                    <?php
                                    $query = "SELECT *, blog_view+blog_view2 FROM `blog` ORDER BY blog_view+blog_view2 DESC LIMIT 5";
                                    $result = mysqli_query($db, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<h4 class="small font-weight-bold">' . urldecode($row['blog_title']) . ' <span class="float-right">' . $row['blog_view+blog_view2'] . '</span></h4>';
                                        echo '<div class="progress mb-4">';
                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: ' . $row['blog_view+blog_view2'] / $totalviewsoverall * 100 . '%" aria-valuenow="' . $row['blog_view'] . '" aria-valuemin="0" aria-valuemax="100"></div></div>';
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-6 col-lg-6 h-100 w-100">
                            <div class="card shadow mb-4 h-100">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Total Blog Views</h6>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body h-100 w-100">
                                    <div class="row h-100 w-100">
                                        <div class="col h-100">
                                            <?php

                                            $query = "SELECT *, sum(blog_view)+sum(blog_view2) FROM `blog`  ";
                                            $result = mysqli_query($db, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                echo '<p class="font-weight-bold text-center " style="font-size:10vh">
                                               ' . $row['sum(blog_view)+sum(blog_view2)'] . '
                                                </p>';
                                            }

                                            ?>
                                            <!-- <p class="font-weight-bold" style="font-size:10vh">
                                                <?php echo $totalpageviews; ?>
                                            </p> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Other Pages View</h6>
                                    <form action="#" method="get">
                                        <label for="birthday">Start:</label>
                                        <input type="date" id="startdate" name="startdate"> <label
                                            for="birthday">End:</label>
                                        <input type="date" id="enddate" name="enddate">
                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                    </form>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                                Total <?php echo $totalvalue ?>
                            </div>
                        </div>


                    </div>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Blog View
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Blog Name</th>
                                            <th scope="col">Views</th>
                                            <th scope="col">Date</th>
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        $query = 'SELECT url , sum(views), DATE_FORMAT(dateent, "%Y-%m-%d") as dateent2 FROM `pageview` WHERE url LIKE "%blog-details.php?postid=%" AND  DATE_FORMAT(dateent, "%Y-%m-%d") >= 2023-10-19 GROUP BY dateent2  
                                        ORDER BY `dateent2`  DESC';
                                        $result = mysqli_query($db, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {



                                            echo '<tr>';
                                            $pos = strpos(urldecode($row['url']), '=');
                                            $blogid = substr($row['url'], $pos + 1);
                                            $query5 = "SELECT blog_postid, blog_title  FROM blog WHERE blog_postid='$blogid'";
                                            // debug_to_console($query5);

                                            $result5 = mysqli_query($db, $query5);
                                            while ($row5 = mysqli_fetch_assoc($result5)) {
                                                $blogname = urldecode($row5['blog_title']);
                                            }

                                            echo '<td ">' . $blogname . '</td>';






                                            echo '<td ">' . $row['sum(views)'] . '</td>';
                                            echo '<td ">' . $row['dateent2'] . '</td>';
                                            // echo '<td><a href="#" class="" onclick="newmodal(' . $row['banner_id'] . ' );" id="modaledit"><i class="fas fa-pen"></i></a></td>';
                                            echo '</tr>';
                                        }

                                        ?>
                                    </tbody>
                                </table>
                                <div class=row>

                                    <div class=col-8>
                                    </div>
                                    <div class=col-4>
                                        <p class="font-weight-bold">Total Views : <?php echo $totalviewsoverall; ?>
                                        </p>

                                    </div>
                                </div>

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
                        <span>Copyright &copy; Your Website 2021</span>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


    <script src="js/pageviews.js"></script>
    <script>
        document.getElementById("blognav").classList.add('active');
    </script>
    <script>
        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {

                labels:

                    <?php echo (json_encode($array1)); ?>,
                datasets: [{
                    label: "Blog Name Views :",
                    lineTension: 0.1,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 165, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data:

                        <?php echo (json_encode($array2)); ?>


                        ,
                }, ],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>