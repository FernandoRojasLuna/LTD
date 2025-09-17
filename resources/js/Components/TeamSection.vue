<template>
    <section id="staff" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestro Equipo</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Conoce al equipo de ingeniería y diseño que hace posible nuestras soluciones.</p>
            </div>

            <div v-if="loading" class="text-center py-12">Cargando miembros del equipo...</div>
            <div v-else>
                <div v-if="members.length === 0" class="text-center py-12 text-gray-500">No hay miembros del equipo aún.</div>

                    <!-- Continuous JS-driven carousel: two groups (original + clone) scrolled with requestAnimationFrame -->
                    <div class="continuous-carousel overflow-hidden py-4" ref="carouselRef" @mouseenter="pauseScroll" @mouseleave="resumeScroll">
                        <div class="track flex items-stretch" ref="trackRef" role="list" style="transform: translateX(0); will-change: transform;">
                            <div class="group flex" ref="groupRef">
                                <div v-for="(mem, idx) in members" :key="`slide1-${mem.id || idx}`" class="slide swiper-slide-auto px-2">
                                    <button @click="openModal(mem)" class="w-full text-left group bg-white rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-2xl focus:outline-none">
                                        <div class="relative h-40 bg-gray-100 overflow-hidden">
                                            <img :src="imageUrl(mem.image)" :alt="mem.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
                                        </div>
                                        <div class="py-3 px-4 text-center">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate">{{ mem.name }}</h4>
                                            <p class="text-xs text-gray-500 truncate">{{ mem.position }}</p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <!-- spacer para separar visualmente los grupos y evitar fusión en pantallas pequeñas -->
                            <div class="spacer" ref="spacerRef" aria-hidden="true" style="flex: 0 0 2rem;"></div>
                            <div class="group flex" aria-hidden="true">
                                <div v-for="(mem, idx) in members" :key="`slide2-${mem.id || idx}`" class="slide swiper-slide-auto px-2">
                                    <button @click="openModal(mem)" class="w-full text-left group bg-white rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-2xl focus:outline-none">
                                        <div class="relative h-40 bg-gray-100 overflow-hidden">
                                            <img :src="imageUrl(mem.image)" :alt="mem.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
                                        </div>
                                        <div class="py-3 px-4 text-center">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate">{{ mem.name }}</h4>
                                            <p class="text-xs text-gray-500 truncate">{{ mem.position }}</p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <!-- Modal: staff details (corporate, responsive, uses only real fields) -->
            <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full mx-2 overflow-hidden" style="max-height:90vh;">
                    <!-- Header -->
                    <div class="relative border-b bg-white">
                        <div class="px-6 py-4">
                            <h3 id="modal-title" class="text-2xl font-semibold text-gray-900">{{ modalMember?.name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ modalMember?.position }}</p>
                        </div>
                        <button id="modal-close-btn" @click="closeModal" class="absolute right-4 top-4 text-gray-600 hover:text-gray-900 focus:outline-none" aria-label="Cerrar modal">Cerrar</button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 overflow-auto">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                            <div class="md:col-span-1">
                                <div class="rounded-lg overflow-hidden bg-gray-50 p-3">
                                    <img :src="imageUrl(modalMember?.image)" :alt="modalMember?.name" class="w-full h-64 object-cover rounded-md shadow-sm" />
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500">Perfil</p>
                                <p class="mt-4 text-gray-700 leading-relaxed">{{ modalMember?.bio || 'Información no disponible.' }}</p>

                                <div class="mt-6 flex flex-wrap items-center gap-3">
                                    <a v-if="modalMember?.linkedin" :href="modalMember.linkedin" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:underline">
                                        <!-- simple LinkedIn icon -->
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.5 8h4V24h-4zM8.5 8h3.78v2.2h.05c.53-1 1.83-2.2 3.77-2.2 4.03 0 4.78 2.66 4.78 6.12V24h-4v-7.2c0-1.72-.03-3.94-2.4-3.94-2.4 0-2.77 1.88-2.77 3.81V24h-4V8z"/></svg>
                                        LinkedIn
                                    </a>

                                    <a v-if="modalMember?.github" :href="modalMember.github" target="_blank" class="inline-flex items-center gap-2 text-gray-800 hover:underline">
                                        <!-- simple GitHub icon -->
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .5C5.37.5 0 5.87 0 12.49c0 5.28 3.44 9.75 8.21 11.33.6.11.82-.26.82-.58 0-.29-.01-1.05-.01-2.07-3.34.73-4.04-1.61-4.04-1.61-.55-1.4-1.34-1.78-1.34-1.78-1.09-.75.08-.74.08-.74 1.2.08 1.83 1.23 1.83 1.23 1.07 1.83 2.8 1.3 3.49.99.11-.78.42-1.3.76-1.6-2.66-.3-5.46-1.33-5.46-5.92 0-1.31.47-2.38 1.23-3.22-.12-.3-.54-1.52.12-3.17 0 0 1.01-.32 3.3 1.23a11.45 11.45 0 013.01-.41c1.02.01 2.05.14 3.01.41 2.28-1.55 3.29-1.23 3.29-1.23.66 1.65.24 2.87.12 3.17.77.84 1.23 1.91 1.23 3.22 0 4.6-2.8 5.61-5.47 5.91.43.37.81 1.1.81 2.22 0 1.6-.01 2.89-.01 3.29 0 .32.21.69.82.57C20.56 22.23 24 17.76 24 12.49 24 5.87 18.63.5 12 .5z"/></svg>
                                        GitHub
                                    </a>

                                    <a v-if="modalMember?.email" :href="`mailto:${modalMember.email}`" class="inline-flex items-center gap-2 text-gray-600 hover:underline">
                                        <!-- simple mail icon -->
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M1.5 4.5h21v15h-21v-15zm19.5-1.5h-18c-.83 0-1.5.67-1.5 1.5v15c0 .83.67 1.5 1.5 1.5h18c.83 0 1.5-.67 1.5-1.5v-15c0-.83-.67-1.5-1.5-1.5zm-9 9.75l-7.5-4.5v-.75l7.5 4.5 7.5-4.5v.75l-7.5 4.5z"/></svg>
                                        Email
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-4 border-t bg-gray-50 flex items-center justify-end gap-3">
                        <button @click="closeModal" class="px-4 py-2 rounded-md bg-white border text-gray-700 hover:bg-gray-50">Cerrar</button>
                        <a v-if="modalMember?.linkedin" :href="modalMember.linkedin" target="_blank" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Ver perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { onMounted, onUnmounted, ref, computed, watch, nextTick } from 'vue'
import { useStaff } from '@/composables/useStaff'

    const { staff, loading, error, fetchStaff, getActiveStaff } = useStaff()
    const members = ref([])

    // To ensure a smooth, continuous "serpiente" loop even when there are few members,
    // render a duplicated list. A pure CSS marquee will drive a continuous animation.
    const displayMembers = computed(() => {
        if (!members.value || members.value.length === 0) return []
        return [...members.value, ...members.value]
    })

    // JS-driven scroller state
    const carouselRef = ref(null)
    const trackRef = ref(null)
    const groupRef = ref(null)
    const spacerRef = ref(null)
    let rafId = null
    const paused = ref(false)

    // speed in pixels per second (tweakable)
    const pxPerSecond = ref(100)


    const lastTime = { value: 0 }
    const offset = { value: 0 }

    const measureAndStart = () => {
        cancelRAF()
        offset.value = 0
        lastTime.value = performance.now()
        // measure widths including spacer
        const groupWidth = groupRef.value ? groupRef.value.offsetWidth : 0
        const spacerWidth = spacerRef.value ? spacerRef.value.offsetWidth : 0
        // store total cycle width on the element for speed
        if (trackRef.value) trackRef.value._cycleWidth = groupWidth + spacerWidth
        rafId = requestAnimationFrame(loop)
    }

    const loop = (now) => {
        if (!lastTime.value) lastTime.value = now
        if (paused.value) { lastTime.value = now; rafId = requestAnimationFrame(loop); return }
        const delta = (now - lastTime.value) / 1000
        lastTime.value = now
        const cycleWidth = trackRef.value && trackRef.value._cycleWidth ? trackRef.value._cycleWidth : (groupRef.value ? groupRef.value.offsetWidth : 0)
        if (cycleWidth > 0) {
            offset.value += delta * pxPerSecond.value
            if (offset.value >= cycleWidth) offset.value -= cycleWidth
            const x = -offset.value
            if (trackRef.value) trackRef.value.style.transform = `translateX(${x}px)`
        }
        rafId = requestAnimationFrame(loop)
    }

    const cancelRAF = () => { if (rafId) { cancelAnimationFrame(rafId); rafId = null } }

    const pauseScroll = () => { paused.value = true }
    const resumeScroll = () => { if (paused.value) { paused.value = false; lastTime.value = performance.now(); } }

// Carousel state handled by Swiper now (continuous autoplay/freeMode)

// Modal state
const modalOpen = ref(false)
const modalMember = ref(null)

// Fetch members
onMounted(async () => {
    try {
        if (fetchStaff) {
            await fetchStaff()
            members.value = staff.value || []
        } else if (getActiveStaff) {
            members.value = await getActiveStaff()
        }
    } catch (e) {
        console.error('Error loading staff:', e)
        members.value = []
    }
    await nextTick()
    // keyboard handler for modal
    window.addEventListener('keydown', onKeyDown)
        // start JS scroller
        await nextTick()
        if (groupRef.value && trackRef.value) {
            // ensure layout measured
            measureAndStart()
            window.addEventListener('resize', measureAndStart)
        }
})

onUnmounted(() => {
    // cleanup
    cancelRAF()
    window.removeEventListener('keydown', onKeyDown)
    window.removeEventListener('resize', measureAndStart)
})

const onKeyDown = (e) => {
    if (e.key === 'Escape' && modalOpen.value) closeModal()
}


// modal controls
const openModal = (mem) => { modalMember.value = mem; modalOpen.value = true; nextTick(() => { const btn = document.querySelector('#modal-close-btn'); if (btn) btn.focus() }) }
const closeModal = () => { modalOpen.value = false; modalMember.value = null }

// Helper: normalize image URLs
const imageUrl = (val) => {
    if (!val) return '/images/staff/placeholder.jpg'
    // If absolute URL
    if (val.startsWith('http://') || val.startsWith('https://')) return val
    // If already starts with / (public path), return as-is
    if (val.startsWith('/')) return val
    // If stored path like 'staff/xxx.jpg' or 'staff\xxx.jpg', prefix with /storage/
    return '/storage/' + val.replace(/^\/+/, '')
}
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Clean card minimal look */
.group img { border-radius: 0.5rem; }
.group button { border: none; background: transparent; }

/* Modal tweaks */
[role="dialog"] { padding: 1rem; }

/* Ensure 5-up layout items don't shrink unexpectedly */
.w-1\/5 { width: 20%; }

/* overlay/button tweaks */
.group .bg-white\/20 { background-color: rgba(255,255,255,0.18); }
.group .backdrop-blur-sm { backdrop-filter: blur(4px); }
.group-hover\:scale-110 { transform: scale(1.10); }

/* For slidesPerView: 'auto' ensure a minimum width so large screens keep movement smooth */
.swiper-slide-auto { min-width: 12rem; }
@media (min-width: 768px) {
    .swiper-slide-auto { min-width: 14rem; }
}
@media (min-width: 1280px) {
    .swiper-slide-auto { min-width: 16rem; }
}

/* Continuous marquee animation for the team carousel */
.continuous-carousel .track {
    display: flex;
    gap: 0.5rem;
    align-items: stretch;
}

/* Ensure slides are inline and don't shrink */
.continuous-carousel .slide { flex: 0 0 auto; }
.continuous-carousel .slide .h-40 { height: 10rem; }
</style>
