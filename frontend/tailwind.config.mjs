/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,ts,tsx}'],
  theme: {
    extend: {
      colors: {
        'primary': '#0e7c7bff',       // Teal - Main action color
        'primary-hover': '#17bebbff', // Verdigris - Lighter teal for primary hovers/accents
        'secondary': '#d62246ff',     // Rusty Red - Emphasis, secondary actions
        'background-light': '#d4f4ddff', // Nyanza - Light background sections
        'dark': '#4b1d3fff',          // Violet JTC - Dark backgrounds, main text color, headers/footers
      },
      fontFamily: {
        sans: ['"Source Sans Pro"', 'sans-serif'],
        serif: ['"Playfair Display"', 'serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
};