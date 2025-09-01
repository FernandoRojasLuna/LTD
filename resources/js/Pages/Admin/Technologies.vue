<template>
    <AppLayout title="Gestión de Tecnologías">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Tecnologías
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Gestión de Tecnologías del Laboratorio
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Esta página estará disponible próximamente. Aquí podrás gestionar todas las tecnologías utilizadas en los proyectos.
                    </p>
                    <Link 
                        :href="route('dashboard')" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium"
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
