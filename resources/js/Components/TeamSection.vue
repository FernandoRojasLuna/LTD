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

                <!-- Mobile: 1-up carousel -->
                <div class="block lg:hidden">
                    <div class="relative" ref="mobileCarousel" @touchstart.passive="mOnTouchStart" @touchmove.passive="mOnTouchMove" @touchend="mOnTouchEnd" @mouseenter="stopMobileAutoplay" @mouseleave="startMobileAutoplay">
                        <div class="overflow-hidden">
                            <div class="flex transition-transform duration-500" :style="{ transform: `translateX(-${mOffset.value}%)` }">
                                <div v-for="(mem, idx) in mobileOrdered" :key="`mem-${mem.id}-${idx}`" class="min-w-full px-3">
                                    <!-- card -->
                                    <div class="group bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer">
                                        <div class="relative h-56 bg-gray-100 overflow-hidden">
                                            <img :src="imageUrl(mem.image)" :alt="mem.name" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-6">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ mem.name }}</h3>
                                                <span :class="mem.active ? 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800' : 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800'">{{ mem.active ? 'Activo' : 'Inactivo' }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">{{ mem.position }}</p>
                                            <p class="text-gray-600 mt-3 line-clamp-3">{{ mem.bio }}</p>
                                            <div class="mt-4 flex items-center space-x-3"><a v-if="mem.linkedin" :href="mem.linkedin" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">LinkedIn</a><a v-if="mem.github" :href="mem.github" target="_blank" class="text-gray-800 hover:text-gray-900 text-sm">GitHub</a><a v-if="mem.email" :href="`mailto:${mem.email}`" class="text-gray-500 text-sm">Email</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2"><button class="bg-white shadow-lg text-gray-700 rounded-full p-2" @click="mPrev">‹</button></div>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2"><button class="bg-white shadow-lg text-gray-700 rounded-full p-2" @click="mNext">›</button></div>
                    </div>
                </div>

                <!-- Desktop: 5-up clean carousel -->
                <div class="hidden lg:block">
                    <div class="relative" ref="desktopCarousel" @mouseenter="stopDesktopAutoplay" @mouseleave="startDesktopAutoplay">
                        <div class="overflow-hidden">
                            <div class="flex transition-transform duration-500 ease-in-out" ref="track">
                                <!-- duplicate items for seamless loop -->
                                <div v-for="(mem, i) in loopedMembers" :key="`mem-${mem.id}-${i}`" class="w-1/5 px-3 flex-shrink-0">
                                    <button @click="openModal(mem)" class="w-full text-left group bg-white rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-2xl focus:outline-none">
                                        <div class="relative h-40 bg-gray-100 overflow-hidden">
                                            <img :src="imageUrl(mem.image)" :alt="mem.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />

                                            <!-- overlay oscuro + boton central -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                                <div class="text-center text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 mb-3 mx-auto w-fit">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </div>
                                                    <p class="text-sm font-medium">Ver detalles</p>
                                                </div>
                                            </div>

                                            <!-- degradado inferior sutil -->
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

                        <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="dPrev" aria-label="Anterior">‹</button>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="dNext" aria-label="Siguiente">›</button>
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

// Carousel state
const current = ref(0) // desktop base index (in items)
const mIndex = ref(0) // mobile index
const autoplay = ref(null)
const mAutoplay = ref(null)
const autoplayMs = 3500
const mOffset = ref(0)

// Desktop/track state (pixels)
const dTrack = ref(null)
const track = ref(null)
const dItemWidth = ref(0)
const dOffset = ref(0) // pixels
const dIndex = ref(0) // current logical index inside loopedMembers
const dAutoplay = ref(null)

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
    // initialize desktop track after DOM
    await nextTick()
    computeDimensions()
    // ensure track listens to transition end to handle wrap
    const trackEl = track.value
    if (trackEl && !trackEl._team_transition_bound) {
        trackEl.addEventListener('transitionend', onTrackTransitionEnd)
        trackEl._team_transition_bound = true
    }
    startDesktopAutoplay()
    startAutoplay()
    startMobileAutoplay()
    // keyboard handler for modal
    window.addEventListener('keydown', onKeyDown)
    window.addEventListener('resize', computeDimensions)
})

onUnmounted(() => {
    stopAutoplay()
    stopMobileAutoplay()
    stopDesktopAutoplay()
    window.removeEventListener('keydown', onKeyDown)
    window.removeEventListener('resize', computeDimensions)
})

const onKeyDown = (e) => {
    if (e.key === 'Escape' && modalOpen.value) closeModal()
}

// Desktop visible 5-up (logical)
const visibleMembers = computed(() => {
    const arr = members.value || []
    const n = arr.length
    if (n === 0) return []
    const base = ((current.value % n) + n) % n
    const out = []
    for (let i = 0; i < Math.min(5, Math.max(1, n)); i++) out.push(arr[(base + i) % n])
    return out
})

// For seamless looping we duplicate the array three times and start in the middle copy
const loopedMembers = computed(() => {
    const arr = members.value || []
    if (arr.length === 0) return []
    // triple duplication: [A, A, A] to allow buffer both sides
    return arr.concat(arr, arr)
})

const next = () => { const n = members.value.length || 1; current.value = (current.value + 1) % n }
const prev = () => { const n = members.value.length || 1; current.value = (current.value - 1 + n) % n }

// Desktop controls (pixel based)
const dNext = () => {
    if (!dItemWidth.value) computeDimensions()
    stopDesktopAutoplay()
    const nextIndex = dIndex.value + 1
    setTrackTransform(nextIndex, true)
    setTimeout(() => startDesktopAutoplay(), 800)
}
const dPrev = () => {
    if (!dItemWidth.value) computeDimensions()
    stopDesktopAutoplay()
    const prevIndex = dIndex.value - 1
    setTrackTransform(prevIndex, true)
    setTimeout(() => startDesktopAutoplay(), 800)
}

const animateTo = (target) => {
    // simple animation using requestAnimationFrame
    const start = dOffset.value
    const duration = 300
    const startTime = performance.now()
    const step = (now) => {
        const t = Math.min(1, (now - startTime) / duration)
        dOffset.value = start + (target - start) * (1 - Math.pow(1 - t, 3))
        if (t < 1) requestAnimationFrame(step)
        else checkLoopReset()
    }
    requestAnimationFrame(step)
}

const checkLoopReset = () => {
    // if we've moved past the duplicated half, reset without visual jump
    const arrLen = members.value.length || 1
    const totalWidth = dItemWidth.value * arrLen
    if (totalWidth <= 0) return
    if (dOffset.value >= totalWidth) {
        dOffset.value = dOffset.value - totalWidth
    } else if (dOffset.value < 0) {
        // wrap negative offset into positive range
        dOffset.value = (dOffset.value % totalWidth + totalWidth) % totalWidth
    }
}

// Autoplay desktop
const startAutoplay = () => { stopAutoplay(); autoplay.value = setInterval(() => next(), autoplayMs) }
const stopAutoplay = () => { if (autoplay.value) { clearInterval(autoplay.value); autoplay.value = null } }

// Mobile ordering & controls
const mobileOrdered = computed(() => {
    const arr = members.value || []
    if (arr.length === 0) return []
    const n = arr.length
    const base = ((mIndex.value % n) + n) % n
    const out = []
    for (let i = 0; i < n; i++) out.push(arr[(base + i) % n])
    return out
})

const mNext = () => { const n = members.value.length || 1; mIndex.value = (mIndex.value + 1) % n }
const mPrev = () => { const n = members.value.length || 1; mIndex.value = (mIndex.value - 1 + n) % n }

// mobile autoplay
const startMobileAutoplay = () => { stopMobileAutoplay(); mAutoplay.value = setInterval(() => mNext(), autoplayMs) }
const stopMobileAutoplay = () => { if (mAutoplay.value) { clearInterval(mAutoplay.value); mAutoplay.value = null } }

// mobile offset
const updateMOffset = () => { mOffset.value = mIndex.value * 100 }

// compute dimensions for desktop track
const computeDimensions = () => {
    const trackEl = track.value
    if (!trackEl) return
    const container = trackEl.parentElement
    if (!container) return
    const containerWidth = container.clientWidth
    dItemWidth.value = Math.floor(containerWidth / 5)
    // initialize index in the middle copy so we have buffer on both sides
    const n = members.value.length || 1
    dIndex.value = n
    dOffset.value = dIndex.value * dItemWidth.value
    // set initial transform without transition
    setTrackTransform(dIndex.value, false)
}

// set transform on the track element directly; withTransition = true enables CSS transition
const setTrackTransform = (index, withTransition = true) => {
    const trackEl = track.value
    if (!trackEl) return
    const x = index * dItemWidth.value
    if (withTransition) {
        trackEl.style.transition = 'transform 500ms ease'
    } else {
        trackEl.style.transition = 'none'
    }
    trackEl.style.transform = `translateX(-${x}px)`
    dOffset.value = x
    dIndex.value = index
}

const onTrackTransitionEnd = () => {
    const n = members.value.length || 1
    if (n <= 0) return
    // when we pass beyond the second copy, bring back to middle copy
    if (dIndex.value >= 2 * n) {
        const newIndex = dIndex.value - n
        // reset transform without transition
        setTrackTransform(newIndex, false)
    } else if (dIndex.value < n) {
        const newIndex = dIndex.value + n
        setTrackTransform(newIndex, false)
    }
}

// modal controls
const openModal = (mem) => { modalMember.value = mem; modalOpen.value = true; nextTick(() => { const btn = document.querySelector('#modal-close-btn'); if (btn) btn.focus() }) }
const closeModal = () => { modalOpen.value = false; modalMember.value = null }

// desktop autoplay (pixel-based)
const startDesktopAutoplay = () => { stopDesktopAutoplay(); dAutoplay.value = setInterval(() => dNext(), autoplayMs) }
const stopDesktopAutoplay = () => { if (dAutoplay.value) { clearInterval(dAutoplay.value); dAutoplay.value = null } }

// touch handlers mobile
let mStartX = 0
const mOnTouchStart = (e) => { mStartX = e.touches[0].clientX }
const mOnTouchMove = (e) => { /* visual not required */ }
const mOnTouchEnd = (e) => { const dx = e.changedTouches[0].clientX - mStartX; const th = 40; if (dx > th) mPrev(); else if (dx < -th) mNext() }

// watchers
watch([mIndex, members], () => { const n = members.value.length || 1; if (mIndex.value >= n) mIndex.value = 0; updateMOffset() })
watch(members, () => { const n = members.value.length || 1; if (current.value >= n) current.value = 0 })

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
</style>
