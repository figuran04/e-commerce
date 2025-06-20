<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ../store?status=error");
  exit;
}

$id = intval($_GET['id']);

// Gunakan ProductModel
$productModel = new ProductModel($conn);
$product = $productModel->getById($id);

// Validasi kepemilikan atau admin
if (!$product) {
  header("Location: ../store?status=error");
  exit;
}

if ($product['user_id'] != $user_id && !$is_admin) {
  header("Location: ../store?status=unauthorized");
  exit;
}

$categories = $productModel->getCategories();

ob_start();
?>
<style type="text/tailwindcss">
  input, textarea, select {
    @apply rounded border border-gray-200 p-2;
  }
</style>
<h2 class="text-xl font-bold">Edit Produk</h2>
<?php include '../partials/alerts.php'; ?>
<form action="../../controllers/store/edit_product.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3 w-min">
  <input type="hidden" name="action" value="edit">
  <input type="hidden" name="id" value="<?= $id ?>">

  <section class="flex flex-col">
    <label>Nama Produk: <span class="text-red-600">*</span></label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
  </section>

  <section class="flex flex-col">
    <label>Deskripsi: <span class="text-red-600">*</span></label>
    <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
  </section>

  <section class="flex flex-col">
    <label>Harga: <span class="text-red-600">*</span></label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
  </section>

  <section class="flex flex-col">
    <label>Stok: <span class="text-red-600">*</span></label>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
  </section>

  <section class="flex flex-col">
    <label>Kategori: <span class="text-red-600">*</span></label>
    <select name="category_id" required>
      <option value="">Pilih Kategori</option>
      <?php foreach ($categories as $category) : ?>
        <option value="<?= $category['id'] ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($category['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </section>

  <section class="flex flex-col">
    <label>Gambar Produk:</label>
    <input type="file" name="image" id="image" accept="image/*">
  </section>
  <section class="flex flex-col">
    <p>Gambar Saat Ini: <img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="w-40"></p>
  </section>

  <button type="submit" class="p-2 text-white rounded bg-lime-600 hover:bg-lime-700">Update Produk</button>
</form>

<a href="../store" class="text-red-500">Kembali</a>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
