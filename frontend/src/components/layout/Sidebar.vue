<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { API_URL } from '../../env'
import {
  NumberedListIcon,
  CalendarIcon
} from '@heroicons/vue/24/outline'


const router = useRouter()
interface NavItem {
  name: string
  icon: any
  to: string
}

const isOpen = ref(true)

const user = JSON.parse(sessionStorage.getItem('user') || '{}')
if (!user || !user.role) {
  console.error('User not found or role is missing in sessionStorage')
  router.push('/')
}



var nav: NavItem[]

if (user.role === 'doctor') {
  nav = [
    { name: 'My Appointments', icon: NumberedListIcon, to: '/doctors/dashboard' },
  ]
} else if (user.role === 'patient') {
  nav = [
    { name: 'My Appointments', icon: NumberedListIcon, to: '/patients/dashboard' },
    { name: 'New Appointment', icon: CalendarIcon, to: '/patients/new-appointment' },
  ]
} else {
  console.error('Unknown user role:', user.role)
  router.push('/')
}

</script>

<template>
  <aside :class="[
    'bg-[#043d3b] text-white flex flex-col transition-all duration-300',
    isOpen ? 'w-64' : 'w-20'
  ]">
    <!-- Logo -->
    <router-link to="/" class="h-16 flex items-center justify-center gap-2">
      <!-- <img src="/logo.svg" class="h-8 w-auto" /> -->
      <span v-show="isOpen" class="font-semibold text-lg">Appointment System</span>
    </router-link>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-2">
      <router-link v-for="item in nav" :key="item.name" :to="item.to" class="group flex items-center gap-3 px-3 py-2 rounded-md
               transition-colors" :class="$route.path === item.to
                ? 'bg-teal-600 text-white'
                : 'text-teal-100 hover:bg-teal-700 hover:text-white'">
        <component :is="item.icon" class="h-6 w-6" />
        <span v-show="isOpen">{{ item.name }}</span>
      </router-link>
    </nav>

    <!-- Collapse button -->
    <button class="h-12 flex items-center justify-center hover:bg-teal-700" @click="isOpen = !isOpen">
      <svg :class="['h-6 w-6 transition-transform', isOpen ? '' : 'rotate-180']" fill="none" stroke="currentColor"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
  </aside>
</template>

<style scoped>
/* no extra styles needed – Tailwind does the heavy lifting */
</style>
