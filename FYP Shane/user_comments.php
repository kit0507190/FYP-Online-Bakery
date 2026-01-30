<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin with role

require_once 'admin_config.php';

$messages = [];
try {
    $stmt = $pdo->query("
        SELECT cm.*, 
               u.name AS user_name, 
               u.email AS user_email
        FROM contact_messages cm
        LEFT JOIN user_db u ON cm.user_id = u.id
        ORDER BY cm.created_at DESC
    ");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error fetching contact messages: " . $e->getMessage();
}

// Handle Mark as Read
if (isset($_GET['mark_read'])) {
    $id = (int)$_GET['mark_read'];
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ? AND status = 'unread'");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['success_message'] = "Message marked as read.";
            }
            header("Location: user_comments.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error updating message status.";
        }
    }
}

// Handle Mark as Replied
if (isset($_GET['mark_replied'])) {
    $id = (int)$_GET['mark_replied'];
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'replied' WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['success_message'] = "Message marked as replied.";
            }
            header("Location: user_comments.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error updating message status.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Contact Messages</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .message-cell { 
            max-width: 300px; 
            white-space: pre-wrap; 
            word-wrap: break-word; 
        }
        .status-unread { color: #d32f2f; font-weight: bold; }
        .status-read   { color: #1976d2; }
        .status-replied { color: #388e3c; font-weight: bold; }
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
        <li><a href="user_comments.php" class="active">User Comments</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            
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

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert success" style="padding:1rem; background:#d4edda; color:#155724; border-radius:8px; margin-bottom:2rem;">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <div class="table-card">
        <h2>Contact Messages</h2>
        <table id="messagesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sender</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:6rem; color:#999;">
                            <i class="fas fa-envelope fa-3x" style="color:#ddd; margin-bottom:1rem;"></i><br>
                            No contact messages found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr class="<?= $msg['status'] === 'unread' ? 'unread-row' : '' ?>">
                            <td><?= htmlspecialchars($msg['id']) ?></td>
                            <td>
                                <?= htmlspecialchars($msg['name']) ?>
                                <?php if ($msg['user_id']): ?>
                                    <br><small>(User #<?= $msg['user_id'] ?>)</small>
                                <?php else: ?>
                                    <br><small><em>Guest</em></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td class="message-cell"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                            <td><?= date('d M Y, H:i', strtotime($msg['created_at'])) ?></td>
                            <td class="status-<?= $msg['status'] ?>">
                                <?= ucfirst($msg['status']) ?>
                            </td>
                            <td>
                                <?php if ($msg['status'] === 'unread'): ?>
                                    <a href="?mark_read=<?= $msg['id'] ?>" 
                                       class="action-btn" 
                                       style="background:#1976d2; color:white;"
                                       onclick="return confirm('Mark this message as read?')">
                                        Mark Read
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($msg['status'] !== 'replied'): ?>
                                    <a href="?mark_replied=<?= $msg['id'] ?>" 
                                       class="action-btn" 
                                       style="background:#388e3c; color:white;"
                                       onclick="return confirm('Mark this message as replied?')">
                                        Mark Replied
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