<?php
/**
 * editprofile.php - 编辑个人资料页面 (已剥离地址修改功能)
 */
session_start();

// 1. 验证登录
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

// 2. 引入数据库配置
require_once 'config.php';

if (!isset($pdo)) {
    die("Database connection failed.");
}

$userId = $_SESSION['user_id'];
$errors = [];
$name = $email = $phone = '';

// 3. 获取当前用户的资料信息 (仅获取姓名、邮箱、电话)
try {
    $query = "SELECT name, email, phone FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? '');
        
        // 设置给 header.php 使用的变量
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

// 4. 处理表单提交更新请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

  // --- 后端基础验证 ---
    if (empty($name)) { 
        $errors[] = "Full name is required."; 
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Full name can only contain letters and spaces.";
    }

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    } else {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        if ($domain !== 'gmail.com') {
            $errors[] = "Invalid email address format，Only @gmail.com accounts are allowed.";
        }
    }

    // --- 电话号码验证 (马来西亚格式) ---
    if (!empty($phone)) {
        // ^01 表示必须以 01 开头
        // [0-9]{8,9} 表示后面跟着 8 到 9 位数字 (总长就是 10-11位)
        if (!preg_match("/^01[0-9]{8,9}$/", $phone)) {
            $errors[] = "Phone number must start with '01' and be 10-11 digits long.";
        }
    }

    // 5. 如果没有错误，执行更新 (仅更新基本资料)
    if (empty($errors)) {
        try {
            $updateQuery = "UPDATE user_db SET name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([$name, $email, $phone, $userId]);

            // 更新 Session 中的名字
            $_SESSION['user_name'] = $name;

            // 修改成功后跳回 profile.php 并触发弹窗
            header("Location: profile.php?success=1");
            exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Bakery House</title>
    <link rel="stylesheet" href="editprofile.css?v=1.1">
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
            <div class="profile-header">
                <h1>Edit Profile</h1>
                <p>Update your personal information below</p>
            </div>

            <form action="editprofile.php" method="POST" class="edit-form" id="profileForm">
                <div class="info-card">
                    <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Full Name</label>
                         <input type="text" name="name" class="form-input" value="<?php echo $name; ?>" 
                                 pattern="[a-zA-Z\s]+" title="Only letters and spaces allowed" required>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="<?php echo $email; ?>" required>
                    </div>
                    
                    <div class="form-group">
                         <label class="form-label">Phone Number</label>
                         <input type="tel" name="phone" class="form-input" 
                                value="<?php echo $phone; ?>" 
                                placeholder="e.g., 0123456789"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                                maxlength="11">
                        <small style="color: #666;">Must start with 01 (e.g., 0123456789)</small>
                    </div>
                </div>

                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Delivery Address</h2>
                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">
                        To provide better service, your delivery addresses are now managed in a dedicated Address Book. 
                        You can add multiple addresses or update your default location there.
                    </p>
                    <a href="manageaddress.php" class="btn btn-manage-redirect">
                        <i class="fas fa-external-link-alt"></i> Go to Address Book
                    </a>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="editprofile.js"></script>
</body>
</html>