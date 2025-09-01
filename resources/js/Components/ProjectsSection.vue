<template>
    <section id="projects" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Nuestros Proyectos
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Desarrollamos soluciones innovadoras que transforman ideas en realidades digitales.
                </p>
            </div>

            <!-- Project Tabs -->
            <div class="flex justify-center mb-8">
                <div class="bg-white rounded-lg p-1 shadow-sm">
                    <button
                        @click="activeTab = 'all'"
                        :class="[
                            'px-6 py-2 rounded-md text-sm font-medium transition-all duration-200',
                            activeTab === 'all' 
                                ? 'bg-indigo-600 text-white shadow-sm' 
                                : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50'
                        ]"
                    >
                        Todos
                    </button>
                    <button
                        @click="activeTab = 'development'"
                        :class="[
                            'px-6 py-2 rounded-md text-sm font-medium transition-all duration-200',
                            activeTab === 'development' 
                                ? 'bg-indigo-600 text-white shadow-sm' 
                                : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50'
                        ]"
                    >
                        En Desarrollo
                    </button>
                    <button
                        @click="activeTab = 'deployed'"
                        :class="[
                            'px-6 py-2 rounded-md text-sm font-medium transition-all duration-200',
                            activeTab === 'deployed' 
                                ? 'bg-indigo-600 text-white shadow-sm' 
                                : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50'
                        ]"
                    >
                        Desplegados
                    </button>
                </div>
            </div>

            <!-- Projects Grid -->
            <div v-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="project in filteredProjects" 
                    :key="project.id"
                    class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1"
                >
                    <!-- Project Image -->
                    <div class="relative h-48 bg-gray-200">
                        <img 
                            :src="project.image" 
                            :alt="project.name"
                            class="w-full h-full object-cover"
                        >
                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            <span 
                                :class="[
                                    'px-3 py-1 text-xs font-medium rounded-full',
                                    getStatusBadgeClass(project.status)
                                ]"
                            >
                                {{ getStatusLabel(project.status) }}
                            </span>
                        </div>
                        <!-- Featured Badge -->
                        <div v-if="project.is_featured" class="absolute top-3 right-3">
                            <span class="bg-yellow-500 text-white px-2 py-1 text-xs font-medium rounded-full">
                                ⭐ Destacado
                            </span>
                        </div>
                    </div>

                    <!-- Project Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ project.name }}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ project.description }}
                        </p>

                        <!-- Technologies -->
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                <span 
                                    v-for="tech in project.technologies.slice(0, 4)" 
                                    :key="tech.id"
                                    :style="{ backgroundColor: tech.color + '20', color: tech.color }"
                                    class="px-2 py-1 text-xs font-medium rounded-full flex items-center"
                                >
                                    <i :class="tech.icon" class="mr-1"></i>
                                    {{ tech.name }}
                                </span>
                                <span 
                                    v-if="project.technologies.length > 4"
                                    class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600"
                                >
                                    +{{ project.technologies.length - 4 }}
                                </span>
                            </div>
                        </div>

                        <!-- Features -->
                        <div v-if="project.features && project.features.length > 0" class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Características:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li v-for="feature in project.features.slice(0, 3)" :key="feature" class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>

                        <!-- Project Links -->
                        <div class="flex space-x-3">
                            <a 
                                v-if="project.url"
                                :href="project.url" 
                                target="_blank"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200"
                            >
                                Ver Proyecto
                            </a>
                            <a 
                                v-if="project.repository"
                                :href="project.repository" 
                                target="_blank"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200"
                            >
                                <i class="fab fa-github mr-1"></i>
                                Código
                            </a>
                            <button 
                                v-if="!project.url && !project.repository"
                                @click="showProjectDetails(project)"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200"
                            >
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay proyectos</h3>
                <p class="text-gray-600">No se encontraron proyectos para esta categoría.</p>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-12">
                <div class="bg-white rounded-lg p-8 shadow-sm">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        ¿Tienes un proyecto en mente?
                    </h3>
                    <p class="text-xl text-gray-600 mb-6">
                        Trabajemos juntos para convertir tu idea en realidad digital.
                    </p>
                    <a 
                        href="#contact" 
                        class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105"
                    >
                        Iniciar Proyecto
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

// Reactive data
const projects = ref([])
const activeTab = ref('all')

// Computed
const filteredProjects = computed(() => {
    if (activeTab.value === 'all') {
        return projects.value
    }
    return projects.value.filter(project => project.status === activeTab.value)
})

// Methods
const fetchProjects = async () => {
    try {
        const response = await axios.get('/api/public/projects')
        projects.value = response.data
    } catch (error) {
        console.error('Error fetching projects:', error)
    }
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

const showProjectDetails = (project) => {
    alert(`Detalles del proyecto: ${project.name}\n\n${project.description}`)
    // Aquí podrías abrir un modal con más detalles
}

// Lifecycle
onMounted(() => {
    fetchProjects()
})
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
