"use client";
import Carousel from "@/components/Carousel";
import Pilihan from "@/components/Kategori/Pilihan";
import Card from "@/utilities/Card";
import Link from "next/link";
import { useEffect, useState } from "react";

export default function Home() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [isTwoColumn, setIsTwoColumn] = useState(false);

  useEffect(() => {
    async function fetchProducts() {
      try {
        const response = await fetch("/api/products");
        const data = await response.json();
        setProducts(data);
      } catch (error) {
        console.error("Error fetching products:", error);
      } finally {
        setLoading(false);
      }
    }

    fetchProducts();
  }, []);

  // Deteksi layar kecil untuk tampilan 2 kolom vertikal
  useEffect(() => {
    function handleResize() {
      setIsTwoColumn(window.innerWidth < 1024); // Aktifkan 2 kolom jika < 1024px (breakpoint lg)
    }

    handleResize();
    window.addEventListener("resize", handleResize);

    return () => window.removeEventListener("resize", handleResize);
  }, []);

  if (loading) {
    return (
      <div className="container p-4">
        <p className="text-center">Loading products...</p>
        <div className="glow-wrapper">
          <div className="glow green"></div>
          <div className="glow pink"></div>
        </div>
      </div>
    );
  }

  // Pisahkan produk ke dalam 2 kolom dengan urutan vertikal
  const leftColumn = products.filter((_, index) => index % 2 === 0);
  const rightColumn = products.filter((_, index) => index % 2 !== 0);

  return (
    <main className="container p-4 min-h-screen">
      <div className="flex flex-col gap-6">
        <Carousel />
        <Pilihan />
        <div className="flex flex-col gap-2">
          {isTwoColumn ? (
            // Layout 2 kolom vertikal
            <div className="grid grid-cols-2 gap-6">
              <div className="dua-kolom">
                {leftColumn.map((product) => (
                  <Card key={product.id} product={product} id={product.id} />
                ))}
              </div>
              <div className="dua-kolom">
                {rightColumn.map((product) => (
                  <Card key={product.id} product={product} id={product.id} />
                ))}
              </div>
            </div>
          ) : (
            // Layout default (lebih dari 2 kolom)
            <div className="grid sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
              {products.slice(0, 18).map((product) => (
                <Card key={product.id} product={product} id={product.id} />
              ))}
            </div>
          )}
          <div className="flex w-full justify-center">
            <Link
              href="/products"
              className="bg-background dark:bg-foreground border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]  rounded-xl"
            >
              <h3 className="px-4 py-1 text-primary hover:text-primary-hover">
                Lihat Semua
              </h3>
            </Link>
          </div>
        </div>
      </div>
      <div className="glow-wrapper">
        <div className="glow green"></div>
        <div className="glow pink"></div>
      </div>
    </main>
  );
}
