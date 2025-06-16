// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const routes = [

  {
    path: '/',
    name: 'Login',
    component: import('../views/authentication/Loginview.vue'), // Lazy-loaded
  },

  {
    path: '/doctors/dashboard',
    name: 'DoctorDashboard',
    component: import('../views/doctors/AppointmentsView.vue'), // Lazy-loaded
  },


   {
    path: '/patients/dashboard',
    name: 'PatientDashboard',
    component: import('../views/patients/PatientsAppointmentsView.vue'), // Lazy-loaded
  },

   {
    path: '/patients/new-appointment',
    name: 'NewAppointment',
    component: import('../views/patients/NewAppointmentView.vue'), // Lazy-loaded
  },


  
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
