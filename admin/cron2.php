<?php
/* ============================================================================
 *                                cron2.php
 *
 *   Cron entry point — cURLs functions.php with testqueue to drain the
 *   email/push queue. (See also cron3 / cron4.)
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */

$ch = curl_init('https://kltheguide.com.my/admin/functions.php');
curl_setopt($ch, CURLOPT_POSTFIELDS,"testqueue=testqueue");

// execute!
$response = curl_exec($ch);

curl_close($ch);

?>