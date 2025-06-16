<?php
if (!function_exists('getFlash')) {
  require_once '../../helpers/flash.php';
}

$alerts = [
  'success' => ['icon' => 'ph-check-circle', 'classes' => 'text-green-800 bg-green-100 border-green-300'],
  'error'   => ['icon' => 'ph-warning-circle', 'classes' => 'text-red-800 bg-red-100 border-red-300'],
  'info'    => ['icon' => 'ph-info', 'classes' => 'text-blue-800 bg-blue-100 border-blue-300'],
  'warning' => ['icon' => 'ph-warning', 'classes' => 'text-yellow-800 bg-yellow-100 border-yellow-300'],
];
?>

<div
  x-data="{ show: true }"
  x-init="setTimeout(() => show = false, 5000)"
  x-show="show"
  x-transition
  class="fixed top-16 right-4 z-50 space-y-3">
  <?php foreach ($alerts as $type => $class_data):
    $message = getFlash($type);
    if ($message): ?>
      <div class="flex items-start gap-2 p-4 w-80 max-w-full text-sm border shadow rounded-lg <?= $class_data['classes'] ?>">
        <i class="ph <?= $class_data['icon'] ?> text-xl mt-0.5"></i>
        <div class="flex-1"><?= htmlspecialchars($message) ?></div>
        <button @click="show = false" class="text-lg ml-2 leading-none">&times;</button>
      </div>
  <?php endif;
  endforeach; ?>
</div>
