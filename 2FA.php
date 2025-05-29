<?php
require 'vendor/autoload.php';
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self';script-src 'self' 'nonce-$nonce' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com https://ajax.googleapis.com;style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com https://www.w3schools.com https://cdnjs.cloudflare.com;font-src 'self' https://fonts.gstatic.com   https://cdnjs.cloudflare.com;img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self'; object-src 'none'; base-uri 'self';");


use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;
function password_check($hashed, $plaintext){
	return ($hashed === $plaintext);
}
function securityIDToQuestion($id){
	switch ($id) {
    case 1:
        return "Name your first childhood pet";
        break;
    case 2:
        return "What is your favourite sport?";
        break;
    case 3:
        return "What street did you grow-up on?";
        break;
	case 4:
		return "What is your favourite movie?";
		break;
}

}
session_start();
include("encryption_manager.php");
include('database.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: sign-up.php?error=0");
        die();
    }
    unset($_SESSION['csrf_token']);

    if (!$_SESSION['authentication_stage'] || !$_SESSION['securityquestion']){
        //We for some reason did not have the authentication stage flag set
        header("Location: sign-in.php?error=9");
    }

    if (!isset($_POST['security_answer']) || !isset($_POST['2FACode'])){
        header("Location: 2factorauthentication.php?error=1");
    }


    $escaped_session_user    =  htmlspecialchars($_SESSION['username']);
    $escaped_session_password = htmlspecialchars($_SESSION['password']);
    $escaped_security_answer =  htmlspecialchars($_POST["security_answer"]);
    $escaped_2FA =				preg_replace('/\D/', '', htmlspecialchars($_POST["2FACode"]));

    $STMT = $connection->prepare('SELECT id, password_hash, security_answer, role, verified, 2FA FROM users WHERE username = ?');
    if ($STMT){
        $STMT->bind_param('s', $escaped_session_user);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($id, $password, $securityA, $role, $is_verified, $FASecret);
            $STMT->fetch();

            if (password_verify($escaped_session_password, $password)){

                if (password_verify($escaped_security_answer, $securityA)){


                    if (!$is_verified)  {
                        header("Location: sign-in.php?error=22");
                    }
                    $qrCodeProvider = new EndroidQrCodeProvider();
                    $FASession= new TwoFactorAuth($qrCodeProvider);
                    $decrypted_2FA = decrypt_data($FASecret);
                    if ($FASession->verifyCode($decrypted_2FA, $escaped_2FA)){
                        session_regenerate_id();
                        $_SESSION['authentication_stage'] = FALSE;
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['username'] = $escaped_user;
                        $_SESSION['id'] = $id;
                        $_SESSION['role'] = $role;
                        $_SESSION['securityquestion'] = "";
                        $_SESSION['password'] = "";
                        header("Location: index.php");
                    }
                    else{
                        header("Location: 2factorauthentication.php?error=10");
                        die();
                    }
                } else{
                    header("Location: 2factorauthentication.php?error=10");
                    die();
                }
            }
            else{
                header("Location: sign-in.php?error=9");
                die();
            }
        }
        else{
            header("Location: sign-in.php?error=8");
            die();
        }
        $STMT->close();
    }
}
else{
    die();
}


?>