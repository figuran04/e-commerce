import { NextResponse } from "next/server";

export async function GET(req, { params }) {
  const { id } = params;

  try {
    const res = await fetch(`https://fakestoreapi.com/products/${id}`);
    if (!res.ok) throw new Error("Gagal mengambil data produk");

    const product = await res.json();
    return NextResponse.json(product, { status: 200 });
  } catch (error) {
    return NextResponse.json(
      { message: "Produk tidak ditemukan", error: error.message },
      { status: 404 }
    );
  }
}
