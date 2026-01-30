<?php
// sync_cart.php - Improved version (2026)
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
        // ── FETCH USER'S CART ───────────────────────────────────────
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                COALESCE(CONCAT('product_images/', p.image), 'images/placeholder.jpg') AS image,
                c.quantity,
                p.stock AS maxStock          -- ← add this
            FROM cart_items c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = :uid
            AND p.deleted_at IS NULL
            ORDER BY c.id ASC
        ");
        $stmt->execute([':uid' => $user_id]);
        $cartData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => 'success',
            'cart'   => $cartData ?: []
        ];
    } 
    elseif ($action === 'update') {
        // ── UPDATE / REPLACE CART ───────────────────────────────────
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);

        if (!is_array($input) || !isset($input['cart'])) {
            throw new Exception('Invalid or missing cart data');
        }

        $incomingCart = $input['cart'];

        $pdo->beginTransaction();

        // 1. Get current stock levels
        $productIds = array_filter(array_column($incomingCart, 'id'), 'is_numeric');
        $stocks = [];

        if ($productIds) {
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $pdo->prepare("
                SELECT id, stock 
                FROM products 
                WHERE id IN ($placeholders) AND deleted_at IS NULL
            ");
            $stmt->execute($productIds);
            $stocks = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        }

        // 2. Validate & adjust quantities
        $validItems = [];
        foreach ($incomingCart as $item) {
            $prodId = (int)($item['id'] ?? 0);
            $qty    = (int)($item['quantity'] ?? 0);

            if ($prodId <= 0 || $qty <= 0) {
                continue;
            }

            $available = $stocks[$prodId] ?? 0;
            if ($qty > $available) {
                $qty = max(0, $available); // never allow over stock
            }

            $validItems[] = ['product_id' => $prodId, 'quantity' => $qty];
        }

        // Inside the 'update' block, after validating $validItems

        $pdo->commit();

        $adjustedItems = [];
        foreach ($incomingCart as $item) {
            $prodId = (int)($item['id'] ?? 0);
            $requestedQty = (int)($item['quantity'] ?? 0);
            $finalQty = 0;

            if ($prodId > 0 && $requestedQty > 0) {
                $available = $stocks[$prodId] ?? 0;
                $finalQty = min($requestedQty, max(0, $available));
                if ($finalQty != $requestedQty) {
                    $adjustedItems[] = [
                        'id' => $prodId,
                        'name' => $item['name'] ?? 'Product #' . $prodId, // optional: fetch name if needed
                        'requested' => $requestedQty,
                        'available' => $available,
                        'set_to' => $finalQty
                    ];
                }
            }
        }

        $response = [
            'status' => 'success',
            'adjusted' => $adjustedItems,           // ← new
            'message' => $adjustedItems ? 'Some items were adjusted due to stock limits' : null
        ];

        // 3. Clear old cart items for this user
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")
            ->execute([$user_id]);

        // 4. Insert validated items
        if ($validItems) {
            $stmt = $pdo->prepare("
                INSERT INTO cart_items (user_id, product_id, quantity)
                VALUES (:uid, :pid, :qty)
            ");
            foreach ($validItems as $item) {
                $stmt->execute([
                    ':uid' => $user_id,
                    ':pid' => $item['product_id'],
                    ':qty' => $item['quantity']
                ]);
            }
        }

        $pdo->commit();

        $response = ['status' => 'success'];
    } 
    else {
        $response['message'] = 'Invalid action';
    }
}
catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("sync_cart.php error: " . $e->getMessage());

    http_response_code(500);
    $response['message'] = 'Server error: ' . $e->getMessage();
    // In production, you might want to hide detailed message:
    // $response['message'] = 'Internal server error';
}

echo json_encode($response);
exit;