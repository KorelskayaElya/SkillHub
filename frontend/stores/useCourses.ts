import { defineStore } from 'pinia'
import api from '@/utils/axios'

export const useCourses = defineStore('courses', {
  state: () => ({
    courses: [] as any[],
    selectedCourse: null as any | null,
  }),

  actions: {
    async fetchCourses() {
      try {
        const response = await api.get('/api/courses')
        this.courses = response.data
      } catch (error) {
        console.error('Ошибка при загрузке курсов:', error)
      }
    },

    setSelectedCourse(course: any) {
      this.selectedCourse = course
    },

    clearSelectedCourse() {
      this.selectedCourse = null
    },

    updateSelectedCourseFromStore(courseId: number) {
      this.selectedCourse = this.courses.find(c => c.id === courseId) || null
    }
  }
})
