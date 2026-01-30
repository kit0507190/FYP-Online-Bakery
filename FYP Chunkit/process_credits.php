<?php
session_start();
require_once 'config.php';

$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    header('Location: payment.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order || $order['payment_method'] !== 'credits') {
    echo "Invalid order.";
    exit;
}

include 'header.php';
?>

<link rel="stylesheet" href="process_debit.css?v=<?php echo time(); ?>">
<style>
    :root { --credit-green: #28a745; --credit-light: #e6ffe6; }
    .auth-card { border-top: 8px solid var(--credit-green); }
    .success-icon { color: var(--credit-green); }
    .btn-done { background: var(--credit-green); color: white; }
    .btn-done:hover { background: #218838; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <img src="payment logo/credits_logo.png" alt="Credits Logo" class="tng-logo" style="width: 120px; margin-bottom: 15px;"> <!-- Replace with your credits logo if you have one -->
        <div class="auth-icon-section">
            <i id="success-tick" class="fas fa-check-circle success-icon" style="display: block; font-size: 60px;"></i>
        </div>

        <h1 class="auth-title">Payment Successful!</h1>
        <p class="auth-subtitle">Your credits have been deducted. Time to enjoy your bakery treats!</p>

        <div class="receipt-box">
            <div class="receipt-row"><span class="receipt-label">Order ID</span><span>#<?php echo $order['id']; ?></span></div>
            <div class="receipt-row"><span class="receipt-label">Payment Method</span><span>Credits</span></div>
            <div class="receipt-row"><span class="receipt-label">Total Amount</span><span>RM <?php echo number_format($order['total'], 2); ?></span></div>
        </div>
    </div>
</div>

<div id="paymentSuccessModal" class="success-modal-overlay" style="display: flex;"> <!-- Show modal immediately -->
    <div class="success-modal-content">
        <div class="modal-icon-circle">
            <svg viewBox="0 0 24 24" width="50" height="50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 12.5l2.5 2.5L17 8" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
        <h2>Payment Successful!</h2>
        <p>Your credits have been deducted. Time to enjoy your bakery treats!</p>
        <button class="btn-done" onclick="goToOrderResult()">Done</button>
    </div>
</div>

<script>
    function goToOrderResult() {
        try { localStorage.removeItem('bakeryCart'); } catch(e) {}
        window.location.href = 'order_result.php?order_id=<?php echo $orderId; ?>';
    }
</script>

<?php include 'footer.php'; ?>
<link rel="stylesheet" href="footer.css">