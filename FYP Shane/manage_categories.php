<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin
require_once 'admin_config.php';  // For PDO connection

// Messages
$success = $error = '';

// Handle adding new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = cleanAdminInput($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($stmt->execute([$name])) {
            $success = "Category added successfully!";
        } else {
            $error = "Failed to add category.";
        }
    } else {
        $error = "Category name is required.";
    }
}

// Handle adding new subcategory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subcategory'])) {
    $category_id = (int)$_POST['category_id'];
    $name = cleanAdminInput($_POST['name']);
    if ($category_id > 0 && !empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO subcategories (category_id, name) VALUES (?, ?)");
        if ($stmt->execute([$category_id, $name])) {
            $success = "Subcategory added successfully!";
        } else {
            $error = "Failed to add subcategory.";
        }
    } else {
        $error = "Please select a category and enter a name.";
    }
}

// Handle EDIT category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $id = (int)$_POST['id'];
    $name = cleanAdminInput($_POST['name']);
    if ($id > 0 && !empty($name)) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        if ($stmt->execute([$name, $id])) {
            $success = "Category updated successfully!";
        } else {
            $error = "Failed to update category.";
        }
    } else {
        $error = "Invalid category name.";
    }
}

// Handle EDIT subcategory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_subcategory'])) {
    $id = (int)$_POST['id'];
    $name = cleanAdminInput($_POST['name']);
    if ($id > 0 && !empty($name)) {
        $stmt = $pdo->prepare("UPDATE subcategories SET name = ? WHERE id = ?");
        if ($stmt->execute([$name, $id])) {
            $success = "Subcategory updated successfully!";
        } else {
            $error = "Failed to update subcategory.";
        }
    } else {
        $error = "Invalid subcategory name.";
    }
}

// Handle DELETE category (soft delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $id = (int)$_POST['id'];
    if ($id > 0) {
        // Still check for active subcategories
        $check = $pdo->prepare("
            SELECT COUNT(*) 
            FROM subcategories 
            WHERE category_id = ? AND deleted_at IS NULL
        ");
        $check->execute([$id]);
        
        if ($check->fetchColumn() > 0) {
            $error = "Cannot delete category: It still has active subcategories.";
        } else {
            $stmt = $pdo->prepare("
                UPDATE categories 
                SET deleted_at = NOW() 
                WHERE id = ? AND deleted_at IS NULL
            ");
            if ($stmt->execute([$id])) {
                $success = "Category moved to trash!";
            } else {
                $error = "Failed to delete category.";
            }
        }
    }
}

// Handle DELETE subcategory (soft delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subcategory'])) {
    $id = (int)$_POST['id'];
    if ($id > 0) {
        $stmt = $pdo->prepare("
            UPDATE subcategories 
            SET deleted_at = NOW() 
            WHERE id = ? AND deleted_at IS NULL
        ");
        if ($stmt->execute([$id])) {
            $success = "Subcategory moved to trash!";
        } else {
            $error = "Failed to delete subcategory.";
        }
    }
}

// New: only active (not deleted)
$categories = $pdo->query("
    SELECT * FROM categories 
    WHERE deleted_at IS NULL 
    ORDER BY name ASC
")->fetchAll();

$subcategories = [];
$stmt = $pdo->query("
    SELECT * FROM subcategories 
    WHERE deleted_at IS NULL 
    ORDER BY name ASC
");
while ($sub = $stmt->fetch()) {
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
    <link rel="stylesheet" href="css/manage_categories.css">
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
        <li><a href="admin_restore.php">Restore Deleted</a></li>
        <?php if ($current_admin['role'] === 'super_admin'): ?>
            <li><a href="user_accounts.php">User Accounts</a></li>
            <li><a href="manage_admins.php">Manage Admins</a></li>
            <li><a href="reports.php">Reports</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="main manage-categories">
    <h1 class="page-title">Manage Categories & Subcategories</h1>

    <?php if ($success): ?>
        <div class="alert success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Add Category Form -->
    <div class="form-card">
        <h2>Add New Category</h2>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="cat_name">Category Name</label>
                    <input type="text" id="cat_name" name="name" required placeholder="e.g. Cake, Bread, Pastry">
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
                    <label for="sub_name">Subcategory Name</label>
                    <input type="text" id="sub_name" name="name" required placeholder="e.g. Cheese Flavour, Croissants">
                </div>
            </div>
            <button type="submit" name="add_subcategory" class="add-btn">Add Subcategory</button>
        </form>
    </div>

    <!-- List Categories and Subcategories -->
    <div class="table-card">
        <h2>Existing Categories & Subcategories</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding:3rem; color:#999;">
                            No categories yet. Add one above!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <!-- Category Row -->
                        <tr>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td><strong>Category</strong></td>
                            <td><?= date('d M Y H:i', strtotime($cat['created_at'])) ?></td>
                            <td class="actions">
                                <button class="edit-btn" onclick="showEditForm('cat-<?= $cat['id'] ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?\n\n(Only possible if it has no subcategories)');">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                    <button type="submit" name="delete_category" class="delete-btn">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Form Row -->
                        <tr id="cat-<?= $cat['id'] ?>" class="edit-row" style="display:none;">
                            <td colspan="4">
                                <form method="POST" class="edit-form">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                    <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
                                    <button type="submit" name="edit_category" class="save-btn">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                    <button type="button" onclick="hideEditForm('cat-<?= $cat['id'] ?>')" class="cancel-btn">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Subcategories -->
                        <?php if (isset($subcategories[$cat['id']])): ?>
                            <?php foreach ($subcategories[$cat['id']] as $sub): ?>
                                <tr>
                                    <td class="subcategory">↳ <?= htmlspecialchars($sub['name']) ?></td>
                                    <td>Subcategory</td>
                                    <td><?= date('d M Y H:i', strtotime($sub['created_at'])) ?></td>
                                    <td class="actions">
                                        <button class="edit-btn" onclick="showEditForm('sub-<?= $sub['id'] ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                            <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                            <button type="submit" name="delete_subcategory" class="delete-btn">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Form Row for Subcategory -->
                                <tr id="sub-<?= $sub['id'] ?>" class="edit-row" style="display:none;">
                                    <td colspan="4">
                                        <form method="POST" class="edit-form">
                                            <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                            <input type="text" name="name" value="<?= htmlspecialchars($sub['name']) ?>" required>
                                            <button type="submit" name="edit_subcategory" class="save-btn">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                            <button type="button" onclick="hideEditForm('sub-<?= $sub['id'] ?>')" class="cancel-btn">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function showEditForm(id) {
    document.getElementById(id).style.display = 'table-row';
}
function hideEditForm(id) {
    document.getElementById(id).style.display = 'none';
}
</script>

</body>
</html>