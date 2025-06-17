// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const routes = [

  {
    path: '/',
    name: 'Login',
    component: import('../views/authentication/Loginview.vue'),
  },

  {
    path: '/doctors/dashboard',
    name: 'DoctorDashboard',
    component: import('../views/doctors/AppointmentsView.vue'),
    meta: {
      requiresAuth: true,
      roles: ['doctor'],
    },
  },


  {
    path: '/patients/dashboard',
    name: 'PatientDashboard',
    component: import('../views/patients/PatientsAppointmentsView.vue'),
    meta: {
      requiresAuth: true,
      roles: ['patient'],
    },
  },

  {
    path: '/patients/new-appointment',
    name: 'NewAppointment',
    component: import('../views/patients/NewAppointmentView.vue'),
    meta: {
      requiresAuth: true,
      roles: ['patient'],
    },
  },



]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = sessionStorage.getItem('token')
  const user = JSON.parse(sessionStorage.getItem('user') || '{}')

  if (to.meta.requiresAuth) {
    if (!token) {
      return next('/')
    }

    if (to.meta.roles && !to.meta.roles.includes(user.role)) {

      return next('/') //todo : use unauthorized view
    }
  }

  next()
})


export default router
