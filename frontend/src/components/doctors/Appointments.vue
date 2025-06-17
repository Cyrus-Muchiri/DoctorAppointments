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
          <tr v-for="(appointment, index) in appointments" :key="index" class="border-b hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">{{ appointment.reason }}</td>
            <td class="px-6 py-4 text-gray-600">{{ appointment.preferred_date }}</td>
            <td class="px-6 py-4">
              <span :class="[
                'px-2 py-1 text-xs font-medium rounded-full',
                getStatusPillColor(appointment.status)
              ]">
                {{ appointment.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <button v-if="appointment.status==='pending'"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                @click="showChangeStatusModal(appointment)">
                Change Status
              </button>

            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>


  <div id="crud-backdrop" class=" fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

  <!-- Main modal -->
  <div id="crud-modal" tabindex="-1" aria-hidden="true"
    class=" hidden  overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <!-- Modal header -->
        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Update Appointment
          </h3>
          <button type="button" id="close-modal-btn"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5">
          <div class="grid gap-4 mb-4 grid-cols-2">

            <div class="col-span-2 sm:col-span-1">
              <label for="category"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
              <select v-model="editiedAppointment.status" id="category"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <option selected="">Select Status</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="pending">Pending</option>
              </select>
            </div>
            <div class="col-span-2">
              <label for="description"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
              <textarea id="description" rows="4" v-model="editiedAppointment.remarks"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Write doctor remarks here"></textarea>
            </div>
          </div>
          <button  type="submit" @click="changeStatus()"
            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                clip-rule="evenodd"></path>
            </svg>
            Update Appointment Status
          </button>
        </div>
      </div>
    </div>
  </div>


</template>

<script setup>
import { ref, onMounted } from 'vue';
import { API_URL } from '../../env';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';
const router = useRouter();
const toast = useToast();

const appointments = ref([]);

const editiedAppointment = ref({})

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

function showChangeStatusModal(appointment) {
  editiedAppointment.value = appointment
  const modal = document.getElementById('crud-modal');
  const backdrop = document.getElementById('crud-backdrop');
  const closeBtn = document.getElementById('close-modal-btn')

  closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    backdrop.classList.add('hidden');
  });

  backdrop.classList.remove('hidden');
  modal.classList.remove('hidden');
  modal.classList.add('flex')


}


async function changeStatus() {

  const token = sessionStorage.getItem('token');
  if (!token) {
    console.error('No authentication token found');
    router.push('/login'); // Redirect to login if no token
    return;
  }

  axios.put(`${API_URL}/appointments/` + editiedAppointment.value.id, {
    status: editiedAppointment.value.status,
    remarks: editiedAppointment.value.remarks
  }, {
    headers: {
      "Content-Type": "application/json",
      "Authorization": `Bearer ${token}`
    }
  })
    .then(response => {
      toast.success('Appointment updated successfully!')
      modal.classList.add('hidden');
      backdrop.classList.add('hidden');
      fetchAppointments()
      return
      // router.push('/doctors/appointments') // Redirect to appointments page
    })
    .catch(error => {
      console.error('Error updating appointment:', error)
      toast.error('Failed to update appointment. Please try again.')
      return
    })
    .finally(() => {
      editiedAppointment.value = {}
    })

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
