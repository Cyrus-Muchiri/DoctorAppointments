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
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(appointment, index) in appointments"
            :key="index"
            class="border-b hover:bg-gray-50"
          >
            <td class="px-6 py-4 font-medium text-gray-900">{{ appointment.reason }}</td>
            <td class="px-6 py-4 text-gray-600">{{ appointment.preferred_date }}</td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  getStatusPillColor(appointment.status)
                ]"
              >
                {{ appointment.status }}
              </span>
            </td>
         
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref,onMounted } from 'vue';
import { API_URL } from '../../env';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
const router = useRouter();
const toast = useToast();

const appointments = ref([]);

async function fetchAppointments() {
  try {

    const token = sessionStorage.getItem('token');
    if (!token) {
      console.error('No authentication token found');
      router.push('/login'); // Redirect to login if no token
      return;
    }

    const response = await fetch(`${API_URL}/appointments`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
   const responseJson = await response.json();
   appointments.value = responseJson.data
  } catch (error) {
    console.error('Error fetching appointments:', error);
    toast.error('Failed to fetch appointments');
  }
}


function getStatusPillColor(status) {
  if (status === "approved") {

    return "bg-green-100 text-green-700'"

  }
  else if (status === "rejected") {

    return "bg-red-100 text-red-700"

  }

  else if (status == "pending") {

    return "bg-yellow-100 text-yellow-700"

  }
}
onMounted(() => {
  fetchAppointments();
});
</script>
