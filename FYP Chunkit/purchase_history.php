<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userEmail = $_SESSION['user_email'] ?? ''; 

try {
    // 1. 获取订单数据（包含 status）
    $query = "SELECT o.id as order_id, o.total, o.created_at, o.payment_status, o.status,
                     d.product_id, d.product_name, d.price as item_price, d.quantity,
                     p.image as product_image 
              FROM orders o 
              JOIN orders_detail d ON o.id = d.order_id 
              LEFT JOIN products p ON d.product_id = p.id 
              WHERE o.customer_email = ? 
              AND o.payment_status = 'paid' 
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
                'status' => $row['status'],
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
    <link rel="stylesheet" href="purchase_history.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="purchase-container">
        <div class="header-card history-toolbar">
    <div class="toolbar-left">
        <h2 class="page-title">Purchase History</h2>
        <p class="page-subtitle">Track and manage your delicious orders</p>
        <hr style="width: 60px; border: none; border-top: 3px solid #d4a76a; margin: 15px auto; border-radius: 10px;">
    </div>
    
    <div class="filter-container">
        <div class="filter-box">
            <i class="fas fa-filter filter-icon"></i>
            <select id="statusFilter" class="modern-select" onchange="filterOrders()">
                <option value="all">All Orders</option>
                <option value="preparing">Preparing</option>
                <option value="ready">Ready</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>
</div>

        <?php if (empty($orders)): ?>
            <div class="shopee-card empty-state">
                <i class="fas fa-shopping-basket"></i>
                <p>No orders yet. Start your sweet journey!</p>
                <a href="menu.php" class="browse-link">Browse Menu</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="shopee-card" data-status="<?php echo htmlspecialchars($order['status']); ?>">
                    <div class="card-header">
                        <div class="shop-info">
                            <span class="shop-brand"><i class="fas fa-store"></i> Bakery House</span>
                            <span class="order-id-tag">Order ID: #<?php echo $order['id']; ?></span>
                        </div>
                        <div class="order-meta">
                            <?php
                                $status = $order['status'];
                                $icon = "fa-clock"; 
                                if($status == 'preparing') $icon = "fa-spinner fa-spin"; 
                                if($status == 'ready')     $icon = "fa-cookie-bite";
                                if($status == 'delivered') $icon = "fa-truck-fast";
                                if($status == 'cancelled') $icon = "fa-circle-xmark";
                            ?>

                            <span class="status-badge status-<?php echo htmlspecialchars($status); ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                            
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
                        <button class="buy-again-btn" onclick='handleBuyAgain(<?php echo htmlspecialchars(json_encode($order['items']), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <i class="fas fa-redo"></i> Buy Again
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
    // 🚀 JS 过滤逻辑
    function filterOrders() {
        const filterValue = document.getElementById('statusFilter').value;
        const cards = document.querySelectorAll('.shopee-card');
        
        cards.forEach(card => {
            const orderStatus = card.getAttribute('data-status');
            if (filterValue === 'all' || orderStatus === filterValue) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    async function handleBuyAgain(items) {
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || []; 
        items.forEach(item => {
            let pid = item.product_id;
            let existingIndex = cart.findIndex(c => c.id == pid);
            let currentQty = 0;
            if (existingIndex > -1) {
                currentQty = cart[existingIndex].quantity;
                cart.splice(existingIndex, 1); 
            }
            cart.push({
                id: pid,
                name: item.product_name,
                price: parseFloat(item.item_price),
                image: item.product_image || "cake/A_Little_Sweet.jpg",
                quantity: currentQty + parseInt(item.quantity)
            });
        });
        localStorage.setItem('bakeryCart', JSON.stringify(cart)); 
        try {
            await fetch('sync_cart.php?action=update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart: cart })
            });
            window.location.href = 'cart.php'; 
        } catch (e) {
            console.error("Sync error:", e);
            window.location.href = 'cart.php';
        }
    }
    </script>
</body>
</html>