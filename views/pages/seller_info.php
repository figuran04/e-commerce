<?php
require '../../config/init.php';
$pageTitle = "Panduan untuk Penjual";
ob_start();
?>

<section class="space-y-6">
  <h1 class="text-2xl font-bold">Panduan untuk Penjual</h1>
  <div class="space-y-4">
    <details class="border p-4 border-gray-300 rounded-lg">
      <summary class="font-medium cursor-pointer" id="cara-jual">Cara Berjualan</summary>
      <p class="mt-2 text-sm text-gray-700">Registrasi sebagai penjual, unggah produk, dan mulai berjualan sekarang.</p>
    </details>
    <details class="border p-4 border-gray-300 rounded-lg">
      <summary class="font-medium cursor-pointer" id="keuntungan">Keuntungan Jualan</summary>
      <p class="mt-2 text-sm text-gray-700">Dapatkan akses ke jutaan pelanggan, laporan penjualan, dan fitur promosi.</p>
    </details>
  </div>
</section>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
