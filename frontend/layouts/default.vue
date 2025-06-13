<template>
  <div class="min-h-screen flex flex-col">
    <Navbar v-if="showNavbar" />
    <main class="flex-1">
      <slot />
    </main>
  </div>
</template>

<script setup>
import Navbar from '@/components/navbar.vue'
import { useHead } from '#imports'
import { useRoute } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()
const hideNavbarRoutes = ['/courses', '/mycourses', '/admin/courses', "/login", "/register"]
const showNavbar = computed(() => {
  return !hideNavbarRoutes.includes(route.path)
})

useHead({
  script: [
    {
      children: `
        (function() {
          try {
            var mode = localStorage.getItem('color-mode');
            if (mode === 'dark' || (!mode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
          } catch (e) {}
        })();
      `
    }
  ]
})
</script>

