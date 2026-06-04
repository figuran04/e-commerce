<?php
// controllers/api/admin/orders.php
// Endpoint: GET /api/admin/orders           — Semua pesanan (admin only)
//           PUT /api/admin/orders?id=X      — Update status pesanan (admin only)

require_once __DIR__ . '/../../../models/OrderModel.php';

if (($currentUser['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses ditolak. Hanya admin."]);
    exit;
}

$orderModel = new OrderModel($conn);

// ─── GET: Semua Pesanan ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT o.id, o.user_id, o.store_id, o.order_date, o.status,
                   u.name AS buyer_name, u.email AS buyer_email,
                   s.name AS store_name,
                   COALESCE(SUM(oi.price * oi.quantity), 0) AS total_price
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN stores s ON o.store_id = s.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
            GROUP BY o.id
            ORDER BY o.order_date DESC";
    $stmt = $conn->query($sql);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Filter status jika ada query param
    $status = $_GET['status'] ?? '';
    if ($status) {
        $orders = array_filter($orders, fn($o) => $o['status'] === $status);
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "count"  => count($orders),
        "data"   => array_values($orders)
    ]);
    exit;
}

// ─── PUT: Update Status Pesanan ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input   = json_decode(file_get_contents('php://input'), true) ?? [];
    $orderId = (int)($input['id']     ?? $_GET['id']     ?? 0);
    $status  = trim($input['status']  ?? '');

    $allowed = ['Dipesan', 'Dikirim', 'Selesai', 'Dibatalkan', 'Ditolak'];
    if (!$orderId || !in_array($status, $allowed)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "id dan status valid wajib diisi."]);
        exit;
    }

    $ok = $orderModel->updateOrderStatus($orderId, $status);
    if ($ok) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Status pesanan diperbarui menjadi '$status'."]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Pesanan tidak ditemukan atau gagal diperbarui."]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
