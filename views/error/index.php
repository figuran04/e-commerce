<?php
$code = isset($_GET['code']) ? $_GET['code'] : 'unknown';

$messages = [
  '400' => 'Bad Request',
  '401' => 'Unauthorized',
  '403' => 'Forbidden',
  '404' => 'Page Not Found',
  '500' => 'Internal Server Error',
  '502' => 'Bad Gateway',
  '503' => 'Service Unavailable'
];

$message = isset($messages[$code]) ? $messages[$code] : 'Unknown Error';

http_response_code((int)$code);
$pageTitle = "Error";
include "../../controllers/search/search_handler.php";
ob_start()
?>

<div class="flex flex-col items-center pt-20 text-lime-600">
  <h1 class="text-4xl font-bold"><?= htmlspecialchars($code) ?></h1>
  <p class="text-2xl font-semibold"><?= htmlspecialchars($message) ?></p>
  <a href="../home" class="underline mt-8 hover:text-lime-700">Kembali ke Beranda</a>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
