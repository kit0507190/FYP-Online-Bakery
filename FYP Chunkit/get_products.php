<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include 'db_connect.php'; 

// Explicitly select only existing columns (safe even if you drop more later)
$sql = "SELECT 
            p.id,
            p.name,
            p.price,
            p.category_id,
            p.subcategory,
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
            c.name AS category_name
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id";

$result = $conn->query($sql);
$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        // Skip Cookies category completely
        if ($row['category_name'] && stripos($row['category_name'], 'Cookie') !== false) {
            continue;
        }

        // Clean subcategory: remove surrounding quotes like "5 inch" → 5 inch
        $cleanSub = trim($row['subcategory'] ?? '', "\"'");

        // Map display category name to lowercase slug (cake, bread, pastry)
        $catMapping = [
            'Cakes'    => 'cake',
            'Bread'    => 'bread',
            'Pastries' => 'pastry'
        ];
        $categorySlug = $catMapping[$row['category_name']] ?? strtolower($row['category_name'] ?? 'all');

        $products[] = [
            "id"           => (int)$row['id'],
            "name"         => $row['name'],
            "price"        => (float)$row['price'],
            "category"     => $categorySlug,
            "subcategory"  => $cleanSub,
            "image"        => $row['image'] ?? '',
            "description"  => $row['description'] ?? '',
            "ingredients"  => $row['ingredients'] ?? '',
            "fullSize"     => $row['size'] ?? '',
            "shortSize"    => $row['size_info'] ?? '',
            "rating"       => (float)$row['rating'],
            "reviewCount"  => (int)$row['review_count'],
            "soldCount"    => (int)$row['sold_count'],
            "tags"         => [] // you removed tags, keep empty
        ];
    }
}

// Always return valid JSON (even if empty)
echo json_encode($products);

$conn->close();
?>