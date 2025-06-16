<?php
require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_name = trim($_POST['category_name']);

  if (empty($category_name)) {
    setFlash('error', 'Nama kategori tidak boleh kosong!');
    header("Location: ../../views/admin?tab=categories");
    exit;
  }

  $categoryModel = new CategoryModel($conn);
  $parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int) $_POST['parent_id'] : null;
  $success = $categoryModel->add($category_name, $parent_id);


  if ($success) {
    setFlash('success', 'Kategori berhasil ditambahkan!');
  } else {
    setFlash('error', 'Terjadi kesalahan saat menambahkan kategori!');
  }

  header("Location: ../../views/admin?tab=categories");
  exit;
}
