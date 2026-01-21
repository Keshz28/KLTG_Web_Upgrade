<?php include('functions.php');


$datenow = date('H');
echo $datenow;


$query4 = "SELECT * FROM mailqueue WHERE sendstatus=0 LIMIT 30 ";
$result4 = mysqli_query($db, $query4);
$counter = 0;
// while ($row4 = mysqli_fetch_assoc($result4)) {
//     if ($counter == 0) {
//         // sendmail('annie@bluedale.com.my', urldecode($row4['sendbody']), $row4['sendtitle']);

//     }
//     $id = $row4['id'];
//     $date = date('Y-m-d H:i:s');
//     // echo "send";
//     // $response = sendmail($row4['sendto'], urldecode($row4['sendbody']), $row4['sendtitle']);

//     $query = "UPDATE mailqueue SET sendstatus=$response , send_time='$date'  WHERE  id=$id";
//     $update = mysqli_query($db, $query);

//     $counter++;
// }
?>