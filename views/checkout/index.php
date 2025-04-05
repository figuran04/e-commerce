<?php
require_once '../../config/init.php';
$pageTitle = "Checkout";
include '../../includes/data/get_checkout_items.php';
// if (empty($cart_items)) {
//   header("Location: ../cart?error=empty_cart");
//   exit;
// }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zerovaa - Checkout</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    />
    <link rel="stylesheet" href="style.css" />
    <style>
        .section {
            margin: 20px;
            padding: 20px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .total {
            margin: 20px;
            padding: 20px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
        }
    </style>
  </head>

  <body class="bg-[#E2E6CF]">
  <header class="bg-[#E2E6CF] shadow sticky top-0 z-50">
      <div
        class="xl:container xl:mx-auto text-lime-600 py-4 px-8 flex justify-between items-center"
      >
        <nav class="w-full flex">
          <div
            class="flex flex-col md:flex-row items-center gap-8 w-full md:w-2/3 justify-between"
          >
            <div class="flex w-full">
              <ul class="flex items-center gap-8 md:justify-normal w-full">
                <li>
                  <h1
                    class="text-xl font-bold text-lime-600 hover:text-lime-700"
                  >
                    <a href="../home">Zerovaa</a>
                  </h1>
                </li>
                <li>
                  <a href="../categories" class="hover:text-lime-700"
                    >Kategori</a
                  >
                </li>
              </ul>
              <ul class="flex gap-2 items-center md:justify-normal md:hidden">
                <li>
                  <a
                    href="../login"
                    class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-gray-50 transition-all"
                    >Masuk</a
                  >
                </li>
                <li>
                  <a
                    href="../register"
                    class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50"
                    >Daftar</a
                  >
                </li>
              </ul>
            </div>
            <form action="../search" method="get" class="relative w-full">
              <input
                type="text"
                name="q"
                placeholder="Cari produk..."
                class="w-full md:max-w-sm py-2 px-4 mr-4 bg-gray-100 rounded-full outline-lime-600"
                required
              />
            </form>
          </div>
          <ul
            class="gap-2 items-center flex-nowrap ml-0 md:ml-8 justify-end hidden md:flex md:w-1/3"
          >
            <li>
              <a
                href="../login"
                class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-gray-50 transition-all"
                >Masuk</a
              >
            </li>
            <li>
              <a
                href="../register"
                class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50"
                >Daftar</a
              >
            </li>
          </ul>
        </nav>
      </div>
  </header>

    <div class="bg-gray-50">
      <main class="container mx-auto p-4 flex flex-col gap-4 min-h-screen">
        <h1 class="text-2xl font-bold">Checkout</h1>

        <div class="container">
            <div class="section">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>Rp<?= number_format($item['price'], 0, ',', '.'); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>Rp<?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total">
                <p>Total: Rp<?= number_format($total_price, 0, ',', '.'); ?></p>
                <form action="../../controllers/orders/process_order.php" method="POST">
                    <button type="submit" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-gray-50">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
      </main>
    </div>

    <footer class="border-t border-[rgba(0,0,0,0.2)] bg-gray-50">
      <div
        class="container mx-auto px-6 grid grid-cols-1 py-6 md:grid-cols-4 gap-6"
      >
        <div>
          <h3 class="text-lg font-semibold text-[#509717]">Zerovaa</h3>
          <ul class="mt-2 space-y-1">
            <li class="cursor-pointer hover:text-[#509717]">Tentang Kami</li>
            <li class="cursor-pointer hover:text-[#509717]">Karir</li>
            <li class="cursor-pointer hover:text-[#509717]">Blog</li>
            <li class="cursor-pointer hover:text-[#509717]">Kontak</li>
            <li class="cursor-pointer hover:text-[#509717]">Bantuan</li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-[#509717]">Beli</h3>
          <ul class="mt-2 space-y-1">
            <li class="cursor-pointer hover:text-[#509717]">Cara Belanja</li>
            <li class="cursor-pointer hover:text-[#509717]">
              Metode Pembayaran
            </li>
            <li class="cursor-pointer hover:text-[#509717]">Pengiriman</li>
          </ul>
          <h3 class="mt-4 text-lg font-semibold text-[#509717]">Jual</h3>
          <ul class="mt-2 space-y-1">
            <li class="cursor-pointer hover:text-[#509717]">Cara Berjualan</li>
            <li class="cursor-pointer hover:text-[#509717]">
              Keuntungan Jualan
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-[#509717]">
            Keamanan & Privasi
          </h3>
          <ul class="mt-2 space-y-1">
            <li class="cursor-pointer hover:text-[#509717]">
              Syarat & Ketentuan
            </li>
            <li class="cursor-pointer hover:text-[#509717]">
              Kebijakan Privasi
            </li>
            <li class="cursor-pointer hover:text-[#509717]">Garansi Produk</li>
            <li class="cursor-pointer hover:text-[#509717]">Pusat Resolusi</li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-[#509717]">Ikuti Kami</h3>
          <div class="flex space-x-3 mt-2">
            <span
              class="bg-[#E2E6CF] p-2 rounded hover:scale-105 hover:bg-[#509717] transition-all cursor-pointer"
              >📘</span
            >
            <span
              class="bg-[#E2E6CF] p-2 rounded hover:scale-105 hover:bg-[#509717] transition-all cursor-pointer"
              >🐦</span
            >
            <span
              class="bg-[#E2E6CF] p-2 rounded hover:scale-105 hover:bg-[#509717] transition-all cursor-pointer"
              >📸</span
            >
          </div>
          <h3 class="mt-4 text-lg font-semibold text-[#509717]">
            Unduh Aplikasi
          </h3>
          <div class="flex space-x-2 mt-2">
            <button class="bg-black text-white px-4 py-1 rounded text-sm">
              App Store
            </button>
            <button class="bg-black text-white px-4 py-1 rounded text-sm">
              Google Play
            </button>
          </div>
        </div>
      </div>
      <div
        class="border-t border-[rgba(0,0,0,0.2)] text-[#509717] py-4 text-center text-sm bg-[#E2E6CF]"
      >
        © 2025 Zerovaa. Semua hak dilindungi.
      </div>
    </footer>
  </body>
</html>
