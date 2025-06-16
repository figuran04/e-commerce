<?php
require_once '../../controllers/profile/profile_controller.php';
require_once '../../helpers/tampil_data.php';
$pageTitle = "Profil";
ob_start();
?>

<div class="space-y-6">
  <!-- Judul -->
  <div class="flex items-center gap-2">
    <i class="ph-fill ph-user"></i>
    <h2 class="text-xl font-bold">Profil Pengguna</h2>
  </div>

  <?php include '../partials/alerts.php'; ?>

  <!-- Section Atas -->
  <div class="flex flex-col gap-2 md:grid md:grid-cols-3">
    <!-- Info Akun -->
    <div class="row-span-2 p-6 bg-white border border-gray-200 rounded-lg">
      <div class="flex items-center gap-4 mb-4">
        <div class="flex items-center justify-center overflow-hidden border-2 border-gray-300 rounded-full size-20">
          <i class="text-5xl text-gray-300 ph-fill ph-user"></i>
        </div>
        <div>
          <h3 class="text-xl font-semibold text-lime-600"><?= htmlspecialchars($user['name'] ?? $_SESSION['user_name']); ?></h3>
          <p class="text-sm text-gray-500">ID Pengguna: #<?= $profile_id; ?></p>
        </div>
      </div>
      <div class="mt-4">
        <label class="block mb-1 text-sm font-medium text-gray-700">Bio</label>
        <div class="p-3 border border-gray-200 rounded bg-gray-50 text-sm text-gray-700 min-h-[3rem]">
          <p class="text-gray-400"><?= tampilkanData($user['bio'], 'Belum ada bio.'); ?></p>
        </div>
      </div>
      <?php if ($profile_id == $_SESSION['user_id']) : ?>
        <div class="flex gap-2 mt-4">
          <a href="./edit_profile.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border-2 rounded text-lime-600 hover:text-lime-700 border-lime-600 hover:border-lime-700">Edit Profil</a>
          <button onclick="alert('Fitur ini belum hadir')" class="px-4 py-1 text-sm font-medium text-gray-500 border-2 border-gray-500 rounded hover:text-gray-600 hover:border-gray-600">Ganti Password</button>
        </div>
      <?php endif; ?>
      <div class="mt-4 space-y-1 text-sm text-gray-700">
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'example@gmail.com'); ?></p>
        <p><strong>No HP/WA:</strong> <?= tampilkanData($user['phone']); ?></p>
        <p><strong>Alamat:</strong> <?= tampilkanData($user['address']); ?></p>
        <p><strong>Tanggal Gabung:</strong> <?= isset($user['created_at']) ? date('d F Y', strtotime($user['created_at'])) : '01 Januari 2024'; ?></p>
      </div>
    </div>

    <?php if ($profile_id == $_SESSION['user_id']) : ?>
      <div class="col-span-2 p-6 text-sm bg-white border border-gray-200 rounded-lg">
        <h3 class="mb-3 text-lg font-semibold">
          <a href="../orders/user_orders.php">Pesanan Saya</a>
        </h3>
        <ul class="grid grid-cols-4 gap-3">
          <li>
            <a href="../orders/user_orders.php?status=Dipesan" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-package"></i>
              Dipesan
            </a>
          </li>
          <li>
            <a href="../orders/user_orders.php?status=Dikirim" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-truck"></i>
              Dikirim
            </a>
          </li>
          <li>
            <a href="../orders/user_orders.php?status=Selesai" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-check-circle"></i>
              Selesai
            </a>
          </li>
          <li>
            <a href="../orders/user_orders.php?status=Dibatalkan" class="flex flex-col text-sm text-center tab-link">
              <i class="text-4xl ph ph-x-circle"></i>
              Dibatalkan
            </a>
          </li>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Log Out -->
    <?php if ($profile_id == $_SESSION['user_id']) : ?>
      <div class="col-span-2 p-6 border border-red-200 rounded-lg bg-red-50">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-red-600">
          <i class="ph-bold ph-sign-out"></i> Log Out
        </h2>
        <p class="mb-3 text-sm text-gray-600">Keluar dari akun Anda secara aman.</p>
        <button onclick="openLogoutModal()" class="px-4 py-1 text-red-500 border border-red-500 rounded hover:bg-red-100">Keluar</button>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal Logout -->
<div id="logoutModal" class="fixed inset-0 bg-[rgba(0,0,0,0.5)] backdrop-blur items-center justify-center z-50 hidden">
  <div class="p-6 bg-white rounded-lg shadow-lg w-72">
    <h2 class="mb-4 text-lg font-semibold">Konfirmasi Logout</h2>
    <p class="mb-4">Apakah Anda yakin ingin keluar?</p>
    <div class="flex justify-end gap-2">
      <button onclick="closeLogoutModal()" class="px-4 py-1 border border-gray-300 rounded hover:bg-gray-100">Batal</button>
      <form action="../../controllers/auth/logout_handler.php" method="post">
        <button type="submit" class="px-4 py-1 text-white bg-red-500 rounded hover:bg-red-600">Keluar</button>
      </form>
    </div>
  </div>
</div>

<script>
  function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
  }

  function closeLogoutModal() {
    document.getElementById('logoutModal').classList.remove('flex');
    document.getElementById('logoutModal').classList.add('hidden');
  }
</script>

<style>
  .tab-link {
    @apply px-4 py-1 text-sm font-medium text-white bg-gray-500 rounded hover:bg-gray-600;
  }
</style>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
