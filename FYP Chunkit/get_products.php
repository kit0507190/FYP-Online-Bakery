<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include __DIR__ . '/db_connect.php';  // Now $pdo is available

if (!isset($pdo)) {
    echo json_encode(['error' => 'PDO connection not available']);
    exit;
}

try {
    $sql = "
        SELECT 
            p.id,
            p.name,
            p.price,
            p.category_id,
            p.subcategory_id,
            p.stock,
            p.description,
            p.ingredients,
            p.size,
            p.rating,
            p.review_count,
            p.sold_count,
            p.size_info,
            p.image,
            p.created_at,
            LOWER(c.name) AS category,
            s.name AS subcategory_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        ORDER BY p.id
    ";

    $stmt = $pdo->query($sql);
    $products = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Skip cookies if needed
        if (stripos($row['category'] ?? '', 'cookie') !== false) {
            continue;
        }

        // Generate subcategory slug from name
        $subName = $row['subcategory_name'] ?? '';
        $subSlug = strtolower($subName);
        $subSlug = preg_replace('/\s*&?\s*/', ' ', $subSlug);
        $subSlug = preg_replace('/\s+/', '-', $subSlug);
        $subSlug = preg_replace('/[^a-z0-9-]/', '', $subSlug);
        $subSlug = trim($subSlug, '-');

        // Your fix map
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
        $subSlug = $fix[$subSlug] ?? $subSlug;

        $products[] = [
            "id"           => (int)$row['id'],
            "name"         => $row['name'],
            "price"        => (float)$row['price'],
            "category"     => $row['category'] ?? 'all',
            "subcategory"  => $subSlug,
            "image"        => $row['image'] ?? '',
            "description"  => $row['description'] ?? '',
            "ingredients"  => $row['ingredients'] ?? '',
            "fullSize"     => $row['size'] ?? '',
            "shortSize"    => $row['size_info'] ?? '',
            "rating"       => (float)$row['rating'],
            "reviewCount"  => (int)$row['review_count'],
            "soldCount"    => (int)$row['sold_count'],
            "tags"         => []
        ];
    }

    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}