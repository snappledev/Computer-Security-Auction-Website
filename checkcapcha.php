<?php
session_start();
include("attempt_handler.php");
$userIP = get_client_ip();
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['csrf_token_capcha']) && $_POST['csrf_token_capcha'] === $_SESSION['csrf_token_capcha']) {
        if (!isset($_POST["capcha_code"])){
             echo json_encode(['success' => false]);
         }
         $postCode = $_POST["capcha_code"];
         if ($postCode === $_SESSION['capcha_key']) {
             echo json_encode(['success' => true]);
         }
         else{
            handle_failed_login($userIP);
             echo json_encode(['success' => false, 'attempts' => get_attempts_left($userIP)]);
         }
    }
    else{
        echo json_encode(['success' => false]);
    }
    exit();
}
?>