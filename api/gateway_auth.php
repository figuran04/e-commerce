<?php
// api/gateway_auth.php - API Gateway khusus untuk Auth & Tenant Service (auth.zerovaa.com)

require_once __DIR__ . '/../config/init.php';

// Header CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

header("Content-Type: application/json; charset=UTF-8");

$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// Rute yang didukung oleh Auth Service
$routes = [
    'auth/login'         => 'controllers/api/auth/login.php',
    'auth/register'      => 'controllers/api/auth/register.php',
    'auth/profile'       => 'controllers/api/auth/profile.php',      // Protected
    'auth/sync_session'  => 'controllers/api/auth/sync_session.php', // Protected
    'auth/logout'        => 'controllers/api/auth/logout.php',
    'stores/bulk'        => 'controllers/api/auth/stores_bulk.php',   // Internal
    'users/bulk'         => 'controllers/api/auth/users_bulk.php',    // Internal
];

$protectedRoutes = [
    'auth/profile'      => true,
    'auth/sync_session' => true,
];

function authenticateJWT() {
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    if (empty($authHeader) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    }

    if (empty($authHeader)) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Akses ditolak. Token autentikasi tidak ditemukan."
        ]);
        exit;
    }

    $token = null;
    if (preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
        $token = $matches[1];
    }

    if (!$token) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Format token autentikasi salah (gunakan Bearer <token>)."
        ]);
        exit;
    }

    require_once __DIR__ . '/../helpers/jwt_helper.php';
    $userData = JWTHelper::validate($token);
    if (!$userData) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Token tidak valid atau telah kedaluwarsa."
        ]);
        exit;
    }

    return $userData;
}

if (array_key_exists($route, $routes)) {
    $currentUser = null;
    if (array_key_exists($route, $protectedRoutes)) {
        $currentUser = authenticateJWT();
    }

    $controllerPath = __DIR__ . '/../' . $routes[$route];
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Controller API tidak ditemukan pada sistem backend."
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "Endpoint API '" . htmlspecialchars($route) . "' tidak ditemukan di Auth Service."
    ]);
}
