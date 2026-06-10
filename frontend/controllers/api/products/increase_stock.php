<?php
// controllers/api/products/increase_stock.php

require_once __DIR__ . '/../../../models/ProductModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$productId = (int)($input['product_id'] ?? 0);
$quantity = (int)($input['quantity'] ?? 0);

if ($productId <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid parameters."]);
    exit;
}

$productModel = new ProductModel();
$ok = $productModel->increaseStock($productId, $quantity);

if ($ok) {
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Stock increased."]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Failed to increase stock."]);
}
