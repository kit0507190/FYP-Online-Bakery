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

        // 1. 核心修复：支付成功才清空数据库购物车
        $clearCartStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCartStmt->execute([$userId]);

        // 2. ✨ 新增：自动增加销量逻辑
        // 根据订单 ID，找到订单详情里所有的产品和对应的购买数量，并加到 products 表中
        $updateSoldStmt = $pdo->prepare("
            UPDATE products p 
            JOIN orders_detail od ON p.id = od.product_id 
            SET p.sold_count = p.sold_count + od.quantity 
            WHERE od.order_id = ?
        ");
        $updateSoldStmt->execute([$orderId]);

    } else {
        // 如果用户点击取消或支付失败
        $payment_status = 'failed';
        $order_status = 'cancelled';
    }

    // 更新订单状态
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = ?, status = ? WHERE id = ?");
    $stmt->execute([$payment_status, $order_status, $orderId]);

    $pdo->commit(); // 👈 只有执行到这里，上面的销量和状态更新才会真正写入数据库

    // --- 核心修改：分流跳转逻辑 (保持不变，仅用于页面跳转) ---
    if ($action === 'paid') {
        header("Location: order_result.php?order_id={$orderId}");
    } else {
        header("Location: payment.php?msg=payment_cancelled");
    }
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}