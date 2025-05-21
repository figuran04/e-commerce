<?php
// controllers/search/search_handler.php
require_once '../../config/init.php';
require_once '../../models/SearchModel.php';

$query = isset($_GET['q']) ? $_GET['q'] : '';

// Membuat instance dari SearchModel
$searchModel = new SearchModel($conn);

// Jika ada query pencarian, lakukan pencarian
if (!empty($query)) {
  $products = $searchModel->searchProducts($query);
} else {
  // Jika tidak ada query pencarian
  $products = [];
}

// Memasukkan data ke dalam array untuk dipassing ke view
$data = [
  'query' => $query,
  'products' => $products
];

// Lanjutkan dengan me-render view atau melakukan proses lainnya
