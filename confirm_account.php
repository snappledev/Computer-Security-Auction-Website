<?php
include('database.php');
include('encryption_manager.php');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");

if (!isset($_GET['email'],$_GET['token'])){
    header("Location: sign-in.php?error=23"); //Invalid token data
    die();
}
$escaped_email = htmlspecialchars($_GET['email']);
$escaped_token = htmlspecialchars($_GET['token']);

if (strlen($escaped_token) != 64){
    header("Location: sign-in.php?error=23");//Invalid token data
    die();
}

$STMT = $connection->prepare('SELECT email, verification_token, verification_token_time FROM users');
if ($STMT){
    $STMT->execute();
    $STMT->store_result();
    if ($STMT->num_rows > 0){
        $STMT->bind_result($encrypted_email, $grabbed_token, $grabbed_token_time);
        while ($STMT->fetch()) {
            $decrypted_email = decrypt_data($encrypted_email);
            if ($decrypted_email === $escaped_email && hash_equals($grabbed_token, $escaped_token)){
                $time_now = strtotime('now');
                $time_past = strtotime($grabbed_token_time);
                $token_age = $time_now - $time_past - 3600; // Subtract 1 hour from the current time
                if ($token_age > 3600) {
                    header("Location: sign-in.php?error=24"); //Give them option to resend token
                    die();
                } else {
                    $STMT = $connection->prepare('UPDATE users SET verified = 1, verification_token = NULL WHERE email = ?');
                    $STMT->bind_param('s', $encrypted_email); // Use the encrypted email for the update
                    $STMT->execute();
                    $STMT->close();
                    header("Location: sign-in.php?error=25"); // Successfully verified email
                    die();
                }
            }
        }
        header("Location: sign-in.php?error=1");
        die();
    }
    else {
        header("Location: sign-in.php?error=1");
        die();
    }
} else {
    header("Location: sign-in.php?error=0");
    die();
}




?>