<?php

session_start();

if (!isset($_SESSION['code_verified']) || $_SESSION['code_verified'] !== true) {
    header("Location: forgotpassword.php?message=" . urlencode("Please verify your code first."));
    exit;
}


$host = 'localhost';
$db   = 'bakeryhouse'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('System error: Database connection failed.');
}

$error = '';
$valid_token = false;
$email = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $expiry_time = strtotime($reset_request['created_at']);
        if (time() <= $expiry_time) { 
            $valid_token = true;
            $email = $reset_request['email']; 
        } else {
            $error = 'Your session has expired. Please request a new code.';
        }
    } else {
        $error = 'Invalid request. Please try the process again.';
    }
} else {
    $error = 'Security token missing.';
}

if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password - Bakery House</title>
    <link rel="stylesheet" href="resetpassword.css">
</head>
<body>
    <div class="container">
        <h1>Set New Password</h1>
        
        <?php if ($error): ?>
            <div class="error-message">⚠️ <?= $error ?></div>
        <?php endif; ?>

        <?php if ($valid_token): ?>
            <div class="account-info">
                Resetting password for:<br><strong><?= htmlspecialchars($email) ?></strong>
            </div>
            
            <form action="reset_password_submit.php" method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" id="password" name="password" placeholder="8+ chars (letters & numbers)" required>
                    <span class="password-toggle" onclick="togglePass('password', this)">Show</span>
                    <span id="password-js-error" class="js-error"></span>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                    <span class="password-toggle" onclick="togglePass('confirm_password', this)">Show</span>
                </div>
                
                <button type="submit" class="btn">Update Password</button>
            </form>
        <?php else: ?>
            <p style="margin-top:20px;"><a href="forgotpassword.php" style="color: #d4a76a; font-weight:bold;">Return and get a new code</a></p>
        <?php endif; ?>
    </div>

    <script src="resetpassword.js"></script>
</body>
</html>