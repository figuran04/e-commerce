<?php foreach ($products as $product) : ?>
  <a href="../product_detail?id=<?= $product['id']; ?>" class="cursor-pointer w-full border border-gray-100 hover:shadow rounded-lg hover:scale-105 transition-all overflow-hidden bg-white">
    <img src="../../uploads/<?= $product['image']; ?>" class="w-full aspect-square object-contain">
    <div class="p-2 mb-1 flex flex-col space-y-1">
      <h3 class="text-sm line-clamp-2 w-full leading-tight"><?= $product['name']; ?></h3>

      <p class="text-lime-600 text-xl font-semibold line-clamp-1">Rp <?= number_format($product['price'], 0, ',', '.'); ?></p>
      <p class="text-gray-400 line-clamp-1 text-sm"><?= $product['category'] ?: 'Tanpa Kategori'; ?></p>
    </div>
  </a>
<?php endforeach; ?>
