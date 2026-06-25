<?php
/* ============================================================================
 *                                vapid.php
 *
 *   ONE-TIME utility — generates a VAPID keypair for web push and PRINTS them
 *   (including the PRIVATE key) to the browser.
 *   SECURITY: must NOT be reachable on production. Remove after generating keys.
 *
 *   MEMO for the next dev — full file map is in PROJECT_GUIDE.md
 * ============================================================================ */
include 'functions.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

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