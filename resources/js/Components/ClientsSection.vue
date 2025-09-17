<template>
  <section id="clients" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative mb-10">
        <div class="text-center">
          <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">Nuestros Clientes</h2>
          <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">Aliados estratégicos que confían en nuestra visión tecnológica para convertir sus desafíos en soluciones digitales</p>
        </div>

        <!-- Navigation arrows: absolute to the top-right so title stays perfectly centered -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
          <div class="hidden md:flex items-center space-x-2">
            <button @click="prev" class="w-10 h-10 rounded-full bg-white shadow flex items-center justify-center text-gray-700 hover:bg-indigo-50">‹</button>
            <button @click="next" class="w-10 h-10 rounded-full bg-white shadow flex items-center justify-center text-gray-700 hover:bg-indigo-50">›</button>
          </div>
        </div>
      </div>

      <!-- Desktop: 3-up carousel showing full cards -->
      <div class="hidden md:block overflow-hidden relative" @mouseenter="pauseAutoplay" @mouseleave="resumeAutoplay">
        <div ref="desktopTrackRef" class="flex transition-all duration-400 flex-nowrap" :style="desktopTrackStyle" @transitionend="onDesktopTransitionEnd">
          <div v-for="(c, i) in extendedClients" :key="'ec-' + i" class="flex-shrink-0 px-3" style="flex-basis: calc(100% / 3);">
            <div class="card-client bg-white rounded-xl shadow-md p-6 h-full flex flex-col hover:shadow-lg hover:-translate-y-1 transition-transform duration-200">
              <div class="flex-shrink-0 flex items-center justify-center mb-4">
                <img :src="resolveImage(c?.logo)" :alt="c?.name" class="h-28 object-contain" />
              </div>
              <div class="flex-1">
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ c?.name || c?.company }}</h3>
                    <p v-if="c?.industry" class="text-sm text-indigo-600">{{ c.industry }}</p>
                  </div>
                  <span :class="[ 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', c?.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ]">
                    {{ c?.active ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>

                <p v-if="c?.description" class="text-sm text-gray-600 mt-3 line-clamp-3">{{ c.description }}</p>

                <div class="mt-4 flex items-center justify-between">
                  <div class="text-sm text-gray-500">
                    <a v-if="c?.website" :href="c.website" target="_blank" class="hover:text-indigo-600">{{ formatWebsite(c.website) }}</a>
                  </div>
                  <div class="flex items-center space-x-1">
                    <template v-for="n in 5" :key="n">
                      <svg v-if="n <= Math.round(c?.rating || 0)" class="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.067 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69L9.05 2.927z" />
                      </svg>
                      <svg v-else class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.067 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69L9.05 2.927z" />
                      </svg>
                    </template>
                  </div>
                </div>

                <div v-if="c?.testimonial" class="mt-4 bg-gray-50 p-3 rounded">
                  <p class="text-sm text-gray-600 italic">"{{ c.testimonial }}"</p>
                  <p v-if="c?.testimonial_author" class="text-sm font-medium text-gray-800 mt-2">- {{ c.testimonial_author }}</p>
                  <p v-if="c?.testimonial_position" class="text-xs text-gray-500">{{ c.testimonial_position }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Indicators -->
        <div class="absolute left-0 right-0 bottom-4 flex justify-center space-x-2">
                <button
                  v-for="(dot, di) in Math.max(clients.length, 1)" :key="di"
                  @click="setIndex(di)"
                  :class="['w-2 h-2 rounded-full', (currentRealIndex === di) ? 'bg-indigo-600' : 'bg-gray-300']"
                  class="focus:outline-none"
                ></button>
              </div>
      </div>

      <!-- Mobile: single card view -->
      <div class="md:hidden">
        <div
          class="touch-pan-x overflow-hidden"
          @touchstart.passive="onTouchStart"
          @touchmove.passive="onTouchMove"
          @touchend.passive="onTouchEnd"
        >
          <div class="flex transition-transform duration-400" :style="mobileTrackStyle">
            <div v-for="(c, i) in clients" :key="c.id" class="min-w-full px-4">
              <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col lg:flex-row">
                  <div class="flex-shrink-0 flex items-center justify-center mb-4 lg:mb-0 lg:mr-6">
                    <img :src="resolveImage(c.logo)" :alt="c.name" class="h-36 object-contain rounded" />
                  </div>
                  <div class="flex-1">
                    <div class="flex items-start justify-between">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ c.name || c.company }}</h3>
                        <p v-if="c.industry" class="text-sm text-indigo-600">{{ c.industry }}</p>
                      </div>
                      <span :class="[ 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', c.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ]">{{ c.active ? 'Activo' : 'Inactivo' }}</span>
                    </div>

                    <p v-if="c.description" class="text-sm text-gray-600 mt-3 line-clamp-3">{{ c.description }}</p>

                    <div class="mt-3 flex items-center justify-between">
                      <div class="text-sm text-gray-500">
                        <a v-if="c.website" :href="c.website" target="_blank" class="hover:text-indigo-600">{{ formatWebsite(c.website) }}</a>
                      </div>
                      <div class="flex items-center space-x-1">
                        <template v-for="n in 5" :key="n">
                          <svg v-if="n <= Math.round(c.rating || 0)" class="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.067 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69L9.05 2.927z" />
                          </svg>
                          <svg v-else class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.067 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69L9.05 2.927z" />
                          </svg>
                        </template>
                      </div>
                    </div>

                    <div v-if="c.testimonial" class="mt-3 bg-gray-50 p-3 rounded">
                      <p class="text-sm text-gray-600 italic">"{{ c.testimonial }}"</p>
                      <p v-if="c.testimonial_author" class="text-sm font-medium text-gray-800 mt-2">- {{ c.testimonial_author }}</p>
                      <p v-if="c.testimonial_position" class="text-xs text-gray-500">{{ c.testimonial_position }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useClients } from '@/composables/useClients'

const { clients, fetchClients } = useClients()

// Desktop carousel state (3 visible)
const visible = 3
const index = ref(visible) // start at offset because we'll use cloned items
const desktopTrackRef = ref(null)

// Extended clients with clones at start and end to allow infinite loop
const extendedClients = computed(() => {
  const n = clients.value.length
  if (n === 0) return []
  const head = clients.value.slice(Math.max(0, n - visible))
  const tail = clients.value.slice(0, Math.min(visible, n))
  return [...head, ...clients.value, ...tail]
})

const isTransitioning = ref(true)
const desktopTrackStyle = computed(() => {
  const n = Math.max(clients.value.length, 1)
  const shift = (index.value) * -100 / visible
  return {
    transform: `translateX(${shift}%)`,
    transition: isTransitioning.value ? 'transform 480ms cubic-bezier(.2,.9,.2,1)' : 'none'
  }
})

// Autoplay
let autoplayTimer = null
const autoplayDelay = 3500

function startAutoplay() {
  stopAutoplay()
  autoplayTimer = setInterval(() => {
    next()
  }, autoplayDelay)
}

function stopAutoplay() {
  if (autoplayTimer) {
    clearInterval(autoplayTimer)
    autoplayTimer = null
  }
}

function pauseAutoplay() { stopAutoplay() }
function resumeAutoplay() { startAutoplay() }

function setIndex(i) {
  const n = clients.value.length || 1
  // set index to point to the real item in the extended array (offset by head length = visible)
  index.value = i + visible
}

const currentRealIndex = computed(() => {
  const n = clients.value.length || 1
  // map extended index to real index
  const real = (index.value - visible) % n
  return ((real % n) + n) % n
})

function prev() {
  index.value = index.value - 1
}

function next() {
  index.value = index.value + 1
}

// Mobile swipe
const touchStartX = ref(0)
const touchDeltaX = ref(0)
const mobileIndex = ref(0)

function onTouchStart(e) {
  touchStartX.value = e.touches[0].clientX
}

function onTouchMove(e) {
  touchDeltaX.value = e.touches[0].clientX - touchStartX.value
}

function onTouchEnd() {
  if (touchDeltaX.value > 50) {
    mobileIndex.value = (mobileIndex.value - 1 + clients.value.length) % Math.max(1, clients.value.length)
  } else if (touchDeltaX.value < -50) {
    mobileIndex.value = (mobileIndex.value + 1) % Math.max(1, clients.value.length)
  }
  touchDeltaX.value = 0
}

const mobileTrackStyle = computed(() => ({
  transform: `translateX(calc(${-mobileIndex.value * 100}%))`,
}))

import { nextTick } from 'vue'

// Infinite loop handling
function onDesktopTransitionEnd() {
  const n = clients.value.length
  if (n === 0) return
  // if moved past the real items at end
  if (index.value >= n + visible) {
    // jump back to equivalent real position without transition
    isTransitioning.value = false
    index.value = index.value - n
    // force reflow so the browser applies the transform without transition
    nextTick(() => {
      if (desktopTrackRef.value && desktopTrackRef.value.offsetHeight) {
        // read layout
      }
      // re-enable transition in next frame
      requestAnimationFrame(() => { isTransitioning.value = true })
    })
  }
  // if moved before the real items at start
  if (index.value < visible) {
    isTransitioning.value = false
    index.value = index.value + n
    nextTick(() => {
      if (desktopTrackRef.value && desktopTrackRef.value.offsetHeight) {
        // read layout
      }
      requestAnimationFrame(() => { isTransitioning.value = true })
    })
  }
}

function formatWebsite(url) {
  if (!url) return ''
  return url.replace(/^https?:\/\//, '').replace(/\/$/, '')
}

function resolveImage(url) {
  if (!url) return '/images/placeholder-client.png'
  if (url.startsWith('data:')) return url
  if (url.startsWith('http') || url.startsWith('//')) return url
  // If already contains /storage/ normalize and return
  if (url.startsWith('/storage/') || url.startsWith('storage/')) return url.replace(/^\/+/, '/')
  return `/storage/${url}`
}

onMounted(() => {
  fetchClients()
  startAutoplay()
})

onUnmounted(() => {
  stopAutoplay()
})
</script>

<style scoped>
/* small helpers */
.touch-pan-x { -webkit-overflow-scrolling: touch; }

/* Card styles */
.card-client {
  border: 1px solid rgba(15,23,42,0.04);
  backdrop-filter: blur(6px);
}

/* Carousel controls */
.carousel-control {
  width: 44px;
  height: 44px;
  border-radius: 9999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: white;
  box-shadow: 0 6px 14px rgba(2,6,23,0.06);
}

.fade-enter-active, .fade-leave-active {
  transition: all .35s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(6px);
}
</style>
