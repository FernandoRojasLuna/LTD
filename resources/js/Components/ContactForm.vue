<template>
  <section id="contact" class="py-16 bg-cover bg-center dark:text-gray-100" :style="{ backgroundImage: 'url(/storage/fondo3.jpg)' }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="bg-white/95 dark:bg-white/10 backdrop-blur rounded-lg shadow-lg p-8 lg:p-12">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
          <div class="pr-6">
            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">¿En qué podemos colaborar?</h2>
            <p class="text-gray-600 dark:text-gray-100 mb-6">Somos especialistas en Desarrollo de Software, Consultoría Digital, Automatización de Procesos y mucho más. Cuéntanos tu reto y te propondremos una solución técnica, estratégica y medible.</p>

            <div class="space-y-3 text-sm text-gray-700 dark:text-gray-100">
              <div><strong>Servicios principales:</strong></div>
              <ul class="list-disc pl-5">
                <li>Desarrollo de plataformas a medida</li>
                <li>Transformación y consultoría digital</li>
                <li>Investigación y prototipado</li>
              </ul>
            </div>

            <div class="mt-6 text-sm text-gray-600 dark:text-gray-100 space-y-2">
              <div><strong>Dirección:</strong> <span class="dark:text-gray-100">{{ contact.address || '—' }}</span></div>
              <div v-if="(contact.phones || []).length"><strong>Teléfono:</strong> <span v-for="(p,i) in contact.phones" :key="i"><span class="dark:text-gray-100">{{ p }}</span><span v-if="i+1 < contact.phones.length">, </span></span></div>
              <div v-if="(contact.emails || []).length"><strong>Email:</strong> <span v-for="(e,i) in contact.emails" :key="i"><span class="dark:text-gray-100">{{ e }}</span><span v-if="i+1 < contact.emails.length">, </span></span></div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
              <!-- Instagram -->
              <a :href="findSocialUrl('instagram')" target="_blank" rel="noopener" :aria-label="'Instagram'" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow-md text-gray-800 dark:bg-white dark:text-gray-800 ring-2 ring-gray-200 dark:ring-gray-700 hover:bg-indigo-600 hover:text-white transition transform hover:-translate-y-0.5">
                <i class="fa-brands fa-instagram"></i>
              </a>

              <!-- Facebook -->
              <a :href="findSocialUrl('facebook')" target="_blank" rel="noopener" :aria-label="'Facebook'" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow-md text-gray-800 dark:bg-white dark:text-gray-800 ring-2 ring-gray-200 dark:ring-gray-700 hover:bg-indigo-600 hover:text-white transition transform hover:-translate-y-0.5">
                <i class="fa-brands fa-facebook-f"></i>
              </a>

              <!-- TikTok -->
              <a :href="findSocialUrl('tiktok')" target="_blank" rel="noopener" :aria-label="'TikTok'" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow-md text-gray-800 dark:bg-white dark:text-gray-800 ring-2 ring-gray-200 dark:ring-gray-700 hover:bg-indigo-600 hover:text-white transition transform hover:-translate-y-0.5">
                <i class="fa-brands fa-tiktok"></i>
              </a>
            </div>
          </div>

            <form @submit.prevent="submit" class="relative rounded overflow-hidden" novalidate>
            <!-- background image (fills the right panel) -->
            <div class="absolute inset-0 bg-cover bg-center" :style="{ backgroundImage: 'url(/storage/fondo3.jpg)' }"></div>
            <!-- dark overlay for contrast (reduced opacity) - keep subtle in both themes -->
            <div class="absolute inset-0 bg-black/10"></div>

            <!-- form content sits above the background (lighter white overlay) -->
            <div class="relative p-6 md:p-8 space-y-4 bg-white/75 dark:bg-white/8 backdrop-blur-sm">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="sr-only">Nombre</label>
                  <input v-model="form.name" placeholder="Nombre" aria-label="Nombre" class="w-full border border-gray-300 dark:border-gray-400 bg-white dark:bg-white/6 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-3 transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                </div>
                <div>
                  <label class="sr-only">Email</label>
                  <input v-model="form.email" type="email" placeholder="Email" aria-label="Email" class="w-full border border-gray-300 dark:border-gray-400 bg-white dark:bg-white/6 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-3 transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                </div>
                <div>
                  <label class="sr-only">Empresa</label>
                  <input v-model="form.company" placeholder="Empresa (opcional)" class="w-full border border-gray-300 dark:border-gray-400 bg-white dark:bg-white/6 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-3 transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="sr-only">Teléfono</label>
                  <input v-model="form.phone" placeholder="Teléfono (opcional)" class="w-full border border-gray-300 dark:border-gray-400 bg-white dark:bg-white/6 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-3 transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
              </div>

              <div>
                <label class="sr-only">Tipo de servicio</label>
                <div class="relative">
                  <select v-model="form.service" class="w-full appearance-none border border-gray-300 bg-white text-gray-500 rounded-lg px-4 py-3 pr-10 transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option disabled value="">Selecciona un servicio</option>
                  <option>Desarrollo de aplicaciones web y móviles</option>
                  <option>Soluciones de inteligencia artificial y machine learning</option>
                  <option>Automatización de procesos empresariales</option>
                  <option>Consultoría en transformación digital</option>
                  <option>Investigación y desarrollo tecnológico</option>
                  <option>Otro</option>
                  </select>
                  <!-- custom arrow -->
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 12a1 1 0 01-.707-.293l-3-3a1 1 0 111.414-1.414L10 9.586l2.293-2.293a1 1 0 011.414 1.414l-3 3A1 1 0 0110 12z" clip-rule="evenodd"/></svg>
                  </div>
                </div>
              </div>

              <div>
                <label class="sr-only">Mensaje</label>
                <textarea v-model="form.message" placeholder="Escribe aquí tu petición" rows="5" class="w-full border border-gray-300 dark:border-gray-400 bg-white dark:bg-white/6 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-3 transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
              </div>

              <!-- honeypot -->
              <input v-model="form.website" type="text" class="hidden" autocomplete="off" tabindex="-1" />

              <div class="flex items-center space-x-3">
                <input id="privacy" type="checkbox" v-model="form.privacy" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-400" required />
                <label for="privacy" class="text-sm"><span class="text-gray-500 dark:text-gray-500">He leído y acepto la</span> <a href="/privacy" class="text-indigo-600 dark:text-indigo-400 underline">política de privacidad</a></label>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="text-sm text-green-600" v-if="success">{{ success }}</div>
                  <div class="text-sm text-red-600" v-if="error">{{ error }}</div>
                </div>
                <button :disabled="loading" type="submit" class="ml-4 inline-flex items-center gap-3 px-6 py-3 bg-indigo-600 text-white font-semibold rounded shadow">
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                  </svg>
                  {{ loading ? 'Enviando...' : 'ENVIAR' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import useContactSettings from '@/composables/useContactSettings'

const { get } = useContactSettings()

const contact = reactive({ address: '', phones: [], emails: [], socials: [] })
const form = reactive({ name: '', email: '', company: '', phone: '', service: '', message: '', privacy: false, website: '' }) // website = honeypot
const loading = ref(false)
const success = ref('')
const error = ref('')

onMounted(async () => {
  try {
    const data = await get()
    contact.address = data.address || ''
    contact.phones = data.phones || []
    contact.emails = data.emails || []
    contact.socials = data.socials || []
  } catch (e) {
    // ignore
  }
})

function socialIcon(name) {
  const n = (name || '').toLowerCase()
  if (n.includes('instagram') || n.includes('ig')) return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3z"/></svg>'
  if (n.includes('facebook') || n.includes('fb')) return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3h2V1h-2c-2.757 0-5 2.243-5 5v2H6v3h2v8h3v-8h2.5l.5-3H11V6c0-.552.448-1 1-1z"/></svg>'
  if (n.includes('linkedin') || n.includes('ln')) return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM0 8h5v14H0V8zm7 0h4.7v2h.1c.7-1.3 2.4-2.7 5-2.7 5.3 0 6.3 3.5 6.3 8V22h-5v-7.5c0-1.8 0-4.1-2.5-4.1-2.5 0-2.9 2-2.9 4v7.6H7V8z"/></svg>'
  return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>'
}

function findSocialUrl(name) {
  const key = (name || '').toLowerCase()
  const list = contact.socials || []
  // soporta social como string (url) o como objeto { name, url }
  for (const item of list) {
    if (!item) continue
    if (typeof item === 'string') {
      if (item.toLowerCase().includes(key) || item.toLowerCase().includes(name)) return item
      // if string looks like a URL, return it as fallback
      if (item.startsWith('http')) return item
      continue
    }
    const nm = (item.name || '').toLowerCase()
    if (nm.includes(key) || nm.includes(name)) return item.url || '#'
    // if url contains key, accept it
    if ((item.url || '').toLowerCase().includes(key)) return item.url
  }

  // debug helper: expose socials content to console if nothing found (remove in production)
  // console.debug('findSocialUrl: no match for', name, 'in', list)
  return '#'
}

async function submit() {
  if (form.website) return // honeypot filled -> bot
  if (!form.name || !form.email || !form.message || !form.privacy) {
    error.value = 'Completa los campos requeridos.'
    setTimeout(() => error.value = '', 4000)
    return
  }

  loading.value = true
  success.value = ''
  error.value = ''
  try {
    const payload = { name: form.name, email: form.email, company: form.company, phone: form.phone, service: form.service, message: form.message }
    // include CSRF token and credentials so Laravel recognizes the session
    const tokenMeta = document.querySelector('meta[name="csrf-token"]')
    const csrf = tokenMeta ? tokenMeta.getAttribute('content') : ''
    const res = await fetch('/contact', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
      credentials: 'include',
      body: JSON.stringify(payload)
    })
    if (!res.ok) {
      if (res.status === 419) throw new Error('Token CSRF inválido o sesión expirada (419). Recarga la página.')
      const body = await res.json().catch(() => ({}))
      throw new Error(body.message || 'Error')
    }
    success.value = 'Mensaje enviado. Nos pondremos en contacto pronto.'
    Object.assign(form, { name: '', email: '', company: '', phone: '', service: '', message: '', privacy: false, website: '' })
  } catch (e) {
    error.value = typeof e === 'string' ? e : (e.message || 'No se pudo enviar el mensaje.')
  } finally {
    loading.value = false
    setTimeout(() => { success.value = ''; error.value = '' }, 6000)
  }
}
</script>

<style scoped>
.bg-cover { background-size: cover }
</style>
