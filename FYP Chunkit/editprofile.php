<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

require_once 'config.php';

$userId = $_SESSION['user_id'];
$errors = [];
$name = $email = $phone = '';

// 2. Fetch current user data from database
try {
    $query = "SELECT name, email, phone FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? '');
    } else {
        session_destroy();
        header("Location: User_Login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 3. Process Profile Update Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Name validation: Required and alphabet only
    if (empty($name)) { 
        $errors[] = "Full name is required."; 
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Full name can only contain letters and spaces.";
    }

    // Email validation: Format and Domain check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    } else {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        $allowed_domains = ['gmail.com', 'student.mmu.edu.my', 'yahoo.com', 'hotmail.com'];
        
        if (!in_array($domain, $allowed_domains)) {
            $errors[] = "Only @gmail.com, @student.mmu.edu.my, @yahoo.com and @hotmail.com are allowed.";
        }
    }

    // Phone number validation: Malaysian format check
    if (!empty($phone) && !preg_match("/^01[0-9]{8,9}$/", $phone)) {
        $errors[] = "Phone number must start with '01' and be 10-11 digits long.";
    }

    // 4. Update Database if no errors
    if (empty($errors)) {
        try {
            $updateQuery = "UPDATE user_db SET name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?";
            $updateStmt = $pdo->prepare($updateQuery);
            if ($updateStmt->execute([$name, $email, $phone, $userId])) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email; 
                header("Location: profile.php?success=1");
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = "Update failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Bakery House</title>
    <link rel="stylesheet" href="editprofile.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Edit Profile</h1>
                <p>Update your personal information below</p>
            </div>

            <form action="editprofile.php" method="POST" class="edit-form" id="profileForm" novalidate>
                <div id="js-error-container" class="message-container">
                    <?php if (!empty($errors)): ?>
                        <div class="error-message">
                            <ul style="margin: 0; padding-left: 20px; list-style: none;">
                                <?php foreach ($errors as $error): ?>
                                    <li><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="info-card">
                    <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    <div class="form-group required-field">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" value="<?php echo $name; ?>" required>
                    </div>
                    <div class="form-group required-field">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                         <label class="form-label">Phone Number</label>
                         <input type="tel" name="phone" class="form-input" value="<?php echo $phone; ?>" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </div>

                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Delivery Address</h2>
                    <p style="color: #666;">To provide better service, your delivery addresses are managed in a dedicated Address Book.</p>
                    <a href="manageaddress.php" class="btn btn-manage-redirect"><i class="fas fa-external-link-alt"></i> Go to Address Book</a>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="profile.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="editprofile.js"></script>
</body>
</html>