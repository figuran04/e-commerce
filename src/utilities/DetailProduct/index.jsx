import Link from "next/link";
import { Star } from "phosphor-react";
import { useState } from "react";

const DetailProduct = ({ product, selectedColor, setSelectedColor }) => {
  const [activeTab, setActiveTab] = useState("detail");
  return (
    <div className="mid-produk">
      <div>
        <h1 className="text-2xl font-bold">{product.title}</h1>
        <div className="text-yellow-500 flex items-center gap-2">
          <Star size={16} weight="fill" />
          {product.rating.rate} / 5 ({product.rating.count} ulasan)
          {/* <span className="">Kategori: {product.category}</span> */}
        </div>
      </div>
      <p className="text-2xl font-semibold">${product.price}</p>

      <div className="flex flex-col gap-2">
        <p>Pilih warna: {selectedColor}</p>
        <div className="flex items-center gap-2">
          <button
            className={`px-4 py-1 border-2 ${
              selectedColor === "Putih"
                ? "border-primary text-primary"
                : "border-[rgba(0,0,0,0.2)]"
            } rounded-md`}
            onClick={() => setSelectedColor("Putih")}
          >
            Putih
          </button>
          <button
            className={`px-4 py-1 border-2 ${
              selectedColor === "Hitam"
                ? "border-primary text-primary"
                : "border-[rgba(0,0,0,0.2)]"
            } rounded-md`}
            onClick={() => setSelectedColor("Hitam")}
          >
            Hitam
          </button>
          <button
            className={`px-4 py-1 border-2 ${
              selectedColor === "Biru"
                ? "border-primary text-primary"
                : "border-[rgba(0,0,0,0.2)]"
            } rounded-md`}
            onClick={() => setSelectedColor("Biru")}
          >
            Biru
          </button>
          <button
            className={`px-4 py-1 border-2 ${
              selectedColor === "Merah"
                ? "border-primary text-primary"
                : "border-[rgba(0,0,0,0.2)]"
            } rounded-md`}
            onClick={() => setSelectedColor("Merah")}
          >
            Merah
          </button>
        </div>
      </div>

      <div>
        <div className="flex items-center gap-4 border-b border-t border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]">
          <button
            onClick={() => setActiveTab("detail")}
            className={`p-2 ${
              activeTab === "detail"
                ? "border-b-2 border-primary text-primary"
                : ""
            }`}
          >
            Detail
          </button>
          <button
            onClick={() => setActiveTab("info")}
            className={`p-2 ${
              activeTab === "info"
                ? "border-b-2 border-primary text-primary"
                : ""
            }`}
          >
            Info Penting
          </button>
        </div>
        <div className="mt-2">
          {activeTab === "detail" ? (
            <div>
              <p>Kondisi: Baru</p>
              <p>Min. Pemesanan: 1 Buah</p>
              <p>
                Etalase:{" "}
                <Link
                  href="/"
                  className="text-primary hover:text-primary-hover"
                >
                  Semua Etalase
                </Link>
              </p>
            </div>
          ) : (
            <div>
              <p>Harga Promo!</p>
              <ul className="ml-5 list-disc">
                <li>Produk berkualitas tinggi</li>
                <li>Filtrasi partikel 95%</li>
                <li>Nyaman digunakan</li>
              </ul>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default DetailProduct;
