<?php

require_once 'admin_auth.php';  // Secure auth + loads $current_admin with role
require_once 'config.php';  // Main DB connection

// Handle Add Product and Delete at the top - before any output
$error_message = '';

if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $stock = (int)$_POST['stock'];
    $desc = trim($_POST['description']);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!array_key_exists($ext, $allowed)) {
            $error_message = 'Error: Only JPG, JPEG, PNG, GIF allowed';
        } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
            $error_message = 'Error: File too large (max 3MB)';
        } else {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            $newname = uniqid('prod_') . '.' . $ext;
            $target = 'uploads/' . $newname;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image = $newname;
            } else {
                $error_message = 'Error uploading image. Check folder permissions.';
            }
        }
    }

    if (empty($error_message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, category_id, stock, description, image) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $category_id, $stock, $desc, $image]);
            header("Location: manage_products.php?success=add");
            exit();
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetchColumn();

        if ($image && file_exists('uploads/' . $image)) {
            unlink('uploads/' . $image);
        }

        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        header("Location: manage_products.php?success=delete");
        exit();
    } catch (PDOException $e) {
        $error_message = 'Error deleting product: ' . $e->getMessage();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Image Zoom Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            text-align: center;
        }

        #modalImage {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        #modalCaption {
            color: #fff;
            font-size: 1.4rem;
            margin-top: 20px;
            font-weight: bold;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .close:hover {
            color: #ff4444;
        }

        .product-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product-thumb:hover {
            transform: scale(1.15);
            border-color: #8B4513;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
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
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_products.php" class="active">Manage Products</a></li>
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
        <div class="alert error" style="margin-bottom: 2rem; padding: 1rem; background: #ffebee; color: #c62828; border-radius: 8px;">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success" style="margin-bottom: 2rem; padding: 1rem; background: #d4edda; color: #155724; border-radius: 8px;">
            <?= $_GET['success'] === 'add' ? 'Product added successfully!' : 'Product deleted successfully!' ?>
        </div>
    <?php endif; ?>

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
                    <div class="input-with-btns">
                        <input type="number" step="0.01" name="price" min="0" required placeholder="45.00" oninput="blockNegative(this)">
                        <div class="btn-group">
                            <button type="button" class="step-btn plus" onclick="addCents()">+0.10</button>
                            <button type="button" class="step-btn minus" onclick="subtractCents()">-0.10</button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" required>
                        <?php
                        $cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
                        foreach ($cats as $cat) {
                            echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <div class="input-with-btns">
                        <input type="number" name="stock" min="0" value="0" required oninput="blockNegative(this)">
                        <div class="btn-group">
                            <button type="button" class="step-btn plus" onclick="addStock()">+1</button>
                            <button type="button" class="step-btn minus" onclick="subtractStock()">-1</button>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Rich chocolate cake with creamy frosting..."></textarea>
                </div>

                <div class="form-group full-width">
                    <label>Product Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <button type="submit" name="add_product" class="add-btn">Add Product</button>
        </form>
    </div>

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
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
                if ($stmt->rowCount() == 0) {
                    echo '<tr><td colspan="7" style="text-align:center; color:#999; padding:4rem;">No products yet. Add one above!</td></tr>';
                } else {
                    while ($row = $stmt->fetch()) {
                        $imagePath = $row['image'] ? 'uploads/' . htmlspecialchars($row['image']) : 'images/placeholder.jpg';
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td>
                                <img src="<?= $imagePath ?>" 
                                     alt="<?= htmlspecialchars($row['name']) ?>" 
                                     class="product-thumb"
                                     onclick="openModal('<?= $imagePath ?>', '<?= htmlspecialchars($row['name']) ?>')">
                            </td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>RM <?= number_format($row['price'], 2) ?></td>
                            <td><?= htmlspecialchars($row['cat_name'] ?? 'Uncategorized') ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td>
                                <a href="?delete=<?= $row['id'] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this product?')" 
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
function addCents() { 
    let i = document.querySelector('input[name="price"]'); 
    i.value = (parseFloat(i.value || 0) + 0.10).toFixed(2); 
}
function subtractCents() { 
    let i = document.querySelector('input[name="price"]'); 
    let v = parseFloat(i.value || 0); 
    i.value = v >= 0.10 ? (v - 0.10).toFixed(2) : "0.00"; 
}
function addStock() { let i = document.querySelector('input[name="stock"]'); i.value = parseInt(i.value || 0) + 1; }
function subtractStock() { let i = document.querySelector('input[name="stock"]'); let v = parseInt(i.value || 0); if (v > 0) i.value = v - 1; }

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
</script>

</body>
</html>