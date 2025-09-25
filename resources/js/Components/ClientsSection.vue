<template>
  <section id="clients" class="py-16 bg-gray-50 dark:bg-gray-900 dark:text-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative mb-10">
        <div class="text-center">
          <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-3">Nuestros Clientes</h2>
          <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Aliados estratégicos que confían en nuestra visión tecnológica para convertir sus desafíos en soluciones digitales</p>
        </div>

        <!-- Navigation arrows: absolute to the top-right so title stays perfectly centered -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
          <div class="hidden md:flex items-center space-x-2">
            <button @click="prev" class="w-10 h-10 rounded-full bg-white dark:bg-gray-700 shadow flex items-center justify-center text-gray-700 dark:text-gray-100 hover:bg-indigo-50 dark:hover:bg-indigo-900">‹</button>
            <button @click="next" class="w-10 h-10 rounded-full bg-white dark:bg-gray-700 shadow flex items-center justify-center text-gray-700 dark:text-gray-100 hover:bg-indigo-50 dark:hover:bg-indigo-900">›</button>
          </div>
        </div>
      </div>

      <!-- Desktop: 3-up carousel showing full cards -->
      <div class="hidden md:block overflow-hidden relative" @mouseenter="pauseAutoplay" @mouseleave="resumeAutoplay">
        <div ref="desktopTrackRef" class="flex transition-all duration-400 flex-nowrap" :style="desktopTrackStyle" @transitionend="onDesktopTransitionEnd">
          <div v-for="(c, i) in extendedClients" :key="'ec-' + i" class="flex-shrink-0 px-3" style="flex-basis: calc(100% / 3);">
            <div class="card-client bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 h-full flex flex-col hover:shadow-lg hover:-translate-y-1 transition-transform duration-200">
              <div class="flex-shrink-0 flex items-center justify-center mb-4">
                <img :src="resolveImage(c?.logo)" :alt="c?.name" class="h-28 object-contain" />
              </div>
              <div class="flex-1">
                <div class="flex items-start justify-between">
                  <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ c?.name || c?.company }}</h3>
              <p v-if="c?.industry" class="text-sm text-indigo-600 dark:text-indigo-400">{{ c.industry }}</p>
                  </div>
                  <span :class="[ 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', c?.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ]">
                    {{ c?.active ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>

                <p v-if="c?.description" class="text-sm text-gray-600 dark:text-gray-300 mt-3 line-clamp-3">{{ c.description }}</p>

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

                <div v-if="c?.testimonial" class="mt-4 bg-gray-50 dark:bg-gray-700 p-3 rounded">
                  <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ c.testimonial }}"</p>
                  <p v-if="c?.testimonial_author" class="text-sm font-medium text-gray-800 dark:text-gray-100 mt-2">- {{ c.testimonial_author }}</p>
                  <p v-if="c?.testimonial_position" class="text-xs text-gray-500 dark:text-gray-400">{{ c.testimonial_position }}</p>
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
          ref="mobileContainerRef"
          class="touch-pan-x overflow-hidden"
        >
          <div ref="mobileTrackRef" class="flex transition-transform duration-400" :style="mobileTrackStyle">
            <div v-for="(c, i) in clients" :key="c.id" class="min-w-full px-4">
              <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex flex-col lg:flex-row">
                  <div class="flex-shrink-0 flex items-center justify-center mb-4 lg:mb-0 lg:mr-6">
                    <img :src="resolveImage(c.logo)" :alt="c.name" class="h-36 object-contain rounded" />
                  </div>
                  <div class="flex-1">
                    <div class="flex items-start justify-between">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ c.name || c.company }}</h3>
                        <p v-if="c.industry" class="text-sm text-indigo-600 dark:text-indigo-400">{{ c.industry }}</p>
                      </div>
                      <span :class="[ 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', c.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ]">{{ c.active ? 'Activo' : 'Inactivo' }}</span>
                    </div>

                    <p v-if="c.description" class="text-sm text-gray-600 dark:text-gray-300 mt-3 line-clamp-3">{{ c.description }}</p>

                    <div class="mt-3 flex items-center justify-between">
                      <div class="text-sm text-gray-500 dark:text-gray-300">
                        <a v-if="c.website" :href="c.website" target="_blank" class="hover:text-indigo-600 dark:hover:text-indigo-300">{{ formatWebsite(c.website) }}</a>
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

                    <div v-if="c.testimonial" class="mt-3 bg-gray-50 dark:bg-gray-700 p-3 rounded">
                      <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ c.testimonial }}"</p>
                      <p v-if="c.testimonial_author" class="text-sm font-medium text-gray-800 dark:text-gray-100 mt-2">- {{ c.testimonial_author }}</p>
                      <p v-if="c.testimonial_position" class="text-xs text-gray-500 dark:text-gray-400">{{ c.testimonial_position }}</p>
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
  const n = Math.max(clients.value.length, 1)
  // If we're on mobile, the index maps directly to mobileIndex
  if (isMobile.value) {
    mobileIndex.value = ((i % n) + n) % n
    return
  }
  // set index to point to the real item in the extended array (offset by head length = visible)
  index.value = i + visible
}

const currentRealIndex = computed(() => {
  const n = Math.max(clients.value.length, 1)
  if (isMobile.value) return ((mobileIndex.value % n) + n) % n
  // map extended index to real index
  const real = (index.value - visible) % n
  return ((real % n) + n) % n
})

function prev() {
  if (isMobile.value) {
    mobileIndex.value = (mobileIndex.value - 1 + Math.max(1, clients.value.length)) % Math.max(1, clients.value.length)
    return
  }
  index.value = index.value - 1
}

function next() {
  if (isMobile.value) {
    mobileIndex.value = (mobileIndex.value + 1) % Math.max(1, clients.value.length)
    return
  }
  index.value = index.value + 1
}

// detect mobile breakpoint (Tailwind md = 768px)
const isMobile = ref(false)
let _mq = null


// Mobile swipe
const touchStartX = ref(0)
const touchDeltaX = ref(0)
const mobileIndex = ref(0)
const mobileContainerRef = ref(null)
const mobileTrackRef = ref(null)
let _documentTouchHandler = null

// mobile touch detection state
const mobileIsDragging = ref(false)
const mobileStartY = ref(0)
const mobileHorizontalDetected = ref(false)

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

function mobileTouchStart(e) {
  if (!e.touches || e.touches.length > 1) return
  mobileIsDragging.value = true
  mobileHorizontalDetected.value = false
  touchStartX.value = e.touches[0].clientX
  mobileStartY.value = e.touches[0].clientY
  touchDeltaX.value = 0
  // attach a document-level touchmove with passive:false as a fallback so we always get move events
  try {
    _documentTouchHandler = function (ev) { mobileTouchMove(ev) }
    document.addEventListener('touchmove', _documentTouchHandler, { passive: false })
  } catch (err) {
    try { document.addEventListener('touchmove', _documentTouchHandler) } catch (e) {}
  }
}

function mobileTouchMove(e) {
  if (!mobileIsDragging.value || !e.touches || e.touches.length > 1) return
  const dx = e.touches[0].clientX - touchStartX.value
  const dy = e.touches[0].clientY - mobileStartY.value

  if (!mobileHorizontalDetected.value) {
    // determine intent: horizontal swipe vs vertical scroll
    if (Math.abs(dx) > 8 && Math.abs(dx) > Math.abs(dy)) {
      mobileHorizontalDetected.value = true
    } else if (Math.abs(dy) > 12 && Math.abs(dy) > Math.abs(dx)) {
      // vertical scroll - do not preventDefault, allow page to scroll
      mobileIsDragging.value = false
      return
    } else {
      return
    }
  }

  // if horizontal detected, prevent vertical scroll and track dx
  e.preventDefault()
  touchDeltaX.value = dx
  // apply visual translate while dragging
  try {
    const container = mobileContainerRef.value
    const track = mobileTrackRef.value
    if (container && track) {
      const w = container.clientWidth || 1
      const basePercent = -mobileIndex.value * 100
      const deltaPercent = (dx / w) * 100
      track.style.transition = 'none'
      track.style.transform = `translateX(${basePercent + deltaPercent}%)`
    }
  } catch (err) {}
}

function mobileTouchEnd() {
  if (!mobileIsDragging.value && !mobileHorizontalDetected.value) return
  mobileIsDragging.value = false
  const dx = touchDeltaX.value
  if (dx > 50) {
    mobileIndex.value = (mobileIndex.value - 1 + clients.value.length) % Math.max(1, clients.value.length)
  } else if (dx < -50) {
    mobileIndex.value = (mobileIndex.value + 1) % Math.max(1, clients.value.length)
  }
  // animate to final index
  try {
    const track = mobileTrackRef.value
    if (track) {
      track.style.transition = 'transform 320ms cubic-bezier(.2,.8,.2,1)'
      track.style.transform = `translateX(${-mobileIndex.value * 100}%)`
      // after animation, clear inline styles so computed style is authoritative
      setTimeout(() => {
        if (track) {
          track.style.transition = ''
          track.style.transform = ''
        }
      }, 360)
    }
  } catch (err) {}
  touchDeltaX.value = 0
  mobileHorizontalDetected.value = false
  // remove document-level fallback
  try {
    if (_documentTouchHandler) {
      document.removeEventListener('touchmove', _documentTouchHandler, { passive: false })
      document.removeEventListener('touchmove', _documentTouchHandler)
      _documentTouchHandler = null
    }
  } catch (err) {
    _documentTouchHandler = null
  }
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
  // setup media query to detect mobile (below md = 768px)
  try {
    _mq = window.matchMedia('(max-width: 767px)')
    const mqHandler = (ev) => {
      const prev = isMobile.value
      isMobile.value = ev.matches
      // sync indices when switching modes so position stays consistent
      try {
        const n = Math.max(clients.value.length, 1)
        if (prev !== isMobile.value) {
          if (isMobile.value) {
            // moved to mobile: map extended index to mobile index
            mobileIndex.value = ((index.value - visible) % n + n) % n
          } else {
            // moved to desktop: map mobile index into extended index space
            index.value = mobileIndex.value + visible
            // ensure transitions are enabled
            isTransitioning.value = true
          }
        }
      } catch (err) {}
    }
    // initialize
    isMobile.value = _mq.matches
    // listen
    if (_mq.addEventListener) _mq.addEventListener('change', mqHandler)
    else _mq.addListener(mqHandler)
    // store for cleanup
    _mq._handler = mqHandler
  } catch (err) {
    isMobile.value = false
  }
  // attach native touch listeners for mobile container to allow proper vertical scroll handling
  nextTick(() => {
    const c = mobileContainerRef?.value
    if (c && c.addEventListener) {
      try {
        c.addEventListener('touchstart', mobileTouchStart, { passive: true })
        c.addEventListener('touchmove', mobileTouchMove, { passive: false })
        c.addEventListener('touchend', mobileTouchEnd, { passive: true })
        c.addEventListener('touchcancel', mobileTouchEnd, { passive: true })
      } catch (err) {
        c.addEventListener('touchstart', mobileTouchStart)
        c.addEventListener('touchmove', mobileTouchMove)
        c.addEventListener('touchend', mobileTouchEnd)
        c.addEventListener('touchcancel', mobileTouchEnd)
      }
    }
  })
})

onUnmounted(() => {
  stopAutoplay()
  const c = mobileContainerRef?.value
  if (c && c.removeEventListener) {
    try {
      c.removeEventListener('touchstart', mobileTouchStart, { passive: true })
      c.removeEventListener('touchmove', mobileTouchMove, { passive: false })
      c.removeEventListener('touchend', mobileTouchEnd, { passive: true })
      c.removeEventListener('touchcancel', mobileTouchEnd, { passive: true })
    } catch (err) {
      c.removeEventListener('touchstart', mobileTouchStart)
      c.removeEventListener('touchmove', mobileTouchMove)
      c.removeEventListener('touchend', mobileTouchEnd)
      c.removeEventListener('touchcancel', mobileTouchEnd)
    }
  }
  // cleanup media query
  try {
    if (_mq) {
      if (_mq.removeEventListener) _mq.removeEventListener('change', _mq._handler)
      else _mq.removeListener(_mq._handler)
      _mq = null
    }
  } catch (err) {}
})
</script>

<style scoped>
/* small helpers */
.touch-pan-x { -webkit-overflow-scrolling: touch; touch-action: pan-y; -ms-touch-action: pan-y; }

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
