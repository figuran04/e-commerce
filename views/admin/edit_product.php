<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../models/CategoryModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login");
    exit;
}

$productModel = new ProductModel($conn);
$categoryModel = new CategoryModel($conn);

// Ambil produk berdasarkan ID dari query string
$product = $productModel->getById($_GET['id']);
if (!$product) {
    $_SESSION['error'] = "Produk tidak ditemukan!";
    header("Location: ../");
    exit;
}

// Ambil semua kategori menggunakan model
$categories = $categoryModel->getAll();

ob_start();
?>

<h2 class="text-xl font-bold mb-4">Edit Product</h2>

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

<form action="../../controllers/admin/edit_product.php?id=<?= $product['id']; ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
    <input class="border rounded px-2 border-gray-300 py-1" type="hidden" name="action" value="edit">

    <label>Product Name:</label>
    <input class="border rounded px-2 border-gray-300 py-1" type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

    <label>Description:</label>
    <textarea class="border rounded px-2 border-gray-300 py-1" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>

    <label>Price:</label>
    <input class="border rounded px-2 border-gray-300 py-1" type="number" name="price" value="<?= $product['price']; ?>" required step="0.01">

    <label>Stock:</label>
    <input class="border rounded px-2 border-gray-300 py-1" type="number" name="stock" value="<?= $product['stock']; ?>" required>

    <label>Category:</label>
    <select class="border rounded px-2 border-gray-300 py-1" name="category_id" required>
        <option value="">Select Category</option>
        <?php while ($category = $categories->fetch_assoc()): ?>
            <option value="<?= $category['id']; ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($category['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Image:</label>
    <input class="border rounded px-2 border-gray-300 py-1" type="file" name="image">
    <p>Current: <?= $product['image']; ?></p>

    <button type="submit" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 hover:bg-lime-700 hover:border-lime-700 text-gray-50 cursor-pointer">Update Product</button>
</form>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
