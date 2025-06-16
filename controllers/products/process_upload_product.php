<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../models/StoreModel.php';
require_once '../../views/partials/alerts.php'; // <-- Tambahkan ini

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$storeModel = new StoreModel($conn);
$store = $storeModel->getStoreByUserId($_SESSION['user_id']);

if (!$store) {
  setFlash('error', "Anda belum memiliki toko.");
  header("Location: ../../views/store/create_store.php");
  exit;
}

$store_id = $store['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $category_id = intval($_POST['category_id']);
  $user_id = $_SESSION['user_id'];

  if (empty($name) || empty($price) || empty($stock) || empty($category_id)) {
    setFlash('error', "Semua bidang wajib diisi!");
    header("Location: ../../views/upload_product");
    exit;
  }

  // Upload gambar
  $image = null;
  if (!empty($_FILES['image']['name'])) {
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = time() . '.' . $extension;
    $target_dir = "../../uploads/";
    $target_file = $target_dir . $image_name;

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $image = $image_name;
    }
  }

  $productData = [
    'store_id' => $store_id,
    'user_id' => $user_id,
    'name' => $name,
    'description' => $description,
    'price' => $price,
    'stock' => $stock,
    'category_id' => $category_id,
    'image' => $image
  ];

  $productModel = new ProductModel($conn);
  if ($productModel->create($productData)) {
    setFlash('success', "Produk berhasil diunggah.");
    header("Location: ../../views/store/index.php");
  } else {
    setFlash('error', "Gagal menambahkan produk.");
    header("Location: ../../views/products/upload_product.php");
  }
}
