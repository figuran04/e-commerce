"use client";
import Card from "@/utilities/Card";
import { useEffect, useState } from "react";

const ProductsPage = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

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
  return (
    <div className="container p-4">
      <h1 className="text-2xl font-semibold mb-4">Produk</h1>
      <div className="flex flex-col gap-2">
        <div className="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
          {products.map((product) => (
            <Card key={product.id} product={product} id={product.id} />
          ))}
        </div>
      </div>
      <div className="glow-wrapper">
        <div className="glow green"></div>
        <div className="glow pink"></div>
      </div>
    </div>
  );
};

export default ProductsPage;
