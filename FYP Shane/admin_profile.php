<?php
// admin/admin_profile.php

require_once 'admin_auth.php';  // Secure login + loads $current_admin with role
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - BakeryHouse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .welcome-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .welcome-box h1 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .info-box h3 {
            color: #667eea;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        
        .info-value {
            color: #212529;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">🛡️ BakeryHouse Admin</div>
    <div class="user-info">
        <span>Welcome, <strong><?= htmlspecialchars($current_admin['username']) ?></strong></span>
        <span class="role-badge"><?= ucfirst(str_replace('_', ' ', htmlspecialchars($current_admin['role']))) ?></span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<div class="container">
    <div class="success-message">
        ✅ Login successful! You are now in the admin panel.
    </div>
    
    <div class="welcome-box">
        <h1>Hello, <?= htmlspecialchars($current_admin['username']) ?>!</h1>
        <p>You are logged in as <strong><?= ucfirst(str_replace('_', ' ', htmlspecialchars($current_admin['role']))) ?></strong>.</p>
        <p class="login-time">Login time: <?= date('F j, Y, g:i a') ?></p>
    </div>
    
    <div class="info-box">
        <h3>Account Information</h3>
        <div class="info-item">
            <span class="info-label">Admin ID:</span>
            <span class="info-value"><?= htmlspecialchars($current_admin['id']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Username:</span>
            <span class="info-value"><?= htmlspecialchars($current_admin['username']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Email:</span>
            <span class="info-value"><?= htmlspecialchars($current_admin['email'] ?? 'Not set') ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Role:</span>
            <span class="info-value"><?= ucfirst(str_replace('_', ' ', htmlspecialchars($current_admin['role']))) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Account Status:</span>
            <span class="info-value" style="color: #28a745;">Active</span>
        </div>
    </div>
    
    <div class="info-box">
        <h3>Quick Actions</h3>
        <div class="action-buttons">
            
            <?php if ($current_admin['role'] === 'super_admin'): ?>
                <a href="user_accounts.php" class="btn">Manage Customers</a>
                <a href="manage_admins.php" class="btn">Manage Staff</a>
                <a href="reports.php" class="btn">View Reports</a>
            <?php endif; ?>
            
            <a href="admin_dashboard.php" class="btn">Go to Main Site</a>
        </div>
    </div>
    
    <div class="info-box">
        <h3>Session Information</h3>
        <div class="info-item">
            <span class="info-label">IP Address:</span>
            <span class="info-value"><?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Browser:</span>
            <span class="info-value"><?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']) ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Session Started:</span>
            <span class="info-value"><?= date('g:i a', $_SESSION['admin_last_activity'] ?? time()) ?></span>
        </div>
    </div>
</div>

<div class="footer">
    <p>© <?= date('Y') ?> BakeryHouse Admin Panel | Secure Access System</p>
    <p style="margin-top: 5px;">Current time: <?= date('g:i:s a') ?></p>
</div>

</body>
</html>