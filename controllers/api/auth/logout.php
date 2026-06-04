<?php
// controllers/api/auth/logout.php
// Endpoint: POST /api/auth/logout
// Tujuan: Destroy session PHP saat user logout dari sisi klien.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

http_response_code(200);
echo json_encode(["status" => "success", "message" => "Berhasil logout."]);
