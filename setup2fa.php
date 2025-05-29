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
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self';script-src 'self' 'nonce-$nonce' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com https://ajax.googleapis.com;style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com https://www.w3schools.com https://cdnjs.cloudflare.com;font-src 'self' https://fonts.gstatic.com   https://cdnjs.cloudflare.com;img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self'; object-src 'none'; base-uri 'self';");

if (!isset($_SESSION['authentication_stage'])){
    header('Location: 2factorauthentication.php?error=2');
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include('error_handler.php');
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <link href="css/customcss.css" rel="stylesheet" />
  <style nonce="<?= $nonce ?>">
    .fasetup .hidden {
        display: none;
    }
    .fasetup .button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .fasetup .button:disabled {
        background-color: #ccc;
    }
    .fasetup input {
        padding: 10px;
        width: 100%;
        margin-bottom: 10px;
    }
  </style>


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
              <div class="card card-header">
                <div class="row d-flex justify-content-center">
                    <div class="col center">
                        <div id="circle_1" class="d-flex justify-content-center align-items-center bg-primary text-white rounded-circle fa_circles_step">
                            1
                        </div>
                        <small> Scan QR Code </small>
                    </div>
                    <div class="col center">
                        <div id="circle_2" class="d-flex justify-content-center align-items-center bg-secondary text-white rounded-circle fa_circles_step" >
                            2
                        </div>
                        <small> Enter OTP </small>
                    </div>
                    <div class="col center">
                        <div id="circle_3" class="d-flex justify-content-center align-items-center bg-secondary text-white rounded-circle fa_circles_step" >
                            3
                        </div>
                        <small> Finish </small>
                    </div>
                </div>
                </div>
                <div class="card card-body fasetup">

                        <div id="step-1" class="step">
                            <h2>Step 1: Scan the QR Code</h2>
                            <p>Use the Google Authenticator app to scan the QR code below:</p>
                            <div>
                            <?php
                                include("database.php");
                                $qrCodeProvider = new EndroidQrCodeProvider();
                                $FASession = new TwoFactorAuth($qrCodeProvider);
                                if ($_SESSION['2FA'] === NULL){
                                    $newKey = $FASession->createSecret();
                                    $_SESSION['2FA'] = $newKey;
                                }
                                $QRCode = $FASession->getQRCodeImageAsDataUri('Lovejoy', $_SESSION['2FA']);

                            echo '<img class="fa_qr_code" src="' . htmlspecialchars($QRCode) . '" alt="QR Code" > <br>';
                            echo ' <p>Important! Please write down the back-up code to ensure you do not lose your account.</p>';
                            echo '<div class="card card-body fa_card_body">';
                            echo '<strong class="center-block">' . htmlspecialchars($_SESSION['2FA']) . ' </strong>';
                            echo '</div>';
                            ?>

                            </div>
                            <form method="POST" action="validate2fa.php">
                                <?php echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '" >'; ?>
                                <br>
                                <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" name="step1_confirmed" id="step1_confirmed" value="0">
                                  <label class="form-check-label" for="step1_confirmed">I confirm I have written down the back-up code.</label>
                                </div>

                                <button class="button next_button_class" data-step="2"  autocomplete="off" type="button" id="next-btn-1"name="step" value="1" disabled>Next</button>
                            </form>
                        </div>

                        <div id="step-2" class="step hidden">
                            <h2>Step 2: Retrieve Your Code</h2>
                            <p>Open your authentication app, locate the code, and enter it below:</p>
                            <form method="POST" action="validate2fa.php">
                               <?php echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '" >'; ?>
                                <input type="text" id="auth_code" name="auth_code" placeholder="Enter 6-digit code" maxlength="6" required>
                                <button class="button next_button_class" data-step="3"  type="button" id="next-btn-2" name="step" value="2" disabled>Next</button>
                            </form>
                        </div>

                        <div id="step-3" class="step hidden">
                            <h2>Step 3: Finalize Setup</h2>
                            <p>Congratulations! You have successfully set up 2FA.</p>
                            <form method="POST" action="validate2fa.php">
                                <?php echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '" >'; ?>
                                <button class="button next_button_class" type="button" name="step" data-step="4" value="3">Finish</button>
                            </form>
                        </div>
                    </div>
                </div>
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
    document.addEventListener("DOMContentLoaded", function() {
      const buttons = document.querySelectorAll('.next_button_class');
      buttons.forEach(function(button) {
        button.addEventListener('click', function() {
          const step = button.getAttribute('data-step'); // Get the value from the data attribute
          showNextStep(step);  // Call the function with the value
        });
      });
    });
    function preventFormSubmitOnEnter(formSelector) {
        document.querySelector(formSelector).addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const nextButton = this.querySelector('.next_button_class:not([disabled])');
                if (nextButton) {
                    nextButton.click();
                }
            }
        });
    }
     preventFormSubmitOnEnter('#step-2 form');
     preventFormSubmitOnEnter('#step-3 form');

  </script>
  <script nonce="<?= $nonce ?>">
           document.getElementById('step1_confirmed').addEventListener('change', function () {
               document.getElementById('next-btn-1').disabled = !this.checked;
           });
           document.getElementById('auth_code').addEventListener('input', function () {
               const code = this.value.trim();
               document.getElementById('next-btn-2').disabled = !(code.length === 6 && /^\d{6}$/.test(code));
           });
           function showNextStep(step) {
               const csrfToken = "<?= htmlspecialchars($_SESSION['csrf_token']) ?>";
               const xhr = new XMLHttpRequest();
               xhr.open('POST', 'validate2fa.php', true);
               xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
               xhr.onload = function () {
                   if (xhr.status === 200) {
                       document.querySelectorAll('.step').forEach(el => el.classList.add('hidden'));
                       const nextStep = document.getElementById(`step-${step}`);
                       if (nextStep) {
                           nextStep.classList.remove('hidden');
                       }
                       document.querySelectorAll('[id^="circle_"]').forEach(el => {
                           el.classList.remove('bg-primary');
                           el.classList.add('bg-secondary');
                       });
                       const selectedCircle = document.getElementById(`circle_${step}`);
                       if (selectedCircle ) {
                           selectedCircle.classList.add('bg-primary');
                           selectedCircle.classList.remove('bg-secondary');
                       }
                   } else {
                       alert('Error progressing to the next step: ' + xhr.responseText);
                   }
               };
               let postData = `csrf_token=${encodeURIComponent(csrfToken)}&step=${step - 1}`;
               if (step == 3) {
                   const code = document.getElementById('auth_code').value.trim();
                   postData += `&auth_code=${encodeURIComponent(code)}`;
               }
               if (step == 4) {
                  window.location.replace("2factorauthentication.php");
              }
               xhr.send(postData);
           }
       </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="js/soft-ui-dashboard.min.js?v=1.1.0"></script>
</body>

</html>