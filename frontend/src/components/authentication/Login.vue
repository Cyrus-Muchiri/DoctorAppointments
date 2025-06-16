<template>
  <div class="min-h-screen flex flex-col md:flex-row items-center justify-center bg-white">
    <!-- Left Section -->
    <div class="w-full md:w-1/2 p-8 md:p-16 space-y-8">
      <div class="text-teal-700 font-semibold text-lg">Appointment System</div>
      <h1 class="text-4xl font-bold text-gray-900">Welcome Back</h1>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <input v-model="email" type="email" placeholder="stanley@gmail.com"
          class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-600" />
        <input v-model="password" type="password" placeholder="Password"
          class="w-full border border-gray-300 rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-600" />

        <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded hover:bg-teal-700 transition">
          Sign In
        </button>
      </form>
    </div>

    <!-- Right Section -->
    <div class="hidden lg:block md:w-1/2 max-w-sm m-4">
      <img src="/login-illustration.jpg" alt="Login visual" class="w-full object-cover rounded-xl" />
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const remember = ref(false)

function handleLogin() {
  console.log('Login clicked', { email: email.value, password: password.value, remember: remember.value })
}
</script>
