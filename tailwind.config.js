// tailwind.config.js
module.exports = {
  darkMode: 'class', // atau 'media' jika ingin mengikuti preferensi OS tanpa toggle manual
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue", // Jika menggunakan Vue
  ],
  theme: {
    extend: {
      // ... ekstensi tema Anda (colors, fontFamily, dll.)
    },
  },
  plugins: [],
}