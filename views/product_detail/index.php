<?php require '../../controllers/product_detail/product_detail_handle.php'; ?>
<?php require_once '../../helpers/format.php'; ?>
<?php $pageTitle = "Detail Produk";
ob_start(); ?>

<style type="text/tailwindcss">
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type=number] {
    -moz-appearance: textfield;
  }
</style>


<!-- ✅ Breadcrumb -->
<nav class="text-sm text-lime-600 mt-4 flex items-center gap-1">
  <a href="<?= $BASE_URL; ?>/home" class="hover:underline hover:text-lime-700">Home</a>
  <p>/</p>
  <a href="../products" class="hover:underline hover:text-lime-700">Produk</a>
  <p>/</p>
  <p class="text-gray-500 truncate"><?= htmlspecialchars($product['name']) ?></p>
</nav>

<?php include '../partials/alerts.php'; ?>

<div class="flex flex-col items-start gap-6 md:grid md:grid-cols-4">

  <!-- BAGIAN KIRI -->
  <div class="flex justify-center w-full">
    <img src="../../uploads/<?= htmlspecialchars($product['image']); ?>" class="object-cover w-64 rounded shadow-md" alt="<?= htmlspecialchars($product['name']); ?>">
  </div>

  <!-- BAGIAN TENGAH -->
  <div class="w-full md:col-span-2">
    <section class="flex flex-col space-y-2">
      <h2 class="text-xl font-semibold line-clamp-2"><?= htmlspecialchars($product['name']); ?></h2>
      <p class="text-3xl font-bold">Rp<?= number_format($product['price'], 0, ',', '.'); ?></p>
      <p class=""><?= htmlspecialchars($product['sold_count']) ?? 0; ?> terjual</p>
      <p>Kategori: <a href="../products?category=<?= htmlspecialchars($product['category_id']) ?>" class="font-semibold cursor-pointer text-lime-600 hover:text-lime-700 hover:underline"><?= htmlspecialchars($product['category']) ?: 'Tanpa Kategori'; ?></a></p>
    </section>

    <section class="flex flex-col my-4 space-y-2">
      <div class="w-full text-lg font-semibold border-t border-b border-gray-300">
        <p class="px-4 py-2 border-b-4 cursor-pointer w-min text-lime-600 border-lime-600">Detail</p>
      </div>

      <div class="space-y-1">
        <p>
          <strong>
            Etalase:
          </strong>
          <a href="../store?id=<?= htmlspecialchars($product['user_id']) ?>"
            class="font-semibold cursor-pointer text-lime-600 hover:text-lime-700 hover:underline">
            Semua Etalase
          </a>
        </p>
        <?php if (!empty($store['address'])): ?>
          <p><strong>Alamat Toko:</strong> <?= htmlspecialchars($store['address']); ?></p>
        <?php endif; ?>
      </div>


      <!-- Deskripsi Produk dengan Toggle -->
      <?php $desc = nl2br(htmlspecialchars($product['description'])); ?>
      <div id="description" class="relative overflow-hidden text-gray-700 transition-all duration-300 max-h-24">
        <div id="descriptionContent"><?= $desc ?></div>
        <div id="fadeOverlay" class="absolute bottom-0 left-0 right-0 h-6 pointer-events-none bg-gradient-to-t from-gray-50 to-transparent"></div>
      </div>

      <button id="toggleDescription" class="font-semibold text-lime-600 hover:underline w-fit">
        Lihat lebih banyak
      </button>
    </section>
  </div>

  <!-- BAGIAN KANAN -->
  <form action="../../controllers/cart/add_to_cart.php" method="POST" class="border border-gray-300 rounded-lg py-4 px-2 flex-col gap-3 w-full bg-white md:bg-transparent">
    <h3 class="text-lg font-semibold">Atur jumlah dan catatan</h3>
    <div class="flex flex-wrap">
      <div class="flex p-2 border border-gray-300 rounded-lg">
        <button id="decrease"><i class="ph-bold ph-minus"></i></button>
        <input type="hidden" name="product_id" value="<?= (int) $product['id']; ?>">
        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= (int) $product['stock']; ?>" class="text-center" data-price="<?= (int) $product['price']; ?>">
        <button id="increase"><i class="ph-bold ph-plus"></i></button>
      </div>

      <p class="mt-2 ml-2 text-sm text-gray-600">Stok: <b><?= (int) $product['stock']; ?></b></p>
    </div>

    <div class="flex items-center justify-between fle-wrap my-1">
      <p>Subtotal:</p>
      <p id="subtotal" class="text-lg font-semibold">Rp<?= number_format($product['price'], 0, ',', '.'); ?></p>
    </div>
    <div class="flex items-center w-full gap-2 flex-nowrap">
      <?php
      $storeId = $product['store_id'] ?? null;
      $sessionStoreId = $_SESSION['store_id'] ?? null;
      ?>

      <?php if (!$sessionStoreId || $storeId !== $sessionStoreId): ?>
        <!-- Tombol tambah ke keranjang -->
        <button type="submit" name="add_to_cart" class="py-2 px-3 text-lime-600 hover:bg-[rgba(101,163,13,0.2)] transition-all cursor-pointer rounded relative flex items-end">
          <i class="relative text-2xl ph ph-shopping-cart"></i>
          <i class="absolute ph-bold ph-plus right-1 top-1"></i>
        </button>

        <!-- Tombol beli langsung -->
        <button type="submit" name="buy_now" class="w-full py-2 text-center rounded cursor-pointer bg-lime-600 hover:bg-lime-700 text-gray-50">
          Beli Sekarang
        </button>

      <?php else: ?>
        <!-- Pengganti jika user adalah pemilik produk -->
        <div class="w-full text-sm text-gray-500 bg-yellow-50 border border-yellow-300 rounded px-3 py-2 text-center">
          Ini produk milikmu.
          <a href="../store/?id=<?= htmlspecialchars($sessionStoreId) ?>" class="text-lime-700 hover:underline ml-1">Kelola di toko</a>
        </div>
      <?php endif; ?>
    </div>
  </form>

</div>

<!-- <div class="p-6 text-sm bg-white border border-gray-200 rounded-lg">
  <h3 class="mb-3 text-lg font-semibold">
    Ulasan
  </h3>
  <p class=" text-gray-600">Belum ada fitur ulasan.</p>
</div> -->

<script>
  const descBox = document.getElementById('description');
  const toggleBtn = document.getElementById('toggleDescription');
  const fade = document.getElementById('fadeOverlay');

  toggleBtn.addEventListener('click', function() {
    const isExpanded = descBox.classList.contains('max-h-none');

    if (isExpanded) {
      descBox.classList.remove('max-h-none');
      descBox.classList.add('max-h-24');
      fade.classList.remove('hidden');
      toggleBtn.innerText = 'Lihat lebih banyak';
    } else {
      descBox.classList.remove('max-h-24');
      descBox.classList.add('max-h-none');
      fade.classList.add('hidden');
      toggleBtn.innerText = 'Lihat lebih sedikit';
    }
  });

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
