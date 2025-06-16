<?php
require_once '../../config/init.php';

class UserModel
{
  private $db;

  public function __construct()
  {
    global $conn;
    $this->db = $conn;
  }

  public static function all()
  {
    global $conn;
    $stmt = $conn->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUserById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function isEmailExist($email)
  {
    $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount() > 0;
  }

  public function register($name, $email, $password)
  {
    $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $email, $password]);
  }

  public function getByEmail($email)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function toggleStatus($id)
  {
    $stmt = $this->db->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return false;

    $new_status = ($user['status'] === 'active') ? 'blocked' : 'active';

    $updateStmt = $this->db->prepare("UPDATE users SET status = ? WHERE id = ?");
    if ($updateStmt->execute([$new_status, $id])) {
      return $new_status;
    }

    return null;
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public function getRoleById($id)
  {
    $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['role'] : null;
  }

  public function updateRole($id, $newRole)
  {
    $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
    return $stmt->execute([$newRole, $id]);
  }
  public function search($keyword)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
    $like = '%' . $keyword . '%';
    $stmt->execute([$like, $like]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function updateProfile($id, $name, $email, $phone, $address, $bio)
  {
    $stmt = $this->db->prepare("
    UPDATE users SET name = ?, email = ?, phone = ?, address = ?, bio = ? WHERE id = ?
  ");
    return $stmt->execute([$name, $email, $phone, $address, $bio, $id]);
  }

  public function getUserByStoreId($store_id)
  {
    $stmt = $this->db->prepare("
    SELECT users.*
    FROM users
    JOIN stores ON stores.user_id = users.id
    WHERE stores.id = ?
  ");
    $stmt->execute([$store_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
