"use client";
import localFont from "next/font/local";
import { Geist, Geist_Mono, Poppins } from "next/font/google";
import "./globals.css";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { usePathname } from "next/navigation";
import { Providers } from "./providers";
import { useEffect, useState } from "react";

// const geistSans = Geist({
//   variable: "--font-geist-sans",
//   subsets: ["latin"],
// });

// const geistMono = Geist_Mono({
//   variable: "--font-geist-mono",
//   subsets: ["latin"],
// });

const poppins = Poppins({
  variable: "--font-poppins",
  subsets: ["latin"],
  weight: ["400", "600", "700"],
});

export default function RootLayout({ children }) {
  const [hideHeaderFooter, setHideHeaderFooter] = useState(false);
  const pathname = usePathname();

  useEffect(() => {
    setHideHeaderFooter(pathname === "/login" || pathname === "/register");
  }, [pathname]);

  return (
    <html lang="en">
      <body suppressHydrationWarning className={`font-poppins antialiased`}>
        <Providers>
          {!hideHeaderFooter && <Navbar />}
          <main className="flex-1 mt-28 body">{children}</main>
          {!hideHeaderFooter && <Footer />}
        </Providers>
      </body>
    </html>
  );
}
