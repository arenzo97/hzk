/** @type {import('tailwindcss/types/config').Config} */
import defaultTheme from 'tailwindcss/defaultTheme'; // <<< Make sure this import is present

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
        // You can keep your specific font stack if 'SF Pro Display' etc. are correctly loaded
        sans: ['"SF Pro Display"', '"Helvetica Neue"', 'Segoe UI', 'Roboto', ...defaultTheme.fontFamily.sans],
        serif: ['"Playfair Display"', 'Georgia', ...defaultTheme.fontFamily.serif],
      },
      
      // <<< ADD THESE KEYFRAMES AND ANIMATIONS >>>
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInDown: {
          '0%': { opacity: '0', transform: 'translateY(-20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        blob: { // For the background blobs in HomepageLayout
          '0%': { transform: 'translate(0px, 0px) scale(1)' },
          '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
          '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
          '100%': { transform: 'translate(0px, 0px) scale(1)' },
        },
        pulseShadow: { // For the glass button hover effect
            '0%, 100%': { 'box-shadow': '0 12px 48px rgba(0, 0, 0, 0.25), inset 0 4px 20px var(--glass-inset-shadow-light)' },
            '50%': { 'box-shadow': '0 18px 60px rgba(0, 0, 0, 0.35), inset 0 4px 20px rgba(255, 255, 255, 0.6)' } // Assuming a slightly stronger inset shadow for the pulse peak
        },
        featureTitleScaleUp: { // For FeaturePageLayout h1
          '0%': { opacity: '0', transform: 'scale(0.9)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        featureContentFadeIn: { // For FeaturePageLayout article slot content
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      },
      animation: {
        'fade-in': 'fadeIn 1s ease-out forwards',
        'fade-in-down': 'fadeInDown 0.8s ease-out forwards',
        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
        'blob': 'blob 7s infinite cubic-bezier(0.68, -0.55, 0.27, 1.55)', // Match your desired blob animation
        'pulseShadow': 'pulseShadow 1.5s infinite alternate',
        'featureTitleScaleUp': 'featureTitleScaleUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards',
        'featureContentFadeIn': 'featureContentFadeIn 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
};