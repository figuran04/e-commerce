<?php
require_once '../../config/init.php';
require_once '../../controllers/admin/admin_handler.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../login");
  exit;
}

$tabConfig = [
  'products' => [
    'label' => 'Produk',
    'columns' => ['id' => 'ID', 'name' => 'Nama Produk', 'category_id' => 'ID Kategori', 'price' => 'Harga', 'stock' => 'Stok'],
    'actions' => ['edit', 'delete'],
    'allow_create' => false
  ],
  'categories' => [
    'label' => 'Kategori',
    'columns' => ['id' => 'ID', 'name' => 'Nama Kategori'],
    'actions' => ['edit', 'delete'],
    'allow_create' => true
  ],
  'users' => [
    'label' => 'Pengguna',
    'columns' => ['id' => 'ID', 'name' => 'Nama Pengguna', 'email' => 'Email', 'role' => 'Role', 'status' => 'Status'],
    'actions' => ['delete', 'toggle status', 'toggle role'],
    'allow_create' => false
  ],
];

$pageTitle = "Admin Dashboard";
ob_start();
?>

<style type="text/tailwindcss">
  input, textarea, select {
    @apply rounded border border-gray-200 p-2;
  }
</style>

<div class="flex items-center justify-between mb-6">
  <div class="flex flex-col">
    <h1 class="text-3xl font-bold"><?= $pageTitle; ?></h1>
    <!-- <p><?= htmlspecialchars($_SESSION['user_name']); ?></p> -->
  </div>
  <div class="flex space-x-4">
    <a href="../home" class="text-gray-700 hover:text-lime-600">Beranda</a>
    <a href="../../controllers/auth/logout_handler.php" class="text-red-500 hover:underline">Logout</a>
  </div>
</div>

<!-- Notifikasi -->
<?php include '../partials/alerts.php'; ?>

<div class="flex space-x-2 overflow-x-auto curstom-scroll">
  <?php foreach ($tabConfig as $key => $conf): ?>
    <a href="?tab=<?= $key ?>" class="px-4 py-2 rounded <?= $tab === $key ? 'bg-lime-600 hover:bg-lime-700 text-white' : 'bg-gray-300 text-black' ?>">
      <?= $conf['label'] ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- Form Pencarian -->
<form method="GET" class="flex space-x-2">
  <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
  <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Cari..." class="px-4 py-2 border rounded w-full">
  <button type="submit" class="px-4 py-2 text-white rounded bg-lime-600 hover:bg-lime-700">Cari</button>
</form>

<!-- Tombol Tambah -->
<?php if ($tabConfig[$tab]['allow_create']) : ?>
  <?php if ($tab === 'categories'): ?>
    <form method="POST" action="../../controllers/admin/add_category.php" class="flex flex-wrap md:flex-nowrap gap-2">
      <input type="text" name="category_name" placeholder="Nama Kategori" class="w-min md:w-full">

      <!-- opsional: pilih kategori induk -->
      <select name="parent_id">
        <option value="">Kategori Utama</option>
        <?php foreach ($rootCategories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit" class="px-2 bg-lime-600 hover:bg-lime-700 text-white rounded py-1">Tambah</button>
    </form>
  <?php endif; ?>
<?php endif; ?>

<!-- Tabel Data -->
<div class="overflow-x-auto custom-scroll">
  <?php if (is_array($admin_data)) {
    renderTable($tab, $admin_data, $tabConfig);
  } else {
    echo "<p class='text-red-600'>Data untuk tab <strong>$tab</strong> tidak tersedia.</p>";
  } ?>
</div>
</div>

<?php
function renderTable($tab, $admin_data, $tabConfig)
{
  if (!isset($tabConfig[$tab])) {
    echo "<p>Data tidak ditemukan.</p>";
    return;
  }

  if (!is_array($admin_data)) {
    echo "<p class='text-red-600'>Data tidak valid.</p>";
    return;
  }

  $config = $tabConfig[$tab];
  $columns = $config['columns'];
  $actions = $config['actions'];

  echo '<table class="w-full border-collapse table-auto">';
  echo '<thead><tr class="bg-gray-200">';
  foreach ($columns as $label) {
    echo "<th class='px-4 py-2 text-left border'>{$label}</th>";
  }
  if (!empty($actions)) {
    echo "<th class='px-4 py-2 border'>Aksi</th>";
  }
  echo '</tr></thead><tbody>';


  foreach ($admin_data as $row) {
    echo '<tr>';
    foreach (array_keys($columns) as $key) {
      $value = htmlspecialchars($row[$key] ?? '');
      if ($key === 'price') {
        $value = 'Rp' . number_format((int)$value, 0, ',', '.');
      }
      echo "<td class='px-4 py-2 border truncate'>{$value}</td>";
    }

    if (!empty($actions)) {
      echo "<td class='px-4 py-2 space-x-2 border truncate'>";
      $id = $row['id'];
      if ($tab === 'products') {
        if (in_array('edit', $actions)) {
          echo "<a href='edit_product.php?id={$id}' class='text-lime-600 hover:underline'>{$actions[0]}</a>";
        }
        if (in_array('delete', $actions)) {
          echo "<a href='../../controllers/admin/delete_product.php?id={$id}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin menghapus produk?\")'>{$actions[1]}</a>";
        }
      } elseif ($tab === 'categories') {
        if (in_array('edit', $actions)) {
          echo "<a href='edit_category.php?id={$id}' class='text-lime-600 hover:underline'>{$actions[0]}</a>";
        }
        if (in_array('delete', $actions)) {
          echo "<a href='../../controllers/admin/delete_category.php?id={$id}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin menghapus produk?\")'>{$actions[1]}</a>";
        }
      } elseif ($tab === 'users') {
        echo "<a href='../../controllers/admin/delete_user.php?id={$id}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin hapus user?\")'>{$actions[0]}</a>";
        echo "<a href='../../controllers/admin/block_user.php?id={$id}' class='text-yellow-600 hover:underline'>{$actions[1]}</a>";
        echo "<a href='../../controllers/admin/toggle_role.php?id={$id}' class='text-lime-600 hover:underline'>{$actions[2]}</a>";
      }
      echo "</td>";
    }

    echo '</tr>';
  }

  echo '</tbody></table>';
}

?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
