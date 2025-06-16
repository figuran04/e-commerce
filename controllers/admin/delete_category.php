<?php
require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $category_id = intval($_GET['id']);
  $categoryModel = new CategoryModel($conn);

  if ($categoryModel->delete($category_id)) {
    setFlash('success', 'Kategori berhasil dihapus!');
  } else {
    setFlash('error', 'Terjadi kesalahan saat menghapus kategori!');
  }

  header("Location: ../../views/admin?tab=categories");
  exit;
}
