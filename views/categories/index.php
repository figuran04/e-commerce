<?php
require_once '../../config/init.php';
$pageTitle = "Kategori";
require '../../controllers/category/category_handler.php';
ob_start();
?>

<h2 class="mb-4 text-xl font-bold"><i class="ph-fill ph-squares-four"></i> Kategori Produk</h2>
<?php include '../partials/alerts.php'; ?>

<?php if (!$hasCategories): ?>
  <div class="p-6 text-yellow-800 bg-yellow-100 rounded-md">
    Belum ada kategori yang ditambahkan. Silakan tambahkan kategori dari halaman admin.
  </div>
<?php else: ?>

  <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
    <!-- Sidebar kategori utama -->
    <div class="w-full p-4 bg-white md:bg-transparent border border-gray-200 rounded-lg h-min">
      <h3 class="mb-2 font-semibold text-gray-700">Kategori Utama</h3>
      <ul class="w-full space-y-1">
        <?php foreach ($rootCategories as $cat): ?>
          <li class="w-full">
            <a href="?category=<?= $cat['id'] ?>"
              class="block w-full text-lime-600 transition-all hover:bg-[rgba(101,163,13,0.1)] p-2 rounded-lg <?= $selectedCategoryId == $cat['id'] ? 'font-semibold bg-[rgba(101,163,13,0.2)]' : 'mr-2 hover:ml-2 hover:mr-0' ?>">
              <?= htmlspecialchars($cat['name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>


    <!-- Isi subkategori -->
    <div class="md:col-span-3">
      <?php if ($selectedCategory): ?>
        <h3 class="mb-2 text-xl font-bold text-gray-700">
          <?= htmlspecialchars($selectedCategory['name']) ?>
        </h3>
        <div class="h-[500px] overflow-hidden columns-2 gap-6">
          <?php
          function renderSubcategories($category, $indent = 0)
          {
            if (!empty($category['children'])) {
              foreach ($category['children'] as $child) {
                // Jika level 1 (anak langsung), beri break-inside agar tidak terpecah kolom
                $wrapperClass = $indent === 0 ? 'mb-2 break-inside-avoid' : 'mb-1';
                $paddingClass = 'pl-' . min($indent * 4, 12);
                $firstChildren = $indent === 0 ? 'font-semibold' : '';

                echo '<div class="' . $wrapperClass . '">';
                echo '<div class="' . $paddingClass . ' ' . $firstChildren . '">';
                echo '<a href="../products/?category=' . $child['id'] . '"
                  class="block px-2 py-1 transition-all duration-300 ease-in-out rounded hover:bg-lime-100 hover:text-lime-700 hover:translate-x-1">';
                echo htmlspecialchars($child['name']);
                echo '</a>';
                echo '</div>';

                // Rekursi untuk anak-anak
                renderSubcategories($child, $indent + 1);
                echo '</div>';
              }
            }
          }

          renderSubcategories($selectedCategory);
          ?>
        </div>

      <?php else: ?>
        <p class="text-gray-500">Kategori tidak ditemukan.</p>
      <?php endif; ?>
    </div>
  </div>

<?php endif; ?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
