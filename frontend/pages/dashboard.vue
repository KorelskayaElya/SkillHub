<template>
  <div class="wrapper">
    <div class="collections">
      <NuxtLink to="/courses" class="collection-card">
        Курсы
      </NuxtLink>
      <NuxtLink to="/mycourses" class="collection-card">
        Мои курсы
      </NuxtLink>
    </div>
    <div class="news-panel">
      <h2 class="news-title">Новости</h2>
      <ul class="news-list">
        <li v-for="course in latestCourses" :key="course.id">
          Добавлен курс: {{ course.title }}
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'

const latestCourses = ref([])

onMounted(async () => {
  try {
    latestCourses.value = await $fetch('http://localhost:8000/api/courses', {
      credentials: 'include',
    })
  } catch (error) {
    console.error(error)
  }
})
</script>


<style>
@import url("https://fonts.googleapis.com/css?family=Roboto:400,300,500");

*,
*:after,
*:before {
  box-sizing: border-box;
}

html,
body {
  font-family: "Roboto", sans-serif;
  margin: 0;
  padding: 0;
}

.wrapper {
  flex: 1;
  padding-top: 80px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  gap: 2rem;
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
  text-decoration: none;
  cursor: pointer;
  user-select: none;
  font-size: 1.6rem;
  font-weight: 700;
  transition: background-color 0.3s, color 0.3s;
  color: #2a2f36;
  background-color: #efefef;
}

.collection-card:hover {
  background-color: #e6b333;
}

html.dark .collection-card {
  background-color: #2a2f36;
  color: #efefef;
}

html.dark .collection-card:hover {
  background-color: #e6b333;
}

.main-grid {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: flex-start;
}

.news-panel {
  flex: 1;
  margin-left: 2rem;
  border: 1px solid #e6b333;
  border-radius: 12px;
  padding: 1rem;
  max-width: 400px;
  background-color: #efefef;
  color: #1a1f25;
}

html.dark .news-panel {
  background-color: #1a1f25;
  color: #efefef;
}

.news-title {
  font-size: 1.4rem;
  margin-bottom: 1rem;
  color: #e6b333;
}

.news-list {
  list-style: none;
  padding: 0;
}

.news-list li {
  margin-bottom: 0.5rem;
  font-size: 1rem;
}

</style>
