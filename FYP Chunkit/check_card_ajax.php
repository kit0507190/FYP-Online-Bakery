<?php
// check_card_ajax.php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$cardNum = str_replace(' ', '', $data['card_number'] ?? '');
$expiry  = $data['card_expiry'] ?? '';
$cvv     = $data['card_cvv'] ?? '';

// 1. 查询数据库获取该卡信息
$stmt = $pdo->prepare("SELECT expiry_date, cvv FROM bank_cards WHERE card_number = ? LIMIT 1");
$stmt->execute([$cardNum]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];

if (!$card) {
    // 如果卡号根本不存在，直接返回卡号错误
    $errors[] = 'card';
} else {
    // 如果卡号存在，则【同时】检查有效期和 CVV
    if ($card['expiry_date'] !== $expiry) {
        $errors[] = 'expiry';
    }
    if ($card['cvv'] !== $cvv) {
        $errors[] = 'cvv';
    }
}

if (!empty($errors)) {
    // 返回包含所有错误代码的数组
    echo json_encode(['status' => 'error', 'codes' => $errors]);
} else {
    echo json_encode(['status' => 'success']);
}