import { defineStore } from 'pinia'

type User = {
  name: string
  email: string
}

export const useAuth = defineStore('auth', {
  state: () => ({
    user: null as User | null,
  }),

  actions: {
    async loadUser() {
      try {
        const user = await $fetch<User>('http://localhost:8000/api/user', {
          credentials: 'include',
        })
        this.user = user
      } catch (error) {
        this.user = null
      }
    },

    async login(email: string, password: string) {
      try {
        await $fetch('http://localhost:8000/api/login', {
          method: 'POST',
          body: { email, password },
          credentials: 'include',
        })

        await this.loadUser()
      } catch (error) {
        this.user = null
        throw error
      }
    },

    async register(data: {
      name: string
      email: string
      password: string
      password_confirmation: string
    }) {
      try {
        await $fetch('http://localhost:8000/api/register', {
          method: 'POST',
          body: data,
          credentials: 'include',
        })

        await this.loadUser()
      } catch (error) {
        throw error
      }
    },

    async logout() {
      try {
        await $fetch('http://localhost:8000/api/logout', {
          method: 'POST',
          credentials: 'include',
        })
      } finally {
        this.user = null
      }
    }
  }
})