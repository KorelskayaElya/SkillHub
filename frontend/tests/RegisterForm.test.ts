import '@testing-library/jest-dom'
import { render, fireEvent, screen } from '@testing-library/vue'
import RegisterForm from '@/pages/register.vue'
import { describe, it, expect } from 'vitest'

describe('RegisterForm', () => {
  it('показывает ошибку, если поля пустые', async () => {
    render(RegisterForm)
    const submitButton = screen.getByRole('button', { name: /Зарегистрироваться/i })

    await fireEvent.click(submitButton)

    expect(screen.getByText(/Пожалуйста, заполните все поля/)).toBeInTheDocument()
  })

  it('показывает ошибку при невалидном email', async () => {
    render(RegisterForm)

    await fireEvent.update(screen.getByPlaceholderText(/Имя/), 'Тест')
    await fireEvent.update(screen.getByPlaceholderText(/Email/), 'test@')
    await fireEvent.update(screen.getByPlaceholderText(/Введите пароль/), '123456')
    await fireEvent.update(screen.getByPlaceholderText(/Повторите пароль/), '123456')

    await fireEvent.click(screen.getByRole('button', { name: /Зарегистрироваться/i }))

    expect(screen.getByText(/Невалидный email/)).toBeInTheDocument()
  })

  it('показывает ошибку при несовпадении паролей', async () => {
    render(RegisterForm)

    await fireEvent.update(screen.getByPlaceholderText(/Имя/), 'Имя')
    await fireEvent.update(screen.getByPlaceholderText(/Email/), 'test@mail.com')
    await fireEvent.update(screen.getByPlaceholderText(/Введите пароль/), '123456')
    await fireEvent.update(screen.getByPlaceholderText(/Повторите пароль/), '654321')

    await fireEvent.click(screen.getByRole('button', { name: /Зарегистрироваться/i }))

    expect(screen.getByText(/Пароли не совпадают/)).toBeInTheDocument()
  })

  it('поля ограничены 40 символами', async () => {
    render(RegisterForm)

    const longText = 'a'.repeat(50)

    const nameInput = screen.getByPlaceholderText('Имя')
    const emailInput = screen.getByPlaceholderText('Email')
    const passwordInput = screen.getByPlaceholderText('Введите пароль')
    const repeatPasswordInput = screen.getByPlaceholderText('Повторите пароль')

    await fireEvent.update(nameInput, longText)
    await fireEvent.update(emailInput, longText)
    await fireEvent.update(passwordInput, longText)
    await fireEvent.update(repeatPasswordInput, longText)

    expect((nameInput as HTMLInputElement).value).toHaveLength(40)
    expect((emailInput as HTMLInputElement).value).toHaveLength(40)
    expect((passwordInput as HTMLInputElement).value).toHaveLength(40)
    expect((repeatPasswordInput as HTMLInputElement).value).toHaveLength(40)
  })
})
