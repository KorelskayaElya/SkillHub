<template>
  <div class="container">
    <header class="header">
      <h1>Курсы</h1>
      <button class="dashboard-btn" @click="goDashboard">← На главную</button>
    </header>

    <div class="course-grid">
      <div
        v-for="course in courses"
        :key="course.id"
        class="course-card"
        @click="openModal(course)"
      >
        <img :src="course.image_url" :alt="course.title" class="course-image" />
        <div class="course-title">{{ course.title }}</div>
      </div>
    </div>

   <div v-if="selectedCourse" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <button class="close-icon" @click="closeModal">✖</button>

        <img :src="selectedCourse.image_url" class="modal-image" />
        <h2 class="modal-title">{{ selectedCourse.title }}</h2>
        <p class="modal-description">{{ selectedCourse.description }}</p>
        <p class="modal-price">Стоимость: {{ selectedCourse.price }} ₽</p>
        <button class="buy-btn" @click="buyCourse(selectedCourse)">Купить</button>
        <div v-if="purchaseSuccess" class="purchase-banner">
          🎉 Курс успешно приобретён!
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCourses } from '@/stores/useCourses'

const router = useRouter()
const coursesStore = useCourses()
const selectedCourse = ref(null)
const purchaseSuccess = ref(false) 
const courses = computed(() => coursesStore.courses)

function goDashboard() {
  router.push('/dashboard')
}

onMounted(() => {
  if (coursesStore.courses.length === 0) {
    coursesStore.fetchCourses()
  }
})

function openModal(course) {
  selectedCourse.value = course
}

function closeModal() {
  selectedCourse.value = null
  purchaseSuccess.value = false
}
function getXsrfToken() {
  const match = document.cookie.match(new RegExp('(^| )XSRF-TOKEN=([^;]+)'))
  if (match) {
    return decodeURIComponent(match[2])
  }
  return ''
}

async function buyCourse(course) {
  try {
    const response = await $fetch(`http://localhost:8000/api/courses/${course.id}/buy`, {
      method: 'POST',
      credentials: 'include',
      headers: {
        'X-XSRF-TOKEN': getXsrfToken(),
      },
    })
    purchaseSuccess.value = true
    setTimeout(() => {
      closeModal()
      router.push('/mycourses')
    }, 2000)
  } catch (error) {
    if (error?.data?.message) {
      alert(`⚠️ ${error.data.message}`)
    } else {
      console.error(error)
      alert('Произошла ошибка при покупке курса.')
    }
  }
}
</script>

<style scoped>

.container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 0 20px;
}
.purchase-banner {
  margin-top: 1rem;
  background-color: #4caf50;
  color: white;
  padding: 1rem;
  border-radius: 8px;
  font-weight: bold;
  animation: fade-in 0.5s ease;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.header h1 {
  color: #e6b333;
  font-size: 2rem;
  margin: 0;
}

.dashboard-btn {
  background: transparent;
  border: 1.5px solid #e6b333;
  color: #e6b333;
  padding: 0.5rem 1.2rem;
  cursor: pointer;
  border-radius: 6px;
  font-weight: 600;
  font-size: 1rem;
  transition: background 0.3s, color 0.3s;
}

.dashboard-btn:hover {
  background: #e6b333;
  color: #1a1f25;
}

.course-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  justify-content: flex-start;
}

.course-card {
  background-color: #2a2f36;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  width: 260px;
  text-align: center;
  transition: transform 0.2s ease;
  cursor: pointer;
}

.course-card:hover {
  transform: scale(1.03);
}

.course-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 16px 16px 0 0;
}

.course-title {
  color: #e6b333;
  font-size: 1.1em;
  font-weight: 500;
  padding: 1rem;
}


.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal {
  position: relative;
  background-color: #2a2f36;
  padding: 1.5rem;
  border-radius: 12px;
  text-align: center;
  max-width: 500px;
  width: 90%;
  color: #efefef;
}

.modal-image {
  width: 100%;
  max-height: 300px;
  object-fit: contain;
  margin-bottom: 1rem;
}

.modal-title {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
  color: #e6b333;
}

.modal-description {
  font-size: 1rem;
  margin-bottom: 1rem;
}

.modal-price {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
}

.buy-btn {
  padding: 0.6rem 1.2rem;
  background: #e6b333;
  color: #1a1f25;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  font-size: 1rem;
  transition: background 0.3s;
}

.buy-btn:hover {
  background: #f2c94c;
}

.close-icon {
  position: absolute;
  top: 12px;
  right: 12px;
  background: transparent;
  border: none;
  color: #e6b333;
  font-size: 1.5rem;
  cursor: pointer;
  transition: color 0.3s;
}

.close-icon:hover {
  color: #f2c94c;
}

</style>
