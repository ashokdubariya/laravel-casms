/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary-blue': '#1a425f',
        'approval-green': '#85c34e',
        'dark-text': '#0F172A',
        'light-bg': '#F8FAFC',
        'border-gray': '#CBD5E1',
      },
      fontFamily: {
        sans: ['SN Pro', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
  future: {
    hoverOnlyWhenSupported: true,
  },
}
