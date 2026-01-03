<?php
session_start();
// Security check: if no email session, kick back to first step
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgotpassword.php");
    exit;
}

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code - Bakery House</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fdf6f0 0%, #f8e8d8 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        /* Logo 样式：模仿 Login 页面 */
        .logo-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: white;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 4px solid #f8e8d8;
        }

        .logo-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        h1 { color: #5a3921; font-size: 26px; margin-bottom: 10px; }
        
        .instruction { color: #666; font-size: 15px; margin-bottom: 25px; line-height: 1.5; }
        .instruction strong { color: #d4a76a; }

        /* 输入框美化 */
        input {
            width: 100%;
            padding: 15px;
            margin: 10px 0 20px;
            border: 2px solid #e1e1e1;
            border-radius: 12px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 8px;
            color: #5a3921;
            transition: all 0.3s ease;
        }
        input:focus {
            outline: none;
            border-color: #d4a76a;
            box-shadow: 0 0 0 3px rgba(212, 167, 106, 0.2);
        }

        /* 按钮美化 */
        .btn {
            width: 100%;
            padding: 14px;
            background: #d4a76a;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover { background: #c2955a; }

        .error { color: #e74c3c; background: #f8d7da; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }
        .success { color: #27ae60; background: #d4edda; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }

        .resend-container {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }
        .resend-link { color: #d4a76a; text-decoration: none; font-weight: 600; }
        .resend-link.disabled { color: #ccc; cursor: not-allowed; pointer-events: none; }

        .back-link { margin-top: 20px; }
        .back-link a { color: #999; text-decoration: none; font-size: 13px; transition: color 0.3s; }
        .back-link a:hover { color: #d4a76a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-image">
            <img src="Bakery House Logo.png" alt="Bakery House Logo">
        </div>

        <h1>Verify Your Account</h1>
        <p class="instruction">
            We have sent a 6-digit code to:<br>
            <strong><?= $_SESSION['reset_email'] ?></strong>
        </p>
        
        <?php if ($error): ?> <div class="error"><?= $error ?></div> <?php endif; ?>
        <?php if ($success): ?> <div class="success"><?= $success ?></div> <?php endif; ?>

        <form action="verify_code_handler.php" method="POST">
            <input type="text" name="code" placeholder="000000" maxlength="6" required autocomplete="off">
            <button type="submit" class="btn">Verify Code</button>
        </form>

        <div class="resend-container">
            Didn't receive the code?<br>
            <a href="resend_code_handler.php" id="resendLink" class="resend-link">Resend Code</a>
            <span id="countdown" style="font-weight: bold; color: #d4a76a;"></span>
        </div>

        <div class="back-link">
            <a href="forgotpassword.php">← Back to Email Input</a>
        </div>
    </div>

    <script>
        let timeLeft = 60; 
        const resendLink = document.getElementById('resendLink');
        const countdown = document.getElementById('countdown');

        function startTimer() {
            resendLink.classList.add('disabled');
            let timer = setInterval(function() {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    resendLink.classList.remove('disabled');
                    countdown.innerHTML = "";
                    timeLeft = 60;
                } else {
                    countdown.innerHTML = " in " + timeLeft + "s";
                }
                timeLeft -= 1;
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</body>
</html>