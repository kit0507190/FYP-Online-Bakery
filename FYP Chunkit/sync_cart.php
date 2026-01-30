<?php
// sync_cart.php - Fixed version (2026)
// Always respond with JSON, even on errors

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// ── Enable error display during development ──
// Remove or comment out these lines in production!
ini_set('display_errors', 0);           // ← change to 1 only when debugging
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Log errors to file instead (safer for production)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log'); // adjust path if needed

require_once 'config.php';

// Make sure PDO throws exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id'] ?? null;
$action  = $_GET['action'] ?? '';

$response = ['status' => 'error', 'message' => 'Unknown error'];

if (!$user_id) {
    http_response_code(401);
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

try {
    if ($action === 'fetch') {
        // ── 1. 获取购物车：按 ID 倒序排列，保证最大的 ID（最新插入的）在最上方 ──
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                COALESCE(CONCAT('product_images/', p.image), 'images/placeholder.jpg') AS image,
                c.quantity,
                p.stock AS maxStock
            FROM cart_items c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = :uid
            AND p.deleted_at IS NULL
            ORDER BY c.id DESC  /* 🟢 关键修改：改为 DESC */
        ");
        $stmt->execute([':uid' => $user_id]);
        $cartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => 'success',
            'cart'   => $cartData ?: []
        ];
    } 
    elseif ($action === 'update') {
        // ── 2. 更新购物车：通过反转插入顺序来控制 ID 大小 ──
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);

        if (!is_array($input) || !isset($input['cart']) || !is_array($input['cart'])) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Invalid cart format'
            ]);
            exit;
        }

        $incomingCart = $input['cart'];

        // A. 验证库存逻辑 (保持不变)
        $productIds = array_filter(array_map('intval', array_column($incomingCart, 'id')));
        $stocks = [];
        if ($productIds) {
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id IN ($placeholders) AND deleted_at IS NULL");
            $stmt->execute($productIds);
            $stocks = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        }

        $validItems = [];
        $adjustedItems = [];
        foreach ($incomingCart as $item) {
            $prodId = (int)($item['id'] ?? 0);
            $reqQty = (int)($item['quantity'] ?? 0);
            if ($prodId <= 0 || $reqQty <= 0) continue;

            $available = $stocks[$prodId] ?? 0;
            $finalQty = min($reqQty, max(0, $available));

            $validItems[] = ['product_id' => $prodId, 'quantity' => $finalQty];

            if ($finalQty < $reqQty) {
                $adjustedItems[] = [
                    'id' => $prodId, 
                    'name' => $item['name'] ?? 'Product', 
                    'requested' => $reqQty, 
                    'available' => $available, 
                    'set_to' => $finalQty
                ];
            }
        }

        // B. 写入数据库
        $pdo->beginTransaction();

        // 先删除旧的
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

        if ($validItems) {
            /**
             * 🟢 关键修改点：
             * 我们希望 JS 数组中 index 0 的产品排在最上面。
             * 因为数据库是按插入先后分配递增 ID 的，
             * 所以我们【反转数组】，让第 0 项最后插入，从而获得最大的 ID。
             */
            $itemsToInsert = array_reverse($validItems);

            $stmt = $pdo->prepare("
                INSERT INTO cart_items (user_id, product_id, quantity)
                VALUES (:uid, :pid, :qty)
            ");
            foreach ($itemsToInsert as $item) {
                $stmt->execute([
                    ':uid' => $user_id,
                    ':pid' => $item['product_id'],
                    ':qty' => $item['quantity']
                ]);
            }
        }

        $pdo->commit();

        // Success response
        $response = [
            'status'   => 'success',
            'adjusted' => $adjustedItems,
            'message'  => !empty($adjustedItems) ? 'Some quantities were reduced due to stock limits' : null
        ];

    } 
    else {
        $response['message'] = 'Invalid action';
    }
} 
catch (Exception $e) {
    // Rollback if transaction is active
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("sync_cart.php error: " . $e->getMessage() . "\n" . $e->getTraceAsString());

    http_response_code(500);
    $response['message'] = 'Server error: ' . $e->getMessage();
    // In production, you might want to hide detailed message:
    // $response['message'] = 'Internal server error';
}

echo json_encode($response);
exit;
?>