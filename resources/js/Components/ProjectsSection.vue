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
                    class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer"
                    @click="showProjectDetails(project)"
                >
                    <!-- Project Image -->
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        <img 
                            :src="project.image" 
                            :alt="project.name"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        >
                        
                        <!-- Subtle Hover Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                            <div class="text-center text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 mb-3 mx-auto w-fit">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">Ver detalles</p>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3 z-10">
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
                        <div v-if="project.is_featured" class="absolute top-3 right-3 z-10">
                            <span class="bg-yellow-500 text-white px-2 py-1 text-xs font-medium rounded-full">
                                ⭐ Destacado
                            </span>
                        </div>
                    </div>

                    <!-- Project Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-300">
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
                        <div v-if="project.url || project.repository" class="flex space-x-3 mt-4">
                            <a 
                                v-if="project.url"
                                :href="project.url" 
                                target="_blank"
                                @click.stop
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200"
                            >
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Ver Proyecto
                            </a>
                            <a 
                                v-if="project.repository"
                                :href="project.repository" 
                                target="_blank"
                                @click.stop
                                class="flex-1 bg-gray-800 hover:bg-gray-900 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200"
                            >
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                Código
                            </a>
                        </div>
                        
                        <!-- Subtle hint text -->
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-400 group-hover:text-indigo-500 transition-colors duration-300">
                                Click para ver detalles completos
                            </p>
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

        <!-- Project Details Modal -->
        <ProjectDetailsModal 
            :show="showModal" 
            :project="selectedProject" 
            @close="closeModal" 
        />
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import ProjectDetailsModal from '@/Components/ProjectDetailsModal.vue'

// Reactive data
const projects = ref([])
const activeTab = ref('all')
const showModal = ref(false)
const selectedProject = ref(null)

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
    selectedProject.value = project
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    selectedProject.value = null
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
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
