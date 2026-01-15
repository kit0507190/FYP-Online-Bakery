<?php
// payment_callback.php
session_start(); 
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: payment.php');
    exit;
}

$orderId = $_POST['order_id'] ?? null;
$action = $_POST['action'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$orderId || !$action || !$userId) {
    echo "Invalid session or callback data.";
    exit;
}

try {
    $pdo->beginTransaction();

    if ($action === 'paid') {
        $payment_status = 'paid';
        $order_status = 'preparing';

        // 🚀 核心修复：这里必须改成 cart_items，因为 sync_cart.php 用的是这个名字
        $clearCartStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCartStmt->execute([$userId]);
    } else {
        $payment_status = 'failed';
        $order_status = 'cancelled';
    }

    // 更新订单状态
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = ?, status = ? WHERE id = ?");
    $stmt->execute([$payment_status, $order_status, $orderId]);

    $pdo->commit();

    // 跳转到结果页
    header("Location: order_result.php?order_id={$orderId}&result={$payment_status}");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}
?>