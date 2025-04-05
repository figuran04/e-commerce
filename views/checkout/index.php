<?php
require_once '../../config/init.php';
$pageTitle = "Checkout";
include '../../includes/data/get_checkout_items.php';
// if (empty($cart_items)) {
//   header("Location: ../cart?error=empty_cart");
//   exit;
// }
ob_start();
?>
<style>
  .section {
    margin: 20px;
    padding: 20px;
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
  }

  th {
    background-color: #f4f4f4;
  }

  .total {
    margin: 20px;
    padding: 20px;
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
  }
</style>

<h1 class="text-2xl font-bold">Checkout</h1>

<div>
  <div class="section">
    <table>
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
          <tr>
            <td><?= htmlspecialchars($item['name']); ?></td>
            <td>Rp<?= number_format($item['price'], 0, ',', '.'); ?></td>
            <td><?= $item['quantity']; ?></td>
            <td>Rp<?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="total">
    <p>Total: Rp<?= number_format($total_price, 0, ',', '.'); ?></p>
    <form action="../../controllers/orders/process_order.php" method="POST">
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
