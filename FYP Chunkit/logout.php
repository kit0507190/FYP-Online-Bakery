<?php

session_start();

// 1. Clear all server-side session variables
$_SESSION = array();

// 2. Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


session_destroy();
?>
<!DOCTYPE html>
<html>
<head><title>Logging out...</title></head>
<body>
    <script>
        localStorage.removeItem('bakeryCart');
        localStorage.removeItem('cartItemCount');
        window.location.href = "User_Login.php";
    </script>
</body>
</html>