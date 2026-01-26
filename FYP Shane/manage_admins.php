<?php
// admin/manage_admins.php

require_once 'admin_auth.php';  // Loads $current_admin and checks login

// Restrict to Super Admin only
if ($current_admin['role'] !== 'super_admin') {
    $_SESSION['error_message'] = "Access denied. Only Super Admins can manage admin accounts.";
    header("Location: admin_dashboard.php");
    exit();
}

require_once 'admin_config.php';

$error_message = '';
$success_message = '';

// Handle Add New Admin
if (isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        $check = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        if ($check->rowCount() > 0) {
            $error_message = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            try {
                $stmt = $pdo->prepare("INSERT INTO admins (username, email, password, role, status) VALUES (?, ?, ?, 'admin', 'active')");
                $stmt->execute([$username, $email, $hashed_password]);
                $success_message = "New Normal Admin created successfully!";
            } catch (PDOException $e) {
                $error_message = "Error adding admin.";
            }
        }
    }
}

// Handle Update Admin
if (isset($_POST['update_admin'])) {
    $id = (int)$_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    if ($id == $current_admin['id']) {
        $error_message = "You cannot modify your own role or status here.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, role = ?, status = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $status, $id]);
            $success_message = "Admin updated successfully!";
        } catch (PDOException $e) {
            $error_message = "Error updating admin.";
        }
    }
}

// Handle Password Reset
if (isset($_POST['reset_password'])) {
    $id = (int)$_POST['id'];
    $new_password = $_POST['new_password'];

    if (strlen($new_password) < 6) {
        $error_message = "Password must be at least 6 characters.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
        try {
            $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $id]);
            $success_message = "Password reset successfully!";
        } catch (PDOException $e) {
            $error_message = "Error resetting password.";
        }
    }
}

// Handle Deactivate Admin (instead of delete)
if (isset($_GET['deactivate'])) {
    $id = (int)$_GET['deactivate'];
    if ($id == $current_admin['id']) {
        $error_message = "You cannot deactivate your own account!";
    } else {
        try {
            $pdo->prepare("UPDATE admins SET status = 'inactive' WHERE id = ?")->execute([$id]);
            $success_message = "Admin account deactivated successfully!";
        } catch (PDOException $e) {
            $error_message = "Error deactivating admin.";
        }
    }
}

// Handle Reactivate Admin
if (isset($_GET['reactivate'])) {
    $id = (int)$_GET['reactivate'];
    if ($id == $current_admin['id']) {
        $error_message = "Your own account is already active.";
    } else {
        try {
            $pdo->prepare("UPDATE admins SET status = 'active' WHERE id = ?")->execute([$id]);
            $success_message = "Admin account reactivated successfully!";
        } catch (PDOException $e) {
            $error_message = "Error reactivating admin.";
        }
    }
}

// Fetch all admins
$admins = $pdo->query("SELECT * FROM admins ORDER BY status ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Manage Admins</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form-card, .table-card { margin-bottom: 3rem; }
        .admin-role { font-weight: bold; }
        .super { color: #d4af37; }
        .normal { color: #28a745; }
        .inactive { color: #dc3545; opacity: 0.7; font-style: italic; }
        .action-btn { padding: 0.5rem 1rem; margin: 0.2rem; font-size: 0.9rem; }
        .edit-form { background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-top: 1rem; }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<nav class="sidebar">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
        <li><a href="view_orders.php">View Orders</a></li>
        <li><a href="stock_management.php">Stock Management</a></li>
        <li><a href="admin_restore.php">Restore Deleted</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
        <li><a href="user_accounts.php">User Accounts</a></li>
        <li><a href="manage_admins.php" class="active">Manage Admins</a></li>
        <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">
    <h1 class="page-title">Manage Admin Accounts</h1>

    <?php if (!empty($error_message)): ?>
        <div class="alert error" style="padding:1rem; background:#ffebee; color:#c62828; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert success" style="padding:1rem; background:#d4edda; color:#155724; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <!-- Add New Admin Form -->
    <div class="form-card">
        <h2>Add New Normal Admin</h2>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="e.g., johnstaff">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="staff@bakeryhouse.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="At least 6 characters">
                </div>
            </div>
            <button type="submit" name="add_admin" class="add-btn">Create Admin Account</button>
        </form>
    </div>

    <!-- Admin List -->
    <div class="table-card">
        <h2>Current Admin Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= $admin['id'] ?></td>
                        <td><?= htmlspecialchars($admin['username']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td class="admin-role <?= $admin['role'] === 'super_admin' ? 'super' : 'normal' ?>">
                            <?= ucfirst(str_replace('_', ' ', $admin['role'])) ?>
                        </td>
                        <td class="<?= $admin['status'] === 'inactive' ? 'inactive' : '' ?>">
                            <?= ucfirst($admin['status']) ?>
                        </td>
                        <td><?= date('d M Y', strtotime($admin['created_at'])) ?></td>
                        <td>
                            <?php if ($admin['id'] != $current_admin['id']): ?>
                                <!-- Edit -->
                                <details style="display:inline-block;">
                                    <summary class="action-btn" style="background:#007bff; color:white; cursor:pointer;">Edit</summary>
                                    <div class="edit-form">
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required style="width:100%; margin-bottom:0.5rem;">
                                            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required style="width:100%; margin-bottom:0.5rem;">
                                            <select name="role" style="width:100%; margin-bottom:0.5rem;">
                                                <option value="admin" <?= $admin['role'] === 'admin' ? 'selected' : '' ?>>Normal Admin</option>
                                                <option value="super_admin" <?= $admin['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                            </select>
                                            <select name="status" style="width:100%; margin-bottom:0.5rem;">
                                                <option value="active" <?= $admin['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                                <option value="inactive" <?= $admin['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                            <button type="submit" name="update_admin" class="action-btn" style="background:#28a745;">Save Changes</button>
                                        </form>

                                        <!-- Password Reset -->
                                        <form method="POST" style="margin-top:1rem;">
                                            <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                            <input type="password" name="new_password" placeholder="New Password (min 6 chars)" minlength="6">
                                            <button type="submit" name="reset_password" class="action-btn" style="background:#ffc107; margin-top:0.5rem;">Reset Password</button>
                                        </form>
                                    </div>
                                </details>

                                <!-- Deactivate Button -->
                                <?php if ($admin['id'] != $current_admin['id']): ?>
                                    <?php if ($admin['status'] === 'active'): ?>
                                    <a href="?deactivate=<?= $admin['id'] ?>" 
                                        onclick="return confirm('Deactivate this admin account? They will no longer be able to log in.')" 
                                        class="action-btn delete-btn">Deactivate</a>
                                <?php else: ?>
                                    <a href="?reactivate=<?= $admin['id'] ?>" 
                                        onclick="return confirm('Reactivate this admin account? They will be able to log in again.')" 
                                        class="action-btn" style="background:#28a745; color:white;">Reactivate</a>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php else: ?>
                                <em>You (current)</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>