<header class="bg-[#E2E6CF] shadow sticky top-0 z-50">
  <div class="xl:container xl:mx-auto text-lime-600 py-4 px-8 flex justify-between items-center">
    <nav class="w-full flex">
      <div class="flex flex-col md:flex-row items-center gap-8 w-full md:w-2/3 justify-between">
        <div class="flex w-full">
          <ul class="flex items-center gap-8 md:justify-normal w-full">
            <li>
              <h1 class="text-xl font-bold text-lime-600 hover:text-lime-700"><a href="../home">Zerovaa</a></h1>
            </li>
            <li><a href="../categories" class="hover:text-lime-700">Kategori</a></li>
          </ul>
          <ul class="flex gap-2 items-center md:justify-normal md:hidden">
            <?php if (isset($_SESSION['user_id'])) : ?>
              <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) : ?>
                <li><a href="../admin" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50 flex gap-1"><span class="hidden md:block">Dashboard</span>Admin</a></li>
              <?php endif; ?>

            <?php else : ?>
              <li><a href="../login" class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-gray-50 transition-all">Masuk</a></li>
              <li><a href="../register" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50">Daftar</a></li>
            <?php endif; ?>
          </ul>
        </div>
        <form action="../search" method="get" class="relative w-full">
          <input type="text" name="q" placeholder="Cari produk..." class="w-full md:max-w-sm py-2 px-4 mr-4 bg-gray-100 rounded-full outline-lime-600" required>
        </form>
      </div>
      <?php if (isset($_SESSION['user_id'])) : ?>
        <ul class="gap-2 items-center flex-nowrap xl:space-x-6 lg:space-x-2 md:space-x-0 ml-0 md:ml-8 justify-end hidden md:flex md:w-1/3">
          <li class="hidden md:block"><a href="../../controllers/orders/fetch_orders.php"><i class="ph-bold ph-scroll text-xl mx-4"></i></a></li>
          <li class="hidden md:block"><a href="../cart"><i class="ph-bold ph-shopping-cart text-xl mx-4"></i></a></li>
          <li class="hidden md:block"><a href="../profile?id=<?= $_SESSION['user_id']; ?>"><i class="ph-bold ph-user text-xl mx-4"></i></a></li>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) : ?>
            <li><a href="../admin" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 hover:bg-lime-700 hover:border-lime-700 text-gray-50 flex gap-1"><span class="hidden lg:block">Dashboard</span>Admin</a></li>
          <?php endif; ?>
        </ul>

      <?php else : ?>
        <ul class="gap-2 items-center flex-nowrap ml-0 md:ml-8 justify-end hidden md:flex md:w-1/3">
          <li><a href="../login" class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-gray-50 transition-all">Masuk</a></li>
          <li><a href="../register" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50">Daftar</a></li>
        </ul>
      <?php endif; ?>
    </nav>
  </div>
</header>
