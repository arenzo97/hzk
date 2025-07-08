/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [ // Use 'content' instead of 'purge'
    "./index.html", // If you have an index.html at the root of your frontend project
    "./src/**/*.{js,ts,jsx,tsx,astro,html}", // Adjust based on your actual file extensions
                                            // This line typically covers most cases:
                                            // - All JS, TS, JSX, TSX files in src and its subdirectories
                                            // - Astro files (if you're using Astro)
                                            // - HTML files if you're writing raw HTML components
  ],
  darkMode: 'class', // Consider changing this to 'class' for dark mode toggling if needed
  theme: {
    extend: {},
  },
  plugins: [],
}