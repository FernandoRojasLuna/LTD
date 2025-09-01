<template>
    <div>
        <Head :title="title" />
        
        <!-- Simple Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ title || 'Laboratorio Digital' }}
                        </h1>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
    title: String,
})
</script>
