<?php include 'header.php'; ?>

<main class="container mx-auto p-4">
  <div class="grid md:grid-cols-2 gap-8">
    <div>
      <img src="images/tas1.png" alt="Fjallraven - Foldsack" class="w-full h-auto rounded">
    </div>
    <div>
      <h1 class="text-2xl font-bold text-lime-600">Fjallraven - Foldsack</h1>
      <p class="text-sm text-gray-500">user1</p>
      <p class="text-2xl text-green-600 font-semibold mt-2">Rp109.000</p>
      <p class="mt-2 text-gray-600">Tas</p>
      <p class="text-gray-500">Stok: 4</p>

      <form action="order_success.php" method="post" class="mt-4">
        <label class="block mb-2 font-semibold">Jumlah</label>
        <input type="number" name="jumlah" value="1" min="1" max="4" class="border p-2 rounded w-20">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-2 hover:bg-blue-700">
          Masukkan ke Keranjang
        </button>
      </form>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
