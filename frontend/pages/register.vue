<template>
  <div class="wrapper">
    <form @submit.prevent="register">
      <h2 class="sign-up">Sign Up</h2>
      <div class="clear"></div>

      <!-- Уведомление -->
      <div
        v-if="successMessage || errorMessage"
        :class="['notification', errorMessage ? 'error' : 'success']"
      >
        {{ successMessage || errorMessage }}
      </div>

      <div class="user">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
      </div>
      <input type="text" :value="name" @input="e => name = e.target.value.slice(0, 40)" placeholder="Name" :class="{ invalid: fieldErrors.name }"/>
      <div class="user">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
      </div>
      <input type="text" :value="email" @input="e => email = e.target.value.slice(0, 40)" placeholder="Email" :class="{ invalid: fieldErrors.email }" maxlength="40"/>

      <div class="lock">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
      </div>
      <input type="password" :value="password" @input="e => password = e.target.value.slice(0, 40)" placeholder="Create Password" :class="{ invalid: fieldErrors.password }" maxlength="40"/>

      <div class="lock">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
      </div>
      <input type="password" :value="password_confirmation" @input="e => password_confirmation = e.target.value.slice(0, 40)" placeholder="Repeat Password" :class="{ invalid: fieldErrors.password_confirmation }" maxlength="40"/>

      <input type="submit" value="Register" class="mt-4" />
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const successMessage = ref('')
const errorMessage = ref('')
const fieldErrors = ref({})

// Валидация email
function isEmailValid(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

// Валидация пароля
function isPasswordStrong(password) {
  return password.length >= 6
}

async function register() {
  // Очистка
  errorMessage.value = ''
  successMessage.value = ''
  fieldErrors.value = {}

  // Простая проверка
  if (!name.value || !email.value || !password.value || !password_confirmation.value) {
    errorMessage.value = 'Пожалуйста, заполните все поля.'
    return
  }

  if (!isEmailValid(email.value)) {
    fieldErrors.value.email = ['Невалидный email.']
    errorMessage.value = 'Невалидный email.'
    return
  }

  if (!isPasswordStrong(password.value)) {
    fieldErrors.value.password = ['Пароль слишком простой (мин. 6 символов).']
    errorMessage.value = 'Пароль слишком простой.'
    return
  }

  if (password.value !== password_confirmation.value) {
    fieldErrors.value.password_confirmation = ['Пароли не совпадают.']
    errorMessage.value = 'Пароли не совпадают.'
    return
  }

  // Отправка
  try {
    await $fetch('http://localhost:8000/api/register', {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value
      },
      credentials: 'include',
    })

    successMessage.value = 'Регистрация прошла успешно!'
    name.value = ''
    email.value = ''
    password.value = ''
    password_confirmation.value = ''
  } catch (e) {
    if (e.data?.errors) {
      fieldErrors.value = e.data.errors
      const firstError = Object.values(e.data.errors)[0][0]
      errorMessage.value = firstError
    } else {
      errorMessage.value = 'Ошибка при регистрации.'
    }
  }

  // Очистка сообщений
  setTimeout(() => {
    successMessage.value = ''
    errorMessage.value = ''
    fieldErrors.value = {}
  }, 4000)
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

input.invalid {
  border-color: #f44336 !important;
  background-color: rgba(244, 67, 54, 0.1);
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
.notification.success {
  background-color: #4caf50; /* Зелёная */
}

</style>
