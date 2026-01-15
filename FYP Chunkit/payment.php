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
        $pdo->commit();

        if ($paymentMethod === 'debitCard') {
            header("Location: process_debit.php?order_id={$orderId}");
        } else {
            header("Location: simulate_gateway.php?order_id={$orderId}&method=" . urlencode($paymentMethod));
        }
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $dbErrorMessage = 'Database Error: ' . $e->getMessage();
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
    <style>
        :root {
            --primary: #5a3921;
            --accent: #d4a76a;
            --bg: #fff7ec;
            --white: #ffffff;
            --border: #eeeeee;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding-top: 100px; 
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 25px;
        }

        .flex-layout {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        .left-column, .right-column {
            flex: 1; 
        }

        .left-column {
            position: sticky;
            top: 110px;
            max-height: calc(100vh - 130px);
            display: flex;
            flex-direction: column;
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 15px rgba(90, 57, 33, 0.05);
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 18px;
            color: var(--primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
            font-weight: 600;
        }

        /* --- Order Summary 滚动区域 --- */
        #summaryItems {
            max-height: 380px; 
            overflow-y: auto; 
            overflow-x: hidden;
            padding-right: 8px;
        }

        #summaryItems::-webkit-scrollbar { width: 5px; }
        #summaryItems::-webkit-scrollbar-track { background: #fdf8f3; border-radius: 10px; }
        #summaryItems::-webkit-scrollbar-thumb { background: #e0d5c1; border-radius: 10px; }

        .summary-item-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f9f9f9;
        }

        .summary-item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
            border: 1px solid #eee;
        }

        .summary-item-info {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-item-detail {
            display: flex;
            flex-direction: column;
        }

        .summary-item-name {
            font-size: 13px;
            color: #444;
            font-weight: 600;
            max-width: 180px;
            line-height: 1.4;
        }

        /* 重点：图片中要求的 Qty 浅色样式 */
        .summary-item-qty {
            font-size: 12px;
            color: #999; 
            font-weight: 400;
            margin-top: 2px;
        }

        .summary-item-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 14px;
        }

        /* --- Payment Method 新增样式 --- */
        .method-item {
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: 0.3s;
        }
        .method-item:hover { border-color: var(--accent); }
        .method-item.active { border-color: var(--accent); background: #fffcf9; }

        #cardDetailsSection {
            display: none;
            padding: 20px;
            background: #fdfdfd;
            border: 1px solid #eee;
            border-radius: 10px;
            margin-top: -5px;
            margin-bottom: 15px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 11px; color: #888; margin-bottom: 5px; text-transform: uppercase; font-weight: 600; }
        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-input:focus { border-color: var(--accent); outline: none; }
        .form-row { display: flex; gap: 15px; }

        /* --- 原有通用样式 --- */
        .address-box { display: flex; gap: 15px; align-items: flex-start; }
        .address-box i { color: var(--accent); font-size: 20px; margin-top: 4px; }
        .user-meta { font-weight: bold; font-size: 16px; margin-bottom: 6px; }
        .address-text { color: #666; font-size: 14px; line-height: 1.6; }
        .btn-change { margin-left: auto; color: var(--accent); font-weight: bold; cursor: pointer; font-size: 14px; }

        .total-row { margin-top: 10px; padding-top: 15px; border-top: 2px solid #fdf8f3; font-weight: bold; font-size: 18px; color: var(--primary); }
        .place-order-btn { width: 100%; padding: 18px; background: var(--primary); color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .place-order-btn:hover { background: #452c1a; }

        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: var(--white); width: 450px; border-radius: 15px; padding: 25px; }
        .addr-option { border: 1px solid #eee; padding: 15px; border-radius: 10px; margin-bottom: 10px; cursor: pointer; }
        .addr-option.selected { border: 2px solid var(--accent); background: #fffcf9; }

        @media (max-width: 992px) {
            .flex-layout { flex-direction: column; }
            .left-column, .right-column { width: 100%; flex: none; }
            .left-column { position: static; max-height: none; }
        }
    </style>
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
                        <div class="card-title">
                            <i class="fas fa-map-marker-alt"></i> Delivery Address
                            <span class="btn-change" onclick="toggleModal(true)">Change</span>
                        </div>
                        <div class="address-box">
                            <i class="fas fa-location-dot"></i>
                            <div>
                                <div class="user-meta">
                                    <span><?php echo htmlspecialchars($userData['name']); ?></span> 
                                    <span style="margin-left:15px;"><?php echo htmlspecialchars($userData['phone'] ?? ''); ?></span>
                                </div>
                                <div class="address-text" id="addressLabel">Loading address...</div>
                            </div>
                        </div>
                        <input type="hidden" name="fullName" value="<?php echo htmlspecialchars($userData['name']); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
                        <input type="hidden" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>">
                        <input type="hidden" name="address" id="hiddenAddress">
                        <input type="hidden" name="city" id="hiddenCity">
                        <input type="hidden" name="postcode" id="hiddenPostcode">
                        <input type="hidden" name="cart_data" id="cartDataInput">
                    </div>

                    <div class="card">
                        <div class="card-title">Payment Method</div>
                        
                        <label class="method-item" id="label-debit">
                            <input type="radio" name="paymentMethod" value="debitCard" required onclick="toggleCardFields(true)">
                            <i class="far fa-credit-card"></i>
                            <span style="flex:1; font-weight:500;">Debit Card</span>
                        </label>

                        <div id="cardDetailsSection">
                            <div class="form-group">
                                <label>Card Number (16 Digits)</label>
                                <input type="text" id="cardNumberInput" name="card_number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19" autocomplete="cc-number">
                            </div>
                            <div class="form-row">
                                <div class="form-group" style="flex:2;">
                                    <label>Expiry Date</label>
                                    <input type="text" id="expiryInput" class="form-input" placeholder="MM/YY" maxlength="5" autocomplete="off">
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label>CVV</label>
                                    <input type="password" id="cvvInput" class="form-input" placeholder="123" maxlength="3" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <label class="method-item" id="label-tng">
                            <input type="radio" name="paymentMethod" value="tng" onclick="toggleCardFields(false)">
                            <i class="fas fa-wallet"></i>
                            <span style="flex:1; font-weight:500;">TNG eWallet</span>
                        </label>

                        <label class="method-item" id="label-fpx">
                            <input type="radio" name="paymentMethod" value="fpx" onclick="toggleCardFields(false)">
                            <i class="fas fa-university"></i>
                            <span style="flex:1; font-weight:500;">FPX Online Banking</span>
                        </label>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order Now</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="addrModal">
        <div class="modal-content">
            <h3 style="text-align:center; margin-bottom:20px; color:var(--primary);">Select Delivery Address</h3>
            <div id="modalList">
                <?php foreach ($allAddresses as $addr): 
                    $p = parseAddr($addr['address_text']); 
                ?>
                    <div class="addr-option <?php echo $addr['is_default'] ? 'selected' : ''; ?>" 
                         onclick="selectAddr(this, '<?php echo addslashes($p['street']); ?>', '<?php echo addslashes($p['area']); ?>', '<?php echo addslashes($p['postcode']); ?>')">
                        <strong><?php echo htmlspecialchars($userData['name']); ?></strong><br>
                        <span style="font-size:13px; color:#666;"><?php echo htmlspecialchars($p['street']); ?>, <?php echo htmlspecialchars($p['area']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <button onclick="toggleModal(false)" style="width:100%; padding:12px; background:#eee; border:none; border-radius:8px; cursor:pointer; font-weight:bold; margin-top:10px;">Close</button>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        document.getElementById('cartDataInput').value = JSON.stringify(cart);

        window.onload = function() {
            const def = document.querySelector('.addr-option.selected');
            if (def) def.click();
            renderSummary();

            // 关键：强制清空可能存在的浏览器填充
            setTimeout(() => {
                document.getElementById('cardNumberInput').value = '';
                document.getElementById('expiryInput').value = '';
                document.getElementById('cvvInput').value = '';
            }, 100);
        };

        // 切换支付详情显示
        function toggleCardFields(show) {
            document.getElementById('cardDetailsSection').style.display = show ? 'block' : 'none';
            document.querySelectorAll('.method-item').forEach(el => el.classList.remove('active'));
            if(show) document.getElementById('label-debit').classList.add('active');
        }

        // 卡号格式化
        document.getElementById('cardNumberInput').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = "";
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += " ";
                formattedValue += value[i];
            }
            e.target.value = formattedValue;
        });

        // 下单校验
        function validateCheckout() {
            const checked = document.querySelector('input[name="paymentMethod"]:checked');
            if (!checked) { alert("Please select a payment method."); return false; }
            
            if (checked.value === 'debitCard') {
                const cardNum = document.getElementById('cardNumberInput').value.replace(/\s+/g, '');
                if (cardNum.length !== 16) {
                    alert("Please enter a valid 16-digit card number.");
                    return false;
                }
            }
            return true;
        }

        function toggleModal(show) { document.getElementById('addrModal').classList.toggle('active', show); }

        function selectAddr(el, street, area, postcode) {
            document.querySelectorAll('.addr-option').forEach(item => item.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('addressLabel').innerText = `${street}, ${area}, Melaka, ${postcode}`;
            document.getElementById('hiddenAddress').value = street;
            document.getElementById('hiddenCity').value = area;
            document.getElementById('hiddenPostcode').value = postcode;
            toggleModal(false);
        }

        function renderSummary() {
            const container = document.getElementById('summaryItems');
            let subtotal = 0;
            let html = '';

            if (cart.length === 0) {
                container.innerHTML = '<p style="text-align:center; color:#999; padding: 20px;">Your cart is empty.</p>';
                return;
            }

            cart.forEach(item => {
                const linePrice = parseFloat(item.price) * parseInt(item.quantity);
                subtotal += linePrice;
                
                // 优化后的 HTML 结构，支持 Qty 浅色显示
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
</body>
</html>