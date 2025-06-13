import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  compatibilityDate: "2025-05-31",
  css: ['~/assets/css/tailwind.css'],
  modules: [
    '@pinia/nuxt',
    ['@nuxtjs/color-mode'],
  ],
  colorMode: {
    classSuffix: '',
  },
  postcss: {
    plugins: {
      '@tailwindcss/postcss': {},
      autoprefixer: {}
    }
  }

})
