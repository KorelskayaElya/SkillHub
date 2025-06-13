import { render, fireEvent, screen } from '@testing-library/vue'
import LoginForm from '@/pages/login.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { describe, it } from 'vitest'

const router = createRouter({
  history: createWebHistory(),
  routes: [],
})

describe('LoginForm', () => {
  it('показывает ошибку при неправильном логине/пароле', async () => {
    render(LoginForm, {
      global: {
        plugins: [router],
      },
    })

    await fireEvent.update(screen.getByPlaceholderText(/Email/), 'wrong@mail.com')
    await fireEvent.update(screen.getByPlaceholderText(/Пароль/), 'wrongpass')

    await fireEvent.click(screen.getByRole('button', { name: /Войти/i }))

    await screen.findByText(/Ошибка при входе|Неверный логин/)
  })
})
