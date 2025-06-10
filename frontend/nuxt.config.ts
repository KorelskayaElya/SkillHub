import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  compatibilityDate: "2025-05-31",
  css: ['~/assets/css/tailwind.css'],
  modules: ['@pinia/nuxt'],
  postcss: {
    plugins: {
      "postcss-simple-vars": {},
      "@tailwindcss/postcss": {},
      autoprefixer: {},
    },
  },
  debug: true
})