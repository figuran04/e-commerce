<?php
require '../../controllers/product_detail/product_detail_handle.php';

$pageTitle = "Detail Produk";
ob_start();
?>

<style type="text/tailwindcss">
  .right {
    @apply border border-gray-300 rounded-lg py-4 px-2 flex flex-col gap-3 w-full;
  }

  /* Untuk Chrome, Safari, Edge, Opera */
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Untuk Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }

</style>

<div class="flex flex-col md:grid md:grid-cols-4 gap-6 mt-6 items-start">

  <!-- BAGIAN KIRI -->
  <div class="w-full flex justify-center">
    <img src="../../uploads/<?= htmlspecialchars($product['image']); ?>" class="w-64 object-cover rounded shadow-md" alt="<?= htmlspecialchars($product['name']); ?>">
  </div>

  <!-- BAGIAN TENGAH -->
  <div class="w-full md:col-span-2">
    <section class="flex flex-col space-y-2">
      <h2 class="text-2xl font-semibold line-clamp-2"><?= htmlspecialchars($product['name']); ?></h2>
      <p class="font-bold text-3xl">Rp<?= number_format($product['price'], 0, ',', '.'); ?></p>
      <p>Kategori: <a href="../products?category=<?= htmlspecialchars($product['category_id']) ?>" class="text-lime-600 hover:text-lime-700 cursor-pointer font-semibold hover:underline"><?= htmlspecialchars($product['category']) ?: 'Tanpa Kategori'; ?></a></p>
    </section>

    <section class="flex flex-col space-y-2 my-4">
      <div class="font-semibold text-lg w-full border-t border-b border-gray-300">
        <p class="w-min py-2 px-4 text-lime-600 border-b-4 border-lime-600 cursor-pointer">Detail</p>
      </div>

      <p>Etalase: <a href="../profile?id=<?= htmlspecialchars($product['user_id']) ?>" class="text-lime-600 hover:text-lime-700 cursor-pointer font-semibold hover:underline">Semua Etalase</a></p>
      <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
    </section>
  </div>

  <!-- BAGIAN KANAN -->
  <form action="../../controllers/cart/add_to_cart.php" method="POST" class="right">
    <h3 class="font-semibold text-lg">Atur jumlah dan catatan</h3>
    <div class="flex flex-wrap">
      <div class="flex p-2 border border-gray-300 rounded-lg">
        <button id="decrease"><i class="ph-bold ph-minus"></i></button>
        <input type="hidden" name="product_id" value="<?= (int) $product['id']; ?>">
        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= (int) $product['stock']; ?>" class="text-center" data-price="<?= (int) $product['price']; ?>">
        <button id="increase"><i class="ph-bold ph-plus"></i></button>
      </div>

      <p class="ml-2 mt-2 text-sm text-gray-600">Stok: <b><?= (int) $product['stock']; ?></b></p>
    </div>

    <div class="flex justify-between items-center fle-wrap">
      <p>Subtotal:</p>
      <p id="subtotal" class="text-lg font-semibold">Rp<?= number_format($product['price'], 0, ',', '.'); ?></p>
    </div>

    <button type="submit" class="px-4 py-2 bg-lime-600 hover:bg-lime-700 text-gray-50 cursor-pointer rounded"><i class="ph-bold ph-plus"></i> Keranjang</button>
  </form>

</div>

<script>
  function updateSubtotal() {
    const quantityInput = document.getElementById('quantity');
    const subtotalElement = document.getElementById('subtotal');
    const pricePerItem = parseInt(quantityInput.dataset.price);

    const quantity = parseInt(quantityInput.value) || 1;
    const subtotal = pricePerItem * quantity;

    subtotalElement.innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
  }

  document.addEventListener("DOMContentLoaded", function() {
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    const maxStock = parseInt(quantityInput.getAttribute('max'));

    quantityInput.addEventListener('input', updateSubtotal);

    decreaseBtn.addEventListener('click', function(e) {
      e.preventDefault();
      let currentValue = parseInt(quantityInput.value);
      if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        updateSubtotal();
      }
    });

    increaseBtn.addEventListener('click', function(e) {
      e.preventDefault();
      let currentValue = parseInt(quantityInput.value);
      if (currentValue < maxStock) {
        quantityInput.value = currentValue + 1;
        updateSubtotal();
      }
    });

    updateSubtotal();
  });
</script>


<?php
$content = ob_get_clean();
include '../../layout.php';
?>
