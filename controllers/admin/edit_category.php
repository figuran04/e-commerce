<?php
require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_GET['id'])) {
  $category_id = $_GET['id'];
  $category_name = trim($_POST['category_name']);

  if (empty($category_name)) {
    $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
    header("Location: ../../views/admin/edit_category.php?id=$category_id");
    exit;
  }

  $categoryModel = new CategoryModel($conn);
  if ($categoryModel->update($category_id, $category_name)) {
    $_SESSION['success'] = "Kategori berhasil diperbarui!";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat memperbarui kategori!";
  }

  header("Location: ../../views/admin?tab=categories");
  exit;
}
