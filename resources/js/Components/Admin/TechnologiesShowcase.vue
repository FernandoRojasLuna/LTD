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

      <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <article v-for="(t, idx) in technologies" :key="`tech-${t.id}-${idx}`" class="flex flex-col items-center p-4 bg-white rounded-lg shadow-sm">
          <div class="w-16 h-16 flex items-center justify-center mb-3">
            <img v-if="isImageUrl(t.icon)" :src="t.icon" :alt="t.name" class="w-12 h-12 object-contain" />
            <i v-else-if="t.icon" :class="t.icon + ' text-2xl'" aria-hidden="true"></i>
            <div v-else class="text-gray-400 font-bold text-xl">{{ initials(t.name) }}</div>
          </div>
          <div class="text-sm font-semibold text-gray-700 text-center">{{ t.name }}</div>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useTechnologies } from '@/composables/useTechnologies'

const { getActiveTechnologies } = useTechnologies()
const technologies = ref([])
const loading = ref(false)

const isImageUrl = (url) => url && (url.startsWith('http') || url.startsWith('/storage/') || url.startsWith('data:'))
const initials = (name) => name ? name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2) : ''

const load = async () => {
  loading.value = true
  try {
    const data = await getActiveTechnologies()
    technologies.value = (data || []).map(t => {
      if (t.icon && !t.icon.startsWith('http') && !t.icon.startsWith('data:')) {
        t.icon = '/storage/' + t.icon.replace(/^\/+/, '')
      }
      return t
    })
  } catch (e) {
    console.error('load technologies', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => load())
</script>

<style scoped>
.carousel-item .w-16 { width: 4rem; height: 4rem }
</style>
