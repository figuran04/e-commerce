<?php
require_once '../../config/init.php';

$query = isset($_GET['q']) ? $_GET['q'] : '';

// Jika ada query pencarian, lakukan pencarian
if (!empty($query)) {
  $sql = "SELECT products.*, categories.name AS category_name
            FROM products
            LEFT JOIN categories ON products.category_id = categories.id
            WHERE products.name LIKE ? OR products.description LIKE ?";

  // Siapkan dan jalankan query
  $stmt = $conn->prepare($sql);
  $searchTerm = "%" . $query . "%"; // Menambahkan wildcard untuk pencarian LIKE
  $stmt->bind_param("ss", $searchTerm, $searchTerm);
  $stmt->execute();
  $result = $stmt->get_result();

  // Simpan hasil pencarian dalam array
  $products = [];
  while ($row = $result->fetch_assoc()) {
    // Menambahkan key 'category' untuk memenuhi kebutuhan includes/product_card.php
    $row['category'] = $row['category_name'] ?: 'Tanpa Kategori';
    $products[] = $row;
  }
} else {
  // Jika tidak ada query pencarian
  $products = [];
}

// Memasukkan data ke dalam array untuk dipassing ke view
$data = [
  'query' => $query,
  'products' => $products
];
