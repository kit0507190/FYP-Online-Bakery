<?php
  session_start();
  include("conn.php");

  //Import PHPMailer classes into the global namespace
  //These must be at the top of your script, not inside a function
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  if (isset($_SESSION['error'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: " . json_encode($_SESSION['error']) . ",
            }).then(() => {
                window.location.href = 'forgotpassword.php';
            });
        });
    </script>";
    unset($_SESSION['error']);
  }

  if (isset($_SESSION['success'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Otp sent successful!',
                text: '{$_SESSION['success']}',
            }).then(function() {
                window.location.href = 'reset-password-otp.php';
            });
        });
    </script>";
    unset($_SESSION['success']);
  }

  if (isset($_POST["submit"])) {
    $email = ($_POST['email']);

    if (!empty($email)){

      $query = "SELECT * FROM users WHERE Email = '$email'";
      $result = mysqli_query($conn, $query);
      
      if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['emailrpo'] = $email;
        $otp=mt_rand(100000,999999);
        $otptime=time();
        $_SESSION['otp_session']=$otp;
        $_SESSION['otp_time']=$otptime;
                        
        //required files
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        //Create an instance; passing true enables exceptions
                        

        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();                              //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;             //Enable SMTP authentication
        $mail->Username   = 'junlong0826@gmail.com';   //SMTP write your email
        $mail->Password   = 'egdecxcrpmzqwmkt';      //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
        $mail->Port       = 465;                                    

        //Recipients
        $mail->setFrom( 'junlong0826@gmail.com'); // Sender Email and name
        $mail->addAddress($email);     //Add a recipient email  
        $mail->addReplyTo('junlong0826@gmail.com', 'test'); // reply to sender email

        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = 'Crayon XinChan Sports OTP';   // email subject headings
        $mail->Body    = 'Dear User, <br><br>'
        . 'To complete the forget password process to Crayon XinChan Sports, please use the following 6-digit OTP code:<br><b><br>' 
        . $otp . '</b><br><br>' 
        . 'Thank you.';

            $mail->send();
        $_SESSION['success'] = "Check your spam if not receive";
        header("Location: forgotpassword.php");
        exit();

      }else {
        $_SESSION['error'] = "Your email hasn't been registered in our website!";
        header("Location: forgotpassword.php");
        exit();
      }

    }else{
      $_SESSION['error'] = "Please fill in your email address !";
      header("Location: forgotpassword.php");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from template.hasthemes.com/julie/julie/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Jan 2025 14:15:04 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Crayon XinChan Sports</title>
    <script src="https://kit.fontawesome.com/3b2a0cbc2e.js" crossorigin="anonymous"></script>

    <!--== Favicon ==-->
    <link rel="shortcut icon" href="Index Picture/logo.webp" type="image/x-icon" />

    <!--== Google Fonts ==-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,400i,500,500i,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,500,600,700" rel="stylesheet">

    <!--== SweetAlert ==-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--== Bootstrap CSS ==-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--== Headroom CSS ==-->
    <link href="assets/css/headroom.css" rel="stylesheet" />
    <!--== Animate CSS ==-->
    <link href="assets/css/animate.css" rel="stylesheet" />
    <!--== Ionicons CSS ==-->
    <link href="assets/css/ionicons.css" rel="stylesheet" />
    <!--== Material Icon CSS ==-->
    <link href="assets/css/material-design-iconic-font.css" rel="stylesheet" />
    <!--== Elegant Icon CSS ==-->
    <link href="assets/css/elegant-icons.css" rel="stylesheet" />
    <!--== Font Awesome Icon CSS ==-->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <!--== Swiper CSS ==-->
    <link href="assets/css/swiper.min.css" rel="stylesheet" />
    <!--== Fancybox Min CSS ==-->
    <link href="assets/css/fancybox.min.css" rel="stylesheet" />
    <!--== Slicknav Min CSS ==-->
    <link href="assets/css/slicknav.css" rel="stylesheet" />

    <!--== Main Style CSS ==-->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!--wrapper start-->
<div class="wrapper">

    <!--== Start Header Wrapper ==-->
    <header class="header-area header-default sticky-header">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-6 col-sm-4 col-lg-3">
            <div class="header-logo-area">
              <a href="index.php">
              <img class="logo-picture" src="Index Picture/logo.webp" alt="Crayon XinChan Sports" width="95px">
              </a>
            </div>
          </div>
          <div class="col-sm-4 col-lg-7 d-none d-lg-block">
            <div class="header-navigation-area">
              <ul class="main-menu nav position-relative">
                <li class="has-submenu"><a href="index.php">Home</a>
                </li>
                <li class="has-submenu full-width"><a href="shop-left-sidebar.php">Shop</a>
                </li>
  
              
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about-us.php">About</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-7 col-lg-2 d-none d-sm-block text-end">
            <div class="header-action-area">
              <ul class="header-action">
                <li class="currency-menu">
                  <a class="action-item" href="#/"><i class="zmdi zmdi-lock-outline icon"></i></a>
                  <ul class="currency-dropdown">
                    <li class="account">
                      <span class="current-account">My account</span>
                      <ul>
                        <?php
                          if (isset($_SESSION['username'])) {
                            echo'<li><a href="update-account.php">Update Account</a></li>';
                            echo'<li><a href="change-password.php">Change Password</a></li>';
                            echo'<li><a href="e-wallet.php">E-Wallet</a></li>';
                            echo'<li><a href="">Wish List</a></li>';
                            echo'<li><a href="cart.php">Shopping Cart</a></li>';
                            echo'<li><a href="">Order History</a></li>';
                            echo'<li><a href="?action=logout">Logout</a></li>';
                          }
                          else {
                            echo'<li><a href="login.php">Login</a></li>';
                          }
                        ?>

                      </ul>
                    </li>
                  </ul>
                </li>
                <li class="mini-cart">
                  <a class="action-item" href="cart">
                    <i class="zmdi zmdi-shopping-cart-plus icon"></i>
                  </a>
        </div>
      </div>
    </header>
    <!--== End Header Wrapper ==-->
    


  
  <main class="main-content">
    

    <!--== Start Account Area Wrapper ==-->
    <section class="account-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-8 m-auto">
            <div class="account-form-wrap">
              <!--== Start Login Form ==-->
              <div class="login-form">
                <div class="content">
                  <h4 class="title">Forgot Password</h4>
                  <p>Please enter your account email address to receive OTP Code to reset your password</p>
                </div>
                <form action="forgotpassword.php" method="POST">

                  <div class="row">
                    
                    <div class="col-12">
                      <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email" required>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="login-form-group">
                        <button class="btn-sign" name="submit" type="submit">Continue</button>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="account-optional-group">
                        <a class="btn-create" href="login.php">< Back to previous</a>
                      </div>
                    </div>

                  </div>
                </form>
              </div>
              <!--== End Login Form ==-->
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--== End Account Area Wrapper ==-->
  </main>

 
  <!--== Start Footer Area Wrapper ==-->
  <footer class="footer-area">
    <div class="footer-top-area">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-lg-3">
            <!--== Start widget Item ==-->
            <div class="widget-item">
              <div class="about-widget">
                <div class="footer-logo-area">
                  <a href="index.php">
                    <h3>Crayon XinChan Sports</h3>
                  </a>
                </div>
                <p class="desc">No.54, Jalan Komersial TAKH 3,75450 Ayer Keroh, Malacca</p>
              </div>
            </div>
            <!--== End widget Item ==-->
          </div>
          <div class="col-sm-6 col-lg-3">
            <!--== Start widget Item ==-->
            <div class="widget-item widget-item-one">
              <h4 class="widget-title">INFORMATION</h4>
              <div class="widget-menu-wrap">
                <ul class="nav-menu">
                  <i class="fa-solid fa-phone"></i>+016-2134245<br>
                  <a href="www.facebook.com"><i class="fa-brands fa-square-facebook"></i></a>
                  <a href="www.instagram.com"><i class="fa-brands fa-square-instagram"></i></a>
                  <a href="www.twitter.com"><i class="fa-brands fa-twitter"></i></a>
                  <a href="qqq@gmail.com"><i class="fa-solid fa-envelope"></i></a>
                </ul>
              </div>
            </div>
            <!--== End widget Item ==-->
          </div>
          <div class="col-sm-6 col-lg-3">
            <!--== Start widget Item ==-->
            <div class="widget-item widget-item-two">
              <h4 class="widget-title">QUICK LINKS</h4>
              <div class="widget-menu-wrap">
                <ul class="nav-menu">
                  <li><a href="index.php">Home</a></li>
                  <li><a href="shop-left-sidebar.php">Products</a></li>
                  <li><a href="about-us.php">About Us</a></li>
                  <li><a href="contact.php">Contact Us</a></li>
                </ul>
              </div>
            </div>
            <!--== End widget Item ==-->
          </div>
          <div class="col-sm-6 col-lg-3">
            
            <!--== End widget Item ==-->
          </div>
        </div>
      </div>
      
    </div>
    <!--== Start Footer Bottom ==-->
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <p class="copyright">© 2023 <span>Julie</span>. Made with <i class="fa fa-heart icon-heart"></i> by <a target="_blank" href="https://themeforest.net/user/codecarnival/portfolio"> Codecarnival</a></p>
          </div>
        </div>
      </div>
    </div>
    <!--== End Footer Bottom ==-->
  </footer>
  <!--== End Footer Area Wrapper ==-->


  <!--== Start Side Menu ==-->
  <aside class="off-canvas-wrapper">
    <div class="off-canvas-inner">
      <div class="off-canvas-overlay"></div>
      <!-- Start Off Canvas Content Wrapper -->
      <div class="off-canvas-content">
        <!-- Off Canvas Header -->
        <div class="off-canvas-header">
          <div class="close-action">
            <button class="btn-menu-close">menu <i class="fa fa-chevron-left"></i></button>
          </div>
        </div>

        <div class="off-canvas-item">
          <!-- Start Mobile Menu Wrapper -->
          <div class="res-mobile-menu menu-active-one">
            <!-- Note Content Auto Generate By Jquery From Main Menu -->
          </div>
          <!-- End Mobile Menu Wrapper -->
        </div>
      </div>
      <!-- End Off Canvas Content Wrapper -->
    </div>
  </aside>
  <!--== End Side Menu ==-->
</div>

<!--=======================Javascript============================-->

<!--=== jQuery Modernizr Min Js ===-->
<script src="assets/js/modernizr.js"></script>
<!--=== jQuery Min Js ===-->
<script src="assets/js/jquery-main.js"></script>
<!--=== jQuery Migration Min Js ===-->
<script src="assets/js/jquery-migrate.js"></script>
<!--=== jQuery Popper Min Js ===-->
<script src="assets/js/popper.min.js"></script>
<!--=== jQuery Bootstrap Min Js ===-->
<script src="assets/js/bootstrap.min.js"></script>
<!--=== jQuery Headroom Min Js ===-->
<script src="assets/js/headroom.min.js"></script>
<!--=== jQuery Swiper Min Js ===-->
<script src="assets/js/swiper.min.js"></script>
<!--=== jQuery Fancybox Min Js ===-->
<script src="assets/js/fancybox.min.js"></script>
<!--=== jQuery Slick Nav Js ===-->
<script src="assets/js/slicknav.js"></script>

<!--=== jQuery Custom Js ===-->
<script src="assets/js/custom.js"></script>

</body>


<!-- Mirrored from template.hasthemes.com/julie/julie/login.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Jan 2025 14:15:52 GMT -->
</html>