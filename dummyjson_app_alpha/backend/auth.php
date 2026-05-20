<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /dummyjson_app_alpha/landing-page/index.php");
        exit();
    }
}

function isLoggedIn() {
    return isset ($_SESSION['user_id']);
    }
    
function fetchAPI($url) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($ch);
    
    curl_close($ch);
    
    return json_decode($response, true);
}
?>