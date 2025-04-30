<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];

  // Ambil data pengguna berdasarkan ID
  $stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    // Toggle status antara 'active' dan 'blocked'
    $new_status = ($user['status'] == 'active') ? 'blocked' : 'active';

    // Update status pengguna
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $user_id);

    if ($stmt->execute()) {
      $_SESSION['success'] = "status pengguna berhasil diperbarui menjadi {$new_status}!";
    } else {
      $_SESSION['error'] = "Terjadi kesalahan saat memperbarui status pengguna.";
    }
  } else {
    $_SESSION['error'] = "Pengguna tidak ditemukan.";
  }

  header("Location: ../../views/admin/?status=success");
  exit;
}
