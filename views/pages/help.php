<?php
require '../../config/init.php';
$pageTitle = "Pusat Bantuan";
ob_start();
?>
<section class="space-y-6">
  <h1 class="text-2xl font-bold">Pusat Bantuan</h1>
  <div class="space-y-4">
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="cara-belanja">Cara Belanja</summary>
      <p class="text-sm text-gray-700 px-4 pb-4">Ikuti panduan ini untuk melakukan pembelian produk dengan mudah.</p>
    </details>
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="pembayaran">Metode Pembayaran</summary>
      <p class="text-sm text-gray-700 px-4 pb-4">Kami menerima transfer bank, e-wallet, dan metode lainnya.</p>
    </details>
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="pengiriman">Pengiriman</summary>
      <p class="text-sm text-gray-700 px-4 pb-4">Pengiriman dilakukan melalui jasa ekspedisi terpercaya dan terintegrasi.</p>
    </details>
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="garansi">Garansi Produk</summary>
      <p class="text-sm text-gray-700 px-4 pb-4">Produk bergaransi mengikuti kebijakan toko masing-masing.</p>
    </details>
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="resolusi">Pusat Resolusi</summary>
      <p class="text-sm text-gray-700 px-4 pb-4">Ajukan komplain atau pengembalian melalui fitur pusat resolusi.</p>
    </details>
  </div>
</section>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>
