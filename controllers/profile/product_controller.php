<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['is_admin']));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);

    // Validasi data
    if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || empty($category_id)) {
      header("Location: ../../views/edit_product?id=$id&status=error");
      exit;
    }

    // Pastikan produk ada dan ambil pemiliknya
    $query = "SELECT user_id, image FROM products WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
      header("Location: ../../views/profile?status=notfound");
      exit;
    }

    // Pastikan hanya admin atau pemilik produk yang bisa edit
    if ($product['user_id'] != $user_id && !$is_admin) {
      header("Location: ../../views/profile?status=unauthorized");
      exit;
    }

    // Mengatur gambar (jika ada yang diunggah)
    $image = $product['image']; // Gambar lama tetap digunakan
    if (!empty($_FILES['image']['name'])) {
      $image_name = time(); // Menggunakan time() sebagai nama gambar
      $target_dir = "../../uploads/";
      $target_file = $target_dir . $image_name;

      if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image = $image_name;  // Gunakan nama gambar baru
      }
    }

    // Update produk dengan kategori dan gambar
    $query = "UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdiiis", $name, $description, $price, $stock, $category_id, $image, $id);

    if ($stmt->execute()) {
      $status = "success";
    } else {
      $status = "error";
    }

    header("Location: ../../views/profile?status=$status");
    exit;
  }
}
