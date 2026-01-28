<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require '../vendor/phpmailer/Exception.php'; 
require '../vendor/phpmailer/PHPMailer.php';
require '../vendor/phpmailer/SMTP.php';


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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); 

    // Check if the email entered by the user exists in our user table.
    $stmt = $pdo->prepare("SELECT id FROM user_db WHERE email = ?");
    $stmt->execute([$email]);
    $user_exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_exists) {
        $code = sprintf("%06d", mt_rand(1, 999999)); 
        $expiry_time = date("Y-m-d H:i:s", time() + 600); // Set the expiration time as 10 minutes later.

        $pdo->beginTransaction();
        try {
            $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
            $insert_stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
            $insert_stmt->execute([$email, $code, $expiry_time]);
            $pdo->commit();


            $_SESSION['reset_email'] = $email;

        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: forgotpassword.php?message=" . urlencode("System error, please try again"));
            exit;
        }


        try {
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
            $mail->Subject = 'Verify your account - Bakery House';
            
            
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
            
            
            header("Location: verify_code.php");
            exit;

        } catch (Exception $e) {
            header("Location: forgotpassword.php?message=" . urlencode("Failed to send email. Please try again."));
            exit;
        }
    } else {
        
        header("Location: forgotpassword.php?message=" . urlencode("The email address is not registered."));
    }
}