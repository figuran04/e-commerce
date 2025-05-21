<?php
require '../../config/init.php';
require '../../models/ProductModel.php';

if (!isset($_SESSION['user_id'])) {
  $_SESSION['error'] = "Silakan login untuk mengunggah produk!";
  header("Location: ../../views/login");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);
  $category_id = intval($_POST['category_id']);
  $user_id = $_SESSION['user_id'];

  if (empty($name) || empty($price) || empty($stock) || empty($category_id)) {
    $_SESSION['error'] = "Semua bidang wajib diisi!";
    header("Location: ../../views/upload_product");
    exit;
  }

  // Upload gambar
  $image = null;
  if (!empty($_FILES['image']['name'])) {
    $image_name = time();  // atau bisa ditambah ekstensi file jika perlu
    $target_dir = "../../uploads/";
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $image = $image_name;
    }
  }

  $productModel = new ProductModel($conn);
  $success = $productModel->create([
    'user_id' => $user_id,
    'name' => $name,
    'description' => $description,
    'price' => $price,
    'stock' => $stock,
    'category_id' => $category_id,
    'image' => $image
  ]);

  if ($success) {
    $_SESSION['success'] = "Produk berhasil diunggah!";
    header("Location: ../../views/profile");
  } else {
    $_SESSION['error'] = "Gagal mengunggah produk!";
    header("Location: ../../views/upload_product");
  }
  exit;
}
