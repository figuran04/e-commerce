<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../views/partials/alerts.php'; // ✅ Flash message handler

$productModel = new ProductModel($conn);

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['is_admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
  $id = intval($_POST['id']);
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $category_id = intval($_POST['category_id']);

  // ✅ Validasi input
  if (!ProductModel::validateInput($name, $description, $price, $stock, $category_id)) {
    setFlash('error', "Input tidak valid. Pastikan semua kolom terisi dengan benar.");
    header("Location: ../../views/store/edit_product?id=$id");
    exit;
  }

  // ✅ Cek produk
  $product = $productModel->getById($id);
  if (!$product) {
    setFlash('error', "Produk tidak ditemukan.");
    header("Location: ../../views/store");
    exit;
  }

  // ✅ Validasi izin edit
  if ($product['user_id'] != $user_id && !$is_admin) {
    setFlash('error', "Anda tidak memiliki izin untuk mengedit produk ini.");
    header("Location: ../../views/store");
    exit;
  }

  // ✅ Proses gambar jika ada
  $image = $product['image'];
  if (!empty($_FILES['image']['name'])) {
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = time() . '.' . $extension;
    $target_file = "../../uploads/" . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $image = $image_name;
    }
  }

  // ✅ Update produk
  $success = $productModel->update($id, $name, $description, $price, $stock, $category_id, $image);

  if ($success) {
    setFlash('success', "Produk berhasil diperbarui.");
  } else {
    setFlash('error', "Gagal memperbarui produk.");
  }

  header("Location: ../../views/store");
  exit;
}
