<?php
// controllers/api/products/bulk.php

require_once __DIR__ . '/../../../models/ProductModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$ids = $input['ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => []]);
    exit;
}

// Ensure all IDs are integers
$ids = array_map('intval', $ids);

$productModel = new ProductModel($conn_products);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $conn_products->prepare("SELECT id, name, price, stock, image, store_id FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map products by their ID for easy lookup
$mapped = [];
foreach ($products as $p) {
    $mapped[$p['id']] = $p;
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "data" => $mapped
]);
