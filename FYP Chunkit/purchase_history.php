<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userEmail = $_SESSION['user_email'] ?? ''; 

// ────────────────────────────────────────────────
//  Handle rating submission
// ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_rating') {
    $productId     = (int) ($_POST['product_id']     ?? 0);
    $rating        = (int) ($_POST['rating']        ?? 0);
    $orderDetailId = (int) ($_POST['order_detail_id'] ?? 0);
    $orderId       = (int) ($_POST['order_id']       ?? 0);

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating']);
        exit();
    }

    try {
        // Check if already rated
        $checkStmt = $pdo->prepare("SELECT 1 FROM product_ratings WHERE product_id = ? AND customer_email = ?");
        $checkStmt->execute([$productId, $userEmail]);
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Already rated']);
            exit();
        }

        // Insert rating
        $insertStmt = $pdo->prepare("
            INSERT INTO product_ratings 
            (product_id, customer_email, rating, order_detail_id, order_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insertStmt->execute([$productId, $userEmail, $rating, $orderDetailId, $orderId]);

        // Update product stats
        $updateStmt = $pdo->prepare("
            UPDATE products 
            SET 
                rating = (
                    SELECT AVG(rating) 
                    FROM product_ratings 
                    WHERE product_id = ?
                ),
                review_count = (
                    SELECT COUNT(*) 
                    FROM product_ratings 
                    WHERE product_id = ?
                )
            WHERE id = ?
        ");
        $updateStmt->execute([$productId, $productId, $productId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit();
}

try {
    // Get paid orders + items (filter deleted products)
    $query = "
        SELECT 
            o.id as order_id, o.total, o.created_at, o.payment_status, o.status,
            d.id as order_detail_id, d.product_id, d.product_name, d.price as item_price, d.quantity,
            p.image as product_image 
        FROM orders o 
        JOIN orders_detail d ON o.id = d.order_id 
        LEFT JOIN products p ON d.product_id = p.id AND p.deleted_at IS NULL
        WHERE o.customer_email = ? 
          AND o.payment_status = 'paid' 
        ORDER BY o.id DESC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userEmail]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get all products user already rated
    $ratingsStmt = $pdo->prepare("SELECT product_id FROM product_ratings WHERE customer_email = ?");
    $ratingsStmt->execute([$userEmail]);
    $userRatings = $ratingsStmt->fetchAll(PDO::FETCH_COLUMN);

    // Group items by order
    $orders = [];
    foreach ($results as $row) {
        $oid = $row['order_id'];
        if (!isset($orders[$oid])) {
            $orders[$oid] = [
                'id'     => $oid,
                'total'  => $row['total'],
                'date'   => $row['created_at'],
                'status' => $row['status'],
                'items'  => []
            ];
        }
        $orders[$oid]['items'][] = $row;
    }
} catch (PDOException $e) {
    die("Database Error: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - Bakery House</title>
    <link rel="stylesheet" href="purchase_history.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="purchase-container">
        <div class="header-card history-toolbar">
            <div class="toolbar-left">
                <h2 class="page-title">Purchase History</h2>
                <p class="page-subtitle">Track and manage your delicious orders</p>
                <hr style="width: 60px; border:none; border-top:3px solid #d4a76a; margin:15px auto; border-radius:10px;">
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
                <div class="shopee-card" data-status="<?= htmlspecialchars($order['status']) ?>">
                    <div class="card-header">
                        <div class="shop-info">
                            <span class="shop-brand"><i class="fas fa-store"></i> Bakery House</span>
                            <span class="order-id-tag">Order ID: #<?= $order['id'] ?></span>
                        </div>
                        <div class="order-meta">
                            <?php
                                $status = $order['status'];
                                $icon = match($status) {
                                    'preparing' => 'fa-spinner fa-spin',
                                    'ready'     => 'fa-cookie-bite',
                                    'delivered' => 'fa-truck-fast',
                                    'cancelled' => 'fa-circle-xmark',
                                    default     => 'fa-clock'
                                };
                            ?>
                            <span class="status-badge status-<?= htmlspecialchars($status) ?>">
                                <i class="fas <?= $icon ?>"></i>
                                <?= htmlspecialchars($status) ?>
                            </span>
                            <span class="order-date">
                                <i class="far fa-calendar-alt"></i> 
                                <?= date('d M Y, h:i A', strtotime($order['date'])) ?>
                            </span>
                        </div>
                    </div>

                    <?php foreach ($order['items'] as $item): ?>
                        <div class="product-item">
                            <?php 
                                $imgSrc = $item['product_image'] 
                                    ? 'product_images/' . $item['product_image'] 
                                    : 'product_images/placeholder.jpg';  
                            ?>
                            <img src="<?= htmlspecialchars($imgSrc) ?>" class="product-img" alt="Product">
                            
                            <div class="product-details">
                                <div class="name"><?= htmlspecialchars($item['product_name']) ?></div>
                                <div class="qty">Quantity: ×<?= $item['quantity'] ?></div>
                                
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <div class="rating-section" 
                                         data-product-id="<?= $item['product_id'] ?>"
                                         data-order-detail-id="<?= $item['order_detail_id'] ?>"
                                         data-order-id="<?= $order['id'] ?>">
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
                            <div class="price">RM <?= number_format($item['item_price'], 2) ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="card-footer">
                        <div class="total-row">
                            <span class="label">Total Amount:</span>
                            <span class="amount">RM <?= number_format($order['total'], 2) ?></span>
                        </div>
                        <button class="buy-again-btn" 
                                onclick='handleBuyAgain(<?= htmlspecialchars(json_encode($order['items'], JSON_NUMERIC_CHECK), ENT_QUOTES, 'UTF-8') ?>)'>
                            <i class="fas fa-redo"></i> Buy Again
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
    function filterOrders() {
        const filter = document.getElementById('statusFilter').value;
        document.querySelectorAll('.shopee-card').forEach(card => {
            const status = card.dataset.status;
            card.style.display = (filter === 'all' || status === filter) ? 'block' : 'none';
        });
    }

    // Rating stars (unchanged – keeping original logic)
    document.querySelectorAll('.rating-section').forEach(section => {
        const stars = section.querySelectorAll('.fa-star');
        let selected = 0;

        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const r = +star.dataset.rating;
                stars.forEach(s => {
                    const sr = +s.dataset.rating;
                    s.classList.toggle('fas', sr <= r);
                    s.classList.toggle('far', sr > r);
                    s.style.color = sr <= r ? '#d4a76a' : '#ccc';
                });
            });

            star.addEventListener('mouseout', () => {
                if (selected === 0) {
                    stars.forEach(s => {
                        s.classList.remove('fas');
                        s.classList.add('far');
                        s.style.color = '#ccc';
                    });
                }
            });

            star.addEventListener('click', async () => {
                selected = +star.dataset.rating;

                stars.forEach(s => {
                    const sr = +s.dataset.rating;
                    s.classList.toggle('fas', sr <= selected);
                    s.classList.toggle('far', sr > selected);
                    s.style.color = sr <= selected ? '#d4a76a' : '#ccc';
                });

                stars.forEach(s => s.style.pointerEvents = 'none');

                try {
                    section.insertAdjacentHTML('beforeend', '<span class="rating-loading">Sending...</span>');

                    const res = await fetch(location.href, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'submit_rating',
                            product_id: section.dataset.productId,
                            rating: selected,
                            order_detail_id: section.dataset.orderDetailId,
                            order_id: section.dataset.orderId
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        section.innerHTML = '<span class="rated-message">Thank you! ★</span>';
                    } else {
                        alert(data.message || 'Error submitting rating');
                        resetStars();
                    }
                } catch (err) {
                    console.error(err);
                    alert('Network error');
                    resetStars();
                } finally {
                    stars.forEach(s => s.style.pointerEvents = 'auto');
                }
            });
        });

        function resetStars() {
            stars.forEach(s => {
                s.classList.remove('fas');
                s.classList.add('far');
                s.style.color = '#ccc';
            });
        }
    });

    // ────────────────────────────────────────────────
    //  Buy Again – with client-side stock check from get_products.php
    // ────────────────────────────────────────────────
    async function handleBuyAgain(items) {
        if (!Array.isArray(items) || items.length === 0) return;

        const productIds = items.map(item => Number(item.product_id)).filter(id => id > 0);

        console.log('Buy Again: Sent product_ids for stock check', productIds);  // DEBUG

        let stockData = null;
        try {
            const res = await fetch('get_products.php', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin'  // Include cookies if needed
            });

            stockData = await res.json();

            console.log('Buy Again: Products data', stockData);  // DEBUG: see full products
        } catch (err) {
            console.error('Products fetch error:', err);
            alert('Error loading product data. Please try again.');
            return;
        }

        // Build stockMap from all products
        const stockMap = {};
        stockData.forEach(p => {
            stockMap[p.id] = {
                name: p.name,
                stock: Number(p.stock) || 0
            };
        });

        const canAdd = [];
        const cannotAdd = [];

        items.forEach(item => {
            const pid = Number(item.product_id);
            const want = Number(item.quantity) || 1;
            const info = stockMap[pid] || { stock: 0, name: item.product_name || `Product #${pid}` };

            if (info.stock >= 1) {
                const take = Math.min(want, info.stock);
                canAdd.push({
                    id: pid,
                    name: item.product_name,
                    price: Number(item.item_price),
                    image: item.product_image ? `product_images/${item.product_image}` : 'product_images/placeholder.jpg',
                    quantity: take,
                    note: take < want ? `(only ${take} left)` : ''
                });
            } else {
                cannotAdd.push({
                    name: item.product_name,
                    requested: want,
                    reason: stockMap.hasOwnProperty(pid) ? 'out of stock' : 'product not found (deleted or invalid ID)'
                });
            }
        });

        // Show feedback for unavailable items
        if (cannotAdd.length > 0) {
            let msg = "The following items are currently unavailable and were skipped:\n\n";
            cannotAdd.forEach(it => {
                msg += `• ${it.name} (wanted ×${it.requested}) - ${it.reason}\n`;
            });
            alert(msg.trim());
        }

        // If nothing can be added → early exit
        if (canAdd.length === 0) {
            alert("None of the items from this order are currently in stock.");
            return;
        }

        // Optional: confirm partial add
        if (canAdd.length < items.length) {
            const proceed = confirm(
                `Only ${canAdd.length} of ${items.length} items are available right now.\n\nAdd the available ones to your cart?`
            );
            if (!proceed) return;
        }

        // Update local cart
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];

        canAdd.forEach(newItem => {
            const idx = cart.findIndex(c => c.id === newItem.id);
            let current = 0;
            if (idx !== -1) {
                current = Number(cart[idx].quantity) || 0;
                cart.splice(idx, 1);
            }
            cart.unshift({
                id:       newItem.id,
                name:     newItem.name,
                price:    newItem.price,
                image:    newItem.image,
                quantity: current + newItem.quantity
            });
        });

        localStorage.setItem('bakeryCart', JSON.stringify(cart));

        // Sync to server (non-blocking)
        fetch('sync_cart.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart: cart }),
            credentials: 'same-origin'
        }).catch(err => console.error('Cart sync failed:', err));

        // Redirect to cart
        location.href = 'cart.php';
    }
    </script>
</body>
</html>