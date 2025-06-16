<?php
require '../../config/init.php';
$pageTitle = "Buat Toko";
ob_start();
?>

<h2 class="flex items-center gap-2 text-xl font-bold"><i class="ph-fill ph-storefront"></i> Buat Toko Baru</h2>
<?php include '../partials/alerts.php'; ?>

<div class="max-w-xl bg-white p-6 rounded-lg border-gray-200 border">
  <form action="../../controllers/store/process_create_store.php" method="POST" class="space-y-4">
    <div>
      <label class="block text-sm font-medium">Nama Toko <span class="text-red-600">*</span></label>
      <input type="text" name="name" required class="w-full p-2 border border-gray-200 rounded mt-1">
    </div>
    <div>
      <label class="block text-sm font-medium">No HP/WA <span class="text-red-600">*</span></label>
      <input type="text" name="phone" class="w-full p-2 border border-gray-200 rounded mt-1" placeholder="Contoh: 081234567890" required>
    </div>
    <div>
      <label class="block mb-1 text-sm font-medium">Alamat <span class="text-red-600">*</span></label>
      <textarea name="address" class="w-full px-3 py-2 border border-gray-200 rounded" required></textarea>
    </div>
    <div>
      <label class="block text-sm font-medium">Deskripsi</label>
      <textarea name="description" rows="4" class="w-full p-2 border border-gray-200 rounded mt-1"></textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-lime-600 text-white rounded hover:bg-lime-700">Buat Toko</button>
  </form>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
