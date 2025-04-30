<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../login");
  exit;
}

// Ambil data produk berdasarkan ID
if (isset($_GET['id'])) {
  $product_id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if (!$product) {
    header("Location: ./?status=error");
    exit;
  }
} else {
  header("Location: ./?status=error");
  exit;
}

// Ambil data kategori
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Handle form submission to update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $category_id = $_POST['category_id'];
  $image = $_FILES['image']['name'];

  // Validasi input
  if (empty($name) || empty($description) || empty($price) || empty($stock)) {
    $_SESSION['error'] = "Semua field harus diisi!";
    header("Location: ./edit_product.php?id={$product_id}");
    exit;
  }

  // Upload image jika ada
  if ($image) {
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Pastikan file berhasil diupload
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      // Jika upload berhasil, gambar baru akan digunakan
    } else {
      $_SESSION['error'] = "Terjadi kesalahan saat mengupload gambar!";
      header("Location: ./edit_product.php?id={$product_id}");
      exit;
    }
  } else {
    $image = $product['image']; // Jika tidak ada gambar baru, gunakan gambar lama
  }

  // Query untuk mengupdate produk
  $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, image = ? WHERE id = ?");
  $stmt->bind_param("ssdiiss", $name, $description, $price, $stock, $category_id, $image, $product_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Produk berhasil diperbarui!";
    header("Location: ./?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat memperbarui produk!";
    header("Location: ./?status=error");
    exit;
  }
}
ob_start();
?>

<style type="text/tailwindcss">
  input, select, option, textarea{
    @apply border;
  }
</style>

<form action="./edit_product.php?id=<?= $product['id']; ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-1">
  <input type="hidden" name="action" value="edit">

  <label for="name">Product Name:</label>
  <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

  <label for="description">Description:</label>
  <textarea id="description" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>

  <label for="price">Price:</label>
  <input type="number" id="price" name="price" value="<?= $product['price']; ?>" required step="0.01">

  <label for="stock">Stock:</label>
  <input type="number" id="stock" name="stock" value="<?= $product['stock']; ?>" required>

  <label for="category_id">Category:</label>
  <select name="category_id" id="category_id" required>
    <option value="">Select Category</option>
    <?php while ($category = $result_categories->fetch_assoc()): ?>
      <option value="<?= $category['id']; ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
        <?= htmlspecialchars($category['name']); ?>
      </option>
    <?php endwhile; ?>
  </select>

  <label for="image">Image:</label>
  <input type="file" id="image" name="image">

  <button type="submit">Update Product</button>
</form>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
