<?php if (isset($_SESSION['user_id'])) : ?>
  <?php $storeLink = isset($_SESSION['store_id']) ? '../store/?id=' . $_SESSION['store_id'] : '../store/'; ?>
  <div class="h-18 md:hidden"></div>
  <div class="bg-[#E2E6CF] w-full fixed bottom-0 h-14 md:hidden shadow">
    <ul class="grid w-full grid-cols-5 px-4 space-x-1 text-lime-600">
      <li class="w-full group">
        <a href="../home" class="flex flex-col items-center justify-center w-full py-2 group-hover:text-lime-700">
          <i class="text-2xl ph ph-house"></i>
          <p class="text-xs group-hover:text-lime-700">Beranda</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="../orders/user_orders.php" class="flex flex-col items-center justify-center w-full py-2 group-hover:text-lime-700">
          <i class="text-2xl ph ph-scroll"></i>
          <p class="text-xs group-hover:text-lime-700">Pesanan</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="../categories" class="flex flex-col items-center justify-center w-full py-2 group-hover:text-lime-700">
          <i class="text-2xl ph ph-squares-four"></i>
          <p class="text-xs group-hover:text-lime-700">Kategori</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="<?= $storeLink ?>" class="flex flex-col items-center justify-center w-full py-2 group-hover:text-lime-700">
          <i class="text-2xl ph ph-storefront"></i>
          <p class="text-xs group-hover:text-lime-700">Toko</p>
        </a>
      </li>
      <li class="w-full group">
        <a href="../profile?id=<?= $_SESSION['user_id']; ?>" class="flex flex-col items-center justify-center w-full py-2 group-hover:text-lime-700">
          <i class="text-2xl ph ph-user"></i>
          <p class="text-xs group-hover:text-lime-700">Profil</p>
        </a>
      </li>
    </ul>
  </div>
<?php endif; ?>
