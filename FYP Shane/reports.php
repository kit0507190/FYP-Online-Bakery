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
    // Use the same dates (from GET or defaults)
    $export_start = $_GET['startDate'] ?? date('Y-m-d', strtotime('-30 days'));
    $export_end = $_GET['endDate'] ?? date('Y-m-d');

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bakery_report_' . $export_start . '_to_' . $export_end . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Rank', 'Product', 'Category', 'Units Sold', 'Revenue', 'Profit (60%)']);

    $stmt = $pdo->prepare("
        SELECT od.product_id, p.name AS product_name, c.name AS category_name, 
               SUM(od.quantity) AS units_sold, SUM(od.subtotal) AS revenue
        FROM orders_detail od
        JOIN orders o ON od.order_id = o.id
        JOIN products p ON od.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE o.status = 'delivered' AND DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY od.product_id
        ORDER BY units_sold DESC
        LIMIT 50
    ");
    $stmt->execute([$export_start, $export_end]);

    if ($stmt->rowCount() > 0) {
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
    } else {
        // If no data, still export headers + empty row
        fputcsv($output, ['No delivered orders in this date range', '', '', '', '', '']);
    }

    fclose($output);
    exit();
}

// Now generate report data for display (using same dates)
$totalStmt = $pdo->prepare("SELECT SUM(total) AS total_revenue FROM orders WHERE status = 'delivered' AND DATE(created_at) BETWEEN ? AND ?");
$totalStmt->execute([$start, $end]);
$totalRevenue = $totalStmt->fetchColumn() ?: 0;

$topSalesStmt = $pdo->prepare("
    SELECT od.product_id, p.name AS product_name, SUM(od.quantity) AS units_sold
    FROM orders_detail od
    JOIN orders o ON od.order_id = o.id
    JOIN products p ON od.product_id = p.id
    WHERE o.status = 'delivered' AND DATE(o.created_at) BETWEEN ? AND ?
    GROUP BY od.product_id
    ORDER BY units_sold DESC
");
$topSalesStmt->execute([$start, $end]);

$topSales = [];
while ($row = $topSalesStmt->fetch(PDO::FETCH_ASSOC)) {
    $topSales[] = $row;
}

$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 10")->fetchColumn();

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

        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php" class="active">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">
    <h1 class="page-title">Business Reports & Analytics</h1>

    <!-- Replace your current form.controls section with this -->

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
            <h3>RM <?= number_format($totalRevenue, 2) ?></h3>
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
                    foreach (array_slice($topSales, 0, 10) as $item):
                        $revStmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                        $revStmt->execute([$item['product_id']]);
                        $price = $revStmt->fetchColumn() ?: 0;
                        $rev = $price * $item['units_sold'];
                ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['units_sold'] ?></td>
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