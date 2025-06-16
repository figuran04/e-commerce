<?php
require '../../controllers/products/products_controller.php';
$pageTitle = "Beranda";
ob_start();
?>

<!-- Tailwind Style khusus untuk grid produk -->
<style type='text/tailwindcss'>
  #slider {
    display: flex;
    flex-wrap: nowrap;
    align-items: flex-start;
    justify-content: flex-start;
    position: relative;
    width: 100%;
    padding: 0 20px;
    overflow-y: hidden;
    overflow-x: scroll;
    scroll-behavior: smooth;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
    -ms-overflow-style: none;
    -webkit-overflow-scrolling: touch;
  }

  #slider::-webkit-scrollbar {
    display: none;
  }

  #slider > * {
    display: flex;
    flex-shrink: 0;
    width: 90%;
    scroll-snap-align: center;
    margin-right: 16px;
  }
  .dot {
    @apply w-1 h-1 bg-gray-300 rounded-full transition-all duration-300;
  }

  .dot.active {
    @apply bg-lime-600 scale-110;
  }
  .product-grid {
    @apply grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4;
  }
</style>
<?php include '../partials/alerts.php'; ?>
<!-- Banner -->
<div id="slider" class="rounded-lg overflow-hidden">
  <img src="../../uploads/banner.png" alt="banner" class="w-full rounded-lg" />
  <img src="../../uploads/banner.png" alt="banner" class="w-full rounded-lg" />
  <img src="../../uploads/banner.png" alt="banner" class="w-full rounded-lg" />
</div>

<!-- Indikator -->
<div class="flex justify-center gap-2" id="dots">
  <div class="dot active"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>

<!-- Kategori Pilihan -->
<div class="flex flex-col p-4 space-y-4 bg-white border border-gray-200 rounded-lg">
  <h1 class="text-xl font-bold">Kategori Pilihan</h1>

  <!-- Kotak kategori (maksimal 8 termasuk 'Semua') -->
  <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-6">
    <?php
    $limit = 6;
    $count = 0;
    foreach ($childCategories as $category):
      if ($count++ >= $limit) break;
      $thumb = $categoryThumbnails[$category['id']] ?? null;
    ?>
      <a href="../products?category=<?= $category['id'] ?>" class="relative flex flex-col items-center justify-between overflow-hidden border border-gray-200 rounded-lg aspect-square hover:shadow">
        <?php if ($thumb): ?>
          <img src="../../uploads/<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($category['name']) ?>" class="object-cover h-full mb-2 rounded">
        <?php else: ?>
          <div class="flex items-center justify-center w-full h-full pb-4 text-gray-400 bg-gray-100 rounded">
            <i class="text-4xl ph ph-image"></i>
          </div>
        <?php endif; ?>
        <p class="absolute bottom-0 flex items-center justify-center w-full h-10 text-sm text-center text-gray-700 bg-white line-clamp-2"><?= htmlspecialchars($category['name']) ?></p>
      </a>
    <?php endforeach; ?>
  </div>


  <!-- Filter kategori horizontal -->
  <div class="flex gap-4 overflow-x-auto custom-scroll">
    <a href="../categories" class="hidden px-4 py-1 border border-gray-200 rounded-xl text-lime-600 whitespace-nowrap md:block
     hover:bg-lime-100 hover:border-lime-600 hover:text-lime-800 transition duration-200">
      Kategori
    </a>
    <?php foreach ($rootCategories as $category): ?>
      <a href="../categories?category=<?= $category['id'] ?>" class="px-4 py-1 border border-gray-200 rounded-xl text-lime-600 whitespace-nowrap
       hover:bg-lime-100 hover:border-lime-600 hover:text-lime-800 transition duration-200">
        <?= htmlspecialchars($category['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

</div>

<!-- Daftar Produk -->
<div class="mt-6 product-grid">
  <?php include '../../includes/product_card.php'; ?>
</div>

<!-- Link lihat semua -->
<div class="w-full mt-6 text-center">
  <a href="../products" class="hover:text-lime-600 hover:underline">Lihat Semua</a>
</div>

<script>
  const slider = document.getElementById('slider');
  const dots = document.querySelectorAll('#dots .dot');
  const banners = slider.querySelectorAll('img');
  const scrollStep = slider.clientWidth * 0.9 + 16;
  let currentIndex = 0;
  let autoScrollInterval;

  function goToSlide(index) {
    const scrollLeft = banners[index].offsetLeft;
    slider.scrollTo({
      left: scrollLeft,
      behavior: 'smooth'
    });
    updateDots(index);
    currentIndex = index;
  }

  function updateDots(index) {
    dots.forEach(dot => dot.classList.remove('active'));
    if (dots[index]) dots[index].classList.add('active');
  }

  function autoScroll() {
    currentIndex = (currentIndex + 1) % banners.length;
    goToSlide(currentIndex);
  }

  // Mulai autoplay
  autoScrollInterval = setInterval(autoScroll, 3000);

  // Pause saat hover
  slider.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
  slider.addEventListener('mouseleave', () => {
    autoScrollInterval = setInterval(autoScroll, 3000);
  });

  // Manual scroll update indikator
  slider.addEventListener('scroll', () => {
    let index = 0;
    banners.forEach((img, i) => {
      const rect = img.getBoundingClientRect();
      if (rect.left >= 0 && rect.left < window.innerWidth / 2) {
        index = i;
      }
    });
    updateDots(index);
    currentIndex = index;
  });
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
