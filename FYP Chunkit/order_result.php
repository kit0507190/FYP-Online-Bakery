<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header('Location: payment.php');
    exit;
}

// 获取订单主表信息
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit;
}

// 获取订单详情
$stmtItems = $pdo->prepare("SELECT product_name, price, quantity, subtotal FROM orders_detail WHERE order_id = ?");
$stmtItems->execute([$orderId]);
$orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    :root {
        --primary: #5a3921; /* Chocolate Brown */
        --accent: #d4a76a;  /* Baking Gold */
        --bg: #fffcf9;      /* Creamy Background */
        --success: #1f8b45; /* Success Green */
    }

    body {
        background-color: var(--bg);
        font-family: 'Poppins', sans-serif;
    }

    .result-wrapper {
        min-height: calc(100vh - 250px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .result-card {
        background: #ffffff;
        width: 100%;
        max-width: 900px;
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(90, 57, 33, 0.1);
        overflow: hidden;
        animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hero-section {
        padding: 60px 40px 40px;
        text-align: center;
        background: linear-gradient(180deg, #fffaf5 0%, #ffffff 100%);
    }

    .status-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(31, 139, 69, 0.1);
        color: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 25px;
    }

    .hero-section h1 {
        color: var(--primary);
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .hero-section p {
        color: #777;
        font-size: 16px;
        line-height: 1.6;
    }

    .order-id-highlight {
        color: var(--accent);
        font-weight: 700;
        border-bottom: 2px dashed var(--accent);
    }

    /* Modification: Change grid to 2 columns for balanced layout */
    .info-bar {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 0 40px;
        margin-bottom: 40px;
    }

    .info-item {
        background: #fdfaf7;
        padding: 20px;
        border-radius: 20px;
        text-align: center;
        border: 1px solid #f1ece6;
    }

    .info-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }

    .info-value {
        font-weight: 700;
        color: var(--primary);
        font-size: 18px;
    }

    .summary-section {
        margin: 0 40px 40px;
        background: #fafafa;
        border-radius: 24px;
        padding: 30px;
    }

    .summary-header {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
        font-size: 18px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .product-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .product-row:last-child { border-bottom: none; }

    .product-name { color: #444; font-weight: 500; }
    .product-qty { color: #999; font-size: 14px; margin-left: 10px; }
    .product-price { font-weight: 700; color: var(--primary); }

    .total-row {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label { font-size: 18px; font-weight: 700; color: var(--primary); }
    .total-amount { font-size: 26px; font-weight: 800; color: var(--accent); }

    .action-group {
        padding: 0 40px 60px;
        display: flex;
        gap: 20px;
    }

    .btn-result {
        flex: 1;
        padding: 18px;
        border-radius: 15px;
        text-align: center;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--primary);
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #3e2717;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(90, 57, 33, 0.2);
    }

    .btn-secondary {
        border: 2px solid var(--accent);
        color: var(--primary);
    }

    .btn-secondary:hover {
        background-color: var(--accent);
        color: #fff;
        transform: translateY(-3px);
    }

    @media (max-width: 600px) {
        .info-bar { grid-template-columns: 1fr; }
        .action-group { flex-direction: column; }
    }
</style>

<div class="result-wrapper">
    <div class="result-card">
        <div class="hero-section">
            <div class="status-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1>Thank you! Your payment is complete.</h1>
            <p>Order <span class="order-id-highlight">#<?php echo $order['id']; ?></span> has been received. <br>
               We'll prepare it and notify you when it's out for delivery.</p>
        </div>

        <div class="info-bar">
            <div class="info-item">
                <span class="info-label">Total Amount</span>
                <span class="info-value">RM <?php echo number_format($order['total'], 2); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Payment Status</span>
                <span class="info-value" style="color: var(--success);">PAID</span>
            </div>
            </div>

        <div class="summary-section">
            <div class="summary-header">Order Summary</div>
            <?php foreach ($orderItems as $it): ?>
                <div class="product-row">
                    <div>
                        <span class="product-name"><?php echo htmlspecialchars($it['product_name']); ?></span>
                        <span class="product-qty">x <?php echo (int)$it['quantity']; ?></span>
                    </div>
                    <span class="product-price">RM <?php echo number_format($it['subtotal'], 2); ?></span>
                </div>
            <?php endforeach; ?>

            <div class="total-row">
                <span class="total-label">Grand Total</span>
                <span class="total-amount">RM <?php echo number_format($order['total'], 2); ?></span>
            </div>
        </div>

        <div class="action-group">
            <a href="mainpage.php" class="btn-result btn-primary">Return to Home</a>
            <a href="menu.php" class="btn-result btn-secondary">Browse More</a>
        </div>
    </div>
</div>

<?php if ($order['payment_status'] === 'paid'): ?>
    <script>
        try { localStorage.removeItem('bakeryCart'); } catch(e) {}
    </script>
<?php endif; ?>

<?php include 'footer.php'; ?>