<template>
    <div class="wrapper">
      <h2 class="sign-up">Добро пожаловать на сайт</h2>
      <p class="username">👋 Привет, {{ user?.name || 'Гость' }}</p>
    </div>
  </template>
  
  <script setup>
  import { useAuth } from '@/stores/useAuth'
  
  const { user } = useAuth()
  </script>
  
  <style scoped>
  @import url("https://fonts.googleapis.com/css?family=Roboto:400,300,500");
  
  *,
  *:after,
  *:before {
    box-sizing: border-box;
  }
  
  html,
  body {
    background: #1a1f25;
    font-family: "Roboto", sans-serif;
    margin: 0;
    padding: 0;
  }
  
  .wrapper {
    width: 100%;
    max-width: 360px;
    margin: 12% auto;
    padding: 1rem;
    text-align: center;
  }
  
  .sign-up {
    color: white;
    font-size: 1.8em;
    margin-bottom: 15px;
  }
  
  .username {
    color: #e6b333;
    font-size: 1.2em;
    margin-top: 10px;
  }
  </style>
  