<?php
// toggle_favorite.php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$product_id = isset($data['product_id']) ? (int)$data['product_id'] : 0;
$product_name = isset($data['product_name']) ? $data['product_name'] : '';

if ($product_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product']);
    exit;
}

// 检查是否已收藏
$check = $conn->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // 取消收藏
    $stmt = $conn->prepare("DELETE FROM user_favorites WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'action' => 'removed']);
} else {
    // 添加收藏，同时存入 product_name
    $stmt = $conn->prepare("INSERT INTO user_favorites (user_id, product_id, product_name) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $product_id, $product_name);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'action' => 'added']);
}
$conn->close();
?>