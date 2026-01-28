<?php
// sync_cart.php - Updated to include full image path

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
        // 1. Get current stock for all products in the incoming cart
        $productIds = array_column($input['cart'] ?? [], 'id');
        $stocks = [];
        if (!empty($productIds)) {
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $pdo->prepare("
                SELECT id, stock 
                FROM products 
                WHERE id IN ($placeholders) AND deleted_at IS NULL
            ");
            $stmt->execute($productIds);
            $stocks = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // id => stock
        }

        // 2. Validate and adjust quantities
        $validItems = [];
        foreach ($input['cart'] ?? [] as $item) {
            $prodId = (int)($item['id'] ?? 0);
            $qty    = (int)($item['quantity'] ?? 0);

            if ($prodId <= 0 || $qty <= 0) {
                continue; // skip invalid items
            }

            $available = isset($stocks[$prodId]) ? (int)$stocks[$prodId] : 0;

            // Soft cap: never allow more than available
            if ($qty > $available) {
                $qty = $available;
                // Optional: you could log this or send a warning later
            }

            $validItems[] = ['id' => $prodId, 'quantity' => $qty];
        }

        // 3. Delete old cart
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

        // 4. Insert validated items
        if (!empty($validItems)) {
            $stmt = $pdo->prepare("
                INSERT INTO cart_items (user_id, product_id, quantity) 
                VALUES (?, ?, ?)
            ");
            foreach ($validItems as $item) {
                $stmt->execute([$user_id, $item['id'], $item['quantity']]);
            }
        }

        $pdo->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

    // --- 动作 B: 从数据库读取该用户的购物车 ---
    } elseif ($action === 'fetch') {
        // 关联产品表，把名字、价格、**完整图片路径**一次性全拿回来
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                CASE 
                    WHEN p.image IS NULL OR p.image = '' THEN 'images/placeholder.jpg'
                    ELSE CONCAT('product_images/', p.image)
                END AS image,
                c.quantity 
            FROM cart_items c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
            ORDER BY c.id ASC
        ");
        $stmt->execute([$user_id]);
        $cartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success', 
            'cart'   => $cartData
        ]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>