<template>
    <AppLayout>
        <Appointments />
    </AppLayout>

</template>
<script setup>
import { ref } from 'vue'
import AppLayout from '../../components/layout/AppLayout.vue'
import Appointments from '../../components/patients/Appointments.vue'
</script>