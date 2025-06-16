<script setup>
import { ref } from 'vue'

const appointmentDate = ref('')
const reason = ref('')
const submitting = ref(false)

const submitForm = () => {
  if (!appointmentDate.value || !reason.value) {
    alert('Please fill in both fields.')
    return
  }

  submitting.value = true

  // Simulate API call
  setTimeout(() => {
    alert(`Appointment booked on ${appointmentDate.value} for "${reason.value}"`)
    submitting.value = false
    appointmentDate.value = ''
    reason.value = ''
  }, 1500)
}
</script>

<template>
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Book Appointment</h2>

    <form @submit.prevent="submitForm" class="space-y-5">
      <!-- Date -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input
          type="date"
          v-model="appointmentDate"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none"
        />
      </div>

      <!-- Reason -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
        <textarea
          v-model="reason"
          rows="4"
          placeholder="Describe the reason for the appointment"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none"
        ></textarea>
      </div>

      <!-- Submit -->
      <div>
        <button
          type="submit"
          :disabled="submitting"
          class="w-full bg-teal-600 text-white py-2 rounded-md hover:bg-teal-700 disabled:opacity-50"
        >
          {{ submitting ? 'Submitting...' : 'Submit Appointment' }}
        </button>
      </div>
    </form>
  </div>
</template>
