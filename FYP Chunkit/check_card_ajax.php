<?php
// check_card_ajax.php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$cardNum = str_replace(' ', '', $data['card_number'] ?? '');
$expiry  = $data['card_expiry'] ?? '';
$cvv     = $data['card_cvv'] ?? '';

// 1. Query the database to retrieve the card information.
$stmt = $pdo->prepare("SELECT expiry_date, cvv FROM bank_cards WHERE card_number = ? LIMIT 1");
$stmt->execute([$cardNum]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];

if (!$card) {
    // If the card number does not exist at all, return card number error directly
    $errors[] = 'card';
} else {
    // If the card number exists, then check both expiry and CVV
    if ($card['expiry_date'] !== $expiry) {
        $errors[] = 'expiry';
    }
    if ($card['cvv'] !== $cvv) {
        $errors[] = 'cvv';
    }
}

if (!empty($errors)) {
    // Return an array containing all error codes
    echo json_encode(['status' => 'error', 'codes' => $errors]);
} else {
    echo json_encode(['status' => 'success']);
}