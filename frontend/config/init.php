<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
  session_start();
}

// Jika session belum ada, coba restore dari cookie JWT (zerovaa_jwt)
// Ini memungkinkan PHP langsung membaca status login tanpa harus menunggu JavaScript
if (!isset($_SESSION['user_id']) && isset($_COOKIE['zerovaa_jwt'])) {
  $jwtToken = urldecode($_COOKIE['zerovaa_jwt']);
  $jwtHelperPath = __DIR__ . '/../helpers/jwt_helper.php';
  $userModelPath = __DIR__ . '/../models/UserModel.php';
  $storeModelPath = __DIR__ . '/../models/StoreModel.php';
  
  if (file_exists($jwtHelperPath) && file_exists($userModelPath)) {
    require_once $jwtHelperPath;
    require_once __DIR__ . '/database.php'; // pastikan $BASE dll sudah ada
    require_once $userModelPath;
    require_once $storeModelPath;
    
    $jwtPayload = JWTHelper::validate($jwtToken);
    if ($jwtPayload && isset($jwtPayload['user_id'])) {
      $uid = $jwtPayload['user_id'];
      $uModel = new UserModel();
      $sModel = new StoreModel();
      $u = $uModel->getUserById($uid);
      $s = $sModel->getStoreByUserId($uid);
      if ($u) {
        $_SESSION['user_id']    = $u['id'];
        $_SESSION['user_name']  = $u['name'];
        $_SESSION['user_email'] = $u['email'];
        $_SESSION['user_role']  = $u['role'];
        $_SESSION['is_admin']   = ($u['role'] === 'admin') ? 1 : 0;
        if ($s) {
          $_SESSION['store_id']   = $s['id'];
          $_SESSION['store_name'] = $s['name'];
        } else {
          unset($_SESSION['store_id'], $_SESSION['store_name']);
        }
      }
    }
  }
}

if (!isset($BASE)) {
  require __DIR__ . '/database.php';
}
