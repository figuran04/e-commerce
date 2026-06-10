<?php
// File debug sementara - hapus setelah selesai debug
require_once __DIR__ . '/../../config/init.php';

header('Content-Type: application/json');
echo json_encode([
    'session_user_id' => $_SESSION['user_id'] ?? null,
    'session_user_name' => $_SESSION['user_name'] ?? null,
    'cookie_jwt_present' => isset($_COOKIE['zerovaa_jwt']),
    'cookie_jwt_prefix' => isset($_COOKIE['zerovaa_jwt']) ? substr($_COOKIE['zerovaa_jwt'], 0, 30) . '...' : null,
    'all_cookies' => array_keys($_COOKIE),
    'session_id' => session_id(),
    'session_status' => session_status(),
    'php_version' => PHP_VERSION,
]);
