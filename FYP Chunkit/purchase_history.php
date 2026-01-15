<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userEmail = $_SESSION['user_email'] ?? ''; 

try {
    // 获取订单数据
    $query = "SELECT o.id as order_id, o.total, o.created_at,
                     d.product_id, d.product_name, d.price as item_price, d.quantity,
                     p.image as product_image 
              FROM orders o 
              JOIN orders_detail d ON o.id = d.order_id 
              LEFT JOIN products p ON d.product_id = p.id 
              WHERE o.customer_email = ? 
              ORDER BY o.id DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userEmail]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orders = [];
    foreach ($results as $row) {
        $oid = $row['order_id'];
        if (!isset($orders[$oid])) {
            $orders[$oid] = [
                'id' => $oid,
                'total' => $row['total'],
                'date' => $row['created_at'],
                'items' => []
            ];
        }
        $orders[$oid]['items'][] = $row;
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - Bakery House</title>
    
    <link rel="stylesheet" href="purchase_history.css?v=2.1">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="purchase-container">
        <div class="header-card">
            <h2 class="page-title">Purchase History</h2>
        </div>

        <?php if (empty($orders)): ?>
            <div class="shopee-card" style="text-align: center; padding: 50px;">
                <i class="fas fa-shopping-basket" style="font-size: 50px; color: #eee; margin-bottom: 20px;"></i>
                <p style="color: #999;">No orders yet. Start your sweet journey!</p>
                <a href="menu.php" style="color: var(--accent); font-weight: bold; text-decoration: none;">Browse Menu</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="shopee-card">
                    <div class="card-header">
                        <div class="shop-info">
                            <span class="shop-brand"><i class="fas fa-store"></i> Bakery House</span>
                            <span class="order-id-tag">Order ID: #<?php echo $order['id']; ?></span>
                        </div>
                        <div class="order-meta">
                            <span class="order-date">
                                <i class="far fa-calendar-alt"></i> 
                                <?php echo date('d M Y, h:i A', strtotime($order['date'])); ?>
                            </span>
                        </div>
                    </div>

                    <?php foreach ($order['items'] as $item): ?>
                        <div class="product-item">
                            <?php 
                                $imgSrc = !empty($item['product_image']) ? $item['product_image'] : 'cake/A_Little_Sweet.jpg'; 
                            ?>
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="product-img" alt="Product">
                            
                            <div class="product-details">
                                <div class="name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="qty">Quantity: x<?php echo $item['quantity']; ?></div>
                            </div>
                            <div class="price">RM <?php echo number_format($item['item_price'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="card-footer">
                        <div class="total-row">
                            <span class="label">Total Amount:</span>
                            <span class="amount">RM <?php echo number_format($order['total'], 2); ?></span>
                        </div>
                        <button class="buy-again-btn" onclick='handleBuyAgain(<?php echo json_encode($order['items']); ?>)'>
                            <i class="fas fa-redo"></i> Buy Again
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function handleBuyAgain(items) {
            let cart = JSON.parse(localStorage.getItem('bakeryCart')) || []; 

            items.forEach(item => {
                let existing = cart.find(c => c.id == item.product_id);
                if (existing) {
                    existing.quantity += parseInt(item.quantity);
                } else {
                    cart.push({
                        id: item.product_id,
                        name: item.product_name,
                        price: parseFloat(item.item_price),
                        image: item.product_image || "cake/A_Little_Sweet.jpg",
                        quantity: parseInt(item.quantity)
                    });
                }
            });

            localStorage.setItem('bakeryCart', JSON.stringify(cart)); 
            window.location.href = 'cart.php'; 
        }
    </script>
</body>
</html>