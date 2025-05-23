<?php
require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_name = trim($_POST['category_name']);

  if (empty($category_name)) {
    $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
    header("Location: ../../views/admin?tab=categories");
    exit;
  }

  $categoryModel = new CategoryModel($conn);
  $success = $categoryModel->add($category_name);

  if ($success) {
    $_SESSION['success'] = "Kategori berhasil ditambahkan!";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menambahkan kategori!";
  }

  header("Location: ../../views/admin?tab=categories");
  exit;
}
