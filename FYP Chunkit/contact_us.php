<?php
/**
 * contact_us.php - Optimized Version
 */
ob_start(); // Start output buffering to ensure header redirects work
session_start();
require_once 'config.php';

// 1. Get user login status and info
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
$userEmail = '';

if ($isLoggedIn) {
    $stmt = $pdo->prepare("SELECT email FROM user_db WHERE id = ?");
    $stmt->execute([$userId]);
    $u = $stmt->fetch();
    $userEmail = $u ? $u['email'] : '';
}

$success_msg = false;

// 2. Check session for success flag (used after redirect)
if (isset($_SESSION['mail_success'])) {
    $success_msg = true;
    unset($_SESSION['mail_success']); // Clear flag after displaying
}

// 3. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_message'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            // ID is handled by AUTO_INCREMENT in DB
            $sql = "INSERT INTO contact_messages (user_id, name, email, message, status) VALUES (?, ?, ?, ?, 'unread')";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$userId, $name, $email, $message])) {
                $_SESSION['mail_success'] = true;
                // Redirect to self to prevent "Confirm Form Resubmission" error
                header("Location: contact_us.php");
                exit();
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Bakery House</title>
    <link rel="stylesheet" href="contact_us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <?php if ($success_msg): ?>
    <div class="toast-overlay" id="toastOverlay">
        <div class="toast-card">
            <div class="toast-icon"><i class="fas fa-check"></i></div>
            <h3>Message Sent!</h3>
            <p>Your Message sent successfully!!</p>
            <button class="close-toast" onclick="closeToast()">Done</button>
        </div>
    </div>
    <?php endif; ?>

    <section class="hero contact-hero">
        <div class="hero-content">
            <h1 id="heroTitle">Contact Us</h1>
            <p id="heroSubtitle">We’d love to hear from you!</p>
        </div>
    </section>

    <main class="contact-page-wrapper">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info-card">
                    <div class="card-header">
                        <h2>Get in Touch <i class="fas fa-bread-slice"></i></h2>
                        <p>Have a question about our treats or need a custom cake? Let us know!</p>
                    </div>
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div><h4>Bakery Location</h4><p>No. 123, Jalan Bunga Raya, 75000 Melaka</p></div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div><h4>Email</h4><p>bakeryhouse@gmail.com</p></div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-card">
                    <form action="contact_us.php" method="POST" id="contactForm">
                        <input type="hidden" name="submit_message" value="1">
                        
                        <div class="form-row">
                            <div class="input-group">
                                <label>Name</label>
                                <input type="text" name="name" value="<?php echo $userName; ?>" required <?php echo $isLoggedIn ? 'readonly' : ''; ?>>
                            </div>
                            <div class="input-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?php echo $userEmail; ?>" required <?php echo $isLoggedIn ? 'readonly' : ''; ?>>
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Message</label>
                            <textarea name="message" rows="6" required placeholder="How can we help?"></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="contact_us.js"></script>
</body>
</html>