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
            WHERE products.name LIKE :nameQuery OR products.description LIKE :descQuery";

    $stmt = $this->conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bindValue(':nameQuery', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':descQuery', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$row) {
      $row['category'] = $row['category_name'] ?? 'Tanpa Kategori';
    }

    return $products;
  }
}
