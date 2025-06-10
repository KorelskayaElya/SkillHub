<template>
  <div class="container">
    <header class="header">
      <h1>Мои курсы</h1>
      <button class="dashboard-btn" @click="goDashboard">← На главную</button>
    </header>

    <div v-if="courses.length > 0" class="course-grid">
      <div v-for="course in courses" :key="course.id" class="course-card">
        <img :src="course.image_url" :alt="course.title" class="course-image" />
        <div class="course-title">{{ course.title }}</div>
      </div>
    </div>

    <p v-else class="no-courses-message">У вас еще нет курсов</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const courses = ref([])

function goDashboard() {
  router.push('/dashboard')
}


onMounted(async () => {
  try {
    courses.value = await $fetch('http://localhost:8000/api/my-courses', {
      credentials: 'include',
    })
  } catch (error) {
    console.error(error)
  }
})
</script>

<style scoped>
.container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 0 20px;
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

.no-courses-message {
  color: #e6b333;
  font-size: 1.3em;
  text-align: center;
  margin-top: 2rem;
}
</style>
