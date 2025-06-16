<?php
$pageTitle = "Search";
include "../../controllers/search/search_handler.php";
ob_start()
?>
<h2 class="text-xl font-bold mb-4"><i class="ph-bold ph-magnifying-glass"></i> Pencarian: "<?php echo htmlspecialchars($query_data['query']); ?>"</h2>
<?php include '../partials/alerts.php'; ?>
<?php if (!empty($query_data['query'])) : ?>
  <?php if (count($query_data['products']) > 0) : ?>
    <!-- Menampilkan produk dengan includes/product_card.php -->
    <?php echo "<div class='grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4'>"; ?>
    <?php include('../../includes/product_card.php'); ?>
    <?php echo "</div>"; ?>
  <?php else : ?>
    <p>Produk tidak ditemukan untuk "<?php echo htmlspecialchars($query_data['query']); ?>".</p>
  <?php endif; ?>
<?php else : ?>
  <p>Masukkan kata kunci dengan benar</p>
<?php endif; ?>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>
