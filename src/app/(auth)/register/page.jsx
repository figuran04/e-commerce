"use client";
import ThemeSwitcher from "@/components/Navbar/ThemeSwitcher";
import Link from "next/link";
import { GoogleLogo } from "phosphor-react";
import { useState } from "react";

export default function RegisterPage() {
  const [inputValue, setInputValue] = useState("");
  return (
    <div className="container mx-auto p-4 flex flex-col items-center">
      <h2 className="text-2xl font-bold text-primary mb-6">Eco-Friendly</h2>
      <div className="flex gap-8">
        <div className="hidden md:flex flex-col items-center gap-2 max-w-96">
          <div className="size-80 p-0 bg-gray-200 rounded-xl overflow-hidden">
            <img
              src="/images/image.png"
              alt="image"
              className="w-full object-cover h-full"
            />
          </div>
          <h2 className="text-2xl font-semibold">
            Jual Beli Mudah di Eco-Friendly
          </h2>
          <p className="text-center text-sm">
            Gabung dan rasakan kemudahan bertransaksi di Eco-Friendly
          </p>
        </div>
        <div className="flex flex-col gap-6 p-8 rounded-lg sm:shadow-md sm:w-96 w-full bg-background dark:bg-foreground">
          <div className="flex flex-col items-center">
            <div className="text-lg font-semibold">
              <span>Daftar Sekarang</span>
            </div>
            <p className="text-sm">
              Sudah punya akun Eco-Friendly?{" "}
              <Link
                href="/login"
                className="text-primary hover:text-primary-hover font-bold"
              >
                Masuk
              </Link>
            </p>
          </div>

          <button className="w-full flex items-center justify-center gap-3 p-3 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg">
            <GoogleLogo weight="bold" size={20} />
            Google
          </button>

          <div className="flex items-center text-xs">
            <div className="flex-grow border-b border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]"></div>
            <span className="px-2">atau daftar dengan</span>
            <div className="flex-grow border-b border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]"></div>
          </div>
          <div>
            <input
              type="text"
              placeholder="Nomor HP atau Email"
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
              className="w-full p-3 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg focus:outline-none focus:ring focus:ring-primary focus:border-primary"
            />
            <p className="text-xs mt-1">Contoh: 081234567890</p>
          </div>
          <div className="flex flex-col gap-1">
            <div className="w-full flex justify-center">
              <Link
                href="/"
                className={`w-full font-bold text-center py-3 rounded-lg ${
                  inputValue.trim()
                    ? "bg-primary hover:bg-primary-hover text-white cursor-pointer"
                    : "bg-gray-300 text-gray-500 cursor-not-allowed"
                }`}
                disabled={!inputValue.trim()}
              >
                Daftar
              </Link>
            </div>
            <p className="text-sm">
              Dengan mendaftar, saya menyetujui{" "}
              <Link href="#" className="underline text-primary">
                Syarat & Ketentuan
              </Link>{" "}
              serta{" "}
              <Link href="#" className="underline text-primary">
                Kebijakan Privasi.
              </Link>
            </p>
          </div>
        </div>
      </div>
      <div className="text-sm flex items-center gap-2 mt-8">
        <span>Copyright 2025, Eco-Friendly</span>
        <span>|</span>
        <a href="#" className="text-primary hover:text-primary-hover font-bold">
          Bantuan
        </a>
      </div>
      <div className="flex w-full justify-end">
        <ThemeSwitcher />
      </div>
      <div className="glow-wrapper">
        <div className="glow green"></div>
        <div className="glow pink"></div>
      </div>
    </div>
  );
}
