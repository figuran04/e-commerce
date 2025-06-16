<?php
class OrderModel
{
  private PDO $conn;

  public function __construct(PDO $conn)
  {
    $this->conn = $conn;
  }

  // Ambil daftar pesanan berdasarkan user_id
  public function getOrdersByUserId($userId)
  {
    $query = "
      SELECT o.id, o.order_date, o.status,
        COALESCE(SUM(od.price * od.quantity), 0) AS total_price
      FROM orders o
      JOIN order_items od ON o.id = od.order_id
      WHERE o.user_id = ?
      GROUP BY o.id
      ORDER BY o.order_date DESC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Anomali
  public function getOrdersByUserIdWithItems($userId)
  {
    $query = "SELECT
      o.id AS order_id,
      o.store_id,
      o.order_date,
      o.status,
      od.product_id,
      od.quantity,
      od.price,
      od.product_name,
      od.product_image,
      s.name AS store_name,
      s.address AS store_address
    FROM orders o
    JOIN order_items od ON o.id = od.order_id
    JOIN stores s ON o.store_id = s.id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
  ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$userId]);

    $orders = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $orderId = $row['order_id'];

      if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
          'id' => $orderId,
          'store_id' => $row['store_id'],
          'store_name' => $row['store_name'],
          'store_address' => $row['store_address'],
          'order_date' => $row['order_date'],
          'status' => $row['status'],
          'total_price' => 0,
          'items' => []
        ];
      }

      $orders[$orderId]['items'][] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'name' => $row['product_name'],
        'image' => $row['product_image']
      ];

      $orders[$orderId]['total_price'] += $row['quantity'] * $row['price'];
    }

    return array_values($orders);
  }


  public function updateStatus(int $order_id, string $new_status, string $expected_current, string $owner_column, int $owner_id): bool
  {
    $sql = "UPDATE orders SET status = ? WHERE id = ? AND status = ? AND $owner_column = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$new_status, $order_id, $expected_current, $owner_id]);
    return $stmt->rowCount() > 0;
  }

  // Buat pesanan baru
  public function createOrder($user_id, $store_id, $total_price): ?int
  {
    $query = "INSERT INTO orders (user_id, store_id, total_price) VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    if ($stmt->execute([$user_id, $store_id, $total_price])) {
      return $this->conn->lastInsertId();
    }
    return null;
  }

  // Tambahkan detail pesanan: dengan snapshot nama & gambar
  public function addOrderDetail($order_id, $product_id, $quantity, $price)
  {
    $productStmt = $this->conn->prepare("SELECT name, image FROM products WHERE id = ?");
    $productStmt->execute([$product_id]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) return false;

    $stmt = $this->conn->prepare("
      INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, price)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([
      $order_id,
      $product_id,
      $product['name'],
      $product['image'],
      $quantity,
      $price
    ]);
  }

  // Kurangi stok produk
  public function updateProductStock($product_id, $quantity)
  {
    $stmt = $this->conn->prepare("
      UPDATE products
      SET stock = stock - ?
      WHERE id = ? AND stock >= ?
    ");
    return $stmt->execute([$quantity, $product_id, $quantity]);
  }

  public function getOrdersByStoreId($storeId): array
  {
    $query = "
      SELECT orders.*, users.name AS buyer_name
      FROM orders
      JOIN users ON orders.user_id = users.id
      WHERE orders.store_id = ?
      ORDER BY orders.order_date DESC
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$storeId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {
      $order['items'] = $this->getOrderItems($order['id']);
    }

    return $orders;
  }

  public function updateOrderStatus(int $order_id, string $status): bool
  {
    $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $order_id]);
  }

  public function getOrderItems(int $order_id): array
  {
    $sql = "SELECT
              product_id,
              product_name AS name,
              product_image AS image,
              quantity,
              price
            FROM order_items
            WHERE order_id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  // Normal
  public function getOrdersByStoreIdWithItems(int $store_id): array
  {
    $sql = "SELECT
            o.*,
            o.user_id AS buyer_id,
            u.name AS buyer_name,
            u.address AS buyer_address,
            u.phone AS buyer_phone
          FROM orders o
          JOIN users u ON o.user_id = u.id
          WHERE o.store_id = ?
          ORDER BY o.order_date DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$store_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {
      $order['items'] = $this->getOrderItems($order['id']);
    }

    return $orders;
  }


  public function getOrderById(int $order_id): ?array
  {
    $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function getOrderByIdWithItems($orderId)
  {
    $query = "
    SELECT
      o.*,
      od.product_id, od.quantity, od.price, od.product_name, od.product_image,
      s.name AS store_name, s.address AS store_address, s.user_id AS store_owner_id
    FROM orders o
    JOIN order_items od ON o.id = od.order_id
    JOIN stores s ON o.store_id = s.id
    WHERE o.id = ?
  ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$orderId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
      return null; // pesanan tidak ditemukan
    }

    // Ambil info utama dari baris pertama
    $order = [
      'id' => $rows[0]['id'],
      'store_owner_id' => $rows[0]['store_owner_id'],
      'store_id' => $rows[0]['store_id'],
      'store_name' => $rows[0]['store_name'],
      'store_address' => $rows[0]['store_address'],
      'user_id' => $rows[0]['user_id'],
      'order_date' => $rows[0]['order_date'],
      'status' => $rows[0]['status'],
      'total_price' => 0,
      'items' => []
    ];

    foreach ($rows as $row) {
      $order['items'][] = [
        'product_id' => $row['product_id'],
        'name' => $row['product_name'],
        'image' => $row['product_image'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
      ];
      $order['total_price'] += $row['quantity'] * $row['price'];
    }

    return $order;
  }

  public function getOrderByIdWithItemsAndUser($orderId)
  {
    $stmt = $this->conn->prepare("
    SELECT
      o.*,
      u.name AS buyer_name,
      u.phone AS buyer_phone,
      s.user_id AS store_owner_id,
      s.name AS store_name,
      s.address AS store_address
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN stores s ON o.store_id = s.id
    WHERE o.id = ?
  ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
      $stmtItems = $this->conn->prepare("
      SELECT oi.*, p.name, p.image
      FROM order_items oi
      JOIN products p ON oi.product_id = p.id
      WHERE oi.order_id = ?
    ");
      $stmtItems->execute([$orderId]);
      $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }

    return $order;
  }
}
