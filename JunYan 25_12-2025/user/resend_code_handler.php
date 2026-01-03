<?php
/**
 * resend_code_handler.php
 * 处理“重发验证码”请求：生成新代码并发送美化后的 HTML 邮件。
 */

// 1. 设置时区为马来西亚
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 引入邮件库 (请确保路径与你的项目结构一致)
require '../vendor/phpmailer/Exception.php'; 
require '../vendor/phpmailer/PHPMailer.php';
require '../vendor/phpmailer/SMTP.php';

// 2. 安全检查：如果 Session 里没有 Email，说明是非法进入，踢回第一步
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgotpassword.php");
    exit;
}

$email = $_SESSION['reset_email'];
$host = 'localhost'; 
$db   = 'bakeryhouse'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // --- 3. 生成新的 6 位码和新的 10 分钟过期时间 ---
    $new_code = sprintf("%06d", mt_rand(1, 999999)); 
    $new_expiry = date("Y-m-d H:i:s", time() + 600); 

    // 更新数据库中该邮箱的记录，换成最新的验证码和时间
    $update_stmt = $pdo->prepare("UPDATE password_resets SET token = ?, created_at = ? WHERE email = ?");
    $update_stmt->execute([$new_code, $new_expiry, $email]);

    // --- 4. 配置 PHPMailer 发送新的 HTML 邮件 ---
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
    $mail->Subject = 'Your NEW Verification Code - Bakery House';

    // --- 与主发送逻辑风格统一的 HTML 模板 ---
    $mail->Body = "
    <div style='background-color: #fdf6f0; padding: 40px; font-family: sans-serif;'>
        <div style='max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
            <div style='background-color: #d4a76a; height: 10px;'></div>
            
            <div style='padding: 30px; text-align: center;'>
                <h2 style='color: #5a3921; margin-top: 0;'>New Verification Code</h2>
                <p style='color: #666; font-size: 16px; line-height: 1.5;'>
                    You requested a new code. Please use the <strong>updated</strong> verification code below to proceed:
                </p>
                
                <div style='background-color: #f8f1e7; border-radius: 10px; padding: 20px; margin: 25px 0; border: 2px dashed #d4a76a;'>
                    <span style='display: block; font-size: 12px; color: #a67c52; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;'>Your New Code</span>
                    <span style='font-size: 36px; font-weight: bold; color: #d4a76a; letter-spacing: 5px;'>{$new_code}</span>
                </div>
                
                <p style='color: #999; font-size: 14px;'>
                    The previous code is now invalid. This new code expires in <strong style='color: #5a3921;'>10 minutes</strong>.
                </p>
                
                <hr style='border: 0; border-top: 1px solid #eee; margin: 30px 0;'>
                
                <p style='color: #666; font-size: 12px;'>
                    If you didn't request this, please ensure your account security or contact us.
                </p>
            </div>
            
            <div style='background-color: #5a3921; padding: 15px; text-align: center;'>
                <p style='color: #fdf6f0; font-size: 12px; margin: 0;'>&copy; 2025 Bakery House. Sweet & Delicious.</p>
            </div>
        </div>
    </div>";

    $mail->send();
    
    // 成功后跳回验证码输入页，并带上提示消息
    header("Location: verify_code.php?success=" . urlencode("A new verification code has been sent to your email."));
    exit;

} catch (Exception $e) {
    // 重发失败，跳回并提示错误
    header("Location: verify_code.php?error=" . urlencode("Resend failed. Please check your connection."));
    exit;
}