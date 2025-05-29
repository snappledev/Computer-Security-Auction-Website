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
require 'vendor/autoload.php';
use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;
session_start();
if (!isset($_SESSION['authentication_stage'])) {
	header('Location: sign-in.php?error=2');
	exit;
}

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

  <!-- CSS Files -->
  <link id="pagestyle" href="css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
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
                                            <h3 class="font-weight-bolder text-info text-gradient">2FA - Important</h3>
                                                <p class="mb-0">To ensure the security of your account, please complete these additional questions</p>
                                            </div>
                        <div class="card-body">
                        <?php if (!isset($_SESSION['csrf_token'])) {
                            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        }
                         echo '<form role="form" action="2FA.php" method="post">
                        <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
                        <label>' .  htmlspecialchars($_SESSION["securityquestion"]) . '</label>
                        <div class="mb-3">
                          <input type="securityquestion" class="form-control" name="security_answer" id="security_answer" placeholder="Answer" aria-label="Answer" aria-describedby="answer-addon">
                        </div>
                        <label>Google Authenticator Code</label>
                        <div class="mb-3">
                          <input type="pin" name="2FACode" id="2FACode "class="form-control" placeholder="Code" aria-label="Code" aria-describedby="code-addon">
                        </div>
                        <div class="text-center">
                          <input type="submit" label="Authenticate"class="btn bg-gradient-info w-100 mt-4 mb-0"></input>
                        </div>
                      </form>';
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
</body>

</html>