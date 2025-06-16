<?php
require_once '../../config/init.php';
require_once '../../models/UserModel.php';
require_once '../../views/partials/alerts.php'; // ✅ Tambahkan ini

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../views/login/');
  exit;
}

$user_id = $_POST['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$bio = $_POST['bio'] ?? '';

$userModel = new UserModel($conn);
$success = $userModel->updateProfile($user_id, $name, $email, $phone, $address, $bio);

if ($success) {
  setFlash('success', "Profil berhasil diperbarui."); // ✅ Ganti
  header("Location: ../../views/profile/");
} else {
  setFlash('error', "Gagal memperbarui profil."); // ✅ Ganti
  header("Location: ../../views/profile/edit_profile.php");
}
exit;
