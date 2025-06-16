<?php
require '../../controllers/store/store_handler.php';
require_once '../../helpers/flash.php';
require_once '../../helpers/format.php';
require_once '../../helpers/tampil_data.php';
$pageTitle = "Toko";
ob_start();

$session_user_id = $session_user_id ?? null;
$session_store_id = $_SESSION['store_id'] ?? null;

// Ringkasan data
$totalProduk = count($products);
$totalStok = array_sum(array_column($products, 'stock'));
$avgHarga = $totalProduk > 0 ? array_sum(array_column($products, 'price')) / $totalProduk : 0;
?>

<style type="text/tailwindcss">
  .view-toggle {
    @apply px-3 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300 transition;
  }

  .view-toggle.active {
    @apply bg-lime-600 text-white font-semibold;
  }
</style>

<div class="space-y-6">
  <!-- Judul -->
  <h2 class="flex items-center gap-2 text-xl font-bold">
    <?php if ($store): ?>
      <?php if ($is_own_profile): ?>
        <i class="ph-fill ph-storefront"></i> Toko Saya
      <?php else: ?>
        <i class="ph-fill ph-user"></i> Toko: <?= htmlspecialchars($user['name']) ?>
      <?php endif; ?>
    <?php else: ?>
      <i class="ph-fill ph-store-slash"></i> Toko
    <?php endif; ?>
  </h2>

  <?php include '../partials/alerts.php'; ?>
  <div class="flex flex-col gap-2 md:grid md:grid-cols-3">
    <div class="row-span-2 p-6 bg-white border border-gray-200 rounded-lg">

      <?php if (!$store && empty($_GET['id'])): ?>
        <p class="text-sm text-gray-500">Anda belum memiliki toko.</p>
        <a href="create_store.php" class="inline-flex items-center gap-2 px-4 py-2 mt-4 text-sm font-medium text-white rounded bg-lime-600 hover:bg-lime-700">
          <i class="ph-bold ph-plus"></i> Buat Toko
        </a>

      <?php elseif (!$store && !empty($_GET['id'])): ?>
        <p class="text-sm text-red-600">Toko tidak ditemukan.</p>

      <?php else: ?>
        <!-- Toko ditemukan -->
        <div class="flex items-center gap-4 mb-4">
          <div class="flex items-center justify-center overflow-hidden border-2 border-gray-300 rounded-full size-20">
            <i class="text-5xl text-gray-300 ph-fill ph-user"></i>
          </div>
          <div>
            <h3 class="text-xl font-semibold text-lime-600"><?= htmlspecialchars($store['name']) ?></h3>
            <p class="text-sm text-gray-500">ID Toko: #<?= $store['id'] ?></p>
          </div>
        </div>
        <div class="mt-4">
          <label class="block mb-1 text-sm font-medium text-gray-700">Deskripsi</label>
          <div class="p-3 border border-gray-200 rounded bg-gray-50 text-sm text-gray-700 min-h-[3rem]">
            <p class="text-gray-400"><?= tampilkanData($store['description']); ?></p>
          </div>
        </div>

        <?php if ($is_own_profile): ?>
          <div class="flex gap-2 mt-4">
            <a href="./edit_store.php" class="flex items-center px-4 py-1 text-sm font-medium border-2 rounded text-lime-600 hover:text-lime-700 border-lime-600 hover:border-lime-700">Edit Profil</a>
            <a href="upload_product.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded bg-lime-600 hover:bg-lime-700">
              <i class="ph-fill ph-upload"></i> Unggah Produk
            </a>
          </div>
        <?php endif; ?>
        <div class="mt-4 space-y-1 text-sm text-gray-700">
          <?php if ($session_store_id && !$is_own_profile): ?>
            <p><strong>No HP/WA:</strong> <?= tampilkanData($user['phone']); ?></p>
          <?php endif; ?>
          <p><strong>Alamat:</strong> <?= tampilkanData($store['address']); ?></p>
        </div>
      <?php endif; ?>
    </div>


    <?php if ($session_store_id && $is_own_profile): ?>
      <div class="col-span-2 p-6 text-sm bg-white border border-gray-200 rounded-lg">
        <h3 class="mb-3 text-lg font-semibold">
          <a href="../orders/store_orders.php">Pesanan Pelanggan</a>
        </h3>
        <ul class="grid grid-cols-4 gap-3">
          <li>
            <a href="../orders/store_orders.php?status=Dipesan" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-package"></i>
              Dipesan
            </a>
          </li>
          <li>
            <a href="../orders/store_orders.php?status=Dikirim" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-truck"></i>
              Dikirim
            </a>
          </li>
          <li>
            <a href="../orders/store_orders.php?status=Selesai" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-check-circle"></i>
              Selesai
            </a>
          </li>
          <li>
            <a href="../orders/store_orders.php?status=Dibatalkan" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-x-circle"></i>
              Dibatalkan
            </a>
          </li>
        </ul>
      </div>

      <!-- Ringkasan Toko -->
      <div class="grid w-full grid-cols-1 col-span-2 gap-2 sm:grid-cols-3">
        <div class="p-4 bg-white border border-gray-200 rounded-lg">
          <p class="text-gray-500">Total Produk</p>
          <p class="text-xl font-bold"><?= $totalProduk ?></p>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-lg">
          <p class="text-gray-500">Total Stok</p>
          <p class="text-xl font-bold"><?= $totalStok ?></p>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-lg">
          <p class="text-gray-500">Rata-rata Harga</p>
          <p class="text-xl font-bold">Rp <?= number_format($avgHarga, 0, ',', '.') ?></p>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($store): ?>
    <!-- Toggle Tampilan -->
    <div class="flex items-center gap-2 mb-4">
      <label class="text-sm font-medium">Tampilan:</label>
      <button data-view="table" onclick="toggleView(this)" class="view-toggle active">
        Tabel
      </button>
      <button data-view="grid" onclick="toggleView(this)" class="view-toggle">
        Kartu
      </button>
    </div>

    <div class="flex items-center gap-2 mb-2">
      <label class="text-sm font-medium">Urutkan:</label>
      <select class="px-2 py-1 text-sm border border-gray-200 rounded" onchange="location.href='?id=<?= $store_id ?>&sort=' + this.value">
        <option value="newest" <?= ($_GET['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Terbaru</option>
        <option value="price_asc" <?= ($_GET['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Harga Termurah</option>
        <option value="price_desc" <?= ($_GET['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Harga Termahal</option>
        <option value="stock_desc" <?= ($_GET['sort'] ?? '') == 'stock_desc' ? 'selected' : '' ?>>Stok Terbanyak</option>
        <option value="stock_asc" <?= ($_GET['sort'] ?? '') == 'stock_asc' ? 'selected' : '' ?>>Stok Tersedikit</option>
      </select>
    </div>


    <!-- Tampilan Tabel -->
    <?php if (!empty($products)) : ?>
      <div id="tableView" class="mb-6 overflow-x-auto border border-gray-200 rounded custom-scroll">
        <table class="min-w-full text-sm text-left">
          <thead class="text-gray-700 bg-gray-200">
            <tr>
              <th class="px-4 py-3">Nama</th>
              <th class="px-4 py-3">Gambar</th>
              <th class="px-4 py-3">Deskripsi</th>
              <th class="px-4 py-3">Harga</th>
              <th class="px-4 py-3">Stok</th>
              <th class="px-4 py-3">Terjual</th>
              <th class="px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php foreach ($products as $product) : ?>
              <tr class="border-gray-200">
                <td class="px-4 my-2">
                  <a href="../product_detail?id=<?= $product['id'] ?>" class="text-sm hover:underline line-clamp-3"><?= htmlspecialchars($product['name']) ?></a>
                </td>
                <td class="px-4 py-2">
                  <img src="<?= !empty($product['image']) ? '../../uploads/' . htmlspecialchars($product['image']) : '../../uploads/default_product.jpg' ?>" class="object-cover w-16 h-16 border border-gray-200 rounded">
                </td>
                <td class="px-4 py-2">
                  <div class="line-clamp-3"><?= htmlspecialchars($product['description']) ?></div>
                </td>
                <td class="px-4 py-2 truncate">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                <td class="px-4 py-2"><?= $product['stock'] ?></td>
                <td class="px-4 py-2"><?= $product['sold_count'] ?></td>
                <td class="px-4 py-2 space-x-2 truncate">
                  <?php if ($product['user_id'] == $session_user_id) : ?>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="text-lime-600 hover:underline">Ubah</a>
                    <a href="../../controllers/store/delete_product.php?id=<?= $product['id'] ?>"
                      onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"
                      class="text-red-600 hover:underline">
                      Hapus
                    </a>
                  <?php else : ?>
                    <a href="../product_detail?id=<?= $product['id'] ?>" class="text-lime-600 hover:underline">Lihat detail</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Tampilan Grid -->
      <div id="gridView" class="grid hidden grid-cols-2 gap-4 mb-6 sm:grid-cols-3 md:grid-cols-4">
        <?php foreach ($products as $product) : ?>
          <div class="overflow-hidden transition bg-white rounded-lg shadow hover:shadow-md">
            <img src="<?= !empty($product['image']) ? '../../uploads/' . htmlspecialchars($product['image']) : '../../uploads/default_product.png' ?>" class="object-contain w-full border border-gray-100 aspect-square">
            <div class="flex flex-col p-2 mb-1 space-y-1">
              <h3 class="text-sm md:text-base font-medium text-gray-900 leading-snug line-clamp-2 h-[2.5rem] md:h-[3rem]">
                <a href="../product_detail?id=<?= $product['id'] ?>" class="hover:underline"><?= htmlspecialchars($product['name']) ?></a>
              </h3>
              <div class="flex items-center justify-between">
                <p class="text-lg font-semibold truncate text-lime-600">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                <p class="text-xs text-gray-500 truncate"><?= formatTerjual($product['sold_count']) ?? 0 ?> terjual</p>
              </div>
              <p class="h-8 text-xs text-gray-600 line-clamp-2"><?= htmlspecialchars($product['description']) ?></p>
              <p class="text-xs text-gray-500">Stok: <?= $product['stock'] ?></p>
              <div class="flex justify-end gap-2">
                <?php if ($product['user_id'] == $session_user_id) : ?>
                  <a href="edit_product.php?id=<?= $product['id'] ?>" class="text-sm text-lime-600 hover:underline">Ubah</a>
                  <a href="../../controllers/store/delete_product.php?id=<?= $product['id'] ?>"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"
                    class="text-sm text-red-600 hover:underline">Hapus</a>
                <?php else : ?>
                  <a href="../product_detail?id=<?= $product['id'] ?>" class="text-sm text-lime-600 hover:underline">Lihat detail</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p class="p-4 text-gray-600">Belum ada produk yang ditambahkan.</p>
    <?php endif; ?>
  <?php endif; ?>
</div>
<script>
  function toggleView(button) {
    const view = button.getAttribute('data-view');
    const buttons = document.querySelectorAll('.view-toggle');
    buttons.forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');

    document.getElementById('tableView').classList.toggle('hidden', view !== 'table');
    document.getElementById('gridView').classList.toggle('hidden', view !== 'grid');
  }
</script>


<?php
$content = ob_get_clean();
include '../../layout.php';
?>
