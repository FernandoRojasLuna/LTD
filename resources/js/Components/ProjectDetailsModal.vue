<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$emit('close')"></div>
        
        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <!-- Close Button -->
                <button 
                    @click="$emit('close')"
                    class="absolute top-4 right-4 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-2 shadow-sm transition-all duration-200"
                >
                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Content Container -->
                <div class="overflow-y-auto max-h-[90vh]">
                    <!-- Hero Section -->
                    <div class="relative h-64 bg-gradient-to-br from-blue-600 to-purple-700">
                        <img 
                            v-if="project.image" 
                            :src="project.image" 
                            :alt="project.name"
                            class="w-full h-full object-cover opacity-20"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h1 class="text-3xl font-bold mb-2">{{ project.name }}</h1>
                            <div class="flex items-center space-x-4">
                                <span :class="statusClasses[project.status]" class="px-3 py-1 rounded-full text-sm font-medium">
                                    {{ statusLabels[project.status] }}
                                </span>
                                <span v-if="project.is_featured" class="bg-yellow-500 text-yellow-900 px-3 py-1 rounded-full text-sm font-medium">
                                    ⭐ Destacado
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- Description -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Descripción</h2>
                            <p class="text-gray-700 leading-relaxed">{{ project.description }}</p>
                            <div v-if="project.full_description" class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h3 class="font-medium text-gray-900 mb-2">Descripción Completa</h3>
                                <p class="text-gray-700 leading-relaxed">{{ project.full_description }}</p>
                            </div>
                        </div>

                        <!-- Project Timeline -->
                        <div v-if="project.start_date || project.end_date" class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Cronología del Proyecto
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-if="project.start_date">
                                    <span class="text-sm font-medium text-blue-700">Fecha de Inicio:</span>
                                    <p class="text-blue-900">{{ formatDate(project.start_date) }}</p>
                                </div>
                                <div v-if="project.end_date">
                                    <span class="text-sm font-medium text-blue-700">Fecha de Finalización:</span>
                                    <p class="text-blue-900">{{ formatDate(project.end_date) }}</p>
                                </div>
                                <div v-if="!project.end_date && project.status === 'development'">
                                    <span class="text-sm font-medium text-blue-700">Estado:</span>
                                    <p class="text-blue-900">🚧 En desarrollo activo</p>
                                </div>
                            </div>
                        </div>

                        <!-- Technologies -->
                        <div v-if="project.technologies && project.technologies.length">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Tecnologías Utilizadas
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span 
                                    v-for="tech in project.technologies" 
                                    :key="tech.id"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800"
                                >
                                    {{ tech.name }}
                                </span>
                            </div>
                        </div>

                        <!-- Features -->
                        <div v-if="project.features && project.features.length">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Características
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div 
                                    v-for="(feature, index) in project.features" 
                                    :key="index"
                                    class="flex items-center space-x-2"
                                >
                                    <svg class="h-4 w-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">{{ feature }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Links Section -->
                        <div v-if="project.url || project.repository" class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                Enlaces del Proyecto
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                <a 
                                    v-if="project.url" 
                                    :href="project.url" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Ver Proyecto en Vivo
                                </a>
                                <a 
                                    v-if="project.repository" 
                                    :href="project.repository" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors duration-200"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    Ver Código
                                </a>
                            </div>
                        </div>

                        <!-- Empty State for Links -->
                        <div v-else class="bg-amber-50 border border-amber-200 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-amber-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-amber-800 font-medium">Enlaces no disponibles</span>
                            </div>
                            <p class="text-amber-700 text-sm mt-1">
                                Este proyecto aún no tiene enlaces configurados para el sitio en vivo o el repositorio de código.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    project: {
        type: Object,
        required: true
    }
})

defineEmits(['close'])

const statusLabels = {
    development: 'En Desarrollo',
    deployed: 'Desplegado',
    paused: 'Pausado', 
    completed: 'Completado'
}

const statusClasses = {
    development: 'bg-blue-500 text-white',
    deployed: 'bg-green-500 text-white',
    paused: 'bg-yellow-500 text-yellow-900',
    completed: 'bg-purple-500 text-white'
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>
