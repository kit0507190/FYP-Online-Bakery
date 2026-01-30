<?php
require_once 'admin_auth.php';  // Secure login + loads $current_admin with role
require_once 'admin_config.php';  // Main database connection

// =============================================
// STATUS TRANSITION RULES (Business Logic)
// =============================================
function isValidTransition($old_status, $new_status) {
    $rules = [
        'pending' => ['preparing', 'cancelled'],      // Pending → Preparing/Cancelled
        'preparing' => ['ready', 'cancelled'],        // Preparing → Ready/Cancelled  
        'ready' => ['delivered', 'cancelled'],        // Ready → Delivered/Cancelled
        'delivered' => [],                            // Delivered → FINAL (no changes)
        'cancelled' => []                             // Cancelled → FINAL (no changes)
    ];
    
    $old = strtolower($old_status);
    $new = strtolower($new_status);
    
    // Same status = allowed (no change)
    if ($old === $new) return true;
    
    return in_array($new, $rules[$old] ?? []);
}

// =============================================
// HANDLE STATUS UPDATE (POST) → then REDIRECT
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id   = (int)$_POST['order_id'];
    $new_status = trim($_POST['new_status']);

    $valid_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
    if (!in_array($new_status, $valid_statuses)) {
        header("Location: view_orders.php?error=invalid_status");
        exit();
    }

    // Get current order details (including old status)
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        header("Location: view_orders.php?error=order_not_found");
        exit();
    }
    $old_status = $order['status'] ?: 'pending';

    // 🚫 BLOCK INVALID TRANSITIONS
    if (!isValidTransition($old_status, $new_status)) {
        header("Location: view_orders.php?error=invalid_transition&order_id=$order_id");
        exit();
    }

    // ────────────────────────────────────────────────
    // Begin transaction — important for atomicity
    // ────────────────────────────────────────────────
    $pdo->beginTransaction();

    try {
        // Update status
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);

        $stock_restored = false;
        $credit_added   = false;
        $credit_amount  = 0.00;

        // Restore stock & add credit ONLY when going TO cancelled (and wasn't already cancelled)
        // ✅ Now protected by transition rules above
        if ($new_status === 'cancelled' && strtolower($old_status) !== 'cancelled') {
            // ──────────────── 1. Restore inventory ────────────────
            $items_stmt = $pdo->prepare("SELECT product_id, quantity FROM orders_detail WHERE order_id = ?");
            $items_stmt->execute([$order_id]);
            $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $item) {
                $qty = (int)($item['quantity'] ?? 0);
                $product_id = (int)($item['product_id'] ?? 0);

                if ($qty > 0 && $product_id > 0) {
                    $update = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                    $update->execute([$qty, $product_id]);
                    $stock_restored = true;
                }
            }

            // ──────────────── 2. Add store credit (email match) ────────────────
            $email = trim($order['customer_email'] ?? '');

            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user_stmt = $pdo->prepare("
                    SELECT id 
                    FROM user_db 
                    WHERE email = :email 
                      AND status = 'active'
                    LIMIT 2
                ");
                $user_stmt->execute([':email' => $email]);
                $users = $user_stmt->fetchAll(PDO::FETCH_COLUMN);

                // Only proceed if EXACTLY ONE active user matches this email
                if (count($users) === 1) {
                    $target_user_id = $users[0];
                    $credit_amount  = (float) $order['total'];

                    $credit_stmt = $pdo->prepare("
                        UPDATE user_db 
                           SET credit = credit + :amt,
                               updated_at = NOW()
                         WHERE id = :uid
                    ");
                    $credit_stmt->execute([
                        ':amt' => $credit_amount,
                        ':uid' => $target_user_id
                    ]);

                    $credit_added = ($credit_stmt->rowCount() === 1);
                }
                // else: skip — either no user, or ambiguous (multiple accounts with same email)
            }
        }

        $pdo->commit();

        // Redirect with feedback
        $redirect = "view_orders.php?status_updated=1";
        if ($stock_restored) {
            $redirect .= "&stock_restored=1";
        }
        if ($credit_added) {
            $redirect .= "&credit_added=" . number_format($credit_amount, 2);
        } else if ($new_status === 'cancelled' && strtolower($old_status) !== 'cancelled') {
            // Optional: show reason (helps debugging)
            $reason = "no matching active user";
            if (empty($order['customer_email'])) {
                $reason = "no email on order";
            } else if (!filter_var($order['customer_email'], FILTER_VALIDATE_EMAIL)) {
                $reason = "invalid email format";
            } else if (count($users ?? []) > 1) {
                $reason = "multiple accounts with same email";
            }
            $redirect .= "&no_credit_reason=" . urlencode($reason);
        }
        header("Location: $redirect");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: view_orders.php?error=update_failed");
        exit();
    }
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
        .status.delivered  { background: #c3e6cb; color: #0f5132; font-weight: bold; }
        .status.cancelled  { background: #f8d7da; color: #721c24; font-weight: bold; }
        #ordersTable img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
        .date-time-col { white-space: nowrap; line-height: 1.45; }
        
        /* 🚫 DISABLED OPTIONS */
        .status-select option:disabled {
            color: #999 !important;
            background: #f5f5f5 !important;
        }
        .status-select:disabled {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }
        .final-status {
            border: 2px solid #dc3545;
            background: #f8d7da !important;
            color: #721c24 !important;
            font-weight: bold !important;
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<nav class="sidebar">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
        <li><a href="view_orders.php" class="active">View Orders</a></li>
        <li><a href="stock_management.php">Stock Management</a></li>
        <li><a href="admin_restore.php">Restore Deleted</a></li>
        <li><a href="user_comments.php">User Comments</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">

    <?php if (isset($_GET['status_updated'])): ?>
        <div class="alert success" style="padding: 1rem; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 2rem;">
            <i class="fas fa-check-circle"></i> Order status updated successfully!
            <?php if (isset($_GET['stock_restored'])): ?>
                <br><strong>Stock restored</strong> (order cancelled).
            <?php endif; ?>
            <?php if (isset($_GET['credit_added'])): ?>
                <br>✓ RM <?= htmlspecialchars($_GET['credit_added']) ?> store credit added to customer account.
            <?php elseif (isset($_GET['no_credit_reason'])): ?>
                <br>(No credit added: <?= htmlspecialchars($_GET['no_credit_reason']) ?>)
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert error" style="padding: 1rem; background: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 2rem;">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php
            if (isset($_GET['invalid_transition'])) {
                echo "Invalid status change! This order cannot transition from " . htmlspecialchars($_GET['order_id'] ?? '') . ".";
            } else {
                echo "Update failed. Please try again.";
            }
            ?>
        </div>
    <?php endif; ?>

    <h1 class="page-title">
        <i class="fas fa-shopping-bag"></i> All Orders
    </h1>

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

                        $status = strtolower($order['status'] ?? 'pending');
                        $statusClass = match($status) {
                            'pending'    => 'status pending',
                            'preparing'  => 'status preparing',
                            'ready'      => 'status ready',
                            'delivered'  => 'status delivered final-status',
                            'cancelled'  => 'status cancelled final-status',
                            default      => 'status'
                        };

                        // ✅ GENERATE VALID OPTIONS ONLY
                        $valid_options = [];
                        $allowed = [
                            'pending' => ['pending', 'preparing', 'cancelled'],
                            'preparing' => ['preparing', 'ready', 'cancelled'],
                            'ready' => ['ready', 'delivered', 'cancelled'],
                            'delivered' => ['delivered'],
                            'cancelled' => ['cancelled']
                        ];

                        foreach ($allowed[$status] ?? ['pending'] as $opt) {
                            $valid_options[$opt] = true;
                        }

                        $deliveryInfo = "<strong>" . htmlspecialchars($order['customer_name']) . "</strong><br>";
                        $deliveryInfo .= "<small>Email: " . htmlspecialchars($order['customer_email'] ?? '-') . "<br>";
                        $deliveryInfo .= "Phone: " . htmlspecialchars($order['customer_phone'] ?? '-') . "</small><br>";
                        $deliveryInfo .= "<small><strong>Deliver to:</strong><br>" . nl2br(htmlspecialchars($order['delivery_address'] ?? '')) . "<br>";
                        $deliveryInfo .= htmlspecialchars($order['city'] ?? '') . ", " . htmlspecialchars($order['postcode'] ?? '') . "</small>";

                        $dateTimeDisplay = date('d M Y  H:i', strtotime($order['created_at']));
                ?>
                        <tr data-status="<?= $status ?>">
                            <td><strong>#<?= sprintf("%04d", $order['id']) ?></strong></td>
                            <td style="font-size:0.9rem; line-height:1.6;"><?= $deliveryInfo ?></td>
                            <td style="font-size:0.95rem;"><?= $itemText ?></td>
                            <td><strong>RM <?= number_format($order['total'], 2) ?></strong></td>
                            <td><span class="<?= $statusClass ?>"><?= ucfirst($status) ?></span></td>
                            <td class="date-time-col"><?= $dateTimeDisplay ?></td>
                            <td>
                                <?php if (in_array($status, ['delivered', 'cancelled'])): ?>
                                    <span class="status-select" style="padding:0.5rem; font-size:0.9rem; width:130px; display:inline-block;">
                                        <i class="fas fa-lock"></i> <?= ucfirst($status) ?> (Final)
                                    </span>
                                <?php else: ?>
                                    <form method="POST" style="display:inline;" onsubmit="return confirmStatusChange(this)">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="new_status" class="filter-select status-select" onchange="this.form.submit()" style="padding:0.5rem; font-size:0.9rem; width:130px;">
                                            <?php
                                            $all_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
                                            foreach ($all_statuses as $opt_status): 
                                                $selected = ($status === $opt_status) ? 'selected' : '';
                                                $disabled = !isset($valid_options[$opt_status]) ? 'disabled' : '';
                                            ?>
                                                <option value="<?= $opt_status ?>" <?= $selected ?> <?= $disabled ?>>
                                                    <?= ucfirst($opt_status) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                <?php endif; ?>
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
        const rowStatus = row.dataset.status || '';
        row.style.display = (status === '' || rowStatus === status) ? '' : 'none';
    });
}

// ✅ CONFIRM BEFORE CRITICAL CHANGES
function confirmStatusChange(form) {
    const select = form.querySelector('select[name="new_status"]');
    const newStatus = select.value;
    const currentStatus = select.querySelector('option:checked').textContent.trim();
    
    if (newStatus === 'cancelled') {
        return confirm('Are you sure? This will cancel the order and RESTORE STOCK for all items.');
    }
    if (newStatus === 'delivered') {
        return confirm('Mark as Delivered? This is FINAL and cannot be changed.');
    }
    return true;
}
</script>

</body>
</html>