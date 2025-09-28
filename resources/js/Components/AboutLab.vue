<template>
    <section id="about" class="py-16 bg-white dark:bg-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Featured Content -->
            <div v-if="featuredContent" class="mb-16">
                    <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ featuredContent.title }}</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="order-2 lg:order-1">
                        <!-- Preserve newlines and ensure left-aligned text so featured content matches admin display -->
                        <div class="prose prose-lg max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-line text-left" v-html="featuredContent.content"></div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <img :src="featuredContent.image_url || featuredContent.image" :alt="featuredContent.title" class="w-full h-96 object-cover rounded-lg shadow-lg hero-standout" />
                    </div>
                </div>
            </div>

            <!-- Services/Technologies Grid -->
            <div v-if="contents.length > 0" class="mb-16">
                    <div class="text-center mb-12">
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4"></h3>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Ofrecemos soluciones integrales en transformación digital, combinando innovación y experiencia.</p>
                    </div>

                <!-- Swiper for screens below 1100 (adaptive carousel 1-4 slides) -->
                <div v-if="!isDesktopMode">
                    <div class="relative" @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
                        <div class="overflow-hidden">
                            <Swiper
                                ref="swiperEl"
                                :modules="[Navigation, Pagination, Autoplay]"
                                :spaceBetween="20"
                                    :breakpoints="swiperBreakpoints"
                                :autoplay="{ delay: autoplayDelay.value, disableOnInteraction: false }"
                                :speed="swiperSpeed"
                            :watchOverflow="true"
                            :loop="true"
                                    :slidesPerGroup="1"
                                    :autoHeight="true"
                                    :centeredSlides="false"
                                    navigation
                                    pagination
                                    @swiper="onSwiper"
                                    @slideChangeTransitionStart="onSlideChangeStart"
                                    @slideChangeTransitionEnd="onSlideChangeEnd"
                    :class="['py-4', isPhoneSingle ? 'no-mobile-arrows' : '']"
                            >
                                <SwiperSlide v-for="(s, i) in swiperItems" :key="`slide-${s.id}-${i}`" class="px-4">
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                                        <div v-if="s.image" class="mb-4">
                                            <img :src="s.image_url || s.image" :alt="s.title" class="w-full h-48 object-cover rounded-lg" loading="lazy" />
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ s.title }}</h4>
                                        <!-- Preserve newlines and align left so content matches admin display -->
                                        <div class="text-gray-600 dark:text-gray-300 whitespace-pre-line text-left" v-html="s.content"></div>
                                        <div class="mt-4 flex justify-center">
                                            <div class="flex justify-center">
                                                <span :class="[getTypeColor(s.type), 'badge-corporate inline-flex items-center gap-2']">
                                                    <svg class="badge-icon w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                                                        <path d="M12 2L15 8H9L12 2Z" fill="currentColor" opacity="0.9"/>
                                                        <path d="M12 22L9 16H15L12 22Z" fill="currentColor" opacity="0.8"/>
                                                        <circle cx="12" cy="12" r="3" fill="currentColor" opacity="0.85"/>
                                                    </svg>
                                                    <span class="badge-text">{{ getTypeLabel(s.type) }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </SwiperSlide>
                            </Swiper>
                        </div>

                        <!-- mobile navigation elements for Swiper to bind -->
                        <button
                            v-if="visibleCount > 2"
                            ref="mobilePrevBtn"
                            aria-hidden
                            class="mobile-nav-btn absolute left-2 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 shadow-lg text-transparent rounded-full p-3 pointer-events-auto z-50"
                        >‹</button>
                        <button
                            v-if="visibleCount > 2"
                            ref="mobileNextBtn"
                            aria-hidden
                            class="mobile-nav-btn absolute right-2 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 shadow-lg text-transparent rounded-full p-3 pointer-events-auto z-50"
                        >›</button>
                    </div>
                </div>

                <!-- Desktop carousel (3-up modular) -->
                <div v-if="isDesktopMode">
                    <div class="relative" @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
                        <div class="flex justify-center items-stretch mx-auto w-full xl:max-w-6xl">
                            <div class="transition-stack w-full relative">
                                <transition :name="'fade-3d-' + direction" mode="out-in">
                                    <div v-if="visibleCount===1" :key="currentCard?.id || index" class="w-full px-3 transition-slide">
                                        <div class="hero-card relative rounded-2xl overflow-hidden shadow-xl h-[60vh] flex">
                                        <!-- Left: Image with overlay showing first word of title -->
                                        <div class="hero-image w-1/2 relative overflow-hidden">
                                            <img :src="currentCard?.image_url || currentCard?.image" :alt="currentCard?.title" class="w-full h-full object-cover" loading="lazy" style="filter:brightness(0.58) contrast(0.98); object-position:center;" />
                                            <div class="hero-overlay absolute inset-0 bg-gradient-to-r from-black/80 via-black/45 to-transparent flex items-center">
                                                <div class="px-8 w-full">
                                                    <h4 class="hero-overlay-title text-white font-extrabold tracking-tight leading-tight mx-auto text-center max-w-[95%]">{{ currentCard?.title }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right: Left-aligned content to match admin formatting -->
                                        <div class="hero-body w-1/2 bg-white dark:bg-gray-800 flex items-center p-12">
                                            <div class="max-w-xl text-left w-full">
                                                <!-- Subtitle (only on desktop mode) -->
                                                <p v-if="isDesktopMode" class="subtitle-corporate mb-6 text-left">{{ currentCard?.subtitle }}</p>
                                                <!-- Content: preserve newlines and left-align -->
                                                <div class="text-gray-600 dark:text-gray-300 text-base mb-6 whitespace-pre-line" v-html="currentCard?.content"></div>
                                                <div class="flex justify-center">
                                                    <span :class="[getTypeColor(currentCard?.type), 'badge-corporate inline-flex items-center gap-2']">
                                                        <svg class="badge-icon w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                                                            <path d="M12 2L15 8H9L12 2Z" fill="currentColor" opacity="0.9"/>
                                                            <path d="M12 22L9 16H15L12 22Z" fill="currentColor" opacity="0.8"/>
                                                            <circle cx="12" cy="12" r="3" fill="currentColor" opacity="0.85"/>
                                                        </svg>
                                                        <span class="badge-text">{{ getTypeLabel(currentCard?.type) }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </transition>
                            </div>
                            <div v-if="visibleCount !== 1" class="flex w-full">
                                <div v-for="(c, i) in desktopItems" :key="`card-${c.id}-${i}`" :class="'w-1/3 px-3'">
                                    <div class="card bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl transition-transform transform hover:-translate-y-2 h-full flex flex-col text-left">
                                        <!-- existing multi-card markup -->
                                        <div v-if="c.image" class="-mx-6 -mt-6 overflow-hidden">
                                            <img :src="c.image_url || c.image" :alt="c.title" class="card-hero w-full h-52 object-cover transform transition-transform duration-300" loading="lazy" />
                                        </div>
                                            <div class="card-body px-6 py-6 flex-1 flex flex-col">
                                            <h4 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ c.title }}</h4>
                                            <p v-if="isDesktopMode" class="text-sm text-indigo-600 dark:text-indigo-400 uppercase tracking-wide font-semibold mb-3">{{ c.subtitle }}</p>
                                            <div class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-4 whitespace-pre-line text-left" v-html="c.content"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <template v-if="isDesktopMode">
                            <button :aria-disabled="!canNavigate" :class="[{ 'opacity-50': !canNavigate }, 'absolute left-2 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 shadow-lg text-gray-700 dark:text-gray-100 rounded-full p-3']" @click="prev" aria-label="Anterior">‹</button>
                            <button :aria-disabled="!canNavigate" :class="[{ 'opacity-50': !canNavigate }, 'absolute right-2 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 shadow-lg text-gray-700 dark:text-gray-100 rounded-full p-3']" @click="next" aria-label="Siguiente">›</button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
        <ProjectsSection />

            <!-- Technologies showcase -->
            <TechnologiesShowcase />

            <!-- Call to Action (corporate redesign with animated gradient and ornaments) -->
            <div class="text-center mt-8 md:mt-12 lg:mt-16">
                <div class="cta-corporate-adv relative mx-auto rounded-2xl p-8 md:p-10 text-white shadow-2xl overflow-hidden">
                    <!-- Background image from storage (fondo3.jpg) -->
                    <img :src="getStorageUrl('fondo3.jpg')" alt="" class="cta-bg-img" aria-hidden />
                    <!-- Decorative SVG soft shapes (subtle, positioned) -->
                    <svg class="cta-deco absolute left-0 top-0 opacity-30 pointer-events-none" width="420" height="420" viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                        <defs>
                            <linearGradient id="g1" x1="0" x2="1">
                                <stop offset="0%" stop-color="#7c3aed" stop-opacity="0.36"/>
                                <stop offset="100%" stop-color="#4f46e5" stop-opacity="0.18"/>
                            </linearGradient>
                        </defs>
                        <circle cx="80" cy="80" r="120" fill="url(#g1)" />
                        <circle cx="360" cy="300" r="80" fill="#ffffff22" />
                    </svg>

                    <div class="cta-inner max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative" style="z-index:2">
                        <div class="text-left lg:pr-8">
                            <h3 class="cta-title text-3xl md:text-4xl font-extrabold leading-tight mb-4">¿Listo para transformar tu negocio o entidad?</h3>
                            <p class="cta-sub text-lg md:text-xl text-indigo-50/95 max-w-2xl">Colaboramos con empresas y gobiernos para acelerar la transformación digital mediante soluciones estratégicas, seguras y medibles.</p>
                        </div>
                        <div class="flex justify-center lg:justify-end">
                            <a href="#contact" class="cta-button-adv group inline-flex items-center gap-4 bg-white/95 text-indigo-700 font-semibold py-4 px-7 rounded-2xl shadow-2xl hover:shadow-2xl transition-transform transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-indigo-300/40" role="button">
                                <span class="btn-icon bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full p-2 flex items-center justify-center shadow-md transition-transform group-hover:scale-105">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                                        <path d="M3 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="btn-text">Contáctanos ahora</span>
                                <!-- subtle animated highlight -->
                                <span aria-hidden class="cta-arc absolute -right-6 -bottom-6 w-36 h-36 rounded-full bg-white/6 blur-2xl animate-pulse-slow"></span>
                            </a>
                        </div>
                    </div>

                    <!-- Trust logos / proof with subtle motion and improved layout -->
                    <div class="trust-row-adv mt-6 border-t border-white/12 pt-6">
                        <div class="flex items-center justify-center gap-3 md:gap-6 mb-3">
                            <span class="trust-text text-sm text-gray-600 dark:text-indigo-100/80">Confiado por</span>
                        </div>

                        <div class="trust-logos-adv w-full max-w-4xl mx-auto flex justify-center items-center">
                            <template v-if="showCarousel">
                                <Swiper
                                    :modules="[Navigation, Autoplay]"
                                    :slidesPerView="1"
                                    :loop="true"
                                    :autoplay="{ delay: 4200, disableOnInteraction: false }"
                                    class="trust-swiper w-full"
                                >
                                    <SwiperSlide v-for="(logo, i) in trustLogos" :key="`logo-${i}-${logo}`" class="trust-item flex items-center justify-center">
                                        <div class="trust-logo-wrap">
                                            <div class="trust-logo-circle-adv">
                                                <img :src="getStorageUrl(logo)" @error="onTrustImgError($event)" :alt="logo" class="trust-logo-img-adv" loading="lazy" />
                                            </div>
                                        </div>
                                    </SwiperSlide>
                                </Swiper>
                            </template>
                            <template v-else>
                                <div class="trust-grid-adv grid grid-cols-3 gap-6 justify-center items-center w-full md:max-w-4xl mx-auto">
                                    <div v-for="(logo, i) in trustLogos" :key="`g-${i}-${logo}`" class="trust-item flex items-center justify-center">
                                        <div class="trust-logo-wrap">
                                            <div class="trust-logo-circle-adv">
                                                <img :src="getStorageUrl(logo)" @error="onTrustImgError($event)" :alt="logo" class="trust-logo-img-adv" loading="lazy" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount, watch, nextTick } from 'vue'
import axios from 'axios'
import TechnologiesShowcase from '@/Components/Admin/TechnologiesShowcase.vue'
import ProjectsSection from '@/Components/ProjectsSection.vue';
// Swiper
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import SwiperCore, { Navigation, Pagination, Autoplay } from 'swiper'
SwiperCore.use([Navigation, Pagination, Autoplay])

const featuredContent = ref(null)
const contents = ref([])

const index = ref(0)
const intervalRef = ref(null)
const smallDelayMs = 3000
const largeDelayMs = 6000
const direction = ref('next')
const canNavigate = ref(true)
// keep lock short so users can rapidly navigate if they want
const navigationLockMs = 650

// Swiper instance ref (for controlling slides from outside)
const swiperRef = ref(null)
// Template component ref (component exposes `.swiper`)
const swiperEl = ref(null)
// explicit DOM refs for navigation elements
const prevBtn = ref(null)
const nextBtn = ref(null)
// mobile-specific DOM refs (Swiper will bind to these for <=1099)
const mobilePrevBtn = ref(null)
const mobileNextBtn = ref(null)

const onSwiper = (swiperInstance) => {
    // store instance for programmatic control
    swiperRef.value = swiperInstance
    // sometimes the component exposes .swiper lazily
    if (swiperEl.value && !swiperEl.value.swiper) {
        try { swiperEl.value.swiper = swiperInstance } catch (e) { /* ignore */ }
    }
    try {
        console.debug('[AboutLab] onSwiper - instance stored', { hasRef: !!swiperRef.value, params: swiperInstance && swiperInstance.params })
    } catch (e) { /* noop */ }

    // bind navigation to mobile buttons if present (and we're in mobile mode)
    nextTick(() => {
        try {
            const s = swiperRef.value
            // ensure swiper uses our computed timings
            try {
                if (s) {
                    s.params.autoplay = s.params.autoplay || {}
                    s.params.autoplay.delay = autoplayDelay.value
                    s.params.autoplay.disableOnInteraction = false
                    s.params.waitForTransition = true
                    s.params.speed = swiperSpeed.value
                    try { s.update() } catch (e) { /* ignore */ }
                    try { if (s.autoplay && !s.autoplay.running) { s.autoplay.start(); console.debug('[AboutLab] onSwiper - autoplay started with delay', autoplayDelay.value) } } catch (e) { /* ignore */ }
                }
            } catch (e) { /* ignore */ }
            if (!s || !s.params || !s.navigation) return
            if (!isDesktopMode.value && mobilePrevBtn.value && mobileNextBtn.value) {
                s.params.navigation = s.params.navigation || {}
                s.params.navigation.prevEl = mobilePrevBtn.value
                s.params.navigation.nextEl = mobileNextBtn.value
                try { s.navigation.destroy() } catch (e) { /* ignore */ }
                try { s.navigation.init() } catch (e) { /* ignore */ }
                try { s.navigation.update() } catch (e) { /* ignore */ }
            } else {
                // ensure mobile navigation isn't bound when switching to desktop
                try { s.navigation.destroy() } catch (e) { /* ignore */ }
            }
        } catch (e) { /* ignore */ }
    })
}

// Helpers for trust logos
const getStorageUrl = (filename) => {
    // public/storage is web-accessible in this project
    return `/storage/${filename}`
}

const onTrustImgError = (ev) => {
    try {
        const el = ev && ev.target
        if (!el) return
        // inline SVG placeholder as fallback
        el.src = 'data:image/svg+xml;utf8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="120" height="36"><rect width="120" height="36" rx="6" fill="#ffffff22"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff88" font-family="Arial, Helvetica, sans-serif" font-size="10">Logo</text></svg>')
    } catch (e) { /* ignore */ }
}

let mqlDesktop = null
let mqlLarge = null

const updateVisibleCount = () => {
    try {
        if (mqlDesktop && mqlDesktop.matches) {
            visibleCount.value = 1
            isDesktopMode.value = true
            return
        }
        isDesktopMode.value = false
        if (mqlLarge && mqlLarge.matches) {
            visibleCount.value = 3
            return
        }
        visibleCount.value = 1
    } catch (e) {
        // fallback
        const w = window.innerWidth
        isDesktopMode.value = w >= 1100
        visibleCount.value = w >= 1024 && w < 1100 ? 3 : 1
    }
}

// set up matchMedia objects
const setupMediaQueries = () => {
    try {
        mqlDesktop = window.matchMedia('(min-width: 1100px)')
        mqlLarge = window.matchMedia('(min-width: 1024px)')

        // prefer 'change' but fallback to 'resize' if unavailable
        if (typeof mqlDesktop.addEventListener === 'function') {
            mqlDesktop.addEventListener('change', updateVisibleCount)
            mqlLarge.addEventListener('change', updateVisibleCount)
        } else if (typeof mqlDesktop.addListener === 'function') {
            mqlDesktop.addListener(updateVisibleCount)
            mqlLarge.addListener(updateVisibleCount)
        }
        // also keep window resize as a safety net
        window.addEventListener('resize', updateVisibleCount)
    } catch (e) {
        window.addEventListener('resize', updateVisibleCount)
    }
}

const teardownMediaQueries = () => {
    try {
        if (mqlDesktop) {
            if (typeof mqlDesktop.removeEventListener === 'function') {
                mqlDesktop.removeEventListener('change', updateVisibleCount)
            } else if (typeof mqlDesktop.removeListener === 'function') {
                mqlDesktop.removeListener(updateVisibleCount)
            }
        }
        if (mqlLarge) {
            if (typeof mqlLarge.removeEventListener === 'function') {
                mqlLarge.removeEventListener('change', updateVisibleCount)
            } else if (typeof mqlLarge.removeListener === 'function') {
                mqlLarge.removeListener(updateVisibleCount)
            }
        }
        window.removeEventListener('resize', updateVisibleCount)
    } catch (e) {
        window.removeEventListener('resize', updateVisibleCount)
    }
}

const getVisibleCards = computed(() => {
    const items = contents.value || []
    const count = visibleCount.value || 1
    if (items.length === 0) return []
    if (items.length <= count) return items.slice()

    const visible = []
    for (let i = 0; i < count; i++) {
        const idx = (index.value + i) % items.length
        visible.push(items[idx])
    }
    return visible
})

// For desktop (non-hero) show all available items (excluding featured one)
const desktopItems = computed(() => {
    const items = contents.value || []
    // exclude featuredContent from the list to avoid duplicate
    return items.filter(c => !(featuredContent.value && c.id === featuredContent.value.id))
})

// Items to feed to Swiper (exclude featured)
const swiperItems = computed(() => {
    return contents.value.filter(c => !(featuredContent.value && c.id === featuredContent.value.id))
})

// Swiper breakpoints: 1 slide default, 2 slides >=672 (keep 2 slides up to 1099)
const swiperBreakpoints = {
    0: { slidesPerView: 1 },
    672: { slidesPerView: 2 }
}

// desktop detection and visible count
const isDesktopMode = ref(false)
const visibleCount = ref(1)

// autoplay computed delay depending on visible count and device mode
const autoplayDelay = computed(() => {
    // prefer explicit desktop mode: desktop uses largeDelayMs, mobile/tablet uses smallDelayMs
    if (isDesktopMode.value) return largeDelayMs
    return smallDelayMs
})

// helper: true when we're on a phone-like viewport showing a single card
const isPhoneSingle = computed(() => {
    return !isDesktopMode.value && visibleCount.value === 1
})

// swiper transition speed (ms)
const swiperSpeed = ref(1200)

// rebind navigation when mode changes (desktop <-> mobile)
watch(isDesktopMode, (desktop) => {
    nextTick(() => {
        try {
            const s = swiperRef.value
            if (!s || !s.params || !s.navigation) return
            if (!desktop && mobilePrevBtn.value && mobileNextBtn.value) {
                s.params.navigation = s.params.navigation || {}
                s.params.navigation.prevEl = mobilePrevBtn.value
                s.params.navigation.nextEl = mobileNextBtn.value
                try { s.navigation.destroy() } catch (e) { /* ignore */ }
                try { s.navigation.init() } catch (e) { /* ignore */ }
                try { s.navigation.update() } catch (e) { /* ignore */ }
            } else {
                try { s.navigation.destroy() } catch (e) { /* ignore */ }
            }
        } catch (e) { /* ignore */ }
    })
})

const visibleItems = computed(() => {
    const items = contents.value || []
    const count = visibleCount.value
    if (items.length === 0) return []
    if (items.length <= count) return items.slice()

    const visible = []
    for (let i = 0; i < count; i++) {
        const idx = (index.value + i) % items.length
        visible.push(items[idx])
    }
    return visible
})

const startAutoplay = () => {
    try {
        // Desktop: use a dedicated interval to advance the internal index for hero view
        if (isDesktopMode.value) {
            // avoid multiple intervals
            if (intervalRef.value) return
            intervalRef.value = setInterval(() => {
                if (!contents.value || contents.value.length <= 1) return
                lockNavigation()
                index.value = (index.value + 1) % contents.value.length
            }, 5000) // 5 seconds for desktop
            console.debug('[AboutLab] startAutoplay - desktop interval started (5s)')
            return
        }
        // Mobile/tablet: rely on Swiper autoplay
        startSwiperAutoplay()
    } catch (e) { /* ignore */ }
}

const stopAutoplay = () => {
    try { stopSwiperAutoplay() } catch (e) { /* ignore */ }
}

// stop any desktop interval
const stopDesktopInterval = () => {
    try {
        if (intervalRef.value) {
            clearInterval(intervalRef.value)
            intervalRef.value = null
            console.debug('[AboutLab] stopDesktopInterval - cleared')
        }
    } catch (e) { /* ignore */ }
}

// Also control Swiper autoplay if present
const startSwiperAutoplay = () => {
    if (swiperRef.value && swiperRef.value.autoplay && typeof swiperRef.value.autoplay.start === 'function') {
        try {
            // only start if not already running to avoid double-start and race conditions
            if (!swiperRef.value.autoplay.running) swiperRef.value.autoplay.start()
        } catch (e) { /* ignore */ }
    }
}

const stopSwiperAutoplay = () => {
    if (swiperRef.value && swiperRef.value.autoplay && typeof swiperRef.value.autoplay.stop === 'function') {
        try {
            if (swiperRef.value.autoplay.running) swiperRef.value.autoplay.stop()
        } catch (e) { /* ignore */ }
    }
}

// Ensure autoplay resumes after manual interaction (prev/next)
const resumeAutoplayAfterInteraction = () => {
    try {
        // small debounce so we don't immediately restart during transition
    setTimeout(() => {
            // use startAutoplay which handles desktop vs mobile logic
            try { startAutoplay() } catch (e) { /* ignore */ }
    }, 300)
    } catch (e) { /* ignore */ }
}

// stronger resume: stop everything and re-init depending on mode
const forceResumeAutoplay = () => {
    try {
        // clear any JS interval
        if (intervalRef.value) {
            clearInterval(intervalRef.value)
            intervalRef.value = null
        }
        // stop swiper autoplay to reset state
        try {
            if (swiperRef.value && swiperRef.value.autoplay && swiperRef.value.autoplay.running) {
                console.debug('[AboutLab] forceResumeAutoplay - stopping swiper autoplay')
                swiperRef.value.autoplay.stop()
            }
        } catch (e) { /* ignore */ }
        // restart based on mode
        if (isDesktopMode.value) {
            // start our desktop interval (explicit 5s for >=1100px)
            try { stopDesktopInterval() } catch (e) { /* ignore */ }
            intervalRef.value = setInterval(() => {
                if (!contents.value || contents.value.length <= 1) return
                lockNavigation()
                index.value = (index.value + 1) % contents.value.length
            }, 5000)
            console.debug('[AboutLab] forceResumeAutoplay - desktop interval started (5s)')
        } else {
            // mobile: reconfigure and start swiper autoplay
            try {
                const s = swiperRef.value
                if (s) {
                    console.debug('[AboutLab] forceResumeAutoplay - reconfiguring swiper autoplay with delay', autoplayDelay.value)
                    s.params.autoplay = s.params.autoplay || {}
                    s.params.autoplay.delay = autoplayDelay.value
                    // ensure speed is applied
                    s.params.speed = swiperSpeed.value
                    try { s.update() } catch (e) { /* ignore */ }
                    try {
                        // robust restart: stop then start after a short pause to avoid races
                        if (s.autoplay && s.autoplay.running) {
                            try { s.autoplay.stop() } catch (e) { /* ignore */ }
                        }
                        setTimeout(() => {
                            try {
                                if (s.autoplay && !s.autoplay.running) s.autoplay.start()
                                console.debug('[AboutLab] forceResumeAutoplay - swiper autoplay started')
                            } catch (e) { console.debug('[AboutLab] forceResumeAutoplay - start failed', e) }
                        }, 120)
                    } catch (e) { /* ignore */ }
                }
            } catch (e) { /* ignore */ }
        }
    } catch (e) { /* ignore */ }
}

const next = () => {
    // if navigation is temporarily disabled, still allow user to nudge the Swiper instance
    if (!canNavigate.value) {
        try {
            if (swiperRef.value && typeof swiperRef.value.slideNext === 'function') {
                swiperRef.value.slideNext()
            }
        } catch (e) { /* ignore */ }
        return
    }
    if (!contents.value || contents.value.length <= 1) return
    direction.value = 'next'
    // If not desktop (Swiper active), prefer controlling the swiper instance explicitly
    if (!isDesktopMode.value) {
        try {
            if (swiperRef.value) {
                const s = swiperRef.value
                const loop = !!s.params && !!s.params.loop
                const len = (contents.value && contents.value.length) ? contents.value.length : 0
                if (len <= 0) return
                if (loop && typeof s.slideToLoop === 'function') {
                    const real = typeof s.realIndex === 'number' ? s.realIndex : 0
                    const targetReal = (real + 1) % len
                    console.debug('[AboutLab] next() slideToLoop', { real, targetReal, len })
                    s.slideToLoop(targetReal)
                    lockNavigation()
                    return
                }
                if (typeof s.slideTo === 'function') {
                    const active = (typeof s.activeIndex === 'number') ? s.activeIndex : 0
                    const target = active + 1
                    try { s.slideTo(target) } catch (err) { try { s.slideNext() } catch (e) { /* ignore */ } }
                    lockNavigation()
                    return
                }
            }
        } catch (e) {
            console.warn('next() forced slideTo failed, falling back to index', e)
        }
        // fallback
        index.value = (index.value + 1) % contents.value.length
    } else {
        index.value = (index.value + 1) % contents.value.length
    }
    lockNavigation()
    console.debug('[AboutLab] next() invoked, activeIndex:', swiperRef.value?.activeIndex, 'realIndex:', swiperRef.value?.realIndex)
    // ensure autoplay continues after manual control
    resumeAutoplayAfterInteraction()
    // stronger resume to ensure consistent loop
    forceResumeAutoplay()
}

const prev = () => {
    // if navigation is temporarily disabled, still allow user to nudge the Swiper instance
    if (!canNavigate.value) {
        try {
            if (swiperRef.value && typeof swiperRef.value.slidePrev === 'function') {
                swiperRef.value.slidePrev()
            }
        } catch (e) { /* ignore */ }
        return
    }
    if (!contents.value || contents.value.length <= 1) return
    direction.value = 'prev'
    if (!isDesktopMode.value) {
        try {
            if (swiperRef.value) {
                const s = swiperRef.value
                const loop = !!s.params && !!s.params.loop
                const len = (contents.value && contents.value.length) ? contents.value.length : 0
                if (len <= 0) return
                if (loop && typeof s.slideToLoop === 'function') {
                    const real = typeof s.realIndex === 'number' ? s.realIndex : 0
                    const targetReal = (real - 1 + len) % len
                    console.debug('[AboutLab] prev() slideToLoop', { real, targetReal, len })
                    s.slideToLoop(targetReal)
                    lockNavigation()
                    return
                }
                if (typeof s.slideTo === 'function') {
                    const active = (typeof s.activeIndex === 'number') ? s.activeIndex : 0
                    const target = active - 1
                    try { s.slideTo(target) } catch (err) { try { s.slidePrev() } catch (e) { /* ignore */ } }
                    lockNavigation()
                    return
                }
            }
        } catch (e) {
            console.warn('prev() forced slideTo failed, falling back to index', e)
        }
        index.value = (index.value - 1 + contents.value.length) % contents.value.length
    } else {
        index.value = (index.value - 1 + contents.value.length) % contents.value.length
    }
    lockNavigation()
    console.debug('[AboutLab] prev() invoked, activeIndex:', swiperRef.value?.activeIndex, 'realIndex:', swiperRef.value?.realIndex)
    // ensure autoplay continues after manual control
    resumeAutoplayAfterInteraction()
    // stronger resume to ensure consistent loop
    forceResumeAutoplay()
}

// Swiper slide events
const onSlideChangeStart = () => {
    lockNavigation()
}

const onSlideChangeEnd = () => {
    // allow any additional small adjustments after transition
    // ensure navigation is re-enabled when swiper finishes moving
    try { canNavigate.value = true } catch (e) { /* ignore */ }
}

const lockNavigation = () => {
    // briefly disable navigation to avoid accidental multi-clicks during transition
    canNavigate.value = false
    // always clear any previous timer to avoid prolonging lock
    try { if (lockNavigation._timer) clearTimeout(lockNavigation._timer) } catch (e) {}
    lockNavigation._timer = setTimeout(() => { canNavigate.value = true; lockNavigation._timer = null }, navigationLockMs)
}

onBeforeUnmount(() => {
    stopAutoplay()
    teardownMediaQueries()
})

// Methods: fetch content
const fetchFeaturedContent = async () => {
    try {
        const response = await axios.get('/api/public/contents/featured')
        if (response.data && response.data.length) featuredContent.value = response.data[0]
    } catch (e) { console.error('fetchFeaturedContent', e) }
}

const fetchContents = async () => {
    try {
        const response = await axios.get('/api/public/contents')
        contents.value = (response.data || []).filter(c => !c.is_featured || c.id !== featuredContent.value?.id)
    } catch (e) { console.error('fetchContents', e) }
}

const getTypeLabel = (type) => {
    const labels = { general: 'General', service: 'Servicio', technology: 'Tecnología', area: 'Área' }
    return labels[type] || 'General'
}

const getTypeColor = (type) => {
    const colors = { general: 'bg-blue-100 text-blue-800', service: 'bg-green-100 text-green-800', technology: 'bg-purple-100 text-purple-800', area: 'bg-orange-100 text-orange-800' }
    return colors[type] || 'bg-gray-100 text-gray-800'
}

const currentCard = computed(() => {
    const cards = getVisibleCards.value
    if (!cards || cards.length === 0) return null
    return cards[0]
})

// keep index in range when contents change
watch(() => contents.value.length, (len) => {
    if (index.value >= len) index.value = 0
})

// Utility: split first word from the rest of the title
const firstWord = (title = '') => {
    if (!title) return ''
    const parts = title.trim().split(' ')
    return parts[0]
}

const restTitle = (title = '') => {
    if (!title) return ''
    const parts = title.trim().split(' ')
    return parts.slice(1).join(' ') || ''
}

onMounted(async () => {
    setupMediaQueries()
    updateVisibleCount()

    await fetchFeaturedContent()
    await fetchContents()
    startAutoplay()
})

// Trust logos: filenames stored in public/storage
const trustLogos = ref([
    'logoGore.jpg',
    'muniLima.jpg',
    'muni.jpg'
    
])

const showCarousel = computed(() => {
    return (trustLogos.value || []).length > 3
})
</script>


 
<style scoped>
.card-hero { transition: transform 0.35s ease, filter 0.35s ease; }
.card-hero:hover { transform: scale(1.03); filter: saturate(1.05) contrast(1.02); }
.card-hero { border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; }

/* Ensure the card container allows image overflow for negative margins */
.bg-white.rounded-xl.p-6.h-full { overflow: visible; }

/* Mobile image hover */
.thumb-mobile img { transition: transform 0.35s ease; }
.thumb-mobile:hover img { transform: scale(1.02); }

/* Optional overlay look (subtle) */
.card-hero::after { content: '' }

/* Corporate badge for type labels */
.badge-corporate {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
    border: 1px solid rgba(15, 23, 42, 0.06);
}
.badge-icon { opacity: 0.95; }
.badge-text { display: inline-block; line-height: 1 }

/* Slight variations so background color (from getTypeColor) and text color stay readable */
.badge-corporate.bg-blue-100 { border-color: rgba(29, 78, 216, 0.08) }
.badge-corporate.bg-green-100 { border-color: rgba(16, 185, 129, 0.08) }
.badge-corporate.bg-purple-100 { border-color: rgba(139, 92, 246, 0.08) }
.badge-corporate.bg-orange-100 { border-color: rgba(249, 115, 22, 0.08) }
.badge-corporate.bg-gray-100 { border-color: rgba(107, 114, 128, 0.06) }

/* Hero layout styles for large screens */
.hero-card { display: flex; border-radius: 1rem; overflow: hidden; }
.hero-image { position: relative; }
.hero-overlay { padding-left: 2rem; }
.hero-overlay-title { text-shadow: 0 10px 30px rgba(2,6,23,0.6); }
.hero-body { background: linear-gradient(180deg, #ffffff 0%, #fbfbfd 100%); }

/* Subtitle corporate style */
.subtitle-corporate {
    color: #6b21a8; /* indigo-800 variant */
    font-size: 0.9rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    font-weight: 700;
}

/* Make the featured image stand out with subtle shadow and rounded corners */
.hero-standout {
    border-radius: 1rem;
    box-shadow: 0 30px 60px rgba(2,6,23,0.12), 0 6px 16px rgba(2,6,23,0.06);
    object-position: center center;
}

/* Make overlay title responsive and legible over images */
.hero-overlay-title {
    font-size: clamp(1.75rem, 3.8vw + 1rem, 4rem); /* responsive */
    letter-spacing: 0.02em;
    line-height: 0.95;
    text-wrap: balance;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    padding: 0.25rem 0.5rem;
    background: linear-gradient(90deg, rgba(0,0,0,0.35), rgba(0,0,0,0.0));
    display: inline-block;
    border-radius: 6px;
}

/* Slight semi-opaque mask behind title for extreme images */
.hero-overlay::before {
    content: '';
    position: absolute;
    left: 0; top: 0; right: 50%; bottom: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.45), rgba(0,0,0,0));
    pointer-events: none;
}

/* Typography tuned for corporate look */
.hero-overlay-title { font-family: ui-serif, Georgia, 'Times New Roman', serif; font-weight: 800; }
.hero-body h4 { font-family: ui-sans-serif, system-ui, -apple-system, 'Helvetica Neue', Arial; font-weight: 700; }
.hero-body .text-gray-600 { color: #475569; }

/* Responsiveness tweaks */
@media (max-width: 1440px) {
    .hero-overlay-title { font-size: 3.25rem; }
}
@media (max-width: 1099px) {
    .hero-card { flex-direction: column; height: auto; }
    .hero-image, .hero-body { width: 100%; }
    .hero-overlay-title { font-size: 2.25rem; padding-left: 1rem; }
    .hero-body { padding: 2rem; }
}

/* Mobile navigation button polish: remove duplicate blue/focus ring and ensure clean look */
@media (max-width: 1099px) {
    .mobile-nav-btn {
        /* neutral background and subtle shadow */
        background: #ffffff !important;
        box-shadow: 0 6px 18px rgba(2,6,23,0.08) !important;
        color: #374151 !important; /* text-gray-700 */
        border: 1px solid rgba(15,23,42,0.06);
        /* ensure button floats above any swiper internal controls */
        z-index: 60 !important;
        /* remove any default browser focus ring that appears as blue background */
        -webkit-tap-highlight-color: transparent;
    }
    /* remove inner duplicate backgrounds or pseudo-elements from Swiper that may show below */
    .mobile-nav-btn::before, .mobile-nav-btn::after { display: none !important; }
    /* prevent blue focus halo but keep accessible outline for keyboard users */
    .mobile-nav-btn:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12) !important; /* subtle indigo ring */
    }
    /* slightly increase hit area without visually changing size */
    .mobile-nav-btn { padding: 0.6rem !important; width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; }
}

/* Card transition animations */
.card-enter-from, .card-leave-to {
    opacity: 0;
    transform: translateX(20px) rotateY(6deg) scale(0.98);
}
.card-enter-to, .card-leave-from {
    opacity: 1;
    transform: translateX(0) rotateY(0) scale(1);
}
.card-enter-active, .card-leave-active {
    transition: all 650ms cubic-bezier(.2,.9,.2,1);
}
.anim-next .card-enter-from { transform: translateX(30px) rotateY(8deg) scale(0.98); }
.anim-prev .card-enter-from { transform: translateX(-30px) rotateY(-8deg) scale(0.98); }
.anim-next .card-leave-to { transform: translateX(-30px) rotateY(-6deg) scale(0.98); }
.anim-prev .card-leave-to { transform: translateX(30px) rotateY(6deg) scale(0.98); }

/* Polished next/prev transitions for single-card hero and mobile */
.card-next-enter-from { opacity: 0; transform: translateX(40px) perspective(1000px) rotateY(8deg) scale(0.98); }
.card-next-enter-to { opacity: 1; transform: translateX(0) perspective(1000px) rotateY(0) scale(1); }
.card-next-leave-from { opacity: 1; transform: translateX(0) perspective(1000px) rotateY(0) scale(1); }
.card-next-leave-to { opacity: 0; transform: translateX(-40px) perspective(1000px) rotateY(-6deg) scale(0.98); }

.card-prev-enter-from { opacity: 0; transform: translateX(-40px) perspective(1000px) rotateY(-8deg) scale(0.98); }
.card-prev-enter-to { opacity: 1; transform: translateX(0) perspective(1000px) rotateY(0) scale(1); }
.card-prev-leave-from { opacity: 1; transform: translateX(0) perspective(1000px) rotateY(0) scale(1); }
.card-prev-leave-to { opacity: 0; transform: translateX(40px) perspective(1000px) rotateY(6deg) scale(0.98); }

.card-next-enter-active, .card-next-leave-active, .card-prev-enter-active, .card-prev-leave-active {
    transition: transform 680ms cubic-bezier(.2,.9,.2,1), opacity 480ms cubic-bezier(.2,.9,.2,1);
}

/* New polished cross-fade + 3D blur transitions */
.transition-stack { position: relative; }
.transition-stack .transition-slide { position: relative; }

.fade-3d-next-enter-from,
.fade-3d-prev-enter-from {
    opacity: 0;
    transform: translateX(var(--x, 30px)) perspective(1000px) rotateY(var(--ry, 6deg)) scale(0.99);
    filter: blur(6px) saturate(0.9);
}
.fade-3d-next-enter-to,
.fade-3d-prev-enter-to {
    opacity: 1;
    transform: translateX(0) perspective(1000px) rotateY(0) scale(1);
    filter: blur(0) saturate(1);
}
.fade-3d-next-leave-from,
.fade-3d-prev-leave-from {
    opacity: 1;
    transform: translateX(0) perspective(1000px) rotateY(0) scale(1);
}
.fade-3d-next-leave-to {
    opacity: 0;
    transform: translateX(-40px) perspective(1000px) rotateY(-8deg) scale(0.98);
    filter: blur(6px) saturate(0.9);
}
.fade-3d-prev-leave-to {
    opacity: 0;
    transform: translateX(40px) perspective(1000px) rotateY(8deg) scale(0.98);
    filter: blur(6px) saturate(0.9);
}
.fade-3d-next-enter-active, .fade-3d-next-leave-active,
.fade-3d-prev-enter-active, .fade-3d-prev-leave-active {
    transition: transform 680ms cubic-bezier(.2,.9,.2,1), opacity 420ms cubic-bezier(.2,.9,.2,1), filter 420ms ease;
}

/* Small overlay cross-fade to hide abrupt image edges */
.transition-stack::after {
    content: '';
    position: absolute;
    left: 0; right: 0; top: 0; bottom: 0;
    pointer-events: none;
    background: linear-gradient(180deg, rgba(255,255,255,0.0) 0%, rgba(255,255,255,0.02) 50%, rgba(0,0,0,0.02) 100%);
    mix-blend-mode: normal;
}

/* CTA corporate styles */
.cta-corporate {
    background: linear-gradient(90deg, rgba(79,70,229,0.96) 0%, rgba(99,102,241,0.95) 100%);
    border-radius: 1rem;
    padding: 2.5rem;
}
.cta-corporate .cta-title { color: #ffffff; }
.cta-corporate .cta-sub { color: rgba(255,255,255,0.92); }
.cta-button {
    min-width: 180px;
    border-radius: 0.75rem;
}
.cta-button svg { opacity: 0.95 }
.trust-row { opacity: 0.95 }
.trust-logo { opacity: 1; filter: none; }

.trust-logos { justify-content: center; }
.trust-item { display: flex; align-items: center; justify-content: center; padding: 12px; }

/* hide old card wrappers if present; we use a circular logo container instead */
.trust-card { display: none !important; }

/* Circular logo container */
.trust-logo-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background: transparent;
    padding: 0.5rem;
}

/* Logo image: circular, full color, responsive */
.trust-logo-img {
    display: block;
    border-radius: 9999px; /* make image itself circular where possible */
    object-fit: contain;
    width: auto;
    /* scale fluidly but cap size */
    max-height: clamp(4.25rem, 9vw + 0.5rem, 10rem); /* approx 68px - 160px */
    filter: none; /* ensure full color */
    transition: transform 260ms ease, box-shadow 260ms ease;
}
.trust-logo-img:hover { transform: translateY(-4px); box-shadow: 0 20px 56px rgba(2,6,23,0.14); }

@media (max-width: 1280px) {
    .trust-logo-img { max-height: 8rem; }
}

@media (max-width: 1024px) {
    .trust-logo-img { max-height: 7rem; }
}

@media (max-width: 640px) {
    .trust-logo-img { max-height: 5.5rem; }
}

@media (max-width: 420px) {
    .trust-logo-img { max-height: 4.75rem; }
}

@media (max-width: 768px) {
    .cta-corporate { padding: 1.5rem; }
    .cta-inner { grid-template-columns: 1fr; }
    .cta-button { min-width: 160px; }
    .cta-title { text-align: center; }
    .cta-sub { text-align: center; }
    .trust-row { justify-content: center }
}

/* Reduce vertical spacing for trust logos on small screens */
@media (max-width: 640px) {
    .trust-item { padding: 6px; }
    .trust-logo-circle { padding: 0.25rem; }
    .trust-row { margin-top: 0.5rem; }
    .trust-logos { max-width: 90%; }
}

/* --- Advanced CTA & Logo Animations (corporate polish) --- */
.cta-corporate-adv {
    /* make gradient overlay subtle so the photo background is visible */
    background: linear-gradient(90deg, rgba(91,33,182,0.22) 0%, rgba(67,56,202,0.18) 35%, rgba(6,182,212,0.12) 100%);
    background-size: 200% 200%;
    animation: ctaGradient 18s ease infinite;
    border: 1px solid rgba(255,255,255,0.06);
}

/* subtle dark overlay to improve text contrast over the photo */
.cta-corporate-adv::before {
    content: '';
    position: absolute;
    left: 0; top: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, rgba(6,6,23,0.12) 0%, rgba(2,6,23,0.18) 100%);
    z-index: 1;
    pointer-events: none;
    border-radius: inherit;
}

/* ensure inner content sits above overlay */
.cta-corporate-adv > .cta-inner { z-index: 2; position: relative; }

/* background image inside CTA: placed absolutely and blended under gradient */
.cta-bg-img {
    position: absolute;
    left: 0; top: 0; right: 0; bottom: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center center;
    opacity: 0.36; /* increase visibility of the photo */
    mix-blend-mode: normal; /* don't let purple dominate via multiply */
    filter: saturate(1) brightness(0.95) contrast(0.98);
    pointer-events: none;
    z-index: 1;
}

@media (max-width: 768px) {
    .cta-bg-img { opacity: 0.22; }
}

@media (prefers-reduced-motion: reduce) {
    .cta-bg-img { transition: none !important; animation: none !important; }
}

@keyframes ctaGradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.cta-button-adv { position: relative; overflow: visible; }
.cta-button-adv .btn-text { position: relative; z-index: 10; }
.cta-button-adv .btn-icon { transition: transform 220ms cubic-bezier(.2,.9,.2,1); }
.cta-button-adv:focus { outline: none; }
.cta-button-adv:focus-visible { box-shadow: 0 8px 30px rgba(99,102,241,0.18); }

.cta-arc { transition: opacity 420ms ease, transform 420ms ease; z-index: 2; }
.group:hover .cta-arc { opacity: 1; transform: translateY(-6px) scale(1.02); }
.animate-pulse-slow { animation: pulseSlow 3.6s ease-in-out infinite; opacity: 0.85 }
@keyframes pulseSlow { 0% { transform: scale(1); opacity: 0.7 } 50% { transform: scale(1.04); opacity: 0.95 } 100% { transform: scale(1); opacity: 0.7 } }

/* trust logos advanced */
.trust-logo-circle-adv { display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; padding: 0.4rem; background: rgba(255,255,255,0.02); transition: transform 320ms cubic-bezier(.2,.9,.2,1), box-shadow 320ms ease; }
.trust-logo-img-adv { display: block; border-radius: 9999px; object-fit: contain; max-width: 140px; max-height: 84px; transition: transform 360ms cubic-bezier(.2,.9,.2,1), box-shadow 360ms ease, filter 360ms ease; }
.trust-item:hover .trust-logo-circle-adv, .trust-item:focus-within .trust-logo-circle-adv { transform: translateY(-6px); box-shadow: 0 30px 80px rgba(2,6,23,0.14); }
.trust-item:hover .trust-logo-img-adv, .trust-item:focus-within .trust-logo-img-adv { transform: scale(1.02); filter: saturate(1.05); }

/* keyboard accessibility for logos */
.trust-item > .trust-logo-wrap { outline: none; }
.trust-item > .trust-logo-wrap:focus-within { box-shadow: 0 0 0 4px rgba(99,102,241,0.14); border-radius: 12px; }

/* responsive adjustments */
@media (max-width: 768px) {
    .trust-grid-adv { grid-template-columns: repeat(3, minmax(0,1fr)); }
    .trust-logo-img-adv { max-width: 120px; max-height: 64px; }
}
@media (max-width: 420px) {
    .trust-grid-adv { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px; }
    .trust-logo-img-adv { max-width: 96px; max-height: 56px; }
}

/* subtle entrance animation for CTA section */
.cta-corporate-adv { transform: translateY(0); opacity: 1; transition: transform 760ms cubic-bezier(.2,.9,.2,1), opacity 480ms ease; }
.cta-corporate-adv[data-visible="false"] { transform: translateY(12px); opacity: 0; }

/* small polish for svg decoration */
.cta-deco { transform-origin: center; animation: decoFloat 10s ease-in-out infinite; }
@keyframes decoFloat { 0% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-6px) rotate(6deg); } 100% { transform: translateY(0) rotate(0deg); } }

/* reduce motion for users who prefer reduced-motion */
@media (prefers-reduced-motion: reduce) {
    .cta-deco, .cta-corporate-adv, .trust-logo-circle-adv, .cta-arc, .trust-logo-img-adv { animation: none !important; transition: none !important; }
}
</style>

<style>
/* Force-hide swiper navigation buttons inside mobile single-card Swiper */
.no-mobile-arrows .swiper-button-prev,
.no-mobile-arrows .swiper-button-next {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}
</style>