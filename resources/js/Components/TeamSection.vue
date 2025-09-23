<template>
    <section id="staff" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestro Equipo</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Conoce al equipo de ingeniería y diseño que hace posible nuestras soluciones.</p>
            </div>

            <div v-if="loading" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
            </div>

            <div v-else-if="members.length === 0" class="text-center py-12 text-gray-500">
                No hay miembros del equipo aún.
            </div>

            <div v-else class="overflow-hidden">
                <!-- Contenedor del carrusel serpiente -->
                <div class="relative w-full team-carousel-container" @mouseenter="pauseAnimation" @mouseleave="resumeAnimation">
                    <div 
                        ref="trackRef"
                        class="team-track flex items-center gap-4"
                        :class="{ 'paused': isPaused }"
                        :style="trackStyle"
                    >
                        <div 
                            v-for="(member, index) in duplicatedMembers" 
                            :key="`member-${member.id}-${index}`"
                            class="team-card flex-shrink-0 bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer group border border-gray-100"
                            :style="cardStyle"
                            @click="openModal(member)"
                        >
                            <div class="relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100" :style="imageContainerStyle">
                                <img 
                                    :src="imageUrl(member.image)" 
                                    :alt="member.name" 
                                    class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105 group-hover:brightness-110" 
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-5 bg-white">
                                <div class="text-center space-y-2">
                                    <h4 class="font-bold text-gray-900 text-base leading-tight">{{ member.name }}</h4>
                                    <p class="text-sm text-indigo-600 font-medium">{{ member.position }}</p>
                                    <div class="pt-2 flex justify-center space-x-1">
                                        <div class="w-1 h-1 bg-indigo-300 rounded-full"></div>
                                        <div class="w-1 h-1 bg-indigo-500 rounded-full"></div>
                                        <div class="w-1 h-1 bg-indigo-300 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: staff details - Design Premium Corporativo -->
            <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <!-- Backdrop con blur sofisticado -->
                <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/70 backdrop-blur-md"></div>
                
                <!-- Modal Container -->
                <div class="relative bg-white rounded-3xl shadow-2xl max-w-4xl w-full mx-2 modal-container" style="max-height:90vh;">
                    <!-- Header con gradiente corporativo -->
                    <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 text-white overflow-hidden">
                        <!-- Decorative background pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full transform -translate-x-20 -translate-y-20"></div>
                            <div class="absolute bottom-0 right-0 w-32 h-32 bg-white rounded-full transform translate-x-16 translate-y-16"></div>
                        </div>
                        
                        <div class="relative px-8 py-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 id="modal-title" class="text-2xl font-bold text-white mb-1">
                                        {{ modalMember?.name }}
                                    </h3>
                                    <p class="text-indigo-100 font-medium text-sm">
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse inline-block"></span>
                                            <span>{{ modalMember?.position }}</span>
                                        </span>
                                    </p>
                                    
                                </div>
                                
                                <button 
                                    ref="closeButtonRef"
                                    @click="closeModal" 
                                    class="w-10 h-10 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition-all duration-200 backdrop-blur-sm" 
                                    aria-label="Cerrar modal"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Body con diseño corporativo premium -->
                    <div class="p-8 bg-gradient-to-br from-gray-50 to-white modal-body">
                        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                            <!-- Columna de imagen - más prominente -->
                            <div class="lg:col-span-2">
                                <div class="relative">
                                    <!-- Imagen principal con marco sofisticado -->
                                    <div class="relative group">
                                        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000"></div>
                                        <div class="relative bg-white p-3 rounded-2xl">
                                            <img 
                                                :src="imageUrl(modalMember?.image)" 
                                                :alt="modalMember?.name" 
                                                class="w-full h-80 object-cover rounded-xl shadow-lg" 
                                            />
                                        </div>
                                    </div>
                                    
                                    <!-- Indicadores de estado -->
                                    <div class="absolute top-6 right-6 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                        Activo
                                    </div>
                                </div>
                            </div>

                            <!-- Columna de contenido -->
                            <div class="lg:col-span-3 space-y-6">
                                <!-- Sección Perfil -->
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Perfil Profesional</h4>
                                    </div>
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ modalMember?.bio || 'Profesional altamente capacitado con experiencia en el desarrollo de soluciones innovadoras. Comprometido con la excelencia y el crecimiento continuo en el campo de la tecnología.' }}
                                    </p>
                                </div>

                                <!-- Sección Redes Sociales Premium -->
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Conectar</h4>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- LinkedIn -->
                                        <a 
                                            v-if="modalMember?.linkedin" 
                                            :href="modalMember.linkedin" 
                                            target="_blank" 
                                            class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-200 border border-blue-200"
                                        >
                                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-blue-900">LinkedIn</p>
                                                <p class="text-sm text-blue-600">Ver perfil profesional</p>
                                            </div>
                                            <svg class="w-5 h-5 text-blue-600 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </a>

                                        <!-- GitHub -->
                                        <a 
                                            v-if="modalMember?.github" 
                                            :href="modalMember.github" 
                                            target="_blank" 
                                            class="group flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-xl transition-all duration-200 border border-gray-200"
                                        >
                                            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">GitHub</p>
                                                <p class="text-sm text-gray-600">Ver repositorios</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-600 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </a>

                                        <!-- Email -->
                                        <a 
                                            v-if="modalMember?.email" 
                                            :href="`mailto:${modalMember.email}`" 
                                            class="group flex items-center p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 rounded-xl transition-all duration-200 border border-emerald-200 sm:col-span-2"
                                        >
                                            <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-emerald-900">Email</p>
                                                <p class="text-sm text-emerald-600">Enviar mensaje directo</p>
                                            </div>
                                            <svg class="w-5 h-5 text-emerald-600 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer mejorado -->
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Perfil profesional verificado
                        </div>
                        <div class="flex items-center gap-3">
                            <button 
                                @click="closeModal" 
                                class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Cerrar
                            </button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount, nextTick, watch } from 'vue'
import { useStaff } from '@/composables/useStaff'

const { staff, loading, error, fetchStaff, getActiveStaff } = useStaff()

// Estado reactivo
const members = ref([])
const isPaused = ref(false)
const trackRef = ref(null)
const closeButtonRef = ref(null)

// Configuración del carrusel
const animationDuration = ref(30) // segundos para completar un ciclo
const cardWidth = ref(240) // ancho de cada tarjeta en px - más ancho para look corporativo
const gap = ref(20) // espacio entre tarjetas en px - más espacio para respirar

// Modal state
const modalOpen = ref(false)
const modalMember = ref(null)

// Actualizar configuración responsive
const updateResponsiveConfig = () => {
    const width = window.innerWidth
    if (width < 640) {
        cardWidth.value = 180
        gap.value = 16
        animationDuration.value = 25
    } else if (width < 1024) {
        cardWidth.value = 200
        gap.value = 18
        animationDuration.value = 27
    } else {
        // Para pantallas grandes: tarjetas más profesionales y espaciadas
        cardWidth.value = 240
        gap.value = 20
        animationDuration.value = 30
    }
}

// Duplicar miembros para efecto infinito
const duplicatedMembers = computed(() => {
    if (members.value.length === 0) return []
    
    // Crear 3 copias para asegurar movimiento infinito suave
    const copies = 3
    const result = []
    
    for (let i = 0; i < copies; i++) {
        members.value.forEach((member, index) => {
            result.push({
                ...member,
                uniqueId: `${member.id}-copy-${i}-${index}`
            })
        })
    }
    
    return result
})

// Estilos computados
const trackStyle = computed(() => {
    const totalWidth = members.value.length * (cardWidth.value + gap.value)
    
    return {
        '--animation-duration': `${animationDuration.value}s`,
        '--translate-distance': `${totalWidth}px`,
        width: `${duplicatedMembers.value.length * (cardWidth.value + gap.value)}px`
    }
})

const cardStyle = computed(() => ({
    minWidth: `${cardWidth.value}px`,
    width: `${cardWidth.value}px`
}))

// Estilo del contenedor de imagen responsive
const imageContainerStyle = computed(() => {
    const width = window.innerWidth
    let height = '240px' // altura por defecto más profesional
    
    if (width < 640) {
        height = '200px'
    } else if (width < 1024) {
        height = '220px'
    }
    
    return {
        height: height
    }
})

// Controles de animación
const pauseAnimation = () => {
    isPaused.value = true
}

const resumeAnimation = () => {
    isPaused.value = false
}

// Helper: normalize image URLs
const imageUrl = (val) => {
    if (!val) return '/images/staff/placeholder.jpg'
    if (val.startsWith('http://') || val.startsWith('https://')) return val
    if (val.startsWith('/')) return val
    return '/storage/' + val.replace(/^\/+/, '')
}

// Modal controls
const openModal = (member) => { 
    modalMember.value = member
    modalOpen.value = true
    nextTick(() => {
        if (closeButtonRef.value) {
            closeButtonRef.value.focus()
        }
    })
}

const closeModal = () => { 
    modalOpen.value = false
    modalMember.value = null
}

// Evitar scroll del body cuando el modal está abierto
watch(modalOpen, (val) => {
    if (val) {
        document.documentElement.style.overflow = 'hidden'
        document.body.style.overflow = 'hidden'
    } else {
        document.documentElement.style.overflow = ''
        document.body.style.overflow = ''
    }
})

// Keyboard handler
const onKeyDown = (e) => {
    if (e.key === 'Escape' && modalOpen.value) {
        closeModal()
    }
}

// Cargar miembros del equipo
const loadMembers = async () => {
    try {
        if (fetchStaff) {
            await fetchStaff()
            members.value = staff.value || []
        } else if (getActiveStaff) {
            members.value = await getActiveStaff()
        }
    } catch (error) {
        console.error('Error loading staff:', error)
        members.value = []
    }
}

// Lifecycle hooks
onMounted(async () => {
    await loadMembers()
    updateResponsiveConfig()
    
    // Event listeners
    window.addEventListener('resize', updateResponsiveConfig)
    window.addEventListener('keydown', onKeyDown)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateResponsiveConfig)
    window.removeEventListener('keydown', onKeyDown)
})
</script>

<style scoped>
.team-carousel-container {
    overflow: hidden;
    position: relative;
}

.team-track {
    animation: slideLeft var(--animation-duration, 30s) linear infinite;
    will-change: transform;
}

.team-track.paused {
    animation-play-state: paused;
}

.team-card {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    transform-style: preserve-3d;
    backface-visibility: hidden;
}

.team-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 10px 25px rgba(0, 0, 0, 0.1);
}

.team-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(168, 85, 247, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
    border-radius: inherit;
}

.team-card:hover::before {
    opacity: 1;
}

.team-card > * {
    position: relative;
    z-index: 2;
}

@keyframes slideLeft {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-1 * var(--translate-distance, 300px)));
    }
}

/* Estilos responsive mejorados */
@media (max-width: 640px) {
    .team-card {
        border-radius: 1rem;
    }
    
    .team-card .p-5 {
        padding: 1rem;
    }
    
    .team-card .text-base {
        font-size: 0.875rem;
    }
    
    .team-card .text-sm {
        font-size: 0.75rem;
    }
}

@media (min-width: 641px) and (max-width: 1024px) {
    .team-card {
        border-radius: 1.25rem;
    }
}

/* Para pantallas grandes - look más corporativo */
@media (min-width: 1025px) {
    .team-card {
        border-radius: 1.5rem;
    }
    
    .team-card .p-5 {
        padding: 1.5rem;
    }
}

/* Gradientes laterales más sofisticados */
.team-carousel-container::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 60px;
    background: linear-gradient(to right, 
        rgba(255, 255, 255, 1) 0%, 
        rgba(255, 255, 255, 0.9) 30%,
        rgba(255, 255, 255, 0.5) 70%,
        rgba(255, 255, 255, 0) 100%);
    z-index: 3;
    pointer-events: none;
}

.team-carousel-container::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 60px;
    background: linear-gradient(to left, 
        rgba(255, 255, 255, 1) 0%, 
        rgba(255, 255, 255, 0.9) 30%,
        rgba(255, 255, 255, 0.5) 70%,
        rgba(255, 255, 255, 0) 100%);
    z-index: 3;
    pointer-events: none;
}

/* Modal styles mejorados */
.fixed.inset-0 {
    backdrop-filter: blur(8px);
    background: rgba(0, 0, 0, 0.7);
}

/* Hacer que el modal tenga layout en columna y el body sea scrollable */
.modal-container {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow: hidden;
}

.modal-body {
    overflow: auto;
    /* Dejar espacio para header (aprox 120px) y footer (aprox 72px) */
    max-height: calc(90vh - 192px);
}

/* Efectos profesionales adicionales */
.team-card .group {
    position: relative;
    overflow: hidden;
}

.team-card .group::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #6366f1, #8b5cf6, #06b6d4, #10b981);
    opacity: 0;
    z-index: -1;
    transition: opacity 0.3s ease;
    border-radius: inherit;
}

.team-card:hover .group::after {
    opacity: 0.1;
}

/* Hover effects para imágenes del equipo */
.team-card img {
    transition: transform 0.5s ease;
}

.team-card:hover img {
    transform: scale(1.05);
}

/* Truncate text */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>