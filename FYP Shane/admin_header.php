<header class="header">
    <div class="header-left">
        <img 
            src="logo.png" 
            alt="BakeryHouse Logo" 
            class="header-logo"
        >
        <div class="title-group">
            <h1>BakeryHouse Admin</h1>
        </div>
    </div>

    <div class="user-info">
        <span>Welcome, <strong><?= htmlspecialchars($current_admin['username']) ?></strong> 
            (<span class="role-highlight"><?= ucfirst(str_replace('_', ' ', $current_admin['role'])) ?></span>)
        </span>
        <a href="admin_logout.php" class="logout">Logout</a>
    </div>
</header>