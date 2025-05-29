<?php
session_start();
include('database.php');
include('email.php');
include('encryption_manager.php');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['csrf_token'])){
        header("Location: sign-up.php?error=0");
        die();
    }
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: sign-up.php?error=0");
        die();
    }
    unset($_SESSION['csrf_token']);

    if (!isset($_POST['capcha-input'])){
        header("Location: sign-in.php?error=34");
        die();
    }
     $escaped_capcha =   $_POST['capcha-input'];

     if (!isset($_SESSION['capcha_key'])){
        header("Location: sign-in.php?error=34");
        die();
     }
     if ($escaped_capcha !== $_SESSION['capcha_key']){
         header("Location: sign-up.php?error=34"); //Invalid capcha
         die();
     }
     unset($_SESSION['capcha_key']);

	if (!isset($_POST['signup_username'], $_POST['signup_password'], $_POST['signup_email'], $_POST['signup_phone'])){
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection 
		die();
	}

	if (empty($_POST['signup_username']) || empty($_POST['signup_password']) || empty($_POST['signup_email']) || empty($_POST['signup_phone'])){
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection 
		die();
	}

	$escaped_username     =		trim($_POST['signup_username']);
	$escaped_password =			trim($_POST['signup_password']);
	$escaped_password_confirm = trim($_POST['confirm_password']);
	$escaped_email =			htmlspecialchars($_POST['signup_email']);
	$escaped_phone =			htmlspecialchars($_POST['signup_phone']);

	$escaped_security_question = $_POST['security_question_question'];
	$escaped_security_answer = $_POST['security_question_answer'];


    if (empty($escaped_username) || empty($escaped_password)) {
        header("Location: sign-in.php?error=DJ");
        die();
    }

	if ($escaped_password != $escaped_password_confirm){
		header("Location: sign-up.php?error=8");
		die();
	}
    //Make sure phone number is only digits
	if (!ctype_digit($escaped_phone) && (strlen($escaped_phone) != 10)) {
	    header("Location: sign-up.php?error=8");
        die();
	}

	//Validate that a user with that username does not exist
	$STMT = $connection->prepare('SELECT id FROM users WHERE username = ? ');
	if ($STMT){
		$STMT->bind_param('s', $escaped_username);
		$STMT->execute();
		$STMT->store_result();
		if ($STMT->num_rows > 0){
			header("Location: sign-up.php?error=5"); //Username already in use
			$STMT->close();
			exit();
		}
		$STMT->close();
	
	}
	else{
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection 
		exit();
	}
    $STMT = $connection->prepare('SELECT email FROM users');
    if ($STMT) {
        $STMT->execute();
        $STMT->bind_result($encrypted_email);
        while ($STMT->fetch()) {
            $decrypted_email = decrypt_data($encrypted_email);
            if ($escaped_email === $decrypted_email) {
                header("Location: sign-up.php?error=6");
                $STMT->close();
                exit();
            }
        }
        $STMT->close();
    } else {
        header("Location: sign-up.php?error=0");
        exit();
    }


    $STMT = $connection->prepare('SELECT phone FROM users');
    if ($STMT) {
        $STMT->execute();
        $STMT->bind_result($encrypted_phone);
        while ($STMT->fetch()) {
            $decrypted_phone = decrypt_data($encrypted_phone);
            if ($escaped_phone === $decrypted_phone) {
                header("Location: sign-up.php?error=26");
                $STMT->close();
                exit();
            }
        }
        $STMT->close();
    } else {
        header("Location: sign-up.php?error=0");
        exit();
    }
	$STMT = $connection->prepare('INSERT INTO users (username, password_hash, email, phone, security_question, security_answer, verification_token, verification_token_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
	if ($STMT) {
		$password_hashed = password_hash($escaped_password, PASSWORD_DEFAULT);
		$security_hashed = password_hash($escaped_security_answer, PASSWORD_DEFAULT);
		$email_verification_token = bin2hex(random_bytes(32));
		$email_encrypted = encrypt_data($escaped_email);
		$phone_encrypted = encrypt_data($escaped_phone);
        $timenow =  date('Y-m-d H:i:s');
		$STMT->bind_param('ssssssss', $escaped_username, $password_hashed, $email_encrypted, $phone_encrypted, $escaped_security_question, $security_hashed, $email_verification_token, $timenow);
		$STMT->execute();
		sendVerificationEmailToUser($escaped_email, $email_verification_token);
		header("Location: sign-in.php?error=21"); //Succesfully registered, redirected to sign in page
		$STMT->close();
		exit();
	} else {
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection 
		exit();
	}


}



?>