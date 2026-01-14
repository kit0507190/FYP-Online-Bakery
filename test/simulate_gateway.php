<?php
include 'config.php';

$orderId = $_GET['order_id'] ?? null;
$method = $_GET['method'] ?? 'payment';

if (!$orderId) {
    header('Location: payment.php');
    exit;
}

// Fetch order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit;
}

$displayMethod = htmlspecialchars($method);
?>
<?php include 'header.php'; ?>

    <div class="container">
        <div class="payment-content">
            <h1 class="payment-title">Simulated Payment - <?php echo $displayMethod; ?></h1>

            <div class="card-box" style="max-width:800px; margin:0 auto; padding:1.25rem; border-radius:8px; border:1px solid #eee; background:#fff">
                <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
                <p><strong>Amount:</strong> RM <?php echo number_format($order['total'], 2); ?></p>
                <p>Please confirm your payment on this simulated <strong><?php echo $displayMethod; ?></strong> page.</p>

                <form method="post" action="payment_callback.php" style="margin-top:1rem;">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <input type="hidden" name="method" value="<?php echo htmlspecialchars($method); ?>">

                    <div style="display:flex; gap:12px;">
                        <button class="place-order-btn" type="submit" name="action" value="paid" style="background:#2b8a3e;color:#fff;border:none;padding:10px 16px;border-radius:4px;">Confirm Payment</button>
                        <button class="place-order-btn" type="submit" name="action" value="failed" style="background:#fff;color:#333;border:1px solid #ccc;padding:10px 16px;border-radius:4px;">Cancel / Fail Payment</button>
                    </div>
                </form>

                <p style="margin-top:.8rem;color:#666">(This is a simulation page. In production this would be handled by the payment provider.)</p>

                <p style="margin-top:1rem;"><a href="payment.php">Return to Checkout</a> • <a href="mainpage.php">Return to Home</a></p>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>