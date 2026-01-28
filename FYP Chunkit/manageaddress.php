<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// --- Logic 1: Delete address ---
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM user_addresses WHERE id = ? AND user_id = ? AND is_default = 0";
    $pdo->prepare($deleteQuery)->execute([$deleteId, $userId]);
    header("Location: manageaddress.php");
    exit();
}

// --- Logic 2: Set the default address ---
if (isset($_GET['set_default'])) {
    $addressId = $_GET['set_default'];
    $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
    $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?")->execute([$addressId, $userId]);
    header("Location: manageaddress.php");
    exit();
}

// Get address list
$stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format address for display
function formatAddress($raw) {
    if (empty($raw)) return "No address detail";
    if (strpos($raw, '|') !== false) {
        $parts = explode('|', $raw);
        $street = $parts[0] ?? '';
        $area   = $parts[1] ?? '';
        $post   = $parts[2] ?? '';
        return htmlspecialchars($street) . ", " . htmlspecialchars($area) . ", " . htmlspecialchars($post);
    }
    return htmlspecialchars($raw); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Addresses - Bakery House</title>
    <link rel="stylesheet" href="manageaddress.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="profile-page">
        <div class="profile-container">
            <div class="back-navigation">
                <a href="profile.php" class="back-link"><i class="fas fa-chevron-left"></i> Back to Profile</a>
            </div>
            <div class="profile-header">
                <h1>My Addresses</h1>
                <p>Manage your saved delivery locations</p>
            </div>
            <div class="address-list">
                <?php if (empty($addresses)): ?>
                    <div class="info-card empty-state"><p>No addresses found.</p></div>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="info-card address-card <?php echo $addr['is_default'] ? 'is-default' : ''; ?>">
                            <div class="address-body">
                                <div class="address-info">
                                    <div class="address-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="address-text"><?php echo formatAddress($addr['address_text']); ?></div>
                                </div>
                                <div class="address-actions">
                                    <?php if ($addr['is_default']): ?>
                                        <span class="badge-default">Default</span>
                                        <a href="edit.address.php?id=<?php echo $addr['id']; ?>" class="btn-edit-icon"><i class="fas fa-edit"></i></a>
                                    <?php else: ?>
                                        <a href="manageaddress.php?set_default=<?php echo $addr['id']; ?>" class="btn-set">Set Default</a>
                                        <a href="edit.address.php?id=<?php echo $addr['id']; ?>" class="btn-edit-icon"><i class="fas fa-edit"></i></a>
                                        <a href="manageaddress.php?delete_id=<?php echo $addr['id']; ?>" class="btn-delete" onclick="return confirm('Delete this address?')"><i class="fas fa-trash-alt"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="add-action">
                <a href="add.address.php" class="btn-primary"><i class="fas fa-plus"></i> Add New Address</a>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>