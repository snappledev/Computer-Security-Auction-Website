<?php
include("attempt_handler.php");
$error_message = '';
$error_class = ''; // This will hold the color class for the error
if (isset($_GET['error'])) {
    $IP = get_client_ip();
    // Check the error code and set the message and color
    $error_code = (int)$_GET['error'];

    switch ($error_code) {
        case '0':
            $error_message = "Oops... Looks like there was a server error. We're working to fix it!'";
            $error_class = 'alert-info';
            break;
        case '1':
            $error_message = "Error: Invalid username or password.";
            $error_class = 'alert-danger';
            break;
        case '2':
            $error_message = "Error: User not signed in. Please sign-in";
            $error_class = 'alert-warning';
            break;
        case '3':
            $error_message = "Error: Admin permission is required to view this page";
            $error_class = 'alert-danger';
            break;
         case '4':
            $error_message = "You have been succesfully signed-out!";
            $error_class = 'alert-success';
             break;
        case '5':
            $error_message = "This username is already in use. Please select another.";
            $error_class = 'alert-warning';
            break;
        case '6':
            $error_message = "This email address is already in use. Please select another.";
            $error_class = 'alert-warning';
            break;
        case '7':
            $error_message = "You have succesfully signed up! You can now sign-in";
            $error_class = 'alert-success';
            break;
        case '8':
            $error_message = "Oops! Your passwords do not match. Please try again";
            $error_class = 'alert-warning';
            break;
        case '9':
            $error_message = "An unexpected error has occured, please re-try";
            $error_class = 'alert-warning';
            break;
        case '10':
            $error_message = "Your security question was incorrect. Please re-try";
            $error_class = 'alert-warning';
            break;
        case '11':
            $error_message = "The file you uploaded was too big. The file must not exceed 10MB.";
            $error_class = 'alert-warning';
            break;
        case '12':
            $error_message = "Oops! Looks like you didn't upload an image. Please try again.";
            $error_class = 'alert-warning';
            break;
        case '13':
            $error_message = "Error: We experienced an internal file error trying to complete this request";
            $error_class = 'alert-warning';
            break;
        case '14':
            $error_message = "Success! Your item has been submitted for evaluation";
            $error_class = 'alert-success';
            break;
        case '20':
            $error_message = "Success! Your item has been submitted for evaluation";
            $error_class = 'alert-success';
            break;
        case '21':
            $error_message = "Success! Please check your email and confirm your account to complete registration!";
            $error_class = 'alert-success';
            break;
        case '22':
            $error_message = "Oops! Looks like your account isnt verified yet! Please check your email and confirm your account to complete registration!";
            $error_class = 'alert-warning';
            break;
        case '23':
            $error_message = "Oops! Looks like there was an error confirming your account. Please re-try";
            $error_class = 'alert-warning';
            break;
        case '24':
            $error_message = "Oops! Looks like your verification email has expired! Please go to resendverification.php to resend";
            $error_class = 'alert-warning';
            break;
        case '25':
            $error_message = "Your email has been successfully verified. Congratulations!";
            $error_class = 'alert-success';
            break;
         case '26':
            $error_message = "This phone number has already been registered on our system. Please sign-in";
            $error_class = 'alert-warning';
            break;
         case '27':
             $error_message = "Your token could not be validated. Please try again later.";
             $error_class = 'alert-warning';
             break;
         case '30':
             $error_message = "A password reset link has been sent to your email. Please check your inbox";
             $error_class = 'alert-success';
             break;
         case '31':
             $error_message = "Your password has been succesfully reset. You may now sign-in!";
             $error_class = 'alert-success';
             break;
         case '32':
              $error_message = "Looks like we've already sent you a password reset email. Please check your email.";
              $error_class = 'alert-warning';
              break;
          case '34':
                $error_message = "There was a problem verifying your captcha. Please re-try";
                $error_class = 'alert-warning';
                break;
          case '35':
              $error_message = "Your account has been temporarily locked due to numerous failed sign-in attempts. Locked til ";
              $error_message .= htmlspecialchars(get_time_left($IP));
              $error_class = 'alert-danger';
              break;
          case '36':
                $error_message = "You have failed a login attempt. " .  htmlspecialchars(get_attempts_left($IP)) . ' / 5 Attempts Left.';
                $error_class = 'alert-danger';
                break;
        default:
            $error_message = "Error: Unidentified internal error occured";
            $error_class = 'alert-danger';
             break;
    }
}

?>