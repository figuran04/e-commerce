<?php if (!isset($modal_id)) $modal_id = 'modal-confirm'; ?>
<div id="<?= htmlspecialchars($modal_id) ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 space-y-4">
    <h3 class="text-lg font-semibold"><?= htmlspecialchars($modal_title ?? 'Konfirmasi') ?></h3>
    <p><?= htmlspecialchars($modal_message ?? 'Apakah Anda yakin ingin melanjutkan?') ?></p>

    <?php if (!empty($modal_action)): ?>
      <form method="POST" action="<?= htmlspecialchars($modal_action) ?>" class="flex justify-end gap-2 mt-4">
        <?php if (!empty($modal_hidden_inputs)): ?>
          <?php foreach ($modal_hidden_inputs as $name => $value): ?>
            <input type="hidden" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($value) ?>">
          <?php endforeach; ?>
        <?php endif; ?>

        <button type="button" onclick="toggleModal('<?= htmlspecialchars($modal_id) ?>')" class="px-4 py-1 text-sm border rounded text-gray-700 hover:bg-gray-100">
          Batal
        </button>
        <button type="submit" class="px-4 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
          Ya, Lanjutkan
        </button>
      </form>
    <?php else: ?>
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="toggleModal('<?= htmlspecialchars($modal_id) ?>')" class="px-4 py-1 text-sm border rounded text-gray-700 hover:bg-gray-100">
          Tutup
        </button>
      </div>
    <?php endif; ?>
  </div>
</div>
