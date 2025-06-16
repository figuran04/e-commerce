<?php
require_once '../../config/init.php';
require_once '../../models/UserModel.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../login/');
  exit;
}

$userModel = new UserModel($conn);
$user_id = $_SESSION['user_id'];
$user = $userModel->getUserById($user_id);

$pageTitle = "Edit Profil";
ob_start();
?>

<style type="text/tailwindcss">
  input, textarea, select {
    @apply rounded border border-gray-200 p-2;
  }
</style>

<h2 class="flex items-center gap-2 text-xl font-bold">
  <i class="ph-fill ph-user-circle"></i>
  Edit Profil
</h2>
<?php include '../partials/alerts.php'; ?>
<div class="p-6 space-y-6 bg-white border border-gray-200 rounded-lg">

  <form action="../../controllers/profile/edit_profile_handler.php" method="POST" class="space-y-4">
    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">

    <div>
      <label class="block mb-1 text-sm font-medium">Nama Lengkap <span class="text-red-600">*</span></label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required class="w-full px-3 py-2 border rounded">
    </div>

    <div>
      <label class="block mb-1 text-sm font-medium">Email <span class="text-red-600">*</span></label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required class="w-full px-3 py-2 border rounded">
    </div>

    <div>
      <label class="block mb-1 text-sm font-medium">Nomor HP/WA <span class="text-red-600">*</span></label>
      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" placeholder="Contoh: 081234567890" class="w-full px-3 py-2 border rounded" required>
    </div>

    <div>
      <label for="address" class="block mb-1 text-sm font-medium text-gray-700">Alamat <span class="text-red-600">*</span></label>
      <textarea
        id="address"
        name="address"
        rows="3"
        placeholder="Contoh: Jl. Merdeka No. 123, Jakarta"
        class="w-full px-3 py-2 text-sm text-gray-700 transition border border-gray-300 rounded-lg focus:ring-lime-500 focus:border-lime-500"
        required><?= htmlspecialchars($user['address'] ?? ''); ?></textarea>
    </div>

    <div>
      <label class="block mb-1 text-sm font-medium" for="bio">Bio</label>
      <textarea name="bio" rows="3" class="w-full px-3 py-2 border rounded"><?= htmlspecialchars($user['bio']); ?></textarea>
    </div>

    <div class="flex justify-end gap-2">
      <a href="javascript:history.back()" class="px-4 py-2 text-sm text-gray-600 border rounded hover:bg-gray-100">Batal</a>
      <button type="submit" class="px-4 py-2 text-sm text-white rounded bg-lime-600 hover:bg-lime-700">Simpan</button>
    </div>
  </form>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
