<?php
// reports.php

require_once 'admin_auth.php';  // Secure login + loads $current_admin

// Restrict to Super Admin only
if ($current_admin['role'] !== 'super_admin') {
    $_SESSION['error_message'] = "Access denied. Reports are restricted to Super Admins only.";
    header("Location: admin_dashboard.php");
    exit();
}

require_once 'admin_config.php';

// Set default date range: last 30 days to today
$start = $_GET['startDate'] ?? date('Y-m-d', strtotime('-30 days'));
$end = $_GET['endDate'] ?? date('Y-m-d');

// Handle CSV Export FIRST (before any output)
if (isset($_GET['export_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bakery_report_' . $start . '_to_' . $end . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Rank', 'Product', 'Category', 'Units Sold', 'Revenue', 'Profit (60%)']);

    $stmt = $pdo->prepare("
        SELECT od.product_id, p.name AS product_name, c.name AS category_name, 
               SUM(od.quantity) AS units_sold, SUM(od.subtotal) AS revenue
        FROM orders_detail od
        JOIN orders o ON od.order_id = o.id
        JOIN products p ON od.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.deleted_at IS NULL
          AND o.status = 'delivered' 
          AND DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY od.product_id, p.name, c.name
        ORDER BY units_sold DESC
        LIMIT 50
    ");
    $stmt->execute([$start, $end]);

    $rank = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $profit = $row['revenue'] * 0.6;
        fputcsv($output, [
            $rank++,
            $row['product_name'],
            $row['category_name'] ?: 'Uncategorized',
            $row['units_sold'],
            number_format($row['revenue'], 2),
            number_format($profit, 2)
        ]);
    }

    fclose($output);
    exit();
}

// Total Revenue (from orders.total - includes delivery fees)
$totalStmt = $pdo->prepare("SELECT SUM(total) AS total_revenue FROM orders WHERE status = 'delivered' AND DATE(created_at) BETWEEN ? AND ?");
$totalStmt->execute([$start, $end]);
$totalRevenue = $totalStmt->fetchColumn() ?: 0;

// Top Sales Query
$topSalesStmt = $pdo->prepare("
    SELECT od.product_id, p.name AS product_name, c.name AS category_name,
           SUM(od.quantity) AS units_sold, SUM(od.subtotal) AS revenue
    FROM orders_detail od
    JOIN orders o ON od.order_id = o.id
    JOIN products p ON od.product_id = p.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.deleted_at IS NULL
      AND o.status = 'delivered' 
      AND DATE(o.created_at) BETWEEN ? AND ?
    GROUP BY od.product_id, p.name, c.name
    ORDER BY units_sold DESC
");
$topSalesStmt->execute([$start, $end]);
$topSales = $topSalesStmt->fetchAll(PDO::FETCH_ASSOC);

// NEW: Calculate total units sold from the already fetched data
$totalUnitsSold = 0;
foreach ($topSales as $item) {
    $totalUnitsSold += (int)$item['units_sold'];
}

$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 5 AND deleted_at IS NULL")->fetchColumn();

$topName = $topSales[0]['product_name'] ?? 'None';
$topUnits = $topSales[0]['units_sold'] ?? 0;

// Chart data
$chartDates = [];
$chartSales = [];
$current = new DateTime($start);
$endDate = new DateTime($end);
while ($current <= $endDate) {
    $dateStr = $current->format('Y-m-d');
    $chartDates[] = $current->format('d M');
    $daily = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered' AND DATE(created_at)='$dateStr'")->fetchColumn();
    $chartSales[] = (float)$daily;
    $current->modify('+1 day');
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
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php" class="active">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">
    <h1 class="page-title">Business Reports & Analytics</h1>

    <form method="GET" class="controls" style="margin-bottom: 2.5rem;">
        <div class="date-range-group" style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <input type="date" name="startDate" class="filter-select date-input" value="<?= htmlspecialchars($start) ?>">
                <span style="color:#8B4513; font-weight:500;">to</span>
                <input type="date" name="endDate" class="filter-select date-input" value="<?= htmlspecialchars($end) ?>">
            </div>
            <button type="submit" class="add-btn filter-btn">Filter</button>
            <a href="?export_csv=1&startDate=<?= urlencode($start) ?>&endDate=<?= urlencode($end) ?>" 
               class="add-btn export-btn" style="background: #28a745; text-decoration:none;">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </form>

    <div class="stats-grid" style="gap: 2.5rem; margin-bottom: 3rem;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-sack-dollar"></i></div>
            <h3>RM <?= number_format($totalRevenue, 2) ?></h3>
            <p>Total Sales (incl. fees)</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-crown"></i></div>
            <h3><?= htmlspecialchars($topName) ?></h3>
            <p>Top Product</p>
            <p style="color:#27AE60; font-weight:bold;"><?= $topUnits ?> units</p>
        </div>
        
        <!-- Updated card -->
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-boxes-stacked"></i></div>
            <h3><?= number_format($totalUnitsSold) ?></h3>
            <p>Total Units Sold</p>
        </div>

        <div class="stat-card" style="<?= $lowStock > 0 ? 'border-left: 6px solid #D97706;' : '' ?>">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle" style="color:#D97706;"></i></div>
            <h3><?= $lowStock ?></h3>
            <p>Low Stock Items</p>
        </div>
    </div>

    <!-- Rest of your page remains unchanged -->
    <div style="background:white; padding:2rem; border-radius:12px; margin-bottom:3rem;">
        <h2 style="margin-bottom:1rem; color:#8B4513;">Sales Trend (Delivered Orders)</h2>
        <canvas id="salesChart"></canvas>
    </div>

    <div class="table-card">
        <h2>Top Products (Delivered Orders)</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Units Sold</th>
                    <th>Revenue (excl. fees)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($topSales)): ?>
                    <tr><td colspan="5" style="text-align:center; padding:3rem; color:#999;">No delivered orders in this date range</td></tr>
                <?php else: ?>
                    <?php foreach (array_slice($topSales, 0, 10) as $rank => $item): ?>
                    <tr>
                        <td><?= $rank + 1 ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['category_name'] ?: 'Uncategorized') ?></td>
                        <td style="font-weight:bold;"><?= $item['units_sold'] ?></td>
                        <td>RM <?= number_format($item['revenue'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <p style="margin-top:1rem; font-size:0.9em; color:#666;">
            <strong>Note:</strong> Revenue excludes ~RM5/order delivery fees. Total sales above includes all fees.
        </p>
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