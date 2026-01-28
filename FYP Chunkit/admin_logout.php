<?php

require_once 'admin_config.php';


session_name('admin_session');
session_start();
session_destroy();
header('Location: admin_login.php');
exit();
?>