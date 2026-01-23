<?php
// sync_cart.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php'; 

header('Content-Type: application/json');

// 验证登录状态
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

try {
    // 替换 sync_cart.php 中的 action === 'update' 部分
if ($action === 'update') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        $pdo->beginTransaction(); // 开启事务

        // 删除旧数据
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

        if (!empty($input['cart']) && is_array($input['cart'])) {
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            foreach ($input['cart'] as $item) {
                // 确保数据存在
                if (isset($item['id']) && isset($item['quantity'])) {
                    $stmt->execute([$user_id, $item['id'], $item['quantity']]);
                }
            }
        }
        
        $pdo->commit(); // 提交事务
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>