<?php

session_start();

// --- 1. Login Check ---
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php"); 
    exit();
}

// --- 2. Importing a database connection ---
require_once 'config.php';

$userId = $_SESSION['user_id'];

try {
    // Fetch user basic information
    $query = "SELECT name, email, phone, created_at FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? 'Not provided');
        $memberSince = date("F j, Y", strtotime($user['created_at']));
        
        
        $addrQuery = "SELECT address_text FROM user_addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
        $addrStmt = $pdo->prepare($addrQuery);
        $addrStmt->execute([$userId]);
        $defaultAddr = $addrStmt->fetch(PDO::FETCH_ASSOC);

        $address_display = "No default address set";

        if ($defaultAddr) {
            $raw_address = $defaultAddr['address_text'];

            // Format address based on delimiter type
            if (strpos($raw_address, '|') !== false) {
                $parts = explode('|', $raw_address);
                $addrLine = htmlspecialchars($parts[0] ?? '');
                $addrArea = htmlspecialchars($parts[1] ?? '');
                $addrPost = htmlspecialchars($parts[2] ?? '');
                $address_display = "$addrLine<br>$addrArea, $addrPost<br>Malacca, Malaysia";
            } 
            
            elseif (strpos($raw_address, ', ') !== false) {
                $address_display = nl2br(htmlspecialchars($raw_address));
            } 
            else {
                $address_display = htmlspecialchars($raw_address);
            }
        }
        
        $isLoggedIn = true;
        $userName = $user['name'];
    } else {
        session_destroy();
        header("Location: User_Login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Bakery House</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>
    
    <?php include 'header.php'; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    <div class="toast-overlay" id="toastOverlay">
        <div class="toast-card">
            <div class="toast-icon"><i class="fas fa-check"></i></div>
            <h3>Profile Updated!</h3>
            <p>Your account information has been updated successfully.</p>
            <button class="close-toast" onclick="closeToast()">Done</button>
        </div>
    </div>
    <?php endif; ?>

    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1>My Profile</h1>
                <p>View and manage your account information</p>
            </div>

            <div class="info-card">
                <h2><i class="fas fa-user-circle"></i> Account Information</h2>
                <div class="info-row">
                    <div class="info-label">FULL NAME:</div>
                    <div class="info-value"><?php echo $name; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">EMAIL:</div>
                    <div class="info-value"><?php echo $email; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">PHONE:</div>
                    <div class="info-value"><?php echo $phone; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">MEMBER SINCE:</div>
                    <div class="info-value"><?php echo $memberSince; ?></div>
                </div>
            </div>

            <div class="info-card">
                <div class="address-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="border-bottom: none; margin-bottom: 0;"><i class="fas fa-map-marker-alt"></i> Default Address</h2>
                    <a href="manageaddress.php" class="btn-manage-address" style="text-decoration: none; color: #d4a76a; font-weight: bold;">
                        <i class="fas fa-cog"></i> Manage
                    </a>
                </div>
                <div class="info-row">
                    <div class="info-label">ADDRESS:</div>
                    <div class="info-value" style="line-height: 1.6;"><?php echo $address_display; ?></div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="editprofile.php" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>

                <a href="changepassword.php" class="btn btn-change-password">
                    <i class="fas fa-key"></i> Change Password
                </a>

                <a href="logout.php" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="profile.js"></script>
</body>
</html>