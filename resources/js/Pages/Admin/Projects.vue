<template>
    <AppLayout title="Gestión de Proyectos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Proyectos
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <ProjectManager />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ProjectManager from '@/Components/Admin/ProjectManager.vue'
</script>
