<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../login");
  exit;
}

// Ambil data kategori berdasarkan ID
if (isset($_GET['id'])) {
  $category_id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
  $stmt->bind_param("i", $category_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $category = $result->fetch_assoc();

  if (!$category) {
    header("Location: ./?status=error");
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
  $category_name = trim($_POST['category_name']);

  if (empty($category_name)) {
    $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
    header("Location: ./edit_category.php?id={$category_id}");
    exit;
  }

  // Query untuk mengupdate kategori
  $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
  $stmt->bind_param("si", $category_name, $category_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Kategori berhasil diperbarui!";
    header("Location: ./?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat memperbarui kategori!";
    header("Location: ./?status=error");
    exit;
  }
}
ob_start();
?>

<form action="./edit_category.php?id=<?= $category['id']; ?>" method="POST">
  <input type="hidden" name="action" value="edit">
  <input type="text" name="category_name" value="<?= htmlspecialchars($category['name']); ?>" class="border" required>
  <button type="submit">Update</button>
</form>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
