<template>
    <AppLayout title="Gestión de Proyectos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Proyectos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Gestión de Proyectos del Laboratorio
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Esta página estará disponible próximamente. Aquí podrás gestionar todos los proyectos en desarrollo y desplegados.
                    </p>
                    <Link 
                        :href="route('dashboard')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                    >
                        Volver al Dashboard
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>
