<?php
// forgot_password_handler.php

// Import PHPMailer files
// 请确保这里的路径与你实际存放文件的位置一致：项目根目录/vendor/phpmailer/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 修正路径：由于此文件在 'user/' 文件夹内，需要 '../' 跳回到 'Bakery House FYP/' 根目录
require '../vendor/phpmailer/Exception.php'; 
require '../vendor/phpmailer/PHPMailer.php';
require '../vendor/phpmailer/SMTP.php'; // <--- 已确保分号

// 1. Database connection configuration
// ********** 请根据你的 XAMPP 配置修改这里 **********
$host = 'localhost';
$db = 'bakeryhouse'; // Your database name
$user = 'root'; 
$pass = ''; 
// **********************************************

try {
    // 已确保分号
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Database connection failed, show error message
    // 修正重定向目标为 .php
    header("Location: forgot_password.php?message=" . urlencode("System error: Database connection failed."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 已确保分号
    $email = trim($_POST['email']); 

    // 2. Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // 修正重定向目标为 .php
        header("Location: forgot_password.php?message=" . urlencode("Invalid email format."));
        exit;
    }

    // 3. Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM user_db WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ****************************************************
    // *** Core security logic and email sending ***
    // ****************************************************

    if ($user) {
        // A. Generate and store Token
        $token = bin2hex(random_bytes(32)); 
        // Set Token expiry time to 30 minutes (current time + 1800 seconds)
        $expiry_time = date("Y-m-d H:i:s", time() + 1800); 
        
        // Delete old requests and insert new request (prevent multiple tokens interfering)
        $pdo->beginTransaction();
        try {
            $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $delete_stmt->execute([$email]);
            
            $insert_stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
            $insert_stmt->execute([$email, $token, $expiry_time]);
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            // Storage failed, show generic error to user
            header("Location: forgot_password.php?message=" . urlencode("System error, please try again later."));
            exit;
        }

        // B. Send reset link email
        // 修正为完整的本地 URL 路径 (假设 resetpassword.php 也在 user 文件夹内)
        $reset_link = "http://localhost/Bakery House FYP/user/resetpassword.php?token=" . $token;
        
        try {
            $mail = new PHPMailer(true);
            
            // SMTP configuration (your Gmail settings)
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'yitanglong857@gmail.com'; 
            $mail->Password   = 'ucxn rkss fgtu ahnk';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587; 
            $mail->CharSet    = 'UTF-8'; 

            // Email headers and body (内容保持不变)
            $mail->setFrom('no-reply@bakeryhouse.com', 'Bakery House Password Reset');
            $mail->addAddress($email); 
            $mail->isHTML(true); 
            $mail->Subject = 'Bakery House Password Reset Request';
            $mail->Body    = "
                <h1>Reset Your Bakery House Password</h1>
                <p>You have requested a password reset. Please click the button below to complete the process. This link will expire in 30 minutes.</p>
                <p>If the button in this email doesn't work, please copy and paste the following link into your browser:<br> <small>{$reset_link}</small></p>
                <p style='text-align: center; margin-top: 20px;'>
                    <a href='{$reset_link}' style='display: inline-block; padding: 10px 20px; background-color: #f6b319; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                        Click Here to Reset Password
                    </a>
                </p>
                <p>If you did not request this, please ignore this email.</p>
            ";

            $mail->send();
            
            // 邮件发送成功后，给出模糊反馈
            $success_message = "If your email exists, we have sent a reset link. Please check your email (including spam folder).";

        } catch (Exception $e) {
            // 邮件发送失败，仍然返回成功的模糊消息
            $success_message = "If your email exists, we have sent a reset link. Please check your email (including spam folder).";
        }
        
    } else {
        // 用户不存在，仍然返回成功的模糊消息
        $success_message = "If your email exists, we have sent a reset link. Please check your email (including spam folder).";
    }

    // 4. 重定向到前端页面并显示模糊的成功消息
    header("Location: forgot_password.php?message=" . urlencode($success_message));
    exit;
}
?>