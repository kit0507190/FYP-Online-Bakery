<?php
include 'config.php';

$orderSuccess = false;
$customerName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['fullName'] ?? '';
    $email       = $_POST['email'] ?? '';
    $phone       = $_POST['phone'] ?? '';
    $address     = $_POST['address'] ?? '';
    $city        = $_POST['city'] ?? '';
    $postcode    = $_POST['postcode'] ?? '';
    $cartDataRaw = $_POST['cart_data'] ?? '[]';

    $cartData = json_decode($cartDataRaw, true);

    // 计算总价
    $total = 0;
    if (is_array($cartData)) {
        foreach ($cartData as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }

    // === 1️⃣ 写入 orders 表 ===
    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (customer_name, customer_email, customer_phone, delivery_address, city, postcode, total, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->execute([
        $customerName,
        $email,
        $phone,
        $address,
        $city,
        $postcode,
        $total
    ]);

    // 拿到刚刚的 order id
    $orderId = $pdo->lastInsertId();

    // === 2️⃣ 写入 orders_detail 表 ===
    $stmtDetail = $pdo->prepare("
        INSERT INTO orders_detail
        (order_id, product_id, product_name, price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($cartData as $item) {
        $subtotal = $item['price'] * $item['quantity'];

        $stmtDetail->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $subtotal
        ]);
    }

    $orderSuccess = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - BakeryHouse</title>
    <link rel="stylesheet" href="payment.css">
</head>
<body>
    <!-- Shared header -->
    <?php include 'header.php'; ?>
    <!-- End shared header -->

    <!-- Payment Content -->
    <div class="container">
        <div class="payment-content">
            <h1 class="payment-title">Checkout</h1>
            
            <div class="payment-container">
                <!-- Order Summary -->
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="order-items" id="orderItems">
                        <!-- Order items will be dynamically loaded -->
                    </div>
                    <div class="order-total">
                        <span>Total:</span>
                        <span id="orderTotal">RM 0.00</span>
                    </div>
                </div>
                
                <!-- Payment Form -->
                <div class="payment-form-container">
                    <h2>Payment Details</h2>
                    <!-- 这里和 html 一样，只是加了 name、method、action、hidden input -->
                    <form id="paymentForm" method="post" action="payment.php">
                        <div class="form-group">
                            <label class="form-label" for="fullName">Full Name</label>
                            <input type="text" name="fullName" id="fullName" class="form-input" placeholder="Enter your full name as per IC">
                            <div class="error-message" id="fullNameError">
                                • Please provide your complete name (first and last name)
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-input" placeholder="your.email@example.com">
                            <div class="error-message" id="emailError">
                                • Please enter a valid email address (e.g., name@example.com)
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-input" placeholder="e.g., 012-345 6789 or +6012-345 6789">
                            <div class="error-message" id="phoneError">
                                • Please enter a valid Malaysian phone number<br>
                                • Format: 01X-XXX XXXX or +601X-XXX XXXX<br>
                                • Example: 012-345 6789 or +6012-345 6789
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="address">Delivery Address</label>
                            <textarea name="address" id="address" class="form-input" rows="3" placeholder="House number, Street name, Area"></textarea>
                            <div class="error-message" id="addressError">
                                • Please provide your complete delivery address<br>
                                • Include: House number, Street name, Area/Location
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="city">City</label>
                                <input type="text" name="city" id="city" class="form-input" placeholder="e.g., Kuala Lumpur">
                                <div class="error-message" id="cityError">
                                    • Please specify your city
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="postcode">Postcode</label>
                                <input type="text" name="postcode" id="postcode" class="form-input" placeholder="e.g., 50000">
                                <div class="error-message" id="postcodeError">
                                    • Please enter a valid 5-digit postcode<br>
                                    • Example: 50000 for Kuala Lumpur
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Payment Method</label>
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <input type="radio" id="creditCard" name="paymentMethod" value="creditCard">
                                    <label for="creditCard">Credit Card</label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="debitCard" name="paymentMethod" value="debitCard">
                                    <label for="debitCard">Debit Card</label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="eWallet" name="paymentMethod" value="eWallet">
                                    <label for="eWallet">E-Wallet (Touch 'n Go, GrabPay, etc.)</label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="fpx" name="paymentMethod" value="fpx">
                                    <label for="fpx">FPX Online Banking</label>
                                </div>
                            </div>
                            <div class="error-message" id="paymentMethodError">
                                • Please select your preferred payment method
                            </div>
                        </div>
                        
                        <!-- Credit Card Details -->
                        <div class="payment-details" id="cardDetails">
                            <div class="form-group">
                                <label class="form-label" for="cardNumber">Card Number</label>
                                <input type="text" id="cardNumber" class="form-input" placeholder="1234 5678 9012 3456">
                                <div class="error-message" id="cardNumberError">
                                    • Please enter a valid 16-digit card number<br>
                                    • Format: XXXX XXXX XXXX XXXX
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="cardName">Name on Card</label>
                                <input type="text" id="cardName" class="form-input" placeholder="As shown on your card">
                                <div class="error-message" id="cardNameError">
                                    • Please enter the name exactly as shown on your card
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="expiryDate">Expiry Date</label>
                                    <input type="text" id="expiryDate" class="form-input" placeholder="MM/YY">
                                    <div class="error-message" id="expiryDateError">
                                        • Please enter a valid expiry date<br>
                                        • Format: MM/YY (e.g., 12/25 for December 2025)
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="cvv">CVV</label>
                                    <input type="text" id="cvv" class="form-input" placeholder="123">
                                    <div class="error-message" id="cvvError">
                                        • Please enter the 3-digit security code<br>
                                        • Located on the back of your card
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 把购物车 JSON 放这里传去 PHP -->
                        <input type="hidden" name="cart_data" id="cartDataInput">
                        
                        <button type="submit" class="place-order-btn" id="placeOrderBtn">
                            <span id="btnText">Place Order</span>
                            <div class="loading-spinner" id="loadingSpinner"></div>
                        </button>
                    </form>
                    
                    <div class="back-link">
                        <a href="cart.html">← Back to Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <!-- Confirmation Modal（前端 design 保留，但现在真实下单后会跳去首页） -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <h2>Order Confirmed!</h2>
            <p>Thank you for your order. Your payment has been processed successfully.</p>
            <p>Your order will be delivered within 2-3 business days.</p>
            <button class="modal-btn" id="continueShopping">Continue Shopping</button>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Shopping cart
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];

        // DOM elements
        const orderItems = document.getElementById('orderItems');
        const orderTotal = document.getElementById('orderTotal');
        const cartCount = document.querySelector('.cart-count');
        const paymentForm = document.getElementById('paymentForm');
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        const cardDetails = document.getElementById('cardDetails');
        const confirmationModal = document.getElementById('confirmationModal');
        const continueShoppingBtn = document.getElementById('continueShopping');
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const toast = document.getElementById('toast');
        const cartDataInput = document.getElementById('cartDataInput');

        // Load order summary
        function loadOrderSummary() {
            if (cart.length === 0) {
                orderItems.innerHTML = `
                    <div class="empty-cart-message">
                        <p>Your cart is empty</p>
                        <a href="menu.html">Browse our menu</a>
                    </div>
                `;
                orderTotal.textContent = 'RM 0.00';
                placeOrderBtn.disabled = true;
                return;
            }
            
            let itemsHTML = '';
            let subtotal = 0;
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                itemsHTML += `
                    <div class="order-item">
                        <span>${item.name} x ${item.quantity}</span>
                        <span>RM ${itemTotal.toFixed(2)}</span>
                    </div>
                `;
            });
            
            // Add delivery fee
            const deliveryFee = 5.00;
            const total = subtotal + deliveryFee;
            
            itemsHTML += `
                <div class="order-item">
                    <span>Delivery Fee</span>
                    <span>RM ${deliveryFee.toFixed(2)}</span>
                </div>
            `;
            
            orderItems.innerHTML = itemsHTML;
            orderTotal.textContent = `RM ${total.toFixed(2)}`;
            placeOrderBtn.disabled = false;
        }

        // Show error message
        function showError(inputId, message) {
            const input = document.getElementById(inputId);
            const errorElement = document.getElementById(inputId + 'Error');
            
            input.classList.add('error');
            errorElement.innerHTML = message;
            errorElement.style.display = 'block';
        }

        // Hide error message
        function hideError(inputId) {
            const input = document.getElementById(inputId);
            const errorElement = document.getElementById(inputId + 'Error');
            
            input.classList.remove('error');
            errorElement.style.display = 'none';
        }

        // Validate email format
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Validate phone number (Malaysian format)
        function isValidPhone(phone) {
            const phoneRegex = /^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/;
            return phoneRegex.test(phone.replace(/\s/g, ''));
        }

        // Validate card number (simple Luhn check)
        function isValidCardNumber(cardNumber) {
            const cleaned = cardNumber.replace(/\s/g, '');
            if (!/^\d+$/.test(cleaned) || cleaned.length !== 16) return false;
            
            let sum = 0;
            let isEven = false;
            
            for (let i = cleaned.length - 1; i >= 0; i--) {
                let digit = parseInt(cleaned.charAt(i), 10);
                
                if (isEven) {
                    digit *= 2;
                    if (digit > 9) digit -= 9;
                }
                
                sum += digit;
                isEven = !isEven;
            }
            
            return sum % 10 === 0;
        }

        // Validate expiry date
        function isValidExpiryDate(expiryDate) {
            const regex = /^(0[1-9]|1[0-2])\/?([0-9]{2})$/;
            if (!regex.test(expiryDate)) return false;
            
            const [month, year] = expiryDate.split('/');
            const expiry = new Date(2000 + parseInt(year), parseInt(month) - 1);
            const now = new Date();
            
            return expiry > now;
        }

        // Validate CVV
        function isValidCVV(cvv) {
            return /^\d{3,4}$/.test(cvv);
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            toast.textContent = message;
            toast.className = `toast ${type}`;
            toast.style.display = 'block';
            
            setTimeout(() => {
                toast.style.display = 'none';
            }, 4000);
        }

        // Validate individual field
        function validateField(fieldId) {
            const value = document.getElementById(fieldId).value.trim();
            
            switch(fieldId) {
                case 'fullName':
                    if (!value) {
                        showError(fieldId, '• Please provide your complete name (first and last name)');
                        return false;
                    } else if (value.split(' ').length < 2) {
                        showError(fieldId, '• Please enter both your first and last name');
                        return false;
                    }
                    break;
                    
                case 'email':
                    if (!value) {
                        showError(fieldId, '• Please enter your email address');
                        return false;
                    } else if (!isValidEmail(value)) {
                        showError(fieldId, '• Please enter a valid email address (e.g., name@example.com)');
                        return false;
                    }
                    break;
                    
                case 'phone':
                    if (!value) {
                        showError(fieldId, '• Please enter your phone number');
                        return false;
                    } else if (!isValidPhone(value)) {
                        showError(fieldId, 
                            '• Please enter a valid Malaysian phone number<br>' +
                            '• Format: 01X-XXX XXXX or +601X-XXX XXXX<br>' +
                            '• Example: 012-345 6789 or +6012-345 6789'
                        );
                        return false;
                    }
                    break;
                    
                case 'address':
                    if (!value) {
                        showError(fieldId, 
                            '• Please provide your complete delivery address<br>' +
                            '• Include: House number, Street name, Area/Location'
                        );
                        return false;
                    } else if (value.length < 10) {
                        showError(fieldId, '• Please provide a more detailed address for accurate delivery');
                        return false;
                    }
                    break;
                    
                case 'city':
                    if (!value) {
                        showError(fieldId, '• Please specify your city or area');
                        return false;
                    }
                    break;
                    
                case 'postcode':
                    if (!value) {
                        showError(fieldId, '• Please enter your postcode');
                        return false;
                    } else if (!/^\d{5}$/.test(value)) {
                        showError(fieldId, 
                            '• Please enter a valid 5-digit postcode<br>' +
                            '• Example: 50000 for Kuala Lumpur'
                        );
                        return false;
                    }
                    break;
                    
                case 'cardNumber':
                    if (!value) {
                        showError(fieldId, '• Please enter your card number');
                        return false;
                    } else if (!isValidCardNumber(value)) {
                        showError(fieldId, 
                            '• Please enter a valid 16-digit card number<br>' +
                            '• Format: XXXX XXXX XXXX XXXX'
                        );
                        return false;
                    }
                    break;
                    
                case 'cardName':
                    if (!value) {
                        showError(fieldId, '• Please enter the name exactly as shown on your card');
                        return false;
                    }
                    break;
                    
                case 'expiryDate':
                    if (!value) {
                        showError(fieldId, '• Please enter your card expiry date');
                        return false;
                    } else if (!isValidExpiryDate(value)) {
                        showError(fieldId, 
                            '• Please enter a valid expiry date<br>' +
                            '• Format: MM/YY (e.g., 12/25 for December 2025)'
                        );
                        return false;
                    }
                    break;
                    
                case 'cvv':
                    if (!value) {
                        showError(fieldId, '• Please enter the 3-digit security code (CVV)');
                        return false;
                    } else if (!isValidCVV(value)) {
                        showError(fieldId, 
                            '• Please enter a valid 3-digit CVV<br>' +
                            '• Located on the back of your card'
                        );
                        return false;
                    }
                    break;
            }
            
            hideError(fieldId);
            return true;
        }

        // Validate form
        function validateForm() {
            let isValid = true;
            
            // Check required fields
            const requiredFields = [
                'fullName', 'email', 'phone', 'address', 'city', 'postcode'
            ];
            
            requiredFields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });
            
            // Check payment method （修复 JS 报错）
const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
if (!paymentMethod) {
    document.getElementById('paymentMethodError').style.display = 'block';
    isValid = false;
} else {
    document.getElementById('paymentMethodError').style.display = 'none';
}

            
            // Additional validation for credit card if selected
            if (paymentMethod && (paymentMethod.value === 'creditCard' || paymentMethod.value === 'debitCard')) {
                const cardFields = ['cardNumber', 'cardName', 'expiryDate', 'cvv'];
                cardFields.forEach(field => {
                    if (!validateField(field)) {
                        isValid = false;
                    }
                });
            }
            
            if (!isValid) {
                showToast('Please fix the errors in the form', 'error');
            }
            
            return isValid;
        }

        // Update cart count
        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
        }

        // Setup event listeners
        function setupEventListeners() {
            // Payment method selection
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Update selected state
                    document.querySelectorAll('.payment-method').forEach(pm => {
                        pm.classList.remove('selected');
                    });
                    this.closest('.payment-method').classList.add('selected');
                    
                    if (this.value === 'creditCard' || this.value === 'debitCard') {
                        cardDetails.classList.add('active');
                    } else {
                        cardDetails.classList.remove('active');
                    }
                    document.getElementById('paymentMethodError').style.display = 'none';

                });
            });
            
            // Real-time validation for form fields
            const formInputs = document.querySelectorAll('.form-input');
            formInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this.id);
                });
                
                input.addEventListener('input', function() {
                    hideError(this.id);
                });
            });
            
            // Payment form submission => 改成真正 POST 去 PHP
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateForm()) {
                    // 把购物车 JSON 放进 hidden input
                    cartDataInput.value = JSON.stringify(cart);

                    // 显示一点 loading（可要可不要）
                    btnText.style.display = 'none';
                    loadingSpinner.style.display = 'block';
                    placeOrderBtn.disabled = true;

                    // 提交表单到 PHP（服务器处理 DB，然后再 redirect）
                    paymentForm.submit();
                }
            });
            
            // Continue shopping button（前端模拟用的，保留 design）
            if (continueShoppingBtn) {
                continueShoppingBtn.addEventListener('click', function() {
                    window.location.href = 'menu.html';
                });
            }
            
            // Cart icon click
            document.querySelector('.cart-icon').addEventListener('click', function() {
                window.location.href = 'cart.html';
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            loadOrderSummary();
            setupEventListeners();
            updateCartCount();
        });
    </script>

<?php if ($orderSuccess): ?>
    <script>
        // 下单成功后：清空 localStorage + 弹出提示 + 回首页
        localStorage.removeItem('bakeryCart');
        alert('Order placed successfully! 🎉 Thank you, <?php echo addslashes($customerName); ?>!');
        window.location.href = 'index.html';
    </script>
<?php endif; ?>

</body>
</html>
