<?php
require '../../controllers/orders/user_orders_handler.php';
require_once '../../helpers/status.php';
require_once '../../includes/order_actions.php';
$pageTitle = "Pesanan Saya";
ob_start();
?>

<div class="space-y-6">
  <div class="flex flex-col justify-between md:items-center md:flex-row">
    <h2 class="flex items-center gap-2 text-xl font-bold text-left"><i class="ph-fill ph-package"></i>Pesanan Saya</h2>
    <a href="../orders/store_orders.php" class="text-right hover:underline hover:text-lime-700">ke pesanan toko <i class="ph ph-arrow-right"></i></a>
  </div>

  <?php include '../partials/alerts.php'; ?>
  <div class="flex gap-4 overflow-x-auto text-sm border-b border-b-gray-200 custom-scroll">
    <?php
    $statuses = ['Semua', 'Dipesan', 'Dikirim', 'Selesai', 'Dibatalkan', 'Ditolak'];
    $activeStatus = $_GET['status'] ?? 'Semua';
    foreach ($statuses as $status) {
      $active = $status === $activeStatus ? 'border-b-[3px] border-lime-600 text-lime-600 font-semibold' : 'text-gray-500';
      echo "<a href='?status=$status' class='pb-2 $active'>$status</a>";
    }
    ?>
  </div>

  <div class="space-y-4">
    <?php if (!empty($order_data['orders'])): ?>
      <?php foreach ($order_data['orders'] as $order): ?>
        <?php
        // Format nomor WhatsApp penjual
        $storePhone = preg_replace('/[^0-9]/', '', $order['store_phone'] ?? '');
        if (strpos($storePhone, '0') === 0) {
          $storePhone = '62' . substr($storePhone, 1);
        }

        // Pesan WhatsApp ke penjual
        $orderLink = $BASE_URL . '/orders/?id=' . $order['id'];
        $buyerMessage = "Halo *{$order['store_name']}*, saya *{$order['user_name']}* ingin menanyakan pesanan saya:\n" .
          "🛒 *ID Pesanan:* {$order['id']}\n" .
          "📦 *Status Saat Ini:* {$order['status']}\n" .
          "💵 *Total Pembayaran:* Rp " . number_format($order['total_price'], 0, ',', '.') . "\n\n";

        // Tambahkan keterangan berdasarkan status
        switch ($order['status']) {
          case 'Dipesan':
            $buyerMessage .= "Pesanan saya masih dalam status *Dipesan*. Mohon infonya ya kak, apakah ada update? 🙏\n";
            break;
          case 'Dikirim':
            $buyerMessage .= "Pesanan saya sudah *dikirim*, tapi saya belum menerimanya. Mohon bantu cek ya kak. 📦\n";
            break;
          case 'Selesai':
            $buyerMessage .= "Saya hanya ingin memastikan bahwa pesanan *Selesai* ini sudah sesuai dan diterima dengan baik 😊\n";
            break;
          case 'Ditolak':
            $buyerMessage .= "Saya ingin menanyakan alasan penolakan pesanan saya. Apakah ada kendala tertentu kak? 😕\n";
            break;
          case 'Dibatalkan':
            $buyerMessage .= "Pesanan saya terlihat *dibatalkan*. Saya ingin konfirmasi apakah ini karena permintaan saya, atau ada kendala lain kak?\n";
            break;
          default:
            $buyerMessage .= "Mohon bantuannya terkait status pesanan saya ya kak 🙏\n";
            break;
        }

        $buyerMessage .= "\n📝 Detail pesanan:\n$orderLink\n\nTerima kasih sebelumnya kak 🙏";

        $waBuyerLink = "https://wa.me/{$storePhone}?text=" . urlencode($buyerMessage);
        ?>
        <?php $statusColor = getStatusColor($order['status']); ?>
        <div class="p-4 border-[3px] rounded-lg shadow-sm border-<?= $statusColor  ?> bg-white">
          <div class="flex items-start justify-between mb-2 text-sm">
            <div>
              <p class=line-clamp-2>ID Pesanan: <a href="../orders/?id=<?= $order['id'] ?>" class="hover:underline text-lime-600 hover:text-lime-700"><?= htmlspecialchars($order['id']) ?></a></p>
              <?php if (!empty($order['store_name'])): ?>
                <p class="text-gray-500">Nama Toko:
                  <a href="../store/?id=<?= htmlspecialchars($order['store_id']) ?>" class="hover:underline text-lime-600 hover:text-lime-700">
                    <?= htmlspecialchars($order['store_name']) ?>
                  </a>
                </p>
              <?php endif; ?>

              <p class="text-gray-500">Tanggal: <?= date('d M Y, H:i', strtotime($order['order_date'])) ?></p>
            </div>

            <span class="px-2 py-1 text-xs text-gray-700 bg-<?= $statusColor ?> rounded">
              <?= htmlspecialchars($order['status']) ?>
            </span>
          </div>

          <div class="grid gap-4 md:grid-cols-2">
            <?php foreach ($order['items'] as $item): ?>
              <a href="../product_detail/?id=<?= $item['product_id'] ?>" class="transition-all rounded-lg hover:bg-gray-100 group">
                <div class="flex items-center gap-3 p-1">
                  <img src="../../uploads/<?= htmlspecialchars($item['image']) ?>" class="object-cover w-16 h-16 rounded-lg group-hover:scale-105">
                  <div>
                    <p class="font-medium line-clamp-2"><?= htmlspecialchars($item['name']) ?></p>
                    <p class="text-sm text-gray-500">
                      Qty: <?= htmlspecialchars($item['quantity']) ?> x Rp<?= number_format($item['price'], 0, ',', '.') ?>
                    </p>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>

          <div class="flex items-center justify-between mt-4">
            <p class="font-semibold text-right text-lime-600">
              Total: Rp<?= number_format($order['total_price'], 0, ',', '.') ?>
            </p>

            <?= renderOrderActions($order, true) ?>
          </div>
          <a href="<?= $waBuyerLink ?>" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 mt-2 text-sm border text-green-600 hover:text-green-700 border-green-600 rounded hover:border-green-700">
            <i class="ph ph-whatsapp-logo"></i> Hubungi Penjual
          </a>
        </div>
      <?php endforeach; ?>

    <?php else: ?>
      <div class="py-10 text-center text-gray-500">
        <i class="mb-2 text-5xl ph ph-truck"></i>
        <p class="text-lg font-medium">Belum ada pesanan.</p>
        <a href="../products" class="inline-block px-4 py-2 mt-4 text-white rounded bg-lime-600 hover:bg-lime-700">
          Belanja Sekarang
        </a>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
