admin_auth<?php
require_once 'admin_auth.php';  // Secure auth + loads $current_admin with role
require_once 'config.php';  // Main DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse | Manage Products</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="header">
    <h1>BakeryHouse Admin</h1>
    <div style="display: flex; align-items: center; gap: 20px;">
        <span>Welcome, <strong><?= htmlspecialchars($current_admin['username']) ?></strong> 
            (<span class="role-highlight"><?= ucfirst(str_replace('_', ' ', $current_admin['role'])) ?></span>)
        </span>
        <a href="admin_logout.php" class="logout">Logout</a>
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
    <div class="welcome-msg">
        Manage your bakery products — add new items, update details, or remove discontinued ones.
    </div>

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
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td><img src='$imagePath' alt='Product' style='width:60px; height:60px; object-fit:cover; border-radius:8px;'></td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>RM " . number_format($row['price'], 2) . "</td>
                            <td>" . htmlspecialchars($row['cat_name'] ?? 'Uncategorized') . "</td>
                            <td>{$row['stock']}</td>
                            <td>
                                <a href='?delete={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this product?')\" class='action-btn delete-btn'>Delete</a>
                            </td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// Handle Add Product
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $desc = trim($_POST['description']);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!array_key_exists($ext, $allowed)) {
            echo "<script>alert('Error: Only JPG, JPEG, PNG, GIF allowed');</script>";
        } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) { // 3MB max
            echo "<script>alert('Error: File too large (max 3MB)');</script>";
        } else {
            $newname = uniqid('prod_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $newname)) {
                $image = $newname;
            } else {
                echo "<script>alert('Error uploading image');</script>";
            }
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, category_id, stock, description, image) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $category_id, $stock, $desc, $image]);
        header("Location: manage_products.php?success=add");
        exit();
    } catch (Exception $e) {
        echo "<script>alert('Error adding product');</script>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Get image to delete file
        $image = $pdo->prepare("SELECT image FROM products WHERE id = ?")->execute([$id]);
        $image = $pdo->query("SELECT image FROM products WHERE id = $id")->fetchColumn();
        if ($image && file_exists('uploads/' . $image)) {
            unlink('uploads/' . $image);
        }

        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        header("Location: manage_products.php?success=delete");
        exit();
    } catch (Exception $e) {
        echo "<script>alert('Error deleting product');</script>";
    }
}
?>

<script>
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
</script>

</body>
</html>