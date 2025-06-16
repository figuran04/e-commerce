<?php

require_once '../../config/init.php';
require_once '../../models/CategoryModel.php';
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (
  $_SERVER['REQUEST_METHOD'] === 'POST' &&
  isset($_POST['action'], $_GET['id']) &&
  $_POST['action'] === 'edit'
) {
  $category_id = (int) $_GET['id'];
  $category_name = trim($_POST['category_name'] ?? '');
  $parent_id = isset($_POST['parent_id']) ? (int) $_POST['parent_id'] : 0;

  if (empty($category_name)) {
    setFlash('error', "Nama kategori tidak boleh kosong!");
    header("Location: ../../views/admin/edit_category.php?id=$category_id");
    exit;
  }

  if ($category_id === $parent_id) {
    setFlash('error', "Kategori tidak boleh menjadi induk dirinya sendiri!");
    header("Location: ../../views/admin/edit_category.php?id=$category_id");
    exit;
  }

  $categoryModel = new CategoryModel($conn);
  $success = $categoryModel->update($category_id, $category_name, $parent_id);

  if ($success) {
    setFlash('success', "Kategori berhasil diperbarui!");
  } else {
    setFlash('error', "Terjadi kesalahan saat memperbarui kategori!");
  }

  header("Location: ../../views/admin?tab=categories");
  exit;
}
