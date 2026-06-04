<?php
// controllers/api/admin/users.php
// Endpoint: GET /api/admin/users        — Daftar semua user (admin only)
//           DELETE /api/admin/users?id= — Hapus user (admin only)

require_once __DIR__ . '/../../../models/UserModel.php';

// Middleware: hanya admin
if (($currentUser['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses ditolak. Hanya admin yang dapat mengakses endpoint ini."]);
    exit;
}

$userModel = new UserModel();

// ─── GET: Daftar Semua User ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $keyword = $_GET['search'] ?? '';
    $users   = $keyword ? $userModel->search($keyword) : UserModel::all();

    // Hapus field password dari response
    $safe = array_map(function($u) {
        unset($u['password']);
        return $u;
    }, $users);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "count"  => count($safe),
        "data"   => $safe
    ]);
    exit;
}

// ─── DELETE: Hapus User ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input  = json_decode(file_get_contents('php://input'), true) ?? [];
    $userId = (int)($input['id'] ?? $_GET['id'] ?? 0);

    if (!$userId) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Parameter 'id' user wajib diisi."]);
        exit;
    }

    // Cegah admin menghapus dirinya sendiri
    if ($userId === (int)$currentUser['user_id']) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Anda tidak dapat menghapus akun Anda sendiri."]);
        exit;
    }

    $ok = $userModel->delete($userId);
    if ($ok) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "User berhasil dihapus."]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan atau gagal dihapus."]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
