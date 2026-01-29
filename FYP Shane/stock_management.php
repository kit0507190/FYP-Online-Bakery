<?php

require_once 'admin_auth.php';  // Secure login + loads $current_admin
require_once 'admin_config.php';  // Main DB connection

// Handle classic form POST (fallback / no JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['adjust'])) {
    $id = (int)$_POST['product_id'];
    $action = $_POST['adjust'];

    if ($id > 0) {
        if ($action === 'add') {
            $pdo->prepare("UPDATE products SET stock = stock + 1 WHERE id = ?")->execute([$id]);
        } elseif ($action === 'subtract') {
            $pdo->prepare("UPDATE products SET stock = GREATEST(stock - 1, 0) WHERE id = ?")->execute([$id]);
        }
    }

    header("Location: stock_management.php?updated=1");
    exit();
}

// ───────────────────────────────────────────────
// AJAX endpoint
// ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'], $_POST['product_id'], $_POST['action'])) {
    header('Content-Type: application/json; charset=utf-8');

    $response = ['success' => false, 'new_stock' => null, 'message' => ''];

    $id     = (int)$_POST['product_id'];
    $action = $_POST['action'] ?? '';

    if ($id < 1 || !in_array($action, ['add', 'subtract'])) {
        $response['message'] = 'Invalid request';
        echo json_encode($response);
        exit;
    }

    try {
        if ($action === 'add') {
            $pdo->prepare("UPDATE products SET stock = stock + 1 WHERE id = ?")->execute([$id]);
        } else {
            $pdo->prepare("UPDATE products SET stock = GREATEST(stock - 1, 0) WHERE id = ?")->execute([$id]);
        }

        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $new_stock = (int) $stmt->fetchColumn();

        $response = [
            'success'   => true,
            'new_stock' => $new_stock,
            'message'   => 'Stock updated'
        ];
    } catch (Exception $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Stock Management</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .page-title { margin-bottom: 1.5rem; color: #5a3921; }
        .controls { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .search-box { padding: 0.8rem 1rem; border: 1px solid #ddd; border-radius: 8px; width: 300px; max-width: 100%; }
        .status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: capitalize;
        }
        .status.cancelled  { background: #f8d7da; color: #721c24; }
        .status.low        { background: #fff3cd; color: #856404; } /* was pending */
        .status.ready      { background: #d4edda; color: #155724; }
        .step-btn {
            padding: 0.6rem 1rem;
            font-size: 1.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 4px;
            min-width: 44px;
        }
        .step-btn.plus     { background: #28a745; color: white; }
        .step-btn.minus    { background: #dc3545; color: white; }
        .step-btn:disabled { background: #6c757d; cursor: not-allowed; opacity: 0.6; }

        /* Mini toast feedback */
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
        <li><a href="view_orders.php">View Orders</a></li>
        <li><a href="stock_management.php" class="active">Stock Management</a></li>
        <li><a href="admin_restore.php">Restore Deleted</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert success" style="padding: 1rem; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 2rem;">
            <i class="fas fa-check-circle"></i> Stock updated successfully!
        </div>
    <?php endif; ?>

    <h1 class="page-title">Stock Management</h1>

    <div class="controls">
        <input type="text" id="searchInput" class="search-box" placeholder="Search by product name..." onkeyup="searchTable()">
    </div>

    <div class="table-card">
        <h2>Current Stock Levels</h2>
        <table id="stockTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Adjust Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT p.*, c.name AS cat_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.deleted_at IS NULL
                    ORDER BY p.stock ASC, p.name ASC
                ");
                if ($stmt->rowCount() == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:6rem; color:#999;">
                            <i class="fas fa-box-open fa-5x" style="color:#eee; margin-bottom:1rem;"></i><br>
                            <strong>No products found</strong><br>
                            <small>Add products in Manage Products first.</small>
                        </td>
                    </tr>
                <?php else:
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $stock = (int)$row['stock'];

                        if ($stock <= 0) {
                            $statusClass = 'status cancelled';
                            $statusText  = 'Out of Stock';
                        } elseif ($stock <= 5) {
                            $statusClass = 'status low';
                            $statusText  = 'Low Stock';
                        } else {
                            $statusClass = 'status ready';
                            $statusText  = 'In Stock';
                        }

                        echo "<tr data-product-id=\"{$row['id']}\">
                            <td>{$row['id']}</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['cat_name'] ?? 'Uncategorized') . "</td>
                            <td class=\"stock-value\"><strong>$stock</strong></td>
                            <td class=\"stock-controls\">
                                <button type=\"button\" class=\"step-btn plus\" 
                                        data-action=\"add\" title=\"Add 1\">+</button>
                                <button type=\"button\" class=\"step-btn minus\" 
                                        data-action=\"subtract\" title=\"Remove 1\" 
                                        " . ($stock <= 0 ? 'disabled' : '') . ">−</button>
                            </td>
                            <td><span class=\"$statusClass\">$statusText</span></td>
                        </tr>";
                    }
                endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function searchTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#stockTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}

function showToast(message = 'Stock updated!', bg = '#28a745') {
    const toast = document.createElement('div');
    toast.className = 'mini-toast';
    toast.textContent = message;
    toast.style.background = bg;
    document.body.appendChild(toast);
    setTimeout(() => toast.style.opacity = '1', 50);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 1800);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.step-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const row    = this.closest('tr');
            const action = this.dataset.action;
            const id     = row.dataset.productId;

            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '⋯';

            try {
                const formData = new URLSearchParams();
                formData.append('ajax', '1');
                formData.append('product_id', id);
                formData.append('action', action);

                const res = await fetch('stock_management.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                });

                if (!res.ok) throw new Error('Network response was not ok');

                const data = await res.json();

                if (data.success) {
                    // Update displayed stock
                    row.querySelector('.stock-value strong').textContent = data.new_stock;

                    // Update status
                    const statusEl = row.querySelector('td:last-child span');
                    let cls = '', txt = '';

                    if (data.new_stock <= 0) {
                        cls = 'status cancelled';
                        txt = 'Out of Stock';
                    } else if (data.new_stock <= 5) {
                        cls = 'status low';
                        txt = 'Low Stock';
                    } else {
                        cls = 'status ready';
                        txt = 'In Stock';
                    }

                    statusEl.className = cls;
                    statusEl.textContent = txt;

                    // Update minus button state
                    const minus = row.querySelector('.minus');
                    if (minus) minus.disabled = (data.new_stock <= 0);

                    showToast();
                } else {
                    showToast(data.message || 'Update failed', '#dc3545');
                }
            } catch (err) {
                console.error(err);
                showToast('Connection error', '#dc3545');
            } finally {
                this.innerHTML = originalHTML;
                this.disabled = false;
            }
        });
    });
});
</script>

</body>
</html>