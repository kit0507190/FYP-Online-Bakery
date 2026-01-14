<?php
// admin/admin_config.php
// 专为管理员系统设计的配置文件

// ======================
// 安全防护 
// ======================

// 禁止直接访问配置文件
if (basename($_SERVER['PHP_SELF']) === 'admin_config.php') {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access to this file is forbidden.');
}

// ======================
// 数据库配置
// ======================

define('ADMIN_DB_HOST', 'localhost');
define('ADMIN_DB_NAME', 'bakeryhouse'); // 和用户共用数据库，但表不同
define('ADMIN_DB_USER', 'root');
define('ADMIN_DB_PASS', '');

// 管理员的表名
define('ADMIN_TABLE', 'admins');

// ======================
// 安全配置（比普通用户更严格）
// ======================

// 登录尝试限制
define('ADMIN_MAX_LOGIN_ATTEMPTS', 3);  // 比用户的5次更严格
define('ADMIN_LOCKOUT_TIME', 900);      // 15分钟锁定（用户可能是5分钟）

// Session 设置（更短的时间，增强安全）
define('ADMIN_SESSION_TIMEOUT', 1800);  // 30分钟无活动自动退出
define('ADMIN_SESSION_NAME', 'admin_session'); // 独立的session名称

// 密码策略
define('ADMIN_MIN_PASSWORD_LENGTH', 10); // 管理员密码要求更长

// ======================
// 路径配置
// ======================

// Admin 根目录
define('ADMIN_ROOT', dirname(__DIR__) . '/admin/');

// ======================
// 数据库连接函数（PDO版本）
// ======================

/**
 * 获取管理员数据库连接
 * 使用PDO，与你的config.php保持一致
 */
function getAdminPDOConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . ADMIN_DB_HOST . ";dbname=" . ADMIN_DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($dsn, ADMIN_DB_USER, ADMIN_DB_PASS, $options);
            
        } catch (PDOException $e) {
            // 管理员系统的错误处理应该更详细，便于调试
            error_log("Admin DB Connection Error: " . $e->getMessage());
            
            // 显示给管理员的信息（比用户看到的更详细）
            if (defined('ADMIN_DEBUG') && ADMIN_DEBUG) {
                die("管理员数据库连接失败: " . $e->getMessage());
            } else {
                die("管理员系统暂时不可用，请稍后再试。");
            }
        }
    }
    
    return $pdo;
}

// ======================
// Session 管理函数
// ======================

/**
 * 启动管理员专用的 Session
 */
function startAdminSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // 使用独立的 session 名称
        session_name(ADMIN_SESSION_NAME);
        
        // 更安全的 cookie 设置
        session_set_cookie_params([
            'lifetime' => 0, // 浏览器关闭时过期
            'path' => '/admin/', // 只对admin目录有效
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => isset($_SERVER['HTTPS']), // 如果是HTTPS则启用
            'httponly' => true, // 防止JavaScript访问
            'samesite' => 'Strict' // 防止CSRF
        ]);
        
        session_start();
    }
}

/**
 * 验证管理员是否已登录
 */
function isAdminLoggedIn() {
    startAdminSession();
    
    // 检查基本的 session 变量
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
        return false;
    }
    
    // 检查 session 是否过期
    if (isset($_SESSION['admin_last_activity']) && 
        (time() - $_SESSION['admin_last_activity'] > ADMIN_SESSION_TIMEOUT)) {
        // 清除 session 数据
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        return false;
    }
    
    // 更新最后活动时间
    $_SESSION['admin_last_activity'] = time();
    return true;
}

/**
 * 重定向到登录页面
 */
function redirectToAdminLogin($error = '') {
    $url = 'login.php';
    if (!empty($error)) {
        $url .= '?error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit();
}

/**
 * 重定向到管理面板
 */
function redirectToAdminDashboard() {
    header('Location: dashboard.php');
    exit();
}

/**
 * 安全的密码验证
 */
function verifyAdminPassword($input_password, $stored_hash) {
    return password_verify($input_password, $stored_hash);
}

/**
 * 为管理员生成密码哈希
 */
function hashAdminPassword($password) {
    // 管理员使用更强的加密选项
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * 清理输入数据
 */
function cleanAdminInput($data) {
    if (is_array($data)) {
        return array_map('cleanAdminInput', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * 记录管理员活动（简单版本）
 */
function logAdminActivity($admin_id, $action, $details = '') {
    try {
        $pdo = getAdminPDOConnection();
        $sql = "INSERT INTO admin_activity_logs (admin_id, action_type, action_details, ip_address) 
                VALUES (:admin_id, :action, :details, :ip)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':admin_id' => $admin_id,
            ':action' => $action,
            ':details' => $details,
            ':ip' => $_SERVER['REMOTE_ADDR']
        ]);
    } catch (Exception $e) {
        // 静默失败，不中断主要功能
        error_log("Failed to log admin activity: " . $e->getMessage());
    }
}

// ======================
// 调试模式配置
// ======================

// 开发环境设置为 true，生产环境设置为 false
define('ADMIN_DEBUG', true); // 根据环境修改

if (ADMIN_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ======================
// 自动启动 Session
// ======================

// 在配置文件加载时自动启动安全 session
startAdminSession();

?>