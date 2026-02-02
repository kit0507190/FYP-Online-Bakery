<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin with role

// Restrict this page to Super Admin only
if ($current_admin['role'] !== 'super_admin') {
    $_SESSION['error_message'] = "Access denied. This page is restricted to Super Admins only.";
    header("Location: admin_dashboard.php");
    exit();
}

require_once 'admin_config.php';

$users = [];
$error_message = '';

try {
    $stmt = $pdo->query("
        SELECT * FROM user_db 
        ORDER BY status ASC, created_at DESC
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
    // In production: error_log("User fetch error: " . $e->getMessage());
}

// Handle Deactivate
if (isset($_GET['deactivate'])) {
    $id = (int)$_GET['deactivate'];
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("
                UPDATE user_db 
                SET status = 'inactive' 
                WHERE id = ? AND status = 'active'
            ");
            $stmt->execute([$id]);
            $updated = $stmt->rowCount();

            if ($updated > 0) {
                $_SESSION['success_message'] = "User account deactivated successfully.";
            } else {
                $_SESSION['error_message'] = "Cannot deactivate: Account is not active or does not exist.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error while deactivating user.";
            // error_log("Deactivate error: " . $e->getMessage());
        }
        header("Location: user_accounts.php");
        exit();
    }
}

// Handle Reactivate
if (isset($_GET['reactivate'])) {
    $id = (int)$_GET['reactivate'];
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("
                UPDATE user_db 
                SET status = 'active' 
                WHERE id = ? AND status = 'inactive'
            ");
            $stmt->execute([$id]);
            $updated = $stmt->rowCount();

            if ($updated > 0) {
                $_SESSION['success_message'] = "User account reactivated successfully.";
            } else {
                $_SESSION['error_message'] = "Cannot reactivate: Account is not inactive or does not exist.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error while reactivating user.";
            // error_log("Reactivate error: " . $e->getMessage());
        }
        header("Location: user_accounts.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | User Accounts</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <li><a href="user_comments.php">User Comments</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php" class="active">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">

    <?php if (!empty($error_message)): ?>
        <div class="alert error" style="padding:1rem; background:#ffebee; color:#c62828; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert error" style="padding:1rem; background:#ffebee; color:#c62828; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert success" style="padding:1rem; background:#d4edda; color:#155724; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <div class="table-card">
        <h2>Customer User Accounts</h2>

        <table id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:6rem; color:#999;">
                            <i class="fas fa-users fa-3x" style="color:#ddd; margin-bottom:1rem;"></i><br>
                            No registered customers found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="<?= $user['status'] === 'inactive' ? 'inactive-row' : '' ?>">
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></td>
                            <td class="<?= $user['status'] === 'inactive' ? 'inactive' : 'active' ?>">
                                <?= ucfirst($user['status']) ?>
                            </td>
                            <td>
                                <?php if ($user['status'] === 'active'): ?>
                                    <a href="?deactivate=<?= $user['id'] ?>" 
                                       class="action-btn delete-btn"
                                       onclick="return confirm('Deactivate <?= htmlspecialchars(addslashes($user['name'])) ?>?\nThey will no longer be able to log in or place orders.')">
                                        Deactivate
                                    </a>
                                <?php else: ?>
                                    <a href="?reactivate=<?= $user['id'] ?>" 
                                       class="action-btn" 
                                       style="background:#28a745; color:white;"
                                       onclick="return confirm('Reactivate <?= htmlspecialchars(addslashes($user['name'])) ?>?')">
                                        Reactivate
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>