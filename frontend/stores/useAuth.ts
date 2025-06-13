import { defineStore } from 'pinia'

type User = {
  name: string
  email: string
}

const API = 'http://localhost:8000'

export const useAuth = defineStore('auth', {
  state: () => ({
    user: null as User | null,
  }),

  actions: {
    async loadUser() {
      try {
        const user = await $fetch<User>(`${API}/api/user`, {
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
        })
        this.user = user
      } catch (error) {
        this.user = null
      }
    },

    async login(email: string, password: string) {
      try {
        await $fetch(`${API}/sanctum/csrf-cookie`, {
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
        })

        await $fetch(`${API}/api/login`, {
          method: 'POST',
          body: { email, password },
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
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
        await $fetch(`${API}/sanctum/csrf-cookie`, {
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
        })

        await $fetch(`${API}/api/register`, {
          method: 'POST',
          body: data,
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
        })

        await this.loadUser()
      } catch (error) {
        throw error
      }
    },

    async logout() {
      try {
        await $fetch(`${API}/sanctum/csrf-cookie`, {
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
        })

        await $fetch(`${API}/api/logout`, {
          method: 'POST',
          credentials: 'include',
          headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '')
          },
        })
      } finally {
        this.user = null
      }
    }
  }
})
