<template>
    <section id="team" class="py-16 bg-white">
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
            <!-- Modal: staff details -->
            <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-auto">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 id="modal-title" class="text-lg font-semibold">{{ modalMember?.name }}</h3>
                        <button id="modal-close-btn" @click="closeModal" class="text-gray-600 hover:text-gray-900 px-3 py-1 rounded focus:outline-none">Cerrar</button>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="col-span-1">
                            <img :src="imageUrl(modalMember?.image)" :alt="modalMember?.name" class="w-full h-64 object-cover rounded-lg" />
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">{{ modalMember?.position }}</p>
                            <p class="mt-4 text-gray-700">{{ modalMember?.bio }}</p>
                            <div class="mt-4 flex items-center gap-3">
                                <a v-if="modalMember?.linkedin" :href="modalMember.linkedin" target="_blank" class="text-indigo-600">LinkedIn</a>
                                <a v-if="modalMember?.github" :href="modalMember.github" target="_blank" class="text-gray-800">GitHub</a>
                                <a v-if="modalMember?.email" :href="`mailto:${modalMember.email}`" class="text-gray-500">Email</a>
                            </div>
                        </div>
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
