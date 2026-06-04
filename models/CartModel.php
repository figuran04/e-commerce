<?php
class CartModel
{
  private $pdo;

  public function __construct(PDO $pdo = null)
  {
    global $conn_orders;
    $this->pdo = $conn_orders ?? $pdo;
  }

  public function getCartItemsByUserId(int $user_id): array
  {
    $query = "SELECT id AS cart_id, product_id, quantity FROM carts WHERE user_id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$user_id]);
    $carts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($carts)) {
      return ['items' => [], 'total_price' => 0];
    }

    $productIds = array_column($carts, 'product_id');
    require_once __DIR__ . '/../helpers/service_helper.php';
    $products = ServiceHelper::fetchProducts($productIds);

    $items = [];
    $total = 0;
    foreach ($carts as $cart) {
      $pId = $cart['product_id'];
      $name = $products[$pId]['name'] ?? 'Produk Tidak Ditemukan';
      $price = $products[$pId]['price'] ?? 0;
      
      $items[] = [
        'cart_id' => $cart['cart_id'],
        'product_id' => $pId,
        'name' => $name,
        'price' => $price,
        'quantity' => $cart['quantity']
      ];
      $total += $price * $cart['quantity'];
    }

    return ['items' => $items, 'total_price' => $total];
  }

  public function getCartItems(int $userId): array
  {
    $query = "SELECT id AS cart_id, product_id, quantity FROM carts WHERE user_id = :userId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $carts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($carts)) return [];

    $productIds = array_column($carts, 'product_id');
    require_once __DIR__ . '/../helpers/service_helper.php';
    $products = ServiceHelper::fetchProducts($productIds);

    $storeIds = [];
    foreach ($products as $p) {
      if (!empty($p['store_id'])) {
        $storeIds[] = $p['store_id'];
      }
    }
    $storeIds = array_unique($storeIds);
    $stores = ServiceHelper::fetchStores($storeIds);

    $items = [];
    foreach ($carts as $cart) {
      $pId = $cart['product_id'];
      $product = $products[$pId] ?? null;
      if ($product) {
        $storeId = $product['store_id'] ?? 0;
        $store = $stores[$storeId] ?? null;

        $items[] = [
          'cart_id' => $cart['cart_id'],
          'product_id' => $pId,
          'name' => $product['name'] ?? '',
          'stock' => $product['stock'] ?? 0,
          'price' => $product['price'] ?? 0.0,
          'image' => $product['image'] ?? '',
          'quantity' => $cart['quantity'],
          'store_id' => $storeId,
          'store_name' => $store['name'] ?? 'Toko Tidak Ditemukan'
        ];
      }
    }

    return $items;
  }

  public function addToCart(int $user_id, int $product_id, int $quantity): int
  {
    // Cek apakah produk sudah ada di keranjang
    $query = "SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$user_id, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      // Jika sudah ada, update quantity
      $new_quantity = $row['quantity'] + $quantity;
      $update_query = "UPDATE carts SET quantity = ? WHERE id = ?";
      $stmt = $this->pdo->prepare($update_query);
      $stmt->execute([$new_quantity, $row['id']]);
      return (int)$row['id']; // kembalikan cart_id yang sudah ada
    } else {
      // Jika belum ada, tambahkan baris baru
      $insert_query = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
      $stmt = $this->pdo->prepare($insert_query);
      $stmt->execute([$user_id, $product_id, $quantity]);
      return (int)$this->pdo->lastInsertId(); // kembalikan cart_id baru
    }
  }


  public function clearCartByUserId(int $user_id): void
  {
    $query = "DELETE FROM carts WHERE user_id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$user_id]);
  }

  public function updateQuantity(int $cart_id, int $quantity): void
  {
    $quantity = max(1, $quantity);
    $query = "UPDATE carts SET quantity = ? WHERE id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$quantity, $cart_id]);
  }

  public function getCartItemsByUserIdAndProductIds(int $user_id, array $product_ids = []): array
  {
    if (empty($product_ids)) return ['items' => [], 'total_price' => 0];

    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $sql = "SELECT product_id, quantity FROM carts WHERE user_id = ? AND product_id IN ($placeholders)";
    $params = array_merge([$user_id], $product_ids);
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $carts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($carts)) return ['items' => [], 'total_price' => 0];

    require_once __DIR__ . '/../helpers/service_helper.php';
    $products = ServiceHelper::fetchProducts(array_column($carts, 'product_id'));

    $items = [];
    $total_price = 0;
    foreach ($carts as $cart) {
      $pId = $cart['product_id'];
      $product = $products[$pId] ?? null;
      if ($product) {
        $price = $product['price'] ?? 0;
        $items[] = [
          'product_id' => $pId,
          'name' => $product['name'] ?? '',
          'price' => $price,
          'quantity' => $cart['quantity']
        ];
        $total_price += $price * $cart['quantity'];
      }
    }

    return ['items' => $items, 'total_price' => $total_price];
  }

  public function getCartItemsByIds(array $cart_ids): array
  {
    if (empty($cart_ids)) return [];

    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $sql = "SELECT id AS cart_id, product_id, quantity FROM carts WHERE id IN ($placeholders)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($cart_ids);
    $carts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($carts)) return [];

    $productIds = array_column($carts, 'product_id');
    require_once __DIR__ . '/../helpers/service_helper.php';
    $products = ServiceHelper::fetchProducts($productIds);

    $storeIds = [];
    foreach ($products as $p) {
      if (!empty($p['store_id'])) {
        $storeIds[] = $p['store_id'];
      }
    }
    $storeIds = array_unique($storeIds);
    $stores = ServiceHelper::fetchStores($storeIds);

    $items = [];
    foreach ($carts as $cart) {
      $pId = $cart['product_id'];
      $product = $products[$pId] ?? null;
      if ($product) {
        $storeId = $product['store_id'] ?? 0;
        $store = $stores[$storeId] ?? null;

        $items[] = [
          'cart_id' => $cart['cart_id'],
          'product_id' => $pId,
          'name' => $product['name'] ?? '',
          'stock' => $product['stock'] ?? 0,
          'price' => $product['price'] ?? 0.0,
          'image' => $product['image'] ?? '',
          'quantity' => $cart['quantity'],
          'store_id' => $storeId,
          'store_name' => $store['name'] ?? 'Toko Tidak Ditemukan'
        ];
      }
    }

    return $items;
  }

  public function removeCartItem(int $cart_id, int $user_id): bool
  {
    $query = "DELETE FROM carts WHERE id = ? AND user_id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$cart_id, $user_id]);
    return $stmt->rowCount() > 0;
  }
  public function removeItemsByIds(int $user_id, array $cart_ids): bool
  {
    if (empty($cart_ids)) return false;

    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $sql = "DELETE FROM carts WHERE user_id = ? AND id IN ($placeholders)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(array_merge([$user_id], $cart_ids));
  }



  public function isCartItemOwnedByUser(int $cart_id, int $user_id): bool
  {
    $query = "SELECT id FROM carts WHERE id = ? AND user_id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$cart_id, $user_id]);
    return $stmt->fetch() !== false;
  }

  public function deleteItemsByIds(array $ids, int $userId): void
  {
    if (empty($ids)) return;

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $params = array_merge($ids, [$userId]);

    $query = "DELETE FROM carts WHERE id IN ($placeholders) AND user_id = ?";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
  }
}
