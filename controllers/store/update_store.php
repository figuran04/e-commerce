<?php
require_once '../../config/init.php';
require_once '../../models/StoreModel.php';
require_once '../../helpers/flash.php';

$storeModel = new StoreModel($conn);

$store_id = $_POST['store_id'];
$name = $_POST['name'];
$description = $_POST['description'];
$address = $_POST['address'];

$success = $storeModel->updateStore($store_id, $name, $description, $address);

if ($success) {
  setFlash('success', "Toko berhasil diperbarui.");
} else {
  setFlash('error', "Gagal memperbarui toko.");
}

header("Location: ../../views/store");
exit;
