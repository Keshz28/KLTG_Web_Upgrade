<?php
/* ============================================================================
 *                                cron3.php
 *
 *   Cron entry point — drains the email/push queue via functions.php testqueue.
 *   (Variant of cron2.)
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

$ch = curl_init('https://kltheguide.com.my/admin/functions.php');
curl_setopt($ch, CURLOPT_POSTFIELDS,"clearqueue=clearqueue");

// execute!
curl_exec($ch);
// echo $response;

curl_close($ch);

?>