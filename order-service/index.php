<?php
// order-service/index.php
// Nginx strips "/api/orders/" and "/api/cart/" prefix before passing here.
// So "/api/orders/" becomes "/" and "/api/cart/" becomes "/"
// We differentiate using X-Original-URI or the remaining path.

require_once __DIR__ . '/config/init.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

header("Content-Type: application/json; charset=UTF-8");

// Get the remaining path after Nginx strips the location prefix.
$request_uri = $_SERVER['REQUEST_URI'];
$route = trim(strtok($request_uri, '?'), '/');

$originalUri = $_SERVER['HTTP_X_ORIGINAL_URI'] ?? $_SERVER['REQUEST_URI'];
if ($route !== 'rpc') {
    if (strpos($originalUri, '/api/cart') !== false) {
        $route = 'cart';
    } else {
        $route = '';
    }
}

// Map routes based on combined original path logic
$routes = [
    ''          => 'controllers/api/cart_orders/orders.php',
    'rpc'       => 'controllers/api/rpc.php',
    'cart'      => 'controllers/api/cart_orders/cart.php',
];

$protectedRoutes = [
    ''      => true,
    'cart'  => true,
];

function authenticateJWT() {
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    if (empty($authHeader) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    }

    if (empty($authHeader)) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Akses ditolak. Token autentikasi tidak ditemukan."]);
        exit;
    }

    $token = null;
    if (preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
        $token = $matches[1];
    }

    require_once __DIR__ . '/helpers/jwt_helper.php';
    $userData = JWTHelper::validate($token);
    if (!$userData) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token tidak valid atau telah kedaluwarsa."]);
        exit;
    }
    return $userData;
}

if (array_key_exists($route, $routes)) {
    $currentUser = null;
    if (array_key_exists($route, $protectedRoutes)) {
        $currentUser = authenticateJWT();
    }
    require_once __DIR__ . '/' . $routes[$route];
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Endpoint API '" . htmlspecialchars($route) . "' tidak ditemukan di Order Service."]);
}
