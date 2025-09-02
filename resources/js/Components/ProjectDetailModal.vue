<template>
    <DialogModal :show="show" @close="closeModal" max-width="4xl">
        <template #title>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">{{ project?.name }}</h3>
                <span 
                    :class="[
                        'px-3 py-1 text-sm font-medium rounded-full',
                        getStatusBadgeClass(project?.status)
                    ]"
                >
                    {{ getStatusLabel(project?.status) }}
                </span>
            </div>
        </template>

        <template #content>
            <div v-if="project" class="space-y-6">
                <!-- Project Image -->
                <div class="relative h-64 bg-gray-200 rounded-lg overflow-hidden">
                    <img 
                        :src="project.image" 
                        :alt="project.name"
                        class="w-full h-full object-cover"
                    >
                    <div v-if="project.is_featured" class="absolute top-4 right-4">
                        <span class="bg-yellow-500 text-white px-3 py-1 text-sm font-medium rounded-full flex items-center">
                            ⭐ Proyecto Destacado
                        </span>
                    </div>
                </div>

                <!-- Project Description -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Descripción</h4>
                    <p class="text-gray-700 leading-relaxed">
                        {{ project.full_description || project.description }}
                    </p>
                </div>

                <!-- Project Timeline -->
                <div v-if="project.start_date || project.end_date" class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Cronología del Proyecto
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-if="project.start_date" class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Fecha de Inicio</p>
                                <p class="text-sm text-gray-600">{{ formatDate(project.start_date) }}</p>
                            </div>
                        </div>
                        <div v-if="project.end_date" class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ project.status === 'completed' ? 'Fecha de Finalización' : 'Fecha Estimada' }}</p>
                                <p class="text-sm text-gray-600">{{ formatDate(project.end_date) }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="project.start_date && project.end_date" class="mt-3 pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Duración:</span> {{ getProjectDuration(project.start_date, project.end_date) }}
                        </p>
                    </div>
                </div>

                <!-- Technologies Used -->
                <div v-if="project.technologies && project.technologies.length > 0">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Tecnologías Utilizadas
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        <span 
                            v-for="tech in project.technologies" 
                            :key="tech.id"
                            :style="{ backgroundColor: tech.color + '20', color: tech.color, borderColor: tech.color }"
                            class="px-3 py-2 text-sm font-medium rounded-lg border flex items-center"
                        >
                            <i :class="tech.icon" class="mr-2"></i>
                            {{ tech.name }}
                        </span>
                    </div>
                </div>

                <!-- Project Features -->
                <div v-if="project.features && project.features.length > 0">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Características Principales
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div 
                            v-for="feature in project.features" 
                            :key="feature" 
                            class="flex items-center p-3 bg-green-50 rounded-lg"
                        >
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">{{ feature }}</span>
                        </div>
                    </div>
                </div>

                <!-- Project Status Info -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Estado del Proyecto
                    </h4>
                    <p class="text-sm text-gray-700">{{ getStatusDescription(project.status) }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button
                        v-if="project.url"
                        @click="openUrl(project.url)"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Ver Proyecto en Vivo
                    </button>
                    <button
                        v-if="project.repository"
                        @click="openUrl(project.repository)"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center"
                    >
                        <i class="fab fa-github mr-2"></i>
                        Ver Código Fuente
                    </button>
                    <button
                        v-if="!project.url && !project.repository"
                        disabled
                        class="flex-1 bg-gray-300 text-gray-500 py-3 px-6 rounded-lg text-sm font-medium cursor-not-allowed flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Proyecto en Desarrollo
                    </button>
                </div>
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="closeModal">
                Cerrar
            </SecondaryButton>
        </template>
    </DialogModal>
</template>

<script setup>
import { computed } from 'vue'
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

// Props
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    project: {
        type: Object,
        default: null
    }
})

// Emits
const emit = defineEmits(['close'])

// Methods
const closeModal = () => {
    emit('close')
}

const openUrl = (url) => {
    window.open(url, '_blank')
}

const getStatusLabel = (status) => {
    const labels = {
        'development': 'En Desarrollo',
        'deployed': 'Desplegado',
        'paused': 'Pausado',
        'completed': 'Completado'
    }
    return labels[status] || 'Desconocido'
}

const getStatusBadgeClass = (status) => {
    const classes = {
        'development': 'bg-blue-100 text-blue-800',
        'deployed': 'bg-green-100 text-green-800',
        'paused': 'bg-orange-100 text-orange-800',
        'completed': 'bg-purple-100 text-purple-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusDescription = (status) => {
    const descriptions = {
        'development': 'Este proyecto está actualmente en desarrollo. Nuestro equipo está trabajando activamente en nuevas funcionalidades y mejoras.',
        'deployed': 'Este proyecto está desplegado y funcionando en producción. Los usuarios pueden acceder y utilizar todas sus funcionalidades.',
        'paused': 'El desarrollo de este proyecto está temporalmente pausado. Puede reanudarse en el futuro según las prioridades del negocio.',
        'completed': 'Este proyecto ha sido completado exitosamente. Todas las funcionalidades planificadas han sido implementadas y entregadas.'
    }
    return descriptions[status] || 'Estado del proyecto no especificado.'
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const getProjectDuration = (startDate, endDate) => {
    const start = new Date(startDate)
    const end = new Date(endDate)
    const diffTime = Math.abs(end - start)
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    
    if (diffDays < 30) {
        return `${diffDays} días`
    } else if (diffDays < 365) {
        const months = Math.floor(diffDays / 30)
        return `${months} ${months === 1 ? 'mes' : 'meses'}`
    } else {
        const years = Math.floor(diffDays / 365)
        const remainingMonths = Math.floor((diffDays % 365) / 30)
        let result = `${years} ${years === 1 ? 'año' : 'años'}`
        if (remainingMonths > 0) {
            result += ` y ${remainingMonths} ${remainingMonths === 1 ? 'mes' : 'meses'}`
        }
        return result
    }
}
</script>
