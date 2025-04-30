<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];

  // Ambil data pengguna berdasarkan ID
  $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    // Toggle role antara 'admin' dan 'user'
    $new_role = ($user['role'] == 'admin') ? 'user' : 'admin';

    // Update role pengguna
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);

    if ($stmt->execute()) {
      $_SESSION['success'] = "Role pengguna berhasil diperbarui menjadi {$new_role}!";
    } else {
      $_SESSION['error'] = "Terjadi kesalahan saat memperbarui role pengguna.";
    }
  } else {
    $_SESSION['error'] = "Pengguna tidak ditemukan.";
  }

  header("Location: ../../views/admin/?status=success");
  exit;
}
