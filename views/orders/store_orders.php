<?php
include '../../controllers/orders/store_orders_handler.php';
require_once '../../includes/order_actions.php';
require_once '../../helpers/status.php';
$pageTitle = "Pesanan Masuk Toko";
ob_start();
?>

<div class="space-y-6">
  <div class="flex flex-col justify-between md:items-center md:flex-row">
    <h2 class="flex items-center gap-2 text-xl font-bold text-left"><i class="ph-fill ph-package"></i>Pesanan Masuk</h2>
    <a href="../orders/user_orders.php" class="text-right hover:underline hover:text-lime-700">ke pesanan saya <i class="ph ph-arrow-right"></i></a>
  </div>

  <?php include '../partials/alerts.php'; ?>

  <!-- Tab Navigasi -->
  <div class="flex gap-4 overflow-x-auto text-sm border-b border-b-gray-300 custom-scroll">
    <?php
    $statuses = ['Semua', 'Dipesan', 'Dikirim', 'Selesai', 'Dibatalkan', 'Ditolak'];
    $activeStatus = $_GET['status'] ?? 'Semua';
    foreach ($statuses as $status) {
      $active = $status === $activeStatus ? 'border-b-[3px] border-lime-600 text-lime-600 font-semibold' : 'text-gray-500';
      echo "<a href='?status=$status' class='pb-2 $active'>$status</a>";
    }
    ?>
  </div>

  <!-- Daftar Pesanan -->
  <div class="space-y-4">
    <?php if (!empty($store_order_data['orders'])): ?>
      <?php foreach ($store_order_data['orders'] as $order): ?>
        <?php
        // Format nomor telepon pembeli
        $phone = preg_replace('/[^0-9]/', '', $order['buyer_phone'] ?? '');
        if (strpos($phone, '0') === 0) {
          $phone = '62' . substr($phone, 1);
        }

        // Default pesan WhatsApp
        $orderLink = $BASE_URL . '/orders/?id=' . $order['id'];
        $baseMessage = "Halo *{$order['buyer_name']}*, berikut informasi mengenai pesanan kamu di toko kami:\n" .
          "🛒 *ID Pesanan:* {$order['id']}\n" .
          "📦 *Status:* {$order['status']}\n" .
          "💵 *Total Pembayaran:* Rp " . number_format($order['total_price'], 0, ',', '.') . "\n\n";

        switch ($order['status']) {
          case 'Dipesan':
            $baseMessage .= "Pesanan kamu sudah kami terima dan sedang diproses. Mohon ditunggu ya 🙏\n";
            break;
          case 'Dikirim':
            $baseMessage .= "Pesanan kamu sudah kami kirim 📦 Silakan cek dan klik *Selesaikan* jika sudah diterima.\n";
            break;
          case 'Ditolak':
            $baseMessage .= "Mohon maaf, pesanan kamu tidak dapat kami proses saat ini 😔 Jika ada pertanyaan, silakan hubungi kami.\n";
            break;
          case 'Dibatalkan':
            $baseMessage .= "Pesanan kamu telah kami batalkan sesuai permintaan. Semoga bisa bertransaksi lagi di lain waktu 🙏\n";
            break;
          case 'Selesai':
            $baseMessage .= "Terima kasih telah menyelesaikan pesanan dan berbelanja di toko kami! 🌟 Kami tunggu pesanan selanjutnya 😊\n";
            break;
        }

        $baseMessage .= "\n📝 *Lihat detail pesanan di:*\n$orderLink";

        // Encode dan buat link
        $waLink = "https://wa.me/{$phone}?text=" . urlencode($baseMessage);
        ?>
        <?php $statusColor = getStatusColor($order['status']); ?>
        <div class="p-4 border-[3px] rounded-lg shadow-sm border-<?= $statusColor  ?> bg-white">
          <div class="flex items-start justify-between w-full mb-2 text-sm">
            <div>
              <p class="font-medium">ID Pesanan: <a href="../orders/?id=<?= $order['id'] ?>" class="text-lime-600 hover:text-lime-700"><?= $order['id'] ?></a></p>
              <p class="text-gray-500">Tanggal: <?= date('d M Y, H:i', strtotime($order['order_date'])) ?></p>
              <p class="text-gray-500">Pembeli:
                <a href="../profile/?id=<?= $order['buyer_id'] ?>" class="hover:underline text-lime-600 hover:text-lime-700">
                  <?= htmlspecialchars($order['buyer_name']) ?>
                </a>
              </p>
              <p class="text-gray-500">Alamat: <?= htmlspecialchars($order['buyer_address']) ?></p>
            </div>
            <span class="px-2 py-1 text-xs text-gray-700 bg-<?= $statusColor  ?> rounded"><?= $order['status'] ?></span>
          </div>

          <div class="grid gap-4 md:grid-cols-2">
            <?php foreach ($order['items'] as $item): ?>
              <a href="../product_detail/?id=<?= $item['product_id'] ?>" class="transition-all rounded-lg hover:bg-gray-100 group">
                <div class="flex items-center gap-3 p-1">
                  <img src="../../uploads/<?= $item['image'] ?>" class="object-cover w-16 h-16 rounded-lg group-hover:scale-105">
                  <div>
                    <p class="font-medium line-clamp-2"><?= $item['name'] ?></p>
                    <p class="text-sm text-gray-500">Qty: <?= $item['quantity'] ?> x Rp<?= number_format($item['price'], 0, ',', '.') ?></p>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
          <div class="flex items-center justify-between mt-4">
            <p class="font-semibold text-right text-lime-600">Total: Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>
            <?php if ($order['status'] === 'Dipesan'): ?>
              <div class="flex gap-2">
                <!-- Tombol Tolak -->
                <?= renderOrderActions($order, false) ?>
                <!-- <form method="POST" action="../../controllers/orders/reject_order.php" onsubmit="return confirm('Tolak pesanan ini?')">
                  <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                  <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-600 rounded hover:text-red-700 hover:cursor-pointer hover:border-red-700">
                    Tolak
                  </button>
                </form> -->
                <!-- Tombol Kirim -->
                <!-- <form method="POST" action="../../controllers/orders/mark_as_shipped.php" onsubmit="return confirm('Kirim pesanan ini?')">
                  <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                  <button type="submit" class="px-4 py-2 text-sm text-white rounded bg-lime-600 hover:bg-lime-700 hover:cursor-pointer">
                    Kirim
                  </button>
                </form> -->
              </div>
            <?php endif; ?>
          </div>
          <a href="<?= $waLink ?>" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 mt-2 text-sm border text-green-600 hover:text-green-700 border-green-600 rounded hover:border-green-700">
            <i class="ph ph-whatsapp-logo"></i> Hubungi Pembeli
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="py-10 text-center text-gray-500">
        <i class="mb-2 text-5xl ph ph-truck"></i>
        <p class="text-lg font-medium">Belum ada pesanan.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
