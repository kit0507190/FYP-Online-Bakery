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
    :root { 
        --fpx-navy: #002e5d; /* FPX 官方深蓝色 */
    }
    /* 🚀 模仿 TNG/Debit 设计：顶部 10px 边框 */
    .auth-card { 
        border-top: 10px solid var(--fpx-navy); 
    }
    .spinner-ring { border-top: 4px solid var(--fpx-navy); }
    .btn-approve { background: var(--fpx-navy); }
    .btn-approve:hover { background: #001a35; }

    /* FPX Logo 专属尺寸 */
    .fpx-logo {
        width: 130px;
        height: auto;
        margin: 0 auto 25px;
        display: block;
    }

    .bank-select {
        width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;
        margin-bottom: 20px; font-family: inherit; font-size: 14px;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <img src="payment logo/Logo-FPX.png" alt="FPX Logo" class="fpx-logo">

        <div class="auth-icon-section">
            <div id="loading-spinner" class="spinner-ring"></div>
            <i id="success-tick" class="fas fa-university success-icon" style="color: var(--fpx-navy);"></i>
        </div>

        <h1 class="auth-title" id="main-title">Redirecting to Bank</h1>
        <p class="auth-subtitle" id="sub-title">Fetching list of supported banks...</p>

        <div id="controls" style="display:none;">
            <select class="bank-select">
                <option>Maybank2u</option>
                <option>CIMB Clicks</option>
                <option>Public Bank</option>
                <option>RHB Now</option>
                <option>Hong Leong Connect</option>
            </select>

            <div class="receipt-box">
                <div class="receipt-row"><span class="receipt-label">Order ID</span><span>#<?php echo $order['id']; ?></span></div>
                <div class="receipt-row"><span class="receipt-label">Amount</span><span>RM <?php echo number_format($order['total'], 2); ?></span></div>
            </div>

            <div class="btn-stack">
                <form id="approveForm" method="post" action="payment_callback.php">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <input type="hidden" name="action" value="paid">
                    <button class="btn-auth btn-approve" type="button" onclick="showSuccessModal()">
                        Proceed to Bank
                    </button>
                </form>
                
                <form id="cancelForm" method="post" action="payment_callback.php">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <input type="hidden" name="action" value="failed">
                    <button class="btn-auth btn-decline" type="button" onclick="showCancelModal()">Cancel</button>
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
        <p>Your bank transaction has been confirmed. Thank you for your purchase!</p>
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
    // 模拟银行连接逻辑
    setTimeout(() => {
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('success-tick').style.display = 'inline-block';
        document.getElementById('main-title').textContent = 'Select Your Bank';
        document.getElementById('sub-title').textContent = 'Choose your preferred bank to complete the payment.';
        document.getElementById('controls').style.display = 'block';
    }, 1800);

    function showSuccessModal() { document.getElementById('paymentSuccessModal').style.display = 'flex'; }
    function submitFinalPayment() {
        try { localStorage.removeItem('bakeryCart'); } catch(e) {}
        document.getElementById('approveForm').submit();
    }

    // 取消逻辑
    function showCancelModal() { document.getElementById('paymentCancelModal').style.display = 'flex'; }
    function submitCancel() { document.getElementById('cancelForm').submit(); }
</script>
<?php include 'footer.php'; ?>
<link rel="stylesheet" href="footer.css">