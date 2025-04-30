<?php
require '../../controllers/products/products_controller.php';

$categories = [];
while ($row = mysqli_fetch_assoc($categoriesResult)) {
  $categories[] = $row;
}
$pageTitle = "Beranda";
ob_start();
?>

<style type='text/tailwindcss'>
  .product-grid{
     @apply grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4;
   }
</style>

<img src="../../uploads/banner.png" alt="banner" class="rounded-lg w-full" />
<div class="bg-white p-4 border border-gray-200 rounded-lg flex flex-col space-y-4">
  <h1 class="text-xl font-bold">Kategori Pilihan</h1>
  <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-4">
    <a href="../products"
      class="border rounded px-4 py-1 aspect-square justify-center w-full flex items-end border-gray-200 line-clamp-2">
      Semua
    </a>
    <?php
    $limit = 7;
    $count = 0;
    foreach ($categories as $category) :
      if ($count++ >= $limit) break;
    ?>
      <a href="../products?category=<?= $category['id'] ?>"
        class="border rounded px-4 py-1 aspect-square justify-center w-full flex items-end border-gray-200 line-clamp-2">
        <?= $category['name']; ?>
      </a>
    <?php endforeach; ?>
  </div>
  <div class="flex gap-4 overflow-x-auto">
    <a href="../products"
      class="border rounded px-4 py-1 border-gray-200 text-lime-600">
      Semua
    </a>
    <?php foreach ($categories as $category) : ?>
      <a href="../products?category=<?= $category['id'] ?>"
        class="border rounded px-4 py-1 border-gray-200 text-lime-600">
        <?= $category['name']; ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="product-grid">
  <?php include '../../includes/product_card.php'; ?>
</div>

<div class="w-full text-center">
  <a href="../products" class="hover:text-lime-600 hover:underline">Lihat Semua</a>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
