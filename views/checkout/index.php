<?php
require_once '../../config/init.php';
require_once '../../controllers/checkout/checkout_handler.php';
$pageTitle = "Checkout";

if (empty($cart_items)) {
  header("Location: ../cart?error=empty_cart");
  exit;
}

ob_start();
?>
<style type="text/tailwindcss">
  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 6px 16px;
    text-align: left;
  }

  th {
    background-color: #f4f4f4;
  }

  .total {
    @apply my-5 p-4 border border-gray-300 rounded-lg flex flex-col gap-2 md:flex-row justify-between md:items-center;
    /* margin: 20px 0;
    padding: 16px;
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between; */
  }
</style>

<h2 class="text-xl font-bold"><i class="ph-fill ph-shopping-cart"></i> Checkout</h2>

<div>
  <div class="flex overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
          <tr class="overflow-hidden">
            <td>
              <p class="line-clamp-2"><?= htmlspecialchars($item['name']); ?></p>
            </td>
            <td>Rp<?= number_format($item['price'], 0, ',', '.'); ?></td>
            <td><?= $item['quantity']; ?></td>
            <td>Rp<?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="total">
    <p class="whitespace-nowrap">Total: Rp<?= number_format($total_price, 0, ',', '.'); ?></p>
    <form action="../../controllers/orders/process_order.php" method="POST" class="w-full text-right">
      <button type="submit" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50">
        Bayar Sekarang
      </button>
    </form>
  </div>
</div>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>
