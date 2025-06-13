<template>
  <div class="wrapper">
    

    <form @submit.prevent="addCourse">
      <div class="back-btn-container">
      <button class="back-btn" @click="goBack">← Назад</button>
      </div>
      <h2 class="sign-up">Добавить курс</h2>
      <input
        type="text"
        v-model="title"
        placeholder="Название курса"
        required
      />

      <div
        class="drop-zone"
        @dragover.prevent
        @drop.prevent="handleDrop"
        @click="triggerFileInput"
      >
        <p v-if="!preview">Перетащите изображение сюда или нажмите</p>
        <img v-if="preview" :src="preview" class="preview-image" />
        <input
          ref="fileInput"
          type="file"
          accept="image/png, image/jpeg, image/webp, image/gif"
          @change="handleFile"
          hidden
        />
      </div>

      <input
        type="submit"
        value="Добавить курс"
        class="submit-btn"
        :disabled="isLoading"
      />
    </form>

    <div v-if="successMessage" class="notification success">
      {{ successMessage }}
    </div>

    <div v-if="errorMessage" class="notification error">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()  
const title = ref('')
const image = ref(null)
const preview = ref('')
const successMessage = ref('')
const errorMessage = ref('')
const isLoading = ref(false)
const fileInput = ref(null)

function goBack() {
  router.push('/dashboard')
}

function triggerFileInput() {
  fileInput.value?.click()
}

function handleFile(e) {
  const file = e.target.files[0]
  processFile(file)
}

function handleDrop(e) {
  const file = e.dataTransfer.files[0]
  processFile(file)
}

function processFile(file) {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif']
  if (!allowedTypes.includes(file.type)) {
    alert('Допустимые форматы: JPG, PNG, WEBP, GIF')
    return
  }

  image.value = file
  const reader = new FileReader()
  reader.onload = () => {
    preview.value = reader.result
  }
  reader.readAsDataURL(file)
}

async function addCourse() {
  if (!title.value.trim()) {
    alert('Пожалуйста, введите название курса')
    return
  }

  if (!image.value) {
    alert('Пожалуйста, загрузите изображение')
    return
  }

  isLoading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  const formData = new FormData()
  formData.append('title', title.value)
  formData.append('image', image.value)

  try {
    await $fetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include',
    })

    const csrfToken = decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')

    await $fetch('http://localhost:8000/api/courses', {
      method: 'POST',
      body: formData,
      credentials: 'include',
      headers: {
        'X-XSRF-TOKEN': csrfToken,
        Accept: 'application/json',
      },
    })

    successMessage.value = 'Курс успешно добавлен!'
    title.value = ''
    image.value = null
    preview.value = ''
  } catch (e) {
    errorMessage.value = e?.data?.message || 'Ошибка при добавлении курса.'
  } finally {
    isLoading.value = false
  }
}

</script>

<style scoped>
.wrapper {
  max-width: 400px;
  margin: 100px auto;
  padding: 2rem;
  background: #2a2f36;
  border-radius: 12px;
  text-align: center;
}

.sign-up {
  color: white;
  margin-bottom: 1.5rem;
}

input[type='text'] {
  width: 100%;
  box-sizing: border-box;
  padding: 0.6rem;
  margin-bottom: 1rem;
  border-radius: 8px;
  border: none;
  font-size: 1rem;
}

.drop-zone {
  width: 100%;
  box-sizing: border-box;
  border: 2px dashed #e6b333;
  border-radius: 8px;
  padding: 1rem;
  color: #ccc;
  cursor: pointer;
  margin-bottom: 1.5rem;
  background: #1a1f25;
  transition: background 0.3s;
}

.drop-zone:hover {
  background: #22272d;
}

.preview-image {
  max-width: 100%;
  max-height: 180px;
  border-radius: 8px;
  margin-top: 0.5rem;
}

.submit-btn {
  width: 100%;
  box-sizing: border-box;
  padding: 0.7rem;
  background: #e6b333;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s;
}

.submit-btn:hover:not(:disabled) {
  background: #f2c94c;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.notification.success {
  margin-top: 1rem;
  color: white;
  background-color: #4caf50;
  padding: 10px;
  border-radius: 5px;
}

.notification.error {
  margin-top: 1rem;
  color: white;
  background-color: #f44336;
  padding: 10px;
  border-radius: 5px;
}
.back-btn-container {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 1rem;
}

.back-btn {
  background: transparent;
  border: 1px solid #e6b333;
  color: #e6b333;
  padding: 0.4rem 1rem;
  cursor: pointer;
  border-radius: 5px;
  font-weight: 600;
  transition: background 0.3s;
}

.back-btn:hover {
  background: #e6b333;
  color: #1a1f25;
}

</style>
