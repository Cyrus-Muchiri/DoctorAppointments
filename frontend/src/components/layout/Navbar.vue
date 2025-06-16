<template>
  <header
    class="h-16 bg-white border-b flex items-center justify-between
           px-6 gap-4"
  >
    <h1 class="text-xl font-semibold">{{ title }}</h1>

    <!-- Right side -->
    <div class="flex items-center gap-3">
  

      <!-- User dropdown placeholder -->
      <ArrowLeftEndOnRectangleIcon
        class="h-6 w-6 text-teal-500 cursor-pointer"
        @click="logout()"
      />
      <img
        src="/avatar.png"
        class="h-9 w-9 rounded-full ring-2 ring-teal-500 cursor-pointer"
      />
    </div>
  </header>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
import { useRouter,RouterLink } from 'vue-router'


import {
 ArrowLeftEndOnRectangleIcon,
} from '@heroicons/vue/24/outline'

defineProps<{ title: string }>()

function logout() {
  const router = useRouter()
  // Perform logout logic here, e.g., clear auth tokens
  console.log('Logging out...')
  // Redirect to login page
  router.push('/login')
}
</script>
