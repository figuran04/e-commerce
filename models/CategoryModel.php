<?php
class CategoryModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getAll()
  {
    $query = "SELECT * FROM categories ORDER BY COALESCE(parent_id, 0), name ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getRootCategories()
  {
    $query = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getNameById($id)
  {
    $stmt = $this->conn->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name'] : null;
  }

  public function add($name, $parent_id = null)
  {
    $stmt = $this->conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
    return $stmt->execute([$name, $parent_id]);
  }

  public function delete($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public function update($id, $name, $parent_id = null)
  {
    if ($parent_id === null) {
      $stmt = $this->conn->prepare("UPDATE categories SET name = ?, parent_id = NULL WHERE id = ?");
      return $stmt->execute([$name, $id]);
    } else {
      $stmt = $this->conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
      return $stmt->execute([$name, $parent_id, $id]);
    }
  }
  public function search($keyword)
  {
    $stmt = $this->conn->prepare("SELECT * FROM categories WHERE name LIKE ?");
    $like = '%' . $keyword . '%';
    $stmt->execute([$like]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getChildCategories()
  {
    $stmt = $this->conn->prepare("SELECT id, name FROM categories WHERE parent_id IS NOT NULL");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getChildren($parentId)
  {
    $stmt = $this->conn->prepare("SELECT * FROM categories WHERE parent_id = ?");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
