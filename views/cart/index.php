<?php
require '../../config/init.php';
$pageTitle = "Keranjang";
include '../../controllers/cart/cart_handler.php'; // Memanggil controller

ob_start();
?>

<h2 class="text-xl font-bold"><i class="ph-fill ph-shopping-cart"></i> Keranjang Belanja</h2>

<?php if (count($data['cartItems']) > 0) : ?> <!-- Ganti num_rows dengan count() -->
  <div class="flex overflow-x-auto">
    <table class="w-full mt-4 border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2">Produk</th>
          <th class="p-2">Harga</th>
          <th class="p-2">Jumlah</th>
          <th class="p-2">Total</th>
          <th class="p-2">Stock</th>
          <th class="p-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data['cartItems'] as $cart) : ?> <!-- Gunakan foreach karena data sudah berupa array -->
          <tr>
            <td class="p-2">
              <img src="../../uploads/<?= $cart['image'] ?>" alt="<?= $cart['name'] ?>" class="w-20 aspect-square object-cover">
              <a href="../product_detail?id=<?= htmlspecialchars($cart['product_id']) ?>" class="underline">
                <?= htmlspecialchars($cart['name']) ?>
              </a>
            </td>
            <td class="p-2">Rp<?= number_format($cart['price'], 0, ',', '.') ?></td>
            <td class="p-2">
              <form action="../../controllers/cart/update_cart.php" method="POST" class="flex items-center">
                <input type="hidden" name="cart_id" value="<?= $cart['cart_id'] ?>">
                <input type="number" name="quantity" value="<?= $cart['quantity'] ?>" min="1" max="<?= (int) $cart['stock'] ?>" class="w-16 border rounded p-1 text-center">
                <button type="submit" class="ml-2 text-lime-600">Update</button>
              </form>
            </td>
            <td class="p-2">Rp<?= number_format($cart['price'] * $cart['quantity'], 0, ',', '.') ?></td>
            <td class="p-2">
              <span class="text-sm text-gray-500">Stock: <?= $cart['stock'] ?></span>
            </td>
            <td class="p-2">
              <a href="../../controllers/cart/remove_cart.php?cart_id=<?= $cart['cart_id'] ?>" class="text-red-500">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <a href="../checkout" class="block text-center bg-lime-600 text-white px-4 py-2 rounded mt-4">Lanjut ke Checkout</a>
<?php else : ?>
  <p class="text-gray-500">Keranjang belanja Anda kosong.</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
