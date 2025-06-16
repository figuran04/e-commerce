<?php
require_once '../../config/init.php';
require '../../controllers/products/products_controller.php';
ob_start();

$activeCategoryId = $_GET['category'] ?? null; // Ambil kategori aktif
?>
<h2 class="text-xl font-bold"><i class="ph-fill ph-grid-nine"></i> <?= $pageTitle; ?></h2>


<?php include '../partials/alerts.php'; ?>

<!-- Navigasi Kategori -->
<div class="flex gap-4 overflow-x-auto whitespace-nowrap custom-scroll">
  <a href="../products"
    class="px-4 py-1 border rounded transition-all
      <?= $activeCategoryId === null
        ? 'bg-lime-600 text-white border-lime-600'
        : 'border-gray-200 hover:border-lime-600 hover:text-lime-700' ?>">
    Semua
  </a>
  <?php foreach ($categoriesResult as $category): ?>
    <a href="../products?category=<?= $category['id'] ?>"
      class="px-4 py-1 border rounded transition-all
        <?= ($activeCategoryId == $category['id'])
          ? 'bg-lime-600 text-white border-lime-600'
          : 'border-gray-200 hover:border-lime-600 hover:text-lime-700' ?>">
      <?= $category['name']; ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- Produk -->
<div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mt-4">
  <?php include '../../includes/product_card.php'; ?>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
