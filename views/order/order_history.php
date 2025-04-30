<?php
require_once '../../includes/data/get_orders.php';
$pageTitle = "Riwayat Pesanan";
ob_start();
?>

<h2 class="text-xl font-bold"><i class="ph-fill ph-scroll"></i> Riwayat Pesanan</h2>

<?php if (empty($orders)) : ?>
  <p class="text-gray-600">Anda belum memiliki pesanan.</p>
<?php else : ?>
  <div class="mt-4 space-y-4">
    <?php foreach ($orders as $order) : ?>
      <div class="p-4 border rounded-lg shadow">
        <p><strong>ID Pesanan:</strong> <?= htmlspecialchars($order['id']); ?></p>
        <p><strong>Total Harga:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.'); ?></p>
        <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($order['status'])); ?></p>
        <p><strong>Tanggal:</strong> <?= htmlspecialchars($order['created_at']); ?></p>
        <a href="order_success.php?order_id=<?= $order['id']; ?>" class="bg-lime-600 text-white px-4 py-2 rounded mt-2 inline-block"><i class="ph-fill ph-note"></i> Lihat Detail</a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
