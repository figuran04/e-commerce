<?php
$pageTitle = "Search";
include "../../controllers/search/search_handler.php";
ob_start()
?>

<h1>Search Results for: "<?php echo htmlspecialchars($data['query']); ?>"</h1>

<?php if (!empty($data['query'])) : ?>
  <?php if (count($data['products']) > 0) : ?>
    <!-- Menampilkan produk dengan includes/product_card.php -->
    <?php echo "<div class='grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4'>"; ?>
    <?php include('../../includes/product_card.php'); ?>
    <?php echo "</div>"; ?>
  <?php else : ?>
    <p>No products found for "<?php echo htmlspecialchars($data['query']); ?>".</p>
  <?php endif; ?>
<?php else : ?>
  <p>Please enter a search term.</p>
<?php endif; ?>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>