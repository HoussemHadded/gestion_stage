/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          dark: '#0B0E14',
          surface: '#151921',
          surfaceSoft: '#1B2230',
          stroke: 'rgba(255,255,255,0.10)',
          text: '#E6E9F2',
          muted: '#9AA3B2',
          primary: '#6366F1',
          secondary: '#8B5CF6',
          success: '#34D399',
          warning: '#FBBF24',
          danger: '#FB7185',
        },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        glass: '0 20px 45px -25px rgba(0,0,0,0.75)',
      },
      backdropBlur: {
        xl: '24px',
      },
      backgroundImage: {
        'brand-gradient': 'linear-gradient(120deg, #6366F1 0%, #8B5CF6 45%, #4F46E5 100%)',
      },
    },
  },
  plugins: [],
};
