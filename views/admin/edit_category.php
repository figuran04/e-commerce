<?php
require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID kategori tidak ditemukan.";
    header("Location: ./");
    exit;
}

$category_id = $_GET['id'];
$categoryModel = new CategoryModel($conn);
$category = $categoryModel->getById($category_id);

if (!$category) {
    $_SESSION['error'] = "Kategori tidak ditemukan.";
    header("Location: ./");
    exit;
}

ob_start();
?>

<h2>Edit Kategori</h2>

<!-- Notifikasi -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="p-4 text-green-700 bg-green-100 border border-green-300 rounded">
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

<form action="../../controllers/admin/edit_category.php?id=<?= $category['id']; ?>" method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="text" name="category_name" value="<?= htmlspecialchars($category['name']); ?>" required>
    <button type="submit">Update</button>
</form>

<?php
$content = ob_get_clean();
include '../../layout.php';
