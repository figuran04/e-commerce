<?php
require '../../includes/data/get_order.php';

$pageTitle = "Pesanan Berhasil";
ob_start();
?>

<h2 class="text-xl font-bold text-lime-600"><i class="ph-fill ph-check-fat"></i> Berhasil!</h2>
<p>Terima kasih telah berbelanja di Zenovaa. Detail pesanan Anda:</p>

<div class="mt-4 p-4 border rounded-lg shadow">
  <p><strong>ID Pesanan:</strong> <?= $order['id'] ?></p>
  <p><strong>Total Harga:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.'); ?></p>
  <p><strong>Status:</strong> <?= ucfirst($order['status']); ?></p>
  <p><strong>Tanggal:</strong> <?= $order['created_at']; ?></p>

  <h3 class="text-lg font-semibold mt-4"><i class="ph-fill ph-shopping-cart"></i> Daftar Produk</h3>
  <ul>
    <?php foreach ($order_items as $item) : ?>
      <li><?= $item['name']; ?> (<?= $item['quantity']; ?>x) - Rp<?= number_format($item['price'], 0, ',', '.'); ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="mt-4 flex">
  <a href="../home" class="px-4 py-2 rounded hidden md:block"><i class="ph-fill ph-house"></i> Beranda</a>
  <a href="../../controllers/orders/fetch_orders.php" class="bg-lime-600 text-white px-4 py-2 rounded"><i class="ph-fill ph-scroll"></i> Riwayat Pesanan</a>
</div>


<?php
$content = ob_get_clean();
include '../../layout.php';
?>
