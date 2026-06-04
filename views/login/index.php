<?php
require_once __DIR__ . '/../../config/init.php';
$pageTitle = "Masuk";
ob_start();
?>
<style type="text/tailwindcss">
  .login-container {
    @apply bg-white rounded-lg text-left md:w-96 w-full h-min md:shadow-lg p-4 flex flex-col gap-1;
  }

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
<div class="flex w-full justify-center mt-20">
  <div class="login-container">
    <h2 class="text-lime-600 text-xl font-semibold text-center mt-2">Masuk ke Zerovaa</h2>
    <div class="text-center">
      <span>Belum punya akun? </span><a href="../register">Daftar</a>
    </div>
    <?php include '../partials/alerts.php'; ?>
    <form id="loginForm" class="flex flex-col w-full gap-1">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="example@gmail.com" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="********" required>

      <a href="../pages/help.php" class="text-right text-sm my-2">Butuh bantuan?</a>
      <div id="loginError" class="text-red-600 text-sm hidden"></div>
      <button class="rounded px-4 py-2 bg-gray-200 text-gray-300 cursor-not-allowed" type="submit" id="nextButton" disabled>Masuk</button>
    </form>
  </div>
</div>

<script>
  document.getElementById("email").addEventListener("input", checkFields);
  document.getElementById("password").addEventListener("input", checkFields);

  function checkFields() {
    var email    = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();
    var nextButton = document.getElementById("nextButton");
    if (email !== "" && password !== "") {
      nextButton.disabled = false;
      nextButton.classList.add("bg-lime-600", "hover:bg-lime-700", "text-gray-50", "cursor-pointer");
      nextButton.classList.remove("bg-gray-200", "text-gray-300", "cursor-not-allowed");
    } else {
      nextButton.disabled = true;
      nextButton.classList.remove("bg-lime-600", "hover:bg-lime-700", "text-gray-50", "cursor-pointer");
      nextButton.classList.add("bg-gray-200", "text-gray-300", "cursor-not-allowed");
    }
  }

  document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    const btn  = document.getElementById("nextButton");
    const errEl = document.getElementById("loginError");
    btn.disabled = true;
    btn.textContent = "Memproses...";
    errEl.classList.add("hidden");

    try {
      const res = await fetch("/5/e-commerce/api/auth/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email:    document.getElementById("email").value.trim(),
          password: document.getElementById("password").value.trim()
        })
      });
      const data = await res.json();

      if (data.status === "success") {
        // Simpan token dan data user ke localStorage
        Auth.save(data.token, data.user);
        // Sinkronkan ke session PHP, lalu redirect
        await Auth.syncSession();
        window.location.href = "/5/e-commerce/views/home";
      } else {
        errEl.textContent = data.message || "Email atau password salah.";
        errEl.classList.remove("hidden");
        btn.disabled = false;
        btn.textContent = "Masuk";
        checkFields();
      }
    } catch (err) {
      errEl.textContent = "Gagal terhubung ke server. Coba lagi.";
      errEl.classList.remove("hidden");
      btn.disabled = false;
      btn.textContent = "Masuk";
      checkFields();
    }
  });
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
