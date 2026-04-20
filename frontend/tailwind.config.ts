import type { Config } from 'tailwindcss'
import forms from '@tailwindcss/forms'

export default <Config>{
  content: [],
  theme: {
    extend: {
      colors: {
        primary: '#4FC08D',
        secondary: '#35495E',
        vue: {
          DEFAULT: '#42b883',
          dark: '#35495e',
          light: '#42b88333',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        display: ['Inter', 'system-ui', 'sans-serif'],
      },
      borderRadius: {
        DEFAULT: '0.5rem',
        lg: '0.75rem',
        xl: '1rem',
      },
    },
  },
  plugins: [forms],
}
