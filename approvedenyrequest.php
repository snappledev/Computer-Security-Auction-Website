
<?php

session_start();
include('database.php');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	    header("Location: sign-up.php?error=0");
        die();
    }
    unset($_SESSION['csrf_token']);

	if (!isset($_POST['action'], $_POST["userid"],$_POST["action"])){
		header("Location: sign-up.php?error=0"); //Server error: we couldnt establish connection
		die();
		#exit("Failed to establish valid variables");
	}
    $id = htmlspecialchars($_POST['id']); 
    $userid = htmlspecialchars($_POST['userid']);
    $action = htmlspecialchars($_POST['action']);
	if (!$_SESSION['loggedin']){
		header("Location: evaluationrequests.php?error=16"); //Insufficient privilleges
		die();
	}
	if ($_SESSION["role"] === 0 ){
		header("Location: evaluationrequests.php?error=19"); //Insufficient privilleges
		die();
	}
	$STMT = $connection->prepare('UPDATE auctions SET status=? WHERE id = ? AND user_id = ?');
	if ($STMT){
		$value = ($action === "approve" ? 2 :  ($action === "deny" ? 1 : 0) );
		$STMT->bind_param('iii', $value, $id, $userid);
		if ($STMT->execute()) {
			if ($STMT->affected_rows > 0) {
				header("Location: evaluationrequests.php?error=17"); // Success
				die();
			} 
			else {
				header("Location: evaluationrequests.php?error=9"); // No change
				die();
			} 
		}
		else {
			header("Location: evaluationrequests.php?error=9");
			die();
		}
		$STMT->close();
	}
	else{
		header("Location: evaluationrequests.php?error=9"); //Server error: we couldnt establish connection 
		exit();
	}

}



?>