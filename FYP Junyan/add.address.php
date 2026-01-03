<?php
/**
 * add_address.php - 添加新地址页面
 */
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');

    if (empty($address_area)) { $errors[] = "Please select an area."; }
    if (empty($address_postcode)) { $errors[] = "Postcode is required."; }
    if (empty($address_line)) { $errors[] = "Street address is required."; }

    if (empty($errors)) {
        try {
            // 拼接格式：area|postcode|line[|other_area]
            $fullAddress = $address_area . '|' . $address_postcode . '|' . $address_line;
            if ($address_area === 'other' && !empty($other_area)) {
                $fullAddress .= '|' . $other_area;
            }

            $sql = "INSERT INTO user_addresses (user_id, address_text, is_default) VALUES (?, ?, 0)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $fullAddress]);

            header("Location: manageaddress.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Address - Bakery House</title>
    <link rel="stylesheet" href="add.address.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <?php include 'header.php'; ?>

    <div class="message-container">
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <main class="profile-page">
        <div class="profile-container">
            
            <div class="back-navigation">
                <a href="manageaddress.php" class="back-link">
                    <i class="fas fa-chevron-left"></i> Back to Manage Addresses
                </a>
            </div>

            <div class="profile-header">
                <h1>Add New Address</h1>
                <p>Provide your delivery details below</p>
            </div>

            <form action="add_address.php" method="POST" class="edit-form" id="addressForm">
                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Delivery Address</h2>
                    
                    <div class="form-row">
                        <div class="form-group-half required-field">
                            <label class="form-label">Area</label>
                            <select id="address_area" name="address_area" class="form-input" required onchange="toggleOtherArea()">
                                <option value="">-- Select Area --</option>
                                <option value="Bandar Melaka">Bandar Melaka</option>
                                <option value="Ayer Keroh">Ayer Keroh</option>
                                <option value="Bukit Beruang">Bukit Beruang</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group-half required-field">
                            <label class="form-label">Postcode</label>
                            <input type="text" name="address_postcode" class="form-input" required placeholder="e.g., 75000">
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3" placeholder="House No, Street Name, etc."></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: none;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" placeholder="Enter your specific area">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Address
                    </button>
                    <a href="manageaddress.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

     <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </div>
                <p>Sweet & Delicious</p>
                <div class="footer-links">
                    <a href="mainpage.php">Home</a>
                    <a href="menu.php">Menu</a>
                    <a href="about_us.php">About</a>
                    <a href="contact_us.php">Contact</a>
                    <a href="privacypolicy.php">Privacy Policy</a>
                    <a href="termservice.php">Terms of Service</a>
                </div>
                <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    function toggleOtherArea() {
        const areaSelect = document.getElementById('address_area');
        const otherGroup = document.getElementById('other_area_group');
        otherGroup.style.display = (areaSelect.value === 'other') ? 'block' : 'none';
    }
    </script>
</body>
</html>