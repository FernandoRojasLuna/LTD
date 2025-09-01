<template>
    <section id="home" class="relative overflow-hidden">
        <!-- Carrusel Container -->
        <div class="relative h-96 md:h-[600px]">
            <!-- Banner Slides -->
            <div 
                v-for="(banner, index) in banners" 
                :key="banner.id"
                :class="[
                    'absolute inset-0 transition-opacity duration-1000 ease-in-out',
                    currentSlide === index ? 'opacity-100' : 'opacity-0'
                ]"
            >
                <!-- Background Image -->
                <div 
                    class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                    :style="{ backgroundImage: `url(${banner.image})` }"
                >
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 flex items-center justify-center h-full">
                    <div class="text-center text-white max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in-up">
                            {{ banner.title }}
                        </h1>
                        <p v-if="banner.subtitle" class="text-xl md:text-2xl mb-6 animate-fade-in-up animation-delay-200">
                            {{ banner.subtitle }}
                        </p>
                        <p v-if="banner.description" class="text-lg md:text-xl mb-8 max-w-2xl mx-auto animate-fade-in-up animation-delay-400">
                            {{ banner.description }}
                        </p>
                        <div v-if="banner.link" class="animate-fade-in-up animation-delay-600">
                            <a 
                                :href="banner.link" 
                                class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105"
                            >
                                Conocer Más
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Dots -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
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
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-2 rounded-full transition duration-300"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button 
            @click="nextSlide"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-2 rounded-full transition duration-300"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

// Reactive data
const banners = ref([])
const currentSlide = ref(0)
const autoSlideInterval = ref(null)

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
    }, 5000) // Cambiar cada 5 segundos
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
})

onUnmounted(() => {
    stopAutoSlide()
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
</style>
