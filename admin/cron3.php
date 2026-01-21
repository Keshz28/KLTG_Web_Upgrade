<?php

$ch = curl_init('https://kltheguide.com.my/admin/functions.php');
curl_setopt($ch, CURLOPT_POSTFIELDS,"clearqueue=clearqueue");

// execute!
curl_exec($ch);
// echo $response;

curl_close($ch);

?>