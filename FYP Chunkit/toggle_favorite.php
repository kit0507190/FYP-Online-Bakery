<?php
include 'db_connect.php';
session_start();
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Failed to toggle favorite'];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Please log in first';
    echo json_encode($response);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;

if ($product_id <= 0) {
    $response['message'] = 'Invalid product ID';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Check if already favorited
    $checkStmt = $pdo->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND product_id = ?");
    $checkStmt->execute([$user_id, $product_id]);
    $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($exists) {
        // Remove
        $deleteStmt = $pdo->prepare("DELETE FROM user_favorites WHERE user_id = ? AND product_id = ?");
        $deleteStmt->execute([$user_id, $product_id]);
        $response['action'] = 'removed';
        $response['message'] = 'Removed from favorites';
    } else {
        // Add (fetch product name for the table)
        $prodStmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
        $prodStmt->execute([$product_id]);
        $product = $prodStmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception('Product not found');
        }

        $insertStmt = $pdo->prepare("INSERT INTO user_favorites (user_id, product_id, product_name) VALUES (?, ?, ?)");
        $insertStmt->execute([$user_id, $product_id, $product['name']]);
        $response['action'] = 'added';
        $response['message'] = 'Added to favorites';
    }

    // Fetch updated favorites list (for sync)
    $favStmt = $pdo->prepare("SELECT product_id FROM user_favorites WHERE user_id = ?");
    $favStmt->execute([$user_id]);
    $favorites = $favStmt->fetchAll(PDO::FETCH_COLUMN, 0);

    $response['status'] = 'success';
    $response['favorites'] = $favorites;

} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Toggle favorite failed: " . $e->getMessage());
}

echo json_encode($response);
?>