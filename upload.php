
<?php
session_start();
include('database.php');
include('encryption_manager.php');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: sign-up.php?error=0");
        die();
    }
    unset($_SESSION['csrf_token']);
	$uploadDirectory = "auction_images/";
    $supportedFormats = ['jpg', 'jpeg', 'png', 'gif'];
    if (isset($_FILES['antique_image']) && $_FILES['antique_image']['error'] === 0) {
        $originalFileName = basename($_FILES['antique_image']['name']);
        $uniqueID = uniqid();
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $newFileName = $uniqueID . '.' . $fileExtension;
        $newFilePath = $uploadDirectory . $newFileName;
        $escaped_description = $_POST['itemdescription'];
        $escaped_comments = $_POST['comments'];
        $escaped_combined_data = "Description: " . $escaped_description . ". Comments: ". $escaped_comments;
        $escaped_price = $_POST['price'];
        $escaped_contact = $_POST['prefered_contact']; //0 = email, 1 = phone
        $escaped_userID = $_SESSION["id"];

        if ((strlen($escaped_comments) > 255)  ||  (strlen($escaped_description) > 255) ){
            header("Location: requestevaluation.php?error=11"); //Maximum size reached
            die();
        }
        if ($_FILES['antique_image']['size'] > 10 * 1024 * 1024) {
            header("Location: requestevaluation.php?error=11"); //Maximum size reached
            die();
        }
        if (!in_array(strtolower($fileExtension), $supportedFormats)) {
            header("Location: requestevaluation.php?error=12"); // Invalid format
            die();
        }
        $encryptedData = encrypt_image($_FILES['antique_image']['tmp_name']);
        if (file_put_contents($newFilePath, $encryptedData)) {
            $STMT = $connection->prepare('INSERT INTO auctions (user_id, comment, contact, image_path, price) VALUES (?, ?, ?, ?, ?)');
	        if ($STMT) {
	            $enc_combined_data = encrypt_data($escaped_combined_data);
	            $enc_price = encrypt_data((string)$escaped_price);
		        $STMT->bind_param('issss', $escaped_userID, $enc_combined_data, $escaped_contact, $newFileName, $enc_price);
		        $STMT->execute();
		        header("Location: requestevaluation.php?error=14"); //Succesfully sent out a product for evaluation
		        $STMT->close();
            }
        } else {
            header("Location: requestevaluation.php?error=13"); // Internal upload error
            die();
        }
    } else {
        header("Location: requestevaluation.php?error=13"); // Internal upload error
        die();
    }
    


}
else{
    header("Location: requestevaluation.php?error=13");
    die();
}


?>