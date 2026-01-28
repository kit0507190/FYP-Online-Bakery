<?php
session_start();
require_once 'config.php';

// 1. Verify login
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$errors = [];

// 2. Handling form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');

    $postcode_map = [
        "Bandar Melaka" => ["75000", "75100", "75200", "75300"],
        "Ayer Keroh"    => ["75450"],
        "Bukit Beruang" => ["75450"]
    ];

    if (empty($address_area)) { 
        $errors[] = "Please select an area."; 
    }
    
    if (!preg_match("/^[0-9]{5}$/", $address_postcode)) {
        $errors[] = "Postcode must be exactly 5 digits.";
    } 
    elseif ($address_area !== 'other' && isset($postcode_map[$address_area])) {
        if (!in_array($address_postcode, $postcode_map[$address_area])) {
            $errors[] = "The postcode $address_postcode does not match the selected area ($address_area).";
        }
    }

    if (empty($address_line)) { 
        $errors[] = "Street address is required."; 
    }
    
    if ($address_area === 'other' && empty($other_area)) {
        $errors[] = "Please specify your area name.";
    }

    if (empty($errors)) {
        try {
            $display_area = ($address_area === 'other') ? $other_area : $address_area;
            $fullAddress = $address_line . "|" . $display_area . "|" . $address_postcode . "|" . $other_area;

            $countQuery = "SELECT COUNT(*) FROM user_addresses WHERE user_id = ?";
            $countStmt = $pdo->prepare($countQuery);
            $countStmt->execute([$userId]);
            $addressCount = $countStmt->fetchColumn();
            $final_is_default = ($addressCount == 0) ? 1 : 0;

            $sql = "INSERT INTO user_addresses (user_id, address_text, is_default, updated_at) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $fullAddress, $final_is_default]);

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
    <title>Edit Address - Bakery House</title>
    <link rel="stylesheet" href="add.address.css?v=<?php echo time(); ?>"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
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

            <form action="add.address.php" method="POST" class="edit-form" id="addressForm">
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

                        <div class="form-group-half required-field postcode-wrapper">
                            <label class="form-label">Postcode</label>
                            
                            <div id="postcode-hint">
                                <i class="fas fa-info-circle"></i> Valid: <span id="hint-text"></span>
                            </div>

                            <input type="text" name="address_postcode" id="address_postcode" class="form-input" 
                                   required placeholder="e.g., 75000" maxlength="5">
    
                            <span id="postcode-error"></span>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3" placeholder="No 1, Jalan Bakery..."></textarea>
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
                    <a href="manageaddress.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="add.address.js"></script>
</body>
</html>