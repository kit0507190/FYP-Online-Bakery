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

        // 核心修复：支付成功才清空数据库购物车
        $clearCartStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCartStmt->execute([$userId]);
    } else {
        // 如果用户点击取消或支付失败
        $payment_status = 'failed';
        $order_status = 'cancelled';
    }

    // 更新订单状态
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = ?, status = ? WHERE id = ?");
    $stmt->execute([$payment_status, $order_status, $orderId]);

    $pdo->commit();

    // --- 核心修改：分流跳转逻辑 ---
    if ($action === 'paid') {
        // 只有支付成功，才去结果展示页
        header("Location: order_result.php?order_id={$orderId}");
    } else {
        // 如果支付取消或失败，退回到支付页面，并带上错误提示参数
        header("Location: payment.php?msg=payment_cancelled");
    }
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}
?>