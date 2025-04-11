<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Zerovaa</title>
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
  <link rel="stylesheet" href="style.css" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");
    body {
      font-family: "Poppins", sans-serif;
    }
  </style>
</head>
<body class="bg-[#E2E6CF]">
  <header class="bg-[#E2E6CF] shadow sticky top-0 z-50">
    <div class="xl:container xl:mx-auto text-lime-600 py-4 px-8 flex justify-between items-center">
      <nav class="w-full flex">
        <div class="flex flex-col md:flex-row items-center gap-8 w-full md:w-2/3 justify-between">
          <div class="flex w-full">
            <ul class="flex items-center gap-8 md:justify-normal w-full">
              <li>
                <h1 class="text-xl font-bold text-lime-600 hover:text-lime-700">
                  <a href="produk.php">Zerovaa</a>
                </h1>
              </li>
              <li><a href="#" class="hover:text-lime-700">Kategori</a></li>
            </ul>
            <ul class="flex gap-2 items-center md:justify-normal md:hidden">
              <li><a href="#" class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-white transition-all">Masuk</a></li>
              <li><a href="#" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-white">Daftar</a></li>
            </ul>
          </div>
          <form action="search.php" method="get" class="relative w-full">
            <input type="text" name="q" placeholder="Cari produk..." class="w-full md:max-w-sm py-2 px-4 mr-4 bg-gray-100 rounded-full outline-lime-600" required />
          </form>
        </div>
        <ul class="gap-2 items-center flex-nowrap ml-0 md:ml-8 justify-end hidden md:flex md:w-1/3">
          <li><a href="#" class="px-4 py-1 rounded border-2 border-lime-600 hover:bg-lime-600 hover:text-white transition-all">Masuk</a></li>
          <li><a href="#" class="px-4 py-1 rounded border-2 border-lime-600 bg-lime-600 text-white">Daftar</a></li>
        </ul>
      </nav>
    </div>
  </header>
