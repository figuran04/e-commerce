<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
  $category_name = trim($_POST['category_name']);

  if (empty($category_name)) {
    $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
    header("Location: ../../views/admin/?status=error");
    exit;
  }

  // Query untuk menambahkan kategori
  $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
  $stmt->bind_param("s", $category_name);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Kategori berhasil ditambahkan!";
    header("Location: ../../views/admin/?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menambahkan kategori!";
    header("Location: ../../views/admin/?status=error");
    exit;
  }
}
