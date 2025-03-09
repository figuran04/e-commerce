"use client";

import { useState, useEffect, use } from "react";
import Link from "next/link";
import DetailProduct from "@/utilities/DetailProduct";
import ImageProduct from "@/utilities/DetailProduct/ImageProduct";
import BuyProduct from "@/utilities/DetailProduct/BuyProduct";

export default function ProductPage({ params }) {
  const { id } = use(params);

  const [product, setProduct] = useState(null);
  const [quantity, setQuantity] = useState(1);
  const [selectedColor, setSelectedColor] = useState("Putih");

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const res = await fetch(`/api/product/${id}`);

        if (!res.ok) {
          throw new Error(`HTTP error! Status: ${res.status}`);
        }

        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
          throw new Error("Invalid JSON response");
        }

        const data = await res.json();
        setProduct(data);
      } catch (error) {
        console.error("Gagal mengambil data produk:", error);
      }
    };

    if (id) {
      fetchProduct();
    }
  }, [id]);

  if (!product) {
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

  return (
    <div className="container p-4">
      <div className="mb-8">
        <Link href="/" className="text-primary hover:text-primary-hover">
          Beranda
        </Link>{" "}
        &gt; <span>Detail Produk</span>
      </div>

      <div className="detail-produk">
        <ImageProduct product={product} />

        <DetailProduct
          product={product}
          selectedColor={selectedColor} // Pass selectedColor to DetailProduct
          setSelectedColor={setSelectedColor} // Pass function to set selectedColor
        />
        <BuyProduct
          product={product}
          quantity={quantity}
          setQuantity={setQuantity}
          selectedColor={selectedColor} // Pass selectedColor to BuyProduct
        />
      </div>
      <div className="glow-wrapper">
        <div className="glow green"></div>
        <div className="glow pink"></div>
      </div>
    </div>
  );
}
