<template>
  <div class="wrapper">
    <form @submit.prevent="login">
      <h2 class="sign-up">Вход</h2>
      <div class="clear"></div>
    
      <div v-if="errorMessage" class="notification error">
        {{ errorMessage }}
      </div>

      <div class="user">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
      </div>
      <input type="text" v-model="email" placeholder="Email" maxlength="40" />

      <div class="lock">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <input type="password" v-model="password" placeholder="Пароль" maxlength="40" />

      <input type="submit" value="Войти" class="mt-4" />
    </form>
    <div class="register-link">
      <p>Нет аккаунта? 
        <router-link to="/register">Регистрация</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/stores/useAuth'

const email = ref('')
const password = ref('')
const router = useRouter()
const errorMessage = ref('')

async function login() {
  errorMessage.value = ''

  try {
    await $fetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include'
    })

    await $fetch('http://localhost:8000/api/login', {
      method: 'POST',
      body: {
        email: email.value.slice(0, 40),
        password: password.value.slice(0, 40)
      },
      credentials: 'include',
      headers: {
        Accept: 'application/json',
        'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
      }
    })

    await useAuth().loadUser()
    router.push('/dashboard')
  } catch (e) {
    if (e?.data?.message === 'Unauthenticated.') {
      errorMessage.value = 'Неверный логин или пароль.'
    } else {
      errorMessage.value = 'Ошибка при входе. Попробуйте позже.'
    }
  }
}
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
}

.wrapper {
  width: 100%;
  max-width: 360px;
  margin: 12% auto;
  padding: 0 1rem;
}

form {
  position: relative;
}

.sign-up {
  color: white;
  font-size: 1.3em;
  margin-bottom: 10px;
  text-align: center;
}

.clear {
  clear: both;
}

.user,
.lock {
  position: absolute;
  width: 40px;
  height: 40px;
  margin-top: 10px;
  padding: 7px;
  left: 5px;
}
.user::after,
.lock::after {
  content: "";
  width: 1px;
  height: 30px;
  position: absolute;
  background: #22272d;
  top: 0px;
  left: 35px;
}

.notification {
  color: white;
  padding: 10px;
  border-radius: 3px;
  margin-bottom: 10px;
  text-align: center;
  animation: fadeIn 0.3s ease-in-out;
}
.notification.error {
  background-color: #f44336;
}

input {
  width: 100%;
  padding: 5px;
  height: 40px;
  border-radius: 3px;
  margin: 5px 0;
  outline: none;
}

input[type="text"],
input[type="password"] {
  background: transparent;
  border: 2px solid #22272d;
  padding-left: 45px;
  color: #e6b333;
}

input[type="submit"] {
  background: #e6b333;
  border: none;
  color: white;
  text-align: center;
  font-size: 0.8em;
  cursor: pointer;
  padding: 10px 0;
  width: 100%;
  border-radius: 3px;
}
.register-link {
  margin-top: 15px;
  text-align: center;
  color: #ccc;
  font-size: 0.9em;
}

.register-link a {
  color: #e6b333;
  text-decoration: none;
  font-weight: 500;
}

.register-link a:hover {
  text-decoration: underline;
}

</style>
