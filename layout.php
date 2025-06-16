<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$currentPath = trim($_SERVER['REQUEST_URI'], '/');
$hideHeaderFooter = preg_match('#views/(login|register|admin)#', $currentPath);
$isAdminPage = strpos($currentPath, 'admin') !== false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="E-commerce yang hanya menjual produk ramah lingkungan dengan sistem carbon footprint tracking, di mana pelanggan bisa melihat dampak lingkungan dari pembelian mereka.">
  <meta name="keywords" content="Zerovaa, E-Commerce, Indonesia, Eco Friendy">
  <meta name="author" content="Zerovaa Team">
  <title><?php echo isset($pageTitle) ? $pageTitle . " - Zerovaa" : "Zerovaa"; ?></title>
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" />
  <link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
  <link
    rel="stylesheet"
    type="text/css"
    href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
  <link rel="stylesheet" href="<?= $BASE_URL; ?>/global.css">
</head>

<body class="bg-[#E2E6CF]">

  <?php if (!$hideHeaderFooter) include 'includes/header.php'; ?>

  <div class="bg-gray-50">

    <?php if ($isAdminPage): ?>
      <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>
        <main class="container flex flex-col min-h-screen gap-4 p-4 mx-auto">
          <?= isset($content) ? $content : '<p>Konten tidak ditemukan.</p>'; ?>
        </main>
      </div>
    <?php else: ?>
      <main class="container flex flex-col min-h-screen gap-4 p-4 mx-auto">
        <?= isset($content) ? $content : '<p>Konten tidak ditemukan.</p>'; ?>
      </main>
    <?php endif; ?>

  </div>

  <?php if (!$hideHeaderFooter) include 'includes/footer.php'; ?>

  <script>
    <?php if (isset($_SESSION['wa_redirect'])) : ?>
      window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
          window.location.href = <?= json_encode($_SESSION['wa_redirect']) ?>;
        }, 3000); // tunggu 2 detik
      });
      <?php unset($_SESSION['wa_redirect']); ?>
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {
      const backBtn = document.getElementById('btn-kembali');

      // Sembunyikan jika tidak bisa kembali
      if (window.history.length <= 1) {
        backBtn.style.display = 'none';
      }
    });

    function toggleModal(id) {
      const modal = document.getElementById(id);
      if (modal) {
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
      }
    }
  </script>

</body>

</html>
