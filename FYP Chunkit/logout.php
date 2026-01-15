<?php
// logout.php
session_start();

// 1. 清除所有服务器端 session 变量
$_SESSION = array();

// 2. 删除 session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. 销毁服务器 session
session_destroy();

// 4. 使用 JavaScript 清除浏览器本地购物车数据，然后跳转
?>
<!DOCTYPE html>
<html>
<head><title>Logging out...</title></head>
<body>
    <script>
        // 🚀 核心修复：彻底清除本地购物车缓存，防止数据带入下一个账号
        localStorage.removeItem('bakeryCart');
        localStorage.removeItem('cartItemCount');
        
        // 跳转回登录页面
        window.location.href = "User_Login.php";
    </script>
</body>
</html>