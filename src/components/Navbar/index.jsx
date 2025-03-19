"use client";

import { useState } from "react";
import {
  Bell,
  EnvelopeSimple,
  MagnifyingGlass,
  ShoppingCartSimple,
  List,
  X,
} from "phosphor-react";
import Link from "next/link";
import ThemeSwitcher from "./ThemeSwitcher";

const Navbar = () => {
  const [menuOpen, setMenuOpen] = useState(false);

  return (
    <div className="bg-background dark:bg-foreground fixed top-0 z-50 w-full border-b border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] shadow-sm">
      {/* Navbar Atas */}
      <div className="flex items-center justify-between px-6 py-3 md:px-8">
        {/* Kiri: Logo & Kategori */}
        <div className="flex items-center gap-6 min-w-56">
          <Link
            href="/"
            className="text-xl font-bold text-primary hover:text-primary-hover line-clamp-1"
          >
            Zerovaa
          </Link>
          <p className="hidden font-medium md:block">Kategori</p>
        </div>

        {/* Tengah: Pencarian */}
        <div className="items-center hidden w-full max-w-md gap-2 px-4 py-2 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-md md:flex">
          <MagnifyingGlass size={24} weight="bold" className="text-gray-400" />
          <input
            type="text"
            placeholder="Cari produk..."
            className="w-full outline-none bg-transparent text-foreground text-sm"
          />
        </div>

        {/* Kanan: Ikon & Tombol Dark Mode */}
        <div className="items-center hidden gap-6 md:flex">
          <Link href="/cart" className="">
            <ShoppingCartSimple size={24} weight="bold" className="" />{" "}
          </Link>
          <Link href="/notification">
            <Bell size={24} weight="bold" className="" />
          </Link>
          <Link href="/message">
            <EnvelopeSimple size={24} weight="bold" className="" />
          </Link>
          <div className="flex items-center gap-2">
            <Link
              href="/login"
              className="w-full p-2 px-4 text-white bg-primary hover:bg-primary-hover rounded-md"
            >
              Masuk
            </Link>
            <Link
              href="/register"
              className="w-full p-2 px-4 border-2 border-primary rounded-md text-primary"
            >
              Daftar
            </Link>
          </div>

          {/* Tombol Dark Mode */}
          <ThemeSwitcher />
        </div>

        {/* Mobile Menu Button */}
        <button className="md:hidden" onClick={() => setMenuOpen(!menuOpen)}>
          {menuOpen ? <X size={28} /> : <List size={28} />}
        </button>
      </div>

      {/* Mobile Menu */}
      {menuOpen && (
        <div className="flex flex-col items-center py-4 space-y-3 md:hidden bg-background dark:bg-foreground border-t border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]">
          <div className="flex w-full max-w-xs gap-2 px-4 py-2 border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-md">
            <MagnifyingGlass size={24} weight="bold" className="" />
            <input
              type="text"
              placeholder="Cari produk..."
              className="w-full bg-transparent outline-none"
            />
          </div>
          <div className="flex items-center gap-6">
            <Link href="/cart" className="">
              <ShoppingCartSimple size={24} weight="bold" className="" />{" "}
            </Link>
            <Link href="/notification">
              <Bell size={24} weight="bold" className="" />
            </Link>
            <Link href="/message">
              <EnvelopeSimple size={24} weight="bold" className="" />
            </Link>
            <div className="flex items-center gap-2">
              <Link
                href="/login"
                className="w-full p-2 px-4 text-white bg-primary rounded-md"
              >
                Masuk
              </Link>
              <Link
                href="/register"
                className="w-full p-2 px-4 border-2 border-primary rounded-md  hover:text-primary-hover"
              >
                Daftar
              </Link>
            </div>
          </div>

          {/* Tombol Dark Mode di Mobile */}
          <ThemeSwitcher />
        </div>
      )}

      {/* Sub Navbar */}
      <div className="items-center justify-center hidden gap-4 py-2 md:flex">
        <p>Trend produk</p>
        <p>Trend produk</p>
        <p>Trend produk</p>
        <p>Trend produk</p>
      </div>
    </div>
  );
};

export default Navbar;
