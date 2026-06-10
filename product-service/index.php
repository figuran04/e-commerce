<?php
// product-service/index.php
// Nginx strips "/api/products/" prefix before passing here.
// So "/api/products/" becomes "" (empty), "/api/products/bulk" becomes "bulk".

require_once __DIR__ . '/config/init.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

header("Content-Type: application/json; charset=UTF-8");

$request_uri = $_SERVER['REQUEST_URI'];
$route = trim(strtok($request_uri, '?'), '/');

$routes = [
    ''                  => 'controllers/api/products.php',
    'categories'        => 'controllers/api/products/categories.php',
    'detail'            => 'controllers/api/products/detail.php',
    'bulk'              => 'controllers/api/products/bulk.php',
    'decrease-stock'    => 'controllers/api/products/decrease_stock.php',
    'increase-stock'    => 'controllers/api/products/increase_stock.php',
    'rpc'               => 'controllers/api/rpc.php',
];

if (array_key_exists($route, $routes)) {
    require_once __DIR__ . '/' . $routes[$route];
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Endpoint API '" . htmlspecialchars($route) . "' tidak ditemukan di Product Service."]);
}
