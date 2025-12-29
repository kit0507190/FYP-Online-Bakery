<?php
require_once 'admin_auth.php';  // Handles session check and loads $current_admin with role

// Restrict this page to Super Admin only
if ($current_admin['role'] !== 'super_admin') {
    $_SESSION['error_message'] = "Access denied. This page is restricted to Super Admins only.";
    header("Location: admin_dashboard.php");
    exit();
}

require_once 'config.php';

$users = [];
try {
    $stmt = $pdo->query("SELECT * FROM user_db ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error fetching users. Please try again later.";
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM user_db WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: user_accounts.php?success=1");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error deleting user.";
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
    <header class="header">
        <h1>BakeryHouse Admin</h1>
        <a href="admin_logout.php" class="logout">Logout</a> <!-- Update to your actual logout file -->
    </header>

    <nav class="sidebar">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_products.php">Manage Products</a></li>
            <li><a href="view_orders.php">View Orders</a></li>
            <li><a href="stock_management.php">Stock Management</a></li>
            
            <?php if ($current_admin['role'] === 'super_admin'): ?>
                <li><a href="user_accounts.php" class="active">User Accounts</a></li>
            <?php endif; ?>
            
            <li><a href="reports.php">Reports</a></li>
        </ul>
    </nav>

    <main class="main">
        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                User deleted successfully!
            </div>
        <?php endif; ?>

        <div class="table-card">
            <h2>User Accounts</h2>
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
                        <tr><td colspan="5" style="text-align:center; color:#999;">No users yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                <td>
                                    <a href="?delete=<?= $user['id'] ?>" 
                                       class="action-btn delete-btn" 
                                       onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($user['name']) ?>?')">
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