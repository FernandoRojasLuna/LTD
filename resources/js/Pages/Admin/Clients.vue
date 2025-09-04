<template>
    <AppLayout title="Gestión de Clientes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Clientes
            </h2>
        </template>

        <div class="py-6">
            <ClientManager />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ClientManager from '@/Components/Admin/ClientManager.vue'
</script>
