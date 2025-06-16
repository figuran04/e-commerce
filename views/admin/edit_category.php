<?php
require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';
require_once '../../views/partials/alerts.php'; // ✅ Tambahkan ini

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../login");
  exit;
}

if (!isset($_GET['id'])) {
  setFlash('error', "ID kategori tidak ditemukan."); // ✅ Pakai setFlash
  header("Location: ./");
  exit;
}

$category_id = (int)$_GET['id'];
$categoryModel = new CategoryModel($conn);
$category = $categoryModel->getById($category_id);

if (!$category) {
  setFlash('error', "Kategori tidak ditemukan."); // ✅ Pakai setFlash
  header("Location: ./");
  exit;
}

// Ambil semua kategori untuk dropdown parent
$allCategories = $categoryModel->getAll();

ob_start();
?>

<h2 class="mb-4 text-xl font-bold"><i class="ph-fill ph-pencil"></i> Edit Kategori</h2>

<?php include '../partials/alerts.php'; ?>

<form action="../../controllers/admin/edit_category.php?id=<?= $category['id']; ?>" method="POST" class="space-y-4">
  <input type="hidden" name="action" value="edit">

  <!-- Nama kategori -->
  <div>
    <label class="block mb-1 font-medium text-gray-700">Nama Kategori</label>
    <input type="text" name="category_name" value="<?= htmlspecialchars($category['name']); ?>" required
      class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-lime-200">
  </div>

  <!-- Parent kategori -->
  <div>
    <label class="block mb-1 font-medium text-gray-700">Induk Kategori</label>
    <select name="parent_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-lime-200">
      <option value="0">-- Tanpa Induk (Root) --</option>
      <?php foreach ($allCategories as $cat): ?>
        <?php if ($cat['id'] != $category['id']): ?>
          <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $category['parent_id'] ? 'selected' : ''; ?>>
            <?= htmlspecialchars($cat['name']); ?>
          </option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <button type="submit"
      class="px-4 py-2 text-white transition rounded bg-lime-600 hover:bg-lime-700">
      Simpan Perubahan
    </button>
    <a href="./?tab=categories" class="ml-2 text-gray-600 hover:underline">Kembali</a>
  </div>
</form>

<?php
$content = ob_get_clean();
include '../../layout.php';
