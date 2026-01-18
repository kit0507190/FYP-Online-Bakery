<?php
// forgot_password.php
session_start(); 

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// 自动填充逻辑
$prefilled_email = '';
if (isset($_SESSION['user_email'])) {
    $prefilled_email = htmlspecialchars($_SESSION['user_email']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Bakery House</title>
    <link rel="stylesheet" href="forgotpassword.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div class="logo-content">
                <div class="logo-image">
                    <img src="Bakery House Logo.png" alt="Bakery House Logo"> 
                </div>
                <div class="logo-text">Bakery House</div>
                <div class="tagline">Sweet & Delicious</div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h1>Forgot Password?</h1>
                <p>Please enter the email address you used for registration.</p>
            </div>
            
            <?php if ($message): ?>
                <div class="message-box">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form action="forgot_password_handler.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" 
                           value="<?= $prefilled_email ?>" 
                           <?= $prefilled_email ? 'readonly' : '' ?>
                           required placeholder="name@gmail.com">
                </div>

                <button type="submit" class="btn">Send Verification Code</button>
            </form>
            
            <div class="back-link">
                <a href="<?= isset($_SESSION['user_id']) ? 'changepassword.php' : 'User_Login.php' ?>">
                    Return to Previous Page
                </a>
            </div>
        </div>
    </div>
</body>
</html>