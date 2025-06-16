<?php
require_once '../../config/init.php';
require_once '../../controllers/checkout/checkout_handler.php';
$pageTitle = "Checkout";
ob_start();
?>
<h2 class="flex items-center gap-2 mb-6 text-xl font-bold"><i class="ph-fill ph-credit-card"></i> Checkout</h2>
<?php include '../partials/alerts.php'; ?>
<div class="grid grid-cols-1 gap-6 md:grid-cols-3">
  <!-- Detail Produk -->
  <div class="p-4 space-y-4 bg-white border rounded-lg md:col-span-2 border-gray-200">
    <?php if (empty($cart_items)): ?>
      <p class="text-center text-gray-500">Tidak ada produk yang dipilih untuk checkout.</p>
    <?php else: ?>
      <?php foreach ($cart_items as $item): ?>
        <div class="flex gap-4 pb-4 border-b border-gray-200">
          <img src="../../uploads/<?= htmlspecialchars($item['image'] ?? 'default.png') ?>" class="object-cover w-20 h-20 border rounded">
          <div class="flex flex-col justify-between">
            <div>
              <p class="font-semibold line-clamp-2"><?= htmlspecialchars($item['name']) ?></p>
              <?php if (!empty($item['store_name'])): ?>
                <p class="text-sm text-gray-500">Toko: <?= htmlspecialchars($item['store_name']) ?></p>
              <?php endif; ?>
            </div>
            <div class="text-sm text-gray-700">
              <p>Harga: Rp<?= number_format($item['price'], 0, ',', '.') ?></p>
              <p>Jumlah: <?= $item['quantity'] ?></p>
              <p class="font-medium text-lime-700">Subtotal: Rp<?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Ringkasan Pembayaran -->
  <div class="p-6 space-y-4 bg-white border rounded-lg border-gray-200">
    <h3 class="pb-2 text-lg font-semibold border-b border-gray-200">Ringkasan Pembayaran</h3>
    <div class="flex justify-between text-gray-600">
      <?php
      $total_quantity = 0;
      foreach ($cart_items as $item) {
        $total_quantity += $item['quantity'];
      }
      $_SESSION['total_checkout_quantity'] = $total_quantity;
      ?>
      <div class="flex flex-col gap-2">
        <p>Jenis Produk: <?= count($cart_items) ?> jenis</p>
        <p>Total Barang: <?= $_SESSION['total_checkout_quantity'];
                          unset($_SESSION['total_checkout_quantity']) ?> item</p>
      </div>
    </div>

    <div class="flex justify-between text-lg font-bold text-lime-700">
      <span>Total Bayar</span>
      <span>Rp<?= number_format($total_price, 0, ',', '.') ?></span>
    </div>

    <div>
      <label for="payment_method" class="block mb-1 text-sm font-medium text-gray-700">Pilih Metode Pembayaran</label>
      <select id="payment_method" name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded focus:outline-none focus:ring focus:ring-lime-200">
        <!-- <option value="">-- Pilih Metode --</option> -->
        <option value="bank_transfer">Transfer Bank</option>
        <option value="ewallet" selected>E-Wallet</option>
        <option value="cod">Bayar di Tempat (COD)</option>
      </select>
    </div>

    <!-- Sub Opsi: Bank Transfer -->
    <div id="bank_options" class="hidden mt-3">
      <label class="block mb-1 text-sm font-medium text-gray-700">Pilih Bank</label>
      <div class="space-y-2">
        <label class="flex items-center gap-2">
          <input type="radio" name="bank_option" value="BCA" class="text-lime-600"> BCA
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="bank_option" value="BNI" class="text-lime-600"> BNI
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="bank_option" value="Mandiri" class="text-lime-600"> Mandiri
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="bank_option" value="BRI" class="text-lime-600"> BRI
        </label>
      </div>
    </div>

    <!-- Sub Opsi: E-Wallet -->
    <div id="ewallet_options" class="hidden mt-3">
      <label class="block mb-1 text-sm font-medium text-gray-700">Pilih E-Wallet</label>
      <div class="space-y-2">
        <label class="flex items-center gap-2">
          <input type="radio" name="ewallet_option" value="OVO" class="text-lime-600" checked> OVO
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="ewallet_option" value="DANA" class="text-lime-600"> DANA
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="ewallet_option" value="GoPay" class="text-lime-600"> GoPay
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="ewallet_option" value="ShopeePay" class="text-lime-600"> ShopeePay
        </label>
      </div>
    </div>

    <form action="../../controllers/orders/process_order.php" method="POST">
      <input type="hidden" name="total_price" value="<?= $total_price ?>">
      <!-- Sisipkan payment_method nanti di backend -->
      <button type="submit" class="w-full px-4 py-2 text-white rounded bg-lime-600 hover:bg-lime-700 mt-4">
        Bayar Sekarang
      </button>
    </form>

    <a href="../cart" class="inline-block w-full mt-2 text-sm text-center text-gray-500 hover:underline"><i class="ph ph-arrow-left"></i> Kembali ke Keranjang</a>
  </div>

  <script>
    const methodSelect = document.getElementById('payment_method');
    const bankOptions = document.getElementById('bank_options');
    const ewalletOptions = document.getElementById('ewallet_options');

    methodSelect.addEventListener('change', function() {
      const selected = this.value;

      // Sembunyikan semua subopsi dulu
      bankOptions.classList.add('hidden');
      ewalletOptions.classList.add('hidden');

      if (selected === 'bank_transfer') {
        bankOptions.classList.remove('hidden');
      } else if (selected === 'ewallet') {
        ewalletOptions.classList.remove('hidden');
      }
    });
    // Trigger sekali saat halaman dimuat
    window.addEventListener('DOMContentLoaded', () => {
      methodSelect.dispatchEvent(new Event('change'));
    });
  </script>

  <?php
  $content = ob_get_clean();
  include '../../layout.php';
  ?>
