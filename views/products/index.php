<?php include 'header.php'; ?>

<main class="container mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Daftar Produk</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <!-- Contoh produk -->
    <a href="detail_produk.php?id=1" class="bg-white rounded shadow p-4 hover:shadow-lg">
      <img src="images/tas1.png" alt="Fjallraven - Foldsack" class="w-full h-48 object-cover rounded">
      <h3 class="text-lg font-bold mt-2">Fjallraven - Foldsack</h3>
      <p class="text-lime-600 font-semibold">Rp109.000</p>
    </a>
    <!-- Tambahkan produk lain di sini -->
  </div>
</main>

<?php include 'footer.php'; ?>
