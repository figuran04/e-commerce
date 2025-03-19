"use client";
import { useState } from "react";
import ThemeSwitcher from "@/components/Navbar/ThemeSwitcher";
import Link from "next/link";
import { GoogleLogo, QrCode } from "phosphor-react";

export default function LoginPage() {
  const [inputValue, setInputValue] = useState("");

  return (
    <div className="container mx-auto p-4 flex flex-col items-center">
      <h2 className="text-2xl font-bold text-primary mb-6">Zerovaa</h2>

      <div className="p-8 rounded-lg sm:shadow-md sm:w-96 w-full bg-background dark:bg-foreground">
        <div className="flex justify-between items-center text-lg font-semibold mb-6">
          <span>Masuk ke Zerovaa</span>
          <Link
            href="/register"
            className="text-primary hover:text-primary-hover font-bold text-sm"
          >
            Daftar
          </Link>
        </div>

        {/* Input */}
        <input
          type="text"
          placeholder="Nomor HP atau Email"
          className="w-full p-3 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg focus:outline-none focus:ring focus:ring-primary focus:border-primary"
          value={inputValue}
          onChange={(e) => setInputValue(e.target.value)}
        />
        <p className="text-xs mt-1">Contoh: 081234567890</p>

        <a
          href="#"
          className="block text-sm text-primary hover:text-primary-hover font-bold text-right mt-2"
        >
          Butuh bantuan?
        </a>

        {/* Tombol Selanjutnya */}
        <div className="w-full flex justify-center">
          <Link
            href="/"
            className={`w-full font-bold text-center py-3 rounded-lg mt-4 ${
              inputValue.trim()
                ? "bg-primary hover:bg-primary-hover text-white cursor-pointer"
                : "bg-gray-300 text-gray-500 cursor-not-allowed"
            }`}
            disabled={!inputValue.trim()}
          >
            Selanjutnya
          </Link>
        </div>

        {/* Garis pembatas */}
        <div className="flex items-center text-xs my-4">
          <div className="flex-grow border-b border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]"></div>
          <span className="px-2">atau masuk dengan</span>
          <div className="flex-grow border-b border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]"></div>
        </div>

        {/* Opsi Login Alternatif */}
        <button className="w-full flex items-center justify-center gap-3 p-3 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg mb-2">
          <QrCode size={24} weight="bold" />
          Scan Kode QR
        </button>

        <button className="w-full flex items-center justify-center gap-3 p-3 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg">
          <GoogleLogo size={24} weight="bold" />
          Google
        </button>
      </div>

      <div className="mt-8 text-sm flex items-center gap-2">
        <span>Copyright 2025, Zerovaa</span>
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
