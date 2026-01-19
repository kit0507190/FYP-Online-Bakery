<?php
// get_products.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include 'db_connect.php'; 

// 查询产品并关联分类名
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id";

$result = $conn->query($sql);
$products = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // 1. 过滤掉 Cookies
        if (stripos($row['category_name'], 'Cookie') !== false) continue;

        // 2. 数据清理：处理子分类中多余的双引号
        $cleanSub = trim($row['subcategory'], "'\"");

        // 3. 映射分类名：将 "Cakes" 转为 "cake"，"Bread" 转为 "bread"
        $catMapping = [
            'Cakes' => 'cake',
            'Bread' => 'bread',
            'Pastries' => 'pastry'
        ];
        $displayCat = isset($catMapping[$row['category_name']]) ? $catMapping[$row['category_name']] : strtolower($row['category_name']);

        $products[] = [
            "id" => (int)$row['id'],
            "name" => $row['name'],
            "price" => (float)$row['price'],
            "category" => $displayCat,
            "subcategory" => $cleanSub,
            "image" => $row['image'],
            "description" => $row['description'],
            "fullDescription" => $row['full_description'],
            "ingredients" => $row['ingredients'],
            "fullSize" => $row['size'],
            "allergens" => $row['allergens'],
            "rating" => (float)$row['rating'],
            "reviewCount" => (int)$row['review_count'],
            "soldCount" => (int)$row['sold_count'], // 👈 新增：销量
            "tags" => !empty($row['tags']) ? explode(',', $row['tags']) : [],
            "shortSize" => $row['size_info'] // 映射 size_info 为前端用的 size
        ];
    }
}
echo json_encode($products);
$conn->close();
?>