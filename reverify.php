<?php
include('email.php');
include('database.php');
include('encryption_manager.php');
session_start();
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: sign-up.php?error=csrf");
        die();
    }
    unset($_SESSION['csrf_token']);

	if (!isset($_POST['email'], $_POST['password'])){
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection
		#exit("Failed to establish valid variables");
	}

	if (empty($_POST['email']) || empty($_POST['password'])){
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection
		#exit("Failed to establish valid variables");
	}

	$escaped_password =			$_POST['password'];
	$escaped_email =			$_POST['email'];

	//Validate that a user with that username does not exist


	$STMT = $connection->prepare('SELECT id, password_hash, verified, email FROM users');
    if ($STMT) {
        $STMT->execute();
        $STMT->bind_result($id, $ps_hash,$is_verified,  $encrypted_email);
        while ($STMT->fetch()) {

            $decrypted_email = decrypt_data($encrypted_email);
            if ($escaped_email === $decrypted_email) {
                if (password_verify($escaped_password, $ps_hash)){
                    if ($is_verified){
                        header("Location: sign-in.php?error=25");
                        die();
                    }
                    $STMT->close();
                    $STMT = $connection->prepare('UPDATE users SET verification_token=?, verification_token_time=? WHERE id=?');
                    if ($STMT) {
                        $timenow = date('Y-m-d H:i:s');
                        $email_verification_token = bin2hex(random_bytes(32));
                        $STMT->bind_param('sss', $email_verification_token, $timenow, $id);
                        $STMT->execute();
                        sendVerificationEmailToUser($escaped_email, $email_verification_token);
                        header("Location: sign-in.php?error=21"); //Succesfully registered, redirected to sign in page
                        die();
                    }
                    $STMT->close();
                    exit();
                }
                else{
                    header("Location: sign-up.php?error=8");
                    exit();
                }

            }
        }
        $STMT->close();
    } else {
        header("Location: sign-up.php?error=0");
        exit();
    }
}
else{
die();
}



?>