import { NextResponse } from "next/server";

export async function GET() {
  try {
    const response = await fetch("https://fakestoreapi.com/products");
    const products = await response.json();

    return NextResponse.json(products, { status: 200 });
  } catch (error) {
    return NextResponse.json({ error: "Failed to fetch products" }, { status: 500 });
  }
}
