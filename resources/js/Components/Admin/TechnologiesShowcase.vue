<template>
  <section class="mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-6">
        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Tecnologías que usamos</h3>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Herramientas y librerías que impulsan nuestros proyectos</p>
      </div>

      <div v-if="loading" class="flex justify-center py-8">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      </div>

      <div v-else-if="technologies.length === 0" class="text-center py-8 text-gray-500">
        No hay tecnologías registradas.
      </div>

      <div v-else class="overflow-hidden">
        <!-- Contenedor del carrusel serpiente -->
        <div class="relative w-full tech-carousel-container" @mouseenter="pauseAnimation" @mouseleave="resumeAnimation">
          <div 
            ref="trackRef"
            class="tech-track flex items-center gap-4"
            :class="{ 'paused': isPaused }"
            :style="trackStyle"
          >
            <article 
              v-for="(tech, index) in duplicatedTechnologies" 
              :key="`tech-${tech.id}-${index}`"
              class="tech-card flex-shrink-0 p-3 bg-white rounded-lg shadow-sm flex flex-col items-center"
              :style="cardStyle"
            >
              <div class="w-16 h-16 flex items-center justify-center mb-2">
                <img 
                  v-if="isImageUrl(tech.icon)" 
                  :src="tech.icon" 
                  :alt="tech.name" 
                  class="w-12 h-12 object-contain" 
                />
                <i 
                  v-else-if="tech.icon" 
                  :class="tech.icon + ' text-2xl text-indigo-600'" 
                  aria-hidden="true"
                ></i>
                <div v-else class="text-gray-400 font-bold text-xl">
                  {{ initials(tech.name) }}
                </div>
              </div>
              <div class="text-sm font-semibold text-gray-700 text-center leading-tight">
                {{ tech.name }}
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount } from 'vue'
import { useTechnologies } from '@/composables/useTechnologies'

const { getActiveTechnologies } = useTechnologies()

// Estado reactivo
const technologies = ref([])
const loading = ref(false)
const isPaused = ref(false)
const trackRef = ref(null)

// Configuración del carrusel
const animationDuration = ref(25) // segundos para completar un ciclo (más rápido)
const cardWidth = ref(160) // ancho de cada tarjeta en px
const gap = ref(16) // espacio entre tarjetas en px

// Funciones de utilidad
const isImageUrl = (url) => url && (url.startsWith('http') || url.startsWith('/storage/') || url.startsWith('data:'))
const initials = (name) => name ? name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) : ''

// Actualizar configuración responsive
const updateResponsiveConfig = () => {
  const width = window.innerWidth
  if (width < 640) {
    cardWidth.value = 120
    gap.value = 12
    animationDuration.value = 20 // más rápido en móviles
  } else if (width < 1024) {
    cardWidth.value = 140
    gap.value = 14
    animationDuration.value = 22
  } else {
    // Para pantallas grandes: mostrar 9 tarjetas
    // Calculamos el ancho disponible menos padding y gaps
    const availableWidth = Math.min(width * 0.85, 1280) // max-w-7xl con padding
    const totalGaps = 8 * 16 // 8 gaps entre 9 tarjetas
    cardWidth.value = Math.floor((availableWidth - totalGaps) / 9)
    gap.value = 16
    animationDuration.value = 25
  }
}

// Duplicar tecnologías para efecto infinito
const duplicatedTechnologies = computed(() => {
  if (technologies.value.length === 0) return []
  
  // Crear 3 copias para asegurar movimiento infinito suave
  const copies = 3
  const result = []
  
  for (let i = 0; i < copies; i++) {
    technologies.value.forEach((tech, index) => {
      result.push({
        ...tech,
        uniqueId: `${tech.id}-copy-${i}-${index}`
      })
    })
  }
  
  return result
})

// Estilos computados
const trackStyle = computed(() => {
  const totalWidth = technologies.value.length * (cardWidth.value + gap.value)
  
  return {
    '--animation-duration': `${animationDuration.value}s`,
    '--translate-distance': `${totalWidth}px`,
    width: `${duplicatedTechnologies.value.length * (cardWidth.value + gap.value)}px`
  }
})

const cardStyle = computed(() => ({
  minWidth: `${cardWidth.value}px`,
  width: `${cardWidth.value}px`
}))

// Controles de animación
const pauseAnimation = () => {
  isPaused.value = true
}

const resumeAnimation = () => {
  isPaused.value = false
}

// Normalizar iconos
const normalizeIcons = (arr) => arr.map(tech => {
  if (tech.icon && !tech.icon.startsWith('http') && !tech.icon.startsWith('data:')) {
    tech.icon = '/storage/' + tech.icon.replace(/^\/+/, '')
  }
  return tech
})

// Cargar tecnologías
const load = async () => {
  loading.value = true
  try {
    const data = await getActiveTechnologies()
    technologies.value = normalizeIcons(data || [])
  } catch (error) {
    console.error('Error loading technologies:', error)
  } finally {
    loading.value = false
  }
}

// Lifecycle hooks
onMounted(async () => {
  await load()
  updateResponsiveConfig()
  
  // Listener para cambios de tamaño de ventana
  window.addEventListener('resize', updateResponsiveConfig)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateResponsiveConfig)
})
</script>

<style scoped>
.tech-carousel-container {
  overflow: hidden;
  position: relative;
}

.tech-track {
  animation: slideLeft var(--animation-duration, 25s) linear infinite;
  will-change: transform;
}

.tech-track.paused {
  animation-play-state: paused;
}

.tech-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.tech-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

@keyframes slideLeft {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(calc(-1 * var(--translate-distance, 300px)));
  }
}

/* Estilos responsive */
@media (max-width: 640px) {
  .tech-card {
    padding: 0.5rem;
  }
  
  .tech-card .w-16 {
    width: 3rem;
    height: 3rem;
  }
  
  .tech-card img {
    width: 2.5rem;
    height: 2.5rem;
  }
  
  .tech-card .text-sm {
    font-size: 0.75rem;
  }
}

@media (min-width: 641px) and (max-width: 1024px) {
  .tech-card .w-16 {
    width: 3.5rem;
    height: 3.5rem;
  }
  
  .tech-card img {
    width: 2.75rem;
    height: 2.75rem;
  }
}

/* Para pantallas grandes (>1024px) - 9 tarjetas visibles */
@media (min-width: 1025px) {
  .tech-card {
    padding: 0.6rem;
  }
  
  .tech-card .w-16 {
    width: 3.5rem;
    height: 3.5rem;
  }
  
  .tech-card img {
    width: 2.8rem;
    height: 2.8rem;
  }
  
  .tech-card .text-sm {
    font-size: 0.8rem;
    line-height: 1.2;
  }
}

/* Efectos adicionales */
.tech-track::before,
.tech-track::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 50px;
  pointer-events: none;
  z-index: 1;
}

.tech-carousel-container::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 30px;
  background: linear-gradient(to right, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
  z-index: 2;
  pointer-events: none;
}

.tech-carousel-container::after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 30px;
  background: linear-gradient(to left, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
  z-index: 2;
  pointer-events: none;
}
</style>