<script setup>
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

// Reactive data
const stats = ref({
    banners: 0,
    contents: 0,
    projects: 0,
    technologies: 0
});

const recentActivities = ref([]);
const isLoading = ref(true);

// Computed para métricas adicionales
const totalItems = computed(() => 
    stats.value.banners + stats.value.contents + stats.value.projects + stats.value.technologies
);

const mostActiveSection = computed(() => {
    const sections = [
        { name: 'Proyectos', count: stats.value.projects },
        { name: 'Banners', count: stats.value.banners },
        { name: 'Contenidos', count: stats.value.contents },
        { name: 'Tecnologías', count: stats.value.technologies }
    ];
    return sections.reduce((max, section) => section.count > max.count ? section : max);
});

// Methods
const fetchStats = async () => {
    try {
        isLoading.value = true;
        const [bannersRes, contentsRes, projectsRes, technologiesRes] = await Promise.all([
            axios.get('/api/banners'),
            axios.get('/api/contents'),
            axios.get('/api/projects'),
            axios.get('/api/technologies')
        ]);
        
        stats.value = {
            banners: bannersRes.data.length,
            contents: contentsRes.data.length,
            projects: projectsRes.data.length,
            technologies: technologiesRes.data.length
        };

        // Simular actividades recientes (posteriormente desde API)
        recentActivities.value = [
            { action: 'Proyecto creado', item: 'Sistema E-commerce', time: '2 min', type: 'project' },
            { action: 'Banner actualizado', item: 'Hero Principal', time: '15 min', type: 'banner' },
            { action: 'Tecnología agregada', item: 'Vue.js 3', time: '1 hora', type: 'tech' },
            { action: 'Contenido editado', item: 'Sobre Nosotros', time: '2 horas', type: 'content' }
        ];
        
    } catch (error) {
        console.error('Error fetching stats:', error);
    } finally {
        isLoading.value = false;
    }
};

// Lifecycle
onMounted(() => {
    fetchStats();
});
</script>

<template>
    <AppLayout title="Dashboard - LTD">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Section - Premium Design -->
            <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 mb-8 overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>
                
                <div class="relative flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">
                            ¡Bienvenido de vuelta! 👋
                        </h2>
                        <p class="text-blue-100 text-lg mb-4 max-w-2xl">
                            Tu laboratorio digital está funcionando perfectamente. Aquí tienes un resumen de la actividad reciente.
                        </p>
                        <div class="flex items-center space-x-6 text-white">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-sm font-medium">Todos los sistemas operativos</span>
                            </div>
                            <div class="text-sm text-blue-200">
                                Última actualización: hace {{ new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) }}
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-24 h-24 bg-white/10 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div v-for="n in 4" :key="n" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="animate-pulse">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded"></div>
                        </div>
                        <div class="w-16 h-8 bg-gray-200 rounded mb-2"></div>
                        <div class="w-24 h-4 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards - Corporate Level -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Banners Card -->
                <div class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ stats.banners }}</div>
                        <div class="text-sm font-medium text-gray-600">Banners Activos</div>
                    </div>
                    <Link 
                        :href="route('admin.banners')" 
                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700"
                    >
                        Gestionar
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>

                <!-- Contents Card -->
                <div class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ stats.contents }}</div>
                        <div class="text-sm font-medium text-gray-600">Contenidos</div>
                    </div>
                    <Link 
                        :href="route('admin.contents')" 
                        class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700"
                    >
                        Gestionar
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>

                <!-- Projects Card -->
                <div class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ stats.projects }}</div>
                        <div class="text-sm font-medium text-gray-600">Proyectos</div>
                    </div>
                    <Link 
                        :href="route('admin.projects')" 
                        class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700"
                    >
                        Gestionar
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>

                <!-- Technologies Card -->
                <div class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-purple-200 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <div class="text-purple-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ stats.technologies }}</div>
                        <div class="text-sm font-medium text-gray-600">Tecnologías</div>
                    </div>
                    <Link 
                        :href="route('admin.technologies')" 
                        class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700"
                    >
                        Gestionar
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Quick Actions - Premium -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h3>
                            <span class="text-sm text-gray-500">Crear nuevo contenido</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <Link 
                                :href="route('admin.banners')" 
                                class="group relative p-4 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-300 transform hover:scale-105"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Nuevo Banner</div>
                                        <div class="text-sm text-gray-600">Crear banner promocional</div>
                                    </div>
                                </div>
                            </Link>

                            <Link 
                                :href="route('admin.contents')" 
                                class="group relative p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 rounded-xl transition-all duration-300 transform hover:scale-105"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center group-hover:bg-emerald-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Nuevo Contenido</div>
                                        <div class="text-sm text-gray-600">Agregar información</div>
                                    </div>
                                </div>
                            </Link>

                            <Link 
                                :href="route('admin.projects')" 
                                class="group relative p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 rounded-xl transition-all duration-300 transform hover:scale-105"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center group-hover:bg-indigo-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Nuevo Proyecto</div>
                                        <div class="text-sm text-gray-600">Añadir proyecto</div>
                                    </div>
                                </div>
                            </Link>

                            <Link 
                                :href="route('admin.technologies')" 
                                class="group relative p-4 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-300 transform hover:scale-105"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-700 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Nueva Tecnología</div>
                                        <div class="text-sm text-gray-600">Agregar tecnología</div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Insights -->
                <div class="space-y-6">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
                        <div class="space-y-4">
                            <div v-for="activity in recentActivities" :key="activity.item" class="flex items-start space-x-3">
                                <div :class="[
                                    'w-8 h-8 rounded-lg flex items-center justify-center',
                                    activity.type === 'project' ? 'bg-indigo-100 text-indigo-600' :
                                    activity.type === 'banner' ? 'bg-blue-100 text-blue-600' :
                                    activity.type === 'tech' ? 'bg-purple-100 text-purple-600' :
                                    'bg-emerald-100 text-emerald-600'
                                ]">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ activity.action }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ activity.item }}</p>
                                    <p class="text-xs text-gray-500 mt-1">hace {{ activity.time }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Insights -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Total elementos</span>
                                <span class="text-lg font-bold text-gray-900">{{ totalItems }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Sección más activa</span>
                                <span class="text-sm font-bold text-blue-600">{{ mostActiveSection.name }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <Link 
                                href="/" 
                                target="_blank"
                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Ver Sitio Público
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
