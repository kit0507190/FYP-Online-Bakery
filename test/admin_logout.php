<?php
// 第一行修改为：
require_once 'admin_config.php';

// 或者如果你只需要 session 功能，可以更简单：
session_name('admin_session');
session_start();
session_destroy();
header('Location: admin_login.php');
exit();
?>