<?php
class StoreModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getStoreByUserId($user_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM stores WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createStore($store)
  {
    $stmt = $this->conn->prepare("INSERT INTO stores (user_id, name, address, description) VALUES (?, ?, ?, ?)");
    return $stmt->execute([
      $store['user_id'],
      $store['name'],
      $store['address'],
      $store['description']
    ]);
  }

  public function userHasStore($user_id)
  {
    return $this->getStoreByUserId($user_id) !== false;
  }
  public function updateStore($id, $name, $description, $address)
  {
    $stmt = $this->conn->prepare("UPDATE stores SET name = ?, description = ?, address = ? WHERE id = ?");
    return $stmt->execute([$name, $description, $address, $id]);
  }

  public function getStoreAddressById($storeId)
  {
    $stmt = $this->conn->prepare("SELECT address FROM stores WHERE id = ?");
    $stmt->execute([$storeId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['address'] ?? '';
  }
  public function getStoreNameById($storeId)
  {
    $stmt = $this->conn->prepare("SELECT name FROM stores WHERE id = ?");
    $stmt->execute([$storeId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['name'] ?? '';
  }
  public function getStoreById($store_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM stores WHERE id = ?");
    $stmt->execute([$store_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
