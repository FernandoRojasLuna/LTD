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
        <!-- Swiper continuous carousel: loop + freeMode + autoplay to emulate a seamless snake-like scroll -->
        <div class="relative w-full" @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
          <Swiper
            ref="swiperTech"
            :modules="[Autoplay, FreeMode]"
            :spaceBetween="12"
            :slidesPerView="slidesPerView"
            :loop="true"
            :freeMode="true"
            :freeModeMomentum="false"
            :autoplay="{ delay: 0, disableOnInteraction: false, pauseOnMouseEnter: false }"
            :speed="autoplaySpeed"
            class="tech-swiper w-full"
          >
            <SwiperSlide v-for="(t, idx) in technologies" :key="`tech-${t.id}-${idx}`" class="tech-slide flex items-center justify-center">
              <article class="p-3 bg-white rounded-lg shadow-sm flex flex-col items-center w-full max-w-xs">
                <div class="w-16 h-16 flex items-center justify-center mb-2">
                  <img v-if="isImageUrl(t.icon)" :src="t.icon" :alt="t.name" class="w-12 h-12 object-contain" />
                  <i v-else-if="t.icon" :class="t.icon + ' text-2xl'" aria-hidden="true"></i>
                  <div v-else class="text-gray-400 font-bold text-xl">{{ initials(t.name) }}</div>
                </div>
                <div class="text-sm font-semibold text-gray-700 text-center">{{ t.name }}</div>
              </article>
            </SwiperSlide>
          </Swiper>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { ref, onMounted, computed, nextTick, onBeforeUnmount } from 'vue'
// Swiper
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/free-mode'
import SwiperCore, { Autoplay, FreeMode } from 'swiper'
SwiperCore.use([Autoplay, FreeMode])
import { useTechnologies } from '@/composables/useTechnologies'

const { getActiveTechnologies } = useTechnologies()
const technologies = ref([])
const loading = ref(false)

const viewport = ref(null)
const track = ref(null)
const swiperTech = ref(null)

// missing reactive state used elsewhere
const position = ref(0)
const animating = ref(true)

// Carousel config (responsive)
const slidesPerView = ref(7)
const autoplaySpeed = ref(12000) // base speed in ms for a full loop-ish feel (swiper speed controls slide transition)

// compute slidesPerView responsively
const updateSlidesPerView = () => {
  const w = window.innerWidth
  if (w < 420) {
    slidesPerView.value = 2
    autoplaySpeed.value = 7000
  } else if (w < 640) {
    slidesPerView.value = 3
    autoplaySpeed.value = 9000
  } else if (w < 1024) {
    slidesPerView.value = 5
    autoplaySpeed.value = 11000
  } else {
    slidesPerView.value = 7
    autoplaySpeed.value = 14000
  }
}

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
    // reset position and ensure animation state is in sync
    position.value = 0
    await nextTick()
    // allow the CSS animation to run by default
    animating.value = true
    // if track element exists, ensure animationPlayState reflects current state
    if (track.value && track.value.style) track.value.style.animationPlayState = animating.value ? 'running' : 'paused'
  } catch (e) {
    console.error('load technologies', e)
  } finally {
    loading.value = false
  }
}

// continuous animation - minimal JS: allow pause on hover
const startAutoplay = () => {
  animating.value = true
  if (track.value && track.value.style) track.value.style.animationPlayState = 'running'
  // start swiper autoplay if available
  try { if (swiperTech.value && swiperTech.value.autoplay && !swiperTech.value.autoplay.running) swiperTech.value.autoplay.start() } catch (e) { /* ignore */ }
}
const stopAutoplay = () => {
  animating.value = false
  if (track.value && track.value.style) track.value.style.animationPlayState = 'paused'
  // stop swiper autoplay if available
  try { if (swiperTech.value && swiperTech.value.autoplay && swiperTech.value.autoplay.running) swiperTech.value.autoplay.stop() } catch (e) { /* ignore */ }
}

onMounted(async () => {
  await load()
  // ensure animation is running after mount
  startAutoplay()
  // responsive slides per view
  updateSlidesPerView()
  window.addEventListener('resize', updateSlidesPerView)
})
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

/* Swiper slide and responsive spacing */
.tech-slide { display: flex; align-items: center; justify-content: center; padding: 6px; }
.tech-slide article { width: 100%; }

@media (max-width: 640px) {
  .tech-slide { padding: 4px; }
  .tech-slide img { max-width: 48px; max-height: 48px; }
  .tech-slide .text-sm { font-size: 0.68rem; }
}

@media (min-width: 641px) and (max-width: 1024px) {
  .tech-slide img { max-width: 56px; max-height: 56px; }
}

/* Hide legacy track animation when using Swiper */
.tech-track { display: none; }
.tech-swiper { display: block; }
</style>
