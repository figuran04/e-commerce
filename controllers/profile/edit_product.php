<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';

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

  // Validasi input
  if (!ProductModel::validateInput($name, $description, $price, $stock, $category_id)) {
    header("Location: ../../views/edit_product?id=$id&status=error");
    exit;
  }

  // Ambil data produk berdasarkan ID
  $product = $productModel->getById($id);
  if (!$product) {
    header("Location: ../../views/profile?status=notfound");
    exit;
  }

  // Validasi apakah user yang mengedit adalah pemilik produk atau admin
  if ($product['user_id'] != $user_id && !$is_admin) {
    header("Location: ../../views/profile?status=unauthorized");
    exit;
  }

  // Proses gambar (jika ada yang diunggah)
  $image = $product['image'];
  if (!empty($_FILES['image']['name'])) {
    $image_name = time();
    $target_file = "../../uploads/" . $image_name;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $image = $image_name; // Gambar baru
    }
  }

  // Update produk dengan data baru
  $success = $productModel->update($id, $name, $description, $price, $stock, $category_id, $image);
  $status = $success ? "success" : "error";

  header("Location: ../../views/profile?status=$status");
  exit;
}
