<?php
session_start();
require_once 'config.php';

// 1. 登录检查
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$dbErrorMessage = '';

// 2. 从数据库获取用户信息
$userStmt = $pdo->prepare("SELECT name, email, phone FROM user_db WHERE id = ?");
$userStmt->execute([$userId]);
$userData = $userStmt->fetch(PDO::FETCH_ASSOC);

// 3. 获取该用户的所有地址
$addrStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$addrStmt->execute([$userId]);
$allAddresses = $addrStmt->fetchAll(PDO::FETCH_ASSOC);

// 统计地址数量
$addressCount = count($allAddresses);

// 4. 处理下单请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['fullName'] ?? '';
    $email       = $_POST['email'] ?? '';
    $phone       = $_POST['phone'] ?? '';
    $address     = $_POST['address'] ?? ''; 
    $city        = $_POST['city'] ?? '';
    $postcode    = $_POST['postcode'] ?? '';
    $cartDataRaw = $_POST['cart_data'] ?? '[]';
    $paymentMethod = $_POST['paymentMethod'] ?? null;

    $cartData = json_decode($cartDataRaw, true);
    $total = 0;
    if (is_array($cartData)) {
        foreach ($cartData as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }

    try {
        $pdo->beginTransaction();

        if (is_array($cartData) && !empty($cartData)) {
            foreach ($cartData as $item) {
                $productId = (int)$item['id'];
                $requestedQty = (int)$item['quantity'];

                // Lock the product row and get current stock
                $stockStmt = $pdo->prepare("SELECT stock FROM products WHERE id = ? AND deleted_at IS NULL FOR UPDATE");
                $stockStmt->execute([$productId]);
                $currentStock = (int)$stockStmt->fetchColumn();

                if ($currentStock === false) {
                    throw new Exception("Product ID {$productId} not found or deleted.");
                }

                if ($currentStock < $requestedQty) {
                    throw new Exception("Sorry, only {$currentStock} left in stock for '{$item['name']}'. Please update your cart.");
                }

                // Decrease stock (reserve for this order)
                $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStmt->execute([$requestedQty, $productId]);
            }
        } else {
            throw new Exception("Your cart is empty or invalid.");
        }

        $stmt = $pdo->prepare("
            INSERT INTO orders 
            (customer_name, customer_email, customer_phone, delivery_address, city, postcode, total, payment_method, payment_status, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')
        ");
        $stmt->execute([$customerName, $email, $phone, $address, $city, $postcode, $total + 5.00, $paymentMethod]);
        $orderId = $pdo->lastInsertId();

        $stmtDetail = $pdo->prepare("
            INSERT INTO orders_detail (order_id, product_id, product_name, price, quantity, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        foreach ($cartData as $item) {
            $stmtDetail->execute([$orderId, $item['id'], $item['name'], $item['price'], $item['quantity'], ($item['price'] * $item['quantity'])]);
        }
        $clearCartStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCartStmt->execute([$userId]);

        $pdo->commit();

        if ($paymentMethod === 'debitCard') {
    header("Location: process_debit.php?order_id={$orderId}");
} elseif ($paymentMethod === 'tng') {
    header("Location: process_tng.php?order_id={$orderId}"); // 新增
} elseif ($paymentMethod === 'fpx') {
    header("Location: process_fpx.php?order_id={$orderId}"); // 新增
} else {
    header("Location: simulate_gateway.php?order_id={$orderId}&method=" . urlencode($paymentMethod));
}
        exit;
    } catch (PDOException $e) {
    $pdo->rollBack();
    // 把下面这行改成 die，这样如果报错，页面会卡住并显示具体错误
    $_SESSION['checkout_error'] = $e->getMessage();
        header("Location: cart.php");
}
}

function parseAddr($raw) {
    if (strpos($raw, '|') !== false) {
        $p = explode('|', $raw);
        return ['street' => $p[0], 'area' => $p[1], 'postcode' => $p[2]];
    }
    return ['street' => $raw, 'area' => '', 'postcode' => ''];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Bakery House</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="payment.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <form id="paymentForm" method="post" onsubmit="return validateCheckout()">
            <div class="flex-layout">
                <div class="left-column">
                    <div class="card">
                        <div class="card-title">Order Summary</div>
                        <div id="summaryItems"></div>
                        <div class="total-row">
                            <div style="display:flex; justify-content:space-between; margin-bottom: 8px; font-size: 14px; font-weight: normal; color: #777;">
                                <span>Delivery Fee:</span>
                                <span>RM 5.00</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span>Total:</span>
                                <span id="totalPriceDisplay" style="color:var(--accent);">RM 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <div class="card">
    <div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
        <span><i class="fas fa-map-marker-alt"></i> Delivery Address</span>
        <span class="btn-change" onclick="toggleModal(true)" style="color: #d4a76a; font-weight: bold; cursor: pointer;">Change</span>
    </div>
    
    <div class="address-box" style="display: flex; align-items: flex-start; gap: 15px; margin-top: 10px;">
        <i class="fas fa-location-dot" style="color: #c5a073; font-size: 18px; margin-top: 4px;"></i>
        <div class="address-details">
            <div class="user-meta" style="font-weight: 800; font-size: 16px; color: #333; margin-bottom: 5px;">
                <span id="displayUserName"><?php echo strtoupper(htmlspecialchars($userData['name'])); ?></span> 
                <span id="displayUserPhone" style="margin-left: 20px;"><?php echo htmlspecialchars($userData['phone'] ?? ''); ?></span>
            </div>
            <div class="address-text" id="addressLabel" style="color: #666; font-size: 14px; line-height: 1.5;">
                <?php 
                if ($addressCount > 0) {
                    $def = $allAddresses[0]; // 默认地址排在第一位
                    $p = parseAddr($def['address_text']);
                    echo htmlspecialchars($p['street']) . ", " . htmlspecialchars($p['area']) . ", Melaka, " . htmlspecialchars($p['postcode']);
                } else {
                    echo "No address found. Please add one.";
                }
                ?>
            </div>
        </div>
    </div>

    <input type="hidden" name="fullName" value="<?php echo htmlspecialchars($userData['name']); ?>">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>">
    <input type="hidden" name="address" id="hiddenAddress" value="<?php echo $addressCount > 0 ? htmlspecialchars(parseAddr($allAddresses[0]['address_text'])['street']) : ''; ?>">
    <input type="hidden" name="city" id="hiddenCity" value="<?php echo $addressCount > 0 ? htmlspecialchars(parseAddr($allAddresses[0]['address_text'])['area']) : ''; ?>">
    <input type="hidden" name="postcode" id="hiddenPostcode" value="<?php echo $addressCount > 0 ? htmlspecialchars(parseAddr($allAddresses[0]['address_text'])['postcode']) : ''; ?>">
    <input type="hidden" name="cart_data" id="cartDataInput">
</div>

                    <div class="card">
    <div class="card-title">Payment Method</div>
    
    <label class="method-item" id="label-debit">
        <input type="radio" name="paymentMethod" value="debitCard" required onclick="toggleCardFields(true)">
        <img src="payment logo/Visa.jpg" alt="Visa" class="method-logo"> <span style="flex:1; font-weight:500;">Debit Card</span>
    </label>

    <div id="cardDetailsSection">
    <div class="form-group">
        <label>Card Number (16 Digits)</label>
        <input type="text" id="cardNumberInput" name="card_number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19">
        <div id="cardError" class="error-msg"></div> </div>
    <div class="form-row">
        <div class="form-group" style="flex:2;">
            <label>Expiry Date</label>
            <input type="text" id="expiryInput" class="form-input" placeholder="MM/YY" maxlength="5">
            <div id="expiryError" class="error-msg"></div> </div>
        <div class="form-group" style="flex:1;">
            <label>CVV</label>
            <input type="password" id="cvvInput" class="form-input" placeholder="123" maxlength="3">
            <div id="cvvError" class="error-msg"></div> </div>
    </div>
</div>

    <label class="method-item" id="label-tng">
        <input type="radio" name="paymentMethod" value="tng" onclick="toggleCardFields(false)">
        <img src="payment logo/Touch_'n_Go_eWallet.png" alt="TNG" class="method-logo"> <span style="flex:1; font-weight:500;">TNG eWallet</span>
    </label>

    <label class="method-item" id="label-fpx">
        <input type="radio" name="paymentMethod" value="fpx" onclick="toggleCardFields(false)">
        <img src="payment logo/Logo-FPX.png" alt="FPX" class="method-logo"> <span style="flex:1; font-weight:500;">FPX Online Banking</span>
    </label>
</div>

                    <button type="submit" class="place-order-btn">Place Order Now</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="addrModal">
    <div class="modal-content">
        <h3><i class="fas fa-map-marker-alt"></i> Delivery Address</h3>
        
        <div id="modalList" style="max-height: 380px; overflow-y: auto; padding: 5px;">
            <?php if (empty($allAddresses)): ?>
                <div class="empty-addr-state">
                    <i class="fas fa-map-location-dot"></i>
                    <p>No addresses found.<br>Please add one to continue.</p>
                </div>
            <?php else: ?>
                <?php foreach ($allAddresses as $addr): 
                    $p = parseAddr($addr['address_text']); 
                ?>
                    <div class="addr-option <?php echo $addr['is_default'] ? 'selected' : ''; ?>" 
                         onclick="selectAddr(this, '<?php echo addslashes($p['street']); ?>', '<?php echo addslashes($p['area']); ?>', '<?php echo addslashes($p['postcode']); ?>')">
                        
                        <div class="addr-title-row">
                            <strong><i class="fas fa-user"></i> <?php echo htmlspecialchars($userData['name']); ?></strong>
                            <?php if ($addr['is_default']): ?>
                                <span class="default-badge">Default</span>
                            <?php endif; ?>
                        </div>
                        
                        <span><i class="fas fa-location-arrow"></i> <?php echo htmlspecialchars($p['street']); ?>, <?php echo htmlspecialchars($p['area']); ?>, <?php echo htmlspecialchars($p['postcode']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding: 0 5px;">
            <a href="manageaddress.php" style="color: #d4a76a; text-decoration: none; font-size: 13px; font-weight: 600;">
                <i class="fas fa-edit"></i> Edit List
            </a>
            <a href="add.address.php" style="color: #5a3921; text-decoration: none; font-size: 13px; font-weight: 600;">
                <i class="fas fa-plus-circle"></i> Add New Address
            </a>
        </div>

        <button class="btn-modal-close" onclick="toggleModal(false)">Cancel</button>
    </div>
</div>

    <div id="addressRequiredModal" class="force-modal-overlay">
        <div class="force-modal-content">
            <div class="modal-icon">📍</div> 
            <h2>Please Add Address</h2>
            <p>You need to add a delivery address to your account before you can proceed with your payment.</p>
            <div class="modal-actions">
                <a href="add.address.php" class="btn-go-address">Go to Add Address</a>
                <div class="btn-maybe-later" onclick="closeAddressModal()">Maybe Later</div>
            </div>
        </div>
    </div>

    <div id="paymentCancelModal" class="force-modal-overlay">
    <div class="force-modal-content">
        <div class="modal-icon" style="color: #e74c3c;">❌</div> 
        <h2>Payment Cancelled</h2>
        <p>You have cancelled the payment process. Your items are still in the cart, and you can try again whenever you're ready.</p>
        <div class="modal-actions">
            <button class="btn-go-address" onclick="closeCancelModal()" style="background: #5a3921; cursor: pointer; border: none; width: 100%;">Got it</button>
        </div>
    </div>
</div>

    <script>
    // --- 1. 基础数据初始化 ---
    const userAddressCount = <?php echo $addressCount; ?>;
    let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    document.getElementById('cartDataInput').value = JSON.stringify(cart);

    window.onload = function() {
        // 自动选择默认地址
        const def = document.querySelector('.addr-option.selected');
        if (def) def.click();

        // 渲染订单摘要
        renderSummary();

        // 延迟清空输入框防止浏览器自动填充干扰
        setTimeout(() => {
            if(document.getElementById('cardNumberInput')) document.getElementById('cardNumberInput').value = '';
            if(document.getElementById('expiryInput')) document.getElementById('expiryInput').value = '';
            if(document.getElementById('cvvInput')) document.getElementById('cvvInput').value = '';
        }, 100);
    };

    // --- 2. 核心校验逻辑 (Place Order 点击时触发) ---
    function validateCheckout() {
    // 1. 每次点击前先清除之前的红色错误状态
    clearErrors();

    // 2. 检查地址是否存在
    if (typeof userAddressCount === 'undefined' || userAddressCount === 0) {
        document.getElementById('addressRequiredModal').style.display = 'flex';
        return false; 
    }

    // 3. 检查支付方式是否选择
    const checked = document.querySelector('input[name="paymentMethod"]:checked');
    if (!checked) { 
        alert("Please select a payment method."); 
        return false; 
    }
    
    // 4. 如果选的是 Debit Card，执行详细校验
    let isValid = true;
    if (checked.value === 'debitCard') {
        const cardNum = document.getElementById('cardNumberInput').value.replace(/\s+/g, '');
        const expiry = document.getElementById('expiryInput').value;
        const cvv = document.getElementById('cvvInput').value;

        // 校验卡号 (必须 16 位)
        if (cardNum.length !== 16 || isNaN(cardNum)) {
            showError('cardNumberInput', 'cardError', 'Please enter a valid 16-digit card number.');
            isValid = false;
        }

        // 校验有效期 (MM/YY 格式且需大于等于 2026年1月)
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            showError('expiryInput', 'expiryError', 'Use MM/YY format.');
            isValid = false;
        } else {
            const [m, y] = expiry.split('/').map(num => parseInt(num));
            const currentYear = 26; // 2026年
            const currentMonth = 1;

            if (m < 1 || m > 12) {
                showError('expiryInput', 'expiryError', 'Invalid month (01-12).');
                isValid = false;
            } else if (y < currentYear || (y === currentYear && m < currentMonth)) {
                showError('expiryInput', 'expiryError', 'Card has expired.');
                isValid = false;
            }
        }

        // 校验 CVV (必须 3 位)
        if (cvv.length !== 3 || isNaN(cvv)) {
            showError('cvvInput', 'cvvError', 'Enter 3-digit CVV.');
            isValid = false;
        }
    }

    // 5. 【核心修复】如果校验通过，把 LocalStorage 的购物车数据同步到隐藏 Input
    if (isValid) {
        if (typeof cart !== 'undefined' && cart.length > 0) {
            // 将购物车数组转成 JSON 字符串，存入隐藏域
            document.getElementById('cartDataInput').value = JSON.stringify(cart);
        } else {
            alert("Your cart is empty!");
            return false;
        }
    }

    return isValid; // 只有全部校验通过且数据同步后，才允许提交表单
}

    // --- 3. UI 辅助函数 ---

    // 显示错误：输入框加红框，下方显红字
    function showError(inputId, errorId, message) {
        const inputField = document.getElementById(inputId);
        const errorDiv = document.getElementById(errorId);
        if (inputField) inputField.classList.add('input-error');
        if (errorDiv) {
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';
        }
    }

    // 清除错误状态
    function clearErrors() {
        document.querySelectorAll('.form-input').forEach(i => i.classList.remove('input-error'));
        document.querySelectorAll('.error-msg').forEach(e => e.style.display = 'none');
    }

    function closeAddressModal() {
        document.getElementById('addressRequiredModal').style.display = 'none';
    }

    // 切换弹窗显示/隐藏
function toggleModal(show) { 
    const modal = document.getElementById('addrModal');
    if (show) {
        modal.classList.add('active');
    } else {
        modal.classList.remove('active');
    }
}

// 选择地址时的处理函数
function selectAddr(el, street, area, postcode) {
    // 1. 样式即时切换
    document.querySelectorAll('.addr-option').forEach(item => item.classList.remove('selected'));
    el.classList.add('selected');

    // 2. 更新主页面的地址显示块
    const displayLabel = document.getElementById('addressLabel');
    displayLabel.style.opacity = '0'; // 简单的淡出效果
    
    setTimeout(() => {
        displayLabel.innerText = `${street}, ${area}, Melaka, ${postcode}`;
        displayLabel.style.opacity = '1';
        
        // 更新隐藏域
        document.getElementById('hiddenAddress').value = street;
        document.getElementById('hiddenCity').value = area;
        document.getElementById('hiddenPostcode').value = postcode;
    }, 200);

    // 3. 自动关闭弹窗，并给用户一个微小的延迟感，确保他们看到了勾选动作
    setTimeout(() => toggleModal(false), 400); 
}

    // --- 4. 输入格式化监听 ---

    // 卡号：每4位加空格
    document.getElementById('cardNumberInput').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = formattedValue;
    });

    // 有效期：自动加斜杠 /
    document.getElementById('expiryInput').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length >= 2) {
            e.target.value = v.substring(0, 2) + '/' + v.substring(2, 4);
        }
    });

    function toggleCardFields(show) {
        document.getElementById('cardDetailsSection').style.display = show ? 'block' : 'none';
        document.querySelectorAll('.method-item').forEach(el => el.classList.remove('active'));
        if(show) document.getElementById('label-debit').classList.add('active');
    }

    function selectAddr(el, street, area, postcode) {
        document.querySelectorAll('.addr-option').forEach(item => item.classList.remove('selected'));
        el.classList.add('selected');
        const formattedAddress = `${street}, ${area}, Melaka, ${postcode}`;
        document.getElementById('addressLabel').innerText = formattedAddress;
        document.getElementById('hiddenAddress').value = street;
        document.getElementById('hiddenCity').value = area;
        document.getElementById('hiddenPostcode').value = postcode;
        toggleModal(false);
    }

    // --- 5. 订单摘要渲染 ---
    // --- 5. 订单摘要渲染 (同步 Cart 的排序逻辑) ---
    function renderSummary() {
        const container = document.getElementById('summaryItems');
        let subtotal = 0;
        let html = '';

        if (cart.length === 0) {
            container.innerHTML = '<p style="text-align:center; color:#999; padding: 20px;">Your cart is empty.</p>';
            return;
        }

        // 🚀 核心修改：使用与 cart.php 一样的反转逻辑，让最新添加的在最上面
        const displayCart = [...cart].reverse();

        displayCart.forEach(item => {
            const linePrice = parseFloat(item.price) * parseInt(item.quantity);
            subtotal += linePrice;
            html += `
                <div class="summary-item-row">
                    <img src="${item.image}" alt="${item.name}" class="summary-item-img">
                    <div class="summary-item-info">
                        <div class="summary-item-detail">
                            <span class="summary-item-name">${item.name}</span>
                            <span class="summary-item-qty">Qty: ${item.quantity}</span>
                        </div>
                        <div class="summary-item-price">RM ${linePrice.toFixed(2)}</div>
                    </div>
                </div>`;
        });

        container.innerHTML = html;
        document.getElementById('totalPriceDisplay').innerText = `RM ${(subtotal + 5.00).toFixed(2)}`;
    }
</script>
    <link rel="stylesheet" href="footer.css">
</body>
</html>