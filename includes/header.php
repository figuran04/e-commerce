<header class="bg-[#E2E6CF] shadow sticky top-0 z-40">
  <nav class="flex items-center justify-between gap-2 md:gap-4 px-5 py-2 xl:container xl:mx-auto text-lime-600 md:px-5 lg:px-8 md:justify-normal">
    <!-- Tombol Back Seperti Browser -->
    <button onclick="window.history.back()"
      class="flex space-x-2 items-center transition-all hover:bg-[rgba(101,163,13,0.2)] p-2 rounded-lg cursor-pointer" id="btn-kembali">
      <i class="text-xl ph ph-caret-left"></i>
      <!-- <span class="hidden md:inline text-sm">Kembali</span> -->
    </button>


    <ul class="items-center hidden gap-4 md:flex">
      <h1 class="text-2xl font-bold text-lime-600 hover:text-lime-700"><a href="<?= $BASE ?>/views/home">Zerovaa</a></h1>
      <p><a href="<?= $BASE ?>/views/categories" class="transition-all hover:bg-[rgba(101,163,13,0.2)] p-2 rounded-lg">Kategori</a></p>
    </ul>
    <form action="<?= $BASE ?>/views/search" method="get" class="items-center w-full py-0.5 pr-1 bg-gray-100 rounded-lg flex">
      <input
        type="text"
        name="q"
        placeholder="Cari produk..."
        class="w-full py-2 pl-4 outline-none border-none"
        required>
      <button type="submit" class="transition-all hover:bg-[rgba(101,163,13,0.2)] px-2 py-1 rounded-lg hover:cursor-pointer">
        <i class="text-2xl ph ph-magnifying-glass"></i>
      </button>
    </form>
    <?php
      $loggedIn = isset($_SESSION['user_id']);
      $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
      $storeLink = $BASE . '/views/store';
    ?>

    <div id="auth-user-nav" class="items-center gap-1 md:gap-2 <?= $loggedIn ? 'flex' : 'hidden' ?>">
      <ul class="flex items-center gap-1 md:gap-2">
        <li class="hidden md:block">
          <a href="<?= $BASE ?>/views/orders/user_orders.php" class="flex space-x-2 items-center transition-all hover:bg-[rgba(101,163,13,0.2)] p-2 rounded-lg">
            <i class="text-2xl ph ph-scroll"></i>
          </a>
        </li>
        <li>
          <a href="<?= $BASE ?>/views/cart" class="flex space-x-2 items-center transition-all hover:bg-[rgba(101,163,13,0.2)] p-2 rounded-lg">
            <i class="text-2xl ph ph-shopping-cart"></i>
          </a>
        </li>
      </ul>
      <p class="hidden text-2xl md:block">|</p>
      <ul class="items-center hidden gap-1 md:flex">
        <li class="hidden md:block">
          <a href="<?= $storeLink ?>" class="flex space-x-2 items-center transition-all hover:bg-[rgba(101,163,13,0.2)] py-1 px-2 rounded-lg">
            <i class="text-2xl ph ph-storefront"></i>
            <p>Toko</p>
          </a>
        </li>
        <li class="hidden md:block">
          <a href="<?= $BASE ?>/views/profile" class="flex space-x-2 items-center transition-all hover:bg-[rgba(101,163,13,0.2)] py-1 px-2 rounded-lg">
            <i class="text-2xl ph ph-user"></i>
            <p id="auth-user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Akun'); ?></p>
          </a>
        </li>
      </ul>

      <?php if ($isAdmin): ?>
        <ul class="flex items-center gap-2">
          <li>
            <a href="<?= $BASE ?>/views/admin" class="flex gap-1 px-3 py-1 transition-all border-2 rounded border-lime-600 bg-lime-600 hover:bg-lime-700 hover:border-lime-700 text-gray-50 text-sm">
              Admin
            </a>
          </li>
        </ul>
      <?php endif; ?>
    </div>

    <ul id="auth-guest-nav" class="flex items-center gap-2 <?= $loggedIn ? 'hidden' : '' ?>">
      <li>
        <a href="<?= $BASE ?>/views/login" class="px-3 py-1 transition-all border-2 rounded border-lime-600 hover:border-lime-700 hover:text-lime-700 text-sm">Masuk</a>
      </li>
      <li>
        <a href="<?= $BASE ?>/views/register" class="px-3 py-1 border-2 rounded border-lime-600 bg-lime-600 hover:border-lime-700 hover:bg-lime-700 text-gray-50 text-sm">Daftar</a>
      </li>
    </ul>
  </nav>
</header>



