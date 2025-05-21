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
    $sql = "SELECT * FROM users";
    return $conn->query($sql);
  }

  public function getUserById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  // Mengecek apakah email sudah terdaftar
  public function isEmailExist($email)
  {
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
  }

  // Menyimpan pengguna baru ke database
  public function register($name, $email, $password)
  {
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    return $stmt->execute();
  }

  // Login
  public function getByEmail($email)
  {
    $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // return false jika tidak ada
  }

  public function toggleStatus($id)
  {
    $stmt = $this->db->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) return false;

    $new_status = ($user['status'] === 'active') ? 'blocked' : 'active';

    $stmt = $this->db->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
      return $new_status;
    }

    return null;
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function getRoleById($id)
  {
    $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user ? $user['role'] : null;
  }

  public function updateRole($id, $newRole)
  {
    $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $id);
    return $stmt->execute();
  }
}
