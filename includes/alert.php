<?php if (isset($_SESSION['success'])): ?>
  <div class="flex items-start gap-2 p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded">
    <i class="ph ph-check-circle text-xl mt-0.5"></i>
    <div><?= $_SESSION['success'];
          unset($_SESSION['success']); ?></div>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="flex items-start gap-2 p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded">
    <i class="ph ph-warning-circle text-xl mt-0.5"></i>
    <div><?= $_SESSION['error'];
          unset($_SESSION['error']); ?></div>
  </div>
<?php endif; ?>
