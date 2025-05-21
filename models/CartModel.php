<?php
// models/CartModel.php
class CartModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getCartItemsByUserId($user_id)
  {
    $query = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity
              FROM carts c
              JOIN products p ON c.product_id = p.id
              WHERE c.user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
      $items[] = $row;
      $total += $row['price'] * $row['quantity'];
    }

    return ['items' => $items, 'total_price' => $total];
  }

  public function getCartItems($userId)
  {
    $query = "SELECT carts.id AS cart_id,
                     products.id AS product_id,
                     products.name,
                     products.stock,
                     products.price,
                     products.image,
                     carts.quantity
              FROM carts
              JOIN products ON carts.product_id = products.id
              WHERE carts.user_id = ?";

    // Persiapkan statement dan eksekusi
    $stmt = $this->conn->prepare($query);
    if ($stmt === false) {
      // Penanganan kesalahan
      throw new Exception("Query preparation failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Mengambil hasil dan mengembalikan sebagai array
    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);  // Langsung mengambil seluruh hasil dalam bentuk array

    return $items;
  }

  public function addToCart($user_id, $product_id, $quantity)
  {
    // Cek apakah produk sudah ada di keranjang
    $query = "SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
      // Jika sudah ada, update jumlah
      $new_quantity = $row['quantity'] + $quantity;
      $update_query = "UPDATE carts SET quantity = ? WHERE id = ?";
      $stmt = $this->conn->prepare($update_query);
      $stmt->bind_param("ii", $new_quantity, $row['id']);
      $stmt->execute();
    } else {
      // Jika belum ada, tambahkan ke database
      $insert_query = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
      $stmt = $this->conn->prepare($insert_query);
      $stmt->bind_param("iii", $user_id, $product_id, $quantity);
      $stmt->execute();
    }
  }

  public function removeFromCart($cart_id)
  {
    $query = "DELETE FROM carts WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
  }

  public function updateQuantity($cart_id, $quantity)
  {
    // Pastikan jumlah minimal 1
    if ($quantity < 1) {
      $quantity = 1;
    }

    $query = "UPDATE carts SET quantity = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $quantity, $cart_id);
    $stmt->execute();
  }
}
