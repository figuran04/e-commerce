<?php
class OrderModel
{
  private PDO $conn;

  public function __construct(PDO $conn = null)
  {
    global $conn_orders;
    $this->conn = $conn_orders ?? $conn;
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
      od.product_image
    FROM orders o
    JOIN order_items od ON o.id = od.order_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
      return [];
    }

    // Fetch store info bulk
    $storeIds = array_unique(array_column($rows, 'store_id'));
    require_once __DIR__ . '/../helpers/service_helper.php';
    $stores = ServiceHelper::fetchStores($storeIds);

    $orders = [];
    foreach ($rows as $row) {
      $orderId = $row['order_id'];
      $storeId = $row['store_id'];
      $store = $stores[$storeId] ?? null;

      if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
          'id' => $orderId,
          'store_id' => $storeId,
          'store_name' => $store['name'] ?? 'Toko Tidak Ditemukan',
          'store_address' => $store['address'] ?? '',
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
    require_once __DIR__ . '/../helpers/service_helper.php';
    $products = ServiceHelper::fetchProducts([$product_id]);
    $product = $products[$product_id] ?? null;

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
    require_once __DIR__ . '/../helpers/service_helper.php';
    return ServiceHelper::decreaseStock($product_id, $quantity);
  }

  public function getOrdersByStoreId($storeId): array
  {
    $query = "
      SELECT orders.*
      FROM orders
      WHERE orders.store_id = ?
      ORDER BY orders.order_date DESC
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$storeId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($orders)) return [];

    $userIds = array_unique(array_column($orders, 'user_id'));
    require_once __DIR__ . '/../helpers/service_helper.php';
    $users = ServiceHelper::fetchUsers($userIds);

    foreach ($orders as &$order) {
      $buyer = $users[$order['user_id']] ?? null;
      $order['buyer_name'] = $buyer['name'] ?? 'Pembeli Tidak Ditemukan';
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
            o.user_id AS buyer_id
          FROM orders o
          WHERE o.store_id = ?
          ORDER BY o.order_date DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$store_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($orders)) return [];

    $userIds = array_unique(array_column($orders, 'buyer_id'));
    require_once __DIR__ . '/../helpers/service_helper.php';
    $users = ServiceHelper::fetchUsers($userIds);

    foreach ($orders as &$order) {
      $buyer = $users[$order['buyer_id']] ?? null;
      $order['buyer_name'] = $buyer['name'] ?? 'Pembeli Tidak Ditemukan';
      $order['buyer_address'] = $buyer['address'] ?? '';
      $order['buyer_phone'] = $buyer['phone'] ?? '';
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
      od.product_id, od.quantity, od.price, od.product_name, od.product_image
    FROM orders o
    JOIN order_items od ON o.id = od.order_id
    WHERE o.id = ?
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$orderId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
      return null;
    }

    $storeId = $rows[0]['store_id'];
    require_once __DIR__ . '/../helpers/service_helper.php';
    $stores = ServiceHelper::fetchStores([$storeId]);
    $store = $stores[$storeId] ?? null;

    $order = [
      'id' => $rows[0]['id'],
      'store_owner_id' => $store['user_id'] ?? 0,
      'store_id' => $storeId,
      'store_name' => $store['name'] ?? 'Toko Tidak Ditemukan',
      'store_address' => $store['address'] ?? '',
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
    $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
      require_once __DIR__ . '/../helpers/service_helper.php';
      
      // Fetch user info
      $users = ServiceHelper::fetchUsers([$order['user_id']]);
      $buyer = $users[$order['user_id']] ?? null;
      $order['buyer_name'] = $buyer['name'] ?? 'Pembeli Tidak Ditemukan';
      $order['buyer_phone'] = $buyer['phone'] ?? '';

      // Fetch store info
      $stores = ServiceHelper::fetchStores([$order['store_id']]);
      $store = $stores[$order['store_id']] ?? null;
      $order['store_owner_id'] = $store['user_id'] ?? 0;
      $order['store_name'] = $store['name'] ?? 'Toko Tidak Ditemukan';
      $order['store_address'] = $store['address'] ?? '';

      // Fetch items
      $stmtItems = $this->conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
      $stmtItems->execute([$orderId]);
      $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

      // Reconstruct with product details compatibility
      foreach ($items as &$item) {
        $item['name'] = $item['product_name'];
        $item['image'] = $item['product_image'];
      }
      $order['items'] = $items;
    }

    return $order;
  }
}
