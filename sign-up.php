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
include('error_handler.php');
session_start();

$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self';script-src 'self' 'nonce-$nonce' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com https://ajax.googleapis.com;style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com https://www.w3schools.com https://cdnjs.cloudflare.com;font-src 'self' https://fonts.gstatic.com   https://cdnjs.cloudflare.com;img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self'; object-src 'none'; base-uri 'self';");

if (empty($_SESSION['csrf_token_capcha'])) {
    $_SESSION['csrf_token_capcha'] = bin2hex(random_bytes(32));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php echo '<meta name="csrf-token-capcha" content="'.   htmlspecialchars($_SESSION['csrf_token_capcha'] ). '">';?>
  <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png">
  <link rel="icon" type="image/png" href="img/heart-logo.png">
  <title>
    LoveJoy - Signup
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <link id="pagestyle" href="css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <link href="css/customcss.css" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="">
  <!-- Navbar -->
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
  <!-- End Navbar -->
  <main class="main-content  mt-0">
    <section class="min-vh-100 mb-8">
      <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg sign_up_image" >
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 text-center mx-auto">
              <h1 class="text-white mb-2 mt-5">Welcome!</h1>
              <p class="text-lead text-white">Create your account today for free!</p>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card z-index-0">
              <div class="card-header text-center pt-4">
                <h5>Register with</h5>
              </div>
              <div class="card-body">

                <?php

                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }?>
                <form role="form text-left" id="signform" name="signform" action="authenticate_signup.php" method="post">
                 <?php echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '"> ' ?>

                  <div class="mb-3">
                    <input type="text" class="form-control" name="signup_username" id="signup_username" placeholder="Username" aria-label="Username" aria-describedby="email-addon" required>
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="signup_password" id="signup_password" placeholder="Password" aria-label="Password" aria-describedby="password-addon"  required>
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="password-addon"  required>
                  </div>
                  <a id="matchmessage"> </a>
                   <a id="message"> </a>
                  <div class="mb-3">
                    <input type="email" class="form-control" name="signup_email" id="signup_email" placeholder="Email" aria-label="Email" aria-describedby="email-addon" required>
                  </div>
                  <div class="mb-3">
                      <label class="visually-hidden" for="autoSizingInputGroup">Username</label>
                      <div class="input-group">
                      <div class="input-group-text signup_phone_input" >
                          +44
                      </div>
                      <input type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==10) return false;" id="signup_phone" name="signup_phone" class="form-control outline" placeholder="Phone" max="9999999999">
                  </div>
                 </div>


                  <small> Please select a security question </small>
                  <div class="mb-3">
                    <select class="form-select" name="security_question_question" id="security_question_question" aria-label="Name of your first childhood pet" required>
                      <option value="1">Name of your first childhood pet?</option>
                      <option value="2">What is your favourite sport?</option>
                      <option value="3">What street did you grow-up on?</option>
                      <option value="4">What is your favourite movie?</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <input type="text" class="form-control" name="security_question_answer" id="security_question_answer" placeholder="Answer" aria-label="Answer" aria-describedby="answer-addon"  required>
                  </div>

                   <div class="card card-body capcha-card">
                       <div class="d-flex align-items-center gap-2">
                           <!-- CAPTCHA Image -->
                           <img id="capchaimage" src="createcapcha.php" alt="CAPTCHA" class="img-fluid capcha_image">
                           <input type="text" class="form-control capcha_input" id="capcha-input" placeholder="Enter code" aria-label="CAPTCHA Code" name="capcha-input" maxlength="4" required>
                           <button type="button" class="btn btn-outline-success capcha capcha_button" name="check-capcha" id="check-capcha" >✔</button>
                       </div>
                       <label id="capchalabel" class="capcha_label"> Please enter a valid code </label>
                   </div>
                   <div class="terms_conditions"> </div>

                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="Agree" checked="" required>
                      <label class="form-check-label" for="rememberMe">I agree to the terms and conditions</label>
                    </div>

                  <div class="text-center">
                    <button type="submit" id="submitbutton" name="submitbutton" class="btn bg-gradient-dark w-100 my-4 mb-2" disabled>Sign up</button>
                  </div>
                  <p class="text-sm mt-3 mb-0">Already have an account? <a href="sign-in.php" class="text-dark font-weight-bolder">Sign in</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mb-4 mx-auto text-center">
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Company
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              About Us
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Team
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Products
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Blog
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Pricing
            </a>
          </div>
          <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-dribbble"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-twitter"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-instagram"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-pinterest"></span>
            </a>
            <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
              <span class="text-lg fab fa-github"></span>
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              Copyright ©   <script nonce="<?= $nonce ?>">
                document.write(new Date().getFullYear())
              </script> Soft by Creative Tim.
            </p>
          </div>
        </div>
      </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  </main>
  <!--   Core JS Files   -->
  <script src="js/core/popper.min.js"></script>
  <script src="js/core/bootstrap.min.js"></script>



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
        const capchaInput = document.getElementById("capcha-input");
        var allValid = (bMatchingPassword && bValidPassword && capchaInput.disabled)
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


 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script nonce="<?= $nonce ?>">
  function showCapchaFailure(words) {
      // Show the label, fade it in
      $('#capchalabel').fadeIn(500);  // 500ms fade in time
        $('#capchalabel').text(words);
      // Hide the label after 3 seconds (3000ms)
      setTimeout(function() {
        $('#capchalabel').fadeOut(500, function() {
          // After fading out, set display to 'none' to ensure it's hidden
          $(this).hide();
        });
      }, 3000);  // 3 seconds delay
    }
    document.addEventListener("DOMContentLoaded", () => {
    const csrfTokenCapcha = document.querySelector('meta[name="csrf-token-capcha"]').getAttribute('content');
    const checkCapchaBtn = document.getElementById("check-capcha");
    const capchaInput = document.getElementById("capcha-input");
    const submitBtn = document.getElementById("submitbutton");

    submitBtn.disabled = true;
    checkCapchaBtn.addEventListener("click", () => {
        const capchaValue = capchaInput.value.trim();
        if (!capchaValue) {
            showCapchaFailure("Please enter a valid code");
            return;
        }
        checkCapchaBtn.classList.add("spin");
        const escapedCapcha = encodeURIComponent(capchaValue);
          fetch("checkcapcha.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
               "X-CSRF-Token": csrfTokenCapcha,
            },
            body: `capcha_code=${escapedCapcha}&csrf_token_capcha=${csrfTokenCapcha}`,
          }).then((response) => response.json()).then((data) => {
              setTimeout(() => {
                if (data.success) {
                  checkCapchaBtn.classList.remove("spin");
                  checkCapchaBtn.classList.add("success");
                  capchaInput.disabled = true;
                  checkCapchaBtn.disabled = true;
                  var password = $('#signup_password').val();
                 var confirmPassword = $('#confirm_password').val();
                 var regexNumber = /\d/;
                 var regexSpecialChar = /[!@#$%^&*(),.?":{}|<>]/;
                 var bValidPassword = password.length > 10 && regexNumber.test(password) && regexSpecialChar.test(password);
                 var bMatchingPassword = (password === confirmPassword);
                  if (bMatchingPassword && bValidPassword){
                    submitBtn.disabled = false;
                  }
                }
                else {
                  showCapchaFailure("This is not a valid code. " + data.attempts + " / 5 attempts left.");
                  submitBtn.disabled = true;
                  checkCapchaBtn.classList.remove("spin");
                  checkCapchaBtn.classList.add("failure");
                  if (data.attempts == 0){
                   window.location.replace("http://localhost/lovejoy/sign-up.php?error=35");
                }
                  setTimeout(() => {
                    checkCapchaBtn.classList.remove("failure");
                    document.getElementById("capchaimage").src = "createcapcha.php";
                  }, 1000);
                  capchaInput.value = "";
                }
              }, 400);
            })
            .catch((error) => {
              showCapchaFailure("There was an error verifying your capcha.");
        });
      });
   });
   document.getElementById("signform").addEventListener("submit", function(event) {
        document.getElementById("capcha-input").disabled = false;
     });
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="js/soft-ui-dashboard.min.js?v=1.1.0"></script>

</body>

</html>