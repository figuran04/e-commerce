<?php require_once '../../helpers/format.php'; ?>

<?php foreach ($products as $product) : ?>
  <a href="../product_detail?id=<?= $product['id']; ?>" class="w-full overflow-hidden transition-all bg-white border border-gray-100 rounded-lg cursor-pointer hover:shadow hover:scale-105">
    <img src="../../uploads/<?= $product['image']; ?>" alt="<?= $product['image']; ?>" class="object-contain w-full aspect-square">
    <div class="flex flex-col p-2 mb-1 space-y-1">
      <h3 class="text-sm md:text-base font-medium text-gray-900 leading-snug line-clamp-2 h-[2.6rem] md:h-[2.8rem]">
        <?= htmlspecialchars($product['name']); ?>
      </h3>
      <div class="flex items-center justify-between">
        <p class="text-lg font-semibold truncate text-lime-600">Rp<?= number_format($product['price'], 0, ',', '.'); ?></p>
        <p class="text-xs text-gray-500 truncate"><?= formatTerjual($product['sold_count']) ?? 0 ?> terjual</p>
      </div>
      <p class="text-xs text-gray-400 truncate"><?= $product['category'] ?? 'Tanpa Kategori' ?></p>
    </div>
  </a>
<?php endforeach; ?>
