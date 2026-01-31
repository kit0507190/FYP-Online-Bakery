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

// ───────────────────────────────────────────────
// AJAX endpoint for status change
// ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'], $_POST['order_id'], $_POST['new_status'])) {
    header('Content-Type: application/json; charset=utf-8');

    $response = [
        'success'     => false,
        'message'     => '',
        'new_status'  => '',
        'stock_restored' => false,
        'credit_added'   => 0.00,
        'no_credit_reason' => ''
    ];

    $order_id   = (int)$_POST['order_id'];
    $new_status = trim($_POST['new_status'] ?? '');

    $valid_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
    if ($order_id < 1 || !in_array($new_status, $valid_statuses)) {
        $response['message'] = 'Invalid request';
        echo json_encode($response);
        exit;
    }

    $pdo->beginTransaction();
    try {
        // Get current order
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $response['message'] = 'Order not found';
            echo json_encode($response);
            exit;
        }

        $old_status = strtolower($order['status'] ?? 'pending');

        if (!isValidTransition($old_status, $new_status)) {
            $response['message'] = "Invalid transition: $old_status → $new_status not allowed";
            echo json_encode($response);
            exit;
        }

        // Update status
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);

        $stock_restored = false;
        $credit_added   = 0.00;
        $no_credit_reason = '';

        // Handle cancellation logic
        if ($new_status === 'cancelled' && $old_status !== 'cancelled') {
            // Restore stock
            $items_stmt = $pdo->prepare("SELECT product_id, quantity FROM orders_detail WHERE order_id = ?");
            $items_stmt->execute([$order_id]);
            $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $item) {
                $qty = (int)($item['quantity'] ?? 0);
                $pid = (int)($item['product_id'] ?? 0);
                if ($qty > 0 && $pid > 0) {
                    $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?")
                        ->execute([$qty, $pid]);
                    $stock_restored = true;
                }
            }

            // Add credit
            $email = trim($order['customer_email'] ?? '');
            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user_stmt = $pdo->prepare("
                    SELECT id FROM user_db 
                    WHERE email = ? AND status = 'active' 
                    LIMIT 2
                ");
                $user_stmt->execute([$email]);
                $users = $user_stmt->fetchAll(PDO::FETCH_COLUMN);

                if (count($users) === 1) {
                    $uid = $users[0];
                    $credit_amount = (float)$order['total'];

                    $pdo->prepare("UPDATE user_db SET credit = credit + ?, updated_at = NOW() WHERE id = ?")
                        ->execute([$credit_amount, $uid]);

                    $credit_added = $credit_amount;
                } else if (count($users) === 0) {
                    $no_credit_reason = 'no matching active user';
                } else {
                    $no_credit_reason = 'multiple accounts with same email';
                }
            } else if ($email) {
                $no_credit_reason = 'invalid email format';
            } else {
                $no_credit_reason = 'no email on order';
            }
        }

        $pdo->commit();

        $response = [
            'success'          => true,
            'message'          => 'Status updated',
            'new_status'       => $new_status,
            'stock_restored'   => $stock_restored,
            'credit_added'     => $credit_added,
            'no_credit_reason' => $no_credit_reason
        ];

    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}

// =============================================
// HANDLE STATUS UPDATE (POST) → then REDIRECT (fallback for no JS)
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
        .final-status-badge {
            padding: 0.5rem 1rem;
            background: #f8d7da;
            color: #721c24;
            border-radius: 6px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .mini-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 20px;
            background: #28a745;
            color: white;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.4s ease;
            font-weight: 500;
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
                            <td class="status-control-cell">
                                <?php if (in_array($status, ['delivered', 'cancelled'])): ?>
                                    <span class="final-status-badge">
                                        <i class="fas fa-lock"></i> <?= ucfirst($status) ?> (Final)
                                    </span>
                                <?php else: ?>
                                    <select class="status-select filter-select ajax-status-select" 
                                            data-order-id="<?= $order['id'] ?>" 
                                            data-current="<?= $status ?>">
                                        <?php
                                        $all_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
                                        foreach ($all_statuses as $opt):
                                            $sel = ($status === $opt) ? 'selected' : '';
                                            $dis = !isset($valid_options[$opt]) ? 'disabled' : '';
                                        ?>
                                            <option value="<?= $opt ?>" <?= $sel ?> <?= $dis ?>>
                                                <?= ucfirst($opt) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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

function showToast(message, bg = '#28a745') {
    const toast = document.createElement('div');
    toast.className = 'mini-toast';
    toast.textContent = message;
    toast.style.background = bg;
    document.body.appendChild(toast);
    setTimeout(() => toast.style.opacity = '1', 50);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 2200);
}

function getStatusClass(status) {
    const classes = {
        'pending': 'status pending',
        'preparing': 'status preparing',
        'ready': 'status ready',
        'delivered': 'status delivered final-status',
        'cancelled': 'status cancelled final-status'
    };
    return classes[status] || 'status';
}

const statusRules = {
    'pending': ['preparing', 'cancelled'],
    'preparing': ['ready', 'cancelled'],
    'ready': ['delivered', 'cancelled'],
    'delivered': [],
    'cancelled': []
};

function getValidOptions(current) {
    const all = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
    const allowed = [current, ...(statusRules[current] || [])];
    return all.map(opt => ({
        value: opt,
        disabled: !allowed.includes(opt)
    }));
}

function updateRowStatus(row, newStatus, stockRestored = false, credit = 0, noCreditReason = '') {
    const statusCell = row.querySelector('td:nth-child(5) span'); // Status badge
    const controlCell = row.querySelector('.status-control-cell');

    // Update badge
    if (statusCell) {
        statusCell.className = getStatusClass(newStatus);
        statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    }

    // Update or lock control
    if (['delivered', 'cancelled'].includes(newStatus)) {
        controlCell.innerHTML = `
            <span class="final-status-badge">
                <i class="fas fa-lock"></i> ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)} (Final)
            </span>
        `;
    } else {
        // Rebuild select with new valid options
        const orderId = row.querySelector('.ajax-status-select')?.dataset.orderId || '';
        controlCell.innerHTML = `
            <select class="status-select filter-select ajax-status-select" 
                    data-order-id="${orderId}" 
                    data-current="${newStatus}">
                ${getValidOptions(newStatus).map(opt => `
                    <option value="${opt.value}" ${opt.value === newStatus ? 'selected' : ''} ${opt.disabled ? 'disabled' : ''}>
                        ${opt.value.charAt(0).toUpperCase() + opt.value.slice(1)}
                    </option>
                `).join('')}
            </select>
        `;
    }

    row.dataset.status = newStatus;

    // Feedback message
    let msg = `Order #${row.querySelector('td:first-child strong').textContent.trim()} → ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}`;
    if (stockRestored) msg += ' (stock restored)';
    if (credit > 0) msg += ` (+RM ${credit.toFixed(2)} credit)`;
    if (noCreditReason) msg += ` (credit skipped: ${noCreditReason})`;

    showToast(msg, (newStatus === 'cancelled') ? '#dc3545' : '#28a745');
}

function confirmStatusChange(select, newStatus) {
    if (newStatus === 'cancelled') {
        return confirm('Cancel order? This will restore stock and attempt to add credit.');
    }
    if (newStatus === 'delivered') {
        return confirm('Mark as Delivered? This is FINAL — cannot be changed later.');
    }
    return true;
}

document.addEventListener('change', async function(e) {
    if (e.target.classList.contains('ajax-status-select')) {
        const select = e.target;
        const newStatus = select.value;
        const row = select.closest('tr');
        const current = select.dataset.current;

        if (newStatus === current) return; // No change

        if (!confirmStatusChange(select, newStatus)) {
            select.value = current;
            return;
        }

        select.disabled = true;

        try {
            const formData = new URLSearchParams();
            formData.append('ajax', '1');
            formData.append('order_id', select.dataset.orderId);
            formData.append('new_status', newStatus);

            const res = await fetch('view_orders.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            });

            if (!res.ok) throw new Error('Network error');

            const data = await res.json();

            if (data.success) {
                updateRowStatus(
                    row,
                    data.new_status,
                    data.stock_restored,
                    data.credit_added,
                    data.no_credit_reason
                );
            } else {
                throw new Error(data.message || 'Update failed');
            }
        } catch (err) {
            console.error(err);
            showToast(err.message || 'Connection error', '#dc3545');
            select.value = current;
        } finally {
            // Only enable if the select still exists (not replaced for final status)
            if (document.body.contains(select)) {
                select.disabled = false;
            }
        }
    }
});
</script>

</body>
</html>