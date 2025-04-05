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

<style type="text/tailwindcss">
  .button {
    @apply bg-lime-500 hover:bg-lime-400 text-white font-semibold rounded px-4 py-1 w-min;
  }
</style>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zerovaa - <?= $pageTitle ?></title>
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container1 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 900px;
            margin-top: 100px;
        }
        .left {
            width: 35%;
            text-align: left;
        }
        .right {
            width: 50%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .submit {
            background:#509717;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        input {
            width: 95%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <?php if (isset($message)) : ?>
        <p style="color: green; text-align: center;"><?= $message ?></p>
    <?php endif; ?>

    <div class="container1">
        <div class="left">
            <img style="width: 250px" src="popup1.png" alt="Zerovaa E-Commerce">
            <h3>Jual Beli Mudah di E-Commerce</h3>
            <p>Bergabung dan rasakan kemudahan bertransaksi di E-Commerce</p>
        </div>

        <div class="right">
            <h3 style="text-align: center;color: #509717">Daftar Sekarang</h3>
            <p style="text-align: center;">Sudah punya akun? <a style="color: #509717;" href="../login">Masuk</a></p>

            <form action="" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button class="submit" type="submit">Daftar</button>
            </form>

            <p style="font-size: 12px;">Dengan mendaftar, saya menyetujui <a style="color: #509717;" href="#">Syarat & Ketentuan</a> serta <a style="color: #509717;" href="#">Kebijakan Privasi</a></p>
        </div>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>
