<?php
// favorites.php
include 'db_connect.php';
include 'header.php';

if (!$isLoggedIn) {
    echo "<script>window.location.href='mainpage.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// ✨ 核心修改：加入 ORDER BY f.id DESC，确保最新收藏的排在最前
$sql = "SELECT p.* FROM products p 
        JOIN user_favorites f ON p.id = f.product_id 
        WHERE f.user_id = ? 
        ORDER BY f.id DESC"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$fav_products = [];
while($row = $result->fetch_assoc()) {
    $fav_products[] = $row;
}
?>

<link rel="stylesheet" href="menu.css">

<style>
/* 弹窗遮罩层 */
#quickViewModal {
    position: fixed !important; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); 
    z-index: 9999 !important; 
    display: none;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    width: 90%;
    max-width: 850px;
    border-radius: 15px;
    position: relative;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="container" style="padding: 50px 20px; min-height: 60vh;">
    <h1 style="color: #5a3921; margin-bottom: 30px; border-bottom: 2px solid #d4a76a; padding-bottom: 10px;">My Favorites ❤️</h1>
    
    <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
        <?php if (count($fav_products) > 0): ?>
            <?php foreach($fav_products as $p): ?>
                <div class="product-card" onclick="openQuickView(<?= $p['id'] ?>)" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); cursor: pointer; position: relative;">
                    <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h3 style="color: #5a3921; margin-bottom: 10px;"><?= $p['name'] ?></h3>
                        <p style="color: #d4a76a; font-weight: bold; font-size: 1.2rem;">RM <?= number_format($p['price'], 2) ?></p>
                        <p style="font-size: 0.9rem; color: #888; margin-top: 5px;"><?= $p['size_info'] ?></p>
                        
                        <button class="add-to-cart-btn" 
                                onclick="event.stopPropagation(); openQuickView(<?= $p['id'] ?>)"
                                style="width: 100%; margin-top: 15px; background: #d4a76a; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 0; grid-column: 1/-1;">
                <p style="font-size: 18px; color: #888;">You haven't added any favorites yet.</p>
                <a href="menu.php" style="display: inline-block; margin-top: 20px; color: #d4a76a; text-decoration: underline;">Explore our Menu</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal" id="quickViewModal">
    <div class="modal-content" id="quickViewContent"></div>
</div>

<div id="toast" class="toast" style="display:none;"></div>

<script>
const products = <?= json_encode($fav_products) ?>;
let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];

// 同步购物车到数据库
async function syncCartToDB() {
    try {
        await fetch('sync_cart.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart: cart })
        });
    } catch (e) {
        console.error("Sync error:", e);
    }
}

// 打开弹窗逻辑
// favorites.php 中的 openQuickView 函数
function openQuickView(productId) {
    const product = products.find(p => p.id == productId);
    if (!product) return;

    const modal = document.getElementById('quickViewModal');
    const content = document.getElementById('quickViewContent');

    content.innerHTML = `
        <button class="close-modal" onclick="closeModal()">×</button>
        <div style="display: flex; gap: 30px; padding: 30px;">
            <div style="flex: 1;">
                <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 10px;">
            </div>
            <div style="flex: 1;">
                <h2 style="margin-bottom: 15px; color: #5a3921;">${product.name}</h2>
                <p style="font-size: 24px; color: #d4a76a; font-weight: bold; margin-bottom: 15px;">RM ${parseFloat(product.price).toFixed(2)}</p>
                <div style="margin-bottom: 15px;">
                    <span style="color: #ffc107;">${'★'.repeat(Math.floor(product.rating || 0))}☆</span>
                    <span>${product.rating || ''} (${product.review_count || 0} reviews)</span>
                </div>
                <p style="margin-bottom: 20px; line-height: 1.6; color: #666;">${product.full_description || product.description || ''}</p>
                
                <p style="margin-bottom: 5px;"><strong>Ingredients:</strong> ${product.ingredients || ''}</p>
                
                <p style="margin-bottom: 5px;"><strong>Inch:</strong> ${product.inch || ''}</p>
                
                <p style="margin-bottom: 5px;"><strong>Allergens:</strong> ${product.allergens || ''}</p>
                
                <div style="display: flex; gap: 10px; margin-top: 25px; align-items: stretch;">
                    <button onclick="addToCart(${product.id})" style="background: #d4a76a; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; flex: 1; font-weight: bold;">
                        Add to Cart
                    </button>
                    <button onclick="toggleFavorite(${product.id}, this)" 
                            style="background: #f5f5f5; border: 1px solid #ddd; border-radius: 5px; cursor: pointer; width: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        ❤️
                    </button>
                </div>
            </div>
        </div>
    `;
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('quickViewModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('quickViewModal')) closeModal();
}

// 加入购物车核心逻辑
function addToCart(productId) {
    const product = products.find(p => p.id == productId);
    if (!product) return;

    // --- 核心逻辑：确保新加的在最上面 ---
    const existingIndex = cart.findIndex(i => i.id == productId);
    let finalQuantity = 1;

    if (existingIndex > -1) {
        // 如果已经收藏的商品已经在购物车，取出数量并删除旧位置
        finalQuantity = cart[existingIndex].quantity + 1;
        cart.splice(existingIndex, 1);
    }

    // 重新推入末尾，配合 cart.php 的 reverse() 就会出现在最顶端
    cart.push({ 
        id: product.id, 
        name: product.name, 
        price: parseFloat(product.price), 
        image: product.image, 
        quantity: finalQuantity 
    });
    // ----------------------------------
    
    localStorage.setItem('bakeryCart', JSON.stringify(cart));
    syncCartToDB(); // 立即同步到数据库
    
    showToast(product.name + " added to cart!");
    updateHeaderCount(); 
    closeModal(); 
}

function updateHeaderCount() {
    const countEl = document.querySelector('.cart-count');
    if (countEl) {
        const total = cart.reduce((sum, i) => sum + i.quantity, 0);
        countEl.textContent = total;
    }
}

async function toggleFavorite(id, btn) {
    try {
        const response = await fetch('toggle_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: id })
        });
        const result = await response.json();
        if (result.status === 'success' && result.action === 'removed') {
            showToast('Removed from favorites');
            setTimeout(() => location.reload(), 800);
        }
    } catch (e) { console.error(e); }
}

function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 2500);
}
</script>

<?php include 'footer.php'; ?>