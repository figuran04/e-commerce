<header class="bg-[#E2E6CF] shadow sticky top-0 z-50">
  <nav class="xl:container xl:mx-auto text-lime-600 py-2 px-5 md:px-5 lg:px-8 flex flex-col gap-3 md:gap-0">
    <div class="grid grid-cols-3 w-full px-1">
      <ul class="flex items-center">
        <li>
          <h1 class="text-xl font-bold text-lime-600 hover:text-lime-700 my-1"><a href="../home">Zerovaa</a></h1>
        </li>
        <li class="mx-[20%] md:mx-[10%] text-center"><a href="../categories" class="hover:text-lime-700">Kategori</a></li>
      </ul>
      <form action="../search" method="get" class="relative w-full  max-w-fit">
        <input
          type="text"
          name="q"
          placeholder="Cari produk..."
          class="w-full py-2 px-4 pr-10 bg-gray-100 rounded-full outline-lime-600 md:block hidden"
          required>
        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-lime-600 hover:text-lime-600 md:block hidden">
          <i class="ph-bold ph-magnifying-glass text-xl"></i>
        </button>
      </form>

      <?php if (isset($_SESSION['user_id'])): ?>
        <?php
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        $plClass = $isAdmin ? 'md:pl-[15%] lg:pl-[20%]' : 'md:pl-[35%] lg:pl-[50%]';
        ?>
        <div class="flex gap-2 items-center w-full justify-end pl-0 <?= $plClass ?>">
          <ul class="flex gap-2 items-center w-full justify-end md:justify-between">
            <li class="hidden md:block">
              <a href="../../controllers/orders/fetch_orders.php"><i class="ph-bold ph-scroll text-xl"></i></a>
            </li>
            <li class="hidden md:block">
              <a href="../cart"><i class="ph-bold ph-shopping-cart text-xl"></i></a>
            </li>
            <li class="hidden md:block">
              <a href="../profile?id=<?= $_SESSION['user_id']; ?>"><i class="ph-bold ph-user text-xl"></i></a>
            </li>
            <?php if ($isAdmin): ?>
              <li>
                <a href="../admin" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 hover:bg-lime-700 hover:border-lime-700 text-gray-50 flex gap-1">Admin</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      <?php else: ?>
        <div class="flex gap-2 items-center w-full justify-end pl-0 md:pl-[15%] lg:pl-[20%]">
          <ul class="flex gap-2 items-center">
            <li>
              <a href="../login" class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-gray-50 transition-all">Masuk</a>
            </li>
            <li>
              <a href="../register" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50">Daftar</a>
            </li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
    <form action="../search" method="get" class="relative w-full md:hidden block">
      <input
        type="text"
        name="q"
        placeholder="Cari produk..."
        class="w-full py-2 px-4 pr-10 bg-gray-100 rounded-full outline-lime-600"
        required>
      <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-lime-600 hover:text-lime-600">
        <i class="ph-bold ph-magnifying-glass text-xl"></i>
      </button>
    </form>
  </nav>
</header>
