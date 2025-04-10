<?php
$pageTitle = "Daftar";
require '../../config/init.php';


// Tangani form jika dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Simpan ke database
  $sql = "INSERT INTO akun_terdaftar (email, password) VALUES ('$email', '$password')";
  if ($conn->query($sql) === TRUE) {
    $message = "Data berhasil disimpan.";
  } else {
    $message = "Error: " . $sql . "<br>" . $conn->error;
  }
  $conn->close();
}

ob_start();
?>

<style>
  /* .container1 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 900px;
    margin-top: 100px;
  } */

  .left {
    text-align: left;
  }

  .right {
    background: white;
    /* padding: 20px; */
    border-radius: 8px;
    /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); */
  }

  .submit {
    /* background: #509717; */
    color: white;
    /* padding: 10px; */
    width: 100%;
    border: none;
    /* border-radius: 5px; */
    margin-top: 10px;
    cursor: pointer;
  }

  input {
    /* width: 95%; */
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  label {
    margin-top: 10px;
  }
</style>
<h1 class="text-2xl font-bold text-center text-lime-600"><a href="../home">Zerovaa</a></h1>

<?php if (isset($message)) : ?>
  <p style="color: green; text-align: center;"><?= $message ?></p>
<?php endif; ?>

<div class="flex flex-col md:flex-row w-full min-h-screen justify-center gap-8 items-center md:-mt-8">
  <div class="left w-96 hidden md:flex flex-col items-center gap-2">
    <img class="w-full rounded-xl" src="popup1.png" alt="Zerovaa E-Commerce">
    <h3 class="text-xl font-semibold">Jual Beli Mudah di Zerovaa</h3>
    <p class="text-center">Bergabung dan rasakan kemudahan bertransaksi di Zerovaa</p>
  </div>

  <div class="right md:w-96 w-full h-min md:shadow-lg p-4 flex flex-col gap-1">
    <h3 style="text-align: center;" class="text-lime-600 text-xl font-semibold">Daftar Sekarang</h3>
    <p style="text-align: center;">Sudah punya akun? <a class="text-lime-600 hover:text-lime-700" href="../login">Masuk</a></p>

    <form action="../../controllers/auth/register_handler.php" method="POST" class="flex flex-col gap-1">
      <label for="name">Nama:</label>
      <input type="text" id="name" name="name" class="border" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" class="border" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" class="border" required>

      <button class="submit bg-lime-600 hover:bg-lime-700 rounded px-4 py-2" type="submit">Daftar</button>
    </form>

    <p class="text-sm">
      Dengan mendaftar, saya menyetujui <a class="text-lime-600" href="#">Syarat & Ketentuan</a> serta <a class="text-lime-600" href="#">Kebijakan Privasi</a>
    </p>
  </div>
</div>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
