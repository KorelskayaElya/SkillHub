import '@testing-library/jest-dom'
import { render, fireEvent, screen } from '@testing-library/vue'
import RegisterForm from '@/pages/register.vue'
import { describe, it, expect } from 'vitest'

describe('RegisterForm', () => {
  it('показывает ошибку, если поля пустые', async () => {
    render(RegisterForm)
    const submitButton = screen.getByRole('button', { name: /register/i })

    await fireEvent.click(submitButton)

    expect(screen.getByText(/Пожалуйста, заполните все поля/)).toBeInTheDocument()
  })

  it('показывает ошибку при невалидном email', async () => {
    render(RegisterForm)

    await fireEvent.update(screen.getByPlaceholderText(/Name/), 'Тест')
    await fireEvent.update(screen.getByPlaceholderText(/Email/), 'test@')
    await fireEvent.update(screen.getByPlaceholderText(/Create Password/), '123456')
    await fireEvent.update(screen.getByPlaceholderText(/Repeat Password/), '123456')

    await fireEvent.click(screen.getByRole('button', { name: /register/i }))

    expect(screen.getByText(/Невалидный email/)).toBeInTheDocument()
  })

  it('показывает ошибку при несовпадении паролей', async () => {
    render(RegisterForm)

    await fireEvent.update(screen.getByPlaceholderText(/Name/), 'Имя')
    await fireEvent.update(screen.getByPlaceholderText(/Email/), 'test@mail.com')
    await fireEvent.update(screen.getByPlaceholderText(/Create Password/), '123456')
    await fireEvent.update(screen.getByPlaceholderText(/Repeat Password/), '654321')

    await fireEvent.click(screen.getByRole('button', { name: /register/i }))

    expect(screen.getByText(/Пароли не совпадают/)).toBeInTheDocument()
  })

  it('поля ограничены 40 символами', async () => {
    render(RegisterForm)

    const longText = 'a'.repeat(50)

    const nameInput = screen.getByPlaceholderText('Name')
    const emailInput = screen.getByPlaceholderText('Email')
    const passwordInput = screen.getByPlaceholderText('Create Password')
    const repeatPasswordInput = screen.getByPlaceholderText('Repeat Password')

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
