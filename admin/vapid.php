<?php
include 'vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

use Minishlink\WebPush\VAPID;
 
$keyset = VAPID::createVapidKeys();
 
// public key - this needs to be accessible via an API endpoint
echo "PUB: ";
echo $keyset["publicKey"];
echo "<br>";

// private key - never expose this!
echo "SEC: ";
echo $keyset["privateKey"];
?>