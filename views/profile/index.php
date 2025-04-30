<?php
require_once '../../controllers/profile/profile_controller.php';
$pageTitle = "Profil";
ob_start();

if (isset($_GET['id'])) {
  $profile_id = $_GET['id'];
} else {
  $profile_id = $_SESSION['user_id'];
}

$user = getUserById($profile_id);

?>

<style>
  table,
  tr,
  td,
  th {
    border: 1px solid black;
    padding: 4px;
  }
</style>

<?php if ($profile_id == $_SESSION['user_id']) : ?>
  <h2 class="text-xl font-bold"><i class="ph-fill ph-user"></i> Profil</h2>
<?php else : ?>
  <h2 class="text-xl font-bold"><i class="ph-fill ph-user"></i> <?= htmlspecialchars($user['name']); ?></h2>
<?php endif; ?>
<?php if ($profile_id == $_SESSION['user_id']) : ?>
  <div class="border-t pt-4 -mb-4 border-gray-300">
  <?php else : ?>
    <div class="border-t pt-4 border-gray-300">
    <?php endif; ?>
    <div class="flex flex-col md:flex-row w-full items-center justify-center md:justify-normal gap-2 md:gap-4">
      <div class="size-20 rounded-full overflow-hidden border-2 border-gray-300 flex justify-center items-center">
        <i class="ph-fill ph-user text-gray-300 text-6xl"></i>
      </div>
      <?php if ($profile_id == $_SESSION['user_id']) : ?>
        <p class="font-semibold">Selamat datang, <span class="text-lime-600"><?= htmlspecialchars($_SESSION['user_name']); ?>!</span></p>
      <?php else : ?>
        <p class="font-semibold">Profil Pengguna: <span class="text-lime-600"><?= htmlspecialchars($user['name']); ?></span></p>
      <?php endif; ?>
    </div>
    </div>
    <?php if ($profile_id == $_SESSION['user_id']) : ?>
      <div class="flex justify-center md:justify-start sticky top-25 md:top-14 bg-gray-50 py-4 border-b border-gray-300">
        <a href="upload_product.php" class="bg-lime-600 text-white text-center py-2 px-4 rounded">
          <i class="ph-fill ph-upload"></i> Unggah Produk Baru
        </a>
      </div>
    <?php endif; ?>

    <h2 class="text-xl font-bold"><i class="ph-fill ph-upload"></i> Produk yang Diunggah</h2>

    <h3 class="text-lg font-semibold">Daftar Produk</h3>

    <?php
    $products = getProductsByUserId($profile_id);
    if (!empty($products)) : ?>
      <div class="flex overflow-x-auto mb-4">
        <table class="min-w-full text-left border border-gray-200">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-4 py-2">Nama</th>
              <th class="px-4 py-2">Gambar</th>
              <th class="px-4 py-2">Deskripsi</th>
              <th class="px-4 py-2">Harga</th>
              <th class="px-4 py-2">Stok</th>
              <th class="px-4 py-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) : ?>
              <tr class="border-t">
                <td class="px-4 py-2"><?= htmlspecialchars($product['name']) ?></td>
                <td class="px-4 py-2">
                  <img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="" class="aspect-square w-14">
                </td>
                <td class="px-4 py-2"><?= htmlspecialchars($product['description']) ?></td>
                <td class="px-4 py-2"><?= number_format($product['price'], 2) ?></td>
                <td class="px-4 py-2"><?= $product['stock'] ?></td>
                <td class="px-4 py-2">
                  <?php if ($product['user_id'] == $_SESSION['user_id']): ?>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="underline text-blue-600">Ubah</a>
                    <a href="../../controllers/profile/delete_product.php?id=<?= $product['id'] ?>" class="underline text-red-600 ml-2">Hapus</a>
                  <?php else : ?>
                    <a href="../product_detail?id=<?= $product['id'] ?>" class="underline text-lime-600">Lihat detail</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else : ?>
      <p>
        Belum ada produk yang ditambahkan.
      </p>
    <?php endif; ?>


    <?php if ($profile_id == $_SESSION['user_id']) : ?>
      <h2 class="text-xl font-bold"><i class="ph-bold ph-sign-out"></i> Log out</h2>
      <div class="p-4 text-red-700 bg-red-100 border border-red-300 rounded">
        <button onclick="openLogoutModal()" class="text-red-500 w-min">Keluar</button>
      </div>
      <div id="logoutModal" class="fixed inset-0 bg-[rgba(0,0,0,0.5)] backdrop-blur flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-72">
          <h2 class="text-lg font-semibold mb-4">Konfirmasi Logout</h2>
          <p class="mb-4">Apakah Anda yakin ingin keluar?</p>
          <div class="flex justify-end gap-2">
            <button onclick="closeLogoutModal()" class="px-4 py-1 rounded border border-gray-300 hover:bg-gray-100">Batal</button>
            <form action="../../controllers/auth/logout_handler.php" method="post">
              <button type="submit" class="px-4 py-1 rounded bg-red-500 text-white hover:bg-red-600">Keluar</button>
            </form>
          </div>
        </div>
      </div>
      <script>
        function openLogoutModal() {
          document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
          document.getElementById('logoutModal').classList.add('hidden');
        }
      </script>
    <?php endif; ?>

    <?php
    $content = ob_get_clean();
    include '../../layout.php';
    ?>
