<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
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
function masked_password_check($plain, $hash){
    return password_verify($plain, $hash);
}

session_start();
include('database.php');
include('attempt_handler.php');

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
	if (!isset($_POST['username']) || !isset($_POST['password'])){
		header("Location: sign-in.php?error=1");
		die();
	}
	if (!isset($_POST['capcha-input'])){
	    header("Location: sign-in.php?error=34");
        die();
	}
	 $escaped_capcha =   $_POST['capcha-input'];
	 if (!isset($_SESSION['capcha_key'])){
	    die();
	 }
	 if ($escaped_capcha !== $_SESSION['capcha_key']){
         header("Location: sign-in.php?error=34"); //Invalid capcha
         die();
     }
	 unset($_SESSION['capcha_key']);


	$escaped_user     =  trim($_POST['username']);
	$escaped_password = trim($_POST['password']);
	if (empty($escaped_user) || empty($escaped_password)) {
        header("Location: sign-in.php?error=1");
        die();
    }

    $userIP = get_client_ip();
    if (is_account_locked($userIP)){
        header("Location: sign-in.php?error=35"); //IP is currently blocked
        die();
    }


	$STMT = $connection->prepare('SELECT id, password_hash, security_question, verified, 2FA FROM users WHERE username = ? ');
	if ($STMT){
		$STMT->bind_param('s', $escaped_user);
		$STMT->execute();
		$STMT->store_result();
		if ($STMT->num_rows > 0){
			$STMT->bind_result($id, $password, $securityQ, $is_verified, $fasecret);
			$STMT->fetch();

			if (masked_password_check($escaped_password, $password)){

				session_regenerate_id();
				if (!$is_verified)  {
				    header("Location: sign-in.php?error=22");
				    die();
				}
				$_SESSION['authentication_stage'] = TRUE;
				#$_SESSION['loggedin'] = TRUE;
				$_SESSION['username'] = $escaped_user;
				$_SESSION['password'] = $escaped_password;
				$_SESSION["2FA"] = $fasecret;
				$_SESSION['id'] = $id;
				$_SESSION['securityquestion'] = securityIDToQuestion($securityQ);
				if ($fasecret == NULL){
				    reset_attempts($userIP);
				    header("Location: setup2fa.php");
				    die();
				}
				else{
                    reset_attempts($userIP);
				    header("Location: 2factorauthentication.php");
				     die();
				}
			}
			else{ //Wrong password
                handle_failed_login($userIP);
				header("Location: sign-in.php?error=36");
				 die();
			}

		}
		else{
			handle_failed_login($userIP);
            header("Location: sign-in.php?error=36");
             die();
		}
		$STMT->close();

	}

}
?>