<?php
// controllers/api/products/categories.php - API Endpoint: GET /api/products/categories

require_once __DIR__ . '/../../../models/CategoryModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
    exit;
}

$categoryModel = new CategoryModel();
$all = $categoryModel->getAll();

// Bangun tree: pisahkan root dan child
$roots  = [];
$childs = [];
foreach ($all as $cat) {
    if (is_null($cat['parent_id'])) {
        $roots[]  = $cat;
    } else {
        $childs[] = $cat;
    }
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "data"   => [
        "root"     => $roots,
        "children" => $childs,
        "all"      => $all
    ]
]);
