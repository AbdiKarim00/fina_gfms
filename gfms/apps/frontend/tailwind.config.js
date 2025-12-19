/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  // Disable Tailwind's base styles to avoid conflicts with Ant Design
  corePlugins: {
    preflight: false,
  },
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        // Kenya Government Colors
        kenya: {
          green: '#006600',
          red: '#FF0000',
          black: '#000000',
          white: '#FFFFFF',
        },
        // Keep existing colors for backward compatibility
        primary: {
          DEFAULT: '#0D6EFD',
          50: '#E8F1FF',
          100: '#D5E5FF',
          200: '#B0CCFF',
          300: '#8BB2FF',
          400: '#6699FF',
          500: '#0D6EFD',
          600: '#0052CC',
          700: '#003D99',
          800: '#002966',
          900: '#001433',
        },
        secondary: {
          DEFAULT: '#6C757D',
          50: '#F8F9FA',
          100: '#E9ECEF',
          200: '#DEE2E6',
          300: '#CED4DA',
          400: '#ADB5BD',
          500: '#6C757D',
          600: '#5A6268',
          700: '#495057',
          800: '#343A40',
          900: '#212529',
        },
        success: '#198754',
        danger: '#DC3545',
        warning: '#FFC107',
        info: '#0DCAF0',
      },
    },
  },
  plugins: [],
};
