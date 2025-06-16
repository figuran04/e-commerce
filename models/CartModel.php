<?php
class CartModel
{
  private $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function getCartItemsByUserId(int $user_id): array
  {
    $query = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity
              FROM carts c
              JOIN products p ON c.product_id = p.id
              WHERE c.user_id = ?";

    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach ($items as $item) {
      $total += $item['price'] * $item['quantity'];
    }

    return ['items' => $items, 'total_price' => $total];
  }

  public function getCartItems(int $userId): array
  {
    $query = "SELECT carts.id AS cart_id,
                     products.id AS product_id,
                     products.name,
                     products.stock,
                     products.price,
                     products.image,
                     carts.quantity,
                     stores.id AS store_id,
                     stores.name AS store_name
              FROM carts
              JOIN products ON carts.product_id = products.id
              JOIN stores ON products.store_id = stores.id
              WHERE carts.user_id = :userId";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $sql = "SELECT c.product_id, p.name, p.price, c.quantity
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ? AND c.product_id IN ($placeholders)";

    $params = array_merge([$user_id], $product_ids);
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_price = 0;
    foreach ($items as $item) {
      $total_price += $item['price'] * $item['quantity'];
    }

    return ['items' => $items, 'total_price' => $total_price];
  }

  public function getCartItemsByIds(array $cart_ids): array
  {
    if (empty($cart_ids)) return [];

    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $sql = "SELECT carts.id AS cart_id,
                   products.id AS product_id,
                   products.name,
                   products.stock,
                   products.price,
                   products.image,
                   carts.quantity,
                   stores.id AS store_id,
                   stores.name AS store_name
            FROM carts
            JOIN products ON carts.product_id = products.id
            JOIN stores ON products.store_id = stores.id
            WHERE carts.id IN ($placeholders)";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($cart_ids);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
