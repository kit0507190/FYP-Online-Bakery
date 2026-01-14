<?php
include 'config.php';

$orderId = $_GET['order_id'] ?? null;

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
?>
<?php include 'header.php'; ?>

    <div class="container">
        <div class="payment-content">
            <h1 class="payment-title">Debit Card - Authorization</h1>

            <div class="card-box" style="max-width:800px; margin:0 auto; padding:1.25rem; border-radius:8px; border:1px solid #eee; background:#fff">
                <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
                <p><strong>Amount:</strong> RM <?php echo number_format($order['total'], 2); ?></p>

                <p id="status">Authorizing payment... <span class="spinner" style="display:inline-block;width:18px;height:18px;border:3px solid #ccc;border-top-color:#333;border-radius:50%;animation:spin 1s linear infinite;vertical-align:middle;"></span></p>

                <div id="controls" style="display:none; margin-top:12px;">
                    <form method="post" action="payment_callback.php" style="display:inline">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <button class="place-order-btn" type="submit" name="action" value="paid" style="background:#2b8a3e;color:#fff;border:none;padding:10px 16px;border-radius:4px;">Simulate Approval</button>
                    </form>
                    <form method="post" action="payment_callback.php" style="display:inline;margin-left:8px;">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <button class="place-order-btn" type="submit" name="action" value="failed" style="background:#fff;color:#333;border:1px solid #ccc;padding:10px 16px;border-radius:4px;">Simulate Decline</button>
                    </form>
                </div>

                <p style="margin-top:.8rem;color:#666">(This simulates network latency / authorization. In production, this would be handled by the card issuer.)</p>

                <p style="margin-top:1rem;"><a href="payment.php">Return to Checkout</a> • <a href="mainpage.php">Return to Home</a></p>
            </div>
        </div>
    </div>

<script>
    // Simulate a short authorization delay, then reveal approve/decline controls
    setTimeout(() => {
        document.getElementById('status').textContent = 'Authorization complete.';
        document.getElementById('controls').style.display = 'block';
    }, 2200);
</script>

<?php include 'footer.php'; ?>