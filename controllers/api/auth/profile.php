<?php
// controllers/api/auth/profile.php - API Endpoint: GET & PUT /api/auth/profile (Protected)
// $currentUser diisi oleh api/gateway.php setelah validasi JWT

require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../models/StoreModel.php';

$userId = $currentUser['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Tidak terautentikasi."]);
    exit;
}

$userModel = new UserModel();

// ───────────────────────────── GET: Ambil profil ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user = $userModel->getUserById($userId);
    if (!$user) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan."]);
        exit;
    }

    // Sembunyikan kolom sensitif
    unset($user['password']);

    // Ambil info toko jika ada
    $storeModel = new StoreModel($conn);
    $store = $storeModel->getStoreByUserId($userId);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data"   => array_merge($user, ["store" => $store ?: null])
    ]);
    exit;
}

// ──────────────────────────── PUT: Update profil ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input   = json_decode(file_get_contents('php://input'), true) ?? [];
    $name    = trim($input['name']    ?? '');
    $email   = trim($input['email']   ?? '');
    $phone   = trim($input['phone']   ?? '');
    $address = trim($input['address'] ?? '');
    $bio     = trim($input['bio']     ?? '');

    if (empty($name) || empty($email)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Nama dan email tidak boleh kosong."]);
        exit;
    }

    // Pastikan email baru tidak dipakai user lain
    $existing = $userModel->getByEmail($email);
    if ($existing && (int)$existing['id'] !== (int)$userId) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "Email sudah dipakai oleh akun lain."]);
        exit;
    }

    $ok = $userModel->updateProfile($userId, $name, $email, $phone, $address, $bio);
    if ($ok) {
        $updated = $userModel->getUserById($userId);
        unset($updated['password']);
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Profil berhasil diperbarui.", "data" => $updated]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui profil."]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
