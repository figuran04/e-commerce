<?php
require '../../config/init.php';
require_once '../../models/CategoryModel.php';
require_once '../../models/StoreModel.php';
require_once '../../views/partials/alerts.php'; // ✅ Tambahkan setFlash

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$categoryModel = new CategoryModel($conn);
$categories = $categoryModel->getChildCategories();

$storeModel = new StoreModel($conn);
$store = $storeModel->getStoreByUserId($_SESSION['user_id']);

if (!$store || empty(trim($store['address']))) {
  setFlash('error', "Silakan lengkapi alamat toko terlebih dahulu sebelum mengunggah produk."); // ✅ Ganti session langsung
  header("Location: ../store/edit_store.php");
  exit;
}

ob_start();
?>

<style type="text/tailwindcss">
  .form_upload {
    @apply flex flex-col gap-6 p-4 sm:p-6 max-w-lg md:mx-0;
  }

  .form_upload label {
    @apply block font-semibold mb-1 text-gray-700;
  }

  .form_upload input,
  .form_upload textarea,
  .form_upload select {
    @apply w-full rounded border border-gray-300 p-3 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-lime-500 transition;
  }

  @media (min-width: 640px) {
    .form_upload textarea {
      @apply h-32;
    }
  }

  .form_upload button {
    @apply bg-lime-600 hover:bg-lime-700 text-white font-semibold p-2 px-3 rounded transition;
  }

  .cancel_link {
    @apply block text-red-600 mt-4 hover:underline;
  }
</style>

<div class="text-xl flex items-center gap-2">
  <i class="ph-fill ph-upload"></i>
  <h2 class="font-bold">Unggah Produk</h2>
</div>

<?php include '../partials/alerts.php'; ?>

<form action="../../controllers/products/process_upload_product.php" method="POST" enctype="multipart/form-data" class="form_upload" novalidate>
  <div>
    <label for="name">Nama Produk <span class="text-red-600">*</span></label>
    <input type="text" name="name" id="name" required placeholder="Masukkan nama produk">
  </div>
  <div>
    <label for="description">Deskripsi <span class="text-red-600">*</span></label>
    <textarea name="description" id="description" required placeholder="Masukkan deskripsi produk" style="resize: none;"></textarea>
  </div>
  <div>
    <label for="price">Harga (Rp) <span class="text-red-600">*</span></label>
    <input type="number" name="price" id="price" required min="0" step="100" placeholder="0">
  </div>
  <div>
    <label for="stock">Stok <span class="text-red-600">*</span></label>
    <input type="number" name="stock" id="stock" required min="0" step="1" placeholder="0">
  </div>
  <div>
    <label for="category">Kategori <span class="text-red-600">*</span></label>
    <select name="category_id" id="category" required>
      <option value="">Pilih Kategori</option>
      <?php foreach ($categories as $category): ?>
        <option value="<?= htmlspecialchars($category['id']) ?>">
          <?= htmlspecialchars($category['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label for="image">Gambar Produk <span class="text-red-600">*</span></label>
    <input type="file" name="image" id="image" accept="image/*" required>
  </div>
  <button type="submit">Unggah</button>
</form>

<a href="../store" class="cancel_link">Batal</a>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
