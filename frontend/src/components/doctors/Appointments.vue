<template>
  <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4 flex justify-between items-center">
      <h2 class="text-lg font-semibold">Clients List</h2>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 border-b text-xs uppercase text-gray-600">
          <tr>
            <th class="px-6 py-3">Reason</th>
            <th class="px-6 py-3">Date</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(appointment, index) in appointments"
            :key="index"
            class="border-b hover:bg-gray-50"
          >
            <td class="px-6 py-4 font-medium text-gray-900">{{ appointment.reason }}</td>
            <td class="px-6 py-4 text-gray-600">{{ appointment.date }}</td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  appointment.status === 'Approved'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'
                ]"
              >
                {{ client.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <button
                class="text-teal-600 hover:underline mr-2"
                @click="alert('View ' + appointment.id)"
              >
                Change Status
              </button>
              <button
                class="text-red-500 hover:underline"
                @click="alert('Remove ' + appointment.id)"
              >
                Remove
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const appointments = ref([]);
</script>
