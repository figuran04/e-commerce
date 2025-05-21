<?php
require '../../controllers/orders/order_handler.php';

$pageTitle = "Pesanan Berhasil";
ob_start();
?>

<h2 class="text-xl font-bold text-lime-600 flex items-center gap-2">
  <i class="ph-fill ph-check-fat"></i> Berhasil!
</h2>
<p>Terima kasih telah berbelanja di Zenovaa. Detail pesanan Anda:</p>

<div class="mt-4 p-4 border rounded-lg shadow">
  <p><strong>ID Pesanan:</strong> <?= htmlspecialchars($order['id']) ?></p>
  <p><strong>Total Harga:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>
  <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
  <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>

  <h3 class="text-lg font-semibold mt-4 flex items-center gap-2">
    <i class="ph-fill ph-shopping-cart"></i> Daftar Produk
  </h3>
  <?php if (count($order_items) > 0): ?>
    <ul class="list-disc pl-5 mt-2">
      <?php foreach ($order_items as $item): ?>
        <li>
          <?= htmlspecialchars($item['name']) ?> (<?= $item['quantity'] ?>x)
          - Rp<?= number_format($item['price'], 0, ',', '.') ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="text-gray-500 italic mt-2">Tidak ada item dalam pesanan ini.</p>
  <?php endif; ?>
</div>

<div class="mt-6 flex gap-3">
  <a href="../home" class="px-4 py-2 rounded hidden md:block border border-gray-300 hover:bg-gray-100 transition">
    <i class="ph-fill ph-house"></i> Beranda
  </a>
  <a href="../../controllers/orders/fetch_orders.php" class="bg-lime-600 hover:bg-lime-700 text-white px-4 py-2 rounded transition">
    <i class="ph-fill ph-scroll"></i> Riwayat Pesanan
  </a>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
