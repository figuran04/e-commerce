<?php
require '../../config/init.php';
$pageTitle = "Keranjang";
include '../../controllers/cart/cart_handler.php';

ob_start();
$groupedByStore = [];
if (!empty($cart_data['cartItems'])) {
  foreach ($cart_data['cartItems'] as $item) {
    $groupedByStore[$item['store_name']][] = $item;
  }
}
?>


<div class="space-y-6">
  <h2 class="flex items-center gap-2 text-xl font-bold"><i class="ph-fill ph-shopping-cart"></i> Keranjang Belanja</h2>
  <?php include '../partials/alerts.php'; ?>
  <?php if (!empty($cart_data['cartItems'])) : ?>
    <form id="cartForm" method="POST" action="../checkout/index.php">
      <input type="hidden" name="action" value="checkout_selected">

      <div class="text-right w-full mb-2">
        <button type="button" id="deleteSelectedBtn" onclick="confirmDeleteSelected()" class="hidden text-red-600 hover:underline">
          <i class="ph ph-trash"></i> Hapus
        </button>
      </div>

      <?php foreach ($groupedByStore as $storeName => $items) : ?>
        <div class="p-4 mb-6 border border-gray-200 rounded-lg bg-white">
          <div class="flex items-center gap-2 mb-3">
            <input type="checkbox" class="w-4 h-4 store-checkbox" data-store="<?= htmlspecialchars($storeName) ?>" id="store-<?= md5($storeName) ?>">
            <label for="store-<?= md5($storeName) ?>" class="text-lg font-semibold">
              <a href="../store/?id=<?= $items[0]['store_id'] ?>" class="hover:underline">
                <?= htmlspecialchars($storeName) ?>
              </a>
            </label>
          </div>

          <div class="overflow-x-auto custom-scroll">
            <table class="min-w-full text-sm text-left">
              <thead class="text-gray-700 bg-gray-100">
                <tr>
                  <th class="p-3"></th>
                  <th class="p-3">Nama</th>
                  <th class="p-3">Gambar</th>
                  <th class="p-3">Harga</th>
                  <th class="p-3">Jumlah</th>
                  <th class="p-3">Subtotal</th>
                  <th class="p-3">Stok</th>
                  <th class="p-3">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                <?php foreach ($items as $cart) : ?>
                  <?php $subtotal = $cart['price'] * $cart['quantity']; ?>
                  <tr>
                    <td class="p-3">
                      <input
                        type="checkbox"
                        name="selected_items[]"
                        value="<?= $cart['cart_id'] ?>"
                        class="w-4 h-4 item-checkbox"
                        data-store="<?= htmlspecialchars($storeName) ?>"
                        data-subtotal="<?= $subtotal ?>"
                        <?= ($cart['stock'] == 0 || $cart['quantity'] > $cart['stock']) ? 'disabled' : '' ?>>
                    </td>
                    <td class="p-3 min-w-40">
                      <a href="../product_detail?id=<?= $cart['product_id'] ?>" class="font-medium hover:underline line-clamp-3"><?= htmlspecialchars($cart['name']) ?></a>
                    </td>
                    <td class="p-3">
                      <img src="../../uploads/<?= $cart['image'] ?>" class="object-cover rounded w-14 h-14">
                    </td>
                    <td class="p-3">Rp<?= number_format($cart['price'], 0, ',', '.') ?></td>
                    <td class="p-3">
                      <input
                        type="number"
                        value="<?= min($cart['quantity'], $cart['stock']) ?>"
                        min="1"
                        max="<?= $cart['stock'] ?>"
                        class="w-16 text-center border border-gray-200 rounded quantity-input"
                        data-cart-id="<?= $cart['cart_id'] ?>"
                        data-price="<?= $cart['price'] ?>"
                        onchange="updateCart(this)"
                        <?= $cart['stock'] == 0 ? 'disabled' : '' ?>>
                    </td>
                    <td class="p-3 subtotal" id="subtotal-<?= $cart['cart_id'] ?>">
                      Rp<?= number_format($subtotal, 0, ',', '.') ?>
                    </td>

                    <td class="p-3 truncate text-sm <?= $cart['stock'] < $cart['quantity'] ? 'text-red-600' : 'text-gray-600' ?>">
                      <?= $cart['stock'] ?>
                      <?php if ($cart['stock'] == 0): ?>
                        <span class="text-xs text-red-600 whitespace-nowrap ml-2">Stok habis! Silakan hapus produk ini.</spam>
                        <?php elseif ($cart['quantity'] > $cart['stock']): ?>
                          <span class="text-xs text-red-600 whitespace-nowrap ml-2">Stok tidak mencukupi! Maksimal <?= $cart['stock'] ?>.</span>
                        <?php elseif ($cart['stock'] <= 5): ?>
                          <span class="text-xs text-yellow-600 whitespace-nowrap ml-2">Stok hampir habis</span>
                        <?php endif; ?>
                    </td>

                    <td class="p-3 text-sm">
                      <a href="javascript:void(0)" onclick="confirmRemove(<?= $cart['cart_id'] ?>)" class="text-red-600 hover:underline">
                        Hapus
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="flex flex-col items-center justify-between gap-2 mt-6 md:flex-row">
        <div class="flex gap-3 justify-between w-full">
          <div class="flex items-center gap-2">
            <input type="checkbox" id="selectAll" class="w-4 h-4">
            <label for="selectAll" class="font-medium">Semua</label>
          </div>
          <div class="flex gap-2">
            <div class="flex items-center gap-1">
              <p class="text-sm font-semibold">Total:</p>
              <p id="selectedTotal" class="font-bold text-lime-700">Rp0</p>
            </div>
            <!-- <a href="javascript:void(0)" onclick="confirmClearCart()" class="text-red-600 hover:underline text-center">
            <i class="ph ph-trash"></i> Kosongkan Keranjang
          </a> -->

            <button type="submit" class="px-6 py-2 text-sm text-white rounded bg-lime-600 hover:bg-lime-700 text-center">
              <i class="ph-bold ph-check"></i> Checkout
            </button>
          </div>
        </div>
      </div>
    </form>

    <form id="deleteSelectedForm" method="POST" action="../../controllers/cart/delete_selected.php" class="hidden">
      <input type="hidden" name="selected_ids" id="selectedDeleteIds">
    </form>

  <?php else : ?>
    <div class="py-10 text-center text-gray-500">
      <i class="mb-2 text-5xl ph ph-shopping-cart"></i>
      <p class="text-lg font-medium">Keranjang belanja Anda kosong.</p>
      <a href="../products" class="inline-block px-4 py-2 mt-4 text-white rounded bg-lime-600 hover:bg-lime-700">
        Mulai Belanja
      </a>
    </div>
  <?php endif; ?>
</div>

<script>
  function updateCart(inputElement) {
    const cartId = inputElement.dataset.cartId;
    const quantity = parseInt(inputElement.value);
    const price = parseFloat(inputElement.dataset.price);
    const newSubtotal = price * quantity;

    // Update tampilan subtotal
    const subtotalEl = document.getElementById(`subtotal-${cartId}`);
    subtotalEl.textContent = 'Rp' + newSubtotal.toLocaleString('id-ID');

    // Update data-subtotal di checkbox
    const checkbox = document.querySelector(`.item-checkbox[value="${cartId}"]`);
    if (checkbox) {
      checkbox.setAttribute('data-subtotal', newSubtotal);

      // Jika checkbox dicentang, update total juga
      if (checkbox.checked) {
        updateSelectedTotal(); // panggil ulang total
      }
    }

    // Kirim data ke server
    fetch('../../controllers/cart/update_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}&quantity=${quantity}`
      })
      .then(response => response.text())
      .then(data => {
        console.log("Update berhasil:", data);
      })
      .catch(error => {
        console.error('Gagal update:', error);
      });
  }




  function confirmRemove(cartId) {
    if (confirm('Apakah Anda yakin ingin menghapus produk dari keranjang Anda? Tindakan ini tidak dapat dibatalkan.')) {
      window.location.href = `../../controllers/cart/remove_cart.php?cart_id=${cartId}`;
    }
  }

  function confirmClearCart() {
    if (confirm('Apakah Anda yakin ingin mengosongkan keranjang Anda? Tindakan ini tidak dapat dibatalkan.')) {
      window.location.href = '../../controllers/cart/clear_cart.php';
    }
  }

  function confirmDeleteSelected() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkedBoxes.length === 0) {
      alert('Pilih setidaknya satu produk untuk dihapus.');
      return;
    }
    if (confirm('Yakin ingin menghapus produk yang Anda pilih dari keranjang?')) {
      const selectedIds = [...checkedBoxes].map(cb => cb.value);
      document.getElementById('selectedDeleteIds').value = selectedIds.join(',');
      document.getElementById('deleteSelectedForm').submit();
    }
  }

  function updateSelectedTotal() {
    let total = 0;
    document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
      const subtotal = parseFloat(cb.getAttribute('data-subtotal')) || 0;
      total += subtotal;
    });
    document.getElementById('selectedTotal').textContent = 'Rp' + total.toLocaleString('id-ID');
  }

  document.addEventListener('DOMContentLoaded', () => {
    const cartForm = document.getElementById('cartForm');
    const selectAll = document.getElementById('selectAll');
    const storeCheckboxes = document.querySelectorAll('.store-checkbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectedTotal = document.getElementById('selectedTotal');
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    function updateSelectedTotal() {
      let total = 0;
      document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
        const subtotal = parseFloat(cb.getAttribute('data-subtotal')) || 0;
        total += subtotal;
      });
      document.getElementById('selectedTotal').textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    function updateSelectAllCheckbox() {
      const allChecked = [...itemCheckboxes].every(cb => cb.checked);
      selectAll.checked = allChecked;
    }

    function toggleDeleteSelectedButton() {
      const anyChecked = document.querySelectorAll('.item-checkbox:checked').length > 0;
      deleteBtn.classList.toggle('hidden', !anyChecked);
    }

    selectAll.addEventListener('change', () => {
      const checked = selectAll.checked;
      storeCheckboxes.forEach(cb => cb.checked = checked);
      itemCheckboxes.forEach(cb => cb.checked = checked);
      updateSelectedTotal();
      updateSelectAllCheckbox();
      toggleDeleteSelectedButton();
    });

    storeCheckboxes.forEach(storeCb => {
      storeCb.addEventListener('change', () => {
        const store = storeCb.dataset.store;
        document.querySelectorAll(`.item-checkbox[data-store="${store}"]`).forEach(cb => {
          cb.checked = storeCb.checked;
        });
        updateSelectedTotal();
        updateSelectAllCheckbox();
        toggleDeleteSelectedButton();
      });
    });

    itemCheckboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        updateSelectedTotal();
        updateSelectAllCheckbox();
        toggleDeleteSelectedButton();
      });
    });

    cartForm.addEventListener('submit', function(e) {
      const checkedItems = document.querySelectorAll('.item-checkbox:checked');
      if (checkedItems.length === 0) {
        e.preventDefault();
        alert('Pilih setidaknya satu produk untuk melanjutkan ke checkout.');
      }
    });
  });
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
