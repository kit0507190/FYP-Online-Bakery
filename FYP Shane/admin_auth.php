<?php
// admin/auth.php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Optional: Prevent session fixation / timeout (recommended)
if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > 1800)) {
    // Session timeout after 30 minutes inactivity
    session_unset();
    session_destroy();
    header("Location: admin_login.php?timeout=1");
    exit();
}
$_SESSION['admin_last_activity'] = time(); // Update last activity

require_once 'admin_config.php';

try {
    $pdo = getAdminPDOConnection();
    
    $stmt = $pdo->prepare("SELECT id, username, email, role, status FROM admins WHERE id = ? AND status = 'active'");
    $stmt->execute([$_SESSION['admin_id']]);
    $current_admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$current_admin) {
        // Invalid or inactive admin
        session_destroy();
        header("Location: admin_login.php");
        exit();
    }

    // Optional: Sync session with DB in case role changed
    $_SESSION['admin_role'] = $current_admin['role'];
    $_SESSION['admin_username'] = $current_admin['username'];

} catch (Exception $e) {
    error_log("Auth error: " . $e->getMessage());
    die("System error. Please try again later.");
}
?>