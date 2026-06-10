<?php
// controllers/api/products.php - API Controller untuk Produk

require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../models/ProductModel.php';

$productModel = new ProductModel();

// Menangani request GET untuk mengambil daftar produk
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $products = $productModel->all();
        
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "count" => count($products),
            "data" => $products
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal mengambil data produk: " . $e->getMessage()
        ]);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Metode HTTP '" . $_SERVER['REQUEST_METHOD'] . "' tidak didukung oleh endpoint ini."
    ]);
    exit;
}
