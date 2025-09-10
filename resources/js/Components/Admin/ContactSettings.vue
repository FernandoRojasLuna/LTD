<template>
  <div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Ajustes de Contacto</h2>

    <div>
      <div class="mb-6">
        <div class="bg-white p-4 rounded shadow-md flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.343 3-3 3S6 12.657 6 11s1.343-3 3-3 3 1.343 3 3z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11c0 4-3 7-7 7s-7-3-7-7"></path></svg>
          </div>
          <div class="flex-1">
            <div class="text-lg font-semibold text-gray-800">Modificar tus datos de contacto</div>
            <p class="text-sm text-gray-600 mt-1">Actualiza la dirección, teléfonos, correos y redes que aparecen en tu página de contacto. Pulsa el botón para desplegar el formulario.</p>
          </div>
          <div>
            <button @click="toggleSettings()" class="px-4 py-2 bg-indigo-600 text-white rounded shadow hover:brightness-95">Modificar contacto</button>
          </div>
        </div>
      </div>

      <div v-if="settingsOpen" class="bg-white p-6 rounded shadow transition-all">
        <div class="mb-4">
          <label class="block text-sm font-medium">Dirección</label>
          <input v-model="form.address" class="mt-1 block w-full border rounded p-2" />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">Teléfonos</label>
          <div v-for="(p, idx) in form.phones" :key="idx" class="flex gap-2 mt-2">
            <input v-model="form.phones[idx]" class="flex-1 border rounded p-2" />
            <button @click="removePhone(idx)" class="px-3 bg-red-500 text-white rounded">-</button>
          </div>
          <button @click="addPhone" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded">Añadir teléfono</button>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">Correos</label>
          <div v-for="(e, idx) in form.emails" :key="idx" class="flex gap-2 mt-2">
            <input v-model="form.emails[idx]" class="flex-1 border rounded p-2" />
            <button @click="removeEmail(idx)" class="px-3 bg-red-500 text-white rounded">-</button>
          </div>
          <button @click="addEmail" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded">Añadir email</button>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">Redes sociales</label>
          <div v-for="(s, idx) in form.socials" :key="idx" class="mt-2 border p-2 rounded">
            <div class="flex gap-2">
              <input v-model="form.socials[idx].name" placeholder="Nombre (ej. instagram)" class="flex-1 border rounded p-2" />
              <input v-model="form.socials[idx].url" placeholder="URL" class="flex-2 border rounded p-2" />
              <button @click="removeSocial(idx)" class="px-3 bg-red-500 text-white rounded">-</button>
            </div>
          </div>
          <button @click="addSocial" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded">Añadir red</button>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">WhatsApp</label>
          <input v-model="form.whatsapp" class="mt-1 block w-full border rounded p-2" />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">Embebido de mapa (iframe)</label>
          <textarea v-model="form.map_embed" class="mt-1 block w-full border rounded p-2"></textarea>
        </div>

        <div class="flex justify-end gap-2">
          <button @click="load" class="px-4 py-2 border rounded">Cargar</button>
          <button @click="save" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
        </div>
      </div>
    </div>

    <div v-if="success" class="mt-4 p-4 bg-green-50 border border-green-200 rounded">{{ success }}</div>
    <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded">{{ error }}</div>
  
  <!-- Solicitudes recibidas -->
  <div class="mt-10">
    <h3 class="text-xl font-semibold mb-3">Solicitudes recibidas</h3>
    <div class="bg-white p-4 rounded shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <input v-model="search" @input="debouncedFetch" placeholder="Buscar por nombre, email o mensaje" class="border rounded p-2 w-80" />
          <select v-model="filter" @change="fetchContacts()" class="border rounded p-2">
            <option value="all">Todos</option>
            <option value="unread">No leídos</option>
          </select>
          <div class="text-sm text-gray-600">No leídos: <span class="font-semibold">{{ unreadCount }}</span></div>
        </div>
        <div>No leído
          <button @click="exportCsv" class="px-3 py-1 border rounded">Exportar CSV</button>
        </div>
      </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-50">
          <th class="py-3 px-4 text-sm font-medium text-gray-600">#</th>
          <th class="py-3 px-4 text-sm font-medium text-gray-600">Nombre</th>
          <th class="py-3 px-4 text-sm font-medium text-gray-600">Email</th>
          <th class="py-3 px-4 text-sm font-medium text-gray-600">Fecha</th>
          <th class="py-3 px-4 text-sm font-medium text-gray-600">Acciones</th>
        </tr>
      </thead>
      <tbody class="bg-white">
        <tr v-for="c in contacts.data || []" :key="c.id" class="border-t hover:bg-gray-50">
          <td class="py-3 px-4 align-middle text-sm text-gray-700">{{ c.id }}</td>
          <td class="py-3 px-4 align-middle">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold shadow-sm">{{ (c.name || '?').charAt(0).toUpperCase() }}</div>
              <div>
                <div class="text-sm font-medium text-gray-800">{{ c.name }}</div>
                <div class="text-xs text-gray-500">{{ c.service || '' }}</div>
              </div>
            </div>
          </td>
          <td class="py-3 px-4 align-middle">
            <div class="text-sm text-gray-700">{{ c.email }}</div>
          </td>
          <td class="py-3 px-4 align-middle text-sm text-gray-600">{{ new Date(c.created_at).toLocaleString() }}</td>
          <td class="py-3 px-4 align-middle">
            <div class="flex items-center gap-2">
              <button @click.prevent="openDetail(c)" class="px-3 py-1 bg-white border border-gray-200 text-blue-600 rounded text-sm shadow-sm hover:shadow-md">Ver</button>
              <button @click.prevent.stop="markRead(c.id)" class="px-3 py-1 bg-green-500 text-white rounded text-sm shadow-sm hover:brightness-95">Marcar leído</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

      <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">Mostrando {{ contacts.from }} - {{ contacts.to }} de {{ contacts.total }}</div>
        <div class="space-x-2">
          <button @click="fetchContacts(contacts.prev_page_url)" :disabled="!contacts.prev_page_url" class="px-3 py-1 border rounded">Anterior</button>
          <button @click="fetchContacts(contacts.next_page_url)" :disabled="!contacts.next_page_url" class="px-3 py-1 border rounded">Siguiente</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Detail modal -->
  <div v-if="selectedContact" class="fixed inset-0 flex items-center justify-center bg-black/40 z-50" role="dialog" aria-modal="true">
    <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 md:w-3/5 max-h-[85vh] overflow-auto border">
      <!-- Header -->
      <div class="flex items-center justify-between border-b pb-4">
        <div>
          <h3 class="text-2xl font-semibold text-gray-800">Solicitud #{{ selectedContact.id }}</h3>
          <div class="text-sm text-gray-500">{{ new Date(selectedContact.created_at).toLocaleString() }}</div>
        </div>
        <!-- intentionally removed top-right buttons (No leído / Cerrar) for cleaner look -->
      </div>

      <!-- Body -->
      <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
        <div>
          <div class="text-xs text-gray-500 uppercase font-medium">Nombre</div>
          <div class="mt-2 text-lg font-medium text-gray-900">{{ selectedContact.name }}</div>

          <div class="mt-4 text-xs text-gray-500 uppercase font-medium">Empresa</div>
          <div class="mt-2 text-sm text-gray-800">{{ selectedContact.company || '-' }}</div>

          <div class="mt-4 text-xs text-gray-500 uppercase font-medium">Servicio</div>
          <div class="mt-2 text-sm text-gray-800">{{ selectedContact.service || '-' }}</div>
        </div>

        <div>
          <div class="text-xs text-gray-500 uppercase font-medium">Email</div>
          <div class="mt-2 text-sm text-gray-800">{{ selectedContact.email }}</div>

          <div class="mt-4 text-xs text-gray-500 uppercase font-medium">Teléfono</div>
          <div class="mt-2 text-sm text-gray-800">{{ selectedContact.phone || '-' }}</div>

          <div class="mt-4 text-xs text-gray-500 uppercase font-medium">IP</div>
          <div class="mt-2 text-sm text-gray-500">{{ selectedContact.ip || '-' }}</div>
        </div>
      </div>

      <div class="mt-6">
        <div class="text-xs text-gray-500 uppercase font-medium">Mensaje</div>
        <div class="mt-2 p-4 bg-gray-50 rounded text-sm text-gray-800 whitespace-pre-wrap border">{{ selectedContact.message }}</div>
      </div>

      <!-- Footer -->
      <div class="mt-6 flex justify-end items-center gap-3">
        <button @click="closeDetail()" class="px-4 py-2 border rounded bg-white text-gray-700 hover:shadow">Cerrar</button>
        <button @click.prevent="markReadAndClose(selectedContact.id)" class="px-4 py-2 bg-green-600 text-white rounded shadow hover:brightness-95">Marcar leído</button>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import useContactSettings from '@/composables/useContactSettings'

const { get, update } = useContactSettings()

const form = reactive({ address: '', phones: [], emails: [], socials: [], whatsapp: '', map_embed: '' })
const success = ref('')
const error = ref('')

const load = async () => {
  try {
    const data = await get()
    form.address = data.address || ''
    form.phones = data.phones || []
    form.emails = data.emails || []
    form.socials = data.socials || []
    form.whatsapp = data.whatsapp || ''
    form.map_embed = data.map_embed || ''
  } catch (e) {
    error.value = 'Error cargando datos'
  }
}

const save = async () => {
  try {
    await update({ ...form })
    success.value = 'Guardado'
    setTimeout(() => success.value = '', 3000)
  } catch (e) {
    error.value = 'Error guardando'
    setTimeout(() => error.value = '', 3000)
  }
}

const addPhone = () => form.phones.push('')
const removePhone = (i) => form.phones.splice(i,1)
const addEmail = () => form.emails.push('')
const removeEmail = (i) => form.emails.splice(i,1)
const addSocial = () => form.socials.push({ name: '', url: '' })
const removeSocial = (i) => form.socials.splice(i,1)

onMounted(() => load())

// Contacts admin list
import { onBeforeMount } from 'vue'
const contacts = ref({ data: [], total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null })
const search = ref('')
const filter = ref('all')
const unreadCount = ref(0)
const selectedContact = ref(null)
const settingsOpen = ref(false)
let settingsLoaded = false

function toggleSettings() {
  settingsOpen.value = !settingsOpen.value
  if (settingsOpen.value && !settingsLoaded) {
    load()
    settingsLoaded = true
  }
}

function debounce(fn, wait = 300) {
  let t
  return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait) }
}

const debouncedFetch = debounce(() => fetchContacts())

async function fetchContacts(url = null) {
  try {
    const base = '/admin/contacts/list'
    let final = url || base
    const params = new URLSearchParams()
    if (!url) {
      if (search.value) params.set('search', search.value)
      if (filter.value) params.set('filter', filter.value)
    }
    if (!url) final = base + '?' + params.toString()
    const res = await fetch(final)
    const data = await res.json()
    contacts.value = data
    unreadCount.value = data.unread_count || 0
  } catch (e) {
    // ignore
  }
}

async function markRead(id) {
  try {
  if (!id) return
  await fetch(`/admin/contacts/${id}/read`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' } })
  // refresh list and unread count
  await fetchContacts()
  } catch (e) {
    // ignore
  }
}

function openDetail(c) { selectedContact.value = c }

function closeDetail() { selectedContact.value = null }

async function markReadAndClose(id) {
  try {
    if (!id) return
    await markRead(id)
    selectedContact.value = null
  } catch (e) {
    // ignore
  }
}

function exportCsv() {
  const rows = (contacts.value.data || []).map(c => ({ id: c.id, name: c.name, email: c.email, service: c.service || '', message: c.message, created_at: c.created_at }))
  const csv = [Object.keys(rows[0] || {}).join(',')].concat(rows.map(r => Object.values(r).map(v => '"' + String(v || '').replace(/"/g,'""') + '"').join(','))).join('\n')
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url; a.download = 'contacts.csv'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url)
}

onBeforeMount(() => fetchContacts())
</script>
