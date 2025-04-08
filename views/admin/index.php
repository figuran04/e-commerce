<?php
require_once '../../config/init.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
//   header("Location: ../login");
//   exit;
// }

$pageTitle = "Admin Dashboard";
ob_start();

// Ambil data produk
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

// Ambil data kategori
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Ambil data pengguna
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

// Cek status hasil operasi dari query string
$status = isset($_GET['status']) ? $_GET['status'] : null;
?>

<div class="flex justify-between items-center mb-6">
  <div class="flex flex-col">
    <h1 class="text-3xl font-bold"><?= $pageTitle; ?></h1>
    <!-- <p><?= htmlspecialchars($_SESSION['user_name']); ?></p> -->
  </div>
  <div class="flex space-x-4">
    <a href="../home" class="text-gray-700 hover:text-lime-600">Beranda</a>
    <a href="../../controllers/auth/logout_handler.php" class="text-red-500 hover:underline">Logout</a>
  </div>
</div>

<div x-data="{
  tab: 'products',
  searchProduct: '',
  searchCategory: '',
  searchUser: ''
}" class="space-y-6">


  <!-- Notifikasi -->
  <?php if ($status === "success"): ?>
    <div class="p-4 text-green-700 bg-green-100 border border-green-300 rounded">Operasi berhasil!</div>
  <?php elseif ($status === "error"): ?>
    <div class="p-4 text-red-700 bg-red-100 border border-red-300 rounded">Terjadi kesalahan, coba lagi.</div>
  <?php endif; ?>

  <!-- Tabs -->
  <div class="flex space-x-4 pb-2">
    <button @click="tab = 'products'" :class="tab === 'products' ? 'border-b-2 border-lime-500 text-lime-600' : 'text-gray-600'" class="px-4 py-2 font-semibold">Products</button>
    <button @click="tab = 'categories'" :class="tab === 'categories' ? 'border-b-2 border-lime-500 text-lime-600' : 'text-gray-600'" class="px-4 py-2 font-semibold">Categories</button>
    <button @click="tab = 'users'" :class="tab === 'users' ? 'border-b-2 border-lime-500 text-lime-600' : 'text-gray-600'" class="px-4 py-2 font-semibold">Users</button>
  </div>

  <!-- Products Tab -->
  <section x-show="tab === 'products'" x-transition>
    <h2 class="text-xl font-semibold mb-2">Manage Products</h2>

    <div class="relative mb-4">
      <input x-model="searchProduct" placeholder="Search products..." class="w-full p-2 pl-10 border rounded" />
      <span class="absolute left-3 top-2.5 text-gray-400"><i class="ph-bold ph-magnifying-glass"></i></span>
    </div>

    <table class="w-full border bg-white shadow">
      <thead class="bg-gray-200">
        <tr>
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Name</th>
          <th class="border px-4 py-2">Description</th>
          <th class="border px-4 py-2">Price</th>
          <th class="border px-4 py-2">Stock</th>
          <th class="border px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_products->fetch_assoc()): ?>
          <tr
            x-show="`${'<?= strtolower($row['name']) ?>'} ${'<?= strtolower($row['description']) ?>'}`.includes(searchProduct.toLowerCase())"
            class="hover:bg-gray-50">
            <td class="border px-4 py-2"><?= $row['id']; ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['name']); ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['description']); ?></td>
            <td class="border px-4 py-2"><?= number_format($row['price'], 0, ',', '.'); ?></td>
            <td class="border px-4 py-2"><?= $row['stock']; ?></td>
            <td class="border px-4 py-2">
              <a href="edit_product.php?id=<?= $row['id']; ?>" class="text-lime-500">Edit</a> |
              <a href="../../controllers/admin/delete_product.php?id=<?= $row['id']; ?>" class="text-red-500" onclick="return confirm('Hapus produk ini?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <!-- Categories Tab -->
  <section x-show="tab === 'categories'" x-transition>
    <h2 class="text-xl font-semibold mb-2">Manage Categories</h2>
    <div class="relative mb-4">
      <input x-model="searchCategory" placeholder="Search categories..." class="w-full p-2 pl-10 border rounded" />
      <span class="absolute left-3 top-2.5 text-gray-400"><i class="ph-bold ph-magnifying-glass"></i></span>
    </div>
    <form action="../../controllers/admin/add_category.php" method="POST" class="flex space-x-2 mb-4">
      <input type="hidden" name="action" value="add">
      <input name="category_name" placeholder="New Category" class="border px-3 py-2 w-1/3 rounded" required>
      <button type="submit" class="bg-lime-600 text-white px-4 py-2 rounded">Add</button>
    </form>
    <table class="w-full border bg-white shadow">
      <thead class="bg-gray-200">
        <tr>
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Name</th>
          <th class="border px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_categories->fetch_assoc()): ?>
          <tr
            x-show="`${'<?= strtolower($row['name']) ?>'}`.includes(searchCategory.toLowerCase())">
            <td class="border px-4 py-2"><?= $row['id']; ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['name']); ?></td>
            <td class="border px-4 py-2">
              <a href="edit_category.php?id=<?= $row['id']; ?>" class="text-lime-500">Edit</a> |
              <a href="../../controllers/admin/delete_category.php?id=<?= $row['id']; ?>" class="text-red-500" onclick="return confirm('Hapus kategori ini?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <!-- Users Tab -->
  <section x-show="tab === 'users'" x-transition>
    <h2 class="text-xl font-semibold mb-2">Manage Users</h2>
    <div class="relative mb-4">
      <input x-model="searchUser" placeholder="Search users..." class="w-full p-2 pl-10 border rounded" />
      <span class="absolute left-3 top-2.5 text-gray-400"><i class="ph-bold ph-magnifying-glass"></i></span>
    </div>
    <table class="w-full border bg-white shadow">
      <thead class="bg-gray-200">
        <tr>
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Name</th>
          <th class="border px-4 py-2">Email</th>
          <th class="border px-4 py-2">Role</th>
          <th class="border px-4 py-2">Status</th>
          <th class="border px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_users->fetch_assoc()): ?>
          <tr
            x-show="`${'<?= strtolower($row['name'] . ' ' . $row['email']) ?>'}`.includes(searchUser.toLowerCase())">
            <td class="border px-4 py-2"><?= $row['id']; ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['name']); ?></td>
            <td class="border px-4 py-2"><?= htmlspecialchars($row['email']); ?></td>
            <td class="border px-4 py-2"><?= $row['role']; ?></td>
            <td class="border px-4 py-2"><?= $row['status']; ?></td>
            <td class="border px-4 py-2 space-x-2">
              <a href="../../controllers/admin/toggle_role.php?id=<?= $row['id']; ?>" class="text-yellow-600">Toggle Role</a>
              <a href="../../controllers/admin/block_user.php?id=<?= $row['id']; ?>" class="text-purple-600">
                <?= $row['status'] === 'active' ? 'Block' : 'Activate'; ?>
              </a>
              <a href="../../controllers/admin/delete_user.php?id=<?= $row['id']; ?>" class="text-red-500" onclick="return confirm('Hapus user ini?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>

</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
