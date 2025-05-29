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
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com https://ajax.googleapis.com;style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com;font-src 'self' https://fonts.gstatic.com;img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self'; object-src 'none'; base-uri 'self';");

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
    LoveJoy - Sign In
  </title>
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <link id="pagestyle" href="css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                  <h3 class="font-weight-bolder text-info text-gradient">Welcome back</h3>
                  <p class="mb-0">Enter your username and password to sign in</p>
                </div>
                <div class="card-body">



                <?php

                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }?>

                  <form role="form" id="signform" action="authenticate_signin.php" method="post">

                     <?php echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']). '"> ' ?>
                    <label>Username</label>
                    <div class="mb-3">
                      <input type="username" class="form-control" name="username" id="username" placeholder="Username" aria-label="Username" aria-describedby="username-addon" required>
                    </div>


                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" name="password" id="password "class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                    </div>
                    <small class="lost_password_small"> Lost password? <a href="resetpassword.php"> <b>Reset now  </b></a> </small>
                    <div class="remember_me_space"></div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
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

                    <div class="text-center">
                      <input type="submit" label="Sign In" id="submit-btn" class="btn bg-gradient-info w-100 mt-4 mb-0" disabled></input>
                    </div>
                  </form>




                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="sign-up.php" class="text-info text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6 sign_in_image"></div>
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
    const submitBtn = document.getElementById("submit-btn");
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
                  submitBtn.disabled = false;
                }
                else {
                  var output = data.attempts;
                  showCapchaFailure("This is not a valid code. " + data.attempts + " / 5 attempts left.");
                  submitBtn.disabled = true;
                  checkCapchaBtn.classList.remove("spin");
                  checkCapchaBtn.classList.add("failure");
                  if (data.attempts == 0){
                     window.location.replace("http://localhost/lovejoy/sign-in.php?error=35");
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