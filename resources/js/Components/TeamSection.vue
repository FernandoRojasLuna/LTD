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

                <!-- Desktop: 3-up carousel -->
                <div class="hidden lg:block">
                    <div class="relative" ref="desktopCarousel" @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
                        <div class="flex justify-center items-stretch mx-auto w-full xl:max-w-6xl">
                            <div v-for="(mem, i) in visibleMembers" :key="`mem-${mem.id}-${i}`" class="w-1/3 px-3">
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
                        <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="prev">‹</button>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="next">›</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script setup>
import { onMounted, onUnmounted, ref, computed, watch } from 'vue'
import { useStaff } from '@/composables/useStaff'

const { staff, loading, error, fetchStaff, getActiveStaff } = useStaff()
const members = ref([])

// Carousel state
const current = ref(0) // desktop base index
const mIndex = ref(0) // mobile index
const autoplay = ref(null)
const mAutoplay = ref(null)
const autoplayMs = 3500
const mOffset = ref(0)

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
    startAutoplay()
    startMobileAutoplay()
})

onUnmounted(() => {
    stopAutoplay()
    stopMobileAutoplay()
})

// Desktop visible 3-up
const visibleMembers = computed(() => {
    const arr = members.value || []
    const n = arr.length
    if (n === 0) return []
    const base = ((current.value % n) + n) % n
    const out = []
    for (let i = 0; i < Math.min(3, Math.max(1, n)); i++) out.push(arr[(base + i) % n])
    return out
})

const next = () => { const n = members.value.length || 1; current.value = (current.value + 1) % n }
const prev = () => { const n = members.value.length || 1; current.value = (current.value - 1 + n) % n }

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
</style>
