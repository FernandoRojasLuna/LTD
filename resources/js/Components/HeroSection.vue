<template>
    <section id="home" class="relative overflow-hidden">
        <!-- Carrusel Container -->
    <div ref="carousel" tabindex="0" @pointerdown="onPointerDown" @pointerup="onPointerUp" @pointercancel="onPointerCancel" @pointermove="onPointerMove" @keydown="onKeydown" class="relative h-96 md:h-[600px] focus:outline-none carousel">

            <!-- Background layers (crossfade) -->
            <div class="absolute inset-0 z-0">
                <div
                    v-for="(banner, index) in banners"
                    :key="`bg-${banner.id}`"
                    :class="['absolute inset-0 bg-cover bg-center bg-no-repeat bg-layer', currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 -z-10']"
                    :style="{ backgroundImage: `linear-gradient(to right, rgba(13,17,28,0.9), rgba(13,17,28,0.5), rgba(13,17,28,0)), url(${banner.image})` }"
                >
                    <!-- Preload image for better crossfade -->
                    <img :src="banner.image" alt="" class="w-full h-full opacity-0 pointer-events-none" />
                </div>
            </div>

            <!-- Content (single active banner) -->
                <div class="relative z-20 flex items-center justify-center h-full">
                <div v-if="banners.length" class="text-center text-white max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 active-slide">
                    <div v-if="banners[currentSlide].label" class="hidden md:block text-sm font-medium uppercase text-yellow-300 mb-2">{{ banners[currentSlide].label }}</div>
                    <h1 class="text-4xl md:text-6xl font-bold mb-4 banner-title">
                        <span :key="currentSlide" class="banner-title-inner">
                            <template v-for="(ch, i) in splitTitle" :key="`ch-${i}-${currentSlide}`">
                                <span
                                    class="char"
                                    :style="{ 'animationDelay': (i * 0.03) + 's' }"
                                >{{ ch }}</span>
                            </template>
                        </span>
                    </h1>
                    <p v-if="banners[currentSlide].subtitle" class="hidden md:block text-xl md:text-2xl mb-6 animate-fade-in-up animation-delay-600 text-opacity-90">
                        {{ banners[currentSlide].subtitle }}
                    </p>
                    <p v-if="banners[currentSlide].description" class="hidden md:block text-lg md:text-xl mb-8 max-w-2xl mx-auto animate-fade-in-up animation-delay-850 text-opacity-90">
                        {{ banners[currentSlide].description }}
                    </p>
                    <div v-if="banners[currentSlide].link" class="animate-fade-in-up animation-delay-1050">
                        <a
                            :href="banners[currentSlide].link"
                                class="inline-block banner-btn-toggle text-white font-medium py-4 px-8 rounded-lg transition-colors duration-300"
                        >
                            Conocer Más
                        </a>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Navigation Dots -->
    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2 z-30">
            <button
                v-for="(banner, index) in banners"
                :key="`dot-${banner.id}`"
                @click="goToSlide(index)"
                :class="[
                    'w-3 h-3 rounded-full transition-all duration-300',
                    currentSlide === index 
                        ? 'bg-white scale-125' 
                        : 'bg-white bg-opacity-50 hover:bg-opacity-75'
                ]"
            ></button>
        </div>
        
        <!-- Navigation Arrows -->
    <button 
            @click="previousSlide"
            aria-label="Anterior banner"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full transition duration-300 z-30 shadow-xl"
            :class="['bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400']"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
    <button 
            @click="nextSlide"
            aria-label="Siguiente banner"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full transition duration-300 z-30 shadow-xl"
            :class="['bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400']"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'

// Reactive data
const banners = ref([])
const currentSlide = ref(0)
const autoSlideInterval = ref(null)
const carousel = ref(null)
let pointerStartX = null
let pointerActive = false

// Methods
const fetchBanners = async () => {
    try {
        const response = await axios.get('/api/public/banners')
        banners.value = response.data
        if (banners.value.length > 0) {
            startAutoSlide()
        }
    } catch (error) {
        console.error('Error fetching banners:', error)
    }
}

const goToSlide = (index) => {
    currentSlide.value = index
    resetAutoSlide()
}

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % banners.value.length
    resetAutoSlide()
}

const previousSlide = () => {
    currentSlide.value = currentSlide.value === 0 
        ? banners.value.length - 1 
        : currentSlide.value - 1
    resetAutoSlide()
}

const startAutoSlide = () => {
    autoSlideInterval.value = setInterval(() => {
        nextSlide()
    }, 7000) // Cambiar cada 7 segundos para una transición más pausada
}

const stopAutoSlide = () => {
    if (autoSlideInterval.value) {
        clearInterval(autoSlideInterval.value)
        autoSlideInterval.value = null
    }
}

const resetAutoSlide = () => {
    stopAutoSlide()
    startAutoSlide()
}

// Lifecycle
onMounted(() => {
    fetchBanners()
    // add touch listeners for better mobile swipe support
    if (carousel.value) {
        carousel.value.addEventListener('touchstart', onPointerDown, { passive: true })
        carousel.value.addEventListener('touchmove', onPointerMove, { passive: true })
        carousel.value.addEventListener('touchend', onPointerUp, { passive: true })
        carousel.value.addEventListener('touchcancel', onPointerCancel, { passive: true })
    }
})

onUnmounted(() => {
    stopAutoSlide()
    if (carousel.value) {
        carousel.value.removeEventListener('touchstart', onPointerDown)
        carousel.value.removeEventListener('touchmove', onPointerMove)
        carousel.value.removeEventListener('touchend', onPointerUp)
        carousel.value.removeEventListener('touchcancel', onPointerCancel)
    }
})

// Touch / Pointer handlers for swipe
const onPointerDown = (e) => {
    pointerActive = true
    pointerStartX = e.clientX || e.touches?.[0]?.clientX
    stopAutoSlide()
}

const onPointerMove = (e) => {
    if (!pointerActive) return
    // could implement small drag effect in future
}

const onPointerUp = (e) => {
    if (!pointerActive) return
    const endX = e.clientX || (e.changedTouches && e.changedTouches[0].clientX) || null
    if (endX !== null && pointerStartX !== null) {
        const delta = endX - pointerStartX
        if (Math.abs(delta) > 40) {
            if (delta < 0) nextSlide()
            else previousSlide()
        }
    }
    pointerActive = false
    pointerStartX = null
    resetAutoSlide()
}

const onPointerCancel = () => {
    pointerActive = false
    pointerStartX = null
    resetAutoSlide()
}

const onKeydown = (e) => {
    if (e.key === 'ArrowLeft') previousSlide()
    if (e.key === 'ArrowRight') nextSlide()
}

// Split active title into characters for staggered animation
const splitTitle = computed(() => {
    const t = banners.value?.[currentSlide.value]?.title || ''
    // preserve non-breaking spaces for rendering
    return Array.from(t).map(ch => ch === ' ' ? '\u00A0' : ch)
})
</script>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 1s ease-out;
}

.animation-delay-200 {
    animation-delay: 0.2s;
    animation-fill-mode: both;
}

.animation-delay-400 {
    animation-delay: 0.4s;
    animation-fill-mode: both;
}

.animation-delay-600 {
    animation-delay: 0.6s;
    animation-fill-mode: both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-futuristic {
    animation: futuristicIn 1s cubic-bezier(.2,.9,.3,1) both;
}

@keyframes futuristicIn {
    0% { opacity: 0; transform: translateY(20px) skewY(6deg) scale(0.98); filter: blur(6px); }
    60% { opacity: 1; transform: translateY(-6px) skewY(-2deg) scale(1.02); filter: blur(0); }
    100% { opacity: 1; transform: translateY(0) skewY(0) scale(1); filter: none; }
}

.slide-bg {
    background-position: center center;
    background-size: cover;
    will-change: transform, opacity;
}

/* subtle particle shimmer overlay for tech feel */
.slide-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 40px 40px;
    mix-blend-mode: overlay;
    opacity: 0.6;
    pointer-events: none;
}

/* Mobile behaviors */
.carousel { touch-action: pan-y pinch-zoom; }

/* hide arrow controls on small screens */
@media (max-width: 767px) {
    button[aria-label="Anterior banner"], button[aria-label="Siguiente banner"] {
        display: none !important;
    }
    .absolute.bottom-6 { bottom: 14px; }
}

/* Crossfade and title/button styles */
.active-slide { z-index: 20 }
.banner-title {
    opacity: 0;
    transform: translateY(18px) scale(0.98);
    transition: all 650ms cubic-bezier(.2,.9,.3,1);
}
.active-slide .banner-title {
    opacity: 1;
    transform: translateY(0) scale(1);
}
.banner-btn {
    background: linear-gradient(90deg, #06b6d4, #3b82f6); /* cyan to blue */
    border: 1px solid rgba(255,255,255,0.08);
}
.banner-btn:hover {
    background: transparent !important;
    color: #06b6d4 !important;
    border: 2px solid #06b6d4 !important; /* turquoise outline on hover */
}

/* Toggle button: filled by default, outline on hover (no scale) */
.banner-btn-toggle {
    background: linear-gradient(90deg, #06b6d4, #3b82f6);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.12);
    box-shadow: 0 10px 30px rgba(6,182,212,0.14);
    border-radius: 12px;
    padding: 12px 32px;
}
.banner-btn-toggle:hover {
    background: transparent !important;
    color: #ffffff !important;
    border: 1.5px solid rgba(255,255,255,1);
    transform: none !important;
    box-shadow: none;
}
.banner-btn-toggle:focus {
    outline: 2px solid rgba(255,255,255,0.12);
    outline-offset: 2px;
}

/* subtle crossfade between slides */
.transition-all.duration-900 {
    transition-property: opacity, transform !important;
}

/* clip-path reveal for title */
.banner-title-inner {
    display: inline-block;
    transform: translateY(10px);
    opacity: 0;
    clip-path: inset(0 0 100% 0);
    transition: clip-path 900ms cubic-bezier(.2,.9,.3,1), opacity 700ms ease, transform 700ms cubic-bezier(.2,.9,.3,1);
}
.active-slide .banner-title-inner {
    clip-path: inset(0 0 0% 0);
    opacity: 1;
    transform: translateY(0);
}

/* fragmented characters animation */
.char {
    display: inline-block;
    opacity: 0;
    transform: translateY(12px) rotateX(6deg) scale(0.99);
    animation: charIn 600ms cubic-bezier(.2,.9,.3,1) both;
    will-change: transform, opacity;
}

@keyframes charIn {
    0% { opacity: 0; transform: translateY(18px) rotateX(10deg) scale(0.98); filter: blur(6px); }
    60% { opacity: 1; transform: translateY(-6px) rotateX(0deg) scale(1.02); filter: blur(0); }
    100% { opacity: 1; transform: translateY(0) rotateX(0deg) scale(1); filter: none; }
}

/* reduce whitespace collapse for NBSP */
.char { white-space: pre; }

/* text delays utility */
.animation-delay-600 { animation-delay: 0.6s; animation-fill-mode: both; }
.animation-delay-850 { animation-delay: 0.85s; animation-fill-mode: both; }
.animation-delay-1050 { animation-delay: 1.05s; animation-fill-mode: both; }

/* Background layer crossfade (inspired by user's example) */
.bg-layer {
    background-size: cover;
    background-position: center center;
    transition: opacity 5s ease-in-out, transform 6s cubic-bezier(.2,.9,.3,1);
    will-change: opacity, transform;
}
.bg-layer.opacity-0 { opacity: 0 }
.bg-layer.opacity-100 { opacity: 1 }
.bg-layer { opacity: 0 }
.bg-layer.z-10 { z-index: 10 }

/* Slight zoom and blur for inactive layers to give depth */
.bg-layer.opacity-0 { transform: scale(1.04); filter: blur(3px); }
.bg-layer.opacity-100 { transform: scale(1); filter: blur(0px); }

/* reduce paint costs on large backgrounds */
.bg-layer img { display: block; width: 1px; height: 1px; position: absolute; left: -9999px }



</style>
