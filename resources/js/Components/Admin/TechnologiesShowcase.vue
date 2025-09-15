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
        <!-- Carrusel: contenedor con track que será transformado. Duplicamos items para bucle continuo -->
        <div ref="viewport" class="relative w-full">
          <div ref="track" class="flex items-stretch will-change-transform" :style="trackStyle">
            <article v-for="(t, idx) in displayItems" :key="`tech-${t.id}-${idx}`" :style="{ width: itemWidthPct + '%' }" class="flex-shrink-0 p-4 bg-white rounded-lg shadow-sm flex flex-col items-center">
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
const position = ref(0) // translateX in %
const animating = ref(false)
const speedMs = 400 // ms per shift
const pauseMs = 1200 // pause between shifts
let timer = null

const isImageUrl = (url) => url && (url.startsWith('http') || url.startsWith('/storage/') || url.startsWith('data:'))
const initials = (name) => name ? name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2) : ''

// Build displayItems by duplicating array to allow seamless loop
const displayItems = computed(() => {
  const base = technologies.value || []
  if (base.length === 0) return []
  // Ensure enough items to slide; repeat until length > VISIBLE + base.length
  const minReps = Math.ceil((VISIBLE + base.length) / base.length)
  const dup = Array.from({ length: minReps }).flatMap(() => base.map((b) => ({ ...b })))
  return dup
})

const trackStyle = computed(() => {
  return {
    transition: animating.value ? `transform ${speedMs}ms ease` : 'none',
    transform: `translateX(${position.value}%)`
  }
})

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
    startAutoplay()
  } catch (e) {
    console.error('load technologies', e)
  } finally {
    loading.value = false
  }
}

const shiftOnce = async () => {
  if (animating.value || displayItems.value.length === 0) return
  animating.value = true
  // move left by one item width percentage
  position.value = position.value - itemWidthPct
  // wait for animation end
  await new Promise(r => setTimeout(r, speedMs))
  const totalBase = technologies.value.length
  if (totalBase > 0) {
    const movedItems = Math.round(Math.abs(position.value) / itemWidthPct)
    if (movedItems >= totalBase) {
      // reset position forward by totalBase * itemWidthPct to avoid accumulated drift
      // temporarily disable transition
      animating.value = false
      position.value = position.value + (totalBase * itemWidthPct)
      await nextTick()
    }
  }
  animating.value = false
}

const startAutoplay = () => {
  stopAutoplay()
  // use interval to shift one at a time
  timer = setInterval(() => {
    shiftOnce()
  }, pauseMs + speedMs)
}

const stopAutoplay = () => {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

onMounted(() => load())
onBeforeUnmount(() => stopAutoplay())
</script>

<style scoped>
.w-16-custom { width: 4rem; height: 4rem }
</style>
