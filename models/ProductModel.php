<?php
class ProductModel
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public static function all()
  {
    global $conn;
    $sql = "SELECT * FROM products";
    return $conn->query($sql);
  }

  public function getById($id)
  {
    $query = "SELECT p.*, c.name AS category FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
  public function getProductsByUserId($user_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM products WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function getAll($categoryId = null)
  {
    if ($categoryId) {
      $query = "SELECT p.id, p.name, p.price, p.image, c.name AS category
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      WHERE p.category_id = ?
                      ORDER BY p.created_at DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $categoryId);
    } else {
      $query = "SELECT p.id, p.name, p.price, p.image, c.name AS category
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      ORDER BY p.created_at DESC";
      $stmt = $this->conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function create($data)
  {
    $query = "INSERT INTO products (user_id, name, description, price, stock, category_id, image)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param(
      "issdiis",
      $data['user_id'],
      $data['name'],
      $data['description'],
      $data['price'],
      $data['stock'],
      $data['category_id'],
      $data['image']
    );
    return $stmt->execute();
  }
  public function update($id, $name, $description, $price, $stock, $category_id, $image)
  {
    $query = "UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssdiiis", $name, $description, $price, $stock, $category_id, $image, $id);
    return $stmt->execute();
  }

  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }


  // Validasi input produk
  public static function validateInput($name, $description, $price, $stock, $category_id)
  {
    return !(empty($name) || empty($description) || $price <= 0 || $stock < 0 || empty($category_id));
  }
}
