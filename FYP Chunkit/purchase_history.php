<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userEmail = $_SESSION['user_email'] ?? ''; 

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_rating') {
    $productId = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $orderDetailId = intval($_POST['order_detail_id']);
    $orderId = intval($_POST['order_id']);

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating']);
        exit();
    }

    try {
        // Check if already rated
        $checkStmt = $pdo->prepare("SELECT id FROM product_ratings WHERE product_id = ? AND customer_email = ?");
        $checkStmt->execute([$productId, $userEmail]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Already rated']);
            exit();
        }

        // Insert rating
        $insertStmt = $pdo->prepare("
            INSERT INTO product_ratings (product_id, customer_email, rating, order_detail_id, order_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insertStmt->execute([$productId, $userEmail, $rating, $orderDetailId, $orderId]);

        // Recalculate average and count
        $updateStmt = $pdo->prepare("
            UPDATE products p
            SET 
                p.rating = (
                    SELECT AVG(r.rating) 
                    FROM product_ratings r 
                    WHERE r.product_id = p.id
                ),
                p.review_count = (
                    SELECT COUNT(*) 
                    FROM product_ratings r 
                    WHERE r.product_id = p.id
                )
            WHERE p.id = ?
        ");
        $updateStmt->execute([$productId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

try {
    // 1. 获取订单数据（包含 status）
    $query = "SELECT o.id as order_id, o.total, o.created_at, o.payment_status, o.status,
                     d.id as order_detail_id, d.product_id, d.product_name, d.price as item_price, d.quantity,
                     p.image as product_image 
              FROM orders o 
              JOIN orders_detail d ON o.id = d.order_id 
              LEFT JOIN products p ON d.product_id = p.id 
              WHERE o.customer_email = ? 
              AND deleted_at IS NULL 
              AND o.payment_status = 'paid' 
              ORDER BY o.id DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userEmail]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all user's ratings in one query for efficiency
    $ratingsStmt = $pdo->prepare("SELECT product_id FROM product_ratings WHERE customer_email = ?");
    $ratingsStmt->execute([$userEmail]);
    $userRatings = $ratingsStmt->fetchAll(PDO::FETCH_COLUMN); // Array of rated product_ids

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
                                $imgSrc = !empty($item['product_image']) 
                                ? 'product_images/' . $item['product_image'] 
                                : 'product_images/placeholder.jpg';  
                            ?>
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="product-img" alt="Product">
                            
                            <div class="product-details">
                                <div class="name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="qty">Quantity: x<?php echo $item['quantity']; ?></div>
                                
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <div class="rating-section" 
                                        data-product-id="<?php echo $item['product_id']; ?>"
                                        data-order-detail-id="<?php echo $item['order_detail_id']; ?>"
                                        data-order-id="<?php echo $order['id']; ?>">
                                        <?php if (in_array($item['product_id'], $userRatings)): ?>
                                            <span class="rated-message">Rated!</span>
                                        <?php else: ?>
                                            <div class="stars">
                                                <i class="far fa-star" data-rating="1"></i>
                                                <i class="far fa-star" data-rating="2"></i>
                                                <i class="far fa-star" data-rating="3"></i>
                                                <i class="far fa-star" data-rating="4"></i>
                                                <i class="far fa-star" data-rating="5"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
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

    // Rating JS
    // Rating JS - auto-submit version
document.querySelectorAll('.rating-section').forEach(section => {
    const stars = section.querySelectorAll('.fa-star');
    let selectedRating = 0;

    // Highlight stars on hover & click
    stars.forEach(star => {
        // Hover preview
        star.addEventListener('mouseover', () => {
            const rating = star.dataset.rating;
            stars.forEach(s => {
                s.classList.toggle('fas', s.dataset.rating <= rating);
                s.classList.toggle('far', s.dataset.rating > rating);
                s.style.color = s.dataset.rating <= rating ? '#d4a76a' : '#ccc';
            });
        });

        // Reset on mouse out (unless clicked)
        star.addEventListener('mouseout', () => {
            if (selectedRating === 0) {
                stars.forEach(s => {
                    s.classList.remove('fas');
                    s.classList.add('far');
                    s.style.color = '#ccc';
                });
            }
        });

        // Click to select & submit
        star.addEventListener('click', async () => {
            selectedRating = star.dataset.rating;

            // Final highlight
            stars.forEach(s => {
                s.classList.toggle('fas', s.dataset.rating <= selectedRating);
                s.classList.toggle('far', s.dataset.rating > selectedRating);
                s.style.color = s.dataset.rating <= selectedRating ? '#d4a76a' : '#ccc';
            });

            // Disable stars during submit
            stars.forEach(s => s.style.pointerEvents = 'none');

            const productId = section.dataset.productId;
            const orderDetailId = section.dataset.orderDetailId;
            const orderId = section.dataset.orderId;

            try {
                // Show brief loading state (optional)
                section.innerHTML += '<span class="rating-loading">Sending...</span>';

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'submit_rating',
                        product_id: productId,
                        rating: selectedRating,
                        order_detail_id: orderDetailId,
                        order_id: orderId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    section.innerHTML = '<span class="rated-message">Thank you! ★</span>';
                } else {
                    alert(result.message || 'Error submitting rating');
                    // Reset stars if failed
                    stars.forEach(s => {
                        s.classList.remove('fas');
                        s.classList.add('far');
                        s.style.color = '#ccc';
                    });
                }
            } catch (e) {
                console.error(e);
                alert('Network error');
                // Reset on error
                stars.forEach(s => {
                    s.classList.remove('fas');
                    s.classList.add('far');
                    s.style.color = '#ccc';
                });
            } finally {
                stars.forEach(s => s.style.pointerEvents = 'auto');
            }
        });
    });
});

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