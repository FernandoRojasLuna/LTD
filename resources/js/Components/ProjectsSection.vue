<template>
    <section id="projects" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nuestros Proyectos</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Cada proyecto es una apuesta por la innovación, el desarrollo sostenible y la transformación digital real.</p>
            </div>

            <!-- Tabs -->
            <div class="flex justify-center mb-8">
                <div class="bg-white rounded-lg p-1 shadow-sm">
                    <button @click="activeTab = 'all'" :class="['px-6 py-2 rounded-md text-sm font-medium transition-all duration-200', activeTab === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50']">Todos</button>
                    <button @click="activeTab = 'development'" :class="['px-6 py-2 rounded-md text-sm font-medium transition-all duration-200', activeTab === 'development' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50']">En Desarrollo</button>
                    <button @click="activeTab = 'deployed'" :class="['px-6 py-2 rounded-md text-sm font-medium transition-all duration-200', activeTab === 'deployed' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50']">Desplegados</button>
                </div>
            </div>

            <!-- Projects: mobile grid, desktop carousel -->
            <div v-if="filteredProjects.length > 0">
                <!-- Mobile: carousel 1-up with swipe -->
                <div class="block lg:hidden">
                    <div class="relative" ref="mobileCarousel" @touchstart.passive="onMobileTouchStart" @touchmove.passive="onMobileTouchMove" @touchend="onMobileTouchEnd" @mouseenter="stopMobileAutoplay" @mouseleave="startMobileAutoplay">
                        <div class="overflow-hidden">
                            <div class="flex transition-transform duration-500" :style="{ transform: `translateX(-${mobileOffsetPercent.value}%)` }">
                                <div v-for="(proj, idx) in mobileOrderedProjects" :key="`mproj-${proj.id}-${idx}`" class="min-w-full px-3">
                                    <div class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer" @click="showProjectDetails(proj)">
                                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                                            <img :src="proj.image" :alt="proj.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                                <div class="text-center text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 mb-3 mx-auto w-fit">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </div>
                                                    <p class="text-sm font-medium">Ver detalles</p>
                                                </div>
                                            </div>
                                            <div class="absolute top-3 left-3 z-10"><span :class="['px-3 py-1 text-xs font-medium rounded-full', getStatusBadgeClass(proj.status)]">{{ getStatusLabel(proj.status) }}</span></div>
                                            <div v-if="proj.is_featured" class="absolute top-3 right-3 z-10"><span class="bg-yellow-500 text-white px-2 py-1 text-xs font-medium rounded-full">⭐ Destacado</span></div>
                                        </div>
                                        <div class="p-6">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-300">{{ proj.name }}</h3>
                                            <p class="text-gray-600 mb-4 line-clamp-3">{{ proj.description }}</p>
                                            <div class="mb-4">
                                                <div class="flex flex-wrap gap-2">
                                                    <span v-for="tech in proj.technologies.slice(0,4)" :key="tech.id" :style="{ backgroundColor: tech.color + '20', color: tech.color }" class="px-2 py-1 text-xs font-medium rounded-full flex items-center"><i :class="tech.icon" class="mr-1"></i>{{ tech.name }}</span>
                                                    <span v-if="proj.technologies.length > 4" class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">+{{ proj.technologies.length - 4 }}</span>
                                                </div>
                                            </div>
                                            <div v-if="proj.features && proj.features.length > 0" class="mb-4"><h4 class="text-sm font-medium text-gray-700 mb-2">Características:</h4><ul class="text-sm text-gray-600 space-y-1"><li v-for="feature in proj.features.slice(0, 3)" :key="feature" class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>{{ feature }}</li></ul></div>
                                            <div v-if="proj.url || proj.repository" class="flex space-x-3 mt-4"><a v-if="proj.url" :href="proj.url" target="_blank" @click.stop class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200">Ver</a><a v-if="proj.repository" :href="proj.repository" target="_blank" @click.stop class="flex-1 bg-gray-800 hover:bg-gray-900 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200">Código</a></div>
                                            <div class="mt-4 text-center"><p class="text-xs text-gray-400 group-hover:text-indigo-500 transition-colors duration-300">Click para ver detalles completos</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- mobile controls -->
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <button class="bg-white shadow-lg text-gray-700 rounded-full p-2" @click="mobilePrev">‹</button>
                        </div>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <button class="bg-white shadow-lg text-gray-700 rounded-full p-2" @click="mobileNext">›</button>
                        </div>
                        <!-- pagination dots -->
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                            <button v-for="(p, i) in filteredProjects" :key="`dot-${i}`" class="w-2 h-2 rounded-full" :class="i === mobileIndex ? 'bg-indigo-600' : 'bg-gray-300'" @click="setMobileIndex(i)"></button>
                        </div>
                    </div>
                </div>

                <!-- Desktop: 3-up infinite carousel -->
                <div class="hidden lg:block">
                    <div class="relative" ref="carousel" @mouseenter="stopAutoplay" @mouseleave="startAutoplay" @touchstart.passive="onTouchStart" @touchmove.passive="onTouchMove" @touchend="onTouchEnd">
                        <div class="flex justify-center items-stretch mx-auto w-full xl:max-w-6xl">
                            <div v-for="(p, i) in visibleProjects" :key="`proj-${p.id}-${i}`" class="w-1/3 px-3">
                                <!-- keep card markup unchanged visually -->
                                <div class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer" @click="showProjectDetails(p)">
                                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                                        <img :src="p.image" :alt="p.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                            <div class="text-center text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 mb-3 mx-auto w-fit">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium">Ver detalles</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 left-3 z-10"><span :class="['px-3 py-1 text-xs font-medium rounded-full', getStatusBadgeClass(p.status)]">{{ getStatusLabel(p.status) }}</span></div>
                                        <div v-if="p.is_featured" class="absolute top-3 right-3 z-10"><span class="bg-yellow-500 text-white px-2 py-1 text-xs font-medium rounded-full">⭐ Destacado</span></div>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-300">{{ p.name }}</h3>
                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ p.description }}</p>
                                        <div class="mb-4">
                                            <div class="flex flex-wrap gap-2">
                                                <span v-for="tech in p.technologies.slice(0,4)" :key="tech.id" :style="{ backgroundColor: tech.color + '20', color: tech.color }" class="px-2 py-1 text-xs font-medium rounded-full flex items-center"><i :class="tech.icon" class="mr-1"></i>{{ tech.name }}</span>
                                                <span v-if="p.technologies.length > 4" class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">+{{ p.technologies.length - 4 }}</span>
                                            </div>
                                        </div>
                                        <div v-if="p.features && p.features.length > 0" class="mb-4"><h4 class="text-sm font-medium text-gray-700 mb-2">Características:</h4><ul class="text-sm text-gray-600 space-y-1"><li v-for="feature in p.features.slice(0, 3)" :key="feature" class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>{{ feature }}</li></ul></div>
                                        <div v-if="p.url || p.repository" class="flex space-x-3 mt-4"><a v-if="p.url" :href="p.url" target="_blank" @click.stop class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200">Ver Proyecto</a><a v-if="p.repository" :href="p.repository" target="_blank" @click.stop class="flex-1 bg-gray-800 hover:bg-gray-900 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition duration-200">Código</a></div>
                                        <div class="mt-4 text-center"><p class="text-xs text-gray-400 group-hover:text-indigo-500 transition-colors duration-300">Click para ver detalles completos</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="prev" aria-label="Anterior">‹</button>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="next" aria-label="Siguiente">›</button>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay proyectos</h3>
                <p class="text-gray-600">No se encontraron proyectos para esta categoría.</p>
            </div>
        </div>

    <!-- Modal (render only when project selected) -->
    <ProjectDetailsModal v-if="showModal && selectedProject" :show="showModal" :project="selectedProject" @close="closeModal" />
    </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { watch } from 'vue'
import axios from 'axios'
import ProjectDetailsModal from '@/Components/ProjectDetailsModal.vue'

// Data
const projects = ref([])
const activeTab = ref('all')
const showModal = ref(false)
const selectedProject = ref(null)

// Carousel state
const currentIndex = ref(0)
const autoplayTimer = ref(null)
const autoplayMs = 3500

// Mobile carousel state (separate visual index so mobile shows one at a time)
const mobileIndex = ref(0)
const mobileTimer = ref(null)
const mobileAutoplayMs = 3500

const mobileOffsetPercent = ref(0)


// Fetch
const fetchProjects = async () => {
    try {
        const res = await axios.get('/api/public/projects')
        projects.value = res.data || []
    } catch (e) {
        console.error('Error fetching projects:', e)
        projects.value = []
    }
}

const filteredProjects = computed(() => {
    if (activeTab.value === 'all') return projects.value
    return projects.value.filter(p => p.status === activeTab.value)
})

// Visible 3-up with modular indexing
const visibleProjects = computed(() => {
    const arr = filteredProjects.value || []
    const n = arr.length
    if (n === 0) return []
    // Ensure currentIndex within bounds
    const base = ((currentIndex.value % n) + n) % n
    const res = []
    for (let i = 0; i < Math.min(3, Math.max(1, n)); i++) {
        res.push(arr[(base + i) % n])
    }
    return res
})

// Controls
const next = () => {
    const n = filteredProjects.value.length || 1
    currentIndex.value = (currentIndex.value + 1) % n
}
const prev = () => {
    const n = filteredProjects.value.length || 1
    currentIndex.value = (currentIndex.value - 1 + n) % n
}

// Autoplay
const startAutoplay = () => {
    stopAutoplay()
    autoplayTimer.value = setInterval(() => {
        next()
    }, autoplayMs)
}
const stopAutoplay = () => {
    if (autoplayTimer.value) {
        clearInterval(autoplayTimer.value)
        autoplayTimer.value = null
    }
}

// Touch swipe (desktop carousel area also listens so users on touch devices can swipe)
let touchStartX = 0
let touchDeltaX = 0
const onTouchStart = (e) => {
    touchStartX = e.touches[0].clientX
}
const onTouchMove = (e) => {
    touchDeltaX = e.touches[0].clientX - touchStartX
}
const onTouchEnd = () => {
    const threshold = 40
    if (touchDeltaX > threshold) {
        prev()
    } else if (touchDeltaX < -threshold) {
        next()
    }
    touchDeltaX = 0
}

// Mobile touch handlers use mobileIndex
const mobileOnTouchStartX = { val: 0 }
const onMobileTouchStart = (e) => { mobileOnTouchStartX.val = e.touches[0].clientX }
const onMobileTouchMove = (e) => { /* visual drag not required */ }
const onMobileTouchEnd = (e) => {
    const endX = e.changedTouches[0].clientX
    const dx = endX - mobileOnTouchStartX.val
    const th = 40
    if (dx > th) mobilePrev()
    else if (dx < -th) mobileNext()
}

// Mobile ordering for infinite loop appearance
const mobileOrderedProjects = computed(() => {
    const arr = filteredProjects.value || []
    if (arr.length === 0) return []
    // produce an array starting at mobileIndex
    const n = arr.length
    const base = ((mobileIndex.value % n) + n) % n
    const out = []
    for (let i = 0; i < n; i++) out.push(arr[(base + i) % n])
    return out
})

const setMobileIndex = (i) => { mobileIndex.value = i }
const mobileNext = () => { mobileIndex.value = (mobileIndex.value + 1) % (filteredProjects.value.length || 1) }
const mobilePrev = () => { mobileIndex.value = (mobileIndex.value - 1 + (filteredProjects.value.length || 1)) % (filteredProjects.value.length || 1) }

// mobile offset percent (to animate translateX)
const updateMobileOffset = () => { mobileOffsetPercent.value = mobileIndex.value * 100 }

// Mobile autoplay
const startMobileAutoplay = () => { stopMobileAutoplay(); mobileTimer.value = setInterval(() => mobileNext(), mobileAutoplayMs) }
const stopMobileAutoplay = () => { if (mobileTimer.value) { clearInterval(mobileTimer.value); mobileTimer.value = null } }

// Helpers (status badges)
const getStatusLabel = (status) => {
    const map = { development: 'En Desarrollo', deployed: 'Desplegado', paused: 'Pausado', completed: 'Completado' }
    return map[status] || 'Desconocido'
}
const getStatusBadgeClass = (status) => {
    const map = { development: 'bg-blue-100 text-blue-800', deployed: 'bg-green-100 text-green-800', paused: 'bg-orange-100 text-orange-800', completed: 'bg-purple-100 text-purple-800' }
    return map[status] || 'bg-gray-100 text-gray-800'
}

const showProjectDetails = (project) => { selectedProject.value = project; showModal.value = true }
const closeModal = () => { showModal.value = false; selectedProject.value = null }

onMounted(() => {
    fetchProjects()
    startAutoplay()
    startMobileAutoplay()
})
onUnmounted(() => {
    stopAutoplay()
    stopMobileAutoplay()
})

// keep mobile offset updated and ensure index in range when projects change
watch([mobileIndex, filteredProjects], () => {
    const n = filteredProjects.value.length || 1
    if (mobileIndex.value >= n) mobileIndex.value = 0
    updateMobileOffset()
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

