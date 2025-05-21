<?php
class OrderModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function createOrder($user_id, $items)
  {
    $this->conn->begin_transaction();
    try {
      $total_price = 0;
      foreach ($items as $item) {
        $total_price += $item['price'] * $item['quantity'];
      }

      // Insert ke tabel orders
      $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
      $stmt->bind_param("id", $user_id, $total_price);
      $stmt->execute();
      $order_id = $stmt->insert_id;

      // Insert ke tabel order_items
      $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
      foreach ($items as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
      }

      // Hapus keranjang
      $stmt = $this->conn->prepare("DELETE FROM carts WHERE user_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();

      $this->conn->commit();
      return $order_id;
    } catch (Exception $e) {
      $this->conn->rollback();
      return false;
    }
  }

  public function getOrderById($order_id, $user_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }

  public function getOrderItems($order_id)
  {
    $stmt = $this->conn->prepare("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
  ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  // Method baru untuk mengambil daftar pesanan pengguna
  public function getUserOrders($user_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }
}
