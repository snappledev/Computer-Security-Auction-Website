<?php
require 'vendor/autoload.php';
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function sendVerificationEmailToUser($target_email, $token){
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
        $mail->addAddress($target_email, 'New user registered');
           //phjk yexo shmh plhd
        $mail->isHTML(true);
        $imagePath = 'img/verify_graphic.png';
        $mail->addEmbeddedImage($imagePath, 'verify_graphic', 'verify_graphic.png');
        $verification_link = 'localhost/lovejoy/confirm_account.php?email=' . urlencode($target_email) . '&token=' . $token;

        $mail->Subject = 'Please register your account with LoveJoy';
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
                           <div style="float: left;" class="email-container">
                               <div  class="email-body" style="text-align: center;">
                                   <h1 >Welcome to LoveJoy</h1>
                                   <p>Thank you for registering with us. Please click the button below to verify your email address and activate your account.</p>
                                   <img src="cid:verify_graphic"alt="Password reset graphic" style="width: 40%; height: 40%; "/>
                                   <!-- Verification Button -->
                                   <a href="' . htmlspecialchars($verification_link, ENT_QUOTES, 'UTF-8')  . '"  style="color: white;" class="btn-verify">Verify Your Email</a>
                                   <p> If your verification link has expired, you can resend it <a href="http://localhost/lovejoy/resendverification.php"> here </a> </p>
                                   <p>If you did not register for an account, please ignore this email.</p>
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
?>