<?php
require_once '../../config/init.php';
require_once '../../models/StoreModel.php';
require_once '../../helpers/flash.php';

$storeModel = new StoreModel($conn);
$store = $storeModel->getStoreByUserId($_SESSION['user_id']);

if (!$store) {
  $_SESSION['error'] = "Toko tidak ditemukan. Silakan buat toko terlebih dahulu.";
  header("Location: ../store/index.php");
  exit;
}

if (!isset($_SESSION['user_id'])) {
  header('Location: ../login/');
  exit;
}

$pageTitle = "Edit Profil";
ob_start();
?>
<style type="text/tailwindcss">
  input, textarea, select {
    @apply rounded border border-gray-200 p-2;
  }
</style>

<h2 class="text-xl font-bold flex items-center gap-2">
  <i class="ph-fill ph-user-circle"></i>
  Edit Toko
</h2>
<?php include '../partials/alerts.php'; ?>
<div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
  <form action="../../controllers/store/update_store.php" method="POST" class="space-y-4">
    <input type="hidden" name="store_id" value="<?= $store['id'] ?>">

    <div>
      <label class="block mb-1 text-sm font-medium">Nama Toko <span class="text-red-600">*</span></label>
      <input type="text" name="name" value="<?= htmlspecialchars($store['name']) ?>" class="w-full px-3 py-2 border rounded" required>
    </div>

    <div>
      <label class="block mb-1 text-sm font-medium">Alamat <span class="text-red-600">*</span></label>
      <textarea name="address" class="w-full px-3 py-2 border rounded" required><?= htmlspecialchars($store['address']) ?></textarea>
    </div>

    <div>
      <label class="block mb-1 text-sm font-medium">Deskripsi</label>
      <textarea name="description" class="w-full px-3 py-2 border rounded"><?= htmlspecialchars($store['description']) ?></textarea>
    </div>
    <button type="submit" class="px-4 py-2 text-white bg-lime-600 rounded hover:bg-lime-700">Simpan</button>
  </form>
</div>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>
