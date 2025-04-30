<?php
require '../../config/init.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$user_id = $_SESSION['user_id'];
ob_start();
?>
<style type="text/tailwindcss">
  .form_upload{
    @apply flex flex-col gap-3 w-min;
  }
  .form_upload input, textarea, select {
    @apply rounded border border-gray-500 p-2;
  }
</style>

<h2 class="text-xl font-bold">Unggah Produk</h2>

<form action="../../controllers/products/process_upload_product.php" method="POST" enctype="multipart/form-data" class="form_upload">
  <section class="flex flex-col">
    <label for="name">Nama Produk:</label>
    <input type="text" name="name" id="name" required>
  </section>

  <section class="flex flex-col">
    <label for="description">Deskripsi:</label>
    <textarea name="description" id="description" required></textarea>
  </section>

  <section class="flex flex-col">
    <label for="price">Harga:</label>
    <input type="number" name="price" id="price" required>
  </section>

  <section class="flex flex-col">
    <label for="stock">Stok:</label>
    <input type="number" name="stock" id="stock" required>
  </section>

  <section class="flex flex-col">
    <label for="category">Kategori:</label>
    <select name="category_id" id="category" required>
      <option value="">Pilih Kategori</option>
      <?php
      $result = $conn->query("SELECT id, name FROM categories");
      while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['name']}</option>";
      }
      ?>
    </select>
  </section>

  <section class="flex flex-col">
    <label for="image">Gambar Produk:</label>
    <input type="file" name="image" id="image" accept="image/*" required>
  </section>
  <button type="submit" class="bg-lime-600 hover:bg-lime-700 text-white py-2 px-4 rounded">Unggah</button>
</form>

<a href="../profile" class="text-red-500">Batal</a>

<?php
$content = ob_get_clean();

include '../../layout.php';
?>
