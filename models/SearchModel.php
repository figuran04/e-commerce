<?php
// models/SearchModel.php
class SearchModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function searchProducts($query)
  {
    $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                WHERE products.name LIKE ? OR products.description LIKE ?";

    $stmt = $this->conn->prepare($sql);
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

    return $products;
  }
}
