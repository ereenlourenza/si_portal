/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        playfair: ['Playfair Display', 'serif'],
        platypi: ['Platypi', 'serif'],
        lato: ['Lato', 'sans-serif'],
      },
      colors: {
        custombrown: '#231C0D',
      },
    },
  },
  plugins: [],
}
