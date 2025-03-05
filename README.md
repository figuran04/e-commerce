## 1. Clone Repositori

Untuk mulai menggunakan repositori ini, ikuti langkah-langkah berikut:

1. Buka **File Explorer** di lokasi tempat Anda ingin menyimpan repositori.
2. **Shift + Klik Kanan** pada area kosong di dalam folder tersebut.
3. Pilih **"Open Git Bash here"** (atau "Git Bash di sini").
4. Jalankan perintah berikut untuk meng-clone repositori:
   ```sh
   git clone git@github.com:figuran04/e-commerce.git
   ```
5. Setelah proses clone selesai, masuk ke folder repositori dengan:
   ```sh
   cd e-commerce
   ```

## 2. Aturan Pengeditan

- Setiap pengguna disarankan **hanya mengubah** isi dari folder yang sesuai dengan nama pengguna mereka.
- Jika folder dengan nama pengguna Anda belum ada, buatlah folder dengan nama tersebut di dalam repositori.
- Jangan mengubah atau menghapus folder pengguna lain tanpa izin.
- Setelah melakukan perubahan, lakukan commit dan push dengan perintah berikut:
  ```sh
  git add .
  git commit -m "pesan perubahan"
  git push origin main
  ```

## 3. Sinkronisasi dengan Repositori

Sebelum melakukan perubahan baru, pastikan repositori Anda selalu diperbarui dengan perintah berikut:

```sh
  git pull origin main
```

Hal ini untuk menghindari konflik dengan perubahan yang dilakukan oleh pengguna lain.

# Rekomendasi Ekstensi VSCode

- Auto Close Tag
- Auto rename Tag
- HTML CSS Support
- Prettier
- Live Server
- Highlight Match
- CSS Peek
- Headwind
- Tailwindcss
- Codeium
