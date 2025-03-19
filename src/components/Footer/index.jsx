const Footer = () => {
  return (
    <footer className="border-t border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] py-6 mt-12">
      <div className="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        {/* Tentang */}
        <div>
          <h3 className="text-lg font-semibold">Zerovaa</h3>
          <ul className="mt-2 space-y-1">
            <li>Tentang Kami</li>
            <li>Karir</li>
            <li>Blog</li>
            <li>Kontak</li>
            <li>Bantuan</li>
          </ul>
        </div>

        {/* Beli & Jual */}
        <div>
          <h3 className="text-lg font-semibold">Beli</h3>
          <ul className="mt-2 space-y-1">
            <li>Cara Belanja</li>
            <li>Metode Pembayaran</li>
            <li>Pengiriman</li>
          </ul>
          <h3 className="mt-4 text-lg font-semibold">Jual</h3>
          <ul className="mt-2 space-y-1">
            <li>Cara Berjualan</li>
            <li>Keuntungan Jualan</li>
          </ul>
        </div>

        {/* Keamanan & Privasi */}
        <div>
          <h3 className="text-lg font-semibold">Keamanan & Privasi</h3>
          <ul className="mt-2 space-y-1">
            <li>Syarat & Ketentuan</li>
            <li>Kebijakan Privasi</li>
            <li>Garansi Produk</li>
            <li>Pusat Resolusi</li>
          </ul>
        </div>

        {/* Aplikasi & Media Sosial */}
        <div>
          <h3 className="text-lg font-semibold">Ikuti Kami</h3>
          <div className="flex space-x-3 mt-2">
            <span className="bg-gray-300 p-2 rounded">📘</span> {/* Facebook */}
            <span className="bg-gray-300 p-2 rounded">🐦</span> {/* Twitter */}
            <span className="bg-gray-300 p-2 rounded">📸</span>{" "}
            {/* Instagram */}
          </div>
          <h3 className="mt-4 text-lg font-semibold">Unduh Aplikasi</h3>
          <div className="flex space-x-2 mt-2">
            <button className="bg-black text-white px-4 py-1 rounded text-sm">
              App Store
            </button>
            <button className="bg-black text-white px-4 py-1 rounded text-sm">
              Google Play
            </button>
          </div>
        </div>
      </div>

      {/* Copyright */}
      <div className="border-t border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] mt-6 pt-4 text-center text-sm">
        © 2025 Zerovaa. Semua hak dilindungi.
      </div>
    </footer>
  );
};

export default Footer;
