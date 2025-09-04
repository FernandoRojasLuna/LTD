<template>
    <AppLayout title="Gestión del Equipo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión del Equipo
            </h2>
        </template>

        <div class="py-6">
            <StaffManager />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import StaffManager from '@/Components/Admin/StaffManager.vue'
</script>
