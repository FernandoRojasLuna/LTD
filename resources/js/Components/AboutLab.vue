<template>
    <section id="about" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Featured Content -->
            <div v-if="featuredContent" class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ featuredContent.title }}</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="order-2 lg:order-1">
                        <div class="prose prose-lg max-w-none text-gray-700" v-html="featuredContent.content"></div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <img :src="featuredContent.image" :alt="featuredContent.title" class="w-full h-96 object-cover rounded-lg shadow-lg" />
                    </div>
                </div>
            </div>

            <!-- Services/Technologies Grid -->
            <div v-if="contents.length > 0" class="mb-16">
                <div class="text-center mb-12">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Nuestras Especialidades</h3>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">Ofrecemos soluciones integrales en transformación digital, combinando innovación y experiencia.</p>
                </div>

                <!-- Mobile grid fallback -->
                <div class="block lg:hidden grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="content in contents" :key="content.id" class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                        <div v-if="content.image" class="mb-4">
                            <img :src="content.image" :alt="content.title" class="w-full h-48 object-cover rounded-lg" loading="lazy" />
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">{{ content.title }}</h4>
                        <div class="text-gray-600 line-clamp-3" v-html="content.content"></div>
                        <div class="mt-4 flex justify-center">
                            <div class="flex justify-center">
                                <span :class="[getTypeColor(content.type), 'badge-corporate inline-flex items-center gap-2']">
                                    <svg class="badge-icon w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                                        <path d="M12 2L15 8H9L12 2Z" fill="currentColor" opacity="0.9"/>
                                        <path d="M12 22L9 16H15L12 22Z" fill="currentColor" opacity="0.8"/>
                                        <circle cx="12" cy="12" r="3" fill="currentColor" opacity="0.85"/>
                                    </svg>
                                    <span class="badge-text">{{ getTypeLabel(content.type) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop carousel (3-up modular) -->
                <div class="hidden lg:block">
                    <div class="relative" @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
                        <div class="flex justify-center items-stretch mx-auto w-full xl:max-w-6xl">
                                <div v-for="(c, i) in getVisibleCards" :key="`card-${c.id}-${i}`" class="w-1/3 px-3">
                                    <div class="card bg-white rounded-2xl overflow-hidden shadow-xl transition-transform transform hover:-translate-y-2 h-full flex flex-col text-left">
                                        <div v-if="c.image" class="-mx-6 -mt-6 overflow-hidden">
                                            <img :src="c.image" :alt="c.title" class="card-hero w-full h-52 object-cover transform transition-transform duration-300" loading="lazy" />
                                        </div>

                                        <div class="card-body px-6 py-6 flex-1 flex flex-col">
                                            <h4 class="text-2xl font-semibold text-gray-900 mb-3">{{ c.title }}</h4>
                                            <div class="text-gray-600 text-sm mb-4 line-clamp-4" v-html="c.content"></div>

                                            <div class="mt-auto">
                                                <div class="mb-4 flex justify-center">
                                                    <div class="flex justify-center">
                                                        <span :class="[getTypeColor(c.type), 'badge-corporate inline-flex items-center gap-2']">
                                                        <svg class="badge-icon w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                                                            <path d="M12 2L15 8H9L12 2Z" fill="currentColor" opacity="0.9"/>
                                                            <path d="M12 22L9 16H15L12 22Z" fill="currentColor" opacity="0.8"/>
                                                            <circle cx="12" cy="12" r="3" fill="currentColor" opacity="0.85"/>
                                                        </svg>
                                                        <span class="badge-text">{{ getTypeLabel(c.type) }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="prev" aria-label="Anterior">‹</button>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white shadow-lg text-gray-700 rounded-full p-3" @click="next" aria-label="Siguiente">›</button>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <div class="bg-indigo-600 rounded-lg p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">¿Listo para transformar tu negocio?</h3>
                    <p class="text-xl mb-6">Contáctanos y descubre cómo podemos ayudarte a alcanzar tus objetivos digitales.</p>
                    <a href="#contact" class="inline-block bg-white text-indigo-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105">Contáctanos Ahora</a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount } from 'vue'
import axios from 'axios'

const featuredContent = ref(null)
const contents = ref([])

const index = ref(0)
const intervalRef = ref(null)
const delay = 3000

const getVisibleCards = computed(() => {
    const items = contents.value || []
    if (items.length === 0) return []
    if (items.length <= 3) return items.slice()

    const visible = []
    for (let i = 0; i < 3; i++) {
        const idx = (index.value + i) % items.length
        visible.push(items[idx])
    }
    return visible
})

const startAutoplay = () => {
    stopAutoplay()
    intervalRef.value = setInterval(() => {
        if (!contents.value || contents.value.length <= 3) return
        index.value = (index.value + 1) % contents.value.length
    }, delay)
}

const stopAutoplay = () => {
    if (intervalRef.value) {
        clearInterval(intervalRef.value)
        intervalRef.value = null
    }
}

const next = () => {
    if (!contents.value || contents.value.length <= 3) return
    index.value = (index.value + 1) % contents.value.length
}

const prev = () => {
    if (!contents.value || contents.value.length <= 3) return
    index.value = (index.value - 1 + contents.value.length) % contents.value.length
}

onBeforeUnmount(() => stopAutoplay())

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

onMounted(async () => {
    await fetchFeaturedContent()
    await fetchContents()
    startAutoplay()
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
</style>