<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin
require_once 'admin_config.php';  // For PDO connection

// Handle adding new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = cleanAdminInput($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($stmt->execute([$name])) {
            $_SESSION['success'] = "Category added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add category.";
        }
    } else {
        $_SESSION['error'] = "Category name is required.";
    }
    header("Location: manage_categories.php");
    exit();
}

// Handle adding new subcategory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subcategory'])) {
    $category_id = (int)$_POST['category_id'];
    $name = cleanAdminInput($_POST['name']);
    if ($category_id > 0 && !empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO subcategories (category_id, name) VALUES (?, ?)");
        if ($stmt->execute([$category_id, $name])) {
            $_SESSION['success'] = "Subcategory added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add subcategory.";
        }
    } else {
        $_SESSION['error'] = "Please select a category and enter a name.";
    }
    header("Location: manage_categories.php");
    exit();
}

// Fetch all categories and subcategories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
$subcategories = [];
foreach ($pdo->query("SELECT * FROM subcategories ORDER BY name ASC") as $sub) {
    $subcategories[$sub['category_id']][] = $sub;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - BakeryHouse</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<nav class="sidebar">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="manage_categories.php" class="active">Manage Categories</a></li>
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
    

    <h1 style="margin-bottom: 2rem;">Manage Categories & Subcategories</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="background: #DFF0D8; color: #3C763D; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: #F2DEDE; color: #A94442; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Add Category Form -->
    <div class="form-card">
        <h2>Add New Category</h2>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>
            <button type="submit" name="add_category" class="add-btn">Add Category</button>
        </form>
    </div>

    <!-- Add Subcategory Form -->
    <div class="form-card">
        <h2>Add New Subcategory</h2>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="category_id">Parent Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Subcategory Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>
            <button type="submit" name="add_subcategory" class="add-btn">Add Subcategory</button>
        </form>
    </div>

    <!-- List Categories and Subcategories -->
    <div class="table-card">
        <h2>Existing Categories & Subcategories</h2>
        <table style="width:100%;">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Subcategories</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="3" style="text-align:center; padding:2rem; color:#999;">No categories yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td>
                                <?php if (isset($subcategories[$cat['id']])): ?>
                                    <ul style="list-style-type: disc; padding-left: 20px;">
                                        <?php foreach ($subcategories[$cat['id']] as $sub): ?>
                                            <li><?= htmlspecialchars($sub['name']) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    No subcategories
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y H:i', strtotime($cat['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>