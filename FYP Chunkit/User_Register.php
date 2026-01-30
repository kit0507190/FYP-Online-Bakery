<?php
require_once 'config.php';

$errors = [];
$name = ""; 
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm  = $_POST["confirmPassword"] ?? '';
    $agree    = isset($_POST["agreeTerms"]);
    
    // Validate Full Name: Required, alphabetic only, and length check
    if (empty($name)) {
        $errors[] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Full name can only contain letters and spaces.";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }

    // Validate Email: Format check and domain restriction (@gmail.com,@student.mmu.edu.my,@yahoo.com,@hotmail.com only)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    } else {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        $allowed_domains = ['gmail.com', 'student.mmu.edu.my', 'yahoo.com', 'hotmail.com'];

        if (!in_array($domain, $allowed_domains)) {
            $errors[] = "Invalid email address format.Only @gmail.com, @student.mmu.edu.my, @yahoo.com, and @hotmail.com are allowed.";
        }
    }

    // Validate Password strength: Minimum 8 chars with letters and numbers
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be 8+ chars with letters & numbers.";
    }

    // Ensure password and confirmation match
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check terms acceptance
    if (!$agree) {
        $errors[] = "You must agree to the terms and privacy policy.";
    }

    if (empty($errors)) {
        try {
            // Check if the email already exists in the database
            $check = $pdo->prepare("SELECT id FROM user_db WHERE email = ?");
            $check->execute([$email]);
            
            if ($check->rowCount() > 0) {
                $errors[] = "This email is already registered.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO user_db (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed]);

                header("Location: User_Login.php?registered=1");
                exit();
            }
        } catch (Exception $e) {
            $errors[] = "Registration failed. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery House - Registration</title>
    <link rel="stylesheet" href="User_Register.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div class="logo-content">
                <div class="logo-image">
                    <img src="Bakery House Logo.png" alt="Bakery House Logo">
                </div>
                <h2 class="logo-text">Bakery House</h2>
                <p class="tagline">Sweet & Delicious</p>
                <div class="slogan-box">
                    <p class="slogan">The Warmth of Home, The Taste of Baking.</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h1>Create An Account</h1>
                <p>Join our Bakery House community!</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-container">
                    <ul class="error-list">
                        <?php foreach ($errors as $e): ?>
                            <li><span class="error-icon">⚠️</span> <?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="POST" id="registerForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="nameInput" value="<?= htmlspecialchars($name) ?>" required placeholder="Enter your full name">
                    <span class="error-msg" id="nameError"></span>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="emailInput" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter your email">
                    <span class="error-msg" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" required placeholder="8+ chars (letters & numbers)">
                    <span class="password-toggle" id="togglePassword">Show</span>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required placeholder="Confirm your password">
                    <span class="password-toggle" id="toggleConfirmPassword">Show</span>
                    <span class="error-msg" id="confirmError"></span>
                </div>

                <div class="terms-group">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" <?= isset($_POST['agreeTerms']) ? 'checked' : '' ?> required>
                    <label for="agreeTerms">I agree to the <a href="#" id="termsLink">Terms of Service</a> and <a href="#" id="privacyLink">Privacy Policy</a></label>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>

                <div class="login-link">
                    Already have an account? <a href="User_Login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <div id="policyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="policyTitle">Terms of Service</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body" id="policyBody"></div>
            <div class="modal-footer">
                <button class="btn-close-modal" id="modalCloseBtn">Understood</button>
            </div>
        </div>
    </div>

    <script src="User_Register.js"></script>
</body>
</html>