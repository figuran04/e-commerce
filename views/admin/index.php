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
        'columns' => ['id' => 'ID', 'name' => 'Nama Produk', 'price' => 'Harga', 'stock' => 'Stok'],
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

<!-- Notifikasi -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="p-4 text-lime-700 bg-lime-500 border border-lime-300 rounded">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="p-4 text-red-700 bg-red-100 border border-red-300 rounded">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="mb-4 flex space-x-2">
    <?php foreach ($tabConfig as $key => $conf): ?>
        <a href="?tab=<?= $key ?>" class="px-4 py-2 rounded <?= $tab === $key ? 'bg-lime-500 hover:bg-lime-600 text-white' : 'bg-gray-300 text-black' ?>">
            <?= $conf['label'] ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Tombol Tambah -->
<div class="mb-4">
    <?php if ($tabConfig[$tab]['allow_create']) : ?>
        <?php if ($tab === 'categories'): ?>
            <form method="post" action="../../controllers/admin/add_category.php" class="inline">
                <input type="text" name="category_name" required placeholder="Nama Kategori" class="px-2 py-1 border rounded">
                <button type="submit" class="bg-lime-500 text-white px-4 py-2 rounded hover:bg-lime-600 cursor-pointer">
                    + Tambah Kategori
                </button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

</div>

<!-- Tabel Data -->
<div class="overflow-x-auto">
    <?php renderTable($tab, $data, $tabConfig); ?>
</div>
</div>

<?php
function renderTable($tab, $data, $tabConfig)
{
    if (!isset($tabConfig[$tab])) {
        echo "<p>Data tidak ditemukan.</p>";
        return;
    }

    $config = $tabConfig[$tab];
    $columns = $config['columns'];
    $actions = $config['actions'];

    echo '<table class="w-full table-auto border-collapse">';
    echo '<thead><tr class="bg-gray-200">';
    foreach ($columns as $label) {
        echo "<th class='border px-4 py-2 text-left'>{$label}</th>";
    }
    if (!empty($actions)) {
        echo "<th class='border px-4 py-2'>Aksi</th>";
    }
    echo '</tr></thead><tbody>';

    while ($row = $data->fetch_assoc()) {
        echo '<tr>';
        foreach (array_keys($columns) as $key) {
            $value = htmlspecialchars($row[$key]);
            if ($key === 'price') {
                $value = 'Rp' . number_format($value, 0, ',', '.');
            }
            echo "<td class='border px-4 py-2'>{$value}</td>";
        }

        if (!empty($actions)) {
            echo "<td class='border px-4 py-2 space-x-2'>";

            $id = $row['id'];
            if ($tab === 'products') {
                if (in_array('edit', $actions)) {
                    echo "<a href='edit_product.php?id={$id}' class='text-blue-600 hover:underline'>$actions[0]</a>";
                }
                if (in_array('delete', $actions)) {
                    echo "<a href='../../controllers/admin/delete_product.php?id={$id}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin menghapus produk?\")'>$actions[1]</a>";
                }
            } elseif ($tab === 'categories') {
                if (in_array('edit', $actions)) {
                    echo "<a href='edit_category.php?id={$id}' class='text-blue-600 hover:underline'>$actions[0]</a>";
                }
                if (in_array('delete', $actions)) {
                    echo "<a href='../../controllers/admin/delete_category.php?id={$id}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin menghapus produk?\")'>$actions[1]</a>";
                }
            } elseif ($tab === 'users') {
                echo "<a href='../../controllers/admin/delete_user.php?id={$id}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin hapus user?\")'>$actions[0]</a>";
                echo "<a href='../../controllers/admin/block_user.php?id={$id}' class='text-yellow-600 hover:underline'>$actions[1]</a>";
                echo "<a href='../../controllers/admin/toggle_role.php?id={$id}' class='text-lime-600 hover:underline'>$actions[2]</a>";
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
