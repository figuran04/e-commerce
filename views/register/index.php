<?php
$pageTitle = "Daftar";
require_once __DIR__ . '/../../config/init.php';
ob_start();
?>

<style type="text/tailwindcss">
  .left {
    @apply w-96 hidden md:flex flex-col items-center gap-2;
  }

  .right {
    @apply rounded-lg bg-white  md:w-96 w-full h-min md:shadow-lg p-4 flex flex-col gap-1;
  }

  /* .submit {
    color: white;
    width: 100%;
    border: none;
    margin-top: 10px;
    cursor: pointer;
  } */

  input {
    @apply border rounded border-gray-300 p-2 outline-lime-600;
  }

  label {
    @apply mt-3;
  }

  a{
    @apply text-lime-600 hover:text-lime-700;
  }
</style>
<h1 class="text-2xl font-bold text-center text-lime-600"><a href="../home">Zerovaa</a></h1>

<div class="flex flex-col items-center w-full gap-8 mt-20 md:flex-row md:items-center md:justify-center">

  <!-- BAGIAN KIRI (Tersembunyi ketika mobile responsive)-->
  <div class="left">
    <img class="w-full rounded-xl" src="popup1.png" alt="Zerovaa E-Commerce">
    <h2 class="text-xl font-semibold">Jual Beli Mudah di Zerovaa</h2>
    <p class="text-center">Bergabung dan rasakan kemudahan bertransaksi di Zerovaa</p>
  </div>

  <!-- BAGIAN KANAN -->
  <div class="right">
    <h2 class="mt-2 text-xl font-semibold text-center text-lime-600">Daftar Sekarang</h2>
    <p class="text-center">Sudah punya akun? <a href="../login">Masuk</a></p>
    <?php include '../partials/alerts.php'; ?>

    <form id="registerForm" class="flex flex-col gap-1">
      <label for="name">Nama:</label>
      <input type="text" id="name" name="name" placeholder="John Doe" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="example@gmail.com" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="********" required>

      <div id="registerError" class="text-red-600 text-sm hidden"></div>
      <button class="px-4 py-2 mt-3 text-gray-300 bg-gray-200 rounded cursor-not-allowed" type="submit" id="nextButton" disabled>Daftar</button>
    </form>

    <p class="text-sm">
      Dengan mendaftar, saya menyetujui <a href="../pages/legal.php#syarat">Syarat & Ketentuan</a> serta <a href="../pages/legal.php#privasi">Kebijakan Privasi</a>
    </p>
  </div>
</div>

<script>
  document.getElementById("name").addEventListener("input", checkFields);
  document.getElementById("email").addEventListener("input", checkFields);
  document.getElementById("password").addEventListener("input", checkFields);

  function checkFields() {
    var name     = document.getElementById("name").value.trim();
    var email    = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();
    var nextButton = document.getElementById("nextButton");
    if (name !== "" && email !== "" && password !== "") {
      nextButton.disabled = false;
      nextButton.classList.add("bg-lime-600", "hover:bg-lime-700", "text-gray-50", "cursor-pointer");
      nextButton.classList.remove("bg-gray-200", "text-gray-300", "cursor-not-allowed");
    } else {
      nextButton.disabled = true;
      nextButton.classList.remove("bg-lime-600", "hover:bg-lime-700", "text-gray-50", "cursor-pointer");
      nextButton.classList.add("bg-gray-200", "text-gray-300", "cursor-not-allowed");
    }
  }

  document.getElementById("registerForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    const btn   = document.getElementById("nextButton");
    const errEl = document.getElementById("registerError");
    btn.disabled = true;
    btn.textContent = "Mendaftarkan...";
    errEl.classList.add("hidden");

    try {
      const res = await fetch("../../api/gateway_auth.php?route=auth/register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name:     document.getElementById("name").value.trim(),
          email:    document.getElementById("email").value.trim(),
          password: document.getElementById("password").value.trim()
        })
      });
      const data = await res.json();

      if (data.status === "success") {
        Auth.save(data.token, data.user);
        await Auth.syncSession();
        window.location.href = "../home/";
      } else {
        errEl.textContent = data.message || "Gagal mendaftar, coba lagi.";
        errEl.classList.remove("hidden");
        btn.disabled = false;
        btn.textContent = "Daftar";
        checkFields();
      }
    } catch (err) {
      errEl.textContent = "Gagal terhubung ke server. Coba lagi.";
      errEl.classList.remove("hidden");
      btn.disabled = false;
      btn.textContent = "Daftar";
      checkFields();
    }
  });
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
