<?php
include 'db_connect.php';
session_start();
header('Content-Type: application/json');
$ids = [];
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $res = $conn->query("SELECT product_id FROM user_favorites WHERE user_id = $uid");
    while($row = $res->fetch_assoc()) { $ids[] = (int)$row['product_id']; }
}
echo json_encode($ids);
?>