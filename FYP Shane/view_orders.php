<?php
require_once 'admin_auth.php';  // Secure login + loads $current_admin with role
require_once 'admin_config.php';  // Main database connection

// =============================================
// HANDLE STATUS UPDATE (POST) → then REDIRECT
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id   = (int)$_POST['order_id'];
    $new_status = trim($_POST['new_status']);

    $valid_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
    if (!in_array($new_status, $valid_statuses)) {
        // In real production you might want better error handling
        header("Location: view_orders.php?error=invalid_status");
        exit();
    }

    // Get current (old) status
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $old_status = $stmt->fetchColumn() ?: '';

    // Update status
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    $stock_deducted = false;

    // Deduct stock only when going TO delivered (and wasn't already delivered)
    if ($new_status === 'delivered' && $old_status !== 'delivered') {
        $items_stmt = $pdo->prepare("SELECT product_id, quantity FROM orders_detail WHERE order_id = ?");
        $items_stmt->execute([$order_id]);
        $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $qty = (int)($item['quantity'] ?? 0);
            $product_id = (int)($item['product_id'] ?? 0);
            if ($qty > 0 && $product_id > 0) {
                $pdo->prepare("UPDATE products SET stock = GREATEST(stock - ?, 0) WHERE id = ?")
                    ->execute([$qty, $product_id]);
                $stock_deducted = true;
            }
        }
    }

    // Redirect with success message (prevents resubmit warning)
    $redirect = "view_orders.php?status_updated=1";
    if ($stock_deducted) {
        $redirect .= "&stock_deducted=1";
    }
    header("Location: $redirect");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | View Orders</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .page-title { margin-bottom: 1.5rem; color: #5a3921; }
        .controls { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .search-box { padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; width: 300px; max-width: 100%; }
        .filter-select { padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; background: white; }
        .status { padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; text-transform: capitalize; }
        .status.pending    { background: #fff3cd; color: #856404; }
        .status.preparing  { background: #d1ecf1; color: #0c5460; }
        .status.ready      { background: #d4edda; color: #155724; }
        .status.delivered  { background: #c3e6cb; color: #0f5132; }
        .status.cancelled  { background: #f8d7da; color: #721c24; }
        #ordersTable img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
        .date-time-col { white-space: nowrap; line-height: 1.45; }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<nav class="sidebar">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="view_orders.php" class="active">View Orders</a></li>
        <li><a href="stock_management.php">Stock Management</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
        <?php endif; ?>
        <li><a href="reports.php">Reports</a></li>
    </ul>
</nav>

<main class="main">

    <?php if (isset($_GET['status_updated'])): ?>
        <div class="alert success" style="padding: 1rem; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 2rem;">
            Order status updated successfully!
            <?php if (isset($_GET['stock_deducted'])): ?>
                <br>Stock has been automatically deducted.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h1 class="page-title">All Orders</h1>

    <div class="controls">
        <input type="text" id="searchInput" class="search-box" placeholder="Search by customer name, email, phone, address..." onkeyup="searchTable()">
        <select class="filter-select" onchange="filterStatus(this.value)">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="preparing">Preparing</option>
            <option value="ready">Ready</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="table-card">
        <table id="ordersTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer & Delivery</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date & Time</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
                if ($stmt->rowCount() == 0): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:6rem; color:#999;">
                            <i class="fas fa-shopping-bag fa-5x" style="color:#eee; margin-bottom:1rem;"></i>
                            <br><strong>No orders yet</strong><br>
                            <small>Waiting for your first customer!</small>
                        </td>
                    </tr>
                <?php else:
                    while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $items_stmt = $pdo->prepare("SELECT * FROM orders_detail WHERE order_id = ?");
                        $items_stmt->execute([$order['id']]);
                        $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

                        $itemText = '';
                        foreach ($items as $item) {
                            $qty = $item['quantity'] ?? 1;
                            $name = htmlspecialchars($item['product_name'] ?? 'Unknown Item');
                            $itemText .= "<strong>{$qty}×</strong> {$name}<br>";
                        }

                        // === IMPORTANT: Normalize status to lowercase ===
                        $status = strtolower($order['status'] ?? 'pending');

                        $statusClass = match($status) {
                            'pending'    => 'status pending',
                            'preparing'  => 'status preparing',
                            'ready'      => 'status ready',
                            'delivered'  => 'status delivered',
                            'cancelled'  => 'status cancelled',
                            default      => 'status'
                        };

                        $deliveryInfo = "<strong>" . htmlspecialchars($order['customer_name']) . "</strong><br>";
                        $deliveryInfo .= "<small>Email: " . htmlspecialchars($order['customer_email'] ?? '-') . "<br>";
                        $deliveryInfo .= "Phone: " . htmlspecialchars($order['customer_phone'] ?? '-') . "</small><br>";
                        $deliveryInfo .= "<small><strong>Deliver to:</strong><br>" . nl2br(htmlspecialchars($order['delivery_address'] ?? '')) . "<br>";
                        $deliveryInfo .= htmlspecialchars($order['city'] ?? '') . ", " . htmlspecialchars($order['postcode'] ?? '') . "</small>";

                        $dateTimeDisplay = date('d M Y  H:i', strtotime($order['created_at']));

                        ?>
                        <tr data-status="<?= $status ?>">
                            <td>#<?= sprintf("%04d", $order['id']) ?></td>
                            <td style="font-size:0.9rem; line-height:1.6;"><?= $deliveryInfo ?></td>
                            <td style="font-size:0.95rem;"><?= $itemText ?></td>
                            <td><strong>RM <?= number_format($order['total'], 2) ?></strong></td>
                            <td><span class="<?= $statusClass ?>"><?= ucfirst($status) ?></span></td>
                            <td class="date-time-col"><?= $dateTimeDisplay ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="new_status" onchange="this.form.submit()" class="filter-select" style="padding:0.5rem; font-size:0.9rem; width:130px;">
                                        <option value="pending"    <?= $status === 'pending'    ? 'selected' : '' ?>>Pending</option>
                                        <option value="preparing"  <?= $status === 'preparing'  ? 'selected' : '' ?>>Preparing</option>
                                        <option value="ready"      <?= $status === 'ready'      ? 'selected' : '' ?>>Ready</option>
                                        <option value="delivered"  <?= $status === 'delivered'  ? 'selected' : '' ?>>Delivered</option>
                                        <option value="cancelled"  <?= $status === 'cancelled'  ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function searchTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}

function filterStatus(status) {
    const rows = document.querySelectorAll('#ordersTable tbody tr');
    rows.forEach(row => {
        const rowStatus = row.dataset.status || ''; // safeguard
        row.style.display = (status === '' || rowStatus === status) ? '' : 'none';
    });
}
</script>

</body>
</html>