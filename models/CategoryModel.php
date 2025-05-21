<?php
class CategoryModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public static function all()
  {
    global $conn;
    $sql = "SELECT * FROM categories";
    return $conn->query($sql);
  }

  public function getAll()
  {
    $query = "SELECT id, name FROM categories ORDER BY name ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result();
  }

  public function getById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  public function getNameById($id)
  {
    $stmt = $this->conn->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['name'] : null;
  }

  public function add($name)
  {
    $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    return $stmt->execute();
  }

  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function update($id, $name)
  {
    $stmt = $this->conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    return $stmt->execute();
  }
}
