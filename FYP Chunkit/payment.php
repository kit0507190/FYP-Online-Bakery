<?php
session_start();
require_once 'config.php';

// 1. Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$dbErrorMessage = '';

// 2. Get user information + credit balance
$userStmt = $pdo->prepare("SELECT name, email, phone FROM user_db WHERE id = ?");
$userStmt->execute([$userId]);
$userData = $userStmt->fetch(PDO::FETCH_ASSOC);

$creditBalance = 0.00;
if ($userData) {
    $creditStmt = $pdo->prepare("SELECT credit FROM user_db WHERE id = ?");
    $creditStmt->execute([$userId]);
    $creditBalance = (float) $creditStmt->fetchColumn() ?: 0.00;
}

// 3. Get all addresses for this user
$addrStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$addrStmt->execute([$userId]);
$allAddresses = $addrStmt->fetchAll(PDO::FETCH_ASSOC);

$addressCount = count($allAddresses);

// 4. Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['fullName']   ?? '';
    $email        = $_POST['email']      ?? '';
    $phone        = $_POST['phone']      ?? '';
    $address      = $_POST['address']    ?? '';
    $city         = $_POST['city']       ?? '';
    $postcode     = $_POST['postcode']   ?? '';
    $cartDataRaw  = $_POST['cart_data']  ?? '[]';
    $paymentMethod = $_POST['paymentMethod'] ?? null;

    $cartData = json_decode($cartDataRaw, true);
    $subtotal = 0;
    if (is_array($cartData)) {
        foreach ($cartData as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }

    $grandTotal = $subtotal + 5.00;

    try {
    $pdo->beginTransaction();

    // ── Stock check & reservation (deduct temporarily) ────────────────────────────────
    if (is_array($cartData) && !empty($cartData)) {
        foreach ($cartData as $item) {
            $productId = (int)$item['id'];
            $requestedQty = (int)$item['quantity'];

            $stockStmt = $pdo->prepare("SELECT stock FROM products WHERE id = ? AND deleted_at IS NULL FOR UPDATE");
            $stockStmt->execute([$productId]);
            $currentStock = (int)$stockStmt->fetchColumn();

            if ($currentStock === false) {
                throw new Exception("Product ID {$productId} not found or deleted.");
            }

            if ($currentStock < $requestedQty) {
                throw new Exception("Sorry, only {$currentStock} left in stock for '{$item['name']}'. Please update your cart.");
            }

            $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $updateStmt->execute([$requestedQty, $productId]);
        }
    } else {
        throw new Exception("Your cart is empty or invalid.");
    }

    // ── Bank card validation ──────────────────────────────────
    if ($paymentMethod === 'debitCard') {
        $inputCardNum = str_replace(' ', '', $_POST['card_number'] ?? '');
        $inputExpiry  = $_POST['card_expiry'] ?? '';
        $inputCVV     = $_POST['card_cvv'] ?? '';

        $checkCardStmt = $pdo->prepare("
            SELECT id FROM bank_cards 
            WHERE card_number = ? AND expiry_date = ? AND cvv = ? 
            LIMIT 1
        ");
        $checkCardStmt->execute([$inputCardNum, $inputExpiry, $inputCVV]);
        
        if (!$checkCardStmt->fetch()) {
            throw new Exception("Invalid Card: The card details entered do not exist in our banking records.");
        }
    }

    // ── Handle credit payment ────────────────────────────────
    $payment_status = 'preparing';

    if ($paymentMethod === 'credits') {
        $creditStmt = $pdo->prepare("SELECT credit FROM user_db WHERE id = ? FOR UPDATE");
        $creditStmt->execute([$userId]);
        $currentCredit = (float) $creditStmt->fetchColumn();

        if ($currentCredit < $grandTotal) {
            throw new Exception("Insufficient credits. Current balance: RM " . number_format($currentCredit, 2));
        }

        $deductStmt = $pdo->prepare("UPDATE user_db SET credit = credit - ? WHERE id = ?");
        $deductStmt->execute([$grandTotal, $userId]);

        $payment_status = 'paid';  // instant success
    }

    // Create main order record ── NO user_id column ────────────────────────────────
    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (customer_name, customer_email, customer_phone, delivery_address, city, postcode, 
         total, payment_method, payment_status, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $customerName, $email, $phone, $address, $city, $postcode,
        $grandTotal, $paymentMethod, $payment_status
    ]);
    $orderId = $pdo->lastInsertId();

    if (!$orderId || $orderId <= 0) {
        throw new Exception("Failed to create order record - no ID returned");
    }

    // Order details
    $stmtDetail = $pdo->prepare("
        INSERT INTO orders_detail (order_id, product_id, product_name, price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    foreach ($cartData as $item) {
        $stmtDetail->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $item['price'] * $item['quantity']
        ]);
    }

    $pdo->commit();

    // ── Post-commit actions (only after successful commit) ─────────────────────────
    if ($paymentMethod === 'credits') {
        // Clear cart
        $clearCartStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCartStmt->execute([$userId]);

        // Update sold_count
        $updateSoldStmt = $pdo->prepare("
            UPDATE products p 
            JOIN orders_detail od ON p.id = od.product_id 
            SET p.sold_count = p.sold_count + od.quantity 
            WHERE od.order_id = ?
        ");
        $updateSoldStmt->execute([$orderId]);

        // Set session flags
        $_SESSION['order_success'] = true;
        $_SESSION['new_order_id']  = $orderId;

        // Redirect
        header("Location: process_credits.php?order_id=" . $orderId);
        exit;
    }
    elseif ($paymentMethod === 'debitCard') {
        header("Location: process_debit.php?order_id={$orderId}");
    } 
    elseif ($paymentMethod === 'tng') {
        header("Location: process_tng.php?order_id={$orderId}");
    } 
    elseif ($paymentMethod === 'fpx') {
        header("Location: process_fpx.php?order_id={$orderId}");
    } 
    else {
        header("Location: simulate_gateway.php?order_id={$orderId}&method=" . urlencode($paymentMethod));
    }
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['checkout_error'] = $e->getMessage();
    header("Location: cart.php");
    exit;
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
    <link rel="stylesheet" href="payment.css?v=<?= time(); ?>">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <form id="paymentForm" method="post" onsubmit="event.preventDefault(); validateCheckout();">
            <div class="flex-layout">
                <div class="left-column">
                    <div class="card">
                        <div class="card-title">Order Summary</div>
                        <div id="summaryItems"></div>
                        <div class="total-row">
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px; color:#777;">
                                <span>Delivery Fee:</span>
                                <span>RM 5.00</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; font-size:18px; font-weight:bold;">
                                <span>Total:</span>
                                <span id="totalPriceDisplay" style="color:var(--accent);">RM 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <div class="card">
                        <div class="card-title" style="display:flex; justify-content:space-between; align-items:center;">
                            <span><i class="fas fa-map-marker-alt"></i> Delivery Address</span>
                            <span class="btn-change" onclick="toggleModal(true)" style="color:#d4a76a; font-weight:bold; cursor:pointer;">Change</span>
                        </div>
                        
                        <div class="address-box" style="display:flex; align-items:flex-start; gap:15px; margin-top:10px;">
                            <i class="fas fa-location-dot" style="color:#c5a073; font-size:18px; margin-top:4px;"></i>
                            <div class="address-details">
                                <div class="user-meta" style="font-weight:800; font-size:16px; color:#333; margin-bottom:5px;">
                                    <span id="displayUserName"><?= strtoupper(htmlspecialchars($userData['name'] ?? '')) ?></span> 
                                    <span id="displayUserPhone" style="margin-left:20px;"><?= htmlspecialchars($userData['phone'] ?? '') ?></span>
                                </div>
                                <div class="address-text" id="addressLabel" style="color:#666; font-size:14px; line-height:1.5;">
                                    <?php 
                                    if ($addressCount > 0) {
                                        $def = $allAddresses[0];
                                        $p = parseAddr($def['address_text']);
                                        echo htmlspecialchars($p['street']) . ", " . htmlspecialchars($p['area']) . ", Melaka, " . htmlspecialchars($p['postcode']);
                                    } else {
                                        echo "No address found. Please add one.";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="fullName"  value="<?= htmlspecialchars($userData['name'] ?? '') ?>">
                        <input type="hidden" name="email"     value="<?= htmlspecialchars($userData['email'] ?? '') ?>">
                        <input type="hidden" name="phone"     value="<?= htmlspecialchars($userData['phone'] ?? '') ?>">
                        <input type="hidden" name="address"   id="hiddenAddress"   value="<?= $addressCount > 0 ? htmlspecialchars(parseAddr($allAddresses[0]['address_text'])['street']) : '' ?>">
                        <input type="hidden" name="city"      id="hiddenCity"      value="<?= $addressCount > 0 ? htmlspecialchars(parseAddr($allAddresses[0]['address_text'])['area']) : '' ?>">
                        <input type="hidden" name="postcode"  id="hiddenPostcode"  value="<?= $addressCount > 0 ? htmlspecialchars(parseAddr($allAddresses[0]['address_text'])['postcode']) : '' ?>">
                        <input type="hidden" name="cart_data" id="cartDataInput">
                    </div>

                    <div class="card">
                        <div class="card-title">Payment Method</div>
                        
                        <label class="method-item" id="label-credits">
                            <input type="radio" name="paymentMethod" value="credits" onclick="toggleCardFields(false)">
                            <i class="fas fa-coins" style="font-size:22px; color:#d4a76a; margin-right:12px;"></i>
                            <span style="flex:1; font-weight:500;">Pay with Credits</span>
                            <span id="creditBalanceDisplay" style="color:#d4a76a; font-weight:600;">
                                RM <?= number_format($creditBalance, 2) ?>
                            </span>
                        </label>

                        <div id="creditsHelp" style="font-size:13px; color:#777; margin: -4px 0 16px 44px; display:none;">
                            Balance will be deducted instantly if sufficient.
                        </div>

                        <label class="method-item" id="label-debit">
                            <input type="radio" name="paymentMethod" value="debitCard" onclick="toggleCardFields(true)">
                            <img src="payment logo/Visa.jpg" alt="Visa" class="method-logo">
                            <span style="flex:1; font-weight:500;">Debit Card</span>
                        </label>

                        <div id="cardDetailsSection" style="display:none;">
                            <div class="form-group">
                                <label>Card Number (16 Digits)</label>
                                <input type="text" id="cardNumberInput" name="card_number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19">
                                <div id="cardError" class="error-msg"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group" style="flex:2;">
                                    <label>Expiry Date</label>
                                    <input type="text" id="expiryInput" name="card_expiry" class="form-input" placeholder="MM/YY" maxlength="5">
                                    <div id="expiryError" class="error-msg"></div>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label>CVV</label>
                                    <input type="password" id="cvvInput" name="card_cvv" class="form-input" placeholder="123" maxlength="3">
                                    <div id="cvvError" class="error-msg"></div>
                                </div>
                            </div>
                        </div>

                        <div id="invalidCardModal" class="force-modal-overlay">
    <div class="force-modal-content">
        <div class="modal-icon" style="color: #e74c3c;">❌</div> 
        <h2 style="color: #e74c3c;">Invalid Card Details</h2>
        <p id="cardErrorMessage">The card details entered do not exist in our records. Please check your card number, expiry date, and CVV.</p>
        <div class="modal-actions">
            <div class="btn-go-address" style="background-color: #e74c3c; cursor:pointer;" onclick="closeCardModal()">Try Again</div>
        </div>
    </div>
</div>

<div id="insufficientCreditsModal" class="force-modal-overlay">
    <div class="force-modal-content">
        <div class="modal-icon" style="color: #e74c3c;">⚠️</div> 
        <h2 style="color: #e74c3c;">Insufficient Credits</h2>
        <p id="insufficientMessage">You do not have enough credits to complete this purchase.</p>
        <div class="modal-actions">
            <div class="btn-go-address" style="background-color: #e74c3c; cursor:pointer;" onclick="closeCreditsModal('insufficientCreditsModal')">Check Balance</div>
        </div>
    </div>
</div>

<div id="confirmCreditsModal" class="force-modal-overlay">
    <div class="force-modal-content">
        <div class="modal-icon" style="color: #d4a76a;">💰</div> 
        <h2 style="color: #5a3921;">Confirm Payment</h2>
        <p id="confirmMessage">Are you sure you want to deduct the amount from your credits?</p>
        <div class="modal-actions">
            <div class="btn-go-address" style="background-color: #5a3921; cursor:pointer; margin-bottom: 10px;" onclick="proceedWithCredits()">Confirm & Pay</div>
            <div class="btn-maybe-later" onclick="closeCreditsModal('confirmCreditsModal')">Cancel</div>
        </div>
    </div>
</div>

                        <label class="method-item" id="label-tng">
                            <input type="radio" name="paymentMethod" value="tng" onclick="toggleCardFields(false)">
                            <img src="payment logo/Touch_'n_Go_eWallet.png" alt="TNG" class="method-logo">
                            <span style="flex:1; font-weight:500;">TNG eWallet</span>
                        </label>

                        <label class="method-item" id="label-fpx">
                            <input type="radio" name="paymentMethod" value="fpx" onclick="toggleCardFields(false)">
                            <img src="payment logo/Logo-FPX.png" alt="FPX" class="method-logo">
                            <span style="flex:1; font-weight:500;">FPX Online Banking</span>
                        </label>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order Now</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Address selection modal -->
    <div class="modal" id="addrModal">
        <div class="modal-content">
            <h3><i class="fas fa-map-marker-alt"></i> Delivery Address</h3>
            
            <div id="modalList" style="max-height:380px; overflow-y:auto; padding:5px;">
                <?php if (empty($allAddresses)): ?>
                    <div class="empty-addr-state">
                        <i class="fas fa-map-location-dot"></i>
                        <p>No addresses found.<br>Please add one to continue.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($allAddresses as $addr): 
                        $p = parseAddr($addr['address_text']); 
                    ?>
                        <div class="addr-option <?= $addr['is_default'] ? 'selected' : '' ?>" 
                             onclick="selectAddr(this, '<?= addslashes($p['street']) ?>', '<?= addslashes($p['area']) ?>', '<?= addslashes($p['postcode']) ?>')">
                            <div class="addr-title-row">
                                <strong><i class="fas fa-user"></i> <?= htmlspecialchars($userData['name']) ?></strong>
                                <?php if ($addr['is_default']): ?>
                                    <span class="default-badge">Default</span>
                                <?php endif; ?>
                            </div>
                            <span><i class="fas fa-location-arrow"></i> <?= htmlspecialchars($p['street']) ?>, <?= htmlspecialchars($p['area']) ?>, <?= htmlspecialchars($p['postcode']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; padding:0 5px;">
                <a href="manageaddress.php" style="color:#d4a76a; text-decoration:none; font-size:13px; font-weight:600;">
                    <i class="fas fa-edit"></i> Edit List
                </a>
                <a href="add.address.php" style="color:#5a3921; text-decoration:none; font-size:13px; font-weight:600;">
                    <i class="fas fa-plus-circle"></i> Add New Address
                </a>
            </div>

            <button class="btn-modal-close" onclick="toggleModal(false)">Cancel</button>
        </div>
    </div>

    <!-- Address required modal -->
    <div id="addressRequiredModal" class="force-modal-overlay">
        <div class="force-modal-content">
            <div class="modal-icon">📍</div> 
            <h2>Please Add Address</h2>
            <p>You need to add a delivery address before proceeding.</p>
            <div class="modal-actions">
                <a href="add.address.php" class="btn-go-address">Go to Add Address</a>
                <div class="btn-maybe-later" onclick="closeAddressModal()">Maybe Later</div>
            </div>
        </div>
    </div>

    <script>
    const userAddressCount = <?= $addressCount ?>;
    const userCredit = <?= json_encode($creditBalance) ?>;
    const deliveryFee  = 5.00;

    let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    document.getElementById('cartDataInput').value = JSON.stringify(cart);

    window.onload = function() {
        const def = document.querySelector('.addr-option.selected');
        if (def) def.click();

        renderSummary();

        setTimeout(() => {
            ['cardNumberInput','expiryInput','cvvInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
        }, 100);
    };

    // Define a global variable to store error types

// 1. Change the variable to an array to store multiple error codes

// Global variable storage state
window.pendingCardErrors = []; 
window.currentTotalAmount = 0; // Store current order total amount

async function validateCheckout() {
    clearErrors();
    window.pendingCardErrors = []; 

    // 1. Address check
    if (userAddressCount === 0) {
        document.getElementById('addressRequiredModal').style.display = 'flex';
        return false;
    }

    // 2. Payment method check
    const checked = document.querySelector('input[name="paymentMethod"]:checked');
    if (!checked) {
        alert("Please select a payment method.");
        return false;
    }

    // Get current order total amount (for subsequent checks)
    const totalStr = document.getElementById('totalPriceDisplay').innerText.replace('RM ', '').replace(',', '');
    window.currentTotalAmount = parseFloat(totalStr);

    // ── Case A: Debit Card validation ──────────────────────────────────
    if (checked.value === 'debitCard') {
        const cardNum = document.getElementById('cardNumberInput').value.replace(/\s+/g, '');
        const expiry  = document.getElementById('expiryInput').value;
        const cvv     = document.getElementById('cvvInput').value;

        let isLocalValid = true;
        if (cardNum.length !== 16 || isNaN(cardNum)) {
            showError('cardNumberInput', 'cardError', 'Please enter a valid 16-digit card number.');
            isLocalValid = false;
        }
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            showError('expiryInput', 'expiryError', 'Use MM/YY format.');
            isLocalValid = false;
        }
        if (cvv.length !== 3 || isNaN(cvv)) {
            showError('cvvInput', 'cvvError', 'Enter 3-digit CVV.');
            isLocalValid = false;
        }
        if (!isLocalValid) return false;

        try {
            const response = await fetch('check_card_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ card_number: cardNum, card_expiry: expiry, card_cvv: cvv })
            });
            const result = await response.json();

            if (result.status === 'error') {
                window.pendingCardErrors = result.codes; 
                document.getElementById('invalidCardModal').style.display = 'flex'; //
                return false; 
            }
        } catch (err) {
            console.error("Payment error:", err);
            return false;
        }
        
        // If card validation passes, proceed to submit
        submitFinalOrder();
    } 
    
    // ── Case B: Pay with Credits validation (using Modal instead of Ugly Alert) ──────
    else if (checked.value === 'credits') {
        if (userCredit < window.currentTotalAmount) {
            // Show "Insufficient balance" modal
            document.getElementById('insufficientMessage').innerHTML = 
                `You have: <b>RM ${userCredit.toFixed(2)}</b><br>Needed: <b>RM ${window.currentTotalAmount.toFixed(2)}</b>`;
            document.getElementById('insufficientCreditsModal').style.display = 'flex';
            return false;
        } else {
            // Show "Confirm payment" modal
            document.getElementById('confirmMessage').innerHTML = 
                `Deduct <b>RM ${window.currentTotalAmount.toFixed(2)}</b> from your credits?<br>New balance: <b>RM ${(userCredit - window.currentTotalAmount).toFixed(2)}</b>`;
            document.getElementById('confirmCreditsModal').style.display = 'flex';
            return false; // Wait for user confirmation in the Modal
        }
    } 
    
    // ── Case C: Other payment methods (TNG / FPX) ─────────────────────────
    else {
        submitFinalOrder();
    }
}

// Final submit function: runs after all checks pass
function submitFinalOrder() {
    if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }
    document.getElementById('cartDataInput').value = JSON.stringify(cart);
    document.getElementById('paymentForm').submit(); 
}

// Runs when the user clicks "Confirm" in the Credits confirmation pop-up.
function proceedWithCredits() {
    document.getElementById('confirmCreditsModal').style.display = 'none';
    submitFinalOrder();
}

// Close the invalid card modal and show specific red error messages
function closeCardModal() {
    document.getElementById('invalidCardModal').style.display = 'none';
    if (window.pendingCardErrors && window.pendingCardErrors.length > 0) {
        window.pendingCardErrors.forEach(code => {
            if (code === 'card') showError('cardNumberInput', 'cardError', 'Invalid card number.');
            else if (code === 'expiry') showError('expiryInput', 'expiryError', 'Incorrect expiry date.');
            else if (code === 'cvv') showError('cvvInput', 'cvvError', 'Incorrect CVV code.');
        });
        window.pendingCardErrors = [];
    }
}

// General: Close Credits-related modals
function closeCreditsModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

    function showError(inputId, errorId, msg) {
        const input = document.getElementById(inputId);
        const err   = document.getElementById(errorId);
        if (input) input.classList.add('input-error');
        if (err) {
            err.textContent = msg;
            err.style.display = 'block';
        }
    }

    function clearErrors() {
        document.querySelectorAll('.form-input').forEach(i => i.classList.remove('input-error'));
        document.querySelectorAll('.error-msg').forEach(e => e.style.display = 'none');
    }

    function closeAddressModal() {
        document.getElementById('addressRequiredModal').style.display = 'none';
    }

    function toggleModal(show) {
        document.getElementById('addrModal').classList.toggle('active', show);
    }

    function selectAddr(el, street, area, postcode) {
        document.querySelectorAll('.addr-option').forEach(item => item.classList.remove('selected'));
        el.classList.add('selected');

        const display = document.getElementById('addressLabel');
        display.style.opacity = '0';
        
        setTimeout(() => {
            display.textContent = `${street}, ${area}, Melaka, ${postcode}`;
            display.style.opacity = '1';

            document.getElementById('hiddenAddress').value   = street;
            document.getElementById('hiddenCity').value      = area;
            document.getElementById('hiddenPostcode').value  = postcode;
        }, 200);

        setTimeout(() => toggleModal(false), 400);
    }

    document.getElementById('cardNumberInput')?.addEventListener('input', e => {
        let v = e.target.value.replace(/\D/g, '');
        e.target.value = v.replace(/(\d{4})(?=\d)/g, '$1 ');
    });

    document.getElementById('expiryInput')?.addEventListener('input', e => {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length >= 2) {
            e.target.value = v.slice(0,2) + '/' + v.slice(2,4);
        }
    });

    function toggleCardFields(show) {
        document.getElementById('cardDetailsSection').style.display = show ? 'block' : 'none';
        
        document.querySelectorAll('.method-item').forEach(el => el.classList.remove('active'));
        const selected = document.querySelector('input[name="paymentMethod"]:checked');
        if (selected) {
            selected.parentElement.classList.add('active');
            document.getElementById('creditsHelp').style.display = 
                (selected.value === 'credits') ? 'block' : 'none';
        }
    }

    function renderSummary() {
    const container = document.getElementById('summaryItems');
    let subtotal = 0;
    let html = '';

    if (cart.length === 0) {
        container.innerHTML = '<p style="text-align:center; color:#999; padding:20px;">Your cart is empty.</p>';
        document.getElementById('totalPriceDisplay').textContent = 'RM 0.00';
        return;
    }

    // ── Key change: Use .reverse() so newest items appear FIRST (same as cart page) ──
    const displayCart = [...cart];

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
    document.getElementById('totalPriceDisplay').textContent = 
        `RM ${(subtotal + deliveryFee).toFixed(2)}`;
}
    </script>

    <link rel="stylesheet" href="footer.css">
</body>
</html>