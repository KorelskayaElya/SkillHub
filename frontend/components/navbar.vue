<template>
  <nav class="navbar">
    <div class="navbar-left">
      <span class="logo">skillHub</span>
      <button class="theme-toggle-btn" @click="toggleDarkMode">
        {{ colorMode.value === 'dark' ? '🌙' : '☀️' }}
      </button>
    </div>
    <div class="navbar-right">
      <template v-if="user">
        <router-link v-if="user.is_admin" to="/admin/courses" class="nav-link">
          ➕ Добавить курс
        </router-link>
        <span class="username">👋 {{ user.name }}</span>
        <button class="logout-btn" @click="logout">Выйти</button>
      </template>
      <template v-else>
        <router-link to="/login" class="nav-link">Войти</router-link>
        <router-link to="/register" class="nav-link">Регистрация</router-link>
      </template>
    </div>
  </nav>
</template>

<script setup>
import { useAuth } from '@/stores/useAuth'
import { onMounted, computed } from 'vue'
import { useColorMode } from '#imports'

const auth = useAuth()
const user = computed(() => auth.user)
const logout = auth.logout

const colorMode = useColorMode()

const toggleDarkMode = () => {
  colorMode.preference = colorMode.preference === 'dark' ? 'light' : 'dark'
}

onMounted(() => {
  if (!auth.user) {
    auth.loadUser()
  }
})
</script>

<style>
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  z-index: 1000;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
  transition: background-color 0.3s ease, color 0.3s ease;
  background-color: #ebebeb;
  color: #2a2f36;
}

html.dark .navbar {
  background-color: #2a2f36;
  color: #efefef;
}

.logo {
  font-weight: bold;
  font-size: 1.5em;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.nav-link {
  color: #e6b333;
  text-decoration: none;
  font-weight: 500;
}

.nav-link:hover {
  text-decoration: underline;
}

.logout-btn {
  background: transparent;
  border: 1px solid #e6b333;
  color: #e6b333;
  padding: 0.4rem 0.8rem;
  cursor: pointer;
  border-radius: 5px;
  transition: background 0.3s;
}

.logout-btn:hover {
  background: #e6b333;
}

.username {
  color: #e6b333;
  font-weight: 500;
}

.theme-toggle-btn {
  margin-left: 1rem;
  background: transparent;
  border: 1px solid #e6b333;
  color: #e6b333;
  padding: 0.3rem 0.6rem;
  cursor: pointer;
  border-radius: 5px;
  font-size: 1.2rem;
  transition: background 0.3s, color 0.3s;
}

.theme-toggle-btn:hover {
  background: #e6b333;
}
</style>
