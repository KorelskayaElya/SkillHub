<template>
  <div class="dashboard">
    <nav class="navbar">
      <div class="navbar-left">
        <span class="logo">skillHub</span>
      </div>
      <div class="navbar-right">
        <template v-if="user">
          <span class="username">👋 {{ user.name }}</span>
          <button class="logout-btn" @click="logout">Выйти</button>
          <router-link v-if="user.is_admin" to="/admin/courses" class="nav-link">
            ➕ Добавить курс
          </router-link>
        </template>
        <template v-else>
          <router-link to="/login" class="nav-link">Войти</router-link>
          <router-link to="/register" class="nav-link">Регистрация</router-link>
        </template>
      </div>
    </nav>

    <div class="wrapper">
      <div class="collections">
        <router-link to="/courses" class="collection-card">
          Курсы
        </router-link>
        <NuxtLink to="/mycourses" class="collection-card">Мои курсы</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuth } from '@/stores/useAuth'
import { onMounted, computed } from 'vue'

const auth = useAuth()

const user = computed(() => auth.user)
const logout = auth.logout

onMounted(() => {
  if (!auth.user) {
    auth.loadUser()
  }
})
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Roboto:400,300,500");

*,
*:after,
*:before {
  box-sizing: border-box;
}

html,
body {
  background: #1a1f25;
  font-family: "Roboto", sans-serif;
  margin: 0;
  padding: 0;
}

.dashboard {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #2a2f36;
  padding: 1rem 2rem;
  z-index: 1000;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.logo {
  color: #fff;
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
  color: #1a1f25;
}

.wrapper {
  flex: 1;
  padding-top: 80px; /* отступ под navbar */
  display: flex;
  justify-content: center;
  align-items: flex-start;
}

.collections {
  display: flex;
  gap: 2rem;
}

.collection-card {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 240px;
  height: 160px;
  border: 1px solid #e6b333;
  border-radius: 12px;
  color: #fff;
  font-size: 1.6rem;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  background-color: #2a2f36;
  transition: background-color 0.3s, color 0.3s;
  user-select: none;
}

.collection-card:hover {
  background-color: #e6b333;
  color: #1a1f25;
}
</style>
