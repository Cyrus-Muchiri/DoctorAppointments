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
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { API_URL } from '../../env'
import { useToast } from 'vue-toastification'
const router = useRouter()
const toast = useToast()


const email = ref('')
const password = ref('')

async function handleLogin() {
  console.log('Login clicked', { email: email.value, password: password.value })
  axios.post(API_URL + '/login', {
    email: email.value,
    password: password.value
  })
    .then(response => {
      sessionStorage.setItem('token', response.data.token)
      sessionStorage.setItem('user', JSON.stringify(response.data.user))
      toast.success('Login successful!')

      if (response.data.user.role === 'doctor') {
        router.push('/doctors/dashboard')
      } else if (response.data.user.role === 'patient') {
        router.push('/patients/dashboard')
      } else {
        console.error('Unknown user role:', response.data.user.role)
      }
    })
    .catch(error => {
      console.error('Login error:', error)
      toast.error('Login failed. Please check your credentials.')
    })







}

onMounted(() => {
  const user = JSON.parse(sessionStorage.getItem('user') || '{}')
  if (user && user.role) {
    if (user.role === 'doctor') {
      router.push('/doctors/dashboard')
    } else if (user.role === 'patient') {
      router.push('/patients/dashboard')
    }
  }
})


</script>
