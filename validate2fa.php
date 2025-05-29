<?php
include("database.php");
include("encryption_manager.php");
require 'vendor/autoload.php';
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com https://ajax.googleapis.com;style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com;font-src 'self' https://fonts.gstatic.com;img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self'; object-src 'none'; base-uri 'self';");
use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        exit();
    }
    if (isset($_POST['step'])) {
        $step = (int)$_POST['step'];

        switch ($step) {
            case 1:
                 $_SESSION['2fa_step'] = 1;
                 $STMT = $connection->prepare("UPDATE users SET 2FA=? WHERE username = ?");
                 $encrypted_2FA = encrypt_data($_SESSION['2FA']);

                 $STMT->bind_param("ss", $encrypted_2FA , $_SESSION["username"]);
                 $STMT->execute();
                http_response_code(200);
                break;
            case 2:
                $authCode = trim($_POST['auth_code'] ?? '');
                $cleaned = preg_replace('/\D/', '', $authCode);
                $qrCodeProvider = new EndroidQrCodeProvider();
                $FASession= new TwoFactorAuth($qrCodeProvider);
                $secret = $_SESSION["2FA"];
                $secretCode = $FASession->getCode($secret);
                if ($FASession->verifyCode($secret, $cleaned)){
                    $_SESSION['2fa_step'] = 2;
                    http_response_code(200);
                }
                else{
                    http_response_code(400);
                    exit('Invalid authentication code. YAH');
                }
                break;
            case 3:
                http_response_code(200);
                header("Location: sign-in.php?completed=true");
                die();
                break;
            default:
                http_response_code(400);
                exit('Invalid step. whoa');
        }
        echo 'Step validated';
        exit;
    }
}
else{

    die();
}



?>