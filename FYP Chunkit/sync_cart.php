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
        // ── 1. Get shopping cart: Sort by ID in descending order, ensuring the largest ID (most recently inserted) is at the top ──
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
            ORDER BY c.id DESC  /* 🟢 Key change: changed to DESC */
        ");
        $stmt->execute([':uid' => $user_id]);
        $cartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => 'success',
            'cart'   => $cartData ?: []
        ];
    } 
    elseif ($action === 'update') {
        // ── 2. Update shopping cart: Control ID size by reversing insertion order ──
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

        // A. Validate stock logic (keep as is)
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

        // B. Write to database
        $pdo->beginTransaction();

        // Delete old entries first
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

        if ($validItems) {
            /**
             * 🟢 Key change:
             * We want the product at index 0 in the JS array to appear at the top.
             * Since the database assigns incrementing IDs based on insertion order,
             * we reverse the array so that the item at index 0 is inserted last, thus getting the highest ID.
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