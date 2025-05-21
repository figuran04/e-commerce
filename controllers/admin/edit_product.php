<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

$productModel = new ProductModel($conn);

// Validasi ID dan Ambil Produk
if (!isset($_GET['id']) || !$product = $productModel->getById($_GET['id'])) {
  $_SESSION['error'] = "Produk tidak ditemukan!";
  header("Location: ../../views/admin?tab=products");
  exit;
}

$product_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $category_id = intval($_POST['category_id']);

  // Validasi input
  if (!ProductModel::validateInput($name, $description, $price, $stock, $category_id)) {
    $_SESSION['error'] = "Semua field wajib diisi dengan benar!";
    header("Location: ../../views/admin/edit_product.php?id={$product_id}");
    exit;
  }

  // Cek apakah ada gambar baru
  $image = $product['image'];
  if (!empty($_FILES['image']['name'])) {
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $image = $_FILES['image']['name'];
    } else {
      $_SESSION['error'] = "Gagal mengupload gambar!";
      header("Location: ../../views/admin/edit_product.php?id={$product_id}");
      exit;
    }
  }

  if ($productModel->update($product_id, $name, $description, $price, $stock, $category_id, $image)) {
    $_SESSION['success'] = "Produk berhasil diperbarui!";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat memperbarui produk!";
  }

  header("Location: ../../views/admin?tab=products");
  exit;
}
