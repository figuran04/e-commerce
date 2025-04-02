<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "toko";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memeriksa apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $idProduk = $_POST['id_produk'];
    $hargaProduk = $_POST['harga_produk'];
    $jumlah = $_POST['jumlah'];
    $subtotal = $_POST['subtotal'];

    // Memulai transaksi (Insert ke tabel transaksi)
    $sqlTransaksi = "INSERT INTO transaksi (total_harga) VALUES (?)";
    $stmt = $conn->prepare($sqlTransaksi);

    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);  // Menampilkan pesan error yang lebih jelas
    }

    $stmt->bind_param("d", $subtotal);  // "d" untuk double
    $stmt->execute();

    if ($stmt->error) {
        die("Error executing query: " . $stmt->error);  // Menampilkan pesan error jika execute gagal
    }

    $idTransaksi = $conn->insert_id;  // Mendapatkan ID transaksi yang baru dimasukkan

    // Menambahkan detail transaksi (Insert ke tabel detail_transaksi)
    $sqlDetail = "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, total_harga) VALUES (?, ?, ?, ?)";
    $stmtDetail = $conn->prepare($sqlDetail);

    if ($stmtDetail === false) {
        die("Error preparing detail query: " . $conn->error);  // Menampilkan pesan error yang lebih jelas
    }

    $stmtDetail->bind_param("iiid", $idTransaksi, $idProduk, $jumlah, $subtotal);
    $stmtDetail->execute();

    if ($stmtDetail->error) {
        die("Error executing detail query: " . $stmtDetail->error);  // Menampilkan pesan error jika execute gagal
    }

    // Jika transaksi berhasil, beri pesan sukses
    echo "Pembelian berhasil! Terima kasih telah berbelanja.";
}

// Menutup koneksi
$conn->close();
?>

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Style untuk Pop-up */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        .popup button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            cursor: pointer;
        }
        .popup .confirm {
            background-color: green;
            color: white;
        }
        .popup .cancel {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Zeroova</div>
        <ul class="nav-links">
            <li><a href="#">Kategori</a></li>
            <li><input type="text" placeholder="Cari..."></li>
            <li><a href="#"><img src="keranjang.png" alt="Keranjang" class="icon"></a></li>
            <li><a href="#"><img src="notifikasi.png" alt="Notifikasi" class="icon"></a></li>
            <li><a href="#"><img src="pesan.png" alt="Pesan" class="icon"></a></li>
            <li><a href="#">Toko</a></li>
            <li><a href="#"><img src="user.png" alt="User" class="icon"></a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="breadcrumb">
            <a href="#">Beranda</a> > <span>Detail Produk</span>
        </div>
        <div class="product-detail">
            <div class="image-section">
                <div class="image-placeholder"></div>
                <div class="thumbnail-placeholder"></div>
            </div>
            <div class="info-section">
                <h1>Masker Kf94 KF 94 Orlee Hitam black Emboss 4Ply 4 ply 3D isi 10 pcs - Putih</h1>
                <p class="sales">Terjual 100 rb+ • ⭐ 4.9 (12,9rb rating)</p>
                <p class="price">Rp8.888</p>
                <div class="color-selection">
                    <span>Pilih warna:</span>
                    <button class="color-btn">Putih</button>
                    <button class="color-btn">Hitam</button>
                    <button class="color-btn">Biru Muda</button>
                    <button class="color-btn">Merah</button>
                    <button class="color-btn">Abu-abu</button>
                </div>
                <div class="tabs">
                    <button class="tab active" onclick="showTab('detail')">Detail</button>
                    <button class="tab" onclick="showTab('info')">Info Penting</button>
                </div>
                <div id="detail" class="tab-content active">
                    <p>Kondisi: Baru</p>
                    <p>Min. Pemesanan: 1 Buah</p>
                    <p>Etalase: <a href="#">Semua Etalase</a></p>
                </div>
                <div id="info" class="tab-content">
                    <p>Harga Promo!</p>
                    <ul>
                        <li>Masker Sensi Earloop 50 pcs Exp panjang 2029 Lapisan 3 ply</li>
                        <li>Berwarna Hijau 95% filtrasi partikel</li>
                        <li>Mengurangi paparan darah dan cairan tubuh</li>
                    </ul>
                </div>
            </div>
            <div class="purchase-section">
                <p>Atur jumlah dan catatan</p>
                <div class="quantity-control">
                    <button id="decrease" class="quantity-btn">−</button>
                    <input type="number" id="quantity" value="1" min="1" max="4800">
                    <button id="increase" class="quantity-btn">+</button>
                </div>
                <p class="subtotal" id="subtotal">Subtotal: Rp8.888</p>

                <!-- Formulir untuk membeli langsung -->
                <form id="purchase-form" action="" method="POST">
                    <input type="hidden" name="id_produk" value="1"> <!-- ID Produk -->
                    <input type="hidden" name="harga_produk" value="8888"> <!-- Harga Produk -->
                    <input type="hidden" name="subtotal" id="subtotal-hidden" value="8888"> <!-- Subtotal -->
                    <input type="hidden" name="jumlah" id="quantity-hidden" value="1"> <!-- Jumlah Produk -->

                    <button type="button" id="buy-now" class="buy-now">Beli Langsung</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Pop-up Konfirmasi Pembelian -->
    <div id="confirmation-popup" class="popup">
        <div class="popup-content">
            <h3>Apakah Anda yakin ingin membeli produk ini?</h3>
            <button class="confirm" id="confirm-buy">Ya, Beli Sekarang</button>
            <button class="cancel" id="cancel-buy">Batal</button>
        </div>
    </div>

    <script>
        const quantityInput = document.getElementById("quantity");
        const subtotalHidden = document.getElementById("subtotal-hidden");
        const quantityHidden = document.getElementById("quantity-hidden");
        const price = 8888;  // Harga produk yang ada pada form hidden

        // Update subtotal dan quantity ketika jumlah berubah
        quantityInput.addEventListener("input", function() {
            let quantity = quantityInput.value;
            let subtotal = quantity * price;
            subtotalHidden.value = subtotal;
            quantityHidden.value = quantity;
            document.getElementById("subtotal").innerText = "Subtotal: Rp" + subtotal.toLocaleString();
        });

        // Menampilkan subtotal pertama kali
        document.getElementById("subtotal").innerText = "Subtotal: Rp" + (price * 1).toLocaleString();

        // Menampilkan pop-up konfirmasi ketika tombol "Beli Langsung" diklik
        document.getElementById("buy-now").addEventListener("click", function() {
            document.getElementById("confirmation-popup").style.display = "flex";
        });

        // Jika pengguna mengonfirmasi pembelian
        document.getElementById("confirm-buy").addEventListener("click", function() {
            document.getElementById("purchase-form").submit(); // Submit form
        });

        // Jika pengguna membatalkan
        document.getElementById("cancel-buy").addEventListener("click", function() {
            document.getElementById("confirmation-popup").style.display = "none"; // Menutup pop-up
        });
    </script>
</body>
</html>
<?php
