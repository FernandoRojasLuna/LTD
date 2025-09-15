<template>
  <section class="mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Tecnologías que usamos</h3>
        <p class="text-sm text-gray-600">Herramientas y librerías que impulsan nuestros proyectos</p>
      </div>

      <div v-if="loading" class="flex justify-center py-8">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      </div>

      <div v-else-if="technologies.length === 0" class="text-center py-8 text-gray-500">
        No hay tecnologías registradas.
      </div>

      <div v-else class="overflow-hidden">
        <!-- Carrusel continuo tipo "culebra": duplicamos la secuencia y animamos el track con keyframes -->
        <div ref="viewport" class="relative w-full overflow-hidden">
          <div
            ref="track"
            class="tech-track flex items-stretch will-change-transform"
            :style="{ '--distance': animationDistancePct + '%', '--duration': animationDuration + 's' }"
          >
            <article v-for="(t, idx) in continuousDisplay" :key="`tech-${t.id}-${idx}`" :style="{ width: itemWidthPct + '%' }" class="flex-shrink-0 p-4 bg-white rounded-lg shadow-sm flex flex-col items-center">
              <div class="w-16 h-16 flex items-center justify-center mb-3">
                <img v-if="isImageUrl(t.icon)" :src="t.icon" :alt="t.name" class="w-12 h-12 object-contain" />
                <i v-else-if="t.icon" :class="t.icon + ' text-2xl'" aria-hidden="true"></i>
                <div v-else class="text-gray-400 font-bold text-xl">{{ initials(t.name) }}</div>
              </div>
              <div class="text-sm font-semibold text-gray-700 text-center">{{ t.name }}</div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { ref, onMounted, computed, nextTick, onBeforeUnmount } from 'vue'
import { useTechnologies } from '@/composables/useTechnologies'

const { getActiveTechnologies } = useTechnologies()
const technologies = ref([])
const loading = ref(false)

const viewport = ref(null)
const track = ref(null)

// Carousel config
const VISIBLE = 7 // always show 7 items
const itemWidthPct = 100 / VISIBLE

// animationDuration computed below (seconds)

const isImageUrl = (url) => url && (url.startsWith('http') || url.startsWith('/storage/') || url.startsWith('data:'))
const initials = (name) => name ? name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2) : ''

// Build continuousDisplay by repeating the base sequence multiple times so CSS animation never shows a gap
const continuousDisplay = computed(() => {
  const base = technologies.value || []
  if (base.length === 0) return []
  // Duplicate twice (base + base) to allow smooth loop
  return Array.from({ length: 2 }).flatMap(() => base.map(b => ({ ...b })))
})

// animationDistancePct: how much percent the track must translate to move one base sequence
const animationDistancePct = computed(() => {
  const baseCount = Math.max(technologies.value.length, 1)
  return baseCount * itemWidthPct
})

// animationDuration: seconds for a full base sequence translation (calm movement)
const animationDuration = computed(() => {
  const baseCount = Math.max(technologies.value.length, 1)
  return Math.max(10, Math.round((baseCount / VISIBLE) * 14))
})

// trackStyle replaced by CSS animation; keep placeholder for compatibility
const trackStyle = computed(() => ({ }))

const normalizeIcons = (arr) => arr.map(t => {
  if (t.icon && !t.icon.startsWith('http') && !t.icon.startsWith('data:')) {
    t.icon = '/storage/' + t.icon.replace(/^\/+/, '')
  }
  return t
})

const load = async () => {
  loading.value = true
  try {
    const data = await getActiveTechnologies()
    technologies.value = normalizeIcons(data || [])
    // reset position
    position.value = 0
    animating.value = false
    await nextTick()
  // continuous animation handled by CSS; no JS autoplay needed
  } catch (e) {
    console.error('load technologies', e)
  } finally {
    loading.value = false
  }
}

// continuous animation - minimal JS: allow pause on hover
const startAutoplay = () => { /* noop, CSS handles it */ }
const stopAutoplay = () => { /* noop */ }

onMounted(() => load())
onBeforeUnmount(() => stopAutoplay())

// expose for template if needed
// ...existing code...
</script>

<style scoped>
.w-16-custom { width: 4rem; height: 4rem }

/* Track animation: desplazamiento continuo hacia la izquierda. La duración se controla por la variable --duration (s). */
.tech-track {
  display: flex;
  align-items: stretch;
  gap: 0;
  /* animation will be applied to the track element */
  animation-name: tech-scroll;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  animation-duration: var(--duration, 12s);
}

@keyframes tech-scroll {
  from { transform: translateX(0%); }
  to { transform: translateX(calc(-1 * var(--distance, 50%))); }
}
</style>
