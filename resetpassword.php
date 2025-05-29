<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->

<?php
session_start();
include('error_handler.php');
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self';script-src 'self' 'nonce-$nonce' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com https://ajax.googleapis.com;style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com https://www.w3schools.com https://cdnjs.cloudflare.com;font-src 'self' https://fonts.gstatic.com   https://cdnjs.cloudflare.com;img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self'; object-src 'none'; base-uri 'self';");

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png">
  <link rel="icon" type="image/png" href="img/heart-logo.png">
  <title>
    LoveJoy - Sign In
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->

  <!-- CSS Files -->
  <link id="pagestyle" href="css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
   <link href="css/customcss.css" rel="stylesheet" />

</head>




<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid pe-0">
           <div class="flex">
            <img src="img/heart-logo.png" height="20" class="navbar-brand-img h-3 lovejoy_logo" alt="main_logo">
            </div>
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="index.php">
              LoveJoy
            </a>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="index.php">
                    <i class="fa fa-home opacity-6 text-dark me-1"></i>
                    Home
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="requestevaluation.php">
                    <i class="fa fa-camera opacity-6 text-dark me-1"></i>
                    Request Evaluation
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="evaluationrequests.php">
                    <i class="fas fa-clipboard opacity-6 text-dark me-1"></i>
                    Evaluation Requests
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="settings.php">
                    <i class="fas fa-cog opacity-6 text-dark me-1"></i>
                    Account Settings
                  </a>
                </li>
              </ul>
              <ul class="navbar-nav ms-auto d-flex">
              <li class="nav-item">
                  <a class="btn btn-round btn-sm mb-0 btn-outline-primary me-2" target="_blank" href="sign-in.php"> Sign In</a>
                </li>
                <li class="nav-item">
                  <a href="sign-up.php" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-dark">Sign Up</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
  <!-- Error Bar / Alert (only visible if error is set) -->
 <?php if (!empty($error_message)): ?>
   <div class="alert <?php echo htmlspecialchars($error_class); ?> error-bar alert-dismissible fade show" role="alert">
     <strong><?php echo htmlspecialchars($error_message); ?></strong>
     <button type="button" class="btn-close error-bar-btn" data-bs-dismiss="alert" aria-label="Close">X</button>
   </div>
   <?php endif; ?>


  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient">Reset Password</h3>
                 <?php
                 include("database.php");
                 $display_resetpage = false;
                 if (!isset($_GET["token"])){
                    //No token so dont display
                    $display_resetpage = false;
                 }
                 else{
                    $token = $_GET["token"];

                    //We have a token set but it may or may not be valid
                    $STMT = $connection->prepare("SELECT email, reset_token_time FROM users WHERE reset_token = ?");
                    $STMT->bind_param("s", $token);
                    $STMT->execute();
                    $STMT->store_result();
                    if ($STMT->num_rows === 0) {
                        $display_resetpage = false;
                    }
                    else{
                        $STMT->bind_result($grabbed_email, $token_expiry);
                        $STMT->fetch();
                        $STMT->close();
                        $time_now = strtotime('now');
                        $time_past =  strtotime($token_expiry);
                        $token_age = $time_now - $time_past - 6700;
                        if ($token_age > 3600) { // 1 hours
                             $display_resetpage = false;
                             unset($_SESSION['reset_token']);
                             $nF = NULL;
                             $STMT = $connection->prepare("UPDATE users SET reset_token=? WHERE reset_token = ?");
                             $STMT->bind_param("ss", $nF, $token);
                             $STMT->execute();

                        } else {
                            //Valid token and we found the email
                            $display_resetpage = true;
                        }
                    }
                 }
                 if ($display_resetpage){
                    echo '<p class="mb-0">You can now reset your password. Please ensure you select a strong password.</p>
                          </div>
                          <div class="card-body">';
                          if (!isset($_SESSION['csrf_token'])) {
                              $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                          }
                          echo '
                        <form role="form" action="validatereset.php" method="post">
                          <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
                          <input type="hidden" name="token" id="token" value="' . htmlspecialchars($token) . '">
                          <label> Password </label>
                          <div class="mb-3">
                          <input type="password" class="form-control" name="reset_password" id="reset_password" placeholder="Password" aria-label="Password" aria-describedby="password-addon"  required>
                        </div>
                        <label> Confirm Password </label>
                        <div class="mb-3">
                          <input type="password" class="form-control" name="confirm_resetpassword" id="confirm_resetpassword" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="password-addon"  required>
                        </div>
                        <a id="matchmessage"> </a>
                         <a id="message"> </a>
                          <div class="text-center">
                            <input type="submit" label="Authenticate"class="btn bg-gradient-info w-100 mt-4 mb-0"></input>
                          </div>
                        </form>';
                 }
                 else{
                    echo '<p class="mb-0">If youve forgotten your password, you can reset it by sending a reset email.</p>
                          </div>
                          <div class="card-body">';
                          if (!isset($_SESSION['csrf_token'])) {
                              $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                          }
                          echo '
                        <form role="form" action="sendresetemail.php" method="post">
                          <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
                          <label> Email </label>
                          <div class="mb-3">
                            <input type="securityquestion" class="form-control" name="resetemail" id="resetemail" placeholder="Email" aria-label="Email" aria-describedby="answer-addon">
                          </div>
                          <div class="text-center">
                            <input type="submit" label="Authenticate"class="btn bg-gradient-info w-100 mt-4 mb-0"></input>
                          </div>
                        </form>';
                 }

                 ?>

                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="sign-up.php" class="text-info text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div>
              </div>
            </div>
 
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
       
        </div>
        <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
          <a target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-dribbble"></span>
          </a>
          <a target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-twitter"></span>
          </a>
          <a target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-instagram"></span>
          </a>
          <a  target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-pinterest"></span>
          </a>
          <a target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-github"></span>
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script nonce="<?= $nonce ?>">
              document.write(new Date().getFullYear())
            </script> LoveJoy
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="js/core/popper.min.js"></script>
  <script src="js/core/bootstrap.min.js"></script>
  <script src="js/plugins/perfect-scrollbar.min.js"></script>
  <script src="js/plugins/smooth-scrollbar.min.js"></script>
  <script nonce="<?= $nonce ?>">
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="js/soft-ui-dashboard.min.js?v=1.1.0"></script>
  <script nonce="<?= $nonce ?>">
  $(document).ready(function() {
      $('#signup_password, #confirm_password').on('keyup', function () {
          var password = $('#signup_password').val();
          var confirmPassword = $('#confirm_password').val();
          var regexNumber = /\d/;
          var regexSpecialChar = /[!@#$%^&*(),.?":{}|<>]/;
          var bValidPassword = password.length > 10 && regexNumber.test(password) && regexSpecialChar.test(password);
          var bMatchingPassword = password === confirmPassword;

          if (bMatchingPassword){
              $('#matchmessage').html('Your passwords match.').css('color', 'green');
          } else {
              $('#matchmessage').html('Passwords do not match. Please try again').css('color', 'red');
          }

          if (!bValidPassword){
              $('#message').html('<br>Password is weak. Must be at least 10 characters long, contain at least one number and one special character.</br>').css('color', 'red');
          }
          else{
              $('#message').html('<br>Your password is the strong.</br>').css('color', 'green');
          }

          var allValid = (bMatchingPassword && bValidPassword)
          if (allValid){
              $('#submitbutton').prop('disabled', false);
          }
          else{
              $('#submitbutton').prop('disabled', true);
          }

      });
  });
  </script>

   <script nonce="<?= $nonce ?>">

  $('#signup_password, #confirm_password').on('keyup', function () {
      if ($('#signup_password').val() == $('#confirm_password').val()) {
          $('#message').html('Your passwords match.').css('color', 'green');
      } else
          $('#message').html('Passwords do not match. Please try again').css('color', 'red');
  });
  </script>
</body>

</html>