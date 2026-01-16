<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BakeryHouse</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'admin_header.php'; ?>

<nav class="sidebar">
    <ul>
        <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="view_orders.php">View Orders</a></li>
        <li><a href="stock_management.php">Stock Management</a></li>

        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>


    </ul>
</nav>

<main class="main">
    <div class="welcome-msg">
        👋 Hello <strong><?= htmlspecialchars($current_admin['username']) ?></strong>! 
        You are logged in as <strong><?= ucfirst(str_replace('_', ' ', $current_admin['role'])) ?></strong>.
    </div>

    <h1 style="margin-bottom: 2rem;">Dashboard Overview</h1>

    <?php
    require_once 'config.php';  // For main DB connection (orders, products)

    $today = date('Y-m-d');
    
    $todaySales = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE DATE(created_at) = '$today' AND status = 'delivered'")->fetchColumn();
    $todayOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = '$today'")->fetchColumn();
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
    $lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 10 AND stock > 0")->fetchColumn();
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Today's Sales</h3>
            <div class="stat-number">RM <?= number_format($todaySales, 2) ?></div>
        </div>
        <div class="stat-card">
            <h3>Orders Today</h3>
            <div class="stat-number"><?= $todayOrders ?></div>
        </div>
        <div class="stat-card">
            <h3>Pending Orders</h3>
            <div class="stat-number"><?= $pendingOrders ?></div>
        </div>
        <div class="stat-card <?= $lowStock > 0 ? 'low-stock' : '' ?>">
            <h3>Low Stock Items</h3>
            <div class="stat-number"><?= $lowStock ?></div>
        </div>
    </div>

    <div style="height: 400px; margin: 3rem 0;">
        <canvas id="salesChart"></canvas>
    </div>

    <h2>Recent Orders</h2>
    <table style="width:100%;">
        <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            <?php
            $recent = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 8")->fetchAll();
            if (empty($recent)): ?>
                <tr><td colspan="5" style="text-align:center; padding:2rem; color:#999;">No orders yet.</td></tr>
            <?php else:
                foreach ($recent as $o): ?>
                <tr>
                    <td>#<?= sprintf("%04d", $o['id']) ?></td>
                    <td><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td>RM <?= number_format($o['total'], 2) ?></td>
                    <td><span class='status <?= $o['status'] ?>'><?= ucfirst($o['status']) ?></span></td>
                    <td><?= date('d M Y H:i', strtotime($o['created_at'])) ?></td>
                </tr>
                <?php endforeach;
            endif; ?>
        </tbody>
    </table>
</main>

<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: <?php
            $labels = [];
            for ($i = 6; $i >= 0; $i--) {
                $labels[] = date('d M', strtotime("-$i days"));
            }
            echo json_encode($labels);
        ?>,
        datasets: [{
            label: 'Daily Sales (RM)',
            data: <?php
                $data = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $sales = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE DATE(created_at) = '$date' AND status='delivered'")->fetchColumn();
                    $data[] = $sales;
                }
                echo json_encode($data);
            ?>,
            borderColor: '#8B4513',
            backgroundColor: 'rgba(139, 69, 19, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>

</body>
</html>