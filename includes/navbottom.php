<?php if (isset($_SESSION['user_id'])) : ?>
  <div class="h-18 md:hidden"></div>
  <div class="bg-[#E2E6CF] w-full fixed bottom-0 h-18 md:hidden shadow">
    <ul class="flex gap-4 sm:gap-16 justify-between px-5 text-lime-600">
      <li class="w-full group">
        <a href="../home" class="p-4 w-full group-hover:text-lime-700 flex flex-col items-center justify-center">
          <i class="ph-bold ph-house text-2xl"></i>
          <p class="text-sm group-hover:text-lime-700">Beranda</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="../../controllers/orders/fetch_orders.php" class="p-4 w-full group-hover:text-lime-700 flex flex-col items-center justify-center">
          <i class="ph-bold ph-scroll text-2xl"></i>
          <p class="text-sm group-hover:text-lime-700">Riwayat</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="../cart" class="p-4 w-full group-hover:text-lime-700 flex flex-col items-center justify-center">
          <i class="ph-bold ph-shopping-cart text-2xl"></i>
          <p class="text-sm group-hover:text-lime-700">Keranjang</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="../profile?id=<?= $_SESSION['user_id']; ?>" class="p-4 w-full group-hover:text-lime-700 flex flex-col items-center justify-center">
          <i class="ph-bold ph-user text-2xl"></i>
          <p class="text-sm group-hover:text-lime-700">Profil</p>
        </a>
      </li>
    </ul>
  </div>
<?php endif; ?>
