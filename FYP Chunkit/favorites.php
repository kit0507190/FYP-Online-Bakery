<?php
// favorites.php
include 'db_connect.php';
include 'header.php';

if (!$isLoggedIn) {
    echo "<script>window.location.href='mainpage.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// ✨ 核心逻辑保持不变：确保最新收藏排在最前
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
    $row['fullSize'] = $row['size']; 
    $fav_products[] = $row;
}
?>

<link rel="stylesheet" href="menu.css">

<style>
/* --- 1. UI 视觉优化 --- */
.product-card {
    background: white; 
    border-radius: 15px; 
    overflow: hidden; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
    cursor: pointer; 
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease; 
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 25px rgba(90, 57, 33, 0.15);
}

.quick-remove {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.9);
    color: #e74c3c;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    z-index: 5;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.quick-remove:hover {
    background: #e74c3c;
    color: white;
    transform: scale(1.1);
}

/* 弹窗遮罩层优化 */
#quickViewModal {
    position: fixed !important; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); 
    backdrop-filter: blur(5px); 
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
    animation: modalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
}

@keyframes modalPop {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@media (max-width: 768px) {
    #quickViewContent > div {
        flex-direction: column !important;
        padding: 15px !important;
    }
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }
}

/* Toast 样式优化 */
.toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: #5a3921;
    color: white;
    padding: 12px 30px;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 100000 !important; /* 确保在弹窗之上 */
}

/* --- 🌟 收藏页标题专属：心跳动效 --- */
.heart-pulse {
    animation: heartbeat 1.5s ease-in-out infinite;
    filter: drop-shadow(0 0 5px rgba(231, 76, 60, 0.3));
}

@keyframes heartbeat {
    0% { transform: scale(1); }
    15% { transform: scale(1.25); }
    30% { transform: scale(1); }
    45% { transform: scale(1.25); }
    100% { transform: scale(1); }
}

/* 确保标题下方的原本边框消失 */
.menu-header-box h1 {
    border-bottom: none !important;
}
</style>

<div class="container" style="padding: 20px 20px; min-height: 60vh;">
    
    <div class="menu-header-box" style="text-align: center; margin-top: 0; padding: 10px 20px; margin-bottom: 40px; background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);">
        <h1 style="color: #5a3921; margin-bottom: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 15px; font-size: 2.6rem; font-weight: 800; border: none; padding-bottom: 0;">
            My Favorites 
            <span class="heart-pulse" style="font-size: 2rem; display: inline-block;">❤️</span>
        </h1>
        
        <p style="color: #8e735b; font-size: 1.1rem; margin-top: 10px; font-family: 'Georgia', serif; font-style: italic;">
            <?php $count = count($fav_products); ?>
            You have curated <strong><?= $count ?></strong> precious <?= $count === 1 ? 'treat' : 'treats' ?>.
        </p>
        
        <hr style="width: 60px; border: none; border-top: 3px solid #d4a76a; margin: 20px auto 0; border-radius: 10px;">
    </div>
    
    <div class="products-grid" id="favoritesGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
        <?php if (count($fav_products) > 0): ?>
            <?php foreach($fav_products as $p): ?>
                <div class="product-card" id="card-<?= $p['id'] ?>" onclick="openQuickView(<?= $p['id'] ?>)">
                    
                    <button class="quick-remove" title="Remove" onclick="event.stopPropagation(); toggleFavorite(<?= $p['id'] ?>, this)">
                        ✕
                    </button>

                    <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h3 style="color: #5a3921; margin-bottom: 10px;"><?= $p['name'] ?></h3>
                        <p style="color: #d4a76a; font-weight: bold; font-size: 1.2rem;">RM <?= number_format($p['price'], 2) ?></p>
                        <p style="font-size: 0.9rem; color: #888; margin-top: 5px;"><?= $p['size_info'] ?></p>
                        
                        <button class="add-to-cart-btn" 
                                onclick="event.stopPropagation(); openQuickView(<?= $p['id'] ?>)"
                                style="width: 100%; margin-top: 15px; background: #d4a76a; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: background 0.2s;">
                            View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div id="emptyMessage" style="text-align: center; padding: 50px 0; grid-column: 1/-1;">
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

function openQuickView(productId) {
    const product = products.find(p => p.id == productId);
    if (!product) return;

    const modal = document.getElementById('quickViewModal');
    const content = document.getElementById('quickViewContent');

    content.innerHTML = `
        <button class="close-modal" onclick="closeModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 28px; cursor: pointer; color: #888; z-index: 10;">×</button>
        
        <div style="display: flex; gap: 40px; padding: 40px; align-items: flex-start;">
            <div style="flex: 1.1; position: sticky; top: 0;">
                <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); object-fit: cover;">
            </div>

            <div style="flex: 1; display: flex; flex-direction: column;">
                <h2 style="margin-bottom: 10px; color: #5a3921; font-size: 1.8rem; line-height: 1.2;">${product.name}</h2>
                
                <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px; font-size: 0.95rem;">
                    <span style="color: #ffc107; font-size: 1.1rem;">${'★'.repeat(Math.floor(product.rating || 0))}☆</span>
                    <span style="color: #5a3921; font-weight: 600;">${product.rating || '0.0'}</span>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #888;">${product.review_count || 0} Reviews</span>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #d4a76a; font-weight: 600;">${product.sold_count || 0} Sold</span>
                </div>

                <p style="font-size: 28px; color: #d4a76a; font-weight: 700; margin-bottom: 25px;">RM ${parseFloat(product.price).toFixed(2)}</p>
                
                <div style="border-top: 1px solid #f0f0f0; padding-top: 20px; margin-bottom: 25px;">
                    <p style="line-height: 1.8; color: #666; font-size: 1rem;">${product.full_description || product.description || ''}</p>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px;">
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Ingredients</span>
                        <span style="flex: 1; color: #555; font-size: 0.9rem;">${product.ingredients || 'Natural ingredients'}</span>
                    </div>
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Size</span>
                        <span style="flex: 1; color: #555; font-size: 0.9rem;">${product.fullSize || 'Standard'}</span>
                    </div>
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Allergens</span>
                        <span style="flex: 1; color: #e57373; font-size: 0.9rem; font-weight: 500;">${product.allergens || 'None'}</span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: auto;">
                    <button onclick="addToCart(${product.id})" 
                            style="background: #d4a76a; color: white; border: none; padding: 15px 30px; border-radius: 10px; cursor: pointer; flex: 1; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(212, 167, 106, 0.3);">
                        Add to Cart
                    </button>
                    <button onclick="toggleFavorite(${product.id}, this)" 
                            style="background: #fff; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; width: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; transition: all 0.2s;">
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

function addToCart(productId) {
    const product = products.find(p => p.id == productId);
    if (!product) return;

    const existingIndex = cart.findIndex(i => i.id == productId);
    let finalQuantity = 1;

    if (existingIndex > -1) {
        finalQuantity = cart[existingIndex].quantity + 1;
        cart.splice(existingIndex, 1);
    }

    cart.push({ 
        id: product.id, 
        name: product.name, 
        price: parseFloat(product.price), 
        image: product.image, 
        quantity: finalQuantity 
    });
    
    localStorage.setItem('bakeryCart', JSON.stringify(cart));
    syncCartToDB(); 
    
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
            closeModal();

            const card = document.getElementById('card-' + id);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    card.remove();
                    const remainingCards = document.querySelectorAll('.product-card');
                    if (remainingCards.length === 0) {
                        const grid = document.getElementById('favoritesGrid');
                        grid.innerHTML = `
                            <div id="emptyMessage" style="text-align: center; padding: 50px 0; grid-column: 1/-1;">
                                <p style="font-size: 18px; color: #888;">You haven't added any favorites yet.</p>
                                <a href="menu.php" style="display: inline-block; margin-top: 20px; color: #d4a76a; text-decoration: underline;">Explore our Menu</a>
                            </div>`;
                    }
                }, 300);
            }
        }
    } catch (e) { 
        console.error(e);
        location.reload(); 
    }
}

function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.style.display = 'block';
    toast.style.animation = 'fadeIn 0.3s';
    setTimeout(() => { 
        toast.style.display = 'none'; 
    }, 2500);
}
</script>

<?php include 'footer.php'; ?>