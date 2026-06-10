<?php
if (!function_exists('renderOrderActions')) {
  function renderOrderActions($order, $isBuyer = false)
  {
    ob_start();
?>
    <div class="flex gap-2 justify-end">
      <?php if (!$isBuyer && $order['status'] === 'Dipesan'): ?>
        <!-- Penjual: Tolak -->
        <form method="POST" action="../../controllers/orders/reject_order.php" onsubmit="return confirm('Tolak pesanan ini?')">
          <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
          <button type="submit" class="px-4 py-2 text-sm text-red-600 hover:text-red-700 hover:cursor-pointer rounded border border-red-600 hover:border-red-700">
            Tolak
          </button>
        </form>

        <!-- Penjual: Kirim -->
        <form method="POST" action="../../controllers/orders/mark_as_shipped.php" onsubmit="return confirm('Kirim pesanan ini?')">
          <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
          <button type="submit" class="px-4 py-2 text-sm text-white rounded bg-lime-600 hover:bg-lime-700 hover:cursor-pointer">
            Kirim
          </button>
        </form>

      <?php elseif ($isBuyer && $order['status'] === 'Dipesan'): ?>
        <!-- Pembeli: Batalkan -->
        <form method="POST" action="../../controllers/orders/buyer_cancel_order.php" onsubmit="return confirm('Batalkan pesanan ini?')">
          <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
          <button type="submit" class="px-4 py-2 text-sm text-red-600 hover:text-red-700 rounded border border-red-600 hover:border-red-700">
            Batalkan
          </button>
        </form>

      <?php elseif ($isBuyer && $order['status'] === 'Dikirim'): ?>
        <!-- Pembeli: Selesaikan -->
        <form method="POST" action="../../controllers/orders/buyer_confirm_received.php" onsubmit="return confirm('Pesanan sudah diterima?')">
          <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
          <button type="submit" class="px-4 py-2 text-sm text-white rounded bg-lime-600 hover:bg-lime-700 hover:cursor-pointer">
            Selesaikan
          </button>
        </form>
      <?php endif; ?>
    </div>
<?php
    return ob_get_clean();
  }
}
?>
