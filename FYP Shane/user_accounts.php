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
try {
    $stmt = $pdo->query("
        SELECT * FROM user_db 
        WHERE deleted_at IS NULL 
        ORDER BY created_at DESC
    ");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error fetching users. Please try again later.";
}

// Handle delete request
// Handle SOFT delete request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("
                UPDATE user_db 
                SET deleted_at = NOW()
                -- , deleted_by = ?    ← uncomment if you add the column
                WHERE id = ? 
                  AND deleted_at IS NULL
            ");
            // $stmt->execute([$current_admin['id'], $id]);   ← if using deleted_by
            $stmt->execute([$id]);

            header("Location: user_accounts.php?success=1");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error deleting user: " . $e->getMessage();
        }
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

        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php" class="active">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert error" style="padding:1rem; background:#ffebee; color:#c62828; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success" style="padding:1rem; background:#d4edda; color:#155724; border-radius:8px; margin-bottom:2rem;">
            User deleted successfully!
        </div>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:6rem; color:#999;">
                            <i class="fas fa-users fa-3x" style="color:#ddd; margin-bottom:1rem;"></i><br>
                            No active registered customers found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></td>
                            <td>
                                <a href="?delete=<?= $user['id'] ?>" 
                                   class="action-btn delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars(addslashes($user['name'])) ?>?\nThis user can be restored later if needed.')">
                                    Delete
                                </a>
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