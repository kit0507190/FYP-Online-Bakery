<?php

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require '../vendor/phpmailer/Exception.php'; 
require '../vendor/phpmailer/PHPMailer.php';
require '../vendor/phpmailer/SMTP.php';

// 2. Security check: If there is no email in the session, it indicates unauthorized access; revert to step 1.
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
    
    // --- 3. Generate a new 6-digit code and a new 10-minute expiration time ---
    $new_code = sprintf("%06d", mt_rand(1, 999999)); 
    $new_expiry = date("Y-m-d H:i:s", time() + 600); 

   
    $update_stmt = $pdo->prepare("UPDATE password_resets SET token = ?, created_at = ? WHERE email = ?");
    $update_stmt->execute([$new_code, $new_expiry, $email]);

    // --- 4. Configure PHPMailer to send new HTML emails ---
    $mail = new PHPMailer(true);
    $mail->isSMTP(); 
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->SMTPAuth   = true; 
    $mail->Username   = 'yitanglong857@gmail.com'; 
    $mail->Password   = 'ucxn rkss fgtu ahnk';     
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587; 
    $mail->CharSet    = 'UTF-8'; 

    $mail->setFrom('no-reply@bakeryhouse.com', 'Bakery House Support');
    $mail->addAddress($email); 
    $mail->isHTML(true); 
    $mail->Subject = 'Your NEW Verification Code - Bakery House';

    
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
    
   // Upon successful verification, redirect back to the verification code input page with a notification message.
    header("Location: verify_code.php?success=" . urlencode("A new verification code has been sent to your email."));
    exit;

} catch (Exception $e) {
    // Resend failed, redirected and displayed an error message.
    header("Location: verify_code.php?error=" . urlencode("Resend failed. Please check your connection."));
    exit;
}