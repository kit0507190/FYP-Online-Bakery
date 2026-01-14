<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: payment.php');
    exit;
}

$orderId = $_POST['order_id'] ?? null;
$action = $_POST['action'] ?? null;
$method = $_POST['method'] ?? null;

if (!$orderId || !$action) {
    echo "Invalid callback.";
    exit;
}

// Determine new statuses
if ($action === 'paid') {
    $payment_status = 'paid';
    // Move order into preparation
    $order_status = 'preparing';
} else {
    $payment_status = 'failed';
    $order_status = 'cancelled';
}

$stmt = $pdo->prepare("UPDATE orders SET payment_status = ?, status = ? WHERE id = ?");
$stmt->execute([$payment_status, $order_status, $orderId]);

// Redirect to result page
header("Location: order_result.php?order_id={$orderId}&result={$payment_status}");
exit;
?>