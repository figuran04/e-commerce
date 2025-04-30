<?php
require_once '../../config/init.php';
$pageTitle = "Masuk";
ob_start();
?>
<style>
  .login-container {
    background: white;
    border-radius: 10px;
    text-align: left;
  }


  .register-link,
  .help-link {
    color: #5c9820;
    text-decoration: none;
  }

  .input-field {
    width: 100%;
    padding: 10px;
    margin: 10px 0 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .help-link {
    display: block;
    text-align: right;
    margin-bottom: 10px;
  }
</style>
<h1 class="text-2xl font-bold text-center text-lime-600"><a href="../home">Zerovaa</a></h1>
<div class="flex w-full justify-center gap-8 mt-20">
  <div class="login-container md:w-96 w-full h-min md:shadow-lg px-4 py-8 flex flex-col gap-1">
    <div class="flex justify-between flex-col">
      <h2 class="text-xl font-bold text-lime-600">Masuk ke Zerovaa</h2>
      <div class="text-end">
        <span>Belum punya akun? </span>
        <a href="../register" class="register-link">Daftar</a>
      </div>
    </div>
    <?php if (isset($_SESSION['error'])) : ?>
      <p style="color: red;"><?= $_SESSION['error']; ?></p>
      <?php unset($_SESSION['error']); // Menghapus error setelah ditampilkan
      ?>
    <?php endif; ?>
    <form action="../../controllers/auth/login_handler.php" method="POST" class="flex flex-col w-full gap-1">
      <label for="email">Email:</label>
      <input class="input-field" type="email" id="email" name="email" class="border" placeholder="example@gmail.com" required>

      <label for="password">Password:</label>
      <input class="input-field" type="password" id="password" name="password" class="border" placeholder="********" required>

      <a href="#" class="help-link text-sm">Butuh bantuan?</a>
      <button class="rounded px-4 py-2 bg-gray-100 text-gray-200 cursor-not-allowed" type="submit" id="nextButton" disabled>Masuk</button>
    </form>
  </div>
</div>

<script>
  document.getElementById("email").addEventListener("input", checkFields);
  document.getElementById("password").addEventListener("input", checkFields);

  function checkFields() {
    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();
    var nextButton = document.getElementById("nextButton");

    // Jika kedua field terisi, aktifkan tombol
    if (email !== "" && password !== "") {
      nextButton.disabled = false;
      nextButton.classList.add("bg-lime-600", "hover:bg-lime-700", "text-gray-50", "cursor-pointer");
      nextButton.classList.remove("bg-gray-100", "text-gray-200", "cursor-not-allowed");
    } else {
      // Jika salah satu atau keduanya kosong, nonaktifkan tombol
      nextButton.disabled = true;
      nextButton.classList.remove("bg-lime-600", "hover:bg-lime-700", "text-gray-50", "cursor-pointer");
      nextButton.classList.add("bg-gray-100", "text-gray-200", "cursor-not-allowed");
    }
  }
</script>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
