<?php
session_start();
include('database.php');
include('email.php');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: resetpassword.php?error=0");
        die();
    }
    unset($_SESSION['csrf_token']);
    $token = $_POST["token"];
	if (!isset($_POST['reset_password'], $_POST['confirm_resetpassword'])){
		header("Location: resetpassword.php?token= " . $token . "error=0"); //Server error: we couldnt establish connection
		die();
	}

	if (empty($_POST['reset_password']) || empty($_POST['confirm_resetpassword'])){
		header("Location: resetpassword.php?token= " . $token . "error=1"); //Server error: we couldnt establish connection
		die();
	}

	if (strlen($token) != 64 || !ctype_xdigit($token)){
	    header("Location: resetpassword.php?token= " . $token . "error=" . strlen($token) . "&dig=" . ctype_xdigit($token)); //mismatched token
        die();
	}
	if (!isset($_SESSION['reset_token']) || $_SESSION['reset_token'] !== $token){
	    header("Location: resetpassword.php?token= " . $token . "error=3"); //Mismatched token
        die();
	}

	$escaped_password =			htmlspecialchars($_POST['reset_password']);
	$escaped_password_confirm = htmlspecialchars($_POST['confirm_resetpassword']);

	if ($escaped_password != $escaped_password_confirm){
		header("Location: resetpassword.php?error=8"); //passwords do not match
		die();
	}


	//Validate that a user with that username does not exist
	$STMT = $connection->prepare('SELECT id FROM users WHERE reset_token= ?');
	if ($STMT){
		$STMT->bind_param('s', $token);
		$STMT->execute();
		$STMT->store_result();
		if ($STMT->num_rows <= 0){
			header("Location: resetpassword.php?error=27"); //Token is not in database, not valid
			$STMT->close();
			exit();
		}
		else{
		    $STMT->bind_result($user_id);
            $STMT->fetch();
		}
		$STMT->close();
	}
	else{
		header("Location: sign-up.php?error=4"); //Server error: we couldnt establish connection
		exit();
	}

	$STMT = $connection->prepare('UPDATE users SET password_hash = ?, reset_token = ?, reset_token_time = ? WHERE id = ?');
	if ($STMT) {
		$password_hashed = password_hash($escaped_password, PASSWORD_DEFAULT);
		$nullified = NULL;
		$STMT->bind_param('ssis', $password_hashed, $nullified, $nullified, $user_id);
		$STMT->execute();
		unset($_SESSION["reset_token"]);
		header("Location: sign-in.php?error=31"); //Password has been reset to new figure
		$STMT->close();
		exit();
	} else {
		header("Location: sign-up.php?error=5"); //Server error: we couldnt establish connection
		exit();
	}


}



?>