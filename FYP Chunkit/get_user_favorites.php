<?php
include 'db_connect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$uid = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name,
            p.price,
            p.description,
            p.full_description,
            p.ingredients,
            p.rating,
            p.review_count AS reviewCount,
            p.sold_count AS soldCount,
            p.size_info AS size,
            CASE 
                WHEN p.image IS NULL OR p.image = '' THEN 'images/placeholder.jpg'
                WHEN p.image LIKE 'http%' THEN p.image
                ELSE CONCAT('product_images/', p.image)
            END AS image,
            LOWER(c.name) AS category,
            s.name AS subcategory_name
        FROM user_favorites f
        JOIN products p ON f.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        WHERE f.user_id = ?
        ORDER BY f.id DESC
    ");
    
    $stmt->execute([$uid]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Optional: clean up subcategory slugs if your JS expects the same format as get_products.php
    foreach ($favorites as &$item) {
        $subName = $item['subcategory_name'] ?? '';
        $subSlug = strtolower($subName);
        $subSlug = preg_replace('/\s*&?\s*/', ' ', $subSlug);
        $subSlug = preg_replace('/\s+/', '-', $subSlug);
        $subSlug = preg_replace('/[^a-z0-9-]/', '', $subSlug);
        $subSlug = trim($subSlug, '-');

        $fix = [
            'cute-mini-cake'          => 'mini',
            'the-animal-series'       => 'animal',
            'full-moon-gift-packages' => 'full-moon',
            'wedding-gift-packages'   => 'wedding',
            'fresh-cream-cake'        => 'fresh-cream',
            'fondant-cake-design'     => 'fondant',
            'puff-pastry'             => 'puff',
            'whole-grain-bread'       => 'wholegrain',
            'danish-pastries'         => 'danish',
            'artisan-bread'           => 'artisan',
        ];
        $item['subcategory'] = $fix[$subSlug] ?? $subSlug;
        unset($item['subcategory_name']); // clean up
    }

    echo json_encode($favorites);

} catch (PDOException $e) {
    error_log("Favorites fetch error: " . $e->getMessage());
    echo json_encode([]);
}
?>