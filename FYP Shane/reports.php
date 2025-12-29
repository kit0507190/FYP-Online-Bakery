<?php

require_once 'admin_auth.php';  // Secure login + loads $current_admin with role

// Restrict access to Super Admin only
if ($current_admin['role'] !== 'super_admin') {
    $_SESSION['error_message'] = "Access denied. Reports are restricted to Super Admins only.";
    header("Location: admin_dashboard.php");
    exit();
}

require_once 'config.php';  // Main DB connection

// Handle CSV Export
if (isset($_GET['export_csv']) && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $start = $_GET['startDate'];
    $end = $_GET['endDate'];

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bakery_report_' . $start . '_to_' . $end . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Rank', 'Product', 'Category', 'Units Sold', 'Revenue', 'Profit (60%)']);

    $stmt = $pdo->prepare("SELECT items FROM orders WHERE status = 'delivered' AND DATE(created_at) BETWEEN ? AND ?");
    $stmt->execute([$start, $end]);

    $sales = [];
    while ($row = $stmt->fetch()) {
        $items = json_decode($row['items'], true) ?: [];
        foreach ($items as $item) {
            $id = $item['id'] ?? 0;
            $qty = $item['quantity'] ?? $item['qty'] ?? 1;
            if ($id > 0) {
                $sales[$id] = ($sales[$id] ?? 0) + $qty;
            }
        }
    }
    arsort($sales);

    $rank = 1;
    foreach (array_slice($sales, 0, 50, true) as $id => $units) {
        $prod = $pdo->query("SELECT name, price, category_id FROM products WHERE id = $id")->fetch();
        if (!$prod) continue;
        $cat = $pdo->query("SELECT name FROM categories WHERE id = {$prod['category_id']}")->fetchColumn() ?: 'Uncategorized';
        $rev = $prod['price'] * $units;
        $profit = $rev * 0.6;

        fputcsv($output, [
            $rank++,
            $prod['name'],
            $cat,
            $units,
            number_format($rev, 2),
            number_format($profit, 2)
        ]);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Reports</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header class="header">
    <h1>BakeryHouse Admin</h1>
    <div style="display: flex; align-items: center; gap: 20px;">
        <span>Welcome, <strong><?= htmlspecialchars($current_admin['username']) ?></strong> 
            (<span class="role-highlight"><?= ucfirst(str_replace('_', ' ', $current_admin['role'])) ?></span>)
        </span>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>

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
    <h1 class="page-title">Business Reports & Analytics</h1>

    <!-- Date Filter Form -->
    <form method="GET" class="controls" style="margin-bottom: 2.5rem; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="date" name="startDate" class="filter-select" value="<?= htmlspecialchars($_GET['startDate'] ?? '2025-12-01') ?>">
            <input type="date" name="endDate" class="filter-select" value="<?= htmlspecialchars($_GET['endDate'] ?? date('Y-m-d')) ?>">
            <button type="submit" class="add-btn" style="padding: 0.8rem 1.5rem;">Filter</button>
        </div>
        <?php if (isset($_GET['startDate']) && isset($_GET['endDate'])): ?>
            <a href="?export_csv=1&startDate=<?= urlencode($_GET['startDate']) ?>&endDate=<?= urlencode($_GET['endDate']) ?>" 
               class="add-btn" style="padding: 0.8rem 1.5rem; background: #28a745; text-decoration:none;">
                <i class="fas fa-download"></i> Export CSV
            </a>
        <?php endif; ?>
    </form>

    <?php
    $start = $_GET['startDate'] ?? '2025-12-01';
    $end = $_GET['endDate'] ?? date('Y-m-d');

    $revenue = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'delivered' AND DATE(created_at) BETWEEN '$start' AND '$end'")->fetchColumn();

    $lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 10")->fetchColumn();

    // Top sales
    $stmt = $pdo->prepare("SELECT items FROM orders WHERE status = 'delivered' AND DATE(created_at) BETWEEN ? AND ?");
    $stmt->execute([$start, $end]);

    $topSales = [];
    while ($row = $stmt->fetch()) {
        $items = json_decode($row['items'], true) ?: [];
        foreach ($items as $item) {
            $id = $item['id'] ?? 0;
            $qty = $item['quantity'] ?? $item['qty'] ?? 1;
            if ($id > 0) {
                $topSales[$id] = ($topSales[$id] ?? 0) + $qty;
            }
        }
    }
    arsort($topSales);

    $topId = key($topSales) ?? 0;
    $topUnits = $topSales[$topId] ?? 0;
    $topName = $topId ? $pdo->query("SELECT name FROM products WHERE id = $topId")->fetchColumn() : 'None';

    // Chart data
    $chartDates = [];
    $chartSales = [];
    $current = new DateTime($start);
    $endDate = new DateTime($end);
    while ($current <= $endDate) {
        $date = $current->format('Y-m-d');
        $chartDates[] = $current->format('d M');
        $sales = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered' AND DATE(created_at)='$date'")->fetchColumn();
        $chartSales[] = $sales;
        $current->modify('+1 day');
    }
    ?>

    <div class="stats-grid" style="gap: 2.5rem; margin-bottom: 3rem;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-sack-dollar"></i></div>
            <h3>RM <?= number_format($revenue, 2) ?></h3>
            <p>Sales in Range</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-crown"></i></div>
            <h3><?= htmlspecialchars($topName) ?></h3>
            <p>Top Product</p>
            <p style="color:#27AE60; font-weight:bold;"><?= $topUnits ?> units</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <h3>RM <?= number_format($revenue, 2) ?></h3>
            <p>Total Revenue</p>
        </div>
        <div class="stat-card" style="<?= $lowStock > 0 ? 'border-left: 6px solid #D97706;' : '' ?>">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle" style="color:#D97706;"></i></div>
            <h3><?= $lowStock ?></h3>
            <p>Low Stock Items</p>
        </div>
    </div>

    <div style="background:white; padding:2rem; border-radius:12px; margin-bottom:3rem;">
        <h2 style="margin-bottom:1rem; color:#8B4513;">Sales Trend</h2>
        <canvas id="salesChart"></canvas>
    </div>

    <div class="table-card">
        <h2>Top Products in Range</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Product</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($topSales)): ?>
                    <tr><td colspan="4" style="text-align:center; padding:3rem; color:#999;">No delivered orders in this date range</td></tr>
                <?php else:
                    $rank = 1;
                    foreach (array_slice($topSales, 0, 10, true) as $id => $units):
                        $p = $pdo->query("SELECT name, price FROM products WHERE id = $id")->fetch();
                        if (!$p) continue;
                        $rev = $p['price'] * $units;
                ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= $units ?></td>
                        <td>RM <?= number_format($rev, 2) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($chartDates) ?>,
        datasets: [{
            label: 'Daily Sales (RM)',
            data: <?= json_encode($chartSales) ?>,
            borderColor: '#8B4513',
            backgroundColor: 'rgba(139,69,19,0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});
</script>

</body>
</html>