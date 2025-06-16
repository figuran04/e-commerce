<?php
class ProductModel
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function all()
  {
    $sql = "SELECT * FROM products";
    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $query = "SELECT p.*, c.name AS category
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getProductsByUserId($userId, $sort = 'newest')
  {
    $orderBy = "id DESC"; // default: terbaru

    switch ($sort) {
      case 'price_asc':
        $orderBy = "price ASC";
        break;
      case 'price_desc':
        $orderBy = "price DESC";
        break;
      case 'stock_asc':
        $orderBy = "stock ASC";
        break;
      case 'stock_desc':
        $orderBy = "stock DESC";
        break;
        // default already set
    }

    $stmt = $this->conn->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY $orderBy");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function getAll($categoryId = null)
  {
    if ($categoryId) {
      $query = "SELECT p.id, p.name, p.price, p.image, p.sold_count, p.category_id, c.name AS category
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              WHERE p.category_id = ?
              ORDER BY p.created_at DESC";
      $stmt = $this->conn->prepare($query);
      $stmt->execute([$categoryId]);
    } else {
      $query = "SELECT p.id, p.name, p.price, p.image, p.sold_count, p.category_id, c.name AS category
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              ORDER BY p.created_at DESC";
      $stmt = $this->conn->query($query);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($product)
  {
    $query = "INSERT INTO products (store_id, user_id, name, description, price, stock, category_id, image)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
      $product['store_id'],
      $product['user_id'],
      $product['name'],
      $product['description'],
      $product['price'],
      $product['stock'],
      $product['category_id'],
      $product['image']
    ]);
  }

  public function update($id, $name, $description, $price, $stock, $category_id, $image)
  {
    $query = "UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$name, $description, $price, $stock, $category_id, $image, $id]);
  }

  public function delete($id): bool
  {
    // Cek apakah produk masih ada di pesanan aktif
    $stmt = $this->conn->prepare("
      SELECT COUNT(*) FROM order_items oi
      JOIN orders o ON oi.order_id = o.id
      WHERE oi.product_id = ? AND o.status IN ('Dipesan')
    ");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
      // Produk masih ada di pesanan aktif, tidak boleh dihapus
      return false;
    }

    // Aman dihapus
    $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
  }


  // Validasi input produk
  public static function validateInput($name, $description, $price, $stock, $category_id)
  {
    return !(empty($name) || empty($description) || $price <= 0 || $stock < 0 || empty($category_id));
  }
  public function getProductById($id)
  {
    $stmt = $this->conn->prepare("
      SELECT p.*, s.name AS store_name
      FROM products p
      LEFT JOIN stores s ON p.store_id = s.id
      WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function decreaseStock(int $product_id, int $quantity): bool
  {
    $sql = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$quantity, $product_id, $quantity]);
  }

  public function increaseStock(int $product_id, int $quantity): bool
  {
    $sql = "UPDATE products SET stock = stock + ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$quantity, $product_id]);
  }

  public function incrementSoldCount($productId, $quantity)
  {
    $stmt = $this->conn->prepare("UPDATE products SET sold_count = sold_count + ? WHERE id = ?");
    return $stmt->execute([$quantity, $productId]);
  }


  public function search($keyword)
  {
    $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $like = '%' . $keyword . '%';
    $stmt->execute([$like]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getCategories()
  {
    $query = "SELECT * FROM categories";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTopImagesByCategory(array $categoryIds): array
  {
    $thumbnails = [];

    foreach ($categoryIds as $catId) {
      $stmt = $this->conn->prepare("SELECT image FROM products WHERE category_id = ? ORDER BY id DESC LIMIT 1");
      $stmt->execute([$catId]);
      $product = $stmt->fetch(PDO::FETCH_ASSOC);
      $thumbnails[$catId] = $product['image'] ?? null;
    }

    return $thumbnails;
  }
  public function getProductsByStoreId($store_id, $sort = 'newest')
  {
    $orderBy = 'p.created_at DESC'; // default sorting

    switch ($sort) {
      case 'price_asc':
        $orderBy = 'p.price ASC';
        break;
      case 'price_desc':
        $orderBy = 'p.price DESC';
        break;
      case 'name_asc':
        $orderBy = 'p.name ASC';
        break;
      case 'name_desc':
        $orderBy = 'p.name DESC';
        break;
      case 'stock_desc':
        $orderBy = 'p.stock DESC';
        break;
      case 'stock_asc':
        $orderBy = 'p.stock DESC';
        break;
      case 'newest':
      default:
        $orderBy = 'p.created_at DESC';
        break;
    }

    $stmt = $this->conn->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.store_id = ?
    ORDER BY $orderBy
  ");

    $stmt->execute([$store_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getByMultipleCategories(array $categoryIDs)
  {
    $placeholders = implode(',', array_fill(0, count($categoryIDs), '?'));
    $stmt = $this->conn->prepare("SELECT * FROM products WHERE category_id IN ($placeholders)");
    $stmt->execute($categoryIDs);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
