<?php
include '../../controllers/orders/order_detail.php';
require_once '../../includes/order_actions.php';
require_once '../../helpers/status.php';
$pageTitle = "Detail Pesanan";
ob_start();
?>

<!-- <pre>
  <?= print_r($order) ?>
</pre> -->

<?php
$isBuyer = $_SESSION['user_id'] != $order['store_owner_id'];

$orderLink = $BASE_URL . '/orders/?id=' . $order['id'];
$waLink = '#'; // default

if ($isBuyer) {
  // Format nomor WhatsApp PENJUAL
  $targetPhone = preg_replace('/[^0-9]/', '', $order['store_phone'] ?? '');
  if (strpos($targetPhone, '0') === 0) {
    $targetPhone = '62' . substr($targetPhone, 1);
  }

  $message = "Halo *{$order['store_name']}*, saya *{$order['buyer_name']}* ingin menanyakan pesanan saya:\n" .
    "🛒 *ID Pesanan:* {$order['id']}\n" .
    "📦 *Status Saat Ini:* {$order['status']}\n" .
    "💵 *Total Pembayaran:* Rp " . number_format($order['total_price'], 0, ',', '.') . "\n\n";

  switch ($order['status']) {
    case 'Dipesan':
      $message .= "Pesanan saya masih dalam status *Dipesan*. Mohon infonya ya kak, apakah ada update? 🙏\n";
      break;
    case 'Dikirim':
      $message .= "Pesanan saya sudah *dikirim*, tapi saya belum menerimanya. Mohon bantu cek ya kak. 📦\n";
      break;
    case 'Selesai':
      $message .= "Saya hanya ingin memastikan bahwa pesanan *Selesai* ini sudah sesuai dan diterima dengan baik 😊\n";
      break;
    case 'Ditolak':
      $message .= "Saya ingin menanyakan alasan penolakan pesanan saya. Apakah ada kendala tertentu kak? 😕\n";
      break;
    case 'Dibatalkan':
      $message .= "Pesanan saya terlihat *dibatalkan*. Saya ingin konfirmasi apakah ini karena permintaan saya, atau ada kendala lain kak?\n";
      break;
    default:
      $message .= "Mohon bantuannya terkait status pesanan saya ya kak 🙏\n";
  }

  $message .= "\n📝 Detail pesanan:\n$orderLink\n\nTerima kasih sebelumnya kak 🙏";
  $waLink = "https://wa.me/{$targetPhone}?text=" . urlencode($message);
} else {
  // Format nomor WhatsApp PEMBELI
  $targetPhone = preg_replace('/[^0-9]/', '', $order['buyer_phone'] ?? '');
  if (strpos($targetPhone, '0') === 0) {
    $targetPhone = '62' . substr($targetPhone, 1);
  }

  $message = "Halo *{$order['buyer_name']}*, ini informasi pesanan kamu di toko kami:\n" .
    "🛒 *ID Pesanan:* {$order['id']}\n" .
    "📦 *Status:* {$order['status']}\n" .
    "💵 *Total:* Rp " . number_format($order['total_price'], 0, ',', '.') . "\n";

  switch ($order['status']) {
    case 'Dipesan':
      $message .= "\nPesanan kamu sedang kami proses. Mohon ditunggu ya 🙏\n";
      break;
    case 'Dikirim':
      $message .= "\nPesanan kamu sudah kami kirim. Silakan cek dan konfirmasi jika sudah diterima. 📬\n";
      break;
    case 'Ditolak':
      $message .= "\nMaaf, pesanan kamu tidak dapat kami proses. 😢\n";
      break;
    case 'Dibatalkan':
      $message .= "\nPesanan kamu telah dibatalkan sesuai permintaan. 🛑\n";
      break;
    case 'Selesai':
      $message .= "\nTerima kasih sudah berbelanja di toko kami! 🌟\n";
      break;
  }

  $message .= "\n📝 Lihat detail pesanan:\n$orderLink";
  $waLink = "https://wa.me/{$targetPhone}?text=" . urlencode($message);
}
?>

<h2 class="text-xl font-bold"><i class="text-xl ph-fill ph-scroll"></i> Detail Pesanan #<?= $order['id'] ?></h2>
<?php include '../partials/alerts.php' ?>

<div class="flex flex-col gap-1">
  <?php
  if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-red-600'>Anda harus login untuk melihat detail pesanan.</p>";
  } elseif ($_SESSION['user_id'] !== $order['user_id'] && $_SESSION['user_id'] !== $order['store_owner_id']) {
    echo "<p class='text-red-600'>Anda tidak memiliki akses ke pesanan ini.</p>";
  } else {
    $isBuyer = $_SESSION['user_id'] != $order['store_owner_id'];
  ?>
    <p><strong>Toko:</strong>
      <a href="../store/?id=<?= htmlspecialchars($order['store_id']) ?>" class="hover:underline text-lime-600 hover:text-lime-700">
        <?= htmlspecialchars($order['store_name']) ?> (<?= htmlspecialchars($order['store_address']) ?>)</a>
    </p>
    <p><strong>Tanggal Pesanan:</strong> <?= $order['order_date'] ?></p>
    <p><strong>Status:</strong> <?= $order['status'] ?></p>
    <p><strong>Total Harga:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>

    <h3 class="font-semibold mt-4">Daftar Produk:</h3>
    <ul class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-1">
      <?php foreach ($order['items'] as $item): ?>
        <li class="hover:bg-gray-200 transition-all rounded-lg p-2 border border-gray-200 bg-white group">
          <a href="../product_detail/?id=<?= $item['product_id'] ?>" class="flex items-center space-x-4">
            <img src="../../uploads/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="w-24 h-24 object-cover rounded-lg group-hover:scale-105 transition-all" />
            <div class="flex flex-col justify-between">
              <p class="font-medium line-clamp-2 h-12 mb-4"><?= $item['name'] ?></p>
              <p><?= $item['quantity'] ?> x Rp<?= number_format($item['price'], 0, ',', '.') ?></p>
            </div>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="text-right w-full my-2">
      <?= renderOrderActions($order, $isBuyer) ?>
    </div>
    <a href="<?= $waLink ?>" target="_blank" class="w-min whitespace-nowrap inline-flex items-center gap-2 px-3 py-2 mt-2 text-sm border text-green-600 hover:text-green-700 border-green-600 rounded hover:border-green-700">
      <i class="ph ph-whatsapp-logo"></i>
      Hubungi <?= $isBuyer ? 'Penjual' : 'Pembeli' ?>
    </a>
  <?php } ?>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
