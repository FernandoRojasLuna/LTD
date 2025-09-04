<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center px-4" @click.self="$emit('close')">
        <!-- Backdrop with blur effect -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Modal Container -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[95vh] overflow-hidden">
            <!-- Enhanced Header with Project Image -->
            <div class="relative h-64 text-white overflow-hidden">
                <!-- Project Image Background -->
                <div 
                    v-if="project.image"
                    class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                    :style="{ backgroundImage: `url(${project.image})` }"
                ></div>
                
                <!-- Fallback gradient background if no image -->
                <div 
                    v-else
                    class="absolute inset-0 bg-gradient-to-br from-slate-900 via-gray-900 to-slate-800"
                ></div>
                
                <!-- Dark overlay for better text readability -->
                <div class="absolute inset-0 bg-black/40 backdrop-blur-[0.5px]"></div>
                
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
                <div class="p-4 bg-gray-50">
                    <div class="space-y-8">
                        <!-- Main Content -->
                        <div class="space-y-8">
                            <!-- Project Description - PREMIUM DESIGN -->
                            <div class="bg-gradient-to-br from-white via-slate-50 to-gray-50 rounded-3xl p-8 shadow-xl border border-slate-200 relative overflow-hidden">
                                <!-- Decorative Elements -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-indigo-100 to-transparent rounded-bl-full opacity-60"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-100 to-transparent rounded-tr-full opacity-40"></div>
                                
                                <div class="relative">
                                    <div class="flex items-start mb-6">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg flex-shrink-0">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
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
                                                <div 
                                                    v-html="formatDescription(project.full_description || project.description)"
                                                    class="text-gray-800 text-lg leading-relaxed font-medium mb-4 space-y-3"
                                                ></div>
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
                                    
                                    <!-- Grid normal para 3 o menos tecnologías -->
                                    <div v-if="project.technologies.length <= 3" class="grid grid-cols-2 lg:grid-cols-3 gap-6">
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
                                                    <!-- Icon fallback if tech.icon doesn't exist or load -->
                                                    <div v-if="!tech.icon && !getTechIcon(tech.name)" class="w-8 h-8 rounded-lg flex items-center justify-center text-xl font-bold" :style="{ backgroundColor: tech.color, color: 'white' }">
                                                        {{ tech.name.charAt(0) }}
                                                    </div>
                                                    <!-- Font icon if available (priority to database icon) -->
                                                    <i v-else :class="tech.icon || getTechIcon(tech.name)" :style="{ color: tech.color }" class="text-3xl"></i>
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

                                    <!-- Carrusel para más de 3 tecnologías -->
                                    <div v-else class="relative">
                                        <!-- Contenedor principal con botones laterales -->
                                        <div class="relative flex items-center">
                                            <!-- Botón anterior (izquierda) -->
                                            <button 
                                                @click="previousSlide"
                                                class="absolute left-0 z-10 flex items-center justify-center w-12 h-12 bg-white border-2 border-gray-200 rounded-full hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-300 focus:outline-none group shadow-lg"
                                                style="transform: translateX(-50%);"
                                            >
                                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>

                                            <!-- Área de las tarjetas -->
                                            <div class="flex-1 px-8">
                                                <div class="grid grid-cols-3 gap-6">
                                                    <div 
                                                        v-for="tech in getVisibleTechs" 
                                                        :key="`${tech.id}-${currentIndex}`"
                                                        class="group relative bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 cursor-pointer overflow-hidden"
                                                    >
                                                        <!-- Tech Card Background Gradient -->
                                                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" :style="{ background: `linear-gradient(135deg, ${tech.color}15, ${tech.color}25)` }"></div>
                                                        
                                                        <!-- Tech Logo/Icon -->
                                                        <div class="relative text-center">
                                                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:scale-110" :style="{ backgroundColor: tech.color + '20' }">
                                                                <!-- Icon fallback if tech.icon doesn't exist or load -->
                                                                <div v-if="!tech.icon && !getTechIcon(tech.name)" class="w-8 h-8 rounded-lg flex items-center justify-center text-xl font-bold" :style="{ backgroundColor: tech.color, color: 'white' }">
                                                                    {{ tech.name.charAt(0) }}
                                                                </div>
                                                                <!-- Font icon if available (priority to database icon) -->
                                                                <i v-else :class="tech.icon || getTechIcon(tech.name)" :style="{ color: tech.color }" class="text-3xl"></i>
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
                                            </div>

                                            <!-- Botón siguiente (derecha) -->
                                            <button 
                                                @click="nextSlide"
                                                class="absolute right-0 z-10 flex items-center justify-center w-12 h-12 bg-white border-2 border-gray-200 rounded-full hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-300 focus:outline-none group shadow-lg"
                                                style="transform: translateX(50%);"
                                            >
                                                <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Navegación inferior limpia -->
                                        <div class="mt-8">
                                            <!-- Solo puntitos de navegación, más elegantes -->
                                            <div class="flex justify-center space-x-3">
                                                <button 
                                                    v-for="(tech, index) in project.technologies" 
                                                    :key="index"
                                                    @click="goToSlide(index)"
                                                    class="relative w-2.5 h-2.5 rounded-full transition-all duration-300 hover:scale-150 focus:outline-none group"
                                                    :class="index === currentIndex ? 'bg-indigo-500' : 'bg-gray-300 hover:bg-gray-400'"
                                                >
                                                    <!-- Tooltip elegante con el nombre de la tecnología -->
                                                    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-20">
                                                        {{ tech.name }}
                                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-2 border-r-2 border-t-2 border-transparent border-t-gray-900"></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- CRONOLOGIA - TIMELINE VISUAL INTERACTIVO -->
                            <div class="bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 rounded-3xl p-8 shadow-xl border border-indigo-200 relative overflow-hidden">
                                <!-- Premium Background Pattern -->
                                <div class="absolute inset-0 opacity-[0.03]">
                                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <pattern id="timeline-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                                <circle cx="20" cy="20" r="1" fill="currentColor"/>
                                                <circle cx="0" cy="0" r="0.5" fill="currentColor"/>
                                                <circle cx="40" cy="40" r="0.5" fill="currentColor"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#timeline-pattern)"/>
                                    </svg>
                                </div>
                                
                                <div class="relative">
                                    <!-- Header Section -->
                                    <div class="flex items-center justify-between mb-10">
                                        <div class="flex items-center">
                                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl flex items-center justify-center mr-6 shadow-2xl">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-3xl font-bold bg-gradient-to-r from-indigo-900 to-purple-900 bg-clip-text text-transparent mb-1">
                                                    Cronología del Proyecto
                                                </h3>
                                                <p class="text-indigo-700 font-semibold">Roadmap ejecutivo y seguimiento de hitos</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Project Progress Summary -->
                                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-indigo-100">
                                            <div class="text-center">
                                                <div class="text-3xl font-bold text-indigo-700 mb-1" :class="getProjectProgressData(project).textColor">
                                                    {{ getProjectProgressData(project).percentage }}%
                                                </div>
                                                <div class="text-sm text-gray-600 font-medium">Progreso Total</div>
                                                <div class="w-20 bg-gray-200 rounded-full h-2 mt-3 mx-auto">
                                                    <div 
                                                        class="h-2 rounded-full transition-all duration-1000 ease-out"
                                                        :class="getProjectProgressData(project).barColor"
                                                        :style="{ width: getProjectProgressData(project).percentage + '%' }"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Timeline Container -->
                                    <div class="relative">
                                        <!-- Main Timeline Line -->
                                        <div class="absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-gray-200 via-indigo-200 to-purple-200 rounded-full transform -translate-y-1/2"></div>
                                        
                                        <!-- Progress Line (Dynamic) -->
                                        <div 
                                            class="absolute top-1/2 left-0 h-1 bg-gradient-to-r from-indigo-500 via-blue-500 to-purple-600 rounded-full transform -translate-y-1/2 transition-all duration-2000 ease-out shadow-lg"
                                            :style="{ width: getProjectProgressData(project).percentage + '%' }"
                                        ></div>

                                        <!-- Timeline Milestones -->
                                        <div class="relative flex justify-between items-center pt-8 pb-8">
                                            
                                            <!-- Milestone 1: Inicio del Proyecto -->
                                            <div class="group flex flex-col items-center cursor-pointer transform hover:scale-110 transition-all duration-300">
                                                <div class="relative">
                                                    <!-- Milestone Circle -->
                                                    <div :class="getMilestoneStatus(project, 'start').circleClass" class="w-16 h-16 rounded-full flex items-center justify-center shadow-xl border-4 border-white relative overflow-hidden">
                                                        <!-- Animated background for completed milestones -->
                                                        <div v-if="getMilestoneStatus(project, 'start').completed" class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent animate-pulse"></div>
                                                        <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                        </svg>
                                                    </div>
                                                    
                                                    <!-- Pulse effect for active milestone -->
                                                    <div v-if="getMilestoneStatus(project, 'start').active" class="absolute inset-0 w-16 h-16 rounded-full bg-emerald-400 animate-ping opacity-20"></div>
                                                </div>
                                                
                                                <!-- Milestone Info -->
                                                <div class="mt-4 text-center group-hover:transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="font-bold text-gray-900 text-sm mb-1">Inicio</div>
                                                    <div class="text-xs text-gray-600 font-medium" v-if="project.start_date">
                                                        {{ formatDate(project.start_date) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500" v-else>Por definir</div>
                                                </div>
                                                
                                                <!-- Tooltip -->
                                                <div class="invisible group-hover:visible absolute -top-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl z-10">
                                                    Inicio del proyecto y planificación
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Milestone 2: Desarrollo -->
                                            <div class="group flex flex-col items-center cursor-pointer transform hover:scale-110 transition-all duration-300">
                                                <div class="relative">
                                                    <div :class="getMilestoneStatus(project, 'development').circleClass" class="w-16 h-16 rounded-full flex items-center justify-center shadow-xl border-4 border-white relative overflow-hidden">
                                                        <div v-if="getMilestoneStatus(project, 'development').completed" class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent animate-pulse"></div>
                                                        <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                                        </svg>
                                                    </div>
                                                    <div v-if="getMilestoneStatus(project, 'development').active" class="absolute inset-0 w-16 h-16 rounded-full bg-blue-400 animate-ping opacity-20"></div>
                                                </div>
                                                
                                                <div class="mt-4 text-center group-hover:transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="font-bold text-gray-900 text-sm mb-1">Desarrollo</div>
                                                    <div class="text-xs text-gray-600 font-medium">En progreso</div>
                                                </div>
                                                
                                                <div class="invisible group-hover:visible absolute -top-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl z-10">
                                                    Implementación y desarrollo activo
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Milestone 3: Testing -->
                                            <div class="group flex flex-col items-center cursor-pointer transform hover:scale-110 transition-all duration-300">
                                                <div class="relative">
                                                    <div :class="getMilestoneStatus(project, 'testing').circleClass" class="w-16 h-16 rounded-full flex items-center justify-center shadow-xl border-4 border-white relative overflow-hidden">
                                                        <div v-if="getMilestoneStatus(project, 'testing').completed" class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent animate-pulse"></div>
                                                        <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div v-if="getMilestoneStatus(project, 'testing').active" class="absolute inset-0 w-16 h-16 rounded-full bg-yellow-400 animate-ping opacity-20"></div>
                                                </div>
                                                
                                                <div class="mt-4 text-center group-hover:transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="font-bold text-gray-900 text-sm mb-1">Testing</div>
                                                    <div class="text-xs text-gray-600 font-medium">Próximamente</div>
                                                </div>
                                                
                                                <div class="invisible group-hover:visible absolute -top-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl z-10">
                                                    Pruebas y control de calidad
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>

                                            <!-- Milestone 4: Deployment -->
                                            <div class="group flex flex-col items-center cursor-pointer transform hover:scale-110 transition-all duration-300">
                                                <div class="relative">
                                                    <div :class="getMilestoneStatus(project, 'deployment').circleClass" class="w-16 h-16 rounded-full flex items-center justify-center shadow-xl border-4 border-white relative overflow-hidden">
                                                        <div v-if="getMilestoneStatus(project, 'deployment').completed" class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent animate-pulse"></div>
                                                        <svg class="w-7 h-7 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                        </svg>
                                                    </div>
                                                    <div v-if="getMilestoneStatus(project, 'deployment').active" class="absolute inset-0 w-16 h-16 rounded-full bg-purple-400 animate-ping opacity-20"></div>
                                                </div>
                                                
                                                <div class="mt-4 text-center group-hover:transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="font-bold text-gray-900 text-sm mb-1">Despliegue</div>
                                                    <div class="text-xs text-gray-600 font-medium" v-if="project.end_date">
                                                        {{ formatDate(project.end_date) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500" v-else>Planificado</div>
                                                </div>
                                                
                                                <div class="invisible group-hover:visible absolute -top-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl z-10">
                                                    Lanzamiento y puesta en producción
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Timeline Stats Cards -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                                            <!-- Days Elapsed -->
                                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-indigo-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-2xl font-bold text-gray-900">{{ getProjectDays(project).elapsed }}</div>
                                                        <div class="text-sm text-gray-600 font-medium">Días Transcurridos</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Days Remaining -->
                                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-indigo-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-2xl font-bold text-gray-900">{{ getProjectDays(project).remaining }}</div>
                                                        <div class="text-sm text-gray-600 font-medium">Días Restantes</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Duration -->
                                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-indigo-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-2xl font-bold text-gray-900">{{ getProjectDays(project).total }}</div>
                                                        <div class="text-sm text-gray-600 font-medium">Duración Total</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
import { computed, ref, onMounted, onUnmounted, watch } from 'vue'

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

// Variables del carrusel
const currentIndex = ref(0)
const carouselInterval = ref(null)
const isAutoPlaying = ref(true)

// Computed para obtener las 3 tecnologías visibles
const getVisibleTechs = computed(() => {
    if (!props.project.technologies || props.project.technologies.length <= 3) {
        return props.project.technologies || []
    }
    
    const techs = props.project.technologies
    const visible = []
    
    for (let i = 0; i < 3; i++) {
        const index = (currentIndex.value + i) % techs.length
        visible.push(techs[index])
    }
    
    return visible
})

// Funciones del carrusel
const startCarousel = () => {
    if (props.project.technologies && props.project.technologies.length > 3 && isAutoPlaying.value) {
        carouselInterval.value = setInterval(() => {
            rotateCarousel()
        }, 3000) // Cambia cada 3 segundos
    }
}

const stopCarousel = () => {
    if (carouselInterval.value) {
        clearInterval(carouselInterval.value)
        carouselInterval.value = null
    }
}

const rotateCarousel = () => {
    if (props.project.technologies && props.project.technologies.length > 3) {
        currentIndex.value = (currentIndex.value + 1) % props.project.technologies.length
    }
}

// Nuevas funciones de navegación manual
const goToSlide = (index) => {
    currentIndex.value = index
    resetAutoPlay()
}

const nextSlide = () => {
    if (props.project.technologies && props.project.technologies.length > 3) {
        currentIndex.value = (currentIndex.value + 1) % props.project.technologies.length
        resetAutoPlay()
    }
}

const previousSlide = () => {
    if (props.project.technologies && props.project.technologies.length > 3) {
        currentIndex.value = currentIndex.value === 0 
            ? props.project.technologies.length - 1 
            : currentIndex.value - 1
        resetAutoPlay()
    }
}

const resetAutoPlay = () => {
    stopCarousel()
    isAutoPlaying.value = false
    
    // Reinicia el auto-play después de 4 segundos de inactividad (más tiempo para evitar aceleración)
    setTimeout(() => {
        if (!isAutoPlaying.value) {
            isAutoPlaying.value = true
            startCarousel()
        }
    }, 4000)
}

const toggleAutoPlay = () => {
    isAutoPlaying.value = !isAutoPlaying.value
    if (isAutoPlaying.value) {
        startCarousel()
    } else {
        stopCarousel()
    }
}

// Watchers y lifecycle
watch(() => props.show, (newValue) => {
    if (newValue) {
        currentIndex.value = 0
        isAutoPlaying.value = true
        startCarousel()
    } else {
        stopCarousel()
    }
})

onMounted(() => {
    if (props.show) {
        startCarousel()
    }
})

onUnmounted(() => {
    stopCarousel()
})

// Helper function to get tech icon classes if not defined in database
const getTechIcon = (techName) => {
    const techIcons = {
        'Vue.js': 'devicon-vuejs-plain',
        'React': 'devicon-react-original',
        'Angular': 'devicon-angularjs-plain',
        'JavaScript': 'devicon-javascript-plain',
        'TypeScript': 'devicon-typescript-plain',
        'Laravel': 'devicon-laravel-plain',
        'Node.js': 'devicon-nodejs-plain',
        'PHP': 'devicon-php-plain',
        'Python': 'devicon-python-plain',
        'MySQL': 'devicon-mysql-plain',
        'PostgreSQL': 'devicon-postgresql-plain',
        'MongoDB': 'devicon-mongodb-plain',
        'Docker': 'devicon-docker-plain',
        'AWS': 'devicon-amazonwebservices-original',
        'Git': 'devicon-git-plain',
        'CSS': 'devicon-css3-plain',
        'HTML': 'devicon-html5-plain',
        'Tailwind CSS': 'devicon-tailwindcss-plain'
    };
    return techIcons[techName] || '';
};

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

const formatDescription = (description) => {
    if (!description) return '';
    
    return description
        // Replace bullet points (- ) with HTML list items
        .replace(/^- (.+)$/gm, '<li class="flex items-start"><span class="w-2 h-2 bg-blue-500 rounded-full mt-3 mr-3 flex-shrink-0"></span><span>$1</span></li>')
        // Wrap consecutive list items in ul tags
        .replace(/(<li[^>]*>.*?<\/li>)/gs, (match) => {
            return `<ul class="space-y-2 my-4">${match}</ul>`;
        })
        // Replace line breaks with proper spacing
        .replace(/\n\n/g, '</p><p class="mb-4">')
        .replace(/\n/g, '<br>')
        // Wrap in paragraph if not starting with list
        .replace(/^(?!<ul)/, '<p class="mb-4">')
        // Close final paragraph if needed
        .replace(/(?!<\/ul>)$/, '</p>')
        // Clean up empty paragraphs
        .replace(/<p[^>]*><\/p>/g, '');
};

// Timeline Functions
const getProjectProgressData = (project) => {
    if (!project.start_date) {
        return {
            percentage: 10,
            textColor: 'text-gray-500',
            barColor: 'bg-gray-400'
        };
    }

    const startDate = new Date(project.start_date);
    const endDate = project.end_date ? new Date(project.end_date) : new Date(startDate.getTime() + (365 * 24 * 60 * 60 * 1000)); // Default 1 year if no end date
    const currentDate = new Date();

    const totalDuration = endDate.getTime() - startDate.getTime();
    const elapsed = Math.max(0, currentDate.getTime() - startDate.getTime());
    const percentage = Math.min(100, Math.max(0, (elapsed / totalDuration) * 100));

    let textColor, barColor;
    if (percentage >= 100) {
        textColor = 'text-emerald-700';
        barColor = 'bg-gradient-to-r from-emerald-500 to-green-600';
    } else if (percentage >= 75) {
        textColor = 'text-blue-700';
        barColor = 'bg-gradient-to-r from-blue-500 to-indigo-600';
    } else if (percentage >= 50) {
        textColor = 'text-indigo-700';
        barColor = 'bg-gradient-to-r from-indigo-500 to-purple-600';
    } else if (percentage >= 25) {
        textColor = 'text-purple-700';
        barColor = 'bg-gradient-to-r from-purple-500 to-pink-600';
    } else {
        textColor = 'text-orange-700';
        barColor = 'bg-gradient-to-r from-orange-500 to-red-600';
    }

    return {
        percentage: Math.round(percentage),
        textColor,
        barColor
    };
};

const getMilestoneStatus = (project, milestone) => {
    const progress = getProjectProgressData(project).percentage;

    const milestoneThresholds = {
        start: 0,
        development: 25,
        testing: 75,
        deployment: 100
    };

    const threshold = milestoneThresholds[milestone] || 0;
    const completed = progress > threshold;
    const active = progress >= threshold && progress <= threshold + 25;

    let circleClass;
    if (completed) {
        circleClass = 'bg-gradient-to-br from-emerald-500 to-green-600';
    } else if (active) {
        circleClass = 'bg-gradient-to-br from-blue-500 to-indigo-600';
    } else {
        circleClass = 'bg-gradient-to-br from-gray-400 to-gray-500';
    }

    return {
        completed,
        active,
        circleClass
    };
};

const getProjectDays = (project) => {
    if (!project.start_date) {
        return {
            elapsed: 0,
            remaining: 'N/A',
            total: 'N/A'
        };
    }

    const startDate = new Date(project.start_date);
    const endDate = project.end_date ? new Date(project.end_date) : new Date(startDate.getTime() + (365 * 24 * 60 * 60 * 1000));
    const currentDate = new Date();

    const totalDays = Math.ceil((endDate.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24));
    const elapsedDays = Math.max(0, Math.ceil((currentDate.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24)));
    const remainingDays = Math.max(0, totalDays - elapsedDays);

    return {
        elapsed: elapsedDays,
        remaining: remainingDays > 0 ? remainingDays : 'Completado',
        total: totalDays
    };
};
</script>
