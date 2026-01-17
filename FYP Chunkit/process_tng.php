<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'config.php';

$orderId = $_GET['order_id'] ?? null;
if (!$orderId) { header('Location: payment.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) { echo "Order not found."; exit; }
include 'header.php'; 
?>

<link rel="stylesheet" href="process_debit.css?v=<?php echo time(); ?>">
<style>
    :root { --tng-blue: #0059b3; --tng-light: #e6f2ff; }
    .auth-card { border-top: 8px solid var(--tng-blue); }
    .spinner-ring { border-top: 4px solid var(--tng-blue); }
    .btn-approve { background: var(--tng-blue); }
    .btn-approve:hover { background: #00448a; }
    .tng-logo { width: 120px; margin-bottom: 15px; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <img src="payment logo/Touch_'n_Go_eWallet.png" alt="TNG Logo" class="tng-logo">
        <div class="auth-icon-section">
            <div id="loading-spinner" class="spinner-ring"></div>
            <i id="success-tick" class="fas fa-check-circle success-icon" style="color: #0059b3;"></i>
        </div>

        <h1 class="auth-title" id="main-title">Connecting to TNG eWallet</h1>
        <p class="auth-subtitle" id="sub-title">Securely linking to your mobile wallet...</p>

        <div class="receipt-box">
            <div class="receipt-row"><span class="receipt-label">Order ID</span><span>#<?php echo $order['id']; ?></span></div>
            <div class="receipt-row"><span class="receipt-label">Payment Method</span><span>TNG eWallet</span></div>
            <div class="receipt-row"><span class="receipt-label">Total Amount</span><span>RM <?php echo number_format($order['total'], 2); ?></span></div>
        </div>

        <div id="controls" style="display:none;">
            <div class="btn-stack">
                <form id="approveForm" method="post" action="payment_callback.php">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <input type="hidden" name="action" value="paid">
                    <button class="btn-auth btn-approve" type="button" onclick="showSuccessModal()">
                        <i class="fas fa-mobile-alt"></i> Pay with eWallet
                    </button>
                </form>
                
                <form id="cancelForm" method="post" action="payment_callback.php">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <input type="hidden" name="action" value="failed">
                    <button class="btn-auth btn-decline" type="button" onclick="showCancelModal()">Cancel Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="paymentSuccessModal" class="success-modal-overlay">
    <div class="success-modal-content">
        <div class="modal-icon-circle">
            <svg viewBox="0 0 24 24" width="50" height="50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 12.5l2.5 2.5L17 8" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
        <h2>Payment Successful!</h2>
        <p>Your TNG eWallet has been charged. Time to enjoy your bakery treats!</p>
        <button class="btn-done" onclick="submitFinalPayment()">Done</button>
    </div>
</div>

<div id="paymentCancelModal" class="cancel-modal-overlay">
    <div class="cancel-modal-content">
        <div class="modal-icon-x">
            <svg viewBox="0 0 24 24" width="80" height="80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2>Payment Cancelled</h2>
        <p>You have cancelled the payment process. Your items are still in the cart, and you can try again whenever you're ready.</p>
        <button class="btn-cancel-done" onclick="submitCancel()">Got it</button>
    </div>
</div>

<script>
    setTimeout(() => {
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('success-tick').style.display = 'inline-block';
        document.getElementById('main-title').textContent = 'Confirm Payment';
        document.getElementById('sub-title').textContent = 'Please authorize the transaction on your app or click below.';
        document.getElementById('controls').style.display = 'block';
    }, 2000);

    function showSuccessModal() { document.getElementById('paymentSuccessModal').style.display = 'flex'; }
    function submitFinalPayment() {
        try { localStorage.removeItem('bakeryCart'); } catch(e) {}
        document.getElementById('approveForm').submit();
    }

    // --- 取消逻辑 ---
    function showCancelModal() { document.getElementById('paymentCancelModal').style.display = 'flex'; }
    function submitCancel() { document.getElementById('cancelForm').submit(); }
</script>
<?php include 'footer.php'; ?>
<link rel="stylesheet" href="footer.css">