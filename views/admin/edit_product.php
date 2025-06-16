<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../models/CategoryModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../login");
  exit;
}

$productModel = new ProductModel($conn);
$categoryModel = new CategoryModel($conn);

// Validasi ID produk
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $productModel->getById($id);
if (!$product) {
  setFlash('error',  "Produk tidak ditemukan!");
  header("Location: ../");
  exit;
}

// Ambil semua kategori (pastikan return berupa array)
$categories = $categoryModel->getChildCategories();

$pageTitle = "Edit Produk";
ob_start();
?>

<h2 class="mb-4 text-xl font-bold">Edit Produk</h2>

<!-- Notifikasi -->
<?php include '../partials/alerts.php'; ?>

<form action="../../controllers/admin/edit_product.php?id=<?= $product['id']; ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
  <input type="hidden" name="action" value="edit">

  <label>Nama Produk:</label>
  <input class="px-2 py-1 border rounded" type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

  <label>Deskripsi:</label>
  <textarea class="px-2 py-1 border rounded" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>

  <label>Harga:</label>
  <input class="px-2 py-1 border rounded" type="number" name="price" value="<?= $product['price']; ?>" required step="0.01" min="0">

  <label>Stok:</label>
  <input class="px-2 py-1 border rounded" type="number" name="stock" value="<?= $product['stock']; ?>" required min="0">

  <label>Kategori:</label>
  <select class="px-2 py-1 border rounded" name="category_id" required>
    <option value="">Pilih Kategori</option>
    <?php foreach ($categories as $category): ?>
      <option value="<?= $category['id']; ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
        <?= htmlspecialchars($category['name']); ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Gambar Produk (opsional):</label>
  <input class="px-2 py-1 border rounded" type="file" name="image">
  <p class="text-sm text-gray-500">Gambar saat ini: <strong><?= htmlspecialchars($product['image']); ?></strong></p>

  <button type="submit" class="px-4 py-2 text-white rounded bg-lime-600 hover:bg-lime-700">
    Simpan Perubahan
  </button>
  <a href="./?tab=products" class="ml-2 text-gray-600 hover:underline">Kembali</a>
</form>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
