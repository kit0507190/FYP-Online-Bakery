<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin with role
require_once 'admin_config.php';  // Main DB connection

// ────────────────────────────────────────────────
// Handle ADD, UPDATE, DELETE
// ────────────────────────────────────────────────
$error_message = '';

function uploadImage($file) {
    if ($file['error'] !== 0) return false;
    
    $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!array_key_exists($ext, $allowed) || $file['size'] > 3 * 1024 * 1024) {
        return false;
    }
    
    $upload_dir = 'product_images/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // You can keep uniqid or use original name sanitized
    // Option A: unique name (safer against overwrites)
    $newname = uniqid('prod_') . '.' . $ext;
    
    // Option B: keep original filename (cleaned) – uncomment if you prefer
    // $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($file['name']));
    // $newname = $filename;
    
    $target = $upload_dir . $newname;
    
    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $newname;           // ← only filename saved to DB
    }
    return false;
}

// ADD PRODUCT
if (isset($_POST['add_product'])) {
    $name           = trim($_POST['name'] ?? '');
    $price          = (float)($_POST['price'] ?? 0);
    $category_id    = (int)($_POST['category_id'] ?? 0);
    $subcategory_id = !empty($_POST['subcategory_id']) ? (int)$_POST['subcategory_id'] : null;
    $stock          = (int)($_POST['stock'] ?? 0);
    $description    = trim($_POST['description'] ?? '');
    $full_description = trim($_POST['full_description'] ?? '');
    $ingredients    = trim($_POST['ingredients'] ?? '');
    $size_info      = trim($_POST['size_info'] ?? '');

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $uploaded = uploadImage($_FILES['image']);
        if ($uploaded) {
            $image = $uploaded;
        } else {
            $error_message = 'Image upload failed (format/size/permission)';
        }
    }

    if (empty($error_message) && !empty($name) && $price > 0 && $category_id > 0) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products 
                (name, price, category_id, subcategory_id, stock, description, full_description, ingredients, size_info, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $price, $category_id, $subcategory_id, $stock, $description, $full_description, $ingredients, $size_info, $image]);
            header("Location: manage_products.php?success=add");
            exit();
        } catch (PDOException $e) {
            $error_message = 'Database error (add): ' . $e->getMessage();
        }
    }
}

// UPDATE PRODUCT
if (isset($_POST['update_product'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id < 1) $error_message = 'Invalid product ID';

    $name           = trim($_POST['name'] ?? '');
    $price          = (float)($_POST['price'] ?? 0);
    $category_id    = (int)($_POST['category_id'] ?? 0);
    $subcategory_id = !empty($_POST['subcategory_id']) ? (int)$_POST['subcategory_id'] : null;
    $stock          = (int)($_POST['stock'] ?? 0);
    $description    = trim($_POST['description'] ?? '');
    $full_description = trim($_POST['full_description'] ?? '');
    $ingredients    = trim($_POST['ingredients'] ?? '');
    $size_info      = trim($_POST['size_info'] ?? '');

    $image = $_POST['existing_image'] ?? '';

    if (!empty($_FILES['image']['name'])) {
        $uploaded = uploadImage($_FILES['image']);
        if ($uploaded) {
            // Delete old image if exists
            if ($image && file_exists('product_images/' . $image)) {
                @unlink('product_images/' . $image);
            }
            $image = $uploaded;
        } else {
            $error_message = 'New image upload failed';
        }
    }

    if (empty($error_message) && $id > 0 && !empty($name) && $price > 0 && $category_id > 0) {
        try {
            $stmt = $pdo->prepare("
                UPDATE products SET
                    name = ?, price = ?, category_id = ?, subcategory_id = ?, stock = ?,
                    description = ?, full_description = ?, ingredients = ?, size_info = ?, image = ?
                WHERE id = ?
            ");
            $stmt->execute([$name, $price, $category_id, $subcategory_id, $stock, $description, $full_description, $ingredients, $size_info, $image, $id]);
            header("Location: manage_products.php?success=update");
            exit();
        } catch (PDOException $e) {
            $error_message = 'Database error (update): ' . $e->getMessage();
        }
    }
}

// DELETE PRODUCT
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetchColumn();

        if ($image && file_exists('product_images/' . $image)) {
            @unlink('product_images/' . $image);
        }

        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        header("Location: manage_products.php?success=delete");
        exit();
    } catch (PDOException $e) {
        $error_message = 'Delete failed: ' . $e->getMessage();
    }
}

// LOAD PRODUCT FOR EDIT
$edit_product = null;
$editing = isset($_GET['edit']);
if ($editing) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $edit_product = $stmt->fetch();
    if (!$edit_product) {
        $error_message = "Product #{$id} not found.";
        $editing = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Manage Products</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/manage_product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Existing styles from your CSS are linked; these are minor additions for visibility */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
        .form-group input, .form-group select, .form-group textarea { 
            padding: 0.8rem; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; background: #fff; color: #333;
        }
        .form-group.full-width { grid-column: span 2; } /* For full-width fields on wider screens */
        @media (max-width: 768px) { .form-group.full-width { grid-column: span 1; } }
        .add-btn { background: #8B4513; color: white; padding: 1rem 2rem; border: none; border-radius: 50px; cursor: pointer; }
        .add-btn:hover { background: #A0522D; }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<nav class="sidebar">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php" class="active">Manage Products</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
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

    <?php if (!empty($error_message)): ?>
        <div class="alert error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">
            <?php
            switch ($_GET['success']) {
                case 'add':    echo 'Product added successfully!'; break;
                case 'update': echo 'Product updated successfully!'; break;
                case 'delete': echo 'Product deleted successfully!'; break;
                default:       echo 'Action completed.';
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if ($editing && $edit_product): ?>
        <!-- ────────────── EDIT FORM ────────────── -->
        <div class="form-card">
            <h2>Edit Product #<?= $edit_product['id'] ?></h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_product['image'] ?? '') ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($edit_product['name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Price (RM)</label>
                        <input type="number" step="0.01" name="price" min="0" required value="<?= number_format($edit_product['price'] ?? 0, 2) ?>" oninput="blockNegative(this)">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="category_id" required onchange="loadSubcategories()">
                            <option value="">Select Category</option>
                            <?php
                            $cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
                            foreach ($cats as $cat) {
                                $selected = ($cat['id'] == $edit_product['category_id']) ? 'selected' : '';
                                echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id">
                            <option value="">None / Optional</option>
                            <?php
                            $subcats = $pdo->query("SELECT s.*, c.name AS cat_name FROM subcategories s JOIN categories c ON s.category_id = c.id ORDER BY c.name, s.name")->fetchAll();
                            foreach ($subcats as $sub) {
                                $sel = ($sub['id'] == $edit_product['subcategory_id']) ? 'selected' : '';
                                echo "<option value='{$sub['id']}' data-category='{$sub['category_id']}' $sel>{$sub['cat_name']} - {$sub['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" min="0" required value="<?= $edit_product['stock'] ?? 0 ?>" oninput="blockNegative(this)">
                    </div>

                    <div class="form-group full-width">
                        <label>Short Description</label>
                        <textarea name="description" rows="4"><?= htmlspecialchars($edit_product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Full Description</label>
                        <textarea name="full_description" rows="6"><?= htmlspecialchars($edit_product['full_description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Ingredients</label>
                        <textarea name="ingredients" rows="4"><?= htmlspecialchars($edit_product['ingredients'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Size Info</label>
                        <input type="text" name="size_info" value="<?= htmlspecialchars($edit_product['size_info'] ?? '') ?>">
                    </div>

                    <div class="form-group full-width">
                        <label>Current Image</label>
                        <?php if (!empty($edit_product['image'])): ?>
                            <?php
                        $current_img = $edit_product['image'] && file_exists('product_images/' . $edit_product['image'])
                                 ? 'product_images/' . htmlspecialchars($edit_product['image'])
                                : 'images/placeholder.jpg';
                        ?>
                        <img src="<?= $current_img ?>" style="max-width:180px; border-radius:8px;" alt="Current">
                        <?php else: ?>
                            <p>No image</p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group full-width">
                        <label>Replace Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                </div>

                <button type="submit" name="update_product" class="add-btn">Save Changes</button>
                <a href="manage_products.php" class="add-btn" style="background:#757575;">Cancel</a>
            </form>
        </div>

    <?php else: ?>
        <!-- ────────────── ADD FORM (integrated with your fields) ────────────── -->
        <div class="form-card">
            <h2>Add New Product</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required placeholder="e.g., Chocolate Cake">
                    </div>

                    <div class="form-group">
                        <label>Price (RM)</label>
                        <input type="number" step="0.01" name="price" min="0" required placeholder="45.00" oninput="blockNegative(this)">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="category_id" required onchange="loadSubcategories()">
                            <option value="">Select Category</option>
                            <?php
                            $cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
                            foreach ($cats as $cat) {
                                echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id">
                            <option value="">Select Subcategory (Optional)</option>
                            <?php
                            $subcats = $pdo->query("SELECT s.*, c.name AS cat_name FROM subcategories s LEFT JOIN categories c ON s.category_id = c.id ORDER BY c.name, s.name")->fetchAll();
                            foreach ($subcats as $subcat) {
                                echo "<option value='{$subcat['id']}' data-category='{$subcat['category_id']}'>{$subcat['cat_name']} - {$subcat['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" min="0" value="0" required oninput="blockNegative(this)">
                    </div>

                    <div class="form-group full-width">
                        <label>Short Description</label>
                        <textarea name="description" rows="4" placeholder="Rich chocolate cake with creamy frosting..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Full Description</label>
                        <textarea name="full_description" rows="6" placeholder="Detailed description here..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Ingredients</label>
                        <textarea name="ingredients" rows="4" placeholder="Flour, sugar, eggs, etc."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Size Info</label>
                        <input type="text" name="size_info" placeholder="e.g., 5 INCH">
                    </div>

                    <div class="form-group full-width">
                        <label>Product Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                </div>

                <button type="submit" name="add_product" class="add-btn">Add Product</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- PRODUCT LIST -->
    <div class="table-card">
        <h2>Product List</h2>
        <table id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT p.*, c.name AS cat_name, s.name AS subcat_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN subcategories s ON p.subcategory_id = s.id 
                    ORDER BY p.id DESC
                ");
                if ($stmt->rowCount() == 0) {
                    echo '<tr><td colspan="8" style="text-align:center; padding:3rem; color:#888;">No products found.</td></tr>';
                } else {
                    while ($row = $stmt->fetch()) {
                        $img = $row['image'] && file_exists('product_images/' . $row['image'])
                            ? 'product_images/' . htmlspecialchars($row['image'])
                             : 'images/placeholder.jpg';
                        $sub = $row['subcat_name'] ? htmlspecialchars($row['subcat_name']) : '—';
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td>
                                <img src="<?= $img ?>" class="product-thumb" 
                                     alt="<?= htmlspecialchars($row['name']) ?>"
                                     onclick="openModal('<?= $img ?>', '<?= htmlspecialchars($row['name']) ?>')">
                            </td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>RM <?= number_format($row['price'], 2) ?></td>
                            <td><?= htmlspecialchars($row['cat_name'] ?? '—') ?></td>
                            <td><?= $sub ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td>
                                <a href="?edit=<?= $row['id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="?delete=<?= $row['id'] ?>" 
                                   onclick="return confirm('Delete this product permanently?')" 
                                   class="action-btn delete-btn">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Image Zoom Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImage" src="" alt="Product Image">
            <div id="modalCaption"></div>
        </div>
    </div>

</main>

<script>
// Helper functions for price/stock buttons
function blockNegative(input) {
    if (input.value < 0) input.value = 0;
}

// Modal functions
function openModal(src, caption) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');

    modal.style.display = 'flex';
    modalImg.src = src;
    captionText.innerHTML = caption;

    // Close when clicking outside image
    modal.onclick = function(e) {
        if (e.target === modal) closeModal();
    }
}

function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Dynamic subcategory filter
function loadSubcategories() {
    const catId = document.getElementById('category_id')?.value;
    if (!catId) return;

    const subSelect = document.getElementById('subcategory_id');
    if (!subSelect) return;

    Array.from(subSelect.options).forEach(opt => {
        if (opt.value === '' || opt.dataset.category === catId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });

    // Reset if current selection is hidden
    if (subSelect.value && subSelect.options[subSelect.selectedIndex].style.display === 'none') {
        subSelect.value = '';
    }
}

// Run once on page load (especially useful for edit mode)
document.addEventListener('DOMContentLoaded', loadSubcategories);
</script>

</body>
</html>