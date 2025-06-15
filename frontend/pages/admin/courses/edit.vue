<template>
  <div class="wrapper">
    <div class="back-btn-container">
      <button class="back-btn" @click="goBack">← Назад</button>
    </div>

    <h2 class="sign-up">Редактировать курс</h2>

    <select v-model="selectedCourseId" @change="loadCourse" class="course-select">
      <option disabled value="">Выберите курс для редактирования</option>
      <option v-for="course in coursesStore.courses" :key="course.id" :value="course.id">
        {{ course.title }}
      </option>
    </select>

    <form v-if="selectedCourseId" @submit.prevent="updateCourse">
      <input type="text" v-model="title" placeholder="Название курса" required />
      <textarea v-model="description" placeholder="Описание курса" required></textarea>
      <input type="number" v-model="price" placeholder="Стоимость курса (₽)" min="0" required />

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

      <input type="submit" value="Сохранить изменения" class="submit-btn" :disabled="isLoading" />
    </form>

    <div v-if="successMessage" class="notification success">
      {{ successMessage }}
    </div>

    <div v-if="errorMessage" class="notification error">
      {{ errorMessage }}
    </div>

    <div v-if="selectedCourse">
      <h3>Выбранный курс:</h3>
      <p>Название: {{ selectedCourse.title }}</p>
      <p>Цена: {{ selectedCourse.price }}₽</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCourses } from '@/stores/useCourses'
import { storeToRefs } from 'pinia'
import api from '@/utils/axios'

const router = useRouter()
const coursesStore = useCourses()
const { selectedCourse } = storeToRefs(coursesStore)

const selectedCourseId = ref('')
const title = ref('')
const description = ref('')
const price = ref('')
const image = ref(null)
const preview = ref('')
const successMessage = ref('')
const errorMessage = ref('')
const isLoading = ref(false)
const fileInput = ref(null)

onMounted(() => {
  coursesStore.fetchCourses()
})

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

async function loadCourse() {
  if (!selectedCourseId.value) return

  try {
    const response = await api.get(`/api/courses/${selectedCourseId.value}`)
    const course = response.data

    title.value = course.title || ''
    description.value = course.description || ''
    price.value = course.price || 0
    preview.value = course.image_url || ''
    image.value = null
  } catch (error) {
    console.error('ERROR LOADING COURSE:', error)
    errorMessage.value = 'Ошибка при загрузке курса.'
  }
}

async function updateCourse() {
  if (!title.value.trim()) {
    alert('Пожалуйста, введите название курса')
    return
  }

  isLoading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  const formData = new FormData()
  formData.append('title', title.value)
  formData.append('description', description.value || '')
  formData.append('price', Number(price.value).toString())
  if (image.value) {
    formData.append('image', image.value)
  }

  for (const pair of formData.entries()) {
    console.log(`${pair[0]}:`, pair[1])
  }

  try {
    await api.get('/sanctum/csrf-cookie')
    formData.append('_method', 'PATCH')

    await api.post(`/api/courses/${selectedCourseId.value}`, formData)

    successMessage.value = 'Курс успешно обновлен!'
    await coursesStore.fetchCourses()

  } catch (e) {
    console.error('UPDATE ERROR:', e)
    errorMessage.value = e?.response?.data?.message || 'Ошибка при обновлении курса.'
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

.course-select {
  width: 100%;
  box-sizing: border-box;
  padding: 0.6rem;
  margin-bottom: 1rem;
  border-radius: 8px;
  border: none;
  font-size: 1rem;
}

input[type='text'],
textarea,
input[type='number'] {
  width: 100%;
  box-sizing: border-box;
  padding: 0.6rem;
  margin-bottom: 1rem;
  border-radius: 8px;
  border: none;
  font-size: 1rem;
  resize: vertical;
}

textarea {
  min-height: 100px;
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
