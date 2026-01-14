<?php
include 'config.php';

$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header('Location: payment.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch order items for display
$stmtItems = $pdo->prepare("SELECT product_name, price, quantity, subtotal FROM orders_detail WHERE order_id = ?");
$stmtItems->execute([$orderId]);
$orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include 'header.php'; ?>

    <style>
        /* Full-page thank-you layout */
        .order-wrapper { min-height: calc(100vh - 220px); display:flex; align-items:center; padding:3rem 1rem; background: linear-gradient(180deg,#fffdfa, #fff7f1); }
        .order-card { width:100%; max-width:1200px; margin:0 auto; background: white; border-radius:14px; padding:2.5rem; box-shadow: 0 20px 60px rgba(21,21,21,0.06); border:1px solid rgba(0,0,0,0.04); }
        .hero { display:flex; gap:1.25rem; align-items:center; padding:1.25rem; border-radius:12px; background: linear-gradient(90deg, rgba(212,167,106,0.08), rgba(255,250,245,0.04)); }
        .hero .check { width:88px; height:88px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; border:2px solid rgba(212,167,106,0.18); box-shadow:0 10px 30px rgba(111,45,45,0.06); }
        .hero h2 { margin:0; font-size:1.6rem; color:#2a2a2a; }
        .hero p { margin:0.3rem 0 0; color:#4c4c4c; }
        .meta { display:flex; gap:1rem; margin-top:1rem; flex-wrap:wrap; }
        .meta .item { padding:0.8rem 1rem; background:#fff; border-radius:10px; box-shadow: 0 6px 18px rgba(18,18,18,0.03); min-width:160px; }
        .meta .label { color:#7a7a7a; font-size:0.85rem; }
        .meta .value { font-weight:800; font-size:1.05rem; }
        .order-summary { margin-top:1.25rem; background:#fbfbfb; padding:1rem; border-radius:10px; }
        .order-summary h3 { margin:0 0 0.6rem 0; }
        .order-summary ul { list-style:none; padding:0; margin:0; }
        .order-summary li { display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px dashed rgba(0,0,0,0.03); }
        .actions { margin-top:1.25rem; }
        .btn { padding:0.8rem 1.2rem; border-radius:10px; background:#6f2d2d; color:#fff; text-decoration:none; font-weight:700; margin-right:0.75rem; }
        .btn.secondary { background:transparent; border:2px solid #d4a76a; color:#6f2d2d; }
        @media (max-width:720px) { .hero { flex-direction:column; align-items:flex-start; } .meta .item { min-width:120px; } }
    </style>

    <div class="order-wrapper">
        <div class="order-card">
            <div class="hero">
                <div class="check" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="44" height="44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 12.5l2.5 2.5L17 8" stroke="#1f8b45" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="120" stroke-dashoffset="120" style="animation:draw 0.6s ease forwards 0.1s"></path></svg>
                </div>
                <div>
                    <h2>Thank you! Your payment is complete.</h2>
                    <p>Order <strong>#<?php echo $order['id']; ?></strong> has been received. We'll prepare it and notify you when it's out for delivery.</p>
                </div>
            </div>

            <div class="meta">
                <div class="item"><div class="label">Amount</div><div class="value">RM <?php echo number_format($order['total'],2); ?></div></div>
                <div class="item"><div class="label">Payment Status</div><div class="value"><?php echo strtoupper($order['payment_status']); ?></div></div>
                <div class="item"><div class="label">Order Status</div><div class="value"><?php echo strtoupper($order['status']); ?></div></div>
            </div>

            <?php if (!empty($orderItems)): ?>
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <ul>
                        <?php foreach ($orderItems as $it): ?>
                            <li><span><?php echo htmlspecialchars($it['product_name']); ?> x <?php echo (int)$it['quantity']; ?></span><span><strong>RM <?php echo number_format($it['subtotal'],2); ?></strong></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="actions">
                <?php if ($order['payment_status'] === 'paid'): ?>
                    <a href="mainpage.php" class="btn">Return to Home</a>
                    <a href="menu.html" class="btn secondary">Browse More</a>
                    <script>try { localStorage.removeItem('bakeryCart'); } catch(e) {}</script>
                <?php elseif ($order['payment_status'] === 'pending'): ?>
                    <a href="payment.php?order_id=<?php echo $order['id']; ?>" class="btn">Complete Payment</a>
                    <a href="mainpage.php" class="btn secondary">Return to Home</a>
                <?php else: ?>
                    <a href="payment.php?order_id=<?php echo $order['id']; ?>" class="btn">Try Payment Again</a>
                    <a href="mainpage.php" class="btn secondary">Return to Home</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>