<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details - Bakery House</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        .detail-container { display: flex; gap: 50px; padding: 50px 10%; background: #f9f9f9; }
        .detail-left { flex: 1; }
        .detail-left img { width: 100%; border-radius: 4px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .detail-right { flex: 1; color: #1a1a1a; }
        .p-name { font-family: serif; font-size: 36px; margin-bottom: 10px; text-transform: uppercase; }
        .p-price { font-size: 20px; margin-bottom: 30px; }
        .option-label { font-size: 12px; color: #666; margin: 15px 0 5px; text-transform: uppercase; }
        .size-tag { display: inline-block; padding: 8px 20px; border: 1px solid #000; background: #1a1a1a; color: #fff; font-size: 12px; }
        select, input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; outline: none; }
        .qty-box { display: flex; align-items: center; border: 1px solid #ddd; width: fit-content; margin-bottom: 20px; }
        .qty-box button { padding: 10px 15px; border: none; background: none; cursor: pointer; }
        .qty-box input { width: 40px; text-align: center; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; margin: 0; }
        .add-btn { width: 100%; padding: 15px; background: #1a1a1a; color: white; border: none; font-weight: bold; cursor: pointer; text-transform: uppercase; }
        .p-desc { margin-top: 30px; font-size: 14px; color: #444; line-height: 1.6; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="detail-container">
    <div class="detail-left">
        <img id="p-image" src="" alt="">
    </div>

    <div class="detail-right">
        <h1 id="p-name" class="p-name">Loading...</h1>
        <div id="p-price" class="p-price">RM 0.00</div>

        <div class="option-label">Size</div>
        <div id="p-size" class="size-tag">-</div>

        <div class="option-label">Small Candles</div>
        <select><option>- select an option -</option><option>1</option><option>2</option></select>

        <div class="option-label">Big Candles</div>
        <select><option>- select an option -</option><option>1</option><option>2</option></select>

        <div class="option-label">Message On Cake</div>
        <input type="text" placeholder="*Please leave it blank if you don't want any message">

        <div class="option-label">Quantity</div>
        <div class="qty-box">
            <button onclick="changeQty(-1)">-</button>
            <input type="number" id="qty-input" value="1">
            <button onclick="changeQty(1)">+</button>
        </div>

        <button class="add-btn" onclick="handleAddToCart()">Add to Cart</button>

        <div id="p-full-desc" class="p-desc"></div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="menu.js"></script>

<script>
    // 1. 获取 URL 中的 ID
    const urlParams = new URLSearchParams(window.location.search);
    const productId = parseInt(urlParams.get('id'));

    // 2. 从 menu.js 的 products 数组中查找对应产品
    // 注意：menu.js 必须在本项目 script 之前引入
    const product = products.find(p => p.id === productId);

    if (product) {
        // 3. 将数据显示在页面上
        document.title = product.name + " - Bakery House";
        document.getElementById('p-image').src = product.image;
        document.getElementById('p-name').textContent = product.name;
        document.getElementById('p-price').textContent = "RM " + product.price.toFixed(2);
        document.getElementById('p-size').textContent = product.size || "STANDARD";
        document.getElementById('p-full-desc').textContent = product.fullDescription || product.description;
    } else {
        document.body.innerHTML = "<h1>Product Not Found</h1><a href='menu.php'>Back to Menu</a>";
    }

    // 数量控制
    function changeQty(n) {
        let input = document.getElementById('qty-input');
        let val = parseInt(input.value) + n;
        if (val >= 1) input.value = val;
    }

    // 复用你的购物车逻辑
    function handleAddToCart() {
        const qty = parseInt(document.getElementById('qty-input').value);
        // 这里可以直接调用 menu.js 里已经写好的 addToCart 函数
        addToCart(productId, qty);
    }
</script>

</body>
</html>