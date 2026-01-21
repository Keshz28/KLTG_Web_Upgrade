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
$totalvalue = 0;

?>

<?php

if (!isset($_GET["startdate"]) && !isset($_GET["enddate"]) && !isset($_GET["banner"])) {
    $t = date("Y-m-d", strtotime("-7 day"));

    $query = "SELECT banner_name , COUNT(*), DATE_FORMAT(date, '%Y-%m-%d') as dateent2 FROM `banner_reach` WHERE DATE_FORMAT(date, '%Y-%m-%d') > '2023-10-18'  GROUP BY banner_name, DATE_FORMAT(date, '%Y-%m-%d') ORDER BY `banner_reach`.`date` DESC;";
    $result = mysqli_query($db, $query);
    $diff = strtotime("now") - strtotime($t);
    $numdays = abs(round($diff / 86400));
    // echo $numdays;
    for ($x = 0; $x <= $numdays; $x++) {
        $stringday = "+" . $x . "day";
        $dates2[] = date('Y-m-d', strtotime($t . $stringday));

    }

    // $dates2 = array_reverse($dates2, TRUE);

    while ($row = mysqli_fetch_assoc($result)) {

        // $myArray[($row['banner_name'])][$row['dateent2']] = intval($row['COUNT(*)']);

        // echo $row['banner_name'];
$totalvalue += $row['COUNT(*)'];
        foreach ($dates2 as $date) {
            // echo "date 1 ". $date . gettype($date);
            // echo "date 2". $row['dateent2'] . gettype($row['dateent2']);
            $comparedate1 = date('Y-m-d', strtotime($date));
            $comparedate2 = date('Y-m-d', strtotime($row['dateent2']));
            // echo $comparedate1;
            // echo $comparedate2;


            // $myArray[$row['banner_name']][$date] = 0;

            if ($comparedate1 == $comparedate2) {
                // echo "same";


                //     echo $row['banner_name'];
                $myArray[$row['banner_name']][$date] = intval($row['COUNT(*)']);
                // echo $myArray[$row['banner_name']][$date];

            } else {
                // echo "no";
                // echo "nsame2";

                // echo $row['banner_name'];
                // $myArray[$row['banner_name']][$date] = 0;
                // echo $myArray[$row['banner_name']][$date];

                // $myArray[($row['banner_name'])][$row['dateent2']] = intval($row['COUNT(*)']);

            }
            if (!isset($myArray[$row['banner_name']][$date])) {
                $myArray[$row['banner_name']][$date] = 0;

            }

        }

    }

    // var_dump($myArray);


} else {
    $startdate = $_GET["startdate"];
    $enddate = $_GET["enddate"];
    $bannerindv = $_GET["banner"];
    if($bannerindv == "all"){
        $bannerq = "";
    }else{
        $bannerq = "AND  banner_name='$bannerindv'";

    }

    // echo $startdate;
    // echo $enddate;
    $diff = strtotime($enddate) - strtotime($startdate);
    $numdays = abs(round($diff / 86400));
    // echo $numdays;
    for ($x = 0; $x <= $numdays; $x++) {
        $stringday = "+" . $x . "day";
        $dates2[] = date('Y-m-d', strtotime($startdate . $stringday));
    }
    $query = "SELECT banner_name , COUNT(*), DATE_FORMAT(date, '%Y-%m-%d') as dateent2 FROM `banner_reach` WHERE DATE_FORMAT(date, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(date, '%Y-%m-%d') <= '$enddate' " . $bannerq  ."  GROUP BY banner_name, DATE_FORMAT(date, '%Y-%m-%d') ORDER BY `banner_reach`.`date` DESC;";
    // echo $query;
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $totalvalue += $row['COUNT(*)'];

        foreach ($dates2 as $date) {
            // echo "date 1 ". $date . gettype($date);
            // echo "date 2". $row['dateent2'] . gettype($row['dateent2']);
            $comparedate1 = date('Y-m-d', strtotime($date));
            $comparedate2 = date('Y-m-d', strtotime($row['dateent2']));
            // echo $comparedate1;
            // echo $comparedate2;


            // $myArray[$row['banner_name']][$date] = 0;

            if ($comparedate1 == $comparedate2) {
                // echo "same";


                //     echo $row['banner_name'];
                $myArray[$row['banner_name']][$date] = intval($row['COUNT(*)']);
                // echo $myArray[$row['banner_name']][$date];

            } else {
                // echo "no";
                // echo "nsame2";

                // echo $row['banner_name'];
                // $myArray[$row['banner_name']][$date] = 0;
                // echo $myArray[$row['banner_name']][$date];

                // $myArray[($row['banner_name'])][$row['dateent2']] = intval($row['COUNT(*)']);

            }
            if (!isset($myArray[$row['banner_name']][$date])) {
                $myArray[$row['banner_name']][$date] = 0;

            }

        }

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

    <title>KLTG ADMIN - Banner Reach</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Banner Reach</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>



                    <!-- Content Row -->




                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Banner Reach View</h6>
                                    <form action="#" method="get">
                                        <label for="banner">Banner:</label>
                                        <select name="banner" id="banner">
                                            <option value="all" selected>All</option>
                                            <?php

                                            $query4 = "SELECT banner_name FROM banner ";
                                            $result4 = mysqli_query($db, $query4);
                                            while ($row4 = mysqli_fetch_assoc($result4)) 
                                            { ?>
                                                <option value="<?php echo $row4['banner_name']?>"><?php echo $row4['banner_name']?></option>


                                            <?php 
                                            } ?>
                               
                                        </select>
                                        <label for="birthday">Start:</label>
                                        <input type="date" id="startdate" name="startdate">
                                        <label for="birthday">End:</label>
                                        <input type="date" id="enddate" name="enddate">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </form>
                                    <!-- <div class="dropdown no-arrow">
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
                                    </div> -->
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                                Total
                                <?php echo $totalvalue ?>
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
        <script>document.getElementById("blognav").classList.add('active');</script>
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
                    toFixedFix = function (n, prec) {
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

                        <?php echo json_encode($dates2) ?>
                    ,
                    datasets: [


                        <?php
                        foreach ($myArray as $key2 => $values) {

                            // foreach ($values as $key => $value) {
                            //     print "    $key => $value\n";
                            // }
                            $r = rand(1, 255);
                            $g = rand(1, 255);
                            $b = rand(1, 255);

                            ?>
                                                                 {
                                label: "<?php echo $key2 ?>",
                                lineTension: 0.1,
                                backgroundColor: "rgba(8, 15, 223, 0.00)",
                                borderColor: "rgba(<?php echo $r ?>, <?php echo $g ?>, <?php echo $b ?>, 1)",
                                pointRadius: 3,
                                pointBackgroundColor: "rgba(<?php echo $r ?>, <?php echo $g ?>, <?php echo $b ?>, 1)",
                                pointBorderColor: "rgba(<?php echo $r ?>, <?php echo $g ?>, <?php echo $b ?>, 1)",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(<?php echo $r ?>, <?php echo $g ?>, <?php echo $b ?>, 1)",
                                pointHoverBorderColor: "rgba(<?php echo $r ?>, <?php echo $g ?>, <?php echo $b ?>, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                                data:

                                    <?php
                                    $arrayvalue = [];
                                    foreach ($values as $key => $value) {
                                        $arrayvalue[] = $value;
                                    }
                                    echo json_encode($arrayvalue);

                                    ?>


                                ,
                            },


                        <?php } ?>
                    ],
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
                                callback: function (value, index, values) {
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
                        display: true
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
                            label: function (tooltipItem, chart) {
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