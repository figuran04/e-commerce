<?php
require '../../config/init.php';
$pageTitle = "Syarat & Kebijakan";
ob_start();
?>

<section class="space-y-6">
  <h1 class="text-2xl font-bold">Syarat & Kebijakan</h1>
  <div class="space-y-4">
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="syarat">Syarat dan Ketentuan</summary>
      <p class="mt-2 text-sm text-gray-700 px-4 pb-4">Dengan menggunakan situs ini, Anda setuju dengan syarat layanan kami.</p>
    </details>
    <details class="border border-gray-200 rounded-lg">
      <summary class="font-medium cursor-pointer p-4" id="privasi">Kebijakan Privasi</summary>
      <p class="mt-2 text-sm text-gray-700 px-4 pb-4">Kami melindungi data pribadi pengguna sesuai dengan regulasi yang berlaku.</p>
    </details>
  </div>
</section>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
