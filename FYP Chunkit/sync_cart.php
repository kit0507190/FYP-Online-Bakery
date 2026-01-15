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
    if ($action === 'update') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // 删除旧数据并插入新数据
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

        if (!empty($input['cart'])) {
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            foreach ($input['cart'] as $item) {
                $stmt->execute([$user_id, $item['id'], $item['quantity']]);
            }
        }
        echo json_encode(['status' => 'success']);

    } elseif ($action === 'fetch') {
        // 关联产品表获取详情
        $stmt = $pdo->prepare("SELECT p.id, p.name, p.price, p.image, c.quantity 
                               FROM cart_items c 
                               JOIN products p ON c.product_id = p.id 
                               WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'cart' => $items]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>