const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  darkMode: "class",
  content: ["./src/**/*.{js,ts,jsx,tsx,mdx}"],
  theme: {
    container: {
      center: true,
      padding: "2rem",
      screens: {
        "2xl": "1400px",
      },
    },
    extend: {
      colors: {
        ...defaultTheme.colors, // Menjaga warna default Tailwind
        background: "#ffffff",
        foreground: "#171717",
        "bg-color": "#f3f4f6",
        "text-color": "#111827",
        primary: {
          DEFAULT: "#10b981",
          hover: "#059669",
        },
        secondary: {
          DEFAULT: "#6b7280",
          hover: "#4b5563",
        },
        card: {
          DEFAULT: "#ffffff",
          foreground: "#171717",
        },
        shadow: "rgba(0, 0, 0, 0.1)",
      },
    },
  },
  plugins: [require("tailwindcss-animate")],
};
