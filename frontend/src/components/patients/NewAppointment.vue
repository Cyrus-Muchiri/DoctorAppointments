

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

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import axios from 'axios'
import { API_URL } from '../../env'

const router = useRouter()
const toast = useToast()

const appointmentDate = ref('')
const reason = ref('')
const submitting = ref(false)

const submitForm = () => {
  if (!appointmentDate.value || !reason.value) {
    alert('Please fill in both fields.')
    return
  }

  submitting.value = true
  const token = sessionStorage.getItem('token')
  if (!token) {
    console.error('No authentication token found')
    toast.error('You must be logged in to book an appointment.')
    router.push('/login') 
    submitting.value = false
    return
  }

  axios.post(`${API_URL}/appointments`, {
    preferred_date: appointmentDate.value,
    reason: reason.value
  }, {
    headers: {
      "Content-Type": "application/json",
      "Authorization": `Bearer ${token}`
    }
  })
  .then(response => {
    toast.success('Appointment booked successfully!')
    router.push('/patients/appointments') // Redirect to appointments page
  })
  .catch(error => {
    console.error('Error booking appointment:', error)
    toast.error('Failed to book appointment. Please try again.')
  })
  .finally(() => {
    submitting.value = false
    appointmentDate.value = ''
    reason.value = ''
  })



  
}
</script>
