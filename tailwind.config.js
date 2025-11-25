/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './application/views/**/*.php',
    './application/views/**/*.html',
    './application/views/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#f8fafc',
          100: '#f1f5f9',
          300: '#cbd5e1',
          500: '#475569',
          600: '#334155',
          800: '#1e293b',
          950: '#0f172a'
        }
      },
      fontFamily: {
        inter: ['Inter', 'system-ui', 'sans-serif'],
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        pulseSubtle: {
          '0%, 100%': { opacity: '0.8' },
          '50%': { opacity: '0.4' },
        },
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-out',
        'slide-up': 'slideUp 0.8s ease-out',
        'pulse-subtle': 'pulseSubtle 2s infinite',
      },
    },
  },
  plugins: [],
};