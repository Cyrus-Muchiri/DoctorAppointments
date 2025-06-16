<script setup lang="ts">
import Sidebar from './Sidebar.vue'
import Navbar from './Navbar.vue'
import Footer from './Footer.vue'
import { useRoute } from 'vue-router'

/* Map routes → page titles (fallback to path) */
const titles: Record<string, string> = {
  '/': 'Dashboard',
  '/users': 'User Management',
  '/financials': 'Financials',
  '/settings': 'Settings'
}

const route = useRoute()
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Left rail -->
    <Sidebar />

    <!-- Main column -->
    <div class="flex-1 flex flex-col">
      <Navbar :title="titles[route.path] ?? route.path" />

      <!-- Page body -->
      <main class="flex-1 p-6 overflow-y-auto">
        <slot />
      </main>

      <Footer />
    </div>
  </div>
</template>
