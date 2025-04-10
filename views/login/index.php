<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zerovaa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafaf8;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            margin: auto;
            margin-top: 50px;
            text-align: left;
        }
        .login-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .login-header h3 {
            font-size: 16px;
            margin: 0;
        }
        .register-link, .help-link, .footer-link {
            color: #5c9820;
            text-decoration: none;
            font-weight: bold;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .input-hint {
            font-size: 12px;
            color: #888;
            margin-left: 5px;
        }
        .help-link {
            display: block;
            text-align: right;
            margin-bottom: 10px;
            font-size: 12px;
        }
        .next-btn, .qr-btn, .google-btn {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .next-btn {
            background: #ddd;
            color: #888;
            cursor: not-allowed;
        }
        .qr-btn, .google-btn {
            background: #fff;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
            font-size: 12px;
            color: #888;
        }
        .divider span {
            flex: 1;
            height: 1px;
            background: #ccc;
            margin: 0 10px;
        }
    </style>
</head>
<body>

    <main>
        <div class="login-container">
            <div class="login-header">
                <h3>Masuk ke E-Commerce</h3>
                <a href="#" class="register-link">Daftar</a>
            </div>
            <input type="text" class="input-field" placeholder="Nomor HP atau Email" id="loginInput">
            <p class="input-hint">Contoh: 081234567890</p>
            <a href="#" class="help-link">Butuh bantuan?</a>
            <button class="next-btn" id="nextButton" disabled>Selanjutnya</button>
            <div class="divider">
                <span></span> atau masuk dengan <span></span>
            </div>
            <button class="qr-btn">
                <i class="fas fa-qrcode"></i> Scan Kode QR
            </button>
            <button class="google-btn">
                <i class="fab fa-google"></i> Google
            </button>
        </div>
    </main>

    <script>
        document.getElementById("loginInput").addEventListener("input", function() {
            var nextButton = document.getElementById("nextButton");
            if (this.value.trim() !== "") {
                nextButton.disabled = false;
                nextButton.style.background = "#5b7c3a";
                nextButton.style.color = "#fff";
                nextButton.style.cursor = "pointer";
            } else {
                nextButton.disabled = true;
                nextButton.style.background = "#ddd";
                nextButton.style.color = "#888";
                nextButton.style.cursor = "not-allowed";
            }
        });
    </script>

</body>
</html>
