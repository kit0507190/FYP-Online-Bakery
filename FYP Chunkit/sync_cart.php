<?php
// sync_cart.php - 修复后的完整版
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php'; 

header('Content-Type: application/json');

// 1. 验证登录状态
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

try {
    // --- 动作 A: 更新/保存购物车到数据库 ---
    if ($action === 'update') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $pdo->beginTransaction();
        try {
            // 先删除该用户旧的购物车记录
            $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

            // 循环插入新的记录
            if (!empty($input['cart']) && is_array($input['cart'])) {
                $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
                foreach ($input['cart'] as $item) {
                    if (isset($item['id']) && isset($item['quantity'])) {
                        $stmt->execute([$user_id, $item['id'], $item['quantity']]);
                    }
                }
            }
            $pdo->commit();
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }

    // --- 动作 B: 从数据库读取该用户的购物车 (这就是你之前丢失的代码) ---
    } elseif ($action === 'fetch') {
        // 关联产品表，把名字、价格、图片一次性全拿回来
        $stmt = $pdo->prepare("
            SELECT p.id, p.name, p.price, p.image, c.quantity 
            FROM cart_items c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success', 
            'cart' => $cartData
        ]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>