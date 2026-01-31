<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin with role
require_once 'admin_config.php';

// Get current script name once (used for redirects)
$current_page = basename(__FILE__);  // will be "admin_restore.php"

// Messages
$success = $error = '';

// Handle RESTORE actions
if (isset($_GET['restore'])) {
    $type = $_GET['type'] ?? '';
    $id = (int)$_GET['restore'];
    
    if ($id > 0 && in_array($type, ['product', 'category', 'subcategory'])) {
        $table = match($type) {
            'product'     => 'products',
            'category'    => 'categories',
            'subcategory' => 'subcategories',
            default       => ''
        };
        
        if ($table) {
            try {
                $stmt = $pdo->prepare("UPDATE $table SET deleted_at = NULL WHERE id = ? AND deleted_at IS NOT NULL");
                $stmt->execute([$id]);
                header("Location: $current_page?success=restore");
                exit();
            } catch (PDOException $e) {
                $error = "Restore failed: " . $e->getMessage();
            }
        }
    }
}

// Handle PERMANENT DELETE actions
if (isset($_GET['perm_delete'])) {
    $type = $_GET['type'] ?? '';
    $id = (int)$_GET['perm_delete'];
    
    if ($id > 0 && in_array($type, ['product', 'category', 'subcategory'])) {
        $table = match($type) {
            'product'     => 'products',
            'category'    => 'categories',
            'subcategory' => 'subcategories',
            default       => ''
        };
        
        if ($table) {
            try {
                if ($type === 'product') {
                    // Special: delete image file if exists
                    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                    $image = $stmt->fetchColumn();
                    if ($image && file_exists('product_images/' . $image)) {
                        @unlink('product_images/' . $image);
                    }
                }
                
                $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
                $stmt->execute([$id]);
                
                header("Location: $current_page?success=perm_delete");
                exit();
            } catch (PDOException $e) {
                $error = "Permanent delete failed: " . $e->getMessage();
            }
        }
    }
}

// Fetch deleted items
$deleted_products = $pdo->query("
    SELECT p.*, c.name AS cat_name, s.name AS subcat_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN subcategories s ON p.subcategory_id = s.id 
    WHERE p.deleted_at IS NOT NULL 
    ORDER BY p.deleted_at DESC
")->fetchAll();

$deleted_categories = $pdo->query("
    SELECT * FROM categories 
    WHERE deleted_at IS NOT NULL 
    ORDER BY deleted_at DESC
")->fetchAll();

$deleted_subcategories = $pdo->query("
    SELECT s.*, c.name AS cat_name 
    FROM subcategories s 
    LEFT JOIN categories c ON s.category_id = c.id 
    WHERE s.deleted_at IS NOT NULL 
    ORDER BY s.deleted_at DESC
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Restore Deleted Items</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/admin_restore.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <li><a href="admin_restore.php" class="active">Restore Deleted</a></li>
        <li><a href="user_comments.php">User Comments</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main">
    <?php if ($error): ?>
        <div class="alert error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <?= $_GET['success'] === 'restore' ? 'Item restored successfully!' : 'Item permanently deleted!' ?>
        </div>
    <?php endif; ?>

    <h1 class="page-title">Restore Deleted Items</h1>

    <div class="tab-container">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="products">Products (<?= count($deleted_products) ?>)</button>
            <button class="tab-btn" data-tab="categories">Categories (<?= count($deleted_categories) ?>)</button>
            <button class="tab-btn" data-tab="subcategories">Subcategories (<?= count($deleted_subcategories) ?>)</button>
        </div>

        <!-- Products Tab -->
        <div id="products" class="tab-content active">
            <div class="table-card">
                <h2>Deleted Products</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($deleted_products)): ?>
                            <tr><td colspan="6" style="text-align:center; padding:3rem; color:#888;">No deleted products.</td></tr>
                        <?php else: ?>
                            <?php foreach ($deleted_products as $row): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['cat_name'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($row['subcat_name'] ?? '—') ?></td>
                                    <td><?= date('d M Y H:i', strtotime($row['deleted_at'])) ?></td>
                                    <td>
                                        <a href="?restore=<?= $row['id'] ?>&type=product" class="action-btn edit-btn" onclick="return confirm('Restore this product?');">Restore</a>
                                        <a href="?perm_delete=<?= $row['id'] ?>&type=product" class="action-btn delete-btn" onclick="return confirm('Permanently delete this product? This cannot be undone.');">Permanent Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories Tab -->
        <div id="categories" class="tab-content">
            <div class="table-card">
                <h2>Deleted Categories</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($deleted_categories)): ?>
                            <tr><td colspan="4" style="text-align:center; padding:3rem; color:#888;">No deleted categories.</td></tr>
                        <?php else: ?>
                            <?php foreach ($deleted_categories as $row): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($row['deleted_at'])) ?></td>
                                    <td>
                                        <a href="?restore=<?= $row['id'] ?>&type=category" class="action-btn edit-btn" onclick="return confirm('Restore this category?');">Restore</a>
                                        <a href="?perm_delete=<?= $row['id'] ?>&type=category" class="action-btn delete-btn" onclick="return confirm('Permanently delete this category? This cannot be undone.');">Permanent Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subcategories Tab -->
        <div id="subcategories" class="tab-content">
            <div class="table-card">
                <h2>Deleted Subcategories</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($deleted_subcategories)): ?>
                            <tr><td colspan="5" style="text-align:center; padding:3rem; color:#888;">No deleted subcategories.</td></tr>
                        <?php else: ?>
                            <?php foreach ($deleted_subcategories as $row): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['cat_name'] ?? '—') ?></td>
                                    <td><?= date('d M Y H:i', strtotime($row['deleted_at'])) ?></td>
                                    <td>
                                        <a href="?restore=<?= $row['id'] ?>&type=subcategory" class="action-btn edit-btn" onclick="return confirm('Restore this subcategory?');">Restore</a>
                                        <a href="?perm_delete=<?= $row['id'] ?>&type=subcategory" class="action-btn delete-btn" onclick="return confirm('Permanently delete this subcategory? This cannot be undone.');">Permanent Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
// Simple tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});
</script>

</body>
</html>