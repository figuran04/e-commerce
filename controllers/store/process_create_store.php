<?php
require_once '../../config/init.php';
require_once '../../models/StoreModel.php';
require_once '../../helpers/flash.php';

if (!isset($_SESSION['user_id'])) {
  setFlash('error', "Silakan login terlebih dahulu.");
  header("Location: ../../views/login");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name        = trim($_POST['name']);
  $address     = trim($_POST['address']);
  $description = trim($_POST['description'] ?? '');
  $phone       = trim($_POST['phone'] ?? '');
  $user_id     = $_SESSION['user_id'];

  // Validasi wajib
  if (empty($name) || empty($address)) {
    setFlash('error', "Nama dan alamat toko wajib diisi.");
    header("Location: ../../views/store/create_store.php");
    exit;
  }

  // Tambahkan simpan phone ke tabel users
  if (!empty($phone)) {
    $stmt = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
    $stmt->execute([$phone, $user_id]);
  }

  $storeModel = new StoreModel($conn);

  if ($storeModel->userHasStore($user_id)) {
    setFlash('error', "Anda sudah memiliki toko.");
    header("Location: ../../views/store/index.php");
    exit;
  }

  $success = $storeModel->createStore([
    'user_id'     => $user_id,
    'name'        => $name,
    'address'     => $address,
    'description' => $description
  ]);

  if ($success) {
    $store = $storeModel->getStoreByUserId($user_id);
    if ($store) {
      $_SESSION['store_id'] = $store['id'];
    }

    setFlash('success', "Toko berhasil dibuat!");
    header("Location: ../../views/store/index.php");
  } else {
    setFlash('error', "Gagal membuat toko.");
    header("Location: ../../views/store/create_store.php");
  }
  exit;
}
