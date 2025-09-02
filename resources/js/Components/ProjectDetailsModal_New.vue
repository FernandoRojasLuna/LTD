<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center px-4" @click.self="$emit('close')">
        <!-- Backdrop with blur effect -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Modal Container -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-7xl max-h-[95vh] overflow-hidden">
            <!-- Enhanced Header with Corporate Design -->
            <div class="relative bg-gradient-to-br from-slate-900 via-gray-900 to-slate-800 text-white overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="topography" patternUnits="userSpaceOnUse" width="540" height="450">
                                <rect x="0" y="0" width="540" height="450" fill="transparent"/>
                                <path d="M540,450C540,450 540,225 540,225C540,225 405,270 270,225C135,180 0,225 0,225L0,450Z" fill="rgba(99, 102, 241, 0.1)"/>
                                <path d="M540,450C540,450 540,337.5 540,337.5C540,337.5 405,382.5 270,337.5C135,292.5 0,337.5 0,337.5L0,450Z" fill="rgba(59, 130, 246, 0.05)"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#topography)"/>
                    </svg>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-indigo-500/20 to-transparent rounded-full -translate-y-48 translate-x-48"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-purple-500/20 to-transparent rounded-full translate-y-36 -translate-x-36"></div>
                
                <div class="relative p-8">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Project Badge -->
                            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-4">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-sm font-semibold text-green-100">Proyecto Activo</span>
                            </div>
                            
                            <!-- Project Title -->
                            <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">
                                {{ project.title }}
                            </h2>
                            
                            <!-- Project Subtitle -->
                            <p class="text-xl text-gray-200 mb-6 max-w-3xl leading-relaxed">
                                {{ project.description }}
                            </p>
                            
                            <!-- Status Badge -->
                            <div class="inline-flex items-center">
                                <span :class="getStatusBadgeClass(project.status)" class="px-6 py-3 rounded-2xl font-bold text-lg shadow-lg">
                                    {{ getStatusLabel(project.status) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-start space-x-4 ml-8">
                            <button 
                                v-if="project.url" 
                                @click="openUrl(project.url)"
                                class="group flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-2xl hover:bg-white/20 transition-all duration-300 transform hover:scale-105"
                            >
                                <svg class="w-5 h-5 mr-2 text-white group-hover:text-blue-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span class="text-white font-semibold">Ver Proyecto</span>
                            </button>
                            
                            <button 
                                v-if="project.repository" 
                                @click="openUrl(project.repository)"
                                class="group flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-2xl hover:bg-white/20 transition-all duration-300 transform hover:scale-105"
                            >
                                <svg class="w-5 h-5 mr-2 text-white group-hover:text-gray-200 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                <span class="text-white font-semibold">Código</span>
                            </button>
                            
                            <button 
                                @click="$emit('close')" 
                                class="group w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl hover:bg-red-500/20 transition-all duration-300 flex items-center justify-center"
                            >
                                <svg class="w-6 h-6 text-white group-hover:text-red-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="overflow-y-auto max-h-[calc(95vh-300px)]">
                <!-- Content Grid -->
                <div class="p-8 bg-gray-50">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Content (Left Column) -->
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Project Description - PREMIUM DESIGN -->
                            <div class="bg-gradient-to-br from-white via-slate-50 to-gray-50 rounded-3xl p-8 shadow-xl border border-slate-200 relative overflow-hidden">
                                <!-- Decorative Elements -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-indigo-100 to-transparent rounded-bl-full opacity-60"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-100 to-transparent rounded-tr-full opacity-40"></div>
                                
                                <div class="relative">
                                    <div class="flex items-start mb-6">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg flex-shrink-0">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Descripción del Proyecto</h3>
                                            <p class="text-gray-600 font-medium">Solución integral desarrollada con estándares empresariales</p>
                                        </div>
                                    </div>
                                    
                                    <div class="prose prose-lg prose-gray max-w-none">
                                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
                                            <!-- Quote decoration -->
                                            <div class="absolute top-4 left-4 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            
                                            <div class="pl-12">
                                                <p class="text-gray-800 text-lg leading-relaxed font-medium mb-4">
                                                    {{ project.full_description || project.description }}
                                                </p>
                                                
                                                <!-- Key Benefits -->
                                                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div class="flex items-center p-3 bg-green-50 rounded-xl">
                                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm font-semibold text-gray-800">Escalable & Robusto</span>
                                                    </div>
                                                    <div class="flex items-center p-3 bg-blue-50 rounded-xl">
                                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01.293.707V12a1 1 0 102 0V9a1 1 0 01.293-.707L13.586 6H12a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V7.414l-2.293 2.293A1 1 0 0112 10v2a3 3 0 11-6 0v-2a1 1 0 01.293-.707L8.586 7H7a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm font-semibold text-gray-800">Alta Performance</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Features - CORPORATE EXCELLENCE -->
                            <div v-if="project.features && project.features.length > 0" class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 rounded-3xl p-8 shadow-xl border border-emerald-200 relative overflow-hidden">
                                <!-- Background Pattern -->
                                <div class="absolute inset-0 opacity-10">
                                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.5"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100" height="100" fill="url(#grid)" />
                                    </svg>
                                </div>
                                
                                <div class="relative">
                                    <div class="flex items-center mb-8">
                                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-1">Características Principales</h3>
                                            <p class="text-gray-700 font-medium">Funcionalidades diseñadas para el éxito empresarial</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div 
                                            v-for="(feature, index) in project.features" 
                                            :key="index" 
                                            class="group bg-white rounded-2xl p-6 shadow-lg border border-white/50 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 hover:scale-105 relative overflow-hidden"
                                        >
                                            <!-- Feature Number Badge -->
                                            <div class="absolute top-4 right-4 w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                                <span class="text-white text-sm font-bold">{{ index + 1 }}</span>
                                            </div>
                                            
                                            <!-- Feature Content -->
                                            <div class="flex items-start">
                                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-emerald-700 transition-colors duration-300">{{ feature }}</h4>
                                                    <div class="w-full bg-gradient-to-r from-emerald-200 to-green-200 rounded-full h-1.5 mb-2">
                                                        <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-1.5 rounded-full w-0 group-hover:w-full transition-all duration-1000 ease-out"></div>
                                                    </div>
                                                    <p class="text-sm text-gray-600 font-medium">Implementación de nivel empresarial</p>
                                                </div>
                                            </div>
                                            
                                            <!-- Hover gradient overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-green-50 opacity-0 group-hover:opacity-50 transition-opacity duration-300 rounded-2xl"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Technologies Section - PREMIUM CORPORATE DESIGN -->
                            <div v-if="project.technologies && project.technologies.length > 0" class="bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 rounded-3xl p-8 shadow-xl border border-slate-200 relative overflow-hidden">
                                <!-- Background Pattern -->
                                <div class="absolute inset-0 opacity-5">
                                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, lightgray 2px, transparent 0), radial-gradient(circle at 75px 75px, lightgray 2px, transparent 0); background-size: 100px 100px;"></div>
                                </div>
                                
                                <div class="relative">
                                    <div class="flex items-center mb-8">
                                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-1">Stack Tecnológico</h3>
                                            <p class="text-gray-600">Tecnologías de vanguardia empresarial</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                                        <div 
                                            v-for="tech in project.technologies" 
                                            :key="tech.id"
                                            class="group relative bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 cursor-pointer overflow-hidden"
                                        >
                                            <!-- Tech Card Background Gradient -->
                                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" :style="{ background: `linear-gradient(135deg, ${tech.color}15, ${tech.color}25)` }"></div>
                                            
                                            <!-- Tech Logo/Icon -->
                                            <div class="relative text-center">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:scale-110" :style="{ backgroundColor: tech.color + '20' }">
                                                    <i :class="tech.icon" :style="{ color: tech.color }" class="text-3xl"></i>
                                                </div>
                                                
                                                <!-- Tech Name -->
                                                <h4 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-gray-800 transition-colors duration-300">{{ tech.name }}</h4>
                                                
                                                <!-- Tech Category Badge -->
                                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold transition-all duration-300" :style="{ backgroundColor: tech.color + '20', color: tech.color }">
                                                    <div class="w-2 h-2 rounded-full mr-2" :style="{ backgroundColor: tech.color }"></div>
                                                    <span v-if="getTechCategory(tech.name)">{{ getTechCategory(tech.name) }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Hover Effect Overlay -->
                                            <div class="absolute inset-0 border-2 border-transparent group-hover:border-opacity-50 rounded-2xl transition-all duration-500" :style="{ borderColor: tech.color }"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tech Stack Summary -->
                                    <div class="mt-8 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">Stack Moderno y Escalable</p>
                                                    <p class="text-sm text-gray-600">{{ project.technologies.length }} tecnologías integradas</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-indigo-600">Enterprise Grade</p>
                                                <p class="text-xs text-gray-500">Nivel Corporativo</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar (Right Column) -->
                        <div class="space-y-6">
                            <!-- Project Timeline -->
                            <div v-if="project.start_date || project.end_date" class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">Cronología</h4>
                                </div>
                                <div class="space-y-4">
                                    <div v-if="project.start_date">
                                        <span class="text-sm font-medium text-blue-700">Fecha de Inicio:</span>
                                        <p class="text-blue-900 font-semibold">{{ formatDate(project.start_date) }}</p>
                                    </div>
                                    <div v-if="project.end_date">
                                        <span class="text-sm font-medium text-blue-700">Fecha de Finalización:</span>
                                        <p class="text-blue-900 font-semibold">{{ formatDate(project.end_date) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Status -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">Estado del Proyecto</h4>
                                </div>
                                <div class="text-center">
                                    <span :class="getStatusBadgeClass(project.status)" class="inline-block px-4 py-2 rounded-xl text-sm font-bold mb-3">
                                        {{ getStatusLabel(project.status) }}
                                    </span>
                                    <p class="text-sm text-gray-600">{{ getStatusDescription(project.status) }}</p>
                                </div>
                            </div>
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

const getStatusLabel = (status) => {
    const labels = {
        'draft': 'En Desarrollo',
        'active': 'Activo',
        'completed': 'Completado',
        'archived': 'Archivado',
        'development': 'En Desarrollo',
        'deployed': 'Desplegado',
        'paused': 'Pausado'
    };
    return labels[status] || 'Sin Estado';
};

const formatDate = (date) => {
    if (!date) return 'No especificada';
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusBadgeClass = (status) => {
    const classes = {
        'draft': 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg',
        'active': 'bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-lg',
        'completed': 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg',
        'archived': 'bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-lg',
        'development': 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg',
        'deployed': 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg',
        'paused': 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg'
    };
    return classes[status] || 'bg-gradient-to-r from-gray-300 to-gray-400 text-gray-700 shadow-lg';
};

const getStatusDescription = (status) => {
    const descriptions = {
        'draft': 'Proyecto en fase de desarrollo y planificación',
        'active': 'Proyecto actualmente en producción',
        'completed': 'Proyecto finalizado exitosamente',
        'archived': 'Proyecto archivado para referencia',
        'development': 'Este proyecto está actualmente en desarrollo. Nuestro equipo está trabajando activamente en nuevas funcionalidades y mejoras.',
        'deployed': 'Este proyecto está desplegado y funcionando en producción. Los usuarios pueden acceder y utilizar todas sus funcionalidades.',
        'paused': 'El desarrollo de este proyecto está temporalmente pausado. Puede reanudarse en el futuro según las prioridades del negocio.'
    };
    return descriptions[status] || 'Estado no definido';
};

const getTechCategory = (techName) => {
    const categories = {
        // Frontend
        'Vue.js': 'Frontend',
        'React': 'Frontend',
        'Angular': 'Frontend',
        'JavaScript': 'Frontend',
        'TypeScript': 'Frontend',
        'Tailwind CSS': 'Frontend',
        'CSS': 'Frontend',
        'HTML': 'Frontend',
        
        // Backend
        'Laravel': 'Backend',
        'Node.js': 'Backend',
        'PHP': 'Backend',
        'Python': 'Backend',
        'Java': 'Backend',
        'Express': 'Backend',
        'Symfony': 'Backend',
        
        // Database
        'MySQL': 'Database',
        'PostgreSQL': 'Database',
        'MongoDB': 'Database',
        'Redis': 'Database',
        'SQLite': 'Database',
        
        // Cloud & DevOps
        'AWS': 'Cloud',
        'Docker': 'DevOps',
        'Kubernetes': 'DevOps',
        'Azure': 'Cloud',
        'Google Cloud': 'Cloud',
        
        // AI & ML
        'TensorFlow': 'AI/ML',
        'PyTorch': 'AI/ML',
        'scikit-learn': 'AI/ML'
    };
    return categories[techName] || 'Technology';
};

const openUrl = (url) => {
    if (url) {
        window.open(url, '_blank');
    }
};
</script>
