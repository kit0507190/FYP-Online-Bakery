<?php
/**
 * forgot_password_handler.php
 * 处理忘记密码请求：验证用户、生成验证码、发送美化后的 HTML 邮件。
 */

// 1. 设置时区为马来西亚，确保生成的过期时间与本地时间一致
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 引入 PHPMailer 核心文件 (请确保路径正确)
require '../vendor/phpmailer/Exception.php'; 
require '../vendor/phpmailer/PHPMailer.php';
require '../vendor/phpmailer/SMTP.php';

// 2. 数据库连接配置
$host = 'localhost';
$db   = 'bakeryhouse';
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Location: forgotpassword.php?message=" . urlencode("Database connection failed"));
    exit;
}

// 3. 处理 POST 请求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); 

    // 检查用户输入的 Email 是否存在于我们的用户表中
    $stmt = $pdo->prepare("SELECT id FROM user_db WHERE email = ?");
    $stmt->execute([$email]);
    $user_exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_exists) {
        // --- 核心逻辑：生成 6 位随机验证码 ---
        $code = sprintf("%06d", mt_rand(1, 999999)); 
        // 设置 10 分钟后的时间点作为“过期时间”
        $expiry_time = date("Y-m-d H:i:s", time() + 600); 

        $pdo->beginTransaction();
        try {
            // 先删除该邮箱之前旧的重置请求，防止数据库记录堆积
            $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
            // 将新验证码、邮箱、过期时间存入数据库
            $insert_stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
            $insert_stmt->execute([$email, $code, $expiry_time]);
            $pdo->commit();

            // 将 email 存入 Session，供后续页面使用
            $_SESSION['reset_email'] = $email;

        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: forgotpassword.php?message=" . urlencode("System error, please try again"));
            exit;
        }

        // --- 4. 配置 PHPMailer 发送美化后的 HTML 邮件 ---
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'yitanglong857@gmail.com'; // 你的 Gmail 账号
            $mail->Password   = 'ucxn rkss fgtu ahnk';     // 你的 Gmail 应用专用密码
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587; 
            $mail->CharSet    = 'UTF-8'; 

            $mail->setFrom('no-reply@bakeryhouse.com', 'Bakery House Support');
            $mail->addAddress($email); 
            $mail->isHTML(true); 
            $mail->Subject = 'Verify your account - Bakery House';
            
            // --- 美化后的 HTML 邮件模板 ---
            $mail->Body = "
            <div style='background-color: #fdf6f0; padding: 40px; font-family: sans-serif;'>
                <div style='max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
                    <div style='background-color: #d4a76a; height: 10px;'></div>
                    
                    <div style='padding: 30px; text-align: center;'>
                        <h2 style='color: #5a3921; margin-top: 0;'>Password Reset Request</h2>
                        <p style='color: #666; font-size: 16px; line-height: 1.5;'>
                            Hello!<br>We received a request to reset your Bakery House account password. Please use the verification code below:
                        </p>
                        
                        <div style='background-color: #f8f1e7; border-radius: 10px; padding: 20px; margin: 25px 0; border: 1px solid #d4a76a;'>
                            <span style='display: block; font-size: 12px; color: #a67c52; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;'>Your Verification Code</span>
                            <span style='font-size: 36px; font-weight: bold; color: #d4a76a; letter-spacing: 5px;'>{$code}</span>
                        </div>
                        
                        <p style='color: #999; font-size: 14px;'>
                            This code will expire in <strong style='color: #5a3921;'>10 minutes</strong>.
                        </p>
                        
                        <hr style='border: 0; border-top: 1px solid #eee; margin: 30px 0;'>
                        
                        <p style='color: #e74c3c; font-size: 12px; text-align: left; background-color: #fff5f5; padding: 10px; border-radius: 5px;'>
                            <strong>Security Note:</strong> If you did not request this password reset, please ignore this email. Your account is still safe.
                        </p>
                    </div>
                    
                    <div style='background-color: #5a3921; padding: 15px; text-align: center;'>
                        <p style='color: #fdf6f0; font-size: 12px; margin: 0;'>&copy; 2025 Bakery House. Sweet & Delicious.</p>
                    </div>
                </div>
            </div>";

            $mail->send();
            
            // 邮件发送成功，跳转到验证码校验页
            header("Location: verify_code.php");
            exit;

        } catch (Exception $e) {
            header("Location: forgotpassword.php?message=" . urlencode("Failed to send email. Please try again."));
            exit;
        }
    } else {
        // 用户不存在提示
        header("Location: forgotpassword.php?message=" . urlencode("The email address is not registered."));
    }
}