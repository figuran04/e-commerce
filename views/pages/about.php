<?php
require '../../config/init.php';
$pageTitle = "Tentang Kami";
ob_start();
?>

<section class="space-y-6">
  <h1 class="text-2xl font-bold">Tentang Kami</h1>
  <p class="text-gray-700">Kami adalah platform e-commerce yang mendukung UMKM dan penjual lokal.</p>

  <h2 class="text-xl font-semibold mt-4" id="karir">Karir</h2>
  <p class="text-gray-700">Bergabunglah dengan tim kami dan jadilah bagian dari perubahan digital!</p>

  <h2 class="text-xl font-semibold mt-4" id="blog">Blog</h2>
  <p class="text-gray-700">Kunjungi blog kami untuk informasi terbaru seputar e-commerce dan tips jualan.</p>

  <h2 class="text-xl font-semibold mt-4" id="kontak">Kontak</h2>
  <p class="text-gray-700">Hubungi kami melalui email: <a href="mailto:support@example.com" class="text-lime-600 hover:underline">support@example.com</a></p>
</section>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
