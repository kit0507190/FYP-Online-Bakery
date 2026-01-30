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

        // Clear cart on success
        $clearCartStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCartStmt->execute([$userId]);

        // Update sold_count on success
        $updateSoldStmt = $pdo->prepare("
            UPDATE products p 
            JOIN orders_detail od ON p.id = od.product_id 
            SET p.sold_count = p.sold_count + od.quantity 
            WHERE od.order_id = ?
        ");
        $updateSoldStmt->execute([$orderId]);

    } else {
        // On cancel/failure: Restore stock, do NOT clear cart
        $payment_status = 'failed';
        $order_status = 'cancelled';

        // Restore stock
        $restoreStmt = $pdo->prepare("
            UPDATE products p
            JOIN orders_detail od ON p.id = od.product_id
            SET p.stock = p.stock + od.quantity
            WHERE od.order_id = ?
        ");
        $restoreStmt->execute([$orderId]);
    }

    // Update order status
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = ?, status = ? WHERE id = ?");
    $stmt->execute([$payment_status, $order_status, $orderId]);

    $pdo->commit();

    // Redirect
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