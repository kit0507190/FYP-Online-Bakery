<?php
// admin/logout.php

// Start the same session that was used during login
session_start();

// Destroy the session
session_destroy();

// Clear any remaining session cookie (extra safety)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: admin_login.php");
exit();
?>