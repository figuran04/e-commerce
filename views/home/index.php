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
<div id="product-container" class="mt-6 product-grid">
  <!-- Loading skeleton -->
  <div class="animate-pulse bg-white p-4 rounded-lg h-64 flex flex-col space-y-4">
    <div class="bg-gray-200 h-40 w-full rounded"></div>
    <div class="bg-gray-200 h-6 w-3/4 rounded"></div>
    <div class="bg-gray-200 h-6 w-1/2 rounded"></div>
  </div>
  <div class="animate-pulse bg-white p-4 rounded-lg h-64 flex flex-col space-y-4">
    <div class="bg-gray-200 h-40 w-full rounded"></div>
    <div class="bg-gray-200 h-6 w-3/4 rounded"></div>
    <div class="bg-gray-200 h-6 w-1/2 rounded"></div>
  </div>
  <div class="animate-pulse bg-white p-4 rounded-lg h-64 flex flex-col space-y-4">
    <div class="bg-gray-200 h-40 w-full rounded"></div>
    <div class="bg-gray-200 h-6 w-3/4 rounded"></div>
    <div class="bg-gray-200 h-6 w-1/2 rounded"></div>
  </div>
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

  // Fetch data produk dari API Gateway secara dinamis
  fetch('../../api/gateway.php?route=products')
    .then(res => {
      if (!res.ok) throw new Error('Network response was not ok');
      return res.json();
    })
    .then(response => {
      if (response.status === 'success') {
        const container = document.getElementById('product-container');
        container.innerHTML = '';
        
        if (response.data.length === 0) {
          container.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">Belum ada produk ramah lingkungan.</p>';
          return;
        }

        response.data.forEach(product => {
          // Format mata uang Rupiah
          const priceFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
          }).format(product.price).replace('Rp', 'Rp');

          const soldCount = parseInt(product.sold_count || 0);
          const soldText = soldCount >= 1000 
            ? (soldCount / 1000).toFixed(1) + 'rb' 
            : soldCount;

          const categoryText = product.category || 'Tanpa Kategori';

          const cardHTML = `
            <a href="../product_detail/?id=${product.id}" class="w-full overflow-hidden transition-all bg-white border border-gray-100 rounded-lg cursor-pointer hover:shadow hover:scale-105">
              <img src="/5/e-commerce/uploads/${product.image}" alt="${escapeHTML(product.name)}" class="object-contain w-full aspect-square">
              <div class="flex flex-col p-2 mb-1 space-y-1">
                <h3 class="text-sm md:text-base font-medium text-gray-900 leading-snug line-clamp-2 h-[2.6rem] md:h-[2.8rem]">
                  ${escapeHTML(product.name)}
                </h3>
                <div class="flex items-center justify-between">
                  <p class="text-lg font-semibold truncate text-lime-600">${priceFormatted}</p>
                  <p class="text-xs text-gray-500 truncate">${soldText} terjual</p>
                </div>
                <p class="text-xs text-gray-400 truncate">${escapeHTML(categoryText)}</p>
              </div>
            </a>
          `;
          container.innerHTML += cardHTML;
        });
      }
    })
    .catch(err => {
      console.error('Error fetching products:', err);
      document.getElementById('product-container').innerHTML = 
        '<p class="col-span-full text-center text-red-500 py-8">Gagal memuat produk dari API Gateway.</p>';
    });

  // Helper untuk membersihkan string dari HTML injection
  function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>'"]/g, 
      tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
      }[tag] || tag)
    );
  }
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>

