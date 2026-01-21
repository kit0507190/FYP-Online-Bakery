<?php
include 'db_connect.php';
session_start();
header('Content-Type: application/json');

$ids = [];
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT product_id FROM user_favorites WHERE user_id = ?");
        $stmt->execute([$uid]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $ids[] = (int)$row['product_id'];
        }
    } catch (PDOException $e) {
        // Log error (don't echo in production)
        error_log("Favorites query failed: " . $e->getMessage());
    }
}
echo json_encode($ids);
?>