<?php

$ch = curl_init('https://kltheguide.com.my/admin/functions.php');
curl_setopt($ch, CURLOPT_POSTFIELDS,"testqueue=testqueue");

// execute!
$response = curl_exec($ch);

curl_close($ch);

?>