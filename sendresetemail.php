<?php


function password_check($hashed, $plaintext){
	return ($hashed === $plaintext);
}
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
include("encryption_manager.php");
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function sendResetEmailToUser($target_email, $token){
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'contact00kitty@gmail.com';
        $mail->Password = 'phjk yexo shmh plhd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('contact00kitty@gmail.com', 'Lovejoy');
        $mail->addAddress($target_email, 'Reset password');
           //phjk yexo shmh plhd
        $mail->isHTML(true);
        $imagePath = 'img/reset_graphic.png';
        $mail->addEmbeddedImage($imagePath, 'reset_graphic', 'reset_graphic.png');  // Embed the image with a CID

        $verification_link = 'localhost/lovejoy/resetpassword.php?token=' . $token;

        $mail->Subject = 'Reset the password for your LoveJoy account';
        $mail->Body = '<!DOCTYPE html>
                       <html lang="en">
                       <head>
                           <meta charset="UTF-8">
                           <meta name="viewport" content="width=device-width, initial-scale=1.0">
                           <title>Email Verification</title>
                           <style>
                               body, h1, p, a {
                                   margin: 0;
                                   padding: 0;
                               }
                               .email-container {
                                   width: 100%;
                                   max-width: 1000px;
                                   margin: 0 auto;
                                   float: left;
                                   background-color: #ffffff;
                                   border: 1px solid #e1e1e1;
                                   border-radius: 8px;
                                   overflow: hidden;
                               }
                               .email-body {
                                   padding: 20px;
                               }
                               h1 {
                                   color: #007bff;
                                   font-size: 24px;
                                   text-align: center;
                               }
                               p {
                                   font-size: 16px;
                                   color: #555555;
                                   line-height: 1.5;
                               }
                               .btn-verify {
                                   display: block;
                                   margin-left: auto;
                                   margin-right: auto;
                                   width: 20%;
                                   padding: 12px 20px;
                                   font-size: 16px;
                                   background-color: #007bff;
                                   color: #ffffff;
                                   text-decoration: none;
                                   border-radius: 4px;
                                   text-align: center;
                                   margin-top: 20px;
                                   margin-bottom: 30px;
                               }
                               .btn-verify:hover {
                                   background-color: #0056b3;
                               }
                               .footer {
                                   padding: 10px;
                                   text-align: center;
                                   font-size: 12px;
                                   color: #777777;
                               }
                           </style>
                       </head>
                       <body>
                           <div class="email-container">
                               <div  class="email-body" style="text-align: center;">
                                   <h1 >Password reset</h1>
                                   <img src="cid:reset_graphic"alt="Password reset graphic" style="width: 40%; height: 40%; "/>
                                   <p>Please click the link below to reset your password.</p>
                                   <!-- Verification Button -->
                                   <a href="' . htmlspecialchars($verification_link, ENT_QUOTES, 'UTF-8')  . '"  style="color: white" class="btn-verify">Reset your Password</a>
                                   <p>If you did not submit a reset password request, please ignore this email.</p>
                               </div>
                               <div class="footer">
                                   <p>&copy; 2024 LoveJoy. All rights reserved.</p>
                               </div>
                           </div>
                       </body>
                       </html>';
        $mail->AltBody = 'This is a plain-text version of the email content.';

        // Send email
        $mail->send();
    } catch (Exception $e) {
        die();
    }
}



session_start();
include('database.php');
include('email.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['csrf_token'])){
        header("Location: resetpassword.php?error=27");
        die();
    }
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: resetpassword.php?error=27");
        die();
    }
    unset($_SESSION['csrf_token']);

	if (!isset($_POST['resetemail'])){
		header("Location: resetpassword.php?error=27");
		die();
	}

	if (empty($_POST['resetemail'])){
		header("Location: resetpassword.php?error=9");
		die();
	}
    if (!filter_var($_POST['resetemail'], FILTER_VALIDATE_EMAIL)){
        header("Location: resetpassword.php?error=9");
        die();
    }
	$escaped_email =			$_POST['resetemail'];

    $STMT = $connection->prepare('SELECT id, email, password_hash, verified, reset_token, reset_token_time FROM users');
    if ($STMT){
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($userID, $encrypted_email, $password, $is_verified, $reset_token_grabbed, $reset_token_time_grabbed);
            while ($STMT->fetch()) {
                $decrypted_email = decrypt_data($encrypted_email);
                if ($decrypted_email === $escaped_email) {
                    if (!$is_verified) {
                        header("Location: resetpassword.php?error=22");
                        $STMT->close();
                        exit();
                    }

                    $time_now = strtotime('now');
                    $time_past = strtotime($reset_token_time_grabbed);
                    $reset_token_delta = $time_now - $time_past - 6700;
                    if ($reset_token_grabbed !== NULL && $reset_token_delta < 3600) {
                        header("Location: resetpassword.php?error=32");
                        $STMT->close();
                        exit();
                    }
                    $reset_token = bin2hex(random_bytes(32));
                    sendResetEmailToUser($decrypted_email, $reset_token);
                    $_SESSION["reset_token"] = $reset_token;
                    $timenow = date('Y-m-d H:i:s');
                    $STMT = $connection->prepare('UPDATE users SET reset_token = ?, reset_token_time = ? WHERE id = ?');
                    if ($STMT) {
                        $STMT->bind_param('sss', $reset_token, $timenow, $userID);
                        $STMT->execute();
                    }
                    header("Location: resetpassword.php?error=30");
                    $STMT->close();
                    exit();
                } else {
                    header("Location: resetpassword.php?error=1");
                    $STMT->close();
                    exit();
                }

            }
            header("Location: sign-up.php?error=0");
            exit();
        } else {
            header("Location: sign-up.php?error=0");
            exit();
        }
    } else {
        header("Location: sign-up.php?error=0");
        exit();
    }


}

?>