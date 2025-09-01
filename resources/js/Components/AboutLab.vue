<template>
    <section id="about" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Featured Content -->
            <div v-if="featuredContent" class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        {{ featuredContent.title }}
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="order-2 lg:order-1">
                        <div 
                            class="prose prose-lg max-w-none text-gray-700"
                            v-html="featuredContent.content"
                        ></div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <img 
                            :src="featuredContent.image" 
                            :alt="featuredContent.title"
                            class="w-full h-96 object-cover rounded-lg shadow-lg"
                        >
                    </div>
                </div>
            </div>
            
            <!-- Services/Technologies Grid -->
            <div v-if="contents.length > 0" class="mb-16">
                <div class="text-center mb-12">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        Nuestras Especialidades
                    </h3>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Ofrecemos soluciones integrales en transformación digital, combinando innovación y experiencia.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="content in contents" 
                        :key="content.id"
                        class="bg-gray-50 rounded-lg p-6 hover:shadow-lg transition duration-300 transform hover:-translate-y-1"
                    >
                        <div v-if="content.image" class="mb-4">
                            <img 
                                :src="content.image" 
                                :alt="content.title"
                                class="w-full h-48 object-cover rounded-lg"
                            >
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">
                            {{ content.title }}
                        </h4>
                        <div 
                            class="text-gray-600 line-clamp-3"
                            v-html="content.content"
                        ></div>
                        <div class="mt-4">
                            <span 
                                :class="[
                                    'inline-block px-3 py-1 text-xs font-medium rounded-full',
                                    getTypeColor(content.type)
                                ]"
                            >
                                {{ getTypeLabel(content.type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="text-center">
                <div class="bg-indigo-600 rounded-lg p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">
                        ¿Listo para transformar tu negocio?
                    </h3>
                    <p class="text-xl mb-6">
                        Contáctanos y descubre cómo podemos ayudarte a alcanzar tus objetivos digitales.
                    </p>
                    <a 
                        href="#contact" 
                        class="inline-block bg-white text-indigo-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105"
                    >
                        Contáctanos Ahora
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Reactive data
const featuredContent = ref(null)
const contents = ref([])

// Methods
const fetchFeaturedContent = async () => {
    try {
        const response = await axios.get('/api/public/contents/featured')
        if (response.data.length > 0) {
            featuredContent.value = response.data[0] // Primer contenido destacado
        }
    } catch (error) {
        console.error('Error fetching featured content:', error)
    }
}

const fetchContents = async () => {
    try {
        const response = await axios.get('/api/public/contents')
        // Filtrar contenido que no sea el destacado principal
        contents.value = response.data.filter(content => 
            !content.is_featured || content.id !== featuredContent.value?.id
        )
    } catch (error) {
        console.error('Error fetching contents:', error)
    }
}

const getTypeLabel = (type) => {
    const labels = {
        'general': 'General',
        'service': 'Servicio',
        'technology': 'Tecnología',
        'area': 'Área'
    }
    return labels[type] || 'General'
}

const getTypeColor = (type) => {
    const colors = {
        'general': 'bg-blue-100 text-blue-800',
        'service': 'bg-green-100 text-green-800',
        'technology': 'bg-purple-100 text-purple-800',
        'area': 'bg-orange-100 text-orange-800'
    }
    return colors[type] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(async () => {
    await fetchFeaturedContent()
    await fetchContents()
})
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    margin-top: 0;
    margin-bottom: 0.5em;
}

.prose p {
    margin-bottom: 1em;
}

.prose ul, .prose ol {
    margin-bottom: 1em;
    padding-left: 1.5em;
}

.prose li {
    margin-bottom: 0.25em;
}
</style>
