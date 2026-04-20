import type { Config } from 'tailwindcss'
import forms from '@tailwindcss/forms'

export default <Config>{
  content: [],
  theme: {
    extend: {
      colors: {
        vue: {
          DEFAULT: '#42b883',
          dark: '#35495e',
          light: '#42b88333',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [forms],
}
