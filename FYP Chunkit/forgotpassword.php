<?php
// forgot_password.php - 忘记密码（输入邮箱）页面
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Bakery House</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            min-height: 550px;
        }

        .logo-section {
            flex: 1;
            background: linear-gradient(to bottom right, #d4a574, #b8864e);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .logo-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .logo-content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
        }

        .logo-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: white;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 5px solid rgba(255, 255, 255, 0.3);
        }

        .logo-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-text {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .tagline {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .slogan {
            font-size: 20px;
            font-weight: 600;
            margin-top: 40px;
            font-style: italic;
            text-align: center;
            line-height: 1.4;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h1 {
            color: #5a3921;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 16px;
        }
        
        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { display: block; margin-bottom: 8px; color: #5a3921; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px 15px; border: 2px solid #e1e1e1; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; }
        .form-group input:focus { outline: none; border-color: #d4a76a; box-shadow: 0 0 0 3px rgba(212, 167, 106, 0.2); }
        .btn { width: 100%; padding: 14px; background: #d4a76a; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s ease; margin-bottom: 20px; }
        .btn:hover { background: #c2955a; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #d4a76a; text-decoration: none; font-weight: 500; }
        
        /* 消息框样式 */
        .message-box {
            background-color: #f0f8ff;
            color: #1a708a;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #cceeff;
            border-radius: 10px;
            text-align: left;
        }
        
        /* 媒体查询保持响应式 */
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            .logo-section, .form-section { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <!-- HTML部分保持不变 -->
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
                    <input type="email" name="email" id="email" required placeholder="name@example.com">
                </div>

                <button type="submit" class="btn">Send Verification Code</button>
            </form>
            
            <div class="back-link">
                <a href="User_Login.php">Return to Login Page</a>
            </div>
        </div>
    </div>
</body>
</html>