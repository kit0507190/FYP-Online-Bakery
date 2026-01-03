<?php
// editprofile.php - 编辑模式 (PDO版本)
// 功能：用户编辑个人资料的页面

// 1. 启动会话 - 用于跟踪用户登录状态
session_start();

// 2. 检查用户是否登录 - 如果没有登录，跳转到登录页面
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();  // 停止执行后续代码
}

// 3. 连接数据库
require_once 'config.php';  // 包含数据库配置文件

// 4. 检查数据库连接是否成功
if (!isset($pdo)) {
    die("Database connection failed.");  // 如果连接失败，显示错误信息
}

// 5. 初始化变量
$userId = $_SESSION['user_id'];  // 从会话中获取用户ID
$errors = [];  // 存储通用错误消息的数组
$fieldErrors = []; // 存储字段特定错误的关联数组
$name = $email = $phone = $address_area = $address_postcode = $address_line = $other_area = '';  // 初始化表单变量

// 6. 从数据库获取当前用户信息
try {
    // 准备SQL查询语句
    $query = "SELECT name, email, phone, address FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);  // 准备执行语句
    $stmt->execute([$userId]);  // 执行查询，传入用户ID
    $user = $stmt->fetch(PDO::FETCH_ASSOC);  // 获取一行结果
    
    if ($user) {
        // 7. 安全地显示用户数据（防止XSS攻击）
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? '');  // 如果为空，使用空字符串
        
        // 8. 解析地址信息（如果是新格式）
        if (!empty($user['address']) && strpos($user['address'], '|') !== false) {
            // 新格式：area|postcode|address_line|other_area
            $address_parts = explode('|', $user['address']);
            if (count($address_parts) >= 3) {
                $address_area = htmlspecialchars($address_parts[0]);
                $address_postcode = htmlspecialchars($address_parts[1]);
                $address_line = htmlspecialchars($address_parts[2]);
                $other_area = isset($address_parts[3]) ? htmlspecialchars($address_parts[3]) : '';
            }
        } else {
            // 旧格式：直接显示在address_line中
            $address_line = htmlspecialchars($user['address'] ?? '');
        }
        
        // 9. 设置导航栏需要的变量
        $isLoggedIn = true;
        $userName = $user['name'];
    } else {
        // 10. 用户不存在，清除会话并跳转到登录页
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    // 11. 捕获数据库错误
    die("Error fetching user data: " . $e->getMessage());
}

// 12. 处理表单提交（当用户点击保存按钮时）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 13. 获取表单数据并清理空格
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');
    
    // 14. 验证必填字段
    if (empty($name)) {
        $fieldErrors['name'] = "Full name is required.";
        $errors[] = "Full name is required.";
    } elseif (strlen($name) < 2) {
        $fieldErrors['name'] = "Name must be at least 2 characters.";
        $errors[] = "Name must be at least 2 characters.";
    }
    
    if (empty($email)) {
        $fieldErrors['email'] = "Email address is required.";
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = "Please enter a valid email address.";
        $errors[] = "Please enter a valid email address.";
    }
    
    // 15. 验证邮箱是否已被其他用户使用
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $checkEmailQuery = "SELECT id FROM user_db WHERE email = ? AND id != ?";
            $checkStmt = $pdo->prepare($checkEmailQuery);
            $checkStmt->execute([$email, $userId]);
            if ($checkStmt->fetch()) {
                $fieldErrors['email'] = "This email is already registered by another user.";
                $errors[] = "This email is already registered by another user.";
            }
        } catch (PDOException $e) {
            $fieldErrors['email'] = "Error checking email availability.";
            $errors[] = "Error checking email availability.";
        }
    }
    
    // 16. 验证电话号码格式（后端验证）
    if (!empty($phone)) {
        if (!preg_match('/^[0-9+\-\s()]{6,20}$/', $phone)) {
            $fieldErrors['phone'] = "Please enter a valid phone number (at least 6 digits).";
            $errors[] = "Please enter a valid phone number (at least 6 digits).";
        }
    }
    
    // 17. 验证地址信息
    if (empty($address_area)) {
        $fieldErrors['address_area'] = "Please select an area.";
        $errors[] = "Please select an area.";
    }
    
    if (empty($address_postcode)) {
        $fieldErrors['address_postcode'] = "Postcode is required.";
        $errors[] = "Postcode is required.";
    } elseif (!preg_match('/^(75[0-9]{3}|77[0-9]{3}|78[0-9]{3})$/', $address_postcode)) {
        $fieldErrors['address_postcode'] = "Please enter a valid Melaka postcode (starts with 75, 77, or 78).";
        $errors[] = "Please enter a valid Melaka postcode (starts with 75, 77, or 78).";
    }
    
    if (empty($address_line)) {
        $fieldErrors['address_line'] = "Address line is required.";
        $errors[] = "Address line is required.";
    } elseif (strlen($address_line) < 5) {
        $fieldErrors['address_line'] = "Please provide a more detailed address (at least 5 characters).";
        $errors[] = "Please provide a more detailed address (at least 5 characters).";
    }
    
    if ($address_area === 'other' && empty($other_area)) {
        $fieldErrors['other_area'] = "Please specify the other area.";
        $errors[] = "Please specify the other area.";
    }
    
    // 18. 组合地址信息
    $address = $address_area . '|' . $address_postcode . '|' . $address_line;
    if (!empty($other_area)) {
        $address .= '|' . $other_area;
    }
    
    // 19. 如果没有错误，更新用户信息
    if (empty($errors)) {
        try {
            // 只更新基本信息
            $updateQuery = "UPDATE user_db SET name = ?, email = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?";
            $params = [$name, $email, $phone, $address, $userId];
            
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute($params);
            
            // 更新会话中的用户信息
            $_SESSION['username'] = $name;
            $_SESSION['email'] = $email;
            
            // 重定向到查看页面
            header("Location: profile.php?success=1");
            exit();
            
        } catch (PDOException $e) {
            $errors[] = "Error updating profile: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Bakery House</title>
    <!-- 引入样式文件 -->
    <link rel="stylesheet" href="editprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- 网站头部导航栏 -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="mainpage.php" class="logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </a>
                <ul class="nav-links">
                    <li><a href="mainpage.php">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="about_us.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li class="cart-icon">
                        <span>🛒 Cart</span>
                        <span class="cart-count">0</span>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                        <!-- 已登录用户显示用户菜单 -->
                        <li class="user-menu">
                            <div class="user-icon" onclick="toggleDropdown()">
                                <?php echo strtoupper(substr($userName, 0, 1)); ?>
                            </div>
                            <div class="dropdown-menu" id="dropdownMenu">
                                <a href="profile.php">Profile</a>
                                <a href="logout.php">Log Out</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <!-- 未登录用户显示注册按钮 -->
                        <li>
                            <a href="User_Login.php" class="signup-btn">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- 消息显示区域（通用错误消息） -->
    <div class="message-container">
        <?php 
        // 只显示非字段特定的错误
        $nonFieldErrors = [];
        foreach ($errors as $error) {
            $isFieldError = false;
            foreach ($fieldErrors as $fieldError) {
                if ($error === $fieldError) {
                    $isFieldError = true;
                    break;
                }
            }
            if (!$isFieldError) {
                $nonFieldErrors[] = $error;
            }
        }
        ?>
        
        <?php if (!empty($nonFieldErrors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($nonFieldErrors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <!-- 主内容区域 -->
    <main class="profile-page">
        <div class="profile-container">
            <!-- 页面标题 -->
            <div class="profile-header">
                <h1>Edit Profile</h1>
                <p>Update your personal information and account settings</p>
            </div>

            <!-- 编辑表单 -->
            <form action="editprofile.php" method="POST" class="edit-form" id="profileForm">
                <!-- 个人信息部分 -->
                <div class="info-card">
                    <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    
                    <!-- 姓名输入 -->
                    <div class="form-group required-field">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input <?php echo isset($fieldErrors['name']) ? 'error' : ''; ?>" 
                               value="<?php echo $name; ?>" 
                               required
                               placeholder="Enter your full name">
                        <div class="form-hint">Your display name</div>
                        <?php if (isset($fieldErrors['name'])): ?>
                            <div class="field-error"><?php echo htmlspecialchars($fieldErrors['name']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 邮箱输入 -->
                    <div class="form-group required-field">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input <?php echo isset($fieldErrors['email']) ? 'error' : ''; ?>" 
                               value="<?php echo $email; ?>" 
                               required
                               placeholder="Enter your email address">
                        <div class="form-hint">We'll never share your email</div>
                        <?php if (isset($fieldErrors['email'])): ?>
                            <div class="field-error"><?php echo htmlspecialchars($fieldErrors['email']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 电话号码输入 -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-input <?php echo isset($fieldErrors['phone']) ? 'error' : ''; ?>" 
                               value="<?php echo $phone; ?>" 
                               placeholder="e.g., 011-2345678">
                        <div class="form-hint">Minimum 6 digits required</div>
                        <?php if (isset($fieldErrors['phone'])): ?>
                            <div class="field-error"><?php echo htmlspecialchars($fieldErrors['phone']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 地址部分 -->
                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Default Address in Melaka</h2>
                    <p class="form-note">Please provide your delivery address in Melaka</p>
                    
                    <div class="form-row">
                        <!-- 区域选择 -->
                        <div class="form-group-half required-field">
                            <label for="address_area" class="form-label">Area</label>
                            <select id="address_area" name="address_area" class="form-input <?php echo isset($fieldErrors['address_area']) ? 'error' : ''; ?>" required onchange="toggleOtherArea()">
                                <option value="">-- Select Area --</option>
                                <optgroup label="Popular Areas">
                                    <option value="Bandar Melaka" <?php echo $address_area === 'Bandar Melaka' ? 'selected' : ''; ?>>Bandar Melaka</option>
                                    <option value="Ayer Keroh" <?php echo $address_area === 'Ayer Keroh' ? 'selected' : ''; ?>>Ayer Keroh</option>
                                    <option value="Bukit Beruang" <?php echo $address_area === 'Bukit Beruang' ? 'selected' : ''; ?>>Bukit Beruang</option>
                                    <option value="Cheng" <?php echo $address_area === 'Cheng' ? 'selected' : ''; ?>>Cheng</option>
                                    <option value="Bachang" <?php echo $address_area === 'Bachang' ? 'selected' : ''; ?>>Bachang</option>
                                </optgroup>
                                <optgroup label="Other Areas">
                                    <option value="Klebang" <?php echo $address_area === 'Klebang' ? 'selected' : ''; ?>>Klebang</option>
                                    <option value="Tanjung Kling" <?php echo $address_area === 'Tanjung Kling' ? 'selected' : ''; ?>>Tanjung Kling</option>
                                    <option value="Alor Gajah Town" <?php echo $address_area === 'Alor Gajah Town' ? 'selected' : ''; ?>>Alor Gajah Town</option>
                                    <option value="Jasin Town" <?php echo $address_area === 'Jasin Town' ? 'selected' : ''; ?>>Jasin Town</option>
                                    <option value="Merlimau" <?php echo $address_area === 'Merlimau' ? 'selected' : ''; ?>>Merlimau</option>
                                    <option value="other" <?php echo $address_area === 'other' ? 'selected' : ''; ?>>Other - Specify below</option>
                                </optgroup>
                            </select>
                            <div class="form-hint">Select your area in Melaka</div>
                            <?php if (isset($fieldErrors['address_area'])): ?>
                                <div class="field-error"><?php echo htmlspecialchars($fieldErrors['address_area']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- 邮编 -->
                        <div class="form-group-half required-field">
                            <label for="address_postcode" class="form-label">Postcode</label>
                            <input type="text" id="address_postcode" name="address_postcode" class="form-input <?php echo isset($fieldErrors['address_postcode']) ? 'error' : ''; ?>" 
                                   value="<?php echo $address_postcode; ?>" 
                                   required
                                   placeholder="e.g., 75000">
                            <div class="form-hint">Melaka postcodes start with 75, 77, or 78</div>
                            <?php if (isset($fieldErrors['address_postcode'])): ?>
                                <div class="field-error"><?php echo htmlspecialchars($fieldErrors['address_postcode']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- 详细地址 -->
                    <div class="form-group required-field">
                        <label for="address_line" class="form-label">Address Details</label>
                        <textarea id="address_line" name="address_line" class="form-textarea <?php echo isset($fieldErrors['address_line']) ? 'error' : ''; ?>" 
                                  required rows="3" 
                                  placeholder="House number, street, building name, etc."><?php echo $address_line; ?></textarea>
                        <div class="form-hint">Enter house number, street, building name, etc.</div>
                        <?php if (isset($fieldErrors['address_line'])): ?>
                            <div class="field-error"><?php echo htmlspecialchars($fieldErrors['address_line']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 其他区域输入（默认隐藏） -->
                    <div class="form-group" id="other_area_group" style="display: <?php echo $address_area === 'other' ? 'block' : 'none'; ?>;">
                        <label for="other_area" class="form-label required">Specify Other Area</label>
                        <input type="text" id="other_area" name="other_area" class="form-input <?php echo isset($fieldErrors['other_area']) ? 'error' : ''; ?>" 
                               value="<?php echo $other_area; ?>"
                               placeholder="Enter your specific area in Melaka">
                        <div class="form-hint">Please specify your area if not listed above</div>
                        <?php if (isset($fieldErrors['other_area'])): ?>
                            <div class="field-error"><?php echo htmlspecialchars($fieldErrors['other_area']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 地址预览 -->
                    <div class="address-preview" id="addressPreview">
                        <strong>Address Preview:</strong>
                        <div id="previewText">
                            <?php if (!empty($address_area) && !empty($address_postcode) && !empty($address_line)): ?>
                                <?php 
                                $display_area = ($address_area === 'other' && !empty($other_area)) ? $other_area : $address_area;
                                echo htmlspecialchars($address_line) . '<br>' .
                                     htmlspecialchars($display_area) . ', ' . $address_postcode . ' Melaka<br>' .
                                     'Malaysia';
                                ?>
                            <?php else: ?>
                                No address selected yet
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 操作按钮 -->
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
                
                <!-- 表单说明 -->
                <div class="form-note" style="text-align: center; margin-top: 20px; color: #666;">
                    <i class="fas fa-info-circle"></i> Fields marked with * are required. Address must be in Melaka.
                </div>
            </form>
        </div>
    </main>

    <!-- 网站页脚 -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </div>
                <p>Sweet & Delicious</p>
                <div class="footer-links">
                    <a href="mainpage.php">Home</a>
                    <a href="menu.html">Menu</a>
                    <a href="about_us.html">About</a>
                    <a href="contact.html">Contact</a>
                    <a href="privacypolicy.html">Privacy Policy</a>
                    <a href="termservice.html">Terms of Service</a>
                </div>
                <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript 代码 -->
    <script>
        // 1. 页面加载完成后执行
        document.addEventListener('DOMContentLoaded', function() {
            initUserMenu();  // 初始化用户菜单
            
            // 2. 保存按钮加载状态
            const saveButton = document.getElementById('saveButton');
            const form = document.getElementById('profileForm');
            
            if (saveButton && form) {
                form.addEventListener('submit', function() {
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    saveButton.disabled = true;  // 防止重复提交
                });
            }
            
            // 3. 实时更新地址预览
            document.getElementById('address_area').addEventListener('change', updateAddressPreview);
            document.getElementById('address_postcode').addEventListener('input', updateAddressPreview);
            document.getElementById('address_line').addEventListener('input', updateAddressPreview);
            document.getElementById('other_area').addEventListener('input', updateAddressPreview);
            
            // 4. 初始化显示其他区域输入框
            toggleOtherArea();
            
            // 5. 如果有错误字段，滚动到第一个错误
            setTimeout(function() {
                const firstError = document.querySelector('.form-input.error, .form-textarea.error, select.form-input.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }, 500);
        });
        
        // 6. 初始化用户下拉菜单
        function initUserMenu() {
            const userIcon = document.querySelector('.user-icon');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (userIcon && dropdownMenu) {
                // 点击用户图标显示/隐藏菜单
                userIcon.addEventListener('click', function(e) {
                    e.stopPropagation();  // 阻止事件冒泡
                    toggleDropdown(dropdownMenu);
                });
                
                // 点击页面其他地方关闭菜单
                document.addEventListener('click', function(e) {
                    if (!userIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.style.display = 'none';
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
        }
        
        // 7. 切换下拉菜单显示/隐藏
        function toggleDropdown(dropdown) {
            if (!dropdown) return;
            
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                dropdown.classList.remove('active');
            } else {
                dropdown.style.display = 'block';
                dropdown.classList.add('active');
            }
        }
        
        // 8. 切换其他区域输入框
        function toggleOtherArea() {
            const areaSelect = document.getElementById('address_area');
            const otherAreaGroup = document.getElementById('other_area_group');
            
            if (areaSelect.value === 'other') {
                otherAreaGroup.style.display = 'block';
            } else {
                otherAreaGroup.style.display = 'none';
            }
            updateAddressPreview();
        }
        
        // 9. 更新地址预览
        function updateAddressPreview() {
            const areaSelect = document.getElementById('address_area');
            const postcodeInput = document.getElementById('address_postcode');
            const addressLine = document.getElementById('address_line');
            const otherAreaInput = document.getElementById('other_area');
            const previewDiv = document.getElementById('previewText');
            
            const area = areaSelect.value;
            const postcode = postcodeInput.value;
            const address = addressLine.value;
            const otherArea = otherAreaInput.value;
            
            let preview = '';
            
            if (area && postcode && address) {
                const displayArea = (area === 'other' && otherArea) ? otherArea : area;
                preview = address + '<br>' +
                          displayArea + ', ' + postcode + ' Melaka<br>' +
                          'Malaysia';
            } else {
                preview = 'No address selected yet';
            }
            
            previewDiv.innerHTML = preview;
        }
    </script>
</body>
</html>